<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PasswordController;


/**
 * Auth routes start
 */

# Register a new user route
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

# Login route
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/**
 * Auth routes end
 */

######################################################
######################################################

/**
 * Verification routes start
 */

# Verify email routes
Route::middleware('auth:sanctum')->group(function () {

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

/**
 * Verification routes end
 */

######################################################
######################################################

/**
 * Reset password routes start
 */

# Reset password routes
Route::middleware('auth:sanctum')->group(function () {

    # Reset password routes
    Route::post('/password/email', [PasswordController::class, 'sendResetLinkEmail'])
    ->name('password.reset');

 
    Route::post('/password/reset', [PasswordController::class, 'resetPassword'])
    ->name('password.update');
});