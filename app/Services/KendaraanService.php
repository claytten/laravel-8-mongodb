<?php

namespace App\Services;

use App\Enums\KendaraanStatusEnum;
use App\Models\Kendaraan;
use App\Repositories\KendaraanRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class KendaraanService
{
  /**
   * @var KendaraanRepository
   */
  protected $kendaraanRepository;

  /**
   * KendaraanService constructor.
   *
   * @param KendaraanRepository $kendaraanRepository
   */
  public function __construct(KendaraanRepository $kendaraanRepository)
  {
    $this->kendaraanRepository = $kendaraanRepository;
  }

  public function saveMobilData(Array $data)
  {
    $validator = Validator::make($data, [
      'mesin' => 'required|string',
      'kapasitas_penumpang' => 'required|numeric',
      'tipe' => 'required|string|max:255'
    ]);

    if($validator->fails()){
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $result = $this->kendaraanRepository->saveDataMobil($data);

    return $result;
  }

  public function saveMotorData(Array $data)
  {
    $validator = Validator::make($data, [
      'mesin' => 'required|string',
      'suspensi' => 'required|string|max:255',
      'transmisi' => 'required|string|max:255'
    ]);

    if($validator->fails()){
      throw new InvalidArgumentException($validator->errors()->first());
    }

    $result = $this->kendaraanRepository->saveDataMotor($data);

    return $result;
  }

  public function updateKendaraan(Array $data, $id)
  {
    $validator = Validator::make($data, [
      'tahun_keluaran' => 'numeric',
      'warna' => 'string|max:255',
      'harga' => 'numeric',
      'status' => 'in:available,sold'
    ]);

    if($validator->fails()){
      throw new InvalidArgumentException($validator->errors()->first());
    }

    try {
      $result = $this->kendaraanRepository->updateKendaraan($data, $id);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException($th->getMessage());
    }

    return $result;
  }

  public function updateKendaraanSpesific(Array $data, Kendaraan $kendaraanModel, Array $validator)
  {
    $validator = Validator::make($data, $validator);

    if($validator->fails()){
      throw new InvalidArgumentException($validator->errors()->first());
    }

    try {
      $result = $kendaraanModel->kendaraanable()->update($data);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException($th->getMessage());
    }

    return $result;
  }

  public function updateStatusKendaraan($id)
  {
    try {
      $result = $this->kendaraanRepository->updateStatusKendaraan($id);
    } catch (\Throwable $th) {
      throw new InvalidArgumentException($th->getMessage());
    }

    return $result;
  }

  public function reportKendaraan($start, $end)
  {
    $totalKendaraan = Kendaraan::whereBetween('created_at', [$start, $end])->count();
    $totalKendaraanTerjual = Kendaraan::whereBetween('created_at', [$start, $end])->where('status', KendaraanStatusEnum::Sold)->count();
    $totalMobilTerjual = Kendaraan::whereBetween('created_at', [$start, $end])->where('status', KendaraanStatusEnum::Sold)->where('kendaraanable_type', Mobil::class)->count();
    $totalMotorTerjual = Kendaraan::whereBetween('created_at', [$start, $end])->where('status', KendaraanStatusEnum::Sold)->where('kendaraanable_type', Motor::class)->count();
    $dominanKendaraanTerjual = $totalMobilTerjual <=> $totalMotorTerjual;
    $dominanKendaraanTerjual = $dominanKendaraanTerjual == 0 ? 'imbang' : ($dominanKendaraanTerjual == 1 ? 'mobil' : 'motor');
    $totalMobil = Kendaraan::whereBetween('created_at', [$start, $end])->where('kendaraanable_type', Mobil::class)->count();
    $totalMotor = Kendaraan::whereBetween('created_at', [$start, $end])->where('kendaraanable_type', Motor::class)->count();
    $totalBiayaModal = Kendaraan::whereBetween('created_at', [$start, $end])->sum('harga');
    $totalPendapatan = Kendaraan::whereBetween('created_at', [$start, $end])->where('status', KendaraanStatusEnum::Sold)->sum('harga');
    $hasil = $totalPendapatan - $totalBiayaModal;

    return [
      'start date' => $start->format('d-m-Y'),
      'end date' => $end->format('d-m-Y'),
      'total_kendaraan' => $totalKendaraan,
      'total_mobil' => $totalMobil,
      'total_mobil_terjual' => $totalMobilTerjual,
      'total_motor' => $totalMotor,
      'total_motor_terjual' => $totalMotorTerjual,
      'total_kendaraan_terjual' => $totalKendaraanTerjual,
      'dominan_kendaraan_terjual' => $dominanKendaraanTerjual,
      'total_biaya_modal' => $totalBiayaModal,
      'total_pendapatan' => $totalPendapatan,
      'hasil plus atau minus' => $hasil,
    ];
  }
}