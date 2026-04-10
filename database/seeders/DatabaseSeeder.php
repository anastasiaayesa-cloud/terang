<?php

namespace Database\Seeders;

use App\Models\Kepegawaian;
use App\Models\LaporanKegiatan;
use App\Models\Perencanaan;
use App\Models\User;
use App\Models\Usulan;
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

<<<<<<< HEAD
        Kepegawaian::factory(50)->create();
=======
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
        Perencanaan::factory(10)->create();
        LaporanKegiatan::factory(15)->create();
        Usulan::factory(10)->create();
>>>>>>> ddf70700cbfda6130c029c7903fa803fe223054f
    }
}
