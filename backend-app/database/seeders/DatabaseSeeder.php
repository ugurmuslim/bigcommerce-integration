<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Optimum 7',
            'email' => 'optimum7@example.com',
            'access_token' => 'stb2jpdzily110or2pnhig5gex0tfub',
            'client_id' => 'j18lbbiafnopz4bugoryums1dmrbka',
            'client_secret' => '0f36ca8ec08fb6ad6edbd3a006902b9806bde8c3fc6b7c34b9b1dbdc00e8f875',
            'store_hash' => 'ldaqi0ooo8',
        ]);
    }
}
