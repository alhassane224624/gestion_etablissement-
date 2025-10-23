<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 19: 2025_09_29_100005_create_professeur_matiere_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('professeur_matiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->date('date_assignation')->default(DB::raw('CURRENT_DATE'));
            $table->text('competences')->nullable();
            $table->timestamps();

            $table->unique(['professeur_id', 'matiere_id', 'filiere_id']);
            $table->index(['professeur_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('professeur_matiere');
    }
};