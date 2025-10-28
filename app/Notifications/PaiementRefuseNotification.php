<?php

namespace App\Notifications;

use App\Models\Paiement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementRefuseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $paiement;
    protected $motif;

    public function __construct(Paiement $paiement, $motif = null)
    {
        $this->paiement = $paiement;
        $this->motif = $motif ?? $paiement->notes_admin;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => '❌ Paiement refusé',
            'message' => "Votre paiement de {$this->paiement->montant} DH a été refusé. Contactez l'administration.",
            'type' => 'danger',
            'icon' => 'fas fa-times-circle',
            'url' => route('stagiaire.paiements'),
            'paiement_id' => $this->paiement->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Paiement refusé - ' . config('app.name'))
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Votre paiement de **{$this->paiement->montant} DH** a été refusé.")
            ->line('**Numéro de transaction :** ' . $this->paiement->numero_transaction)
            ->line('**Motif :** ' . ($this->motif ?? 'Non spécifié'))
            ->line('Veuillez contacter l\'administration pour plus d\'informations.')
            ->action('Voir mes paiements', route('stagiaire.paiements'))
            ->line('Cordialement.');
    }
}