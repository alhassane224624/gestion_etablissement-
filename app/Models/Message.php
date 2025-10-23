<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
        'type',
        'subject',
        'attachments',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'attachments' => 'array',
    ];

    // Relations
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetween($query, $userId1, $userId2)
    {
        return $query->where(function($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
        });
    }

    public function scopeConversations($query, $userId)
    {
        return $query->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId);
    }

    // Méthodes helpers
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function isSender($userId)
    {
        return $this->sender_id == $userId;
    }

    public function isReceiver($userId)
    {
        return $this->receiver_id == $userId;
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Méthode statique pour obtenir les conversations
    public static function getConversationsFor($userId)
    {
        return self::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function($message) use ($userId) {
                return $message->sender_id == $userId 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return $messages->first();
            });
    }

    // Compter les messages non lus
    public static function countUnreadFor($userId)
    {
        return self::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}