<?php
// =============================================================================
// FICHIER 14: AdminActionLog.php (NOUVEAU)
// =============================================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends Model
{
    protected $table = 'admin_actions_log';

    protected $fillable = [
        'admin_id',
        'action',
        'target_type',
        'target_id',
        'details',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function target()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeParAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeParAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getActionLibelleAttribute()
    {
        return match($this->action) {
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'activate' => 'Activation',
            'deactivate' => 'Désactivation',
            'validate' => 'Validation',
            'cancel' => 'Annulation',
            default => ucfirst($this->action)
        };
    }
}
