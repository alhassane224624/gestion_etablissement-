<?php

// =============================================================================
// FICHIER 11: Periode.php (MIS À JOUR)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $fillable = [
        'nom',
        'type',
        'debut',
        'fin',
        'annee_scolaire_id',
        'is_active'
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function bulletins()
    {
        return $this->hasMany(Bulletin::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParAnneeScolaire($query, $anneeScolaireId)
    {
        return $query->where('annee_scolaire_id', $anneeScolaireId);
    }

    public function scopeEnCours($query)
    {
        return $query->whereDate('debut', '<=', today())
            ->whereDate('fin', '>=', today());
    }

    // Accessors
    public function getTypeLibelleAttribute()
    {
        return match($this->type) {
            'semestre' => 'Semestre',
            'trimestre' => 'Trimestre',
            'periode' => 'Période',
            default => 'Non défini'
        };
    }

    public function getDureeJoursAttribute()
    {
        return $this->debut->diffInDays($this->fin);
    }

    // Méthodes helpers
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isEnCours()
    {
        return $this->debut <= today() && $this->fin >= today();
    }
}