<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WeatherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'clientIp' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 422));
    }

    public function validationData(): array
    {
        return ['clientIp' => $this->header('clientIp')];
    }

    public function messages(): array
    {
        return [
            'clientIp.required' => 'Client IP is required',
            'clientIp.string' => 'Client IP must be a string',
        ];
    }
}