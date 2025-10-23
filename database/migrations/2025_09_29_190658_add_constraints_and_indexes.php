<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier et ajouter la contrainte CHECK seulement si elle n'existe pas
        $constraintExists = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'classes' 
            AND CONSTRAINT_NAME = 'check_effectif'
        ");

        if (empty($constraintExists)) {
            DB::statement("
                ALTER TABLE classes ADD CONSTRAINT check_effectif 
                CHECK (effectif_actuel <= effectif_max)
            ");
        }

        // Ajouter les index composites pour la détection de conflits
        Schema::table('plannings', function (Blueprint $table) {
            // Vérifier si l'index n'existe pas déjà
            if (!$this->indexExists('plannings', 'idx_planning_prof_conflict')) {
                $table->index(['professeur_id', 'date', 'heure_debut', 'heure_fin', 'statut'], 'idx_planning_prof_conflict');
            }
            if (!$this->indexExists('plannings', 'idx_planning_salle_conflict')) {
                $table->index(['salle_id', 'date', 'heure_debut', 'heure_fin', 'statut'], 'idx_planning_salle_conflict');
            }
        });

        // Supprimer les anciens triggers s'ils existent
        DB::unprepared("DROP TRIGGER IF EXISTS check_prof_conflict_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS check_prof_conflict_before_update");
        DB::unprepared("DROP TRIGGER IF EXISTS check_salle_conflict_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS check_salle_conflict_before_update");

        // Créer les triggers pour vérifier les conflits de planning
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
        // Supprimer les triggers
        DB::unprepared("DROP TRIGGER IF EXISTS check_prof_conflict_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS check_prof_conflict_before_update");
        DB::unprepared("DROP TRIGGER IF EXISTS check_salle_conflict_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS check_salle_conflict_before_update");

        // Supprimer les index
        Schema::table('plannings', function (Blueprint $table) {
            if ($this->indexExists('plannings', 'idx_planning_prof_conflict')) {
                $table->dropIndex('idx_planning_prof_conflict');
            }
            if ($this->indexExists('plannings', 'idx_planning_salle_conflict')) {
                $table->dropIndex('idx_planning_salle_conflict');
            }
        });

        // Supprimer la contrainte
        DB::statement("ALTER TABLE classes DROP CONSTRAINT IF EXISTS check_effectif");
    }

    /**
     * Vérifier si un index existe
     */
    private function indexExists($table, $index)
    {
        $result = DB::select("
            SHOW INDEX FROM {$table} WHERE Key_name = ?
        ", [$index]);
        
        return !empty($result);
    }
};