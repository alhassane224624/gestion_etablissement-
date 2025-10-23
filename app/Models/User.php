<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'created_by',
        'is_active',
        'activated_at',
        'activated_by',
        'specialite',
        'bio',
        'telephone',
        // 'email_verified_at' retiré du fillable (géré automatiquement)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ✅ NOUVELLE RELATION - CRITIQUE POUR LES STAGIAIRES
    public function stagiaire()
    {
        return $this->hasOne(Stagiaire::class, 'user_id');
    }

    // ✅ MÉTHODE HELPER
    public function hasStagiaireProfile()
    {
        return $this->stagiaire()->exists();
    }


    // Relations existantes
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activatedBy()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    public function usersCreated()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'professeur_filiere', 'professeur_id', 'filiere_id')
            ->withPivot('created_by', 'is_active', 'date_assignation', 'remarques')
            ->withTimestamps();
    }

    public function matieresEnseignees()
    {
        return $this->belongsToMany(Matiere::class, 'professeur_matiere', 'professeur_id', 'matiere_id')
            ->withPivot('filiere_id', 'assigned_by', 'is_active', 'date_assignation', 'competences')
            ->withTimestamps();
    }

    public function stagiairesCreated()
    {
        return $this->hasMany(Stagiaire::class, 'created_by');
    }

    public function notesCreated()
    {
        return $this->hasMany(Note::class, 'created_by');
    }

    public function planningsCreated()
    {
        return $this->hasMany(Planning::class, 'created_by');
    }

    public function planningsValidated()
    {
        return $this->hasMany(Planning::class, 'validated_by');
    }

    public function planningsAsProfesseur()
    {
        return $this->hasMany(Planning::class, 'professeur_id');
    }

    public function absencesCreated()
    {
        return $this->hasMany(Absence::class, 'created_by');
    }

    public function bulletinsCreated()
    {
        return $this->hasMany(Bulletin::class, 'created_by');
    }

    public function bulletinsValidated()
    {
        return $this->hasMany(Bulletin::class, 'validated_by');
    }

    public function adminActionsLog()
    {
        return $this->hasMany(AdminActionLog::class, 'admin_id');
    }

    // Relations messagerie
    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Méthodes helpers
    public function isAdmin()
    {
        return $this->role === 'administrateur';
    }

    public function isStagiaire(): bool
    {
        return $this->role === 'stagiaire';
    }

    public function isProfesseur()
    {
        return $this->role === 'professeur';
    }

    public function canTeachMatiere($matiereId)
    {
        return $this->matieresEnseignees()
            ->where('matiere_id', $matiereId)
            ->wherePivot('is_active', true)
            ->exists();
    }

    public function hasAccessToFiliere($filiereId)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isProfesseur()) {
            return $this->filieres()
                ->where('filiere_id', $filiereId)
                ->wherePivot('is_active', true)
                ->exists();
        }

        return false;
    }

    public function getActiveFilieresAttribute()
    {
        return $this->filieres()->wherePivot('is_active', true)->get();
    }

    public function getActiveMatieresAttribute()
    {
        return $this->matieresEnseignees()->wherePivot('is_active', true)->get();
    }

    // Méthodes messagerie
    public function getUnreadMessagesCount()
    {
        return $this->messagesReceived()->where('is_read', false)->count();
    }

    public function getConversations()
    {
        return Message::getConversationsFor($this->id);
    }

    public function getConversationWith($userId)
    {
        return Message::between($this->id, $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessageTo($receiverId, $message, $subject = null)
    {
        return Message::create([
            'sender_id' => $this->id,
            'receiver_id' => $receiverId,
            'message' => $message,
            'subject' => $subject,
        ]);
    }

    public function getStagiaires()
    {
        if (!$this->isProfesseur()) {
            return collect();
        }

        $filiereIds = $this->filieres()->wherePivot('is_active', true)->pluck('filieres.id');
        
        return Stagiaire::whereIn('filiere_id', $filiereIds)
            ->where('is_active', true)
            ->with(['filiere', 'classe', 'niveau'])
            ->get();
    }
}
