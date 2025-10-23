<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ============================================================================
// MODÈLE REMISE
// ============================================================================
class Remise extends Model
{
    protected $fillable = [
        'stagiaire_id',
        'created_by',
        'titre',
        'type',
        'valeur',
        'motif',
        'date_debut',
        'date_fin',
        'is_active',
    ];

    protected $casts = [
        'valeur' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('is_active', true)
            ->where('date_debut', '<=', now())
            ->where(function($q) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', now());
            });
    }

    // Méthodes
    public function calculerMontant($montantBase)
    {
        if ($this->type === 'pourcentage') {
            return ($montantBase * $this->valeur) / 100;
        }
        
        return min($this->valeur, $montantBase);
    }

    public function getTypeLibelleAttribute()
    {
        return $this->type === 'pourcentage' 
            ? "{$this->valeur}%" 
            : number_format($this->valeur, 2) . ' DH';
    }
}
