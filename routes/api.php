<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'تم إرسال رابط التحقق!']);
    })->middleware(['throttle:6,1'])->name('verification.send');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $user = User::find($request->id);
    $expires = $request->expires;
    $signature = $request->signature;
    try {
        if (!Hash::check($signature, $user->email_verification_hash . $expires)) {
            throw new Exception('خطأ في رمز التحقق');
        }
        $request->fulfill();
        return response()->json(['message' => 'تم التحقق من بريدك الإلكتروني بنجاح.']);
    } catch (Exception $e) {
        return response()->json(['error' => true, 'message' => $e->getMessage()], 401);
    }
})->middleware(['signed'])->name('verification.verify');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/**
 * Auth routes end
 */