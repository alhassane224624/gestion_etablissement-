<?php

namespace App\Notifications;

use App\Models\Paiement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

// ============================================================================
// NOTIFICATION : PAIEMENT REÇU
// ============================================================================
class PaiementRecuNotification extends Notification
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
            'title' => 'Paiement enregistré',
            'message' => "Votre paiement de {$this->paiement->montant} DH a été enregistré avec succès.",
            'type' => 'success',
            'icon' => 'fas fa-money-bill-wave',
            'url' => route('stagiaire.paiements'),
            'paiement_id' => $this->paiement->id,
            'numero_transaction' => $this->paiement->numero_transaction,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Paiement enregistré - ' . config('app.name'))
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Nous avons bien reçu votre paiement de **{$this->paiement->montant} DH**.")
            ->line('**Numéro de transaction :** ' . $this->paiement->numero_transaction)
            ->line('**Date :** ' . $this->paiement->date_paiement->format('d/m/Y'))
            ->line('**Méthode :** ' . $this->paiement->methode_libelle)
            ->line('Votre paiement est en cours de validation.')
            ->action('Voir mes paiements', route('stagiaire.paiements'))
            ->line('Merci de votre confiance !');
    }
}
