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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email'=>['required','unique:users,email'],
            'phone'=>['required','unique:users,phone','regex:/(01)[0-9]{9}/'],
            'password' => ['required', 'confirmed'],
            'bio'=>['nullable','string'],
            'birth'=>['required','date'],
            'file' => ['nullable', 'max:1000', 'mimes:png,jpg,jpeg,webp'],
            'provider'=>['nullable','string'],
            'provider_id' => ['nullable','numeric'],
        ];
    }
}
