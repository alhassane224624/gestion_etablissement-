<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\Stagiaire;
use App\Models\Classe;
use App\Models\Periode;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

// 🔔 AJOUT : Importer la notification
use App\Notifications\BulletinGenerated;

class BulletinController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Bulletin::with(['stagiaire', 'classe', 'periode', 'creator']);

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        $bulletins = $query->latest()->paginate(20);
        
        $classes = Classe::with('niveau', 'filiere')->get();
        $periodes = Periode::with('anneeScolaire')->get();

        return view('bulletins.index', compact('bulletins', 'classes', 'periodes'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'periode_id' => 'required|exists:periodes,id',
        ]);

        $classe = Classe::with('stagiaires')->findOrFail($validated['classe_id']);
        $periode = Periode::findOrFail($validated['periode_id']);

        $generated = 0;
        
        foreach ($classe->stagiaires as $stagiaire) {
            $exists = Bulletin::where('stagiaire_id', $stagiaire->id)
                ->where('periode_id', $periode->id)
                ->exists();

            if (!$exists) {
                $bulletin = $this->generateBulletinForStagiaire($stagiaire, $classe, $periode);
                
                // 🔔 NOTIFICATION : Notifier le stagiaire que son bulletin est prêt
                if ($bulletin && $stagiaire->user) {
                    $stagiaire->user->notify(new BulletinGenerated($bulletin));
                }
                
                $generated++;
            }
        }

        return redirect()->back()
            ->with('success', "{$generated} bulletins générés avec succès.");
    }

    public function show(Bulletin $bulletin)
    {
        $bulletin->load(['stagiaire', 'classe.niveau', 'classe.filiere', 'periode']);
        return view('bulletins.show', compact('bulletin'));
    }

    public function downloadPdf(Bulletin $bulletin)
    {
        $bulletin->load(['stagiaire', 'classe.niveau', 'classe.filiere', 'periode']);
        
        $pdf = PDF::loadView('bulletins.pdf', compact('bulletin'));
        
        $filename = 'bulletin_' . $bulletin->stagiaire->matricule . '_' . 
                    $bulletin->periode->nom . '.pdf';
        
        return $pdf->download($filename);
    }

    public function validateBulletin(Bulletin $bulletin)
    {
        if ($bulletin->validated_at) {
            return redirect()->back()
                ->with('error', 'Ce bulletin est déjà validé.');
        }

        $bulletin->update([
            'validated_at' => now(),
            'validated_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Bulletin validé avec succès.');
    }

    private function generateBulletinForStagiaire(Stagiaire $stagiaire, Classe $classe, Periode $periode)
    {
        $notes = Note::where('stagiaire_id', $stagiaire->id)
            ->where('periode_id', $periode->id)
            ->with('matiere')
            ->get();

        if ($notes->isEmpty()) {
            return null;
        }

        $moyennesParMatiere = $notes->groupBy('matiere_id')->map(function ($notesMatiere) {
            $matiere = $notesMatiere->first()->matiere;
            
            if (!$matiere) {
                return null;
            }
            
            $moyenne = $notesMatiere->avg('note');
            
            return [
                'matiere' => $matiere->nom ?? 'N/A',
                'code' => $matiere->code ?? 'N/A',
                'coefficient' => $matiere->coefficient ?? 1,
                'moyenne' => round($moyenne, 2),
                'note_sur' => 20
            ];
        })->filter();

        $totalPoints = 0;
        $totalCoefficients = 0;
        
        foreach ($moyennesParMatiere as $moyenneMatiere) {
            $totalPoints += $moyenneMatiere['moyenne'] * $moyenneMatiere['coefficient'];
            $totalCoefficients += $moyenneMatiere['coefficient'];
        }

        $moyenneGenerale = $totalCoefficients > 0 ? 
            round($totalPoints / $totalCoefficients, 2) : 0;

        $stagiairesDeLaClasse = $classe->stagiaires()
            ->whereHas('notes', function($q) use ($periode) {
                $q->where('periode_id', $periode->id);
            })
            ->get();

        $moyennesClasse = $stagiairesDeLaClasse->map(function ($s) use ($periode) {
            $notesS = Note::where('stagiaire_id', $s->id)
                ->where('periode_id', $periode->id)
                ->with('matiere')
                ->get();

            $moyennesS = $notesS->groupBy('matiere_id')->map(function ($nm) {
                $matiere = $nm->first()->matiere;
                if (!$matiere) return null;
                
                return [
                    'moyenne' => $nm->avg('note'),
                    'coefficient' => $matiere->coefficient ?? 1
                ];
            })->filter();

            $totalP = 0;
            $totalC = 0;
            foreach ($moyennesS as $m) {
                $totalP += $m['moyenne'] * $m['coefficient'];
                $totalC += $m['coefficient'];
            }

            return [
                'stagiaire_id' => $s->id,
                'moyenne' => $totalC > 0 ? $totalP / $totalC : 0
            ];
        })->sortByDesc('moyenne')->values();

        $rang = $moyennesClasse->search(function ($item) use ($stagiaire) {
            return $item['stagiaire_id'] === $stagiaire->id;
        }) + 1;

        $appreciation = $this->genererAppreciation($moyenneGenerale, $rang, $stagiairesDeLaClasse->count());

        return Bulletin::create([
            'stagiaire_id' => $stagiaire->id,
            'classe_id' => $classe->id,
            'periode_id' => $periode->id,
            'moyenne_generale' => $moyenneGenerale,
            'rang' => $rang,
            'total_classe' => $stagiairesDeLaClasse->count(),
            'appreciation_generale' => $appreciation,
            'moyennes_matieres' => $moyennesParMatiere->values()->toArray(),
            'created_by' => Auth::id(),
        ]);
    }

    private function genererAppreciation($moyenne, $rang, $totalEleves)
    {
        if ($moyenne >= 16) {
            return "Excellent travail ! Continuez ainsi.";
        } elseif ($moyenne >= 14) {
            return "Très bon travail. Félicitations !";
        } elseif ($moyenne >= 12) {
            return "Bon travail dans l'ensemble.";
        } elseif ($moyenne >= 10) {
            return "Travail satisfaisant. Peut mieux faire.";
        } else {
            return "Travail insuffisant. Des efforts sont nécessaires.";
        }
    }
}
