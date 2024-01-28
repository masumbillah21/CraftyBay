<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

    Route::middleware('token')->group(function () {
        Route::post('verify-otp', [UserController::class, 'verifyOTP']);
        Route::get('profile', [UserProfileController::class, 'readProfile']);
        Route::post('profile', [UserProfileController::class, 'createProfile']);
        Route::apiResource('brand', BrandController::class)->except(['index', 'create', 'edit']);
    });
    
});

