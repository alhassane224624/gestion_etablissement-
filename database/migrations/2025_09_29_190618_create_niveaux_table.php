<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 11: 2025_09_29_100001_create_niveaux_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->integer('ordre');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->integer('duree_semestres')->default(2);
            $table->timestamps();

            $table->unique(['filiere_id', 'ordre']);
            $table->index(['filiere_id', 'ordre']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('niveaux');
    }
};