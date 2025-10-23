<?php
// App\Exports\AbsencesExport.php
namespace App\Exports;

use App\Models\Absence;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsencesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filiereId;
    protected $dateDebut;
    protected $dateFin;

    public function __construct($filiereId = null, $dateDebut = null, $dateFin = null)
    {
        $this->filiereId = $filiereId;
        $this->dateDebut = $dateDebut ?: now()->startOfMonth();
        $this->dateFin = $dateFin ?: now()->endOfMonth();
    }

    public function query()
    {
        $query = Absence::query()
            ->with(['stagiaire.filiere'])
            ->whereBetween('date', [$this->dateDebut, $this->dateFin]);

        if ($this->filiereId) {
            $query->whereHas('stagiaire', function($q) {
                $q->where('filiere_id', $this->filiereId);
            });
        }

        return $query->orderBy('date');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Nom',
            'Prénom',
            'Matricule',
            'Filière',
            'Type',
            'Heure début',
            'Heure fin',
            'Durée',
            'Justifiée',
            'Motif'
        ];
    }

    public function map($absence): array
    {
        return [
            $absence->date->format('d/m/Y'),
            $absence->stagiaire->nom,
            $absence->stagiaire->prenom,
            $absence->stagiaire->matricule,
            $absence->stagiaire->filiere->nom,
            $absence->type_libelle,
            $absence->heure_debut,
            $absence->heure_fin ?: '-',
            $absence->duree,
            $absence->justifiee ? 'Oui' : 'Non',
            $absence->motif ?: '-'
        ];
    }
}