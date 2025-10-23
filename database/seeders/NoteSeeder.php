<?php
// ============================================================================
// NoteSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Stagiaire;
use App\Models\Matiere;
use App\Models\Periode;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $stagiaires = Stagiaire::all();
        $matieres = Matiere::all();
        $periode = Periode::first();
        $createdBy = 2; // Premier professeur

        $noteCount = 0;
        foreach ($stagiaires as $stagiaire) {
            foreach ($matieres->random(8) as $matiere) {
                // 2-4 notes par stagiaire par matière
                for ($i = 0; $i < rand(2, 4); $i++) {
                    Note::create([
                        'stagiaire_id' => $stagiaire->id,
                        'matiere_id' => $matiere->id,
                        'classe_id' => $stagiaire->classe_id,
                        'note' => round($faker->numberBetween(40, 200) / 10, 2),
                        'note_sur' => 20,
                        'semestre' => 1,
                        'periode_id' => $periode->id,
                        'type_note' => $faker->randomElement(['ds', 'cc', 'examen']),
                        'commentaire' => $faker->optional(0.3)->sentence,
                        'created_by' => $createdBy,
                    ]);
                    $noteCount++;
                }
            }
        }

        echo "✅ $noteCount notes créées\n";
    }
}