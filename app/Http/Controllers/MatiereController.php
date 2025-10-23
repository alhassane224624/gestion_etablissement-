<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Matiere::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $matieres = $query->orderBy('nom')->paginate(15);

        $stats = [
            'total' => Matiere::count(),
            'matieres_avec_filieres' => Matiere::has('filieres')->count(),
            'matieres_avec_niveaux' => Matiere::has('niveaux')->count(),
        ];

        return view('matieres.index', compact('matieres', 'stats'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
        
        return view('matieres.create', compact('filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        // ✅ Nettoyer les niveaux avant validation
        $this->cleanNiveauxData($request);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:matieres,code',
            'coefficient' => 'required|integer|min:1|max:10',
            'couleur' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:1000',
            'filieres' => 'nullable|array',
            'filieres.*' => 'exists:filieres,id',
            'niveaux' => 'nullable|array',
            'niveaux.*.niveau_id' => 'required|exists:niveaux,id',
            'niveaux.*.heures_cours' => 'nullable|integer|min:0',
            'niveaux.*.is_obligatoire' => 'nullable|boolean'
        ]);

        $matiere = Matiere::create([
            'nom' => $validated['nom'],
            'code' => $validated['code'],
            'coefficient' => $validated['coefficient'],
            'couleur' => $validated['couleur'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // 🔗 Attacher les filières
        if (isset($validated['filieres'])) {
            $matiere->filieres()->attach($validated['filieres']);
        }

        // 🔗 Attacher les niveaux avec les détails
        if (isset($validated['niveaux'])) {
            foreach ($validated['niveaux'] as $niveauData) {
                if (isset($niveauData['niveau_id'])) {
                    $matiere->niveaux()->attach($niveauData['niveau_id'], [
                        'heures_cours' => $niveauData['heures_cours'] ?? null,
                        'is_obligatoire' => $niveauData['is_obligatoire'] ?? true
                    ]);
                }
            }
        }

        return redirect()->route('matieres.index')
            ->with('success', 'Matière créée avec succès.');
    }

    public function show(Matiere $matiere)
    {
        $matiere->load(['filieres', 'niveaux.filiere', 'notes']);
        
        $stats = [
            'total_notes' => $matiere->notes()->count(),
            'moyenne_generale' => $matiere->notes()->avg('note'),
            'filieres_count' => $matiere->filieres()->count(),
            'niveaux_count' => $matiere->niveaux()->count(),
        ];

        return view('matieres.show', compact('matiere', 'stats'));
    }

    public function edit(Matiere $matiere)
    {
        $matiere->load(['filieres', 'niveaux']);
        $filieres = Filiere::all();
        $niveaux = Niveau::with('filiere')->get();
        
        return view('matieres.edit', compact('matiere', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        // ✅ Nettoyer les niveaux avant validation
        $this->cleanNiveauxData($request);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:matieres,code,' . $matiere->id,
            'coefficient' => 'required|integer|min:1|max:10',
            'couleur' => 'nullable|string|max:7',
            'description' => 'nullable|string|max:1000',
            'filieres' => 'nullable|array',
            'filieres.*' => 'exists:filieres,id',
            'niveaux' => 'nullable|array',
            'niveaux.*.niveau_id' => 'required|exists:niveaux,id',
            'niveaux.*.heures_cours' => 'nullable|integer|min:0',
            'niveaux.*.is_obligatoire' => 'nullable|boolean'
        ]);

        // ✅ Mise à jour de la matière
        $matiere->update([
            'nom' => $validated['nom'],
            'code' => $validated['code'],
            'coefficient' => $validated['coefficient'],
            'couleur' => $validated['couleur'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        // ✅ Synchronisation des filières
        $matiere->filieres()->sync($validated['filieres'] ?? []);

        // ✅ Synchronisation des niveaux
        $niveauxSync = [];
        if (!empty($validated['niveaux'])) {
            foreach ($validated['niveaux'] as $niveauData) {
                if (!empty($niveauData['niveau_id'])) {
                    $niveauxSync[$niveauData['niveau_id']] = [
                        'heures_cours' => $niveauData['heures_cours'] ?? null,
                        'is_obligatoire' => $niveauData['is_obligatoire'] ?? true,
                    ];
                }
            }
        }

        $matiere->niveaux()->sync($niveauxSync);

        return redirect()->route('matieres.index')
            ->with('success', 'Matière mise à jour avec succès.');
    }

    public function destroy(Matiere $matiere)
    {
        if ($matiere->notes()->exists()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cette matière car elle contient des notes.');
        }

        $matiere->delete();

        return redirect()->route('matieres.index')
            ->with('success', 'Matière supprimée avec succès.');
    }

    /**
     * 🧹 Nettoie les données des niveaux avant validation
     * Supprime les entrées vides et convertit le JSON si nécessaire
     */
    private function cleanNiveauxData(Request $request)
    {
        // Si les niveaux sont envoyés en JSON, on les convertit
        if ($request->has('niveaux') && is_string($request->niveaux)) {
            $decoded = json_decode($request->niveaux, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['niveaux' => $decoded]);
            } else {
                $request->merge(['niveaux' => []]);
            }
        }

        // Filtrer les niveaux pour ne garder que ceux qui ont un niveau_id valide
        if ($request->has('niveaux') && is_array($request->niveaux)) {
            $niveauxFiltres = array_filter($request->niveaux, function($niveau) {
                return isset($niveau['niveau_id']) && !empty($niveau['niveau_id']);
            });
            
            // Réindexer le tableau pour éviter les indices non-consécutifs
            $request->merge(['niveaux' => array_values($niveauxFiltres)]);
        } else {
            $request->merge(['niveaux' => []]);
        }
    }
}