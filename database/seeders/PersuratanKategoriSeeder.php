<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersuratanKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $persuratan_kategoris = [
            [
                'nama_kategori' => 'Surat Keputusan',            ],
            [
                'nama_kategori' => 'Surat Tugas',            ],
            [
                'nama_kategori' => 'Surat Undangan',      ],
            [
                'nama_kategori' => 'Nota Dinas',      ],

        ];
        DB::table('persuratan_kategoris')->insert( $persuratan_kategoris);

    }
}
