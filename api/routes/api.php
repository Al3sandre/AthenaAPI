<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;

Route::middleware('auth:sanctum')->group(function () {
    // Routes protégées par Sanctum
});

Route::get('/test', function () {
    return response()->json(['message' => 'Laravel en mode API stateless']);
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('categories', CategoryController::class);
