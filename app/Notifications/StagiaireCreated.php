<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Stagiaire;

class StagiaireCreated extends Notification implements ShouldQueue
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
            'title' => 'Nouveau stagiaire inscrit',
            'message' => sprintf(
                'Le stagiaire %s %s a été inscrit dans la filière %s.',
                $this->stagiaire->nom,
                $this->stagiaire->prenom,
                $this->stagiaire->filiere->nom ?? 'Non définie'
            ),
            'stagiaire_id' => $this->stagiaire->id,
            'url' => route('stagiaires.show', $this->stagiaire->id),
            'icon' => 'fas fa-user-plus',
            'type' => 'success'
        ];
    }
}