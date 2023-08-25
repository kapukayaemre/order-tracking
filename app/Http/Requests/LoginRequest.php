<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "email"    => ["required", "email", "max:200"],
            "password" => ["required", "min:8"]
        ];
    }

    public function messages(): array
    {
        return [
            "email.required"    => "Email Alanı Zorunludur",
            "email.email"       => "Email Adresiniz Email Formatında Değil",
            "email.max"         => "Email En Fazla 200 Karakterden Oluşabilir",
            "password.required" => "Parola Alanı Zorunludur",
            "password.min"      => "Parola En Az 8 Karakterden Oluşmalıdır"
        ];
    }
}
