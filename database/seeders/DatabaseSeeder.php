<?php

namespace Database\Seeders;

use App\Models\Kepegawaian;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@terang.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            PangkatSeeder::class,
            BankSeeder::class,
            PendidikanSeeder::class,
            KabupatenSeeder::class,
            InstansiSeeder::class,
            DokumenPerencanaanSeeder::class,
            PaguHotelSeeder::class,
        ]);

        Kepegawaian::factory(20)->create();
    }
}
