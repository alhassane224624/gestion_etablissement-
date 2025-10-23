<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 22: 2025_09_29_100011_create_bulletins_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('periode_id')->constrained()->onDelete('cascade');
            $table->decimal('moyenne_generale', 4, 2)->nullable();
            $table->integer('rang')->nullable();
            $table->integer('total_classe');
            $table->text('appreciation_generale')->nullable();
            $table->json('appreciations_matieres')->nullable();
            $table->json('moyennes_matieres')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['stagiaire_id', 'periode_id']);
            $table->index(['classe_id', 'periode_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bulletins');
    }
};

