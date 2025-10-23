<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'stagiaire_id',
        'user_id',
        'numero_transaction',
        'montant',
        'type_paiement',
        'methode_paiement',
        'statut',
        'date_paiement',
        'reference_externe',
        'gateway',
        'description',
        'metadata',
        'date_echeance',
        'valide_at',
        'valide_by',
        'recu_path',
        'justificatif_path',
        'notes_admin',
        'notes_stagiaire',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'date_echeance' => 'date',
        'valide_at' => 'datetime',
        'montant' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Boot : génération auto du numéro de transaction.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paiement) {
            if (empty($paiement->numero_transaction)) {
                $paiement->numero_transaction = self::genererNumeroTransaction();
            }
        });
    }

    /* ------------------------- RELATIONS ------------------------- */

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valide_by');
    }

    public function echeanciers()
    {
        return $this->belongsToMany(Echeancier::class, 'echeancier_paiement')
                    ->withPivot('montant_affecte')
                    ->withTimestamps();
    }

    /* ------------------------- SCOPES ------------------------- */

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeRefuses($query)
    {
        return $query->where('statut', 'refuse');
    }

    /* ------------------------- ACCESSORS ------------------------- */

    /**
     * Libellé du type de paiement
     */
    public function getTypeLibelleAttribute()
    {
        return match($this->type_paiement) {
            'inscription' => 'Frais d\'inscription',
            'mensualite' => 'Mensualité',
            'examen' => 'Frais d\'examen',
            'autre' => 'Autre',
            default => 'Non défini'
        };
    }

    /**
     * Libellé de la méthode de paiement
     */
    public function getMethodeLibelleAttribute()
    {
        return match($this->methode_paiement) {
            'especes' => 'Espèces',
            'carte' => 'Carte bancaire',
            'virement' => 'Virement bancaire',
            'cheque' => 'Chèque',
            'mobile_money' => 'Mobile Money',
            default => 'Non défini'
        };
    }

    /**
     * Libellé du statut
     */
    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'valide' => 'Validé',
            'refuse' => 'Refusé',
            'rembourse' => 'Remboursé',
            default => 'Non défini'
        };
    }

    /* ------------------------- MÉTHODES MÉTIER ------------------------- */

    /**
     * Génère un numéro de transaction unique
     */
    private static function genererNumeroTransaction()
    {
        $prefix = 'PAY';
        $year = now()->format('Y');
        $lastId = self::whereYear('created_at', now()->year)->count() + 1;
        $number = str_pad($lastId, 6, '0', STR_PAD_LEFT);
        
        return "{$prefix}{$year}{$number}";
    }

    /**
     * Affecte automatiquement le paiement sur les échéances impayées.
     */
    public function affecterAuxEcheances()
    {
        DB::transaction(function () {
            $montantRestant = $this->montant;

            $echeances = $this->stagiaire
                ->echeanciers()
                ->whereIn('statut', ['impaye', 'paye_partiel', 'en_retard'])
                ->orderBy('date_echeance')
                ->get();

            foreach ($echeances as $echeance) {
                if ($montantRestant <= 0) break;

                $montantAffecte = min($montantRestant, $echeance->montant_restant);
                
                $this->echeanciers()->attach($echeance->id, [
                    'montant_affecte' => $montantAffecte
                ]);
                
                $echeance->affecterPaiement($this, $montantAffecte);

                $montantRestant -= $montantAffecte;
            }

            // Met à jour les totaux stagiaire
            $this->stagiaire->updateSoldePaiement();
        });
    }

    /**
     * Valide le paiement.
     */
    public function valider($userId = null)
    {
        $this->update([
            'statut' => 'valide',
            'valide_at' => now(),
            'valide_by' => $userId ?? auth()->id(),
        ]);

        // Générer le reçu si pas déjà généré
        if (!$this->recu_path) {
            $this->genererRecu();
        }

        $this->stagiaire->updateSoldePaiement();
    }

    /**
     * Refuse le paiement avec motif.
     */
    public function refuser($motif)
    {
        $this->update([
            'statut' => 'refuse',
            'notes_admin' => $motif,
        ]);
    }

    /**
     * Vérifie si le paiement est déjà validé.
     */
    public function estValide(): bool
    {
        return $this->statut === 'valide';
    }

    /**
     * Génère le reçu PDF du paiement
     */
    public function genererRecu()
    {
        try {
            // Charger les relations nécessaires
            $this->load(['stagiaire.filiere', 'stagiaire.classe', 'echeanciers']);

            // Générer le PDF
            $pdf = Pdf::loadView('paiements.recu', ['paiement' => $this]);
            
            // Définir le chemin
            $filename = 'recu_' . $this->numero_transaction . '.pdf';
            $path = 'recus/' . $this->stagiaire_id . '/' . $filename;
            
            // Sauvegarder le PDF
            Storage::disk('public')->put($path, $pdf->output());
            
            // Mettre à jour le paiement
            $this->update(['recu_path' => $path]);
            
            return $path;
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération reçu: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprime le reçu PDF associé
     */
    public function supprimerRecu()
    {
        if ($this->recu_path && Storage::disk('public')->exists($this->recu_path)) {
            Storage::disk('public')->delete($this->recu_path);
            $this->update(['recu_path' => null]);
        }
    }
}