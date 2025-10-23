<?php

// =============================================================================
// FICHIER 5: Niveau.php (NOUVEAU)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $table = 'niveaux';

    protected $fillable = [
        'nom',
        'ordre',
        'filiere_id',
        'duree_semestres',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'duree_semestres' => 'integer',
    ];

    // Relations
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function stagiaires()
    {
        return $this->hasMany(Stagiaire::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'matiere_niveau')
            ->withPivot('heures_cours', 'is_obligatoire')
            ->withTimestamps();
    }

    // Scopes
    public function scopeParFiliere($query, $filiereId)
    {
        return $query->where('filiere_id', $filiereId);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre');
    }

    // Accessors
    public function getNomCompletAttribute()
    {
        return "{$this->nom} - {$this->filiere->nom}";
    }

    // Méthodes helpers
    public function getMatieresObligatoires()
    {
        return $this->matieres()->wherePivot('is_obligatoire', true)->get();
    }

    public function getMatieresOptionnelles()
    {
        return $this->matieres()->wherePivot('is_obligatoire', false)->get();
    }
}