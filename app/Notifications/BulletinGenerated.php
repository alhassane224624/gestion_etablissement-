<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Bulletin;

class BulletinGenerated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Bulletin $bulletin
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Bulletin disponible',
            'message' => sprintf(
                'Votre bulletin pour la période %s est maintenant disponible.',
                $this->bulletin->periode->nom ?? 'Période'
            ),
            'bulletin_id' => $this->bulletin->id,
            'url' => route('stagiaire.bulletin'),
            'icon' => 'fas fa-file-pdf',
            'type' => 'info'
        ];
    }
}