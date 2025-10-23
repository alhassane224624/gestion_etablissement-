<?php
// ============================================================================
// NiveauSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Niveau;
use App\Models\Filiere;
use Illuminate\Database\Seeder;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = Filiere::all();

        foreach ($filieres as $filiere) {
            // Créer 2 niveaux par filière
            for ($i = 1; $i <= 2; $i++) {
                Niveau::create([
                    'nom' => 'Niveau ' . $i,
                    'ordre' => $i,
                    'filiere_id' => $filiere->id,
                    'duree_semestres' => 2,
                ]);
            }
        }

        echo "✅ Niveaux créés pour toutes les filières\n";
    }
}