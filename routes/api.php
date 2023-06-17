<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GoogleController;

/*
|--------------------------------------------------------------------------
|                                API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => 'jwt'], function () {
    Route::get('/home', [HomeController::class, 'getWeather']);
    Route::get('/check-auth', [AuthController::class, 'getUser']);
});

Route::group(['prefix' => 'google'], function () {
    Route::get('/get-url', [GoogleController::class, 'getUrl']);
    Route::any('/login', [GoogleController::class, 'loginWithGoogleData']);
});


