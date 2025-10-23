<?php
// ============================================================================
// SalleSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        $salles = [
            ['nom' => 'Amphi A', 'type' => 'amphitheatre', 'capacite' => 150, 'batiment' => 'Bâtiment A', 'etage' => 1],
            ['nom' => 'Amphi B', 'type' => 'amphitheatre', 'capacite' => 100, 'batiment' => 'Bâtiment A', 'etage' => 2],
            ['nom' => 'Salle 101', 'type' => 'salle_cours', 'capacite' => 40, 'batiment' => 'Bâtiment B', 'etage' => 1],
            ['nom' => 'Salle 102', 'type' => 'salle_cours', 'capacite' => 35, 'batiment' => 'Bâtiment B', 'etage' => 1],
            ['nom' => 'Salle 201', 'type' => 'salle_cours', 'capacite' => 45, 'batiment' => 'Bâtiment B', 'etage' => 2],
            ['nom' => 'Labo Info 1', 'type' => 'salle_informatique', 'capacite' => 30, 'batiment' => 'Bâtiment C', 'etage' => 1],
            ['nom' => 'Labo Info 2', 'type' => 'salle_informatique', 'capacite' => 25, 'batiment' => 'Bâtiment C', 'etage' => 2],
            ['nom' => 'Labo Chimie', 'type' => 'laboratoire', 'capacite' => 20, 'batiment' => 'Bâtiment D', 'etage' => 1],
        ];

        foreach ($salles as $salle) {
            Salle::create(array_merge($salle, [
                'disponible' => true,
                'equipements' => ['projecteur', 'tableau_interactif', 'wifi'],
            ]));
        }

        echo "✅ " . count($salles) . " salles créées\n";
    }
}