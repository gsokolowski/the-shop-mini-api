<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\AuthenticateSanctum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// http://127.0.0.1:8000/api/products
Route::get('/products', [ProductController::class, 'index']);
// http://127.0.0.1:8000/api/products/slug-name
Route::get('/products/{slug}', [ProductController::class, 'show']);

// behind the auth
Route::middleware([AuthenticateSanctum::class])->group(function () {
   
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
});
