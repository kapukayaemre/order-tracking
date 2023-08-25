<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorStoreRequest;
use App\Http\Requests\AuthorUpdateRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $authors = Author::query()->where("status", "active")->get();
        return response()
            ->json([
                "status"  => "success",
                "message" => "Yazar Listesi",
                "authors" => $authors
            ])
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorStoreRequest $request): JsonResponse
    {
        $insertAuthor = Author::query()->create([
            "name" => $request->input("name")
        ]);

        if ($insertAuthor) {
            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Yazar Başarıyla Kayıt Edildi",
                    "author"  => $insertAuthor
                ])
                ->setStatusCode(201);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Yazar Kayıt İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $author = Author::find($id);

        if ($author) {
            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Yazar Detayları",
                    "author"  => $author
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Yazar Bulunamadı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorUpdateRequest $request, int $id): JsonResponse
    {
        $author = Author::find($id);

        $updateAuthor = Author::where("id", $id)->update([
            "name"   => $request->input("name"),
            "status" => $request->input("status") ?? $author->status
        ]);

        $updatedAuthor = Author::find($id);

        if ($updateAuthor) {
            return response()
                ->json([
                    "status"  => "success",
                    "message" => "Yazar Başarıyla Güncellendi",
                    "author"  => $updatedAuthor
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Yazar Güncelleme İşlemi Başarısız Sonuçlandı!"
            ])
            ->setStatusCode(400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $deletedAuthor = Author::find($id);
        $deleteAuthor  = Author::where("id", $id)->delete();

        if ($deleteAuthor) {
            return response()
                ->json([
                    "status"  => "sucess",
                    "message" => "Yazar Başarıyla Silindi",
                    "author"  => $deletedAuthor
                ])
                ->setStatusCode(200);
        }

        return response()
            ->json([
                "status"  => "fail",
                "message" => "Yazar Silme İşlemi Başarısız Sonuçlandı!",
            ])
            ->setStatusCode(400);
    }
}
