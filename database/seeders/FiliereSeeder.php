<?php
// ============================================================================
// FiliereSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = [
            ['nom' => 'Développement Informatique', 'niveau' => 'Bac+2'],
            ['nom' => 'Administration Réseau', 'niveau' => 'Bac+2'],
            ['nom' => 'Infographie', 'niveau' => 'Bac+2'],
            ['nom' => 'Secrétariat de Direction', 'niveau' => 'Bac+2'],
            ['nom' => 'Comptabilité', 'niveau' => 'Bac+2'],
            ['nom' => 'Commerce Digital', 'niveau' => 'Bac+2'],
            ['nom' => 'Gestion Hotelière', 'niveau' => 'Bac+2'],
        ];

        foreach ($filieres as $filiere) {
            Filiere::create($filiere);
        }

        echo "✅ " . count($filieres) . " filières créées\n";
    }
}