<?php

namespace Database\Seeders;

use App\Models\Planning;
use App\Models\User;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Salle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanningSeeder extends Seeder
{
    public function run(): void
    {
        $professeurs = User::where('role', 'professeur')->get();
        $classes = Classe::all();
        $matieres = Matiere::all();
        $salles = Salle::all();

        $planningCount = 0;
        $startDate = now()->addDays(1)->startOfDay();

        foreach ($classes as $classe) {
            // Créer 15 plannings par classe
            for ($i = 0; $i < 15; $i++) {
                $date = $startDate->copy()->addDays($i);
                $heure = rand(8, 15);

                // Professeur choisi aléatoirement
                $professeur = $professeurs->random();

                // Vérifier si le professeur a déjà un cours à ce créneau
                $debut = sprintf('%02d:00:00', $heure);
                $fin = sprintf('%02d:00:00', $heure + 2);

                $conflit = Planning::where('professeur_id', $professeur->id)
                    ->whereDate('date', $date)
                    ->where(function ($q) use ($debut, $fin) {
                        $q->whereBetween('heure_debut', [$debut, $fin])
                          ->orWhereBetween('heure_fin', [$debut, $fin])
                          ->orWhere(function ($sub) use ($debut, $fin) {
                              $sub->where('heure_debut', '<', $debut)
                                  ->where('heure_fin', '>', $fin);
                          });
                    })
                    ->exists();

                if ($conflit) {
                    // éviter le conflit : passer au suivant
                    continue;
                }

                // Création du planning sans conflit
                try {
                    Planning::create([
                        'professeur_id' => $professeur->id,
                        'classe_id' => $classe->id,
                        'matiere_id' => $matieres->random()->id,
                        'salle_id' => $salles->random()->id,
                        'date' => $date,
                        'heure_debut' => $debut,
                        'heure_fin' => $fin,
                        'type_cours' => rand(0, 1) ? 'cours' : 'td',
                        'statut' => 'valide',
                        'created_by' => 1,
                        'validated_by' => 1,
                        'validated_at' => now(),
                    ]);

                    $planningCount++;
                } catch (\Throwable $e) {
                    // ignore les erreurs de trigger MySQL restantes
                    continue;
                }
            }
        }

        echo "✅ $planningCount plannings créés sans conflit.\n";
    }
}
