<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
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
        return response()->json("hehe");
    }
}
