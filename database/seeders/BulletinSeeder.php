<?php
// ============================================================================
// BulletinSeeder.php - Version Corrigée
// ============================================================================

namespace Database\Seeders;

use App\Models\Bulletin;
use App\Models\Stagiaire;
use App\Models\Periode;
use App\Models\Matiere;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BulletinSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $stagiaires = Stagiaire::with('classe')->get();
        $periode = Periode::first();

        if (!$periode) {
            echo "❌ Aucune période trouvée. Veuillez d'abord créer une période.\n";
            return;
        }

        // Récupérer toutes les matières
        $matieres = Matiere::all();
        
        if ($matieres->isEmpty()) {
            echo "❌ Aucune matière trouvée. Veuillez d'abord créer des matières.\n";
            return;
        }

        $bulletinCount = 0;
        
        foreach ($stagiaires as $stagiaire) {
            // Vérifier que le stagiaire a une classe
            if (!$stagiaire->classe_id) {
                continue;
            }

            // ✅ Générer les moyennes par matière
            $moyennesParMatiere = [];
            $totalPoints = 0;
            $totalCoefficients = 0;

            // Prendre 5-8 matières aléatoires pour ce stagiaire
            $matieresEleve = $matieres->random(min(rand(5, 8), $matieres->count()));
            
            foreach ($matieresEleve as $matiere) {
                $moyenne = round($faker->numberBetween(50, 200) / 10, 2); // Entre 5 et 20
                
                $moyennesParMatiere[] = [
                    'matiere' => $matiere->nom,
                    'code' => $matiere->code,
                    'coefficient' => $matiere->coefficient,
                    'moyenne' => $moyenne,
                    'note_sur' => 20
                ];

                $totalPoints += $moyenne * $matiere->coefficient;
                $totalCoefficients += $matiere->coefficient;
            }

            // Calculer la moyenne générale
            $moyenneGenerale = $totalCoefficients > 0 
                ? round($totalPoints / $totalCoefficients, 2) 
                : 0;

            // Générer l'appréciation selon la moyenne
            $appreciation = $this->genererAppreciation($moyenneGenerale);

            // Créer le bulletin
            $bulletin = Bulletin::create([
                'stagiaire_id' => $stagiaire->id,
                'classe_id' => $stagiaire->classe_id,
                'periode_id' => $periode->id,
                'moyenne_generale' => $moyenneGenerale,
                'rang' => rand(1, 30),
                'total_classe' => 30,
                'appreciation_generale' => $appreciation,
                'moyennes_matieres' => $moyennesParMatiere, // ✅ IMPORTANT
                'created_by' => 1,
                'validated_by' => rand(0, 1) ? 1 : null,
                'validated_at' => rand(0, 1) ? now() : null,
            ]);
            
            $bulletinCount++;
        }

        echo "✅ $bulletinCount bulletins créés avec succès\n";
    }

    /**
     * Générer une appréciation selon la moyenne
     */
    private function genererAppreciation(float $moyenne): string
    {
        if ($moyenne >= 16) {
            return collect([
                'Excellent travail ! Continuez ainsi.',
                'Résultats exceptionnels. Félicitations !',
                'Performance remarquable. Bravo !',
                'Travail exemplaire tout au long de la période.',
            ])->random();
        } elseif ($moyenne >= 14) {
            return collect([
                'Très bon travail. Félicitations !',
                'Résultats très satisfaisants.',
                'Bons résultats. Continuez vos efforts.',
                'Travail sérieux et régulier.',
            ])->random();
        } elseif ($moyenne >= 12) {
            return collect([
                'Bon travail dans l\'ensemble.',
                'Résultats corrects. Peut encore progresser.',
                'Travail satisfaisant. Continuez.',
                'Bons efforts fournis.',
            ])->random();
        } elseif ($moyenne >= 10) {
            return collect([
                'Travail satisfaisant. Peut mieux faire.',
                'Résultats justes. Plus de rigueur nécessaire.',
                'Travail acceptable mais insuffisant dans certaines matières.',
                'Des efforts supplémentaires sont attendus.',
            ])->random();
        } else {
            return collect([
                'Travail insuffisant. Des efforts importants sont nécessaires.',
                'Résultats préoccupants. Un sérieux redressement s\'impose.',
                'Lacunes importantes. Nécessite un travail soutenu.',
                'Résultats très faibles. Attention !',
            ])->random();
        }
    }
}