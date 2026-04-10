<?php

namespace Database\Seeders;

use App\Models\Instansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = [
            ['nama' => 'Dinas Pendidikan', 'alamat' => 'Jl. Pendidikan No. 1', 'telp' => '021-1234567', 'kabupaten_id' => null],
            ['nama' => 'Dinas Kesehatan', 'alamat' => 'Jl. Kesehatan No. 2', 'telp' => '021-2345678', 'kabupaten_id' => null],
            ['nama' => 'Dinas Pekerjaan Umum', 'alamat' => 'Jl. Raya No. 3', 'telp' => '021-3456789', 'kabupaten_id' => null],
            ['nama' => 'Dinas Sosial', 'alamat' => 'Jl. Sosial No. 4', 'telp' => '021-4567890', 'kabupaten_id' => null],
            ['nama' => 'Badan Kepegawaian Daerah', 'alamat' => 'Jl. BKD No. 5', 'telp' => '021-5678901', 'kabupaten_id' => null],
        ];

        foreach ($instansis as $instansi) {
            Instansi::updateOrCreate(
                ['nama' => $instansi['nama']],
                $instansi
            );
        }
    }
}
