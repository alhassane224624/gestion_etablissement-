<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 8: 2025_09_26_144253_create_periodes_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type', ['semestre', 'trimestre', 'periode']);
            $table->date('debut');
            $table->date('fin');
            $table->foreignId('annee_scolaire_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periodes');
    }
};