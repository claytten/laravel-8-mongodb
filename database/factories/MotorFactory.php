<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use App\Models\Motor;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotorFactory extends Factory
{
    protected $model = Motor::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mesin' => $this->faker->word,
            'suspensi' => $this->faker->word,
            'transmisi' => $this->faker->word,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Motor $motor) {
            Kendaraan::factory()->create([
                'kendaraanable_id' => $motor->id,
                'kendaraanable_type' => Motor::class,
                'tahun_keluaran' => $this->faker->numberBetween(2010, 2023),
                'warna' => $this->faker->safeColorName,
                'harga' => $this->faker->numberBetween(5000000, 100000000),
                'status' => $this->faker->randomElement(['available', 'sold']),
            ]);
        });
    }
}
