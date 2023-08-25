<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorStoreRequest extends FormRequest
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
            "name" => ["required", "max:200"]
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Name Alanı Zorunludur",
            "name.max"      => "Name Alanı En Fazla 200 Karakterden Oluşabilir"
        ];
    }
}
