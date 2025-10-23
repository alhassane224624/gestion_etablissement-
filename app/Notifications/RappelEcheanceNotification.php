<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RappelEcheanceNotification extends Notification
{
    use Queueable;

    protected $echeancier;

    public function __construct($echeancier)
    {
        $this->echeancier = $echeancier;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $joursRestants = now()->diffInDays($this->echeancier->date_echeance, false);
        
        return [
            'title' => '⏰ Échéance proche',
            'message' => "Échéance de {$this->echeancier->montant_restant} DH dans {$joursRestants} jour(s).",
            'type' => 'warning',
            'icon' => 'fas fa-clock',
            'url' => route('stagiaire.echeanciers'),
            'echeancier_id' => $this->echeancier->id,
        ];
    }

    public function toMail($notifiable)
    {
        $joursRestants = now()->diffInDays($this->echeancier->date_echeance, false);
        
        return (new MailMessage)
            ->subject('⏰ Rappel d\'échéance - ' . config('app.name'))
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Nous vous rappelons qu'une échéance de paiement approche :")
            ->line('**Titre :** ' . $this->echeancier->titre)
            ->line('**Montant :** ' . $this->echeancier->montant_restant . ' DH')
            ->line('**Date limite :** ' . $this->echeancier->date_echeance->format('d/m/Y'))
            ->line("**Jours restants :** {$joursRestants}")
            ->action('Voir mes échéanciers', route('stagiaire.echeanciers'))
            ->line('Merci de régulariser votre situation dans les délais.');
    }
}