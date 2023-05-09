<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'harga_jual' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $kendaraan = Kendaraan::find($request->kendaraan_id);
            $kendaraan->save();

            $penjualan = $kendaraan->penjualan()->create([
                'nama_pembeli' => Auth::user()->name,
                'alamat_pembeli' => $request->alamat_pembeli,
                'harga_jual' => $request->harga_jual,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $penjualan,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
