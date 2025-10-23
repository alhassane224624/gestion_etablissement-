<?php
// ============================================================================
// PeriodeSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Periode;
use App\Models\AnneeScolaire;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $annee = AnneeScolaire::where('is_active', true)->first();

        if (!$annee) return;

        // Semestre 1
        Periode::create([
            'nom' => 'Semestre 1',
            'type' => 'semestre',
            'debut' => $annee->debut,
            'fin' => $annee->debut->copy()->addMonths(5)->endOfMonth(),
            'annee_scolaire_id' => $annee->id,
            'is_active' => true,
        ]);

        // Semestre 2
        Periode::create([
            'nom' => 'Semestre 2',
            'type' => 'semestre',
            'debut' => $annee->debut->copy()->addMonths(6)->startOfMonth(),
            'fin' => $annee->fin,
            'annee_scolaire_id' => $annee->id,
            'is_active' => false,
        ]);

        echo "✅ Périodes créées\n";
    }
}
