<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Stagiaire;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 🔔 AJOUT : Importer la notification
use App\Notifications\NoteCreated;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::with(['stagiaire', 'matiere', 'classe', 'periode', 'creator']);

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('type_note')) {
            $query->where('type_note', $request->type_note);
        }

        if (Auth::user()->isProfesseur()) {
            $query->where('created_by', Auth::id());
        }

        $notes = $query->latest()->paginate(20);
        
        $classes = Classe::with('niveau', 'filiere')->get();
        $matieres = Matiere::all();
        $periodes = Periode::where('is_active', true)->orWhereHas('notes')->get();
        
        return view('notes.index', compact('notes', 'classes', 'matieres', 'periodes'));
    }

    public function create(Request $request)
    {
        if (!Auth::user()->isProfesseur()) {
            abort(403, 'Seuls les professeurs peuvent ajouter des notes.');
        }

        $stagiaire_id = $request->get('stagiaire_id');
        $stagiaire = $stagiaire_id ? Stagiaire::with('classe', 'niveau')->findOrFail($stagiaire_id) : null;
        
        $stagiaires = Stagiaire::with('classe', 'filiere')->where('is_active', true)->get();
        $matieres = Matiere::all();
        $periodes = Periode::where('is_active', true)->get();
        
        return view('notes.create', compact('stagiaires', 'matieres', 'periodes', 'stagiaire'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isProfesseur()) {
            abort(403, 'Seuls les professeurs peuvent ajouter des notes.');
        }

        $validated = $request->validate([
            'stagiaire_id' => 'required|exists:stagiaires,id',
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'nullable|exists:classes,id',
            'periode_id' => 'nullable|exists:periodes,id',
            'note' => 'required|numeric|min:0|max:20',
            'type_note' => 'required|in:ds,cc,examen,tp,projet',
            'note_sur' => 'nullable|numeric|min:1|max:20',
            'commentaire' => 'nullable|string|max:1000'
        ]);

        $validated['created_by'] = Auth::id();
        $validated['note_sur'] = $validated['note_sur'] ?? 20;

        if (empty($validated['classe_id'])) {
            $stagiaire = Stagiaire::findOrFail($validated['stagiaire_id']);
            $validated['classe_id'] = $stagiaire->classe_id;
        }

        $note = Note::create($validated);

        // 🔔 NOTIFICATION : Notifier le stagiaire de sa nouvelle note
        $stagiaire = Stagiaire::find($validated['stagiaire_id']);
        if ($stagiaire && $stagiaire->user) {
            $stagiaire->user->notify(new NoteCreated($note));
        }

        return redirect()->route('notes.index')
            ->with('success', 'Note ajoutée avec succès.');
    }

    // ... Garder toutes les autres méthodes sans modification
    public function show(Note $note)
    {
        $note->load(['stagiaire', 'matiere', 'classe', 'periode', 'creator']);
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        if (!Auth::user()->isProfesseur()) {
            abort(403, 'Seuls les professeurs peuvent modifier les notes.');
        }

        if ($note->created_by !== Auth::id()) {
            abort(403, 'Non autorisé à modifier cette note.');
        }

        $matieres = Matiere::all();
        $periodes = Periode::all();
        $stagiaires = Stagiaire::with('classe', 'filiere')->where('is_active', true)->get();

        return view('notes.edit', compact('note', 'matieres', 'periodes', 'stagiaires'));
    }

    public function update(Request $request, Note $note)
    {
        if (!Auth::user()->isProfesseur()) {
            abort(403, 'Seuls les professeurs peuvent modifier les notes.');
        }

        if ($note->created_by !== Auth::id()) {
            abort(403, 'Non autorisé à modifier cette note.');
        }

        $validated = $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'note' => 'required|numeric|min:0|max:20',
            'type_note' => 'required|in:ds,cc,examen,tp,projet',
            'note_sur' => 'nullable|numeric|min:1|max:20',
            'commentaire' => 'nullable|string|max:1000',
            'periode_id' => 'nullable|exists:periodes,id'
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')
            ->with('success', 'Note mise à jour avec succès.');
    }

    public function destroy(Note $note)
    {
        if (!Auth::user()->isProfesseur()) {
            abort(403, 'Seuls les professeurs peuvent supprimer les notes.');
        }

        if ($note->created_by !== Auth::id()) {
            abort(403, 'Non autorisé à supprimer cette note.');
        }

        $note->delete();

        return response()->json(['success' => true]);
    }

    public function releveStagiaire(Stagiaire $stagiaire, Request $request)
    {
        $periode_id = $request->get('periode_id');
        
        $query = $stagiaire->notes()
            ->with(['matiere', 'periode', 'creator']);

        if ($periode_id) {
            $query->where('periode_id', $periode_id);
        }

        $notes = $query->get();
        
        $moyennes = $notes->groupBy('matiere_id')->map(function ($notesMatiere) {
            return [
                'matiere' => $notesMatiere->first()->matiere,
                'moyenne' => $notesMatiere->avg('note'),
                'notes' => $notesMatiere
            ];
        });

        $moyenneGenerale = $notes->avg('note');

        $periodes = Periode::all();
        
        return view('notes.releve', compact('stagiaire', 'notes', 'moyennes', 'moyenneGenerale', 'periodes', 'periode_id'));
    }

    public function export(Request $request)
    {
        $query = Note::with(['stagiaire', 'matiere', 'classe', 'periode', 'creator']);

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('type_note')) {
            $query->where('type_note', $request->type_note);
        }

        if (Auth::user()->isProfesseur()) {
            $query->where('created_by', Auth::id());
        }

        $notes = $query->orderBy('created_at', 'desc')->get();

        $filename = 'notes_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($notes) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'ID', 'Matricule', 'Nom', 'Prénom', 'Classe', 'Matière', 
                'Coefficient', 'Type', 'Période', 'Note', 'Note sur', 'Note/20',
                'Appréciation', 'Commentaire', 'Professeur', 'Date'
            ], ';');

            foreach ($notes as $note) {
                fputcsv($file, [
                    $note->id,
                    $note->stagiaire->matricule ?? '',
                    $note->stagiaire->nom ?? '',
                    $note->stagiaire->prenom ?? '',
                    $note->classe->nom ?? '',
                    $note->matiere->nom ?? '',
                    $note->matiere->coefficient ?? '',
                    strtoupper($note->type_note),
                    $note->periode->nom ?? '',
                    number_format($note->note, 2, ',', ''),
                    $note->note_sur,
                    number_format($note->note_sur_20, 2, ',', ''),
                    $note->appreciation,
                    $note->commentaire ?? '',
                    $note->creator->name ?? '',
                    $note->created_at->format('d/m/Y H:i')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}