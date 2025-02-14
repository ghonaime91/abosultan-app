<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\PasswordController;

Route::middleware('appLanguage')->prefix('{locale}')->group(function () {

    // Register a new user route
    Route::post('/register', [RegistrationController::class, 'register'])
        ->middleware('throttle:3,10')
        ->name('register');

    // Login route
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:4,1')
        ->name('login');

    // Logout route
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    // Verify email routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/email/verify', [EmailVerificationController::class, 'verifyEmail'])
            ->name('email_verify');

        Route::post('/email/resend', [EmailVerificationController::class, 'resendVerificationEmail'])
            ->middleware('throttle:2,1') 
            ->name('resend_email_verification');

        Route::get('/email/notice', [EmailVerificationController::class, 'verificationNotice'])
            ->name('email_verify_notify');
    });


});