<?php
// =============================================================================
// FICHIER 4: Matiere.php (NOUVEAU)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = [
        'nom',
        'code',
        'coefficient',
        'couleur',
        'description',
    ];

    protected $casts = [
        'coefficient' => 'integer',
    ];

    // Relations
    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'matiere_filiere')
            ->withTimestamps();
    }

    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'matiere_niveau')
            ->withPivot('heures_cours', 'is_obligatoire')
            ->withTimestamps();
    }

    public function professeurs()
    {
        return $this->belongsToMany(User::class, 'professeur_matiere', 'matiere_id', 'professeur_id')
            ->withPivot('filiere_id', 'assigned_by', 'is_active', 'date_assignation', 'competences')
            ->withTimestamps();
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function plannings()
    {
        return $this->hasMany(Planning::class);
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->whereHas('filieres');
    }

    // Accessors
    public function getCouleurHexAttribute()
    {
        return $this->couleur ?? '#6c757d';
    }

    // Méthodes helpers
    public function isObligatoirePourNiveau($niveauId)
    {
        return $this->niveaux()
            ->where('niveau_id', $niveauId)
            ->wherePivot('is_obligatoire', true)
            ->exists();
    }
}