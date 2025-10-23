<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class MessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        // ✅ CORRECTION : Utiliser les bons noms de colonnes de votre table messages
        // Votre table utilise : sender_id, receiver_id, message (pas expediteur_id, contenu)
        
        $sender = $this->message->sender; // Relation 'sender' au lieu de 'expediteur'
        
        return [
            'title' => 'Nouveau message',
            'message' => sprintf(
                '%s vous a envoyé un message : %s',
                $sender ? $sender->name : 'Un utilisateur',  // ✅ Vérification si sender existe
                \Str::limit($this->message->message, 50)     // ✅ 'message' au lieu de 'contenu'
            ),
            'message_id' => $this->message->id,
            'user_id' => $this->message->sender_id,          // ✅ 'sender_id' au lieu de 'expediteur_id'
            'url' => route('messages.conversation', $this->message->sender_id),
            'icon' => 'fas fa-envelope',
            'type' => 'info'
        ];
    }
}