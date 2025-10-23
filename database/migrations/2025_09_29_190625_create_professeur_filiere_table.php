<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 18: 2025_09_29_100004_create_professeur_filiere_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('professeur_filiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->date('date_assignation')->default(DB::raw('CURRENT_DATE'));
            $table->text('remarques')->nullable();
            $table->timestamps();

            $table->unique(['professeur_id', 'filiere_id']);
            $table->index(['filiere_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('professeur_filiere');
    }
};