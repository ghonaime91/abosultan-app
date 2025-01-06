<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



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

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return response()->json(['message' => 'تم التحقق من بريدك الإلكتروني بنجاح.']);
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'تم إرسال رابط التحقق!']);
    })->middleware(['throttle:6,1'])->name('verification.send');
});


# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/**
 * Auth routes end
 */