<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordController;

Route::middleware('appLanguage')->prefix('{locale}')->group(function () {

    // Register a new user route
    Route::post('/register', [RegistrationController::class, 'register'])
        ->name('register');

    // Login route
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    // Logout route
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    // Verify email routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/email/verify', [EmailVerificationController::class, 'verifyEmail']);
        Route::post('/email/resend', [EmailVerificationController::class, 'resendVerificationEmail']);
        Route::get('/email/notice', [EmailVerificationController::class, 'verificationNotice']);
    });


});