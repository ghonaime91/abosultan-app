<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


# Register a new user route
Route::post('/register', [AuthController::class, 'register']);

# Login route
Route::post('/login', [AuthController::class, 'login']);

# Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);