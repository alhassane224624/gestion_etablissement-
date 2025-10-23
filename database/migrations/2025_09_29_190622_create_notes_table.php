<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 15: 2025_03_31_002704_create_notes_table.php
// =============================================================================
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->nullable()->constrained()->onDelete('cascade');
            $table->float('note');
            $table->string('semestre')->nullable();
            $table->foreignId('periode_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type_note', ['ds', 'cc', 'examen', 'tp', 'projet'])->default('ds');
            $table->decimal('note_sur', 3, 1)->default(20);
            $table->text('commentaire')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index(['matiere_id', 'classe_id']);
            $table->index(['stagiaire_id', 'matiere_id']);
            $table->index(['periode_id', 'stagiaire_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};