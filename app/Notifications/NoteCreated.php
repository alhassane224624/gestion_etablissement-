<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Note;

class NoteCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Note $note
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Nouvelle note ajoutée',
            'message' => sprintf(
                'Une note de %.2f/20 a été ajoutée en %s.',
                $this->note->note,
                $this->note->matiere->nom ?? 'Matière'
            ),
            'note_id' => $this->note->id,
            'stagiaire_id' => $this->note->stagiaire_id,
            'url' => route('stagiaire.notes'),
            'icon' => 'fas fa-file-alt',
            'type' => $this->note->note >= 10 ? 'success' : 'warning'
        ];
    }
}