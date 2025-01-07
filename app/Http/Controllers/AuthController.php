<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{

    # Register a new user
    public function register(Request $request)
    {
        try {
            // Validate the input data
            $fields = $request->validate([
                'name' => 'required|string',

                'email' => [
                    'required',
                    'string',
                    'unique:users,email',
                    'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]{2,10}$/'
                    ],
                    
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:6',
                    'regex:/[A-Z]/',
                    'regex:/[@$!%*?&#]/',
                ],

            ]);

            // Create the user
            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password'])
            ]);

            // Send email verification notification
            event(new Registered($user));

            // Create the token
            $token = $user->createToken('abosultanApp')->plainTextToken;

            // Prepare the response
            $response = [
                'success' => true,
                'message' => 'تم تسجيل حساب جديد بنجاح. يرجى التحقق من بريدك الإلكتروني.',
                'user' => $user,
                'token' => $token
            ];

            return response($response, 201);

        } catch (ValidationException $e) {

            // In case of validation errors, return the validation errors
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors() 
            ], 422);

        } catch (\Exception $e) {

            // In case of any other error, return a general error response
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الخادم',
                'error' => $e->getMessage()
            ], 500);

        }
    }



    # Login a user
    public function login(Request $request)
    {
        try {
            // Validate the input data
            $fields = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            // Check if the user exists
            $user = User::where('email', $fields['email'])->first();

            if (!$user || !Hash::check($fields['password'], $user->password)) {
                // Return error response if user not found or password incorrect
                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'
                ], 401);
            }

            // Create the token
            $token = $user->createToken('abosultanApp')->plainTextToken;

            // Prepare the response
            $response = [
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح',
                'user' => $user,
                'token' => $token
            ];

            return response($response, 200);

        } catch (ValidationException $e) {

            // In case of validation errors, return the validation errors
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors() 
            ], 422);
        
        } catch (\Exception $e) {

            // In case of any other error, return a general error response
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الخادم',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    # Logout the user
    public function logout(Request $request)
    {
        try {
            // Revoke the current user's token
            $request->user()->currentAccessToken()->delete();

            // Prepare the response
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الخروج بنجاح'
            ], 200);
            
        } catch (\Exception $e) {

            // In case of any error, return a general error response
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الخادم',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    # Verify the user's email
    public function verify(Request $request)
    {
        try {
            // Check if the signature is valid
            if (!$request->hasValidSignature()) {
                return response()->json([
                    'success' => false,
                    'message' => 'رابط التحقق غير صالح أو منتهي الصلاحية.'
                ], 400);
            }

            // Find the user using the provided ID
            $user = User::findOrFail($request->id);

            // Check if the email is already verified
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'البريد الإلكتروني مفعل بالفعل.'
                ], 200);
            }

            // Mark the email as verified
            if ($user->markEmailAsVerified()) {
                // Trigger the verified event after successful verification
                event(new Verified($user));
                return response()->json([
                    'success' => true,
                    'message' => 'تم التحقق من بريدك الإلكتروني بنجاح.'
                ], 200);
            }

            // In case of an error during email verification
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التحقق من بريدك الإلكتروني.'
            ], 500);

        } catch (\Throwable $e) {
            // Handle any exceptions and return a detailed error message
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء العملية: ' . $e->getMessage()
            ], 500);
        }
    }

}
