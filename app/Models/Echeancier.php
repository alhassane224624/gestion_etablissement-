<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeancier extends Model
{
    use HasFactory;

    protected $table = 'echeanciers';

    protected $fillable = [
        'stagiaire_id',
        'annee_scolaire_id',
        'titre',
        'montant',
        'date_echeance',
        'statut',
        'montant_paye',
        'montant_restant',
        'notification_envoyee',
        'notification_sent_at',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'date_echeance' => 'date',
        'notification_envoyee' => 'boolean',
        'notification_sent_at' => 'datetime',
    ];

    // ------------------ RELATIONS ------------------
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function paiements()
    {
        return $this->belongsToMany(Paiement::class, 'echeancier_paiement')
            ->withPivot('montant_affecte')
            ->withTimestamps();
    }

    // ------------------ SCOPES ------------------
    public function scopeImpayes($query)
    {
        return $query->where('statut', 'impaye');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'en_retard')
            ->orWhere(function ($q) {
                $q->where('statut', 'impaye')
                  ->where('date_echeance', '<', now());
            });
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_echeance', '>', now())
            ->where('statut', 'impaye');
    }

    // ------------------ ACCESSORS ------------------
    public function getStatutLibelleAttribute()
    {
        return match ($this->statut) {
            'impaye' => 'Impayé',
            'paye_partiel' => 'Payé partiellement',
            'paye' => 'Payé',
            'en_retard' => 'En retard',
            default => 'Inconnu',
        };
    }

    public function getIsEnRetardAttribute()
    {
        return $this->date_echeance < now() && in_array($this->statut, ['impaye', 'paye_partiel']);
    }

    // ------------------ MÉTHODES MÉTIER ------------------
    public function affecterPaiement(Paiement $paiement, $montant)
    {
        $this->paiements()->attach($paiement->id, ['montant_affecte' => $montant]);

        $this->montant_paye += $montant;
        $this->montant_restant = $this->montant - $this->montant_paye;

        if ($this->montant_restant <= 0) {
            $this->statut = 'paye';
        } elseif ($this->montant_paye > 0) {
            $this->statut = 'paye_partiel';
        }

        $this->save();
    }

    public function verifierRetard()
    {
        if ($this->date_echeance < now() && in_array($this->statut, ['impaye', 'paye_partiel'])) {
            $this->update(['statut' => 'en_retard']);
        }
    }
}
