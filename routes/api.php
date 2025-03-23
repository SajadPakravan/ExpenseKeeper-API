<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/sign-out', [AuthController::class, 'signOut']);
    Route::get('/user', [UserController::class, 'userInfo']);
    Route::post('/user', [UserController::class, 'editUser']);
    Route::post('/user/email', [UserController::class, 'email']);
    Route::post('/user/phone', [UserController::class, 'phone']);
    Route::post('/user/avatar', [UserController::class, 'avatar']);
});
