<?php

// Console/Commands/SendDailySummaryCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Stagiaire;
use App\Models\Note;
use App\Models\Absence;
use App\Notifications\DailySummary;

class SendDailySummaryCommand extends Command
{
    protected $signature = 'summary:daily';
    protected $description = 'Envoyer le résumé quotidien aux administrateurs';

    public function handle()
    {
        $admins = User::where('role', 'administrateur')->get();

        $summary = [
            'nouveaux_stagiaires' => Stagiaire::whereDate('created_at', today())->count(),
            'notes_ajoutees' => Note::whereDate('created_at', today())->count(),
            'absences_jour' => Absence::whereDate('date', today())->count(),
            'absences_non_justifiees' => Absence::whereDate('date', today())
                ->where('justifiee', false)->count(),
        ];

        foreach ($admins as $admin) {
            $admin->notify(new DailySummary($summary));
        }

        $this->info('Résumé quotidien envoyé à ' . $admins->count() . ' administrateur(s).');

        return 0;
    }
}
