<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 21: 2025_09_29_100006_create_matiere_niveau_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('matiere_niveau', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->integer('heures_cours')->nullable();
            $table->boolean('is_obligatoire')->default(true);
            $table->timestamps();

            $table->unique(['matiere_id', 'niveau_id']);
            $table->index(['niveau_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('matiere_niveau');
    }
};