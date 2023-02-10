<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('/validtoken' , 'validToken');
    Route::post('register', 'register')->name('register.user');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::middleware('auth:api')->group(function () {
    Route::get('/ping' , function () {
        return 'pong';
    });
});
