<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 20: 2025_04_04_165056_create_matiere_filiere_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('matiere_filiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['filiere_id', 'matiere_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('matiere_filiere');
    }
};