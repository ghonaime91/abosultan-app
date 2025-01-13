<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\PasswordController;

Route::middleware('appLanguage')->prefix('{locale}')->group(function () {

# Register a new user route
Route::post('/register', [RegistrationController::class, 'register'])
    ->name('register');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Verify email routes
Route::middleware('auth:sanctum')->group(function () {

    # Logout route
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    # Verify email notice
    Route::get('/email/verify',[VerificationController::class, 'verificationNotice'])
        ->name('verification.notice');

    # Resend email verification notification
    Route::post('/email/verification-notification',
    [VerificationController::class, 'resendVerificationEmail'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

# Verify email 
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

# Reset password routes
Route::post('/forgot-password/email', [PasswordController::class, 'sendResetLinkEmail'])
->name('password.reset');

Route::get('/password/email', [PasswordController::class, 'viewResetForm'])
->name('password.reset');

Route::post('/password/reset', [PasswordController::class, 'resetPassword'])
->name('password.update');

});