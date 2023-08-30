<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetail;
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
            ->with(["user","orderDetails"])
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
    public function store(OrderStoreRequest $request): JsonResponse
    {
        $orders = $request->input("orders");

        /*** Order Code */
        $orderCode = "TT-" . Str::uuid()->toString();

        /*** Get all discounts */
        $discounts = Discount::where("status", "active")->get();

        /*** Total Product Count */
        $totalProductCount = 0;

        /*** Total Discount Amount */
        $totalDiscountAmount = 0;

        /*** Total Discount Rate */
        $maxDiscountRate = 0;

        /*** Total Amount */
        $totalAmount = 0;

        /*** Shipping Price */
        $shippingPrice = 0;

        /*** Free product */
        $freeProductId = null;

        foreach ($orders as $order) {
            /*** Get product */
            $product = Product::where("id", $order["product_id"])->first();

            if (!$product) {
                return response()
                    ->json([
                        "status"  => "fail",
                        "message" => "Ürün Bulunamadı Sepetten Çıkarmak İstermisiniz ?"
                    ])
                    ->setStatusCode(404);
            }

            /*** Check Stock */
            if ($product->stock_quantity <= 0) {
                Product::where("id", $product->id)->update([
                    "status" => "passive"
                ]);

                return response()->json([
                    "status"  => "fail",
                    "message" => "İlgili Ürün Stok Tükenmiştir!",
                    "product" => $product
                ])->setStatusCode(404);
            }

            /*** Check requested product and current stock difference */
            if ($product->stock_quantity < $order["quantity"]) {

                return response()->json([
                    "status"      => "fail",
                    "message"     => "Belirttiğiniz miktarda stok bulunmamaktadır!",
                    "available"   => "Mevcut Stok " . $product->stock_quantity,
                    "requested"   => $order["quantity"],
                    "unavailable" => "Talep Edilen ile Stok Farkı " . ($order["quantity"] - $product->stock_quantity),
                    "product"     => $product,
                ])->setStatusCode(404);
            }

            /*** Product Count */
            $totalProductCount += $order["quantity"];

            /*** Total amount */
            $totalAmount += ($order["quantity"] * $product->price);

            /*** Discounts */
            $possibleFreeProduct = [];

            foreach ($discounts as $discount) {
                /*** Get the highest discount */
                if ($totalAmount >= $discount->min_amount) {
                    $maxDiscountRate = max($maxDiscountRate, $discount->discount_rate);
                }

                /*** Check for possible free product */
                if (isset($order["author_id"]) && $order["author_id"] === $discount->author_id && $order["category_id"] === $discount->category_id && $order["quantity"] >= $discount->min_buy_count) {
                    $possibleFreeProduct = [
                        "product_id"   => $order["product_id"],
                        "total_amount" => $order["price"]
                    ];
                }
            }

            /*** optional */
            /*** If customer gets a free product, select the cheapest one */
            if (!empty($possibleFreeProduct)) {
                if (!$freeProductId) {
                    $freeProductId = $possibleFreeProduct["product_id"];
                } else {
                    /*** Daha önce bir hediye ürünü seçilmişse, şimdi hangisi daha ucuzsa o seçilsin */
                    $existingProduct = Product::where("id", $freeProductId)->first();
                    $newProduct = Product::where("id", $possibleFreeProduct["product_id"])->first();

                    if ($newProduct->price < $existingProduct->price) {
                        $freeProductId = $newProduct->id;
                    }
                }
            }
        }

        /*** Shipping price */
        $shippingPrice = ($totalAmount >= 200) ? 0 : 75;

        /*** Discount amount */
        $totalDiscountAmount = $totalAmount * ($maxDiscountRate / 100);

        /*** Save order */
        $mainOrder = Order::create([
            "user_id"           => Auth::user()->id,
            "order_code"        => $orderCode,
            "product_count"     => $totalProductCount,
            "discount_amount"   => $totalDiscountAmount,
            "total_amount"      => $totalAmount,
            "discounted_amount" => $totalAmount - $totalDiscountAmount,
            "shipping_price"    => $shippingPrice,
            "discount_rate"     => ($maxDiscountRate > 0) ? $maxDiscountRate : null
        ]);

        foreach ($orders as $order) {
            /*** Get product */
            $product = Product::where("id", $order["product_id"])->first();


            /*** Save Order Details */
            OrderDetail::create([
                "order_id"           => $mainOrder->id,
                "product_id"         => $product->id,
                "discount_id"        => ($maxDiscountRate > 0) ? $discount->id : null,
                "quantity"           => $order["quantity"],
                "price"              => $product->price,
                "free_product"       => $freeProductId,
                "address"            => $order["address"],
                "description"        => $order["description"] ?? null
            ]);

            /*** Update current stock */
            $product->stock_quantity -= $order["quantity"];

            if ($product->stock_quantity <= 0) {
                Product::where("id", $product->id)->update([
                    "status" => "passive"
                ]);
            }

            $product->save();
        }

        return response()->json([
            "status"  => "success",
            "message" => "Sipariş Başarıyla Kayıt Edildi!"
        ])->setStatusCode(201);
    }




    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with(["user","orderDetails"])->where("id",$id)->first();
        if ($order) {
            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Sipariş Detayları",
                    "order" => $order
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Sipariş Bulunamadı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()
                ->json([
                    "status"  => "fail",
                    "message" => "Güncellenecek sipariş bulunamadı."
                ])
                ->setStatusCode(404);
        }

        $updatedOrders = $request->input("updated_orders");

        /*** Get all discounts */
        $discounts = Discount::where("status", "active")->get();

        /*** Total Product Count */
        $totalProductCount = 0;

        /*** Total Discount Amount */
        $totalDiscountAmount = 0;

        /*** Total Discount Rate */
        $maxDiscountRate = 0;

        /*** Total Amount */
        $totalAmount = 0;

        /*** Shipping Price */
        $shippingPrice = 0;

        /*** Free Product */
        $freeProductId = null;


        foreach ($updatedOrders as $updatedOrder) {
            /*** Get product */
            $product = Product::where("id", $updatedOrder["product_id"])->first();

            if (!$product) {
                return response()
                    ->json([
                        "status"  => "fail",
                        "message" => "Ürün Bulunamadı Sepetten Çıkarmak İstermisiniz ?"
                    ])
                    ->setStatusCode(404);
            }

            /*** Check Stock */
            if ($product->stock_quantity <= 0) {
                Product::where("id", $product->id)->update([
                    "status" => "passive"
                ]);

                return response()->json([
                    "status"  => "fail",
                    "message" => "İlgili Ürün Stok Tükenmiştir!",
                    "product" => $product
                ])->setStatusCode(404);
            }

            /*** Check requested product and current stock difference */
            if ($product->stock_quantity < $updatedOrder["quantity"]) {

                return response()->json([
                    "status"      => "fail",
                    "message"     => "Belirttiğiniz miktarda stok bulunmamaktadır!",
                    "available"   => "Mevcut Stok " . $product->stock_quantity,
                    "requested"   => $updatedOrder["quantity"],
                    "unavailable" => "Talep Edilen ile Stok Farkı " . ($updatedOrder["quantity"] - $product->stock_quantity),
                    "product"     => $product,
                ])->setStatusCode(404);
            }

            /*** Product Count */
            $totalProductCount += $updatedOrder["quantity"];

            /*** Total amount */
            $totalAmount += ($updatedOrder["quantity"] * $product->price);

            /*** Discounts */
            $possibleFreeProduct = [];

            foreach ($discounts as $discount) {
                /*** Get the highest discount */
                if ($totalAmount >= $discount->min_amount) {
                    $maxDiscountRate = max($maxDiscountRate, $discount->discount_rate);
                }

                /*** Check for possible free product */
                if (isset($updatedOrder["author_id"]) && $updatedOrder["author_id"] === $discount->author_id && $updatedOrder["category_id"] === $discount->category_id && $updatedOrder["quantity"] >= $discount->min_buy_count) {
                    $possibleFreeProduct = [
                        "product_id"   => $updatedOrder["product_id"],
                        "total_amount" => $updatedOrder["price"]
                    ];
                }
            }

            /*** optional */
            /*** If customer gets a free product, select the cheapest one */
            if (!empty($possibleFreeProduct)) {
                if (!$freeProductId) {
                    $freeProductId = $possibleFreeProduct["product_id"];
                } else {
                    /*** Daha önce bir hediye ürünü seçilmişse, şimdi hangisi daha ucuzsa o seçilsin */
                    $existingProduct = Product::where("id", $freeProductId)->first();
                    $newProduct = Product::where("id", $possibleFreeProduct["product_id"])->first();

                    if ($newProduct->price < $existingProduct->price) {
                        $freeProductId = $newProduct->id;
                    }
                }
            }
        }

        /*** Shipping price */
        $shippingPrice = ($totalAmount >= 200) ? 0 : 75;

        /*** Discount amount */
        $totalDiscountAmount = $totalAmount * ($maxDiscountRate / 100);


        /*** Update order */
        $updateMainOrder = Order::where("id", $id)->update([
            "product_count"     => $totalProductCount,
            "discount_amount"   => $totalDiscountAmount,
            "total_amount"      => $totalAmount,
            "discounted_amount" => $totalAmount - $totalDiscountAmount,
            "shipping_price"    => $shippingPrice,
            "discount_rate"     => ($maxDiscountRate > 0) ? $maxDiscountRate : null,
            "status"            => $request->input("status") ?? $product->status
        ]);


        foreach ($updatedOrders as $updatedOrder) {
            /*** Get product */
            $product = Product::where("id", $updatedOrder["product_id"])->first();

            /*** Save Order Details */
            OrderDetail::where("product_id", $product->id)->update([
                "order_id"           => $updateMainOrder,
                "product_id"         => $product->id,
                "discount_id"        => ($maxDiscountRate > 0) ? $discount->id : null,
                "quantity"           => $updatedOrder["quantity"],
                "price"              => $product->price,
                "free_product"       => $freeProductId,
                "address"            => $updatedOrder["address"],
                "description"        => $updatedOrder["description"] ?? null,
            ]);

            /*** Update current stock */
            $product->stock_quantity -= $updatedOrder["quantity"];

            if ($product->stock_quantity <= 0) {
                Product::where("id", $product->id)->update([
                    "status" => "passive"
                ]);
            }

            $product->save();
        }

        return response()->json([
            "status"  => "success",
            "message" => "Sipariş Başarıyla Güncellendi!"
        ])->setStatusCode(201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $order = Order::where("id", $id)->first();

        if (!$order) {
            return response()
                ->json([
                    "status"  => "fail",
                    "message" => "Silinecek sipariş bulunamadı!"
                ])
                ->setStatusCode(404);
        }

        /*** Find order details */
       $orderDetails = $order->orderDetails;

        /*** Revert product stocks */
        foreach ($orderDetails as $orderDetail) {
            $product = $orderDetail->product;
            $product->stock_quantity += $orderDetail->quantity;
            $product->status = "active";
            $product->save();
        }

        $order->delete();

        return response()
            ->json([
                "status"  => "success",
                "message" => "Sipariş başarıyla silindi!"
            ])
            ->setStatusCode(200);

    }
}
