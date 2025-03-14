<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArrivalProductController;

Route::middleware('auth:sanctum')->group(function () {
    // Routes protégées par Sanctum
});

Route::get('/test', function () {
    return response()->json(['message' => 'Laravel en mode API stateless']);
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('arrivals', ArrivalController::class);
Route::apiResource('arrival-products', ArrivalProductController::class);
