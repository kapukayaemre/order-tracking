<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
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
            "product_id"    => ["required", "integer"],
            "discount_rate" => ["required", "integer", "max:100"]
        ];
    }

    public function messages(): array
    {
        return [
            "product_id.required"    => "Bir Ürün Seçilmesi Zorunludur",
            "product_id.integer"     => "Geçersiz Formatta Ürün",
            "discount_rate.required" => "İndirim Oranı Girilmesi Zorunludur",
            "discount_rate.integer"  => "Geçersiz Formatta İndirim Oranı",
            "discount_rate.max"      => "İndirim Oranı 100'den Fazla Olamaz"
        ];
    }
}
