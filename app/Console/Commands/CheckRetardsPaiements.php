<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Echeancier;
use App\Models\Stagiaire;
use App\Notifications\RappelEcheanceNotification;
use App\Notifications\RetardPaiementNotification;

class CheckRetardsPaiements extends Command
{
    protected $signature = 'paiements:check-retards';
    protected $description = 'Vérifier les retards de paiement et envoyer des notifications';

    public function handle()
    {
        $this->info('🔍 Vérification des retards de paiement...');

        // 1. Mettre à jour les échéanciers en retard
        $echeanciersRetard = Echeancier::where('date_echeance', '<', now())
            ->whereIn('statut', ['impaye', 'paye_partiel'])
            ->get();

        $countRetards = 0;
        foreach ($echeanciersRetard as $echeancier) {
            if ($echeancier->statut !== 'en_retard') {
                $echeancier->update(['statut' => 'en_retard']);
                $countRetards++;
                
                // Notifier le stagiaire
                if ($echeancier->stagiaire->user) {
                    $echeancier->stagiaire->user->notify(
                        new RetardPaiementNotification($echeancier)
                    );
                }
            }
        }

        $this->info("✅ {$countRetards} échéanciers marqués en retard");

        // 2. Envoyer des rappels pour les échéances proches
        $delaiRappel = \App\Models\ConfigurationPaiement::get('delai_rappel_echeance', 7);
        $dateRappel = now()->addDays($delaiRappel);

        $echeanciersAVenir = Echeancier::whereBetween('date_echeance', [now(), $dateRappel])
            ->whereIn('statut', ['impaye', 'paye_partiel'])
            ->where('notification_envoyee', false)
            ->get();

        $countRappels = 0;
        foreach ($echeanciersAVenir as $echeancier) {
            if ($echeancier->stagiaire->user) {
                $echeancier->stagiaire->user->notify(
                    new RappelEcheanceNotification($echeancier)
                );
                
                $echeancier->update([
                    'notification_envoyee' => true,
                    'notification_sent_at' => now(),
                ]);
                
                $countRappels++;
            }
        }

        $this->info("📧 {$countRappels} rappels d'échéance envoyés");

        // 3. Mettre à jour les statuts de paiement des stagiaires
        $stagiaires = Stagiaire::all();
        foreach ($stagiaires as $stagiaire) {
            $stagiaire->updateSoldePaiement();
        }

        $this->info("✅ Statuts des stagiaires mis à jour");

        // 4. Vérifier les suspensions automatiques
        $maxRetard = \App\Models\ConfigurationPaiement::get('max_retard_avant_suspension', 60);
        $dateLimite = now()->subDays($maxRetard);

        $stagiairesSuspendre = Stagiaire::whereHas('echeanciers', function($q) use ($dateLimite) {
            $q->where('statut', 'en_retard')
              ->where('date_echeance', '<', $dateLimite);
        })
        ->where('statut_paiement', '!=', 'suspendu')
        ->get();

        $countSuspensions = 0;
        foreach ($stagiairesSuspendre as $stagiaire) {
            $stagiaire->update([
                'statut_paiement' => 'suspendu',
                'statut' => 'suspendu',
                'motif_statut' => 'Suspension automatique - Retard de paiement supérieur à ' . $maxRetard . ' jours'
            ]);
            $countSuspensions++;
        }

        $this->info("⚠️ {$countSuspensions} stagiaires suspendus pour retard de paiement");

        $this->info('✅ Vérification terminée avec succès !');
        
        return Command::SUCCESS;
    }
}