<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Planning;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Salle::query();

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('batiment')) {
            $query->where('batiment', 'like', '%' . $request->batiment . '%');
        }

        if ($request->filled('disponible')) {
            $query->where('disponible', $request->boolean('disponible'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('batiment', 'like', "%{$search}%");
            });
        }

        $salles = $query->orderBy('nom')->paginate(15);

        // Statistiques
        $stats = [
            'total' => Salle::count(),
            'disponibles' => Salle::where('disponible', true)->count(),
            'occupees_aujourd_hui' => $this->getSallesOccupeesAujourdhui(),
            'capacite_totale' => Salle::sum('capacite')
        ];

        return view('salles.index', compact('salles', 'stats'));
    }

    public function create()
    {
        $types = [
            'amphitheatre' => 'Amphithéâtre',
            'salle_cours' => 'Salle de cours',
            'laboratoire' => 'Laboratoire',
            'salle_informatique' => 'Salle informatique'
        ];

        $equipements = [
            'projecteur' => 'Projecteur',
            'tableau_interactif' => 'Tableau interactif',
            'ordinateurs' => 'Ordinateurs',
            'climatisation' => 'Climatisation',
            'sono' => 'Système audio',
            'wifi' => 'Wi-Fi',
            'prises_electriques' => 'Prises électriques',
            'ecran' => 'Écran',
            'micro' => 'Microphone',
            'camera' => 'Caméra'
        ];

        return view('salles.create', compact('types', 'equipements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:salles,nom',
            'capacite' => 'required|integer|min:1|max:1000',
            'type' => 'required|in:amphitheatre,salle_cours,laboratoire,salle_informatique',
            'equipements' => 'array',
            'equipements.*' => 'string',
            'disponible' => 'boolean',
            'batiment' => 'nullable|string|max:255',
            'etage' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        // Nettoyer les équipements
        $validated['equipements'] = array_filter($validated['equipements'] ?? []);
        $validated['disponible'] = $request->has('disponible') ? true : false;

        Salle::create($validated);

        return redirect()->route('salles.index')
                        ->with('success', 'Salle créée avec succès.');
    }

    public function show($salle)
    {
        $salle = Salle::with('plannings')->findOrFail($salle);
        
        // Récupérer les plannings de cette salle pour la semaine courante
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        
        $plannings = $salle->plannings()
                          ->whereBetween('date', [$debutSemaine, $finSemaine])
                          ->with(['professeur', 'classe', 'matiere'])
                          ->orderBy('date')
                          ->orderBy('heure_debut')
                          ->get();

        // Statistiques d'utilisation
        $stats = [
            'cours_cette_semaine' => $plannings->count(),
            'heures_utilisees_semaine' => $this->calculateHeuresUtilisees($plannings),
            'taux_occupation' => $this->calculateTauxOccupation($salle),
            'professeur_principal' => $this->getProfesseurPrincipal($salle)
        ];

        return view('salles.show', compact('salle', 'plannings', 'stats'));
    }

    public function edit($salle)
    {
        $salle = Salle::findOrFail($salle);
        
        $types = [
            'amphitheatre' => 'Amphithéâtre',
            'salle_cours' => 'Salle de cours',
            'laboratoire' => 'Laboratoire',
            'salle_informatique' => 'Salle informatique'
        ];

        $equipements = [
            'projecteur' => 'Projecteur',
            'tableau_interactif' => 'Tableau interactif',
            'ordinateurs' => 'Ordinateurs',
            'climatisation' => 'Climatisation',
            'sono' => 'Système audio',
            'wifi' => 'Wi-Fi',
            'prises_electriques' => 'Prises électriques',
            'ecran' => 'Écran',
            'micro' => 'Microphone',
            'camera' => 'Caméra'
        ];

        return view('salles.edit', compact('salle', 'types', 'equipements'));
    }

    public function update(Request $request, $salle)
    {
        $salle = Salle::findOrFail($salle);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:salles,nom,' . $salle->id,
            'capacite' => 'required|integer|min:1|max:1000',
            'type' => 'required|in:amphitheatre,salle_cours,laboratoire,salle_informatique',
            'equipements' => 'array',
            'equipements.*' => 'string',
            'disponible' => 'boolean',
            'batiment' => 'nullable|string|max:255',
            'etage' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $validated['equipements'] = array_filter($validated['equipements'] ?? []);
        $validated['disponible'] = $request->has('disponible') ? true : false;

        $salle->update($validated);

        return redirect()->route('salles.index')
                        ->with('success', 'Salle mise à jour avec succès.');
    }

    public function destroy($salle)
    {
        $salle = Salle::findOrFail($salle);
        
        // Vérifier s'il y a des plannings futurs
        $planningsFuturs = $salle->plannings()
                                ->where('date', '>=', now())
                                ->count();

        if ($planningsFuturs > 0) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de supprimer cette salle car elle a {$planningsFuturs} cours planifiés."
            ], 400);
        }

        $salle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salle supprimée avec succès.'
        ]);
    }

    public function toggleDisponibilite($salle)
    {
        $salle = Salle::findOrFail($salle);
        $salle->update(['disponible' => !$salle->disponible]);

        $status = $salle->disponible ? 'disponible' : 'indisponible';
        
        return redirect()->back()
                        ->with('success', "Salle {$salle->nom} marquée comme {$status}.");
    }

    public function planning($salle, Request $request)
{
    $salle = Salle::findOrFail($salle);

    // Récupérer la semaine avec format par défaut
    $semaine = $request->get('semaine', now()->format('o-W'));
    
    // Valider le format de la semaine
    if (!preg_match('/^\d{4}-W\d{1,2}$/', $semaine)) {
        $semaine = now()->format('o-W');
    }

    // Extraire l'année et le numéro de semaine de manière sécurisée
    $parts = explode('-W', $semaine);
    if (count($parts) !== 2) {
        $annee = now()->year;
        $numeroSemaine = now()->week;
    } else {
        [$annee, $numeroSemaine] = $parts;
    }

    // Créer les dates de début et fin de semaine
    $debutSemaine = Carbon::now()
        ->setISODate($annee, $numeroSemaine)
        ->startOfWeek();
    $finSemaine = $debutSemaine->copy()->endOfWeek();

    // Récupérer les plannings de la salle pour la semaine
    $plannings = $salle->plannings()
                      ->whereBetween('date', [$debutSemaine, $finSemaine])
                      ->with(['professeur', 'classe.niveau', 'classe.filiere', 'matiere'])
                      ->orderBy('date')
                      ->orderBy('heure_debut')
                      ->get();

    // Organiser les plannings par jour
    $planning_semaine = [];
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
    
    for ($i = 0; $i < 6; $i++) {
        $jour = $debutSemaine->copy()->addDays($i);
        $planning_semaine[$jours[$i]] = [
            'date' => $jour,
            'cours' => $plannings->filter(function($planning) use ($jour) {
                return $planning->date->isSameDay($jour);
            })->sortBy('heure_debut')
        ];
    }

    return view('salles.planning', compact(
        'salle', 
        'planning_semaine', 
        'semaine', 
        'debutSemaine', 
        'finSemaine'
    ));
}

    public function checkDisponibilite(Request $request)
    {
        $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'exclude_planning_id' => 'nullable|integer'
        ]);

        $salle = Salle::findOrFail($request->salle_id);
        
        $disponible = $salle->isDisponible(
            $request->date,
            $request->heure_debut,
            $request->heure_fin,
            $request->exclude_planning_id
        );

        if (!$disponible) {
            $conflit = $salle->plannings()
                            ->where('date', $request->date)
                            ->where(function($q) use ($request) {
                                $q->where('heure_debut', '<', $request->heure_fin)
                                  ->where('heure_fin', '>', $request->heure_debut);
                            })
                            ->when($request->exclude_planning_id, function($q) use ($request) {
                                $q->where('id', '!=', $request->exclude_planning_id);
                            })
                            ->with(['professeur', 'classe', 'matiere'])
                            ->first();

            return response()->json([
                'disponible' => false,
                'conflit' => $conflit,
                'message' => "Salle occupée de {$conflit->heure_debut} à {$conflit->heure_fin} par {$conflit->professeur->name}"
            ]);
        }

        return response()->json(['disponible' => true]);
    }

    private function getSallesOccupeesAujourdhui()
    {
        return Planning::whereDate('date', today())
                      ->distinct('salle_id')
                      ->count();
    }

    private function calculateHeuresUtilisees($plannings)
    {
        $totalMinutes = 0;
        foreach ($plannings as $planning) {
            $debut = Carbon::parse($planning->heure_debut);
            $fin = Carbon::parse($planning->heure_fin);
            $totalMinutes += $debut->diffInMinutes($fin);
        }
        return round($totalMinutes / 60, 1);
    }

    private function calculateTauxOccupation(Salle $salle)
    {
        // Calcul sur les 30 derniers jours
        $plannings = $salle->plannings()
                          ->where('date', '>=', now()->subDays(30))
                          ->get();
        
        $heuresUtilisees = $this->calculateHeuresUtilisees($plannings);
        $heuresDisponibles = 30 * 8; // 8h par jour pendant 30 jours
        
        return $heuresDisponibles > 0 ? round(($heuresUtilisees / $heuresDisponibles) * 100, 1) : 0;
    }

    private function getProfesseurPrincipal(Salle $salle)
    {
        $result = $salle->plannings()
                    ->selectRaw('professeur_id, COUNT(*) as total')
                    ->groupBy('professeur_id')
                    ->orderBy('total', 'desc')
                    ->with('professeur')
                    ->first();
                    
        return $result ? $result->professeur : null;
    }
}