<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class StoreUserRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name" => "string",
            "email" => "required|email",
            "password" => "required|min:6|max:10",
            "cpf" => "required|numeric",
            "cnpj" => "required|numeric",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $json = [
            'status' => 'Validation Error',
            'message' => 'Do you must fix that fields',
            'errors' => $validator->getMessageBag()
        ];
        $response = response($json, 400);
        throw (new ValidationException($validator, $response))->status(400);
    }
}
