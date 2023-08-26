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
            "product_id"    => ["nullable", "integer"],
            "author_id"     => ["nullable", "integer"],
            "category_id"   => ["nullable", "integer"],
            "min_amount"    => ["nullable", "numeric"],
            "discount_rate" => ["nullable", "integer", "max:100"],
            "min_buy_count" => ["nullable", "integer"],
            "free_count"    => ["nullable", "integer"]
        ];
    }

    public function messages(): array
    {
        return [
            "product_id.integer"    => "Geçersiz Formatta Ürün",
            "author_id.integer"     => "Geçersiz Formatta Yazar",
            "category_id.integer"   => "Geçersiz Formatta Kategori",
            "min_amount.numeric"    => "Sayısal Formatta Ödeme Giriniz",
            "discount_rate.integer" => "Sayısal Formatta İndirim Oranı Giriniz",
            "discount_rate.max"     => "İndirim Oranı 100'den Fazla Olamaz",
            "min_buy_count.integer" => "Sayısal Formatta Minimum Satın Alma Sayısı Giriniz",
            "free_count.integer"    => "Sayısal Formatta Ücretsiz Kitap Sayısı Giriniz",
        ];
    }
}
