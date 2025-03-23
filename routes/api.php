<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\tools\DatabaseController;
use App\Http\Controllers\API\AuthController;

Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/tools/db', [DatabaseController::class, 'manageDatabase']);

Route::middleware('auth:sanctum')->group(function () {
    // Route::post('/tools/db', [DatabaseController::class, 'manageDatabase']);
});
