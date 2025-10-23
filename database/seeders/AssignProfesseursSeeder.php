<?php
// =============================================================================
// FICHIER 15: AssignProfesseursSeeder.php (AMÉLIORÉ ET SÉCURISÉ)
// =============================================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Matiere;
use Illuminate\Support\Facades\DB;

class AssignProfesseursSeeder extends Seeder
{
    /**
     * Mapper les spécialités des professeurs aux codes de matières
     * que nous utilisons dans le système
     */
    private function getSpecialiteMatiereMapping(): array
    {
        return [
            'Mathématiques' => ['MAT101'],
            'Physique' => ['PHY101'],
            'Informatique' => ['INF101', 'PRG101', 'BDD101'],
            'Chimie' => ['CHI101'],
            'Français' => ['FRA101'],
            'Anglais' => ['ANG101'],
        ];
    }

    public function run(): void
    {
        $this->command->line('');
        $this->command->line('🔄 Assignation des professeurs aux filières et matières...');
        $this->command->line('');

        try {
            // Récupérer les données
            $professeurs = User::where('role', 'professeur')->get();
            $filieres = Filiere::all();
            $admin = User::where('role', 'administrateur')->first();

            // Vérifications de sécurité
            if ($professeurs->isEmpty()) {
                $this->command->warn('⚠️  Aucun professeur trouvé. Exécutez d\'abord UserSeeder.');
                return;
            }

            if ($filieres->isEmpty()) {
                $this->command->warn('⚠️  Aucune filière trouvée. Exécutez d\'abord FiliereSeeder.');
                return;
            }

            if (!$admin) {
                $this->command->warn('⚠️  Aucun administrateur trouvé.');
                return;
            }

            $specialiteMatieres = $this->getSpecialiteMatiereMapping();
            $assignmentCount = 0;
            $matiereCount = 0;

            // Boucle sur chaque professeur
            foreach ($professeurs as $professeur) {
                $specialite = $professeur->specialite ?? 'Informatique';

                // === ASSIGNER AUX FILIÈRES ===
                $filieresAssignees = $filieres->random(rand(1, 2));

                foreach ($filieresAssignees as $filiere) {
                    // Vérifier si l'assignation n'existe pas déjà
                    if (!$professeur->filieres()->where('filiere_id', $filiere->id)->exists()) {
                        $professeur->filieres()->attach($filiere->id, [
                            'created_by' => $admin->id,
                            'is_active' => true,
                            'date_assignation' => now(),
                            'remarques' => "Assigné à {$filiere->nom}",
                        ]);

                        $assignmentCount++;

                        $this->command->info(
                            "✅ {$professeur->name} assigné à {$filiere->nom}"
                        );
                    }
                }

                // === ASSIGNER LES MATIÈRES ===
                if (isset($specialiteMatieres[$specialite])) {
                    $codesMatieres = $specialiteMatieres[$specialite];
                    $matieres = Matiere::whereIn('code', $codesMatieres)->get();

                    if ($matieres->isEmpty()) {
                        $this->command->warn(
                            "⚠️  Aucune matière trouvée pour la spécialité: {$specialite}"
                        );
                        continue;
                    }

                    foreach ($matieres as $matiere) {
                        foreach ($filieresAssignees as $filiere) {
                            // Vérifier si l'assignation n'existe pas déjà
                            $exists = $professeur->matieresEnseignees()
                                ->where('matiere_id', $matiere->id)
                                ->where('filiere_id', $filiere->id)
                                ->exists();

                            if (!$exists) {
                                $professeur->matieresEnseignees()->attach($matiere->id, [
                                    'filiere_id' => $filiere->id,
                                    'assigned_by' => $admin->id,
                                    'is_active' => true,
                                    'date_assignation' => now(),
                                    'competences' => "Spécialiste en {$specialite}",
                                ]);

                                $matiereCount++;

                                $this->command->info(
                                    "   📚 {$matiere->nom} ({$matiere->code}) assignée"
                                );
                            }
                        }
                    }
                } else {
                    $this->command->warn(
                        "⚠️  Spécialité inconnue pour {$professeur->name}: {$specialite}"
                    );
                }
            }

            // === RÉSUMÉ ===
            $this->command->line('');
            $this->command->line('📊 RÉSUMÉ DE L\'ASSIGNATION:');
            $this->command->line("   • Professeurs assignés aux filières: {$assignmentCount}");
            $this->command->line("   • Matières assignées: {$matiereCount}");
            $this->command->line('');
            $this->command->info('✅ Assignation complétée avec succès!');
            $this->command->line('');

        } catch (\Exception $e) {
            $this->command->error('❌ Erreur lors de l\'assignation: ' . $e->getMessage());
            throw $e;
        }
    }
}