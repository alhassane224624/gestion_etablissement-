<?php
// =============================================================================
// FICHIER 9: Bulletin.php (NOUVEAU)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $fillable = [
        'stagiaire_id',
        'classe_id',
        'periode_id',
        'moyenne_generale',
        'rang',
        'total_classe',
        'appreciation_generale',
        'appreciations_matieres',
        'moyennes_matieres',
        'created_by',
        'validated_at',
        'validated_by',
    ];

    protected $casts = [
        'moyenne_generale' => 'decimal:2',
        'rang' => 'integer',
        'total_classe' => 'integer',
        'appreciations_matieres' => 'array',
        'moyennes_matieres' => 'array',
        'validated_at' => 'datetime',
    ];

    // Relations
    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}