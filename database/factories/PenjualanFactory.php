<?php

namespace Database\Factories;

use App\Enums\PenjualanStatusEnum;
use App\Models\Kendaraan;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenjualanFactory extends Factory
{
    protected $model = Penjualan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // returning array
        $kendaraan = $this->GetRandomKendaraanId()[0];

        return [
            'kendaraan_id' => $kendaraan['_id'],
            'pemilik' => $this->GetRandomUserName(),
            'nama_pembeli' => null,
            'alamat_pembeli' => null,
            'harga_jual' => $kendaraan['harga']
        ];
    }

    private function GetRandomKendaraanId() {
        return Kendaraan::raw(function($collection){ return $collection->aggregate([ ['$sample' => ['size' => 3]] ]); });
    }

    private function GetRandomUserName() {
        $user = User::raw(function($collection){ return $collection->aggregate([ ['$sample' => ['size' => 3]] ]); });
        return $user[0]['name'];
    }
}
