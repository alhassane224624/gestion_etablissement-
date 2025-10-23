<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Stagiaire;

class StagiaireUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Stagiaire $stagiaire
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Stagiaire modifié',
            'message' => sprintf(
                'Le profil de %s %s a été mis à jour.',
                $this->stagiaire->nom,
                $this->stagiaire->prenom
            ),
            'stagiaire_id' => $this->stagiaire->id,
            'url' => route('stagiaires.show', $this->stagiaire->id),
            'icon' => 'fas fa-user-edit',
            'type' => 'info'
        ];
    }
}

