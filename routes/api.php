<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/**
 * Auth routes start
 */

# Register a new user route
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
    
# Verify email routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])
        ->name('verification.notice');

    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationNotification'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

