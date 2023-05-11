<?php

namespace App\Http\Requests\Kendaraan;

use App\Traits\ResponseApiTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class CreateKendaraanRequest extends FormRequest
{
    use ResponseApiTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tahun_keluaran' => ['required', 'numeric'],
            'warna' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric'],
            'type_request' => ['required', 'in:mobil,motor'],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        return $this->sendError($validator->errors(), 'Ops! Some errors occurred', Response::HTTP_BAD_REQUEST);
    }
}
