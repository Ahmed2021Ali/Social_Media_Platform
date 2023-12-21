<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable','email',Rule::unique('users','email')->ignore($this->user()->id, 'id')],
            'phone'=>['nullable','numeric',Rule::unique('users','phone')->ignore($this->user()->id, 'id')],
            'password' => ['nullable', 'confirmed'],
            'bio'=>['nullable','string'],
            'birth'=>['nullable','date'],
            'file' => ['nullable', 'max:1000', 'mimes:png,jpg,jpeg,webp'],
            'provider'=>['nullable','string'],
            'provider_id' => ['nullable','numeric'],
        ];
    }
}
