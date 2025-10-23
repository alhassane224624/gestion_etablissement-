<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Planning;

class PlanningUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Planning $planning
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Emploi du temps modifié',
            'message' => sprintf(
                'Le cours de %s du %s a été modifié.',
                $this->planning->matiere->nom ?? 'Matière',
                $this->planning->date->format('d/m/Y')
            ),
            'planning_id' => $this->planning->id,
            'url' => route('stagiaire.emploi-du-temps'),
            'icon' => 'fas fa-calendar-alt',
            'type' => 'warning'
        ];
    }
}

