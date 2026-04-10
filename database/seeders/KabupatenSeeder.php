<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kabupatens = [
            [
                'nama' => 'Kab. Bintan', ],
            [
                'nama' => 'Kab. Karimun', ],
            [
                'nama' => 'Kab. Kepulauan Anambas', ],
            [
                'nama' => 'Kab. Lingga', ],
            [
                'nama' => 'Kab. Natuna', ],
            [
                'nama' => 'Kota Batam', ],
            [
                'nama' => 'Kota Tanjungpinang', ],
            [
                'nama' => 'Lainnya (Luar Kepri)',
            ],
        ];

        DB::table('kabupatens')->insert($kabupatens);
    }
}
