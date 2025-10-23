<?php
// =============================================================================
// FICHIER 3: NiveauController.php (NOUVEAU - MANQUANT)
// =============================================================================
namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Models\Filiere;
use App\Models\Matiere;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $niveaux = Niveau::with(['filiere', 'matieres'])
            ->withCount(['classes', 'stagiaires'])
            ->orderBy('filiere_id')
            ->orderBy('ordre')
            ->get();

        return view('niveaux.index', compact('niveaux'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $matieres = Matiere::all();
        
        return view('niveaux.create', compact('filieres', 'matieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'ordre' => 'required|integer|min:1',
            'filiere_id' => 'required|exists:filieres,id',
            'duree_semestres' => 'required|integer|min:1|max:10',
            'matieres' => 'nullable|array',
            'matieres.*.matiere_id' => 'required|exists:matieres,id',
            'matieres.*.heures_cours' => 'nullable|integer|min:0',
            'matieres.*.is_obligatoire' => 'boolean'
        ]);

        // Vérifier l'unicité de l'ordre dans la filière
        $exists = Niveau::where('filiere_id', $validated['filiere_id'])
            ->where('ordre', $validated['ordre'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['ordre' => 'Cet ordre existe déjà pour cette filière.'])
                ->withInput();
        }

        $niveau = Niveau::create([
            'nom' => $validated['nom'],
            'ordre' => $validated['ordre'],
            'filiere_id' => $validated['filiere_id'],
            'duree_semestres' => $validated['duree_semestres']
        ]);

        // Attacher les matières
        if (isset($validated['matieres'])) {
            foreach ($validated['matieres'] as $matiereData) {
                $niveau->matieres()->attach($matiereData['matiere_id'], [
                    'heures_cours' => $matiereData['heures_cours'] ?? null,
                    'is_obligatoire' => $matiereData['is_obligatoire'] ?? true
                ]);
            }
        }

        return redirect()->route('niveaux.index')
            ->with('success', 'Niveau créé avec succès.');
    }

    public function show(Niveau $niveau)
    {
        $niveau->load(['filiere', 'matieres', 'classes', 'stagiaires']);
        
        $stats = [
            'total_classes' => $niveau->classes()->count(),
            'total_stagiaires' => $niveau->stagiaires()->count(),
            'total_matieres' => $niveau->matieres()->count(),
        ];

        return view('niveaux.show', compact('niveau', 'stats'));
    }

    public function edit(Niveau $niveau)
    {
        $niveau->load('matieres');
        $filieres = Filiere::all();
        $matieres = Matiere::all();
        
        return view('niveaux.edit', compact('niveau', 'filieres', 'matieres'));
    }

    public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'ordre' => 'required|integer|min:1',
            'filiere_id' => 'required|exists:filieres,id',
            'duree_semestres' => 'required|integer|min:1|max:10',
            'matieres' => 'nullable|array',
        ]);

        // Vérifier l'unicité de l'ordre dans la filière (sauf pour ce niveau)
        $exists = Niveau::where('filiere_id', $validated['filiere_id'])
            ->where('ordre', $validated['ordre'])
            ->where('id', '!=', $niveau->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['ordre' => 'Cet ordre existe déjà pour cette filière.'])
                ->withInput();
        }

        $niveau->update([
            'nom' => $validated['nom'],
            'ordre' => $validated['ordre'],
            'filiere_id' => $validated['filiere_id'],
            'duree_semestres' => $validated['duree_semestres']
        ]);

        // Synchroniser les matières
        $matieresSync = [];
        if (isset($validated['matieres'])) {
            foreach ($validated['matieres'] as $matiereData) {
                $matieresSync[$matiereData['matiere_id']] = [
                    'heures_cours' => $matiereData['heures_cours'] ?? null,
                    'is_obligatoire' => $matiereData['is_obligatoire'] ?? true
                ];
            }
        }
        $niveau->matieres()->sync($matieresSync);

        return redirect()->route('niveaux.index')
            ->with('success', 'Niveau mis à jour avec succès.');
    }

    public function destroy(Niveau $niveau)
    {
        // Vérifier s'il y a des classes ou stagiaires
        if ($niveau->classes()->exists() || $niveau->stagiaires()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce niveau car il contient des classes ou des stagiaires.');
        }

        $niveau->delete();

        return redirect()->route('niveaux.index')
            ->with('success', 'Niveau supprimé avec succès.');
    }
}