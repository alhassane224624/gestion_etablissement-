<?php

// =============================================================================
// FICHIER 3: Filiere.php (MIS À JOUR)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Filiere extends Model
{
    use LogsActivity;

    protected $fillable = [
        'nom',
        'niveau',
    ];

    protected static $logAttributes = ['nom', 'niveau'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nom', 'niveau'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relations
    public function stagiaires()
    {
        return $this->hasMany(Stagiaire::class);
    }

    public function niveaux()
    {
        return $this->hasMany(Niveau::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    // Relation many-to-many avec matieres
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'matiere_filiere')
            ->withTimestamps();
    }

    // Relation many-to-many avec professeurs
    public function professeurs()
    {
        return $this->belongsToMany(User::class, 'professeur_filiere', 'filiere_id', 'professeur_id')
            ->withPivot('created_by', 'is_active', 'date_assignation', 'remarques')
            ->withTimestamps();
    }

    // Scopes
    public function scopeAvecStagiairesActifs($query)
    {
        return $query->whereHas('stagiaires', function($q) {
            $q->where('is_active', true);
        });
    }

    // Accessors
    public function getNomCompletAttribute()
    {
        return "{$this->nom} ({$this->niveau})";
    }

    // Méthodes helpers
    public function getTotalStagiairesActifs()
    {
        return $this->stagiaires()->where('is_active', true)->count();
    }

    public function getMatieresActives()
    {
        return $this->matieres;
    }
}
