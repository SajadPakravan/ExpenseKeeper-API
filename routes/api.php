<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmailController;
use App\Http\Controllers\API\PhoneController;
use App\Http\Controllers\API\transaction\TransactionController;
use App\Http\Controllers\API\UserController;

Route::post('/auth/sign-up', [AuthController::class, 'signUp']);
Route::post('/auth/sign-in', [AuthController::class, 'signIn']);
Route::post('/auth/send-verify-code', [AuthController::class, 'sendVerifyCode']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/sign-out', [AuthController::class, 'signOut']);
    Route::get('/user', [UserController::class, 'userInfo']);
    Route::post('/user/username', [UserController::class, 'username']);
    Route::post('/user/name', [UserController::class, 'name']);
    Route::post('/email/verify-code', [EmailController::class, 'sendVerifyCode']);
    Route::post('/user/email', [UserController::class, 'email']);
    Route::post('/phone/verify-code', [PhoneController::class, 'sendVerifyCode']);
    Route::post('/user/phone', [UserController::class, 'phone']);
    Route::post('/user/avatar', [UserController::class, 'avatar']);
    Route::post('/user/password', [UserController::class, 'password']);
    Route::post('/transactions', [TransactionController::class, 'create']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::get('/transactions', [TransactionController::class, 'getAll']);
    Route::get('/transactions/{id}', [TransactionController::class, 'getOne']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'delete']);
});
