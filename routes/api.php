<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// http://127.0.0.1:8000/api/products
Route::get('/products', [ProductController::class, 'index']);
// http://127.0.0.1:8000/api/products/slug-name
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// behind the auth
Route::middleware('auth:sanctum')->group(function () {
   
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
