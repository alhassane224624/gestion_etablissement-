<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Table des transactions de paiement
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Qui a traité le paiement
            
            // Informations du paiement
            $table->string('numero_transaction')->unique();
            $table->decimal('montant', 10, 2);
            $table->string('type_paiement'); // inscription, mensualite, examen, autre
            $table->string('methode_paiement'); // especes, carte, virement, cheque, mobile_money
            $table->string('statut')->default('en_attente'); // en_attente, valide, refuse, rembourse
            
            // Détails de la transaction
            $table->string('reference_externe')->nullable(); // ID transaction Stripe/PayPal
            $table->string('gateway')->nullable(); // stripe, paypal, cmi, cash
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Données supplémentaires
            
            // Dates importantes
            $table->date('date_paiement');
            $table->date('date_echeance')->nullable();
            $table->timestamp('valide_at')->nullable();
            $table->foreignId('valide_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Documents
            $table->string('recu_path')->nullable(); // Chemin du reçu PDF
            $table->string('justificatif_path')->nullable(); // Preuve de paiement uploadée
            
            // Notes
            $table->text('notes_admin')->nullable();
            $table->text('notes_stagiaire')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour recherches rapides
            $table->index(['stagiaire_id', 'statut']);
            $table->index(['date_paiement']);
            $table->index(['type_paiement']);
        });

        // Table des échéanciers de paiement
        Schema::create('echeanciers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annee_scolaires')->onDelete('cascade');
            
            $table->string('titre'); // "Septembre 2024", "Frais d'inscription", etc.
            $table->decimal('montant', 10, 2);
            $table->date('date_echeance');
            $table->string('statut')->default('impaye'); // impaye, paye_partiel, paye, en_retard
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('montant_restant', 10, 2);
            
            $table->boolean('notification_envoyee')->default(false);
            $table->timestamp('notification_sent_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['stagiaire_id', 'statut']);
            $table->index(['date_echeance']);
        });

        // Table de liaison paiements <-> échéanciers
        Schema::create('echeancier_paiement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('echeancier_id')->constrained()->onDelete('cascade');
            $table->foreignId('paiement_id')->constrained()->onDelete('cascade');
            $table->decimal('montant_affecte', 10, 2);
            $table->timestamps();
        });

        // Table des remises/réductions
        Schema::create('remises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->string('titre');
            $table->string('type'); // pourcentage, montant_fixe
            $table->decimal('valeur', 10, 2);
            $table->text('motif');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });

        // Table des configurations de paiement
        Schema::create('configuration_paiements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, json, encrypted
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Ajouter des colonnes à la table stagiaires
        Schema::table('stagiaires', function (Blueprint $table) {
            $table->decimal('total_a_payer', 10, 2)->default(0)->after('frais_inscription');
            $table->decimal('total_paye', 10, 2)->default(0)->after('total_a_payer');
            $table->decimal('solde_restant', 10, 2)->default(0)->after('total_paye');
            $table->string('statut_paiement')->default('en_attente')->after('solde_restant'); 
            // en_attente, a_jour, en_retard, suspendu
        });
    }

    public function down()
    {
        Schema::table('stagiaires', function (Blueprint $table) {
            $table->dropColumn(['total_a_payer', 'total_paye', 'solde_restant', 'statut_paiement']);
        });
        
        Schema::dropIfExists('configuration_paiements');
        Schema::dropIfExists('remises');
        Schema::dropIfExists('echeancier_paiement');
        Schema::dropIfExists('echeanciers');
        Schema::dropIfExists('paiements');
    }
};