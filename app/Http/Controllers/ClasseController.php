<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Filiere;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Classe::with(['niveau', 'filiere', 'anneeScolaire'])
            ->withCount('stagiaires');

        if ($request->filled('annee_scolaire_id')) {
            $query->where('annee_scolaire_id', $request->annee_scolaire_id);
        }

        if ($request->filled('filiere_id')) {
            $query->where('filiere_id', $request->filiere_id);
        }

        if ($request->filled('niveau_id')) {
            $query->where('niveau_id', $request->niveau_id);
        }

        $classes = $query->orderBy('nom')->paginate(15);
        
        $annees = AnneeScolaire::orderBy('debut', 'desc')->get();
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();

        return view('classes.index', compact('classes', 'annees', 'filieres', 'niveaux'));
    }

    public function create()
    {
        $annees = AnneeScolaire::orderBy('debut', 'desc')->get();
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
        
        return view('classes.create', compact('annees', 'filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'niveau_id' => 'required|exists:niveaux,id',
            'filiere_id' => 'required|exists:filieres,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'effectif_max' => 'required|integer|min:1|max:100',
        ]);

        Classe::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function show(Classe $classe)
    {
        $classe->load(['niveau', 'filiere', 'anneeScolaire', 'stagiaires']);
        
        $stats = [
            'total_stagiaires' => $classe->stagiaires()->count(),
            'places_restantes' => $classe->effectif_max - $classe->effectif_actuel,
            'taux_remplissage' => $classe->effectif_max > 0 
                ? ($classe->effectif_actuel / $classe->effectif_max) * 100 
                : 0,
        ];

        return view('classes.show', compact('classe', 'stats'));
    }

    public function edit(Classe $classe)
    {
        $annees = AnneeScolaire::orderBy('debut', 'desc')->get();
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
        
        return view('classes.edit', compact('classe', 'annees', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'niveau_id' => 'required|exists:niveaux,id',
            'filiere_id' => 'required|exists:filieres,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'effectif_max' => 'required|integer|min:' . $classe->effectif_actuel . '|max:100',
        ]);

        $classe->update($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe)
    {
        if ($classe->stagiaires()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette classe car elle contient des stagiaires.');
        }

        $classe->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Classe supprimée avec succès.');
    }
}