<?php

namespace Database\Seeders;

use App\Models\DokumenPerencanaan;
use Illuminate\Database\Seeder;

class DokumenPerencanaanSeeder extends Seeder
{
    public function run(): void
    {
        $dokumen = [
            ['nama' => 'Rencana Kerja Tahunan 2024', 'tanggal' => '2024-01-15'],
            ['nama' => 'Rencana Kerja Tahunan 2025', 'tanggal' => '2025-01-15'],
            ['nama' => 'Rencana Strategis 2024-2029', 'tanggal' => '2024-03-01'],
            ['nama' => 'Dokumen Pelaksanaan Anggaran 2024', 'tanggal' => '2024-02-01'],
            ['nama' => 'Laporan Kinerja Tahunan 2024', 'tanggal' => '2024-12-31'],
        ];

        foreach ($dokumen as $item) {
            DokumenPerencanaan::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
