<?php

// Console/Commands/CheckAbsencesCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absence;
use App\Models\User;
use App\Notifications\UnjustifiedAbsenceReminder;
use Carbon\Carbon;

class CheckAbsencesCommand extends Command
{
    protected $signature = 'absences:check-unjustified';
    protected $description = 'Vérifier et rappeler les absences non justifiées';

    public function handle()
    {
        // Absences de plus de 48h non justifiées
        $cutoffDate = Carbon::now()->subHours(48);
        
        $absences = Absence::where('justifiee', false)
            ->where('created_at', '<', $cutoffDate)
            ->whereDoesntHave('notifications', function($query) {
                $query->where('type', UnjustifiedAbsenceReminder::class);
            })
            ->with(['stagiaire'])
            ->get();

        $admins = User::where('role', 'administrateur')->get();

        foreach ($absences as $absence) {
            foreach ($admins as $admin) {
                $admin->notify(new UnjustifiedAbsenceReminder($absence));
            }
        }

        $this->info('Vérification terminée. ' . $absences->count() . ' absences non justifiées signalées.');

        return 0;
    }
}
