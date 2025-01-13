<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    # Login a user
    public function login(Request $request)
    {
        try {
            // Validate the input data
            $fields = $request->validate([
                'email'    => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Check if the user exists
            $user = User::where('email', $fields['email'])->first();

            // Return an error response if user not found or password incorrect
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.failed')
                ], 401);
            }

            // Create the sanctum login token
            $token = $user->createToken('MyApp')->plainTextToken;

            return response([
                'success' => true,
                'message' => __('auth.login_success'),
                'user'    => $user,
                'token'   => $token
            ], 200);

        } catch (ValidationException $e) {

            // In case of validation errors, return the validation errors
            return response()->json([
                'success' => false,
                'message' => __('exceptions.validation_error'),
                'errors'  => $e->errors() 
            ], 422);
        
        } catch (\Exception $e) {
            // In case of any other error, return a general error response
            return response()->json([
                'success' => false,
                'message' => __('exceptions.internal_server_error'),
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    # Logout the user
    public function logout(Request $request)
    {
        try {
            // Revoke the current user's token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => __('auth.logout_success')
            ], 200);
            
        } catch (\Exception $e) {
            // In case of any error, return a general error response
            return response()->json([
                'success' => false,
                'message' => __('exceptions.internal_server_error'),
                'error'   => $e->getMessage()        
            ], 500);
        }
    }


}
