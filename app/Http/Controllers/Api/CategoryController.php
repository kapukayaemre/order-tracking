<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        /*** Categories cached for one minute */
        $categories = Cache::remember("categories", 60, function () {
           return Category::query()
               ->where("status", "active")
               ->with("product")
               ->get();
        });

        return response()
            ->json([
                "status"     => "success",
                "message"    => "Kategori Listesi",
                "categories" => $categories
            ])
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $insertCategory = Category::query()->create([
            "title" => $request->input("title")
        ]);

        if ($insertCategory) {
            return response()
                ->json([
                    "status"   => "success",
                    "message"  => "Kategori Başarıyla Kayıt Edildi",
                    "category" => $insertCategory
                ])
                ->setStatusCode(201);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kategori Kayıt İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $category = Category::find($id);

        if ($category) {
            return response()
                ->json([
                    "status"   => "success",
                    "message"  => "Kategori Detayları",
                    "category" => $category
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kategori Bulunamadı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        $category = Category::find($id);

        $updateCategory = Category::where("id", $id)->update([
            "title"  => $request->input("title"),
            "status" => $request->input("status") ?? $category->status
        ]);

        $updatedCategory = Category::find($id);

        if ($updateCategory) {
            return response()
                ->json([
                    "status"   => "success",
                    "message"  => "Kategori Başarıyla Güncellendi",
                    "category" => $updatedCategory
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kategori Güncelleme İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $deletedCategory = Category::find($id);
        $deleteCategory  = Category::where("id", $id)->delete();

        if ($deleteCategory) {
            return response()
                ->json([
                    "status"   => "sucess",
                    "message"  => "Kategori Başarıyla Silindi",
                    "category" => $deletedCategory
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Kategori Silme İşlemi Başarısız Sonuçlandı!",
            ])
            ->setStatusCode(400);
    }
}
