<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountDeactivated extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Compte désactivé',
            'message' => 'Votre compte a été temporairement désactivé. Contactez l\'administrateur pour plus d\'informations.',
            'url' => null,
            'icon' => 'fas fa-exclamation-triangle',
            'type' => 'danger'
        ];
    }
}