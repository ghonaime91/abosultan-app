<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

/**
 * Auth routes start
 */

# Register a new user route
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/verify', function () {
        return response()->json(['message' => 'يرجى التحقق من بريدك الإلكتروني.']);
    })->name('verification.notice');

    Route::post('/email/verification-notification',
     [VerificationController::class, 'resendVerificationEmail']
    )->middleware(['throttle:6,1'])->name('verification.send');
});

# Verify email route
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

