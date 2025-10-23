<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StagiairesExport;

// 🔔 AJOUT : Importer les notifications
use App\Notifications\StagiaireCreated;
use App\Notifications\StagiaireUpdated;
use App\Notifications\InscriptionValidated;

class StagiaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['inscriptionForm', 'inscriptionStore']);
    }

    public function index(Request $request)
    {
        $query = Stagiaire::with(['filiere', 'classe', 'niveau', 'createdBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filiere_id')) {
            $query->where('filiere_id', $request->filiere_id);
        }

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('niveau_id')) {
            $query->where('niveau_id', $request->niveau_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $stagiaires = $query->latest('created_at')->paginate(20);
        
        $filieres = Filiere::all();
        $classes = Classe::with('niveau')->get();
        $niveaux = Niveau::with('filiere')->get();

        return view('stagiaires.index', compact('stagiaires', 'filieres', 'classes', 'niveaux'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $classes = Classe::with('niveau', 'filiere')->where('effectif_actuel', '<', 'effectif_max')->get();
        $niveaux = Niveau::with('filiere')->get();
        
        return view('stagiaires.create', compact('filieres', 'classes', 'niveaux'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:255|unique:stagiaires',
            'date_naissance' => 'nullable|date|before:today',
            'lieu_naissance' => 'nullable|string|max:255',
            'sexe' => 'nullable|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'adresse' => 'nullable|string|max:500',
            'nom_tuteur' => 'nullable|string|max:255',
            'telephone_tuteur' => 'nullable|string|max:20',
            'email_tuteur' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'filiere_id' => 'required|exists:filieres,id',
            'classe_id' => 'nullable|exists:classes,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'date_inscription' => 'nullable|date',
            'frais_inscription' => 'nullable|numeric|min:0',
            'frais_payes' => 'boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'name'       => $validated['prenom'] . ' ' . $validated['nom'],
            'email'      => $validated['email'],
            'password'   => Hash::make('stagiaire123'),
            'role'       => 'stagiaire',
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        $stagiaire = Stagiaire::create([
            'user_id'         => $user->id,
            'nom'             => $validated['nom'],
            'prenom'          => $validated['prenom'],
            'matricule'       => $validated['matricule'],
            'date_naissance'  => $validated['date_naissance'] ?? null,
            'lieu_naissance'  => $validated['lieu_naissance'] ?? null,
            'sexe'            => $validated['sexe'] ?? null,
            'telephone'       => $validated['telephone'] ?? null,
            'email'           => $validated['email'],
            'adresse'         => $validated['adresse'] ?? null,
            'nom_tuteur'      => $validated['nom_tuteur'] ?? null,
            'telephone_tuteur'=> $validated['telephone_tuteur'] ?? null,
            'email_tuteur'    => $validated['email_tuteur'] ?? null,
            'photo'           => $photoPath,
            'filiere_id'      => $validated['filiere_id'],
            'classe_id'       => $validated['classe_id'] ?? null,
            'niveau_id'       => $validated['niveau_id'] ?? null,
            'date_inscription'=> $validated['date_inscription'] ?? now(),
            'frais_inscription'=> $validated['frais_inscription'] ?? 0,
            'frais_payes'     => $validated['frais_payes'] ?? false,
            'created_by'      => Auth::id(),
        ]);

        if ($stagiaire->classe_id) {
            $stagiaire->classe->increment('effectif_actuel');
        }

        // 🔔 NOTIFICATION : Notifier tous les administrateurs
        User::where('role', 'administrateur')
            ->get()
            ->each(function($admin) use ($stagiaire) {
                $admin->notify(new StagiaireCreated($stagiaire));
            });

        return redirect()->route('stagiaires.index')
            ->with('success', "✅ Le stagiaire {$stagiaire->prenom} {$stagiaire->nom} a été créé avec son compte utilisateur.");
    }

    public function show(Stagiaire $stagiaire)
    {
        $stagiaire->load(['filiere', 'classe', 'niveau', 'notes.matiere', 'absences']);
        
        $stats = [
            'total_notes' => $stagiaire->notes()->count(),
            'moyenne_generale' => $stagiaire->notes()->avg('note'),
            'total_absences' => $stagiaire->absences()->count(),
            'absences_injustifiees' => $stagiaire->absences()->where('justifiee', false)->count(),
        ];

        return view('stagiaires.show', compact('stagiaire', 'stats'));
    }

    public function edit(Stagiaire $stagiaire)
    {
        $filieres = Filiere::all();
        $classes = Classe::with('niveau', 'filiere')->get();
        $niveaux = Niveau::with('filiere')->get();
        
        return view('stagiaires.edit', compact('stagiaire', 'filieres', 'classes', 'niveaux'));
    }

    public function update(Request $request, Stagiaire $stagiaire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:255|unique:stagiaires,matricule,' . $stagiaire->id,
            'date_naissance' => 'nullable|date|before:today',
            'lieu_naissance' => 'nullable|string|max:255',
            'sexe' => 'nullable|in:M,F',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:500',
            'nom_tuteur' => 'nullable|string|max:255',
            'telephone_tuteur' => 'nullable|string|max:20',
            'email_tuteur' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'filiere_id' => 'required|exists:filieres,id',
            'classe_id' => 'nullable|exists:classes,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'statut' => 'required|in:actif,suspendu,diplome,abandonne,transfere',
            'motif_statut' => 'nullable|string|max:1000',
            'frais_inscription' => 'nullable|numeric|min:0',
            'frais_payes' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($stagiaire->photo) {
                Storage::disk('public')->delete($stagiaire->photo);
            }
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $oldClasseId = $stagiaire->classe_id;
        $newClasseId = $validated['classe_id'] ?? null;

        if ($oldClasseId != $newClasseId) {
            if ($oldClasseId) {
                Classe::find($oldClasseId)->decrement('effectif_actuel');
            }
            if ($newClasseId) {
                Classe::find($newClasseId)->increment('effectif_actuel');
            }
        }

        $stagiaire->update($validated);

        // 🔔 NOTIFICATION : Notifier les administrateurs de la modification
        User::where('role', 'administrateur')
            ->get()
            ->each(function($admin) use ($stagiaire) {
                $admin->notify(new StagiaireUpdated($stagiaire));
            });

        return redirect()->route('stagiaires.index')
            ->with('success', 'Stagiaire mis à jour avec succès.');
    }

    public function destroy(Stagiaire $stagiaire)
    {
        if ($stagiaire->photo) {
            Storage::disk('public')->delete($stagiaire->photo);
        }

        if ($stagiaire->classe_id) {
            $stagiaire->classe->decrement('effectif_actuel');
        }

        $stagiaire->delete();

        return redirect()->route('stagiaires.index')
            ->with('success', 'Stagiaire supprimé avec succès.');
    }

    public function export(Request $request)
    {
        $filiere_id = $request->get('filiere_id');
        $classe_id = $request->get('classe_id');
        
        return Excel::download(
            new StagiairesExport($filiere_id, $classe_id), 
            'stagiaires_' . now()->format('Y_m_d') . '.xlsx'
        );
    }

    public function changeStatut(Request $request, Stagiaire $stagiaire)
    {
        $validated = $request->validate([
            'statut' => 'required|in:actif,suspendu,diplome,abandonne,transfere',
            'motif_statut' => 'nullable|string|max:1000',
        ]);

        $stagiaire->update($validated);

        // 🔔 NOTIFICATION : Si inscription validée, notifier le stagiaire
        if ($validated['statut'] === 'actif' && $stagiaire->user) {
            $stagiaire->user->notify(new InscriptionValidated($stagiaire));
        }

        return redirect()->back()
            ->with('success', 'Statut du stagiaire modifié avec succès.');
    }
}