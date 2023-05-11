<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mobil;
use App\Models\Motor;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mobil::factory()->count(10)->create();
        Motor::factory()->count(10)->create();
    }
}
