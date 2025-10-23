<?php
// ============================================================================
// UserSeeder.php - CRÉER LES (ADMIN, PROFESSEURS, ETC)
// ============================================================================

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@emsi.ma',
            'password' => Hash::make('password123'),
            'role' => 'administrateur',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Professeurs
        $professeurs = [
            ['name' => 'Mr. Ahmed Bennani', 'email' => 'ahmed.bennani@emsi.ma', 'specialite' => 'Mathématiques'],
            ['name' => 'Mme. Fatima Karim', 'email' => 'fatima.karim@emsi.ma', 'specialite' => 'Physique'],
            ['name' => 'Mr. Hassan El Idrissi', 'email' => 'hassan.idrissi@emsi.ma', 'specialite' => 'Informatique'],
            ['name' => 'Mme. Laila Moumine', 'email' => 'laila.moumine@emsi.ma', 'specialite' => 'Chimie'],
            ['name' => 'Mr. Karim Aziz', 'email' => 'karim.aziz@emsi.ma', 'specialite' => 'Français'],
        ];

        foreach ($professeurs as $prof) {
            User::create([
                'name' => $prof['name'],
                'email' => $prof['email'],
                'password' => Hash::make('password123'),
                'role' => 'professeur',
                'specialite' => $prof['specialite'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // ✅ Correction de la ligne d'affichage
        echo "✅ " . (count($professeurs) + 1) . " utilisateurs créés\n";
    }
}
