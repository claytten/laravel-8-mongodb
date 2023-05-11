<?php

namespace App\Observers;

use App\Enums\KendaraanStatusEnum;
use App\Models\Kendaraan;

class KendaraanObserver
{
    /**
     * Handle the Kendaraan "created" event.
     *
     * @param  \App\Models\Kendaraan $kendaraan
     * @return void
     */
    public function created(Kendaraan $kendaraan)
    {
        $kendaraan->status = KendaraanStatusEnum::Available;
        $kendaraan->save();
    }
}
