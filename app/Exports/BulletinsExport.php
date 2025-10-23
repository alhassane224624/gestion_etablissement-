<?php

namespace App\Exports;

use App\Models\Stagiaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BulletinsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filiereId;

    public function __construct($filiereId)
    {
        $this->filiereId = $filiereId;
    }

    public function collection()
    {
        $stagiaires = Stagiaire::with(['filiere.matieres', 'notes'])
            ->where('filiere_id', $this->filiereId)
            ->get()
            ->map(function ($stagiaire) {
                $stagiaire->moyenne_generale = $this->calculerMoyenneGenerale($stagiaire);
                return $stagiaire;
            })
            ->sortByDesc('moyenne_generale')
            ->values();

        $stagiaires->each(function ($stagiaire, $index) {
            $stagiaire->rang = $index + 1;
        });

        return $stagiaires;
    }

    public function headings(): array
    {
        $matieres = \App\Models\MatiereFiliere::where('filiere_id', $this->filiereId)
            ->pluck('matiere')
            ->toArray();

        return array_merge(
            ['Rang', 'Matricule', 'Nom', 'Prénom', 'Filière', 'Moyenne Générale'],
            array_map(fn($matiere) => $matiere . ' (Note)', $matieres),
            array_map(fn($matiere) => $matiere . ' (Coefficient)', $matieres)
        );
    }

    public function map($stagiaire): array
    {
        $matieres = \App\Models\MatiereFiliere::where('filiere_id', $this->filiereId)->get();
        $notes = $stagiaire->notes;

        $notesData = [];
        $coefficientsData = [];

        foreach ($matieres as $matiere) {
            $note = $notes->filter(function ($note) use ($matiere) {
                return strtolower($note->matiere) === strtolower($matiere->matiere);
            })->sortByDesc('updated_at')->first();
            $notesData[] = $note ? $note->note : 'N/A';
            $coefficientsData[] = $matiere->coefficient;
        }

        return array_merge(
            [
                $stagiaire->rang,
                $stagiaire->matricule,
                $stagiaire->nom,
                $stagiaire->prenom,
                $stagiaire->filiere->nom,
                $stagiaire->moyenne_generale,
            ],
            $notesData,
            $coefficientsData
        );
    }

    private function calculerMoyenneGenerale(Stagiaire $stagiaire)
    {
        $matieresFiliere = $stagiaire->filiere->matieres;
        $notes = $stagiaire->notes;

        $totalPoints = 0;
        $totalCoefficients = 0;

        foreach ($matieresFiliere as $matiereFiliere) {
            $note = $notes->filter(function ($note) use ($matiereFiliere) {
                return strtolower($note->matiere) === strtolower($matiereFiliere->matiere);
            })->sortByDesc('updated_at')->first();
            if ($note) {
                $totalPoints += $note->note * $matiereFiliere->coefficient;
                $totalCoefficients += $matiereFiliere->coefficient;
            }
        }

        return $totalCoefficients > 0 ? round($totalPoints / $totalCoefficients, 2) : 0;
    }
}