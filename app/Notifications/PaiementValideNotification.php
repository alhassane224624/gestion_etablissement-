<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementValideNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => '✅ Paiement validé',
            'message' => "Votre paiement de {$this->paiement->montant} DH a été validé. Reçu disponible.",
            'type' => 'success',
            'icon' => 'fas fa-check-circle',
            'url' => route('stagiaire.paiement.recu', $this->paiement),
            'paiement_id' => $this->paiement->id,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('✅ Paiement validé - ' . config('app.name'))
            ->greeting('Excellente nouvelle !')
            ->line("Votre paiement de **{$this->paiement->montant} DH** a été validé avec succès.")
            ->line('**Numéro de transaction :** ' . $this->paiement->numero_transaction)
            ->line('**Date de validation :** ' . $this->paiement->valide_at->format('d/m/Y à H:i'))
            ->action('Télécharger le reçu', route('stagiaire.paiement.recu', $this->paiement))
            ->line('Votre reçu de paiement est maintenant disponible.')
            ->line('Merci !');
    }
}
