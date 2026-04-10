<?php

namespace Database\Factories;

use App\Models\Kepegawaian;
use App\Models\Usulan;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsulanFactory extends Factory
{
    protected $model = Usulan::class;

    public function definition(): array
    {
        return [
            'pegawai_id' => Kepegawaian::inRandomOrder()->first()?->pegawai_id ?? Kepegawaian::factory(),
            'nama_kegiatan' => $this->faker->sentence(4),
            'tanggal_kegiatan' => $this->faker->dateTimeBetween('-3 months', '+3 months'),
            'lokasi_kegiatan' => $this->faker->city(),
            'deskripsi' => $this->faker->optional(0.7)->paragraph(2),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'catatan' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
