<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $orders = $request->input("orders");

        foreach ($orders as $order) {
            /*** Get product */
            $product = Product::findOrFail($order["product_id"]);

            /*** Check Stock */
            if ($product->stock_quantity <= 0) {
                Product::where("id", $product->id)->update([
                    "status" => "passive"
                ]);

                return response()->json([
                    "status" => "fail",
                    "message" => "İlgili Ürün Stok Tükenmiştir!"
                ])->setStatusCode(404);
            }

            /*** Check requested product and current stock difference */
            if ($product->stock_quantity < $order["quantity"]) {

                return response()->json([
                    "status" => "fail",
                    "message" => "Belirttiğiniz miktarda stok bulunmamaktadır!",
                    "available" => "Mevcut Stok " . $product->stock_quantity,
                    "request" => $order["quantity"],
                    "unavailable" => "Talep Edilen ile Stok Farkı " . ($order["quantity"] - $product->stock_quantity),
                    "product" => $product,
                ])->setStatusCode(404);
            }

            /*** Check discounts */
            $discounts = Discount::where("status", "active")->get();

            /*** Discounts */
            $maxDiscountRate = 0;
            $possibleFreeProduct = [];

            foreach ($discounts as $discount) {
                /*** Get the highest discount */
                if ($order["total_amount"] >= $discount->min_amount) {
                    $maxDiscountRate = max($maxDiscountRate, $discount->discount_rate);
                }

                /*** Check for possible free product */
                if ($order["author_id"] === $discount->author_id && $order["category_id"] === $discount->category_id && $order["quantity"] >= $discount->min_buy_count) {
                    $possibleFreeProduct[] = [
                        "product_id" => $order["product_id"],
                        "total_amount" => $order["total_amount"]
                    ];
                }
            }

            /*** If customer get free product then select most cheapest product */
            if (!empty($possibleFreeProduct)) {
                $cheapestProduct = min($possibleFreeProduct, function ($productA, $productB) {
                    return $productA["total_amount"] - $productB["total_amount"];
                });

                $freeProductId = $cheapestProduct[0]["product_id"];
            } else {
                $freeProductId = null;
            }

            /*** Total amount */
            $totalAmount = ($order["quantity"] * $product->price);

            /*** Shipping price */
            $shippingPrice = ($totalAmount >= 200) ? 0 : 75;

            /*** Discount amount */
            $discountAmount = $totalAmount * ($maxDiscountRate / 100);

            /*** Discounted amount */
            $discountedAmount = $totalAmount - $discountAmount;

            // Siparişi kaydet
            $order = Order::create([
                "user_id" => Auth::user()->id,
                "product_id" => $product->id,
                "discount_id" => ($maxDiscountRate > 0) ? $discount->id : null,
                "order_code" => "TT-" . Str::uuid()->toString(),
                "quantity" => $order["quantity"],
                "total_amount" => $totalAmount,
                "shipping_price" => $shippingPrice,
                "free_product" => $freeProductId,
                "discount" => ($maxDiscountRate > 0) ? $maxDiscountRate : null,
                "description" => $order["description"] ?? null,
                "status" => "active"
            ]);

            // Stok miktarını güncelle
            $product->stock_quantity -= $order["quantity"];

            if ($product->stock_quantity <= 0) {
                Product::where("id", $product->id)->update([
                    "status" => "passive"
                ]);
            }

            $product->save();
        }

        return response()->json([
            "status" => "success",
            "message" => "Sipariş Başarıyla Kayıt Edildi"
        ])->setStatusCode(201);
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
