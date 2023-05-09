<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\Mobil;
use Illuminate\Database\Eloquent\Factories\Factory;

class MobilFactory extends Factory
{
    protected $model = Mobil::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mesin' => $this->faker->word,
            'kapasitas_penumpang' => $this->faker->numberBetween(2, 10),
            'tipe' => $this->faker->word,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Mobil $mobil) {
            Kendaraan::factory()->create([
                'kendaraanable_id' => $mobil->id,
                'kendaraanable_type' => Mobil::class,
                'tahun_keluaran' => $this->faker->numberBetween(2010, 2023),
                'warna' => $this->faker->safeColorName,
                'harga' => $this->faker->numberBetween(10000000, 200000000),
            ]);
        });
    }
}
