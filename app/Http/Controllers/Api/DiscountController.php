<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountStoreRequest;
use App\Http\Requests\DiscountUpdateRequest;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $discounts = Discount::query()
            ->where("status", "active")
            ->with(["product", "author", "category"])
            ->get();

        return response()
            ->json([
                "status"    => "success",
                "message"   => "Kampanya Listesi",
                "discounts" => $discounts
            ])
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountStoreRequest $request): JsonResponse
    {
        $product = Product::where("id", $request->input("product_id"))
            ->with(["category"])
            ->first();

        $insertDiscount = Discount::query()->create([
            "product_id"    => $product->id ?? null,
            "author_id"     => $request->input("author_id") ?? $product->author_id ?? null,
            "category_id"   => $request->input("category_id") ?? $product->category_id ?? null,
            "min_amount"    => $request->input("min_amount"),
            "discount_rate" => $request->input("discount_rate"),
            "min_buy_count" => $request->input("min_buy_count"),
            "free_count"    => $request->input("free_count")
        ]);

        if ($insertDiscount) {
            return response()
                ->json([
                    "status"   => "success",
                    "message"  => "Kampanya Başarıyla Kayıt Edildi",
                    "discount" => $insertDiscount
                ])
                ->setStatusCode(201);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kampanya Kayıt İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $discount = Discount::with(["product","category","author"])->find($id);

        if ($discount) {
            return response()
                ->json([
                    "status"   => "success",
                    "message"  => "Kampanya Detayları",
                    "discount" => $discount
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kampanya Bulunamadı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountUpdateRequest $request, int $id): JsonResponse
    {
        $discount = Discount::with(["author", "category", "product"])->find($id);

        $updateDiscount = Discount::where("id", $id)->update([
            "product_id"    => $discount->product_id ?? null,
            "author_id"     => $request->input("author_id") ?? $discount->author_id ?? null,
            "category_id"   => $request->input("category_id") ?? $discount->category_id ?? null,
            "min_amount"    => $request->input("min_amount"),
            "discount_rate" => $request->input("discount_rate"),
            "min_buy_count" => $request->input("min_buy_count"),
            "free_count"    => $request->input("free_count"),
            "status"        => $request->input("status") ?? $discount->status
        ]);

        $updatedDiscount = Discount::find($id);

        if ($updateDiscount) {
            return response()
                ->json([
                    "status"   => "success",
                    "message"  => "Kampanya Başarıyla Güncellendi",
                    "discount" => $updatedDiscount
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kampanya Güncelleme İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $deletedDiscount = Discount::find($id);
        $deleteDiscount  = Discount::where("id", $id)->delete();

        if ($deleteDiscount) {
            return response()
                ->json([
                    "status"   => "sucess",
                    "message"  => "Kampanya Başarıyla Silindi",
                    "discount" => $deletedDiscount
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kampanya Silme İşlemi Başarısız Sonuçlandı!",
            ])
            ->setStatusCode(400);
    }
}
