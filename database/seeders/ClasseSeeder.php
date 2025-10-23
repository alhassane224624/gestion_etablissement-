<?php
// ============================================================================
// ClasseSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Niveau;
use App\Models\AnneeScolaire;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        $anneeScolaire = AnneeScolaire::first();
        $niveaux = Niveau::all();

        foreach ($niveaux as $niveau) {
            // Créer 2 classes par niveau
            for ($i = 1; $i <= 2; $i++) {
                Classe::create([
                    'nom' => $niveau->filiere->nom . ' - N' . $niveau->ordre . '-' . chr(64 + $i),
                    'niveau_id' => $niveau->id,
                    'filiere_id' => $niveau->filiere_id,
                    'annee_scolaire_id' => $anneeScolaire->id,
                    'effectif_max' => 30,
                    'effectif_actuel' => 0,
                ]);
            }
        }

        echo "✅ Classes créées\n";
    }
}