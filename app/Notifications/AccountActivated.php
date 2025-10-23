<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountActivated extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Compte activé',
            'message' => 'Votre compte a été activé avec succès. Vous pouvez maintenant accéder à toutes les fonctionnalités.',
            'url' => route('dashboard'),
            'icon' => 'fas fa-check-circle',
            'type' => 'success'
        ];
    }
}