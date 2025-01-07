<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'تم إرسال رابط التحقق!']);
    })->middleware(['throttle:6,1'])->name('verification.send');
});


Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = User::findOrFail($request->id);

    if ($user->email_verified_at) {
        return response()->json(['message' => 'البريد الإلكتروني مفعل بالفعل.']);
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
        return response()->json(['message' => 'تم التحقق من بريدك الإلكتروني.']);
    }

    return response()->json(['message' => 'خطاء في التحقق من بريدك الإلكتروني.']);
    
})->middleware(['signed'])->name('verification.verify');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

