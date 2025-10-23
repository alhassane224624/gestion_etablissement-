<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\Filiere;
use App\Models\Note;
use App\Models\User;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $stats = [
            'general' => $this->getGeneralStatistics(),
            'filieres' => $this->getFiliereStatistics(),
            'notes' => $this->getNotesStatistics(),
            'professeurs' => $this->getProfesseurStatistics(),
            'evolution' => $this->getEvolutionStatistics(),
        ];

        return view('statistics.index', compact('stats'));
    }

    private function getGeneralStatistics()
    {
        return [
            'total_stagiaires' => Stagiaire::count(),
            'total_filieres' => Filiere::count(),
            'total_professeurs' => User::where('role', 'professeur')->count(),
            'total_notes' => Note::count(),
            'stagiaires_ce_mois' => Stagiaire::whereMonth('created_at', now()->month)->count(),
            'moyenne_generale' => round(Note::avg('note') ?? 0, 2),
            'notes_ce_mois' => Note::whereMonth('created_at', now()->month)->count(),
        ];
    }

    private function getFiliereStatistics()
    {
        return Filiere::withCount(['stagiaires'])
            ->with(['stagiaires.notes'])
            ->get()
            ->map(function ($filiere) {
                $notes = $filiere->stagiaires->flatMap->notes;
                $notesCount = $notes->count();
                
                return [
                    'id' => $filiere->id,
                    'nom' => $filiere->nom,
                    'niveau' => $filiere->niveau,
                    'stagiaires_count' => $filiere->stagiaires_count,
                    'notes_count' => $notesCount,
                    'moyenne' => $notesCount > 0 ? round($notes->avg('note'), 2) : 0,
                    'taux_reussite' => $notesCount > 0 
                        ? round(($notes->where('note', '>=', 10)->count() / $notesCount) * 100, 1) 
                        : 0,
                ];
            });
    }

    private function getNotesStatistics()
    {
        // Statistiques par matière avec JOIN
        $parMatiere = Note::select('matieres.nom as matiere', 'matieres.id as matiere_id')
            ->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
            ->selectRaw('COUNT(notes.id) as total')
            ->selectRaw('AVG(notes.note) as moyenne')
            ->selectRaw('MAX(notes.note) as note_max')
            ->selectRaw('MIN(notes.note) as note_min')
            ->selectRaw('COUNT(CASE WHEN notes.note >= 10 THEN 1 END) as reussites')
            ->groupBy('matieres.id', 'matieres.nom')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'par_matiere' => $parMatiere,
            
            'distribution' => [
                'excellent' => Note::where('note', '>=', 16)->count(),
                'bien' => Note::whereBetween('note', [14, 15.99])->count(),
                'assez_bien' => Note::whereBetween('note', [12, 13.99])->count(),
                'passable' => Note::whereBetween('note', [10, 11.99])->count(),
                'insuffisant' => Note::where('note', '<', 10)->count(),
            ],
        ];
    }

    private function getProfesseurStatistics()
    {
        return User::where('role', 'professeur')
            ->withCount(['matieresEnseignees as matieres_count'])
            ->with(['filieres'])
            ->get()
            ->map(function ($professeur) {
                $notes = Note::where('created_by', $professeur->id)->get();
                return [
                    'id' => $professeur->id,
                    'nom' => $professeur->name,
                    'matieres_count' => $professeur->matieres_count ?? 0,
                    'filieres_count' => $professeur->filieres->count(),
                    'notes_saisies' => $notes->count(),
                    'derniere_activite' => $notes->max('created_at'),
                ];
            });
    }

    private function getEvolutionStatistics()
    {
        $derniers_mois = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $derniers_mois->push([
                'mois' => $date->format('M Y'),
                'stagiaires' => Stagiaire::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'notes' => Note::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
            ]);
        }
        return $derniers_mois;
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        if ($format === 'pdf') {
            return $this->exportToPDF();
        } else {
            return $this->exportToExcel();
        }
    }

    private function exportToExcel()
    {
        $stats = [
            'general' => $this->getGeneralStatistics(),
            'filieres' => $this->getFiliereStatistics(),
            'notes' => $this->getNotesStatistics(),
            'professeurs' => $this->getProfesseurStatistics(),
        ];

        // Générer un fichier CSV (compatible Excel)
        $filename = 'statistiques_' . now()->format('Y_m_d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8 pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // === STATISTIQUES GÉNÉRALES ===
            fputcsv($file, ['=== STATISTIQUES GÉNÉRALES ==='], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, ['Indicateur', 'Valeur'], ';');
            fputcsv($file, ['Total Stagiaires', $stats['general']['total_stagiaires']], ';');
            fputcsv($file, ['Total Filières', $stats['general']['total_filieres']], ';');
            fputcsv($file, ['Total Professeurs', $stats['general']['total_professeurs']], ';');
            fputcsv($file, ['Total Notes', $stats['general']['total_notes']], ';');
            fputcsv($file, ['Moyenne Générale', number_format($stats['general']['moyenne_generale'], 2) . '/20'], ';');
            fputcsv($file, ['Stagiaires ce mois', $stats['general']['stagiaires_ce_mois']], ';');
            fputcsv($file, ['Notes ce mois', $stats['general']['notes_ce_mois']], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, [''], ';');

            // === PERFORMANCES PAR FILIÈRE ===
            fputcsv($file, ['=== PERFORMANCES PAR FILIÈRE ==='], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, ['Filière', 'Niveau', 'Stagiaires', 'Notes', 'Moyenne', 'Taux Réussite'], ';');
            foreach ($stats['filieres'] as $filiere) {
                fputcsv($file, [
                    $filiere['nom'],
                    $filiere['niveau'],
                    $filiere['stagiaires_count'],
                    $filiere['notes_count'],
                    number_format($filiere['moyenne'], 2) . '/20',
                    number_format($filiere['taux_reussite'], 1) . '%'
                ], ';');
            }
            fputcsv($file, [''], ';');
            fputcsv($file, [''], ';');

            // === DISTRIBUTION DES NOTES ===
            fputcsv($file, ['=== DISTRIBUTION DES NOTES ==='], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, ['Mention', 'Nombre'], ';');
            fputcsv($file, ['Excellent (≥16)', $stats['notes']['distribution']['excellent']], ';');
            fputcsv($file, ['Bien (14-16)', $stats['notes']['distribution']['bien']], ';');
            fputcsv($file, ['Assez Bien (12-14)', $stats['notes']['distribution']['assez_bien']], ';');
            fputcsv($file, ['Passable (10-12)', $stats['notes']['distribution']['passable']], ';');
            fputcsv($file, ['Insuffisant (<10)', $stats['notes']['distribution']['insuffisant']], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, [''], ';');

            // === PERFORMANCES PAR MATIÈRE ===
            fputcsv($file, ['=== PERFORMANCES PAR MATIÈRE ==='], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, ['Matière', 'Total Notes', 'Moyenne', 'Note Max', 'Note Min', 'Réussites', 'Taux'], ';');
            foreach ($stats['notes']['par_matiere'] as $matiere) {
                $tauxReussite = $matiere->total > 0 ? ($matiere->reussites / $matiere->total) * 100 : 0;
                fputcsv($file, [
                    $matiere->matiere,
                    $matiere->total,
                    number_format($matiere->moyenne, 2) . '/20',
                    number_format($matiere->note_max, 2),
                    number_format($matiere->note_min, 2),
                    $matiere->reussites . '/' . $matiere->total,
                    number_format($tauxReussite, 1) . '%'
                ], ';');
            }
            fputcsv($file, [''], ';');
            fputcsv($file, [''], ';');

            // === ACTIVITÉ DES PROFESSEURS ===
            fputcsv($file, ['=== ACTIVITÉ DES PROFESSEURS ==='], ';');
            fputcsv($file, [''], ';');
            fputcsv($file, ['Professeur', 'Matières', 'Filières', 'Notes Saisies', 'Dernière Activité'], ';');
            foreach ($stats['professeurs'] as $prof) {
                fputcsv($file, [
                    $prof['nom'],
                    $prof['matieres_count'],
                    $prof['filieres_count'],
                    $prof['notes_saisies'],
                    $prof['derniere_activite'] ? \Carbon\Carbon::parse($prof['derniere_activite'])->format('d/m/Y H:i') : 'Jamais'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Remplacer la méthode exportToPDF par:
private function exportToPDF()
{
    $stats = [
        'general' => $this->getGeneralStatistics(),
        'filieres' => $this->getFiliereStatistics(),
        'notes' => $this->getNotesStatistics(),
    ];

    $pdf = Pdf::loadView('exports.statistics-pdf', compact('stats'));
    
    $filename = 'statistiques_' . now()->format('Y_m_d_His') . '.pdf';
    
    return $pdf->download($filename);
}
}