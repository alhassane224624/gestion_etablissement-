<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// =============================================================================
// MIGRATION 17: 2025_09_26_195055_create_plannings_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('type_cours', ['cours', 'td', 'tp', 'examen']);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->enum('statut', ['brouillon', 'valide', 'en_cours', 'termine', 'annule'])->default('brouillon');
            $table->text('motif_annulation')->nullable();
            $table->timestamps();

            $table->index(['date', 'heure_debut', 'heure_fin']);
            $table->index(['professeur_id', 'date']);
            $table->index(['classe_id', 'date']);
            $table->index(['salle_id', 'date']);
            $table->index(['created_by', 'statut']);
            $table->index(['validated_by', 'validated_at']);
            $table->index(['professeur_id', 'date', 'heure_debut', 'heure_fin', 'statut'], 'idx_planning_prof_conflict');
            $table->index(['salle_id', 'date', 'heure_debut', 'heure_fin', 'statut'], 'idx_planning_salle_conflict');
        });

        // Créer les triggers pour vérifier les conflits
        DB::unprepared("
            CREATE TRIGGER check_prof_conflict_before_insert
            BEFORE INSERT ON plannings
            FOR EACH ROW
            BEGIN
                IF NEW.statut IN ('valide', 'en_cours') THEN
                    IF EXISTS (
                        SELECT 1 FROM plannings 
                        WHERE professeur_id = NEW.professeur_id 
                        AND date = NEW.date 
                        AND statut IN ('valide', 'en_cours')
                        AND (
                            (NEW.heure_debut >= heure_debut AND NEW.heure_debut < heure_fin)
                            OR (NEW.heure_fin > heure_debut AND NEW.heure_fin <= heure_fin)
                            OR (NEW.heure_debut <= heure_debut AND NEW.heure_fin >= heure_fin)
                        )
                    ) THEN
                        SIGNAL SQLSTATE '45000' 
                        SET MESSAGE_TEXT = 'Conflit de planning pour ce professeur';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER check_prof_conflict_before_update
            BEFORE UPDATE ON plannings
            FOR EACH ROW
            BEGIN
                IF NEW.statut IN ('valide', 'en_cours') THEN
                    IF EXISTS (
                        SELECT 1 FROM plannings 
                        WHERE id != NEW.id
                        AND professeur_id = NEW.professeur_id 
                        AND date = NEW.date 
                        AND statut IN ('valide', 'en_cours')
                        AND (
                            (NEW.heure_debut >= heure_debut AND NEW.heure_debut < heure_fin)
                            OR (NEW.heure_fin > heure_debut AND NEW.heure_fin <= heure_fin)
                            OR (NEW.heure_debut <= heure_debut AND NEW.heure_fin >= heure_fin)
                        )
                    ) THEN
                        SIGNAL SQLSTATE '45000' 
                        SET MESSAGE_TEXT = 'Conflit de planning pour ce professeur';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER check_salle_conflict_before_insert
            BEFORE INSERT ON plannings
            FOR EACH ROW
            BEGIN
                IF NEW.statut IN ('valide', 'en_cours') THEN
                    IF EXISTS (
                        SELECT 1 FROM plannings 
                        WHERE salle_id = NEW.salle_id 
                        AND date = NEW.date 
                        AND statut IN ('valide', 'en_cours')
                        AND (
                            (NEW.heure_debut >= heure_debut AND NEW.heure_debut < heure_fin)
                            OR (NEW.heure_fin > heure_debut AND NEW.heure_fin <= heure_fin)
                            OR (NEW.heure_debut <= heure_debut AND NEW.heure_fin >= heure_fin)
                        )
                    ) THEN
                        SIGNAL SQLSTATE '45000' 
                        SET MESSAGE_TEXT = 'Conflit de planning pour cette salle';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER check_salle_conflict_before_update
            BEFORE UPDATE ON plannings
            FOR EACH ROW
            BEGIN
                IF NEW.statut IN ('valide', 'en_cours') THEN
                    IF EXISTS (
                        SELECT 1 FROM plannings 
                        WHERE id != NEW.id
                        AND salle_id = NEW.salle_id 
                        AND date = NEW.date 
                        AND statut IN ('valide', 'en_cours')
                        AND (
                            (NEW.heure_debut >= heure_debut AND NEW.heure_debut < heure_fin)
                            OR (NEW.heure_fin > heure_debut AND NEW.heure_fin <= heure_fin)
                            OR (NEW.heure_debut <= heure_debut AND NEW.heure_fin >= heure_fin)
                        )
                    ) THEN
                        SIGNAL SQLSTATE '45000' 
                        SET MESSAGE_TEXT = 'Conflit de planning pour cette salle';
                    END IF;
                END IF;
            END
        ");
    }

    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS check_prof_conflict_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS check_prof_conflict_before_update");
        DB::unprepared("DROP TRIGGER IF EXISTS check_salle_conflict_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS check_salle_conflict_before_update");
        Schema::dropIfExists('plannings');
    }
};