<?php

namespace App\Http\Controllers\API;

use App\Enums\KendaraanStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Mobil;
use App\Models\Motor;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('kendaraanable')->get();

        return response()->json([
            'status' => 'success',
            'data' => $kendaraans,
        ]);
    }

    public function store(Request $request)
    {
        $validatedKendaraan = $request->validate([
            'tahun_keluaran' => 'required|numeric',
            'warna' => 'required|string|max:255',
            'harga' => 'required|numeric',
        ]);
        
        $data = null;
        if($request->type_request == 'mobil') {
            
            $validatedMobil = $request->validate([
                'mesin' => 'required|string',
                'kapasitas_penumpang' => 'required|numeric',
                'tipe' => 'required|string|max:255'
            ]);

            $data = Mobil::create($validatedMobil)->kendaraan()->create($validatedKendaraan);
        } else {
            $validatedMotor = $request->validate([
                'mesin' => 'required|string',
                'suspensi' => 'required|string|max:255',
                'transmisi' => 'required|string|max:255'
            ]);

            $data = Motor::create($validatedMotor)->kendaraan()->create($validatedKendaraan);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function updateKendaraan(Request $request, $id)
    {
        //this type is for validation updating kendaraan, motor, or mobil
        $kendaraan = Kendaraan::find($id);

        if(!empty($request->type == 'kendaraan')) {
            $validatedData = $request->validate([
                'tahun_keluaran' => 'numeric',
                'warna' => 'string|max:255',
                'harga' => 'numeric',
                'status' => 'in:available,sold'
            ]);
    
    
            $kendaraan->update($validatedData);
    
            return response()->json([
                'status' => 'success',
                'data' => $kendaraan
            ]);
        }

        if(get_class($kendaraan->kendaraanable) == Mobil::class) {
            $validatedData = $request->validate([
                'mesin' => 'string',
                'kapasitas_penumpang' => 'numeric',
                'tipe' => 'string|max:255'
            ]);

            $kendaraan->kendaraanable->update($validatedData);

            return response()->json([
                'status' => 'success',
                'data' => $kendaraan
            ]);
        }

        if(get_class($kendaraan->kendaraanable) == Mobil::class) {
            $validatedData = $request->validate([
                'mesin' => 'string',
                'suspensi' => 'string|max:255',
                'transmisi' => 'string|max:255'
            ]);

            $kendaraan->kendaraanable->update($validatedData);

            return response()->json([
                'status' => 'success',
                'data' => $kendaraan
            ]);
        }

        return response()->json([
            'status' => 'error',
        ], 500);
    }

    public function updateStatus($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $kendaraan->status = KendaraanStatusEnum::Sold;
        $kendaraan->save();

        return response()->json([
            'status' => 'success',
            'data' => $kendaraan,
            'hehe' => KendaraanStatusEnum::Sold
        ]);
    }

    public function show($id)
    {
        $kendaraan = Kendaraan::with('kendaraanable')->find($id);

        return response()->json([
            'status' => 'success',
            'data' => empty($kendaraan) ? [] : $kendaraan
        ]);
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kendaraan berhasil dihapus'
        ]);
    }

    public function report($date_in, $date_out)
    {
        // formating date input dd-mm-yyyy
        $start = DateTime::createFromFormat('d-m-Y', $date_in);
        $end = DateTime::createFromFormat('d-m-Y', $date_out);
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

        // hasil kesimpulan minus atau plus
        $hasil = $totalPendapatan - $totalBiayaModal;
        return response()->json([
            'status' => 'success',
            'data' => [
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
                'hasil plus atau minus' => $hasil
            ]
        ]);
    }
}
