<?php
// Console/Commands/CleanOldNotificationsCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanOldNotificationsCommand extends Command
{
    protected $signature = 'notifications:clean {--days=30 : Nombre de jours à garder}';
    protected $description = 'Nettoyer les anciennes notifications';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $deleted = DB::table('notifications')
            ->where('created_at', '<', $cutoffDate)
            ->delete();

        $this->info("Supprimé {$deleted} notifications de plus de {$days} jours.");

        return 0;
    }
}