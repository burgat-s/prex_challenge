<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/gifs/search', [GifController::class, 'search']);
    Route::get('/gifs/{id}', [GifController::class, 'findById']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
});