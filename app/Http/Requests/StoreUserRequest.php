<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email'=>['required','unique:users,email'],
            'phone'=>['required','unique:users,phone','regex:/^01[0-2,5,9]{1}[0-9]{8}$/'],
            'password' => ['required', 'confirmed'],
            'bio'=>['nullable','string'],
            'birth'=>['required','date'],
            'file' => ['nullable', 'max:2000', 'mimes:png,jpg,jpeg,webp'],
            'provider'=>['nullable','string'],
            'provider_id' => ['nullable','numeric'],
        ];
    }
}
