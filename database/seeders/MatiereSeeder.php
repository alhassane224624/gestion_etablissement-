<?php

// ============================================================================
// MatiereSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Matiere;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    public function run(): void
    {
        $matieres = [
            ['nom' => 'Mathématiques', 'code' => 'MAT101', 'coefficient' => 3],
            ['nom' => 'Physique', 'code' => 'PHY101', 'coefficient' => 2],
            ['nom' => 'Informatique', 'code' => 'INF101', 'coefficient' => 4],
            ['nom' => 'Chimie', 'code' => 'CHI101', 'coefficient' => 2],
            ['nom' => 'Français', 'code' => 'FRA101', 'coefficient' => 2],
            ['nom' => 'Anglais', 'code' => 'ANG101', 'coefficient' => 2],
            ['nom' => 'Histoire', 'code' => 'HIS101', 'coefficient' => 1],
            ['nom' => 'Géographie', 'code' => 'GEO101', 'coefficient' => 1],
            ['nom' => 'Programmation', 'code' => 'PRG101', 'coefficient' => 4],
            ['nom' => 'Bases de Données', 'code' => 'BDD101', 'coefficient' => 3],
        ];

        foreach ($matieres as $matiere) {
            Matiere::create($matiere);
        }

        echo "✅ " . count($matieres) . " matières créées\n";
    }
}