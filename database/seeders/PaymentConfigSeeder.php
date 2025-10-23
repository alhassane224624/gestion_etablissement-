<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConfigurationPaiement;

class PaymentConfigSeeder extends Seeder
{
    public function run()
    {
        $configurations = [
            // Paramètres généraux
            [
                'key' => 'frais_inscription_defaut',
                'value' => '5000',
                'type' => 'string',
                'description' => 'Montant par défaut des frais d\'inscription (DH)'
            ],
            [
                'key' => 'mensualite_defaut',
                'value' => '1500',
                'type' => 'string',
                'description' => 'Montant par défaut de la mensualité (DH)'
            ],
            [
                'key' => 'validation_auto_especes',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Validation automatique des paiements en espèces'
            ],
            [
                'key' => 'delai_rappel_echeance',
                'value' => '7',
                'type' => 'string',
                'description' => 'Nombre de jours avant échéance pour envoyer un rappel'
            ],
            [
                'key' => 'max_retard_avant_suspension',
                'value' => '60',
                'type' => 'string',
                'description' => 'Nombre de jours de retard avant suspension automatique'
            ],
            
            // Notifications
            [
                'key' => 'notif_paiement_recu',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Envoyer une notification quand un paiement est reçu'
            ],
            [
                'key' => 'notif_paiement_valide',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Envoyer une notification quand un paiement est validé'
            ],
            [
                'key' => 'notif_rappel_echeance',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Envoyer des rappels d\'échéance'
            ],
            [
                'key' => 'notif_retard_paiement',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Envoyer des notifications de retard'
            ],
            
            // Informations établissement
            [
                'key' => 'etablissement_nom',
                'value' => 'École de Management et Systèmes d\'Information',
                'type' => 'string',
                'description' => 'Nom de l\'établissement'
            ],
            [
                'key' => 'etablissement_adresse',
                'value' => 'Tangier, Morocco',
                'type' => 'string',
                'description' => 'Adresse de l\'établissement'
            ],
            [
                'key' => 'etablissement_telephone',
                'value' => '+212 539 123 456',
                'type' => 'string',
                'description' => 'Téléphone de l\'établissement'
            ],
            [
                'key' => 'etablissement_email',
                'value' => 'contact@emsi.ma',
                'type' => 'string',
                'description' => 'Email de l\'établissement'
            ],
            
            // Coordonnées bancaires
            [
                'key' => 'rib_etablissement',
                'value' => '',
                'type' => 'string',
                'description' => 'RIB de l\'établissement'
            ],
            [
                'key' => 'banque_nom',
                'value' => '',
                'type' => 'string',
                'description' => 'Nom de la banque'
            ],
            
            // Textes pour reçus
            [
                'key' => 'recu_footer_text',
                'value' => 'Merci pour votre paiement. Ce reçu fait foi de paiement.',
                'type' => 'string',
                'description' => 'Texte de pied de page sur les reçus'
            ],
            [
                'key' => 'recu_conditions',
                'value' => 'Aucun remboursement ne sera effectué après validation de l\'inscription.',
                'type' => 'string',
                'description' => 'Conditions générales sur les reçus'
            ],
        ];

        foreach ($configurations as $config) {
            ConfigurationPaiement::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }

        $this->command->info('Configurations de paiement créées avec succès !');
    }
}