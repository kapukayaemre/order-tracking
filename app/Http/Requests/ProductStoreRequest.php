<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            "category_id"    => ["required", "integer"],
            "author_id"      => ["required", "integer"],
            "discount_id"    => ["nullable", "integer"],
            "title"          => ["required", "max:255"],
            "description"    => ["nullable", "max:500"],
            "price"          => ["required", "numeric"],
            "stock_quantity" => ["required", "integer"]
        ];
    }

    public function messages(): array
    {
        return [
            "category_id.required"    => "Kategori Seçimi Zorunludur",
            "category_id.integer"     => "Seçilen Kategori Formatı Uygun Değil",
            "author_id.required"      => "Yazar Seçimi Zorunludur",
            "author_id.integer"       => "Seçilen Yazar Formatı Uygun Değil",
            "discount_id.integer"     => "Seçilen Kampanya Formatı Uygun Değil",
            "title.required"          => "Ürün Başlığı Zorunludur",
            "title.max"               => "Ürün Başlığı En Fazla 255 Karakter Olabilir",
            "description.max"         => "Ürün Açıklaması En Fazla 500 Karakterden Oluşabilir",
            "price.required"          => "Ürün Fiyatı Zorunludur",
            "price.decimal"           => "Ürün Fiyatı Küsüratıyla Birlikte Belirtilmelidir",
            "stock_quantity.required" => "Ürün Adeti Zorunludur",
            "stock_quantity.integer"  => "Ürün Adeti Sayı Olmalıdır"
        ];
    }
}
