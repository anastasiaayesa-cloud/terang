<?php

namespace Database\Factories;

use App\Models\Kepegawaian;
use Illuminate\Database\Eloquent\Factories\Factory;

class KepegawaianFactory extends Factory
{
    protected $model = Kepegawaian::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),

            'nip' => $this->faker->unique()->numerify('##################'),

            'jabatan' => $this->faker->jobTitle(),

            // sementara null (FK belum ada)
            'pangkat_id' => null,

            'tempat_lahir' => $this->faker->city(),

            // sesuai migration (string)
            'tgl_lahir' => $this->faker->date('Y-m-d'),

            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),

            'agama' => $this->faker->randomElement([
                'Islam',
                'Kristen Katolik',
                'Kristen Protestan',
                'Hindu',
                'Buddha',
                'Konghucu',
            ]),

            'instansi_id' => null,

            'hp' => $this->faker->phoneNumber(),

            'email' => $this->faker->unique()->safeEmail(),

            'npwp' => $this->faker->numerify('##.###.###.#-###.###'),

            'bank_id' => null,

            'no_rek' => $this->faker->bankAccountNumber(),

            'pendidikan_id' => null,

            // 🔥 FIX DI SINI
            'is_bpmp' => $this->faker->boolean(),

            'user_id' => null,
        ];
    }
}
