<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserProfileController;

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



Route::prefix('v1')->group(function () {
    Route::post('login', [UserController::class, 'userLogin']);
    Route::get('brand', [BrandController::class, 'index']);
    Route::apiResource('category', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('product', ProductController::class)->only(['index', 'show']);
    Route::get('product/brand/{brand}', [ProductController::class, 'byBrand']);
    Route::get('product/category/{category}', [ProductController::class, 'byCategory']);

    Route::middleware('token')->group(function () {
        Route::post('verify-otp', [UserController::class, 'verifyOTP']);
        Route::get('profile', [UserProfileController::class, 'readProfile']);
        Route::post('profile', [UserProfileController::class, 'createProfile']);
        Route::apiResource('brand', BrandController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('category', CategoryController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('product', ProductController::class)->only(['store', 'update', 'destroy']);
    });
    
});

