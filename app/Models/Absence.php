<?php
// =============================================================================
// FICHIER 10: Absence.php (MIS À JOUR)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Absence extends Model
{
    use LogsActivity;

    protected $fillable = [
        'stagiaire_id',
        'date',
        'heure_debut',
        'heure_fin',
        'type',
        'motif',
        'justifiee',
        'document_justificatif',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'justifiee' => 'boolean',
    ];

    protected static $logAttributes = ['*'];
    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['stagiaire_id', 'date', 'type', 'justifiee'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relations
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeJustifiees($query)
    {
        return $query->where('justifiee', true);
    }

    public function scopeInjustifiees($query)
    {
        return $query->where('justifiee', false);
    }

    public function scopeParPeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date', [$debut, $fin]);
    }
    public function periode()
{
    return $this->belongsTo(Periode::class);
}


    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date', today());
    }

    // Accessors
    public function getTypeLibelleAttribute()
    {
        return match($this->type) {
            'matin' => 'Matin',
            'apres_midi' => 'Après-midi',
            'journee' => 'Journée complète',
            'heure' => 'Par heure',
            default => 'Non défini'
        };
    }

    public function getDureeAttribute()
    {
        if ($this->type === 'heure' && $this->heure_debut && $this->heure_fin) {
            $debut = \Carbon\Carbon::parse($this->heure_debut);
            $fin = \Carbon\Carbon::parse($this->heure_fin);
            return $debut->diffInHours($fin) . 'h';
        }

        return match($this->type) {
            'matin' => '4h',
            'apres_midi' => '4h',
            'journee' => '8h',
            default => '-'
        };
    }
}