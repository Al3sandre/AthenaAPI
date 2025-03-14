<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ArrivalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ArrivalProductController;

Route::middleware('auth:sanctum')->group(function () {

});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('arrivals', ArrivalController::class);
Route::apiResource('arrival-products', ArrivalProductController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('cart-items', CartItemController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
