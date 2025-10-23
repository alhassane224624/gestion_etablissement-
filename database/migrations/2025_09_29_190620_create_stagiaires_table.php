<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// =============================================================================
// MIGRATION 13: 2025_03_30_164210_create_stagiaires_table.php
// =============================================================================
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stagiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->unique();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('adresse')->nullable();
            $table->string('nom_tuteur')->nullable();
            $table->string('telephone_tuteur', 20)->nullable();
            $table->string('email_tuteur')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->foreignId('classe_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('niveau_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date_inscription')->default(DB::raw('CURRENT_DATE'));
            $table->enum('statut', ['actif', 'suspendu', 'diplome', 'abandonne', 'transfere'])->default('actif');
            $table->text('motif_statut')->nullable();
            $table->decimal('frais_inscription', 10, 2)->nullable();
            $table->boolean('frais_payes')->default(false);
            $table->timestamps();

            $table->index(['created_by', 'is_active']);
            $table->index(['classe_id', 'statut']);
            $table->index(['niveau_id', 'filiere_id']);
            $table->index(['statut', 'date_inscription']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stagiaires');
    }
};