<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filieres = Filiere::when($search, function ($query, $search) {
            return $query->where('nom', 'like', "%{$search}%")
                        ->orWhere('niveau', 'like', "%{$search}%");
        })->get();

        return view('filieres.index', compact('filieres'));
    }

    public function create()
    {
        return view('filieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
        ]);

        Filiere::create($request->all());
        return redirect()->route('filieres.index')->with('success', 'Filière ajoutée avec succès.');
    }

    public function show(Filiere $filiere)
    {
        // Chargement des relations utiles
        $filiere->load(['niveaux.matieres', 'niveaux.classes', 'matieres', 'professeurs', 'stagiaires.classe']);

        return view('filieres.show', compact('filiere'));
    }

    public function edit(Filiere $filiere)
    {
        return view('filieres.edit', compact('filiere'));
    }

    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'niveau' => 'required|string|max:255',
        ]);

        $filiere->update($request->all());
        return redirect()->route('filieres.index')->with('success', 'Filière mise à jour avec succès.');
    }

    public function destroy(Filiere $filiere)
    {
        $filiere->delete();
        return redirect()->route('filieres.index')->with('success', 'Filière supprimée avec succès.');
    }
}
