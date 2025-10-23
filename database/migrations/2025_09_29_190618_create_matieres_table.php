<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// =============================================================================
// MIGRATION 10: 2025_09_29_100000_create_matieres_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('matieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code', 10)->unique();
            $table->integer('coefficient')->default(1);
            $table->string('couleur', 7)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('matieres');
    }
};
