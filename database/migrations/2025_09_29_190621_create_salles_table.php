<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 14: 2025_09_26_195041_create_salles_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('capacite');
            $table->enum('type', ['amphitheatre', 'salle_cours', 'laboratoire', 'salle_informatique']);
            $table->json('equipements')->nullable();
            $table->boolean('disponible')->default(true);
            $table->string('batiment')->nullable();
            $table->string('etage')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salles');
    }
};
