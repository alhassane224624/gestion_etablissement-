<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Absence;

class AbsenceCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Absence $absence
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Nouvelle absence enregistrée',
            'message' => sprintf(
                'Une absence a été enregistrée le %s pour %s.',
                $this->absence->date->format('d/m/Y'),
                $this->absence->matiere->nom ?? 'la matière'
            ),
            'absence_id' => $this->absence->id,
            'stagiaire_id' => $this->absence->stagiaire_id,
            'url' => route('stagiaire.absences'),
            'icon' => 'fas fa-calendar-times',
            'type' => 'warning'
        ];
    }
}