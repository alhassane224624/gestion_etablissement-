<?php
// ============================================================================
// DatabaseSeeder.php - POINT D'ENTRÉE PRINCIPAL (ORDRE CORRECT)
// ============================================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Exécuter les seeders dans l'ordre correct
     * ⚠️ L'ordre est CRITIQUE - respecter les dépendances!
     */
    public function run(): void
    {
        $this->command->line('');
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->line('  🌱 DÉMARRAGE DU PROCESSUS DE REMPLISSAGE DE LA BDD');
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->line('');

        // 1️⃣ Utilisateurs (Admin, Professeurs, etc.)
        // ⚠️ DOIT ÊTRE PREMIER - les autres seeders ont besoin de l'admin
        $this->command->line('1️⃣  Création des utilisateurs...');
        $this->call(UserSeeder::class);
        $this->command->line('');

        // 2️⃣ Filières
        // ⚠️ Doit être avant les niveaux et classes
        $this->command->line('2️⃣  Création des filières...');
        $this->call(FiliereSeeder::class);
        $this->command->line('');

        // 3️⃣ Niveaux
        // ⚠️ Dépend des filières
        $this->command->line('3️⃣  Création des niveaux...');
        $this->call(NiveauSeeder::class);
        $this->command->line('');

        // 4️⃣ Matières
        // ⚠️ Doit être avant l'assignation des professeurs
        $this->command->line('4️⃣  Création des matières...');
        $this->call(MatiereSeeder::class);
        $this->command->line('');

        // 5️⃣ Années scolaires
        // ⚠️ IMPORTANT! Doit être AVANT les classes (qui en ont besoin)
        $this->command->line('5️⃣  Création des années scolaires...');
        $this->call(AnneeScolaireSeeder::class);
        $this->command->line('');

        // 6️⃣ Périodes
        // ⚠️ Dépend des années scolaires
        $this->command->line('6️⃣  Création des périodes...');
        $this->call(PeriodeSeeder::class);
        $this->command->line('');

        // 7️⃣ Classes
        // ⚠️ Dépend des niveaux, filières, et années scolaires
        $this->command->line('7️⃣  Création des classes...');
        $this->call(ClasseSeeder::class);
        $this->command->line('');

        // 8️⃣ Salles
        $this->command->line('8️⃣  Création des salles...');
        $this->call(SalleSeeder::class);
        $this->command->line('');

        // 9️⃣ Stagiaires
        // ⚠️ Dépend des classes, niveaux, filières
        $this->command->line('9️⃣  Création des stagiaires...');
        $this->call(StagiaireSeeder::class);
        $this->command->line('');

        // 🔟 Assignation des professeurs
        // ⚠️ DOIT ÊTRE APRÈS les filières, niveaux, matières et utilisateurs
        $this->command->line('🔟 Assignation des professeurs aux filières et matières...');
        $this->call(AssignProfesseursSeeder::class);
        $this->command->line('');

        // 1️⃣1️⃣ Notes
        // ⚠️ Dépend des stagiaires, matières, périodes, classes
        $this->command->line('1️⃣1️⃣ Création des notes...');
        $this->call(NoteSeeder::class);
        $this->command->line('');

        // 1️⃣2️⃣ Absences
        // ⚠️ Dépend des stagiaires
        $this->command->line('1️⃣2️⃣ Création des absences...');
        $this->call(AbsenceSeeder::class);
        $this->command->line('');

        // 1️⃣3️⃣ Plannings
        // ⚠️ Dépend des professeurs, salles, matières, classes
        $this->command->line('1️⃣3️⃣ Création des plannings...');
        $this->call(PlanningSeeder::class);
        $this->command->line('');

        // 1️⃣4️⃣ Bulletins
        // ⚠️ Dépend des stagiaires, périodes
        $this->command->line('1️⃣4️⃣ Création des bulletins...');
        $this->call(BulletinSeeder::class);
        $this->command->line('');

        // 1️⃣5️⃣ Messages
        $this->command->line('1️⃣5️⃣ Création des messages...');
        $this->call(MessageSeeder::class);
        $this->command->line('');

        // ✅ FIN
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->info('✅ REMPLISSAGE COMPLET DE LA BDD RÉUSSI!');
        $this->command->line('═══════════════════════════════════════════════════════════');
        $this->command->line('');
        $this->displayLoginCredentials();
        $this->command->line('');
    }

    /**
     * Afficher les identifiants de connexion
     */
    private function displayLoginCredentials(): void
    {
        $this->command->line('🔐 IDENTIFIANTS DE CONNEXION:');
        $this->command->line('');
        $this->command->line('👨‍💼 ADMINISTRATEUR:');
        $this->command->line('   Email:    admin@emsi.ma');
        $this->command->line('   Mot de passe: password123');
        $this->command->line('');
        $this->command->line('👨‍🏫 PROFESSEURS:');
        $this->command->line('   ahmed.bennani@emsi.ma');
        $this->command->line('   fatima.karim@emsi.ma');
        $this->command->line('   hassan.idrissi@emsi.ma');
        $this->command->line('   laila.moumine@emsi.ma');
        $this->command->line('   karim.aziz@emsi.ma');
        $this->command->line('   Mot de passe: password123 (pour tous)');
        $this->command->line('');
        $this->command->line('📊 STATISTIQUES CRÉÉES:');
        $this->command->line('   • 1 Administrateur + 5 Professeurs');
        $this->command->line('   • 7 Filières avec 2 niveaux chacune');
        $this->command->line('   • 14 Classes');
        $this->command->line('   • 10 Matières');
        $this->command->line('   • 8 Salles');
        $this->command->line('   • ~350 Stagiaires');
        $this->command->line('   • ~2800 Notes');
        $this->command->line('   • ~350 Absences');
        $this->command->line('   • ~210 Plannings');
        $this->command->line('   • ~350 Bulletins');
        $this->command->line('   • 50 Messages');
        $this->command->line('');
    }
}