<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Jobs\StoreProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = Cache::remember("products", 60, function () {
           return Product::query()
               ->where("status", "active")
               ->where("stock_quantity", ">", 0)
               ->with(["category", "author"])
               ->get();
        });

        return response()
            ->json([
                "status"   => "success",
                "message"  => "Ürün Listesi",
                "products" => $products
            ])
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        $productData = [
            "category_id"    => $request->input("category_id"),
            "author_id"      => $request->input("author_id"),
            "discount_id"    => $request->input("discount_id"),
            "title"          => $request->input("title"),
            "description"    => $request->input("description"),
            "price"          => $request->input("price"),
            "stock_quantity" => $request->input("stock_quantity")
        ];

        StoreProduct::dispatch($productData);

        return response()
            ->json([
                "status"  => "success",
                "message" => "Ürün Kayıt İşlemi Kuyruğa Eklendi"
            ])
            ->setStatusCode(202);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with(["author", "category"])->find($id);

        if ($product) {
            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Ürün Detayları",
                    "product" => $product
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Ürün Bulunamadı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        $updateProduct = Product::where("id", $id)->update([
            "category_id"    => $request->input("category_id"),
            "author_id"      => $request->input("author_id"),
            "discount_id"    => $request->input("discount_id"),
            "title"          => $request->input("title"),
            "description"    => $request->input("description"),
            "price"          => $request->input("price"),
            "stock_quantity" => $request->input("stock_quantity"),
            "status"         => $request->input("status") ?? $product->status
        ]);

        $updatedProduct = Product::find($id);

        if ($updateProduct) {
            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Ürün Başarıyla Güncellendi",
                    "product" => $updatedProduct
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Ürün Güncelleme İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deletedProduct = Product::find($id);
        $deleteProduct  = Product::where("id", $id)->delete();

        if ($deleteProduct) {
            return response()
                ->json([
                    "status"  => "sucess",
                    "message" => "Ürün Başarıyla Silindi",
                    "product"  => $deletedProduct
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Ürün Silme İşlemi Başarısız Sonuçlandı!",
            ])
            ->setStatusCode(400);
    }
}
