<?php

namespace App\Console\Commands;

use App\Models\Echeancier;
use App\Notifications\RappelEcheanceNotification;
use App\Notifications\RetardPaiementNotification;
use Illuminate\Console\Command;

class EnvoyerRappelsPaiements extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'paiements:rappels {--force : Forcer l\'envoi même si déjà envoyé}';

    /**
     * The console command description.
     */
    protected $description = 'Envoie des rappels automatiques pour les échéances à venir et les retards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 Démarrage de l\'envoi des rappels...');

        // 1. Rappels pour échéances dans 7 jours
        $this->envoyerRappelsEcheancesProches();

        // 2. Rappels pour échéances dans 3 jours
        $this->envoyerRappelsEcheancesUrgentes();

        // 3. Notifications de retard
        $this->envoyerNotificationsRetard();

        // 4. Mettre à jour les statuts
        $this->mettreAJourStatuts();

        $this->info('✅ Envoi des rappels terminé avec succès !');
    }

    /**
     * Envoie des rappels pour les échéances dans 7 jours
     */
    private function envoyerRappelsEcheancesProches()
    {
        $echeanciers = Echeancier::with('stagiaire.user')
            ->where('statut', 'impaye')
            ->whereBetween('date_echeance', [
                now()->addDays(6),
                now()->addDays(7)
            ])
            ->where(function($q) {
                if (!$this->option('force')) {
                    $q->where('notification_envoyee', false)
                      ->orWhereNull('notification_sent_at');
                }
            })
            ->get();

        $count = 0;
        foreach ($echeanciers as $echeancier) {
            if ($echeancier->stagiaire->user) {
                $echeancier->stagiaire->user->notify(
                    new RappelEcheanceNotification($echeancier)
                );

                $echeancier->update([
                    'notification_envoyee' => true,
                    'notification_sent_at' => now(),
                ]);

                $count++;
            }
        }

        $this->line("📧 Rappels 7 jours : {$count} notifications envoyées");
    }

    /**
     * Envoie des rappels urgents pour les échéances dans 3 jours
     */
    private function envoyerRappelsEcheancesUrgentes()
    {
        $echeanciers = Echeancier::with('stagiaire.user')
            ->where('statut', 'impaye')
            ->whereBetween('date_echeance', [
                now()->addDays(2),
                now()->addDays(3)
            ])
            ->get();

        $count = 0;
        foreach ($echeanciers as $echeancier) {
            if ($echeancier->stagiaire->user) {
                $echeancier->stagiaire->user->notify(
                    new RappelEcheanceNotification($echeancier)
                );
                $count++;
            }
        }

        $this->line("⚠️  Rappels 3 jours : {$count} notifications envoyées");
    }

    /**
     * Envoie des notifications pour les paiements en retard
     */
    private function envoyerNotificationsRetard()
    {
        $echeanciers = Echeancier::with('stagiaire.user')
            ->whereIn('statut', ['impaye', 'paye_partiel'])
            ->where('date_echeance', '<', now())
            ->get();

        // Mettre à jour le statut en retard
        foreach ($echeanciers as $echeancier) {
            $echeancier->update(['statut' => 'en_retard']);
        }

        // Notifier les retards de plus de 3 jours
        $echeanciersRetard = $echeanciers->filter(function($e) {
            return now()->diffInDays($e->date_echeance) >= 3;
        });

        $count = 0;
        foreach ($echeanciersRetard as $echeancier) {
            if ($echeancier->stagiaire->user) {
                $echeancier->stagiaire->user->notify(
                    new RetardPaiementNotification($echeancier)
                );
                $count++;
            }
        }

        $this->line("🚨 Notifications retard : {$count} notifications envoyées");
    }

    /**
     * Met à jour les statuts de paiement des stagiaires
     */
    private function mettreAJourStatuts()
    {
        $this->info('🔄 Mise à jour des statuts de paiement...');

        $stagiaires = \App\Models\Stagiaire::actifs()
            ->has('echeanciers')
            ->get();

        $count = 0;
        foreach ($stagiaires as $stagiaire) {
            $stagiaire->updateSoldePaiement();
            $count++;
        }

        $this->line("✅ {$count} stagiaires mis à jour");
    }
}