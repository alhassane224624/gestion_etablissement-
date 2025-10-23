<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Commandes Artisan personnalisées.
     */
    protected $commands = [
        \App\Console\Commands\CheckRetardsPaiements::class,
    ];

    /**
     * Définir le planning des tâches planifiées.
     */
    protected function schedule(Schedule $schedule)
    {
        // Vérifie les retards de paiement chaque jour à 8h00
        $schedule->command('paiements:check-retards')
            ->dailyAt('08:00')
            ->emailOutputOnFailure('admin@emsi.ma');

        // Nettoie les anciennes notifications tous les dimanches à 2h00
        $schedule->command('notifications:clear-old')
            ->weekly()
            ->sundays()
            ->at('02:00');
    }

    /**
     * Enregistre les commandes pour l'application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
