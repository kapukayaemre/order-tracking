<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderDetailController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(["middleware" => "auth:sanctum"], function () {
    /*** Author Routes */
    Route::apiResource("authors", AuthorController::class);

    /*** Category Routes */
    Route::apiResource("categories", CategoryController::class);

    /*** Discount Routes */
    Route::apiResource("discounts", DiscountController::class);

    /*** Discount Routes */
    Route::apiResource("products", ProductController::class);

    /*** Order Routes */
    Route::apiResource("orders", OrderController::class);

    /*** Order Details Routes */
    Route::apiResource("order-details", OrderDetailController::class);
});

/*** Auth Login */
Route::post('login', [LoginController::class, "login"])->name("login");

