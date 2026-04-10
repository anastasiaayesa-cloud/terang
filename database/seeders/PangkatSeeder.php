<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pangkats = [
            [
                'nama' => 'Pengatur Muda, II/a', ],
            [
                'nama' => 'Pengatur Muda Tingkat I, II/b', ],
            [
                'nama' => 'Pengatur, II/c', ],
            [
                'nama' => 'Pengatur Tingkat I, II/d', ],
            [
                'nama' => 'Penata Muda, III/a', ],
            [
                'nama' => 'Penata Muda Tingkat I, III/b', ],
            [
                'nama' => 'Penata, III/c', ],
            [
                'nama' => 'Penata Tingkat I, III/d', ],
            [
                'nama' => 'Pembina, IV/a', ],
            [
                'nama' => 'Pembina Tingkat I, IV/b', ],
            [
                'nama' => 'Pembina Utama Muda, IV/c', ],
            [
                'nama' => 'Pembina Utama Madya, IV/d', ],
            [
                'nama' => 'Pembina Utama, IV/e',
            ],
        ];

        DB::table('pangkats')->insert($pangkats);
    }
}
