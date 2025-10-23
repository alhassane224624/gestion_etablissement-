<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Stagiaire;

class InscriptionValidated extends Notification
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
            'title' => 'Inscription validée',
            'message' => sprintf(
                'Félicitations ! Votre inscription à la filière %s a été validée.',
                $this->stagiaire->filiere->nom ?? 'la filière'
            ),
            'stagiaire_id' => $this->stagiaire->id,
            'url' => route('stagiaire.dashboard'),
            'icon' => 'fas fa-graduation-cap',
            'type' => 'success'
        ];
    }
}