<?php

namespace App\Notifications;

use App\Models\Echeancier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RetardPaiementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $echeancier;

    public function __construct(Echeancier $echeancier)
    {
        $this->echeancier = $echeancier;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $joursRetard = now()->diffInDays($this->echeancier->date_echeance);
        
        return [
            'title' => '⚠️ Paiement en retard',
            'message' => "Échéance de {$this->echeancier->montant_restant} DH en retard de {$joursRetard} jour(s).",
            'type' => 'danger',
            'icon' => 'fas fa-exclamation-triangle',
            'url' => route('stagiaire.echeanciers'),
            'echeancier_id' => $this->echeancier->id,
        ];
    }

    public function toMail($notifiable)
    {
        $joursRetard = now()->diffInDays($this->echeancier->date_echeance);
        
        return (new MailMessage)
            ->subject('⚠️ Retard de paiement - ' . config('app.name'))
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Nous constatons un retard de paiement sur votre compte :")
            ->line('**Titre :** ' . $this->echeancier->titre)
            ->line('**Montant dû :** ' . number_format($this->echeancier->montant_restant, 2) . ' DH')
            ->line('**Date limite dépassée :** ' . $this->echeancier->date_echeance->format('d/m/Y'))
            ->line("**Retard :** {$joursRetard} jour(s)")
            ->line('⚠️ **Important :** Un retard prolongé peut entraîner une suspension de votre inscription.')
            ->action('Régulariser maintenant', route('stagiaire.echeanciers'))
            ->line('Contactez-nous en cas de difficultés.');
    }
}