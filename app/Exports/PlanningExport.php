<?php
namespace App\Exports;

use App\Models\Planning;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PlanningExport implements FromQuery, WithHeadings, WithMapping
{
    protected $semaine;
    protected $filiere_id;
    protected $professeur_id;

    public function __construct($semaine, $filiere_id = null, $professeur_id = null)
    {
        $this->semaine = $semaine;
        $this->filiere_id = $filiere_id;
        $this->professeur_id = $professeur_id;
    }

    public function query()
    {
        [$annee, $numeroSemaine] = explode('-W', $this->semaine);
        $debutSemaine = Carbon::now()->setISODate($annee, $numeroSemaine)->startOfWeek();
        $finSemaine = $debutSemaine->copy()->endOfWeek();

        $query = Planning::whereBetween('date', [$debutSemaine, $finSemaine])
            ->with(['filiere', 'professeur', 'salle'])
            ->orderBy('date')
            ->orderBy('heure_debut');

        if ($this->filiere_id) {
            $query->where('filiere_id', $this->filiere_id);
        }

        if ($this->professeur_id) {
            $query->where('professeur_id', $this->professeur_id);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Jour',
            'Heure Début',
            'Heure Fin',
            'Matière',
            'Type',
            'Filière',
            'Professeur',
            'Salle',
            'Description'
        ];
    }

    public function map($planning): array
    {
        return [
            $planning->date->format('d/m/Y'),
            $planning->date->format('l'),
            $planning->heure_debut,
            $planning->heure_fin,
            $planning->matiere,
            ucfirst($planning->type_cours),
            $planning->filiere->nom,
            $planning->professeur->name,
            $planning->salle->nom,
            $planning->description
        ];
    }
}