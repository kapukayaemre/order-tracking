<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'orders.*.user_id'     => ['required', 'integer'],
            'orders.*.product_id'  => ['required', 'integer'],
            'orders.*.author_id'   => ['required', 'integer'],
            'orders.*.category_id' => ['required', 'integer'],
            'orders.*.quantity'    => ['required', 'integer'],
            'orders.*.price'       => ['required', 'numeric'],
            'orders.*.address'     => ['required', 'max:500'],
            'orders.*.description' => ['nullable', 'max:500']
        ];
    }

    public function messages(): array
    {
        return [
            "orders.*.user_id.required"     => "Lütfen Oturum Açınız",
            "orders.*.user_id.integer"      => "Seçilen Kullanıcı Formatı Uygun Değil",
            "orders.*.product_id.required"  => "Ürün Seçilmesi Zorunludur",
            "orders.*.product_id.integer"   => "Seçilen Ürün Formatı Uygun Değil",
            "orders.*.author_id.required"   => "Ürüne Ait Bir Yazar Olmalıdır",
            "orders.*.author_id.integer"    => "Seçilen Yazar Formatı Uygun Değil",
            "orders.*.category_id.required" => "Kategori Seçilmesi Zorunludur",
            "orders.*.category_id.integer"  => "Seçilen Kategori Formatı Uygun Değil",
            "orders.*.quantity.required"    => "Ürün Adeti Girilmesi Zorunludur",
            "orders.*.quantity.integer"     => "Seçilen Ürün Adeti Formatı Uygun Değil",
            "orders.*.price.required"       => "Tutar Gönderilmesi Zorunludur",
            "orders.*.price.numeric"        => "Tutar Sayısal Formattan Oluşmalıdır",
            "orders.*.address.required"     => "Adres Girilmesi Zorunludur",
            "orders.*.address.max"          => "Adres En Fazla 500 Karakter Olabilir",
            "orders.*.description.max"      => "Açıklama En Fazla 500 Karakter Olabilir"
        ];
    }
}
