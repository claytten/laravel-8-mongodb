<?php

namespace App\Http\Controllers\API;

use App\Enums\KendaraanStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Mobil;
use App\Models\Motor;
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
        $rule = [
            'tahun_keluaran' => ['required', 'numeric'],
            'warna' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric'],
            'type_request' => ['required', 'in:mobil,motor']
        ];
        
        $data = null;
        $typeClass = null;
        if($request->type_request == 'mobil') {
            
            $rule['mesin'] = ['required', 'string'];
            $rule['kapasitas_penumpang'] = ['required', 'numeric'];
            $rule['tipe'] = ['required', 'string', 'max:255'];
            $validated = $this->validate($request, $rule);

            $data = Mobil::create($validated);
            $typeClass = Mobil::class;
        } else {
            $rule['mesin'] = ['required', 'string'];
            $rule['suspensi'] = ['required', 'string', 'max:255'];
            $rule['transmisi'] = ['required', 'string', 'max:255'];
            $validated = $this->validate($request, $rule);

            $data = Motor::create($validated);
            $typeClass = Motor::class;
        }

        $kendaraan = Kendaraan::create([
            'kendaraanable_id' => $data->id,
            'kendaraanable_type' => $typeClass,
            'tahun_keluaran' => $request->tahun_keluaran,
            'warna' => $request->warna,
            'harga' => $request->harga,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $kendaraan
        ]);
    }

    public function updateKendaraan(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        if(get_class($kendaraan->kendaraanable) == Mobil::class) {
            $validatedData = $request->validate([
                'mesin' => 'required|numeric',
                'kapasitas_penumpang' => 'required|numeric',
                'tipe' => 'required|string|max:255'
            ]);

            $kendaraan->kendaraanable->update($validatedData);
        }

        if(get_class($kendaraan->kendaraanable) == Motor::class) {
            $validatedData = $request->validate([
                'mesin' => 'required|numeric',
                'suspensi' => 'required|string|max:255',
                'transmisi' => 'required|string|max:255'
            ]);

            $kendaraan->kendaraanable->update($validatedData);
        }

        $validatedData = $request->validate([
            'tahun_keluaran' => 'required|numeric',
            'warna' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'status' => 'required|in:available,sold'
        ]);


        $kendaraan->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $kendaraan
        ]);
    }

    public function updateStatus($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $kendaraan->update([
            'status' => KendaraanStatusEnum::Sold
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $kendaraan
        ]);
    }

    public function show($id)
    {
        $kendaraan = Kendaraan::with('kendaraanable')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $kendaraan
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

    public function report($start, $end)
    {
        // formating date YYYY-MM-DD HH:MM:SS
        // reporting dalam range waktu tertentu
        // total kendaraan dalam range waktu tertentu
        $totalKendaraan = Kendaraan::whereBetween('created_at', [$start, $end])->count();

        // total kendaraan yang terjual dalam range waktu tertentu
        $totalKendaraanTerjual = Kendaraan::whereBetween('created_at', [$start, $end])->where('status', KendaraanStatusEnum::Sold)->count();

        // tipe kendaraan yang paling laris (mobil/motor) dalam range waktu tertentu
        $totalMobil = Kendaraan::whereBetween('created_at', [$start, $end])->where('kendaraanable_type', Mobil::class)->count();
        $totalMotor = Kendaraan::whereBetween('created_at', [$start, $end])->where('kendaraanable_type', Motor::class)->count();

        // total biaya modal dalam range waktu tertentu
        $totalBiayaModal = Kendaraan::whereBetween('created_at', [$start, $end])->sum('harga');

        // total kendaraan yang terjual dalam range waktu tertentu
        $totalPendapatan = Kendaraan::whereBetween('created_at', [$start, $end])->where('status', KendaraanStatusEnum::Sold)->sum('harga');

        // hasil kesimpulan minus atau plus
        $hasil = $totalPendapatan - $totalBiayaModal;
        return response()->json([
            'status' => 'success',
            'data' => [
                'start date' => $start,
                'end date' => $end,
                'total_kendaraan' => $totalKendaraan,
                'total_kendaraan_terjual' => $totalKendaraanTerjual,
                'total_mobil' => $totalMobil,
                'total_motor' => $totalMotor,
                'total_biaya_modal' => $totalBiayaModal,
                'total_pendapatan' => $totalPendapatan,
                'hasil plus atau minus' => $hasil
            ]
        ]);
    }
}
