<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 12: 2025_09_29_100002_create_classes_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained()->onDelete('cascade');
            $table->integer('effectif_max')->default(30);
            $table->integer('effectif_actuel')->default(0);
            $table->timestamps();

            $table->unique(['nom', 'annee_scolaire_id']);
            $table->index(['niveau_id', 'filiere_id']);
        });

        // Ajouter la contrainte CHECK
        DB::statement("
            ALTER TABLE classes ADD CONSTRAINT check_effectif 
            CHECK (effectif_actuel <= effectif_max)
        ");
    }

    public function down()
    {
        DB::statement("ALTER TABLE classes DROP CONSTRAINT IF EXISTS check_effectif");
        Schema::dropIfExists('classes');
    }
};