<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Stagiaire;
use App\Models\Classe;
use App\Models\Filiere;

class StagiaireSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Exemple de données fictives — adapte selon ton école
        $stagiairesData = [
            [
                'nom' => 'Diallo',
                'prenom' => 'Aminata',
                'email' => 'aminata.diallo@example.com',
                'telephone' => '628123456',
                'filiere_id' => 1,
                'classe_id' => 1,
                'niveau_id' => 1,
            ],
            [
                'nom' => 'Camara',
                'prenom' => 'Moussa',
                'email' => 'moussa.camara@example.com',
                'telephone' => '627987654',
                'filiere_id' => 1,
                'classe_id' => 2,
                'niveau_id' => 1,
            ],
            [
                'nom' => 'Traoré',
                'prenom' => 'Fatoumata',
                'email' => 'fatou.tr@example.com',
                'telephone' => '629123321',
                'filiere_id' => 2,
                'classe_id' => 3,
                'niveau_id' => 1,
            ],
        ];

        foreach ($stagiairesData as $data) {
            DB::transaction(function () use ($data) {
                // ✅ Étape 1 : Créer un compte utilisateur automatiquement
                $user = User::create([
                    'name'       => "{$data['prenom']} {$data['nom']}",
                    'email'      => $data['email'],
                    'password'   => Hash::make('stagiaire123'), // mot de passe par défaut
                    'role'       => 'stagiaire',
                    'is_active'  => true,
                    'created_by' => 1, // admin par défaut (id=1)
                ]);

                // ✅ Étape 2 : Créer le stagiaire lié au compte utilisateur
                Stagiaire::create([
                    'user_id'         => $user->id,
                    'nom'             => $data['nom'],
                    'prenom'          => $data['prenom'],
                    'matricule'       => strtoupper(Str::random(8)),
                    'email'           => $data['email'],
                    'telephone'       => $data['telephone'] ?? null,
                    'filiere_id'      => $data['filiere_id'],
                    'classe_id'       => $data['classe_id'],
                    'niveau_id'       => $data['niveau_id'],
                    'date_inscription'=> now(),
                    'frais_inscription'=> 0,
                    'frais_payes'     => false,
                    'is_active'       => true,
                    'statut'          => 'actif',
                    'created_by'      => 1,
                ]);

                // ✅ Incrémenter l’effectif de la classe
                if (isset($data['classe_id'])) {
                    Classe::where('id', $data['classe_id'])->increment('effectif_actuel');
                }
            });
        }

        echo "✅ Stagiaires et comptes utilisateurs créés avec succès !\n";
    }
}
