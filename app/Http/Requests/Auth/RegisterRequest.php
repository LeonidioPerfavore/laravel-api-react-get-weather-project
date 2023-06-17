<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = User::VALIDATION_RULES;
        $rules['email'][1] = 'unique:users,email';
        return array_merge($rules, ['name' => ['required','string', 'min:2', 'max:255']]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 422));
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.min' => 'Name must be at least :min characters',
            'name.max' => 'Name must not exceed :max characters',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is already taken',
            'email.string' => 'Email must be a string',
            'email.max' => 'Email must not exceed :max characters',
            'email.email' => 'Email must be a valid email address',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must be at least :min characters',
            'password.max' => 'Password must not exceed :max characters',
        ];
    }
}