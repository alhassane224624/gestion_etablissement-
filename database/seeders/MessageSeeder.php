<?php
// ============================================================================
// MessageSeeder.php
// ============================================================================

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $users = User::all();

        $messageCount = 0;
        for ($i = 0; $i < 50; $i++) {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();

            Message::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'message' => $faker->paragraph,
                'subject' => $faker->sentence,
                'is_read' => $faker->boolean(50),
                'read_at' => $faker->optional(0.5)->dateTime,
            ]);
            $messageCount++;
        }

        echo "✅ $messageCount messages créés\n";
    }
}