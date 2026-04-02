<?php

namespace Database\Factories;

use App\Models\DokumenPerencanaan;
use App\Models\Perencanaan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerencanaanFactory extends Factory
{
    protected $model = Perencanaan::class;

    public function definition(): array
    {
        return [
            'kode' => $this->faker->unique()->numerify('PRK-#####'),
            'nama_komponen' => $this->faker->sentence(3),
            'file_pdf' => DokumenPerencanaan::inRandomOrder()->first()?->id ?? null,
        ];
    }
}
