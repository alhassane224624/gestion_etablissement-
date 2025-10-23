<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Stagiaire;
use App\Models\Echeancier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

// Notifications (doivent implémenter ShouldQueue)
use App\Notifications\PaiementRecuNotification;
use App\Notifications\PaiementValideNotification;
use App\Notifications\PaiementRefuseNotification;

class PaiementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste paginée des paiements + filtres
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Paiement::class);

        $q = Paiement::with(['stagiaire', 'echeanciers'])
            ->when($request->filled('search'), function ($qq) use ($request) {
                $s = $request->string('search');
                $qq->where('numero_transaction', 'like', "%{$s}%")
                   ->orWhereHas('stagiaire', fn ($sq) =>
                        $sq->where('nom', 'like', "%{$s}%")
                           ->orWhere('prenom', 'like', "%{$s}%")
                           ->orWhere('matricule', 'like', "%{$s}%")
                    );
            })
            ->when($request->filled('statut'), fn ($qq) => $qq->where('statut', $request->statut))
            ->when($request->filled('methode_paiement'), fn ($qq) => $qq->where('methode_paiement', $request->methode_paiement))
            ->when($request->filled('date_debut'), fn ($qq) => $qq->whereDate('date_paiement', '>=', $request->date_debut))
            ->when($request->filled('date_fin'), fn ($qq) => $qq->whereDate('date_paiement', '<=', $request->date_fin))
            ->latest('date_paiement');

        $paiements = $q->paginate(20);

        return view('paiements.index', [
            'paiements' => $paiements,
            'stats' => [
                'total_paiements' => Paiement::where('statut', 'valide')->sum('montant'),
                'en_attente'      => Paiement::where('statut', 'en_attente')->count(),
                'refuses'         => Paiement::where('statut', 'refuse')->count(),
            ],
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        $this->authorize('create', Paiement::class);

        $stagiaire = $request->filled('stagiaire_id')
            ? Stagiaire::with('echeanciers')->findOrFail($request->stagiaire_id)
            : null;

        $stagiaires = Stagiaire::actifs()->orderBy('nom')->get();

        return view('paiements.create', compact('stagiaire', 'stagiaires'));
    }

    /**
     * Enregistrer un paiement (statut = en_attente)
     * + affectation FIFO sur les échéances impayées
     */
    public function store(Request $request)
    {
        $this->authorize('create', Paiement::class);

        $validated = $request->validate([
            'stagiaire_id'    => ['required','exists:stagiaires,id'],
            'montant'         => ['required','numeric','min:0.01'],
            'methode_paiement'=> ['nullable','string','max:100'],
            'date_paiement'   => ['required','date'],
            'justificatif'    => ['nullable','file','mimes:pdf,jpg,jpeg,png,webp','max:5120'],
        ]);

        // Refus de sur-paiement : ne pas dépasser le restant cumulé
        $totalRestant = $this->getTotalRestantStagiaire($validated['stagiaire_id']);
        if ((float)$validated['montant'] > (float)$totalRestant) {
            return back()
                ->withErrors(['montant' => "Le montant dépasse le total restant du stagiaire (".number_format($totalRestant, 2)." DH)."])
                ->withInput();
        }

        $paiement = null;

        DB::transaction(function () use ($request, $validated, &$paiement) {
            // Création du paiement
            $paiement = Paiement::create([
                'stagiaire_id'      => $validated['stagiaire_id'],
                'numero_transaction'=> strtoupper(Str::random(12)),
                'montant'           => $validated['montant'],
                'methode_paiement'  => $validated['methode_paiement'] ?? null,
                'statut'            => 'en_attente',
                'date_paiement'     => $validated['date_paiement'],
            ]);

            // Stockage privé du justificatif (disk local = privé par défaut)
            if ($request->hasFile('justificatif')) {
                $filename = 'justificatif_'.now()->format('Ymd_His').'.'.$request->file('justificatif')->getClientOriginalExtension();
                $path = $request->file('justificatif')->storeAs('paiements/'.$paiement->id, $filename, 'local');
                $paiement->update(['justificatif_path' => $path]);
            }

            // Affectation FIFO du montant sur les échéances
            $this->affecterPaiementFIFO($paiement);

            // Notification "reçu" au stagiaire (paiement enregistré, en cours de validation)
            $this->notifyStagiaire($paiement, new PaiementRecuNotification($paiement));
        });

        return redirect()->route('paiements.show', $paiement)->with('success', 'Paiement enregistré et affecté aux échéances.');
    }

    /**
     * Détail d’un paiement
     */
    public function show(Paiement $paiement)
    {
        $this->authorize('view', $paiement);
        $paiement->load(['stagiaire', 'echeanciers' => fn($q) => $q->orderBy('date_echeance')]);
        return view('paiements.show', compact('paiement'));
    }

    /**
     * Valider un paiement
     * - statut -> valide
     * - recalcul du solde stagiaire
     * - notification de validation
     */
    public function valider(Paiement $paiement)
    {
        $this->authorize('update', $paiement);

        if ($paiement->statut === 'valide') {
            return back()->with('info', 'Ce paiement est déjà validé.');
        }

        DB::transaction(function () use ($paiement) {
            $paiement->update([
                'statut'    => 'valide',
                'valide_at' => now(),
            ]);

            // Recalcule les agrégats du stagiaire
            optional($paiement->stagiaire)->updateSoldePaiement();

            // Notification de validation
            $this->notifyStagiaire($paiement, new PaiementValideNotification($paiement));
        });

        return back()->with('success', 'Paiement validé avec succès.');
    }

    /**
     * Refuser un paiement (avec motif)
     * - statut -> refuse
     * - notification de refus
     */
    public function refuser(Request $request, Paiement $paiement)
    {
        $this->authorize('update', $paiement);

        $data = $request->validate([
            'motif' => ['required','string','max:1000'],
        ]);

        if ($paiement->statut === 'refuse') {
            return back()->with('info', 'Ce paiement est déjà refusé.');
        }

        DB::transaction(function () use ($paiement, $data) {
            $paiement->update([
                'statut'      => 'refuse',
                'motif_refus' => $data['motif'],
            ]);

            // Optionnel : détacher l’affectation si tu veux "annuler" l’impact
            // (sinon, on considère que l'affectation n'est appliquée qu'après validation)
            // $paiement->echeanciers()->detach();

            $this->notifyStagiaire($paiement, new PaiementRefuseNotification($paiement, $data['motif']));
        });

        return back()->with('success', 'Paiement refusé.');
    }

    /**
     * Reçu PDF
     */
    public function recu(Paiement $paiement)
    {
        $this->authorize('view', $paiement);

        $paiement->load(['stagiaire','echeanciers' => fn($q) => $q->orderBy('date_echeance')]);

        $pdf = Pdf::loadView('recu', [
            'paiement' => $paiement,
        ])->setPaper('a4');

        // stream() pour aperçu, download() pour téléchargement
        return $pdf->stream('recu_'.$paiement->numero_transaction.'.pdf');
    }

    /**
     * Suppression d’un paiement (optionnel – à restreindre)
     */
    public function destroy(Paiement $paiement)
    {
        $this->authorize('delete', $paiement);

        DB::transaction(function () use ($paiement) {
            // Détacher les affectations + rollback des montants d’échéance
            foreach ($paiement->echeanciers as $ech) {
                $aff = $ech->pivot->montant_affecte;
                // rollback montants
                $ech->montant_paye    = max(0, $ech->montant_paye - $aff);
                $ech->montant_restant = max(0, $ech->montant - $ech->montant_paye);
                $ech->statut = $ech->montant_paye <= 0 ? 'impaye' : 'paye_partiel';
                $ech->save();
            }
            $paiement->echeanciers()->detach();

            // supprimer justificatif
            if ($paiement->justificatif_path && Storage::disk('local')->exists($paiement->justificatif_path)) {
                Storage::disk('local')->deleteDirectory(dirname($paiement->justificatif_path));
            }

            $stagiaire = $paiement->stagiaire;
            $paiement->delete();

            // Recalcul des agrégats
            optional($stagiaire)->updateSoldePaiement();
        });

        return redirect()->route('paiements.index')->with('success', 'Paiement supprimé.');
    }

    // =========================================================================
    // ----------------------   MÉTHODES PRIVÉES   ------------------------------
    // =========================================================================

    /**
     * Montant restant global du stagiaire (échéances impayées/en retard)
     */
    private function getTotalRestantStagiaire(int $stagiaireId): float
    {
        return (float) Echeancier::where('stagiaire_id', $stagiaireId)
            ->whereIn('statut', ['impaye','paye_partiel','en_retard'])
            ->sum('montant_restant');
    }

    /**
     * Affectation FIFO du paiement sur les échéances impayées + recalculs
     * (doit être appelée dans une transaction)
     */
    private function affecterPaiementFIFO(Paiement $paiement): void
    {
        $reste = (float) $paiement->montant;

        // Echéances impayées/en retard du plus ancien au plus proche
        $echeanciers = Echeancier::where('stagiaire_id', $paiement->stagiaire_id)
            ->whereIn('statut', ['impaye','paye_partiel','en_retard'])
            ->where('montant_restant', '>', 0)
            ->orderBy('date_echeance', 'asc')
            ->lockForUpdate() // éviter conditions de course
            ->get();

        foreach ($echeanciers as $ech) {
            if ($reste <= 0) break;

            $aAffecter = (float) min($reste, $ech->montant_restant);
            if ($aAffecter <= 0) continue;

            // Utilise la méthode métier du modèle
            $ech->affecterPaiement($paiement, $aAffecter);

            $reste -= $aAffecter;
        }

        // Si reste > 0 ici, c'est qu'il n'y avait plus de restant (sur-paiement)
        // => le contrôle amont empêche ce cas; on peut sinon lever une exception.
        if ($reste > 0.000001) {
            throw new \RuntimeException('Montant non affecté détecté (contrôle sur-paiement manquant).');
        }
    }

    /**
     * Notifier le stagiaire (s’il a un compte utilisateur lié)
     */
    private function notifyStagiaire(Paiement $paiement, $notification): void
    {
        $stagiaire = $paiement->stagiaire;
        if ($stagiaire && method_exists($stagiaire, 'user') && $stagiaire->user) {
            // Notification en file (ShouldQueue sur la notification)
            $stagiaire->user->notify($notification);
        }
    }
}
