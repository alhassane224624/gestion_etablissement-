<?php
// =============================================================================
// FICHIER 13: Salle.php (MIS À JOUR)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom',
        'capacite',
        'type',
        'equipements',
        'disponible',
        'batiment',
        'etage'
    ];

    protected $casts = [
        'equipements' => 'array',
        'disponible' => 'boolean',
        'capacite' => 'integer',
    ];

    // Relations
    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParBatiment($query, $batiment)
    {
        return $query->where('batiment', $batiment);
    }

    // Accessors
    public function getTypeLibelleAttribute()
    {
        return match($this->type) {
            'amphitheatre' => 'Amphithéâtre',
            'salle_cours' => 'Salle de cours',
            'laboratoire' => 'Laboratoire',
            'salle_informatique' => 'Salle informatique',
            default => 'Non défini'
        };
    }

    public function getEquipementsListAttribute()
    {
        $labels = [
            'projecteur' => 'Projecteur',
            'tableau_interactif' => 'Tableau interactif',
            'ordinateurs' => 'Ordinateurs',
            'climatisation' => 'Climatisation',
            'sono' => 'Système audio',
            'wifi' => 'Wi-Fi',
            'prises_electriques' => 'Prises électriques',
            'ecran' => 'Écran',
            'micro' => 'Microphone',
            'camera' => 'Caméra'
        ];

        if (!is_array($this->equipements)) {
            return [];
        }

        return collect($this->equipements)
            ->map(fn($eq) => $labels[$eq] ?? $eq)
            ->toArray();
    }

    // Méthodes helpers
    public function isDisponible($date, $heureDebut, $heureFin, $excludePlanningId = null)
    {
        $query = $this->plannings()
            ->where('date', $date)
            ->whereIn('statut', ['valide', 'en_cours'])
            ->where(function($q) use ($heureDebut, $heureFin) {
                $q->where('heure_debut', '<', $heureFin)
                  ->where('heure_fin', '>', $heureDebut);
            });

        if ($excludePlanningId) {
            $query->where('id', '!=', $excludePlanningId);
        }

        return !$query->exists();
    }

    public function getPlanningSemaine($debut, $fin)
    {
        return $this->plannings()
            ->whereBetween('date', [$debut, $fin])
            ->with(['professeur', 'classe', 'matiere'])
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();
    }

    public function getTauxOccupation($debut, $fin)
    {
        $plannings = $this->plannings()
            ->whereBetween('date', [$debut, $fin])
            ->get();

        $totalMinutes = 0;
        foreach ($plannings as $planning) {
            $heureDebut = \Carbon\Carbon::parse($planning->heure_debut);
            $heureFin = \Carbon\Carbon::parse($planning->heure_fin);
            $totalMinutes += $heureDebut->diffInMinutes($heureFin);
        }

        $joursOuvrables = $debut->diffInDaysFiltered(function($date) use ($fin) {
            return $date->isWeekday() && $date <= $fin;
        }, $fin);

        $minutesDisponibles = $joursOuvrables * 8 * 60; // 8h par jour

        return $minutesDisponibles > 0 ? 
            round(($totalMinutes / $minutesDisponibles) * 100, 1) : 0;
    }
}