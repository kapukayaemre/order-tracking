<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $orders = Order::query()
            ->where("status", "active")
            ->with(["user", "product"])
            ->get();

        return response()
            ->json([
                "status"  => "success",
                "message" => "Sipariş Listesi",
                "orders"  => $orders
            ])
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        /*** Check Stock */
        $productStock = Product::find($request->input("product_id"));

        if ($productStock->stock_quantity <= 0) {
            /*** Product Status Set to Passive*/
            $updateProduct = Product::where("id", $productStock->id)->update([
                "status" => "passive"
            ]);

            return response()
                ->json([
                    "status"  => "fail",
                    "message" => "İlgili Ürün Stok Tükenmiştir!"
                ])
                ->setStatusCode(404);
        }

        /*** Stock Difference with Request */
        if ($productStock->stock_quantity < $request->input("quantity")) {

            $unavailableQuantity = $request->input("quantity") - $productStock->stock_quantity;

            return response()
                ->json([
                    "status"        => "fail",
                    "message"       => "Belirttiğiniz miktarda stok bulunmamaktadır!",
                    "available"     => "Mevcut Stok ".$productStock->stock_quantity,
                    "request"       => $request->input("quantity"),
                    "unavailable"   => "Talep Edilen ile Stok Farkı ".$unavailableQuantity
                ])
                ->setStatusCode(404);
        }

        /*** Discounts */
        $discounts = Discount::where("status", "active")->get();

        /*** Highest Discount Rate */
        $discountRate = '';
        $discountCount = '';
        foreach ($discounts as $discount) {
            /*** Get max discount rate for amount */
            if ($request->input("amount") > $discount->min_amount) {
                $discountRate = $discount->discount_rate;
            }

            /*if ($request->input("quantity") > $discount->min_buy_count) {

            }*/

        }


        dd($discountRate);


        $insertOrder = Order::query()->create([
            "user_id"       => $request->input("user_id"),
            "product_id"    => $request->input("product_id"),
            "discount_rate" => $request->input("discount_rate"),
            "order_code"    => "TT-" . Str::uuid()->toString(),
            "quantity"      => $request->input("quantity"),
            "amount"        => $request->input("amount"),
            "description"   => $request->input("description")
        ]);

        if ($insertOrder) {

            /*** Decrease Stock Quantity */

            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Sipariş Başarıyla Kayıt Edildi",
                    "order"   => $insertOrder
                ])
                ->setStatusCode(201);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Sipariş Kayıt İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
