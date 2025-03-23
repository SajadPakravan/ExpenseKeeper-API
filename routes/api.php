<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/sign-out', [AuthController::class, 'signOut']);
});
