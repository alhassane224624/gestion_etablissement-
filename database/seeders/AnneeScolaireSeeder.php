<?php
// ============================================================================
// AnneeScolaireSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use Illuminate\Database\Seeder;

class AnneeScolaireSeeder extends Seeder
{
    public function run(): void
    {
        $anneeActuelle = now()->year;

        AnneeScolaire::create([
            'nom' => ($anneeActuelle - 1) . '/' . $anneeActuelle,
            'debut' => now()->setYear($anneeActuelle - 1)->setMonth(9)->setDay(1),
            'fin' => now()->setYear($anneeActuelle)->setMonth(6)->setDay(30),
            'is_active' => false,
        ]);

        AnneeScolaire::create([
            'nom' => $anneeActuelle . '/' . ($anneeActuelle + 1),
            'debut' => now()->setYear($anneeActuelle)->setMonth(9)->setDay(1),
            'fin' => now()->setYear($anneeActuelle + 1)->setMonth(6)->setDay(30),
            'is_active' => true,
        ]);

        echo "✅ Années scolaires créées\n";
    }
}