<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendidikans=[
            [
                'nama' => 'SD/sederajat',],
                [
                'nama' => 'SMP/sederajat',],
                [
                'nama' => 'SMA/sederajat',],
                [
                'nama' => 'D-1/sederajat',],
                [
                'nama' => 'D-2/sederajat',],
                [
                'nama' => 'D-3/sederajat',],
                [
                'nama' => 'S-1/sederajat',],
                [
                'nama' => 'S-2/sederajat',],
                [
                'nama' => 'S-3/sederajat',],
            ];

            DB::table('pendidikans')->insert($pendidikans);
    }
}
