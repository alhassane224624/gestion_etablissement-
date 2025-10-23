<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 7: 2025_09_26_142428_create_annee_scolaires_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('annee_scolaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('debut');
            $table->date('fin');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('annee_scolaires');
    }
};