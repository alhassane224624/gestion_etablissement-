<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 16: 2025_09_26_150940_create_absences_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin')->nullable();
            $table->enum('type', ['matin', 'apres_midi', 'journee', 'heure']);
            $table->text('motif')->nullable();
            $table->boolean('justifiee')->default(false);
            $table->string('document_justificatif')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['stagiaire_id', 'date']);
            $table->index(['date', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('absences');
    }
};
