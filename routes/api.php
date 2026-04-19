<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\ProfileController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::middleware('role:admin')->group(function () {
        Route::get('admin/stats', [AuthController::class, 'stats']);
        Route::apiResource('users', AuthController::class)->only(['index', 'destroy']);
    });

    Route::middleware('role:recruteur,admin')->group(function () {
        Route::apiResource('offres', OffreController::class);
    });

    Route::middleware('role:candidate')->group(function () {
        Route::get('candidat/offres', [OffreController::class, 'index']);
        Route::post('profile', [ProfileController::class, 'update']);
        Route::get('profile', [ProfileController::class, 'show']);
    });

});
