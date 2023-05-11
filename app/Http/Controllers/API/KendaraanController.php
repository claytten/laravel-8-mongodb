<?php

namespace App\Http\Controllers\API;

use App\Enums\KendaraanStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Mobil;
use App\Models\Motor;
use App\Services\KendaraanService;
use App\Traits\ResponseApiTrait;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    use ResponseApiTrait;

    public function __construct(
        protected KendaraanService $kendaraanService
    )
    {
        
    }

    public function index()
    {
        $kendaraans = Kendaraan::with('kendaraanable')->get();

        return $this->sendResponse($kendaraans, 'Kendaraan retrieved successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'tahun_keluaran' => 'required|numeric',
            'warna' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'type_request' => 'required|in:mobil,motor',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        
        $result = null;
        if($request->type_request == 'mobil') {
            $result = $this->kendaraanService->saveMobilData($data);
        }
        if ($request->type_request == 'motor'){
            $result = $this->kendaraanService->saveMotorData($data);
        }

        return $this->sendResponse($result, 'Kendaraan created successfully.');
    }

    public function updateKendaraan(Request $request, $id)
    {
        $kendaraan = Kendaraan::find($id);
        $data = $request->all();

        if(!empty($request->type == 'kendaraan')) {
            try {
                $result = $this->kendaraanService->updateKendaraan($data, $id);
                return $this->sendResponse($result, 'Kendaraan updated successfully.');
            } catch (\Throwable $th) {
                return $this->sendError('Kendaraan not found.', $th->getMessage(), 404);
            }
        }

        if(get_class($kendaraan->kendaraanable) == Mobil::MobilTypeModel) {
            try {
                $result = $this->kendaraanService->updateKendaraanSpesific($data, $kendaraan, [
                    'mesin' => 'string',
                    'kapasitas_penumpang' => 'numeric',
                    'tipe' => 'string|max:255'
                ]);
                return $this->sendResponse($result, 'Mobil updated successfully.');
            } catch (\Throwable $th) {
                return $this->sendError('Mobil not found.', $th->getMessage(), 404);
            }
        }

        if(get_class($kendaraan->kendaraanable) == Motor::MotorTypeModel) {
            try {
                $result = $this->kendaraanService->updateKendaraanSpesific($data, $kendaraan, [
                    'mesin' => 'string',
                    'suspensi' => 'string|max:255',
                    'transmisi' => 'string|max:255'
                ]);
                return $this->sendResponse($result, 'Motor updated successfully.');
            } catch (\Throwable $th) {
                return $this->sendError('Motor not found.', $th->getMessage(), 404);
            }
        }

        return $this->sendError('Kendaraan not found.', 'Kendaraan not found.', 404);
    }

    public function updateStatus($id)
    {
        try {
            $result = $this->kendaraanService->updateStatusKendaraan($id);
            return $this->sendResponse($result, 'Kendaraan updated successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Kendaraan not found.', $th->getMessage(), 404);
        }
    }

    public function show($id)
    {
        $kendaraan = Kendaraan::with('kendaraanable')->find($id);

        if (empty($kendaraan)) {
            return $this->sendError('Kendaraan not found.');
        }

        return $this->sendResponse($kendaraan, 'Kendaraan retrieved successfully.');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::with('kendaraanable')->find($id);
        if (empty($kendaraan)) {
            return $this->sendError('Kendaraan not found.');
        }
        $kendaraan->delete();

        return $this->sendResponse($kendaraan, 'Kendaraan deleted successfully.');
    }

    public function report($date_in, $date_out)
    {
        // formating date input dd-mm-yyyy
        $start = DateTime::createFromFormat('d-m-Y', $date_in);
        $end = DateTime::createFromFormat('d-m-Y', $date_out);

        try {
            $result = $this->kendaraanService->reportKendaraan($start, $end);
            return $this->sendResponse($result, 'Kendaraan report successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Kendaraan not found.', $th->getMessage(), 404);
        }
    }
}
