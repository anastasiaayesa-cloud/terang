<?php

namespace Database\Factories;

use App\Models\Kepegawaian;
use App\Models\LaporanKegiatan;
use App\Models\Perencanaan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanKegiatanFactory extends Factory
{
    protected $model = LaporanKegiatan::class;

    public function definition(): array
    {
        $tanggalMulai = $this->faker->dateTimeBetween('-6 months', '-1 month');
        $tanggalSelesai = (clone $tanggalMulai)->modify('+'.$this->faker->numberBetween(1, 14).' days');

        return [
            'pegawai_id' => Kepegawaian::inRandomOrder()->first()?->pegawai_id ?? Kepegawaian::factory(),
            'perencanaan_id' => Perencanaan::inRandomOrder()->first()?->id ?? Perencanaan::factory(),
            'judul_laporan' => $this->faker->sentence(6),
            'deskripsi_kegiatan' => $this->faker->paragraph(3),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'lokasi_kegiatan' => $this->faker->city(),
            'file_laporan' => 'laporan-kegiatan/dummy-'.$this->faker->uuid.'.pdf',
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'catatan_reviewer' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
