<?php

namespace App\Http\Requests\Users;

use App\Traits\ResponseApiTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class RegisterRequest extends FormRequest
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
            'name' => ['required'],
            'email' => ['required','email'],
            'address' => ['required'],
            'password' => ['required'],
            'confirm_password' => ['required','same:password'],
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
