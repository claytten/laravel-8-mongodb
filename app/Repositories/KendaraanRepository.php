<?php

namespace App\Repositories;

use App\Enums\KendaraanStatusEnum;
use App\Models\Kendaraan;
use App\Models\Mobil;
use App\Models\Motor;

class KendaraanRepository
{
  /**
   * @var Kendaraan
   */
  protected $kendaraan;

  /**
   * @var Mobil
   * 
   */
  protected $mobil;

  /**
   * @var Motor
   * 
   */
  protected $motor;

  /**
   * KendaraanRepository constructor.
   *
   * @param Kendaraan $kendaraan
   */
  public function __construct(
    Kendaraan $kendaraan,
    Mobil $mobil,
    Motor $motor
  )
  {
    $this->kendaraan = $kendaraan;
    $this->mobil = $mobil;
    $this->motor = $motor;
  }

  /**
   * Create new Mobil
   * @param Array $data
   * 
   * @return Kendaraan
   */
  public function saveDataMobil(Array $data)
  {
    $mobil = $this->mobil->create($data);
    $kendaraan = $mobil->kendaraan()->create($data);

    return $kendaraan;
  }

  /**
   * Create new Motor
   * @param Array $data
   * 
   * @return Kendaraan
   */
  public function saveDataMotor(Array $data)
  {
    $motor = $this->motor->create($data);
    $kendaraan = $motor->kendaraan()->create($data);

    return $kendaraan;
  }

  public function getKendaraanById($id)
  {
    $kendaraan = $this->kendaraan->findOrFail($id);

    return $kendaraan;
  }

  /**
   * Update Kendaraan
   * @param Array $data
   * @param Int $id
   * 
   * @return Kendaraan
   */
  public function updateKendaraan(Array $data, $id)
  {
    $kendaraan = $this->getKendaraanById($id);
    $kendaraan->update($data);

    return $kendaraan;
  }

  public function updateStatusKendaraan($id)
  {
    $kendaraan = $this->getKendaraanById($id);
    $kendaraan->status = KendaraanStatusEnum::Sold;
    $kendaraan->save();

    return $kendaraan;
  }
}