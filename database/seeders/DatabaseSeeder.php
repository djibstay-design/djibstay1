<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'role' => 'SUPER_ADMIN',
            'password' => bcrypt('password'),
        ]);

        $this->call(HotelSeeder::class);
    }
}
