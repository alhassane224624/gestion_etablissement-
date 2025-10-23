<?php
// =============================================================================
// FICHIER 12: AnneeScolaire.php (MIS À JOUR)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    protected $fillable = [
        'nom',
        'debut',
        'fin',
        'is_active'
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function periodes()
    {
        return $this->hasMany(Periode::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function periodeActive()
    {
        return $this->hasOne(Periode::class)->where('is_active', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeEnCours($query)
    {
        return $query->whereDate('debut', '<=', today())
            ->whereDate('fin', '>=', today());
    }

    // Accessors
    public function getDureeJoursAttribute()
    {
        return $this->debut->diffInDays($this->fin);
    }

    public function getProgressionAttribute()
    {
        if ($this->debut > today()) {
            return 0;
        }

        if ($this->fin < today()) {
            return 100;
        }

        $totalJours = $this->duree_jours;
        $joursEcoules = $this->debut->diffInDays(today());

        return round(($joursEcoules / $totalJours) * 100, 1);
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