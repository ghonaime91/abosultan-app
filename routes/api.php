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


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    try {
        $user = User::find($request->route('id'));
        // التحقق إذا كان المستخدم موجودًا
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        // التحقق من صحة الطلب
        if (!$request->hasValidSignature()) {
            return response()->json([
                'success' => false,
                'message' => 'رابط التحقق غير صالح أو منتهي الصلاحية'
            ], 400);
        }

        // تسجيل دخول المستخدم مؤقتًا
        Auth::login($user);

        // تنفيذ التحقق
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'بريدك الإلكتروني تم التحقق منه مسبقًا.'
            ]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق من بريدك الإلكتروني بنجاح.'
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء التحقق',
            'error' => $e->getMessage()
        ], 500);
    }
})->middleware(['signed'])->name('verification.verify');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

