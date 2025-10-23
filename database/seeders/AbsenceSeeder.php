<?php
// ============================================================================
// AbsenceSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Stagiaire;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AbsenceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $stagiaires = Stagiaire::all();
        $createdBy = 1;

        $absenceCount = 0;
        foreach ($stagiaires as $stagiaire) {
            // 0-5 absences par stagiaire
            for ($i = 0; $i < rand(0, 5); $i++) {
                Absence::create([
    'stagiaire_id' => $stagiaire->id,
    'date' => $faker->dateTimeBetween('-3 months'),
    'type' => $faker->randomElement(['matin', 'apres_midi', 'journee']),
    'heure_debut' => '08:00',
    'heure_fin' => '12:00',
    'justifiee' => $faker->boolean(40),
    'motif' => $faker->optional(0.5)->text(100),
    'created_by' => $createdBy,
]);

                $absenceCount++;
            }
        }

        echo "✅ $absenceCount absences créées\n";
    }
}