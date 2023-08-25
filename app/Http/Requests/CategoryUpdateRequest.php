<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            "title" => ["required", "max:200"]
        ];
    }

    public function messages(): array
    {
        return [
            "title.required" => "Title Alanı Zorunludur",
            "title.max"      => "Title Alanı En Fazla 200 Karakter Olabilir"
        ];
    }
}
