<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerificationController extends Controller
{
    
    # Verify the user's email
    public function verifyEmail(Request $request)
    {
        try {

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



    # Resend the email verification notification
    public function resendVerificationEmail(Request $request)
    {
        try {

            // Send the email verification notification
            $request->user()->sendEmailVerificationNotification();

            // Prepare the response
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رابط التحقق!'
            ], 200);
        } catch (\Throwable $e) {
            // Handle any exceptions and return a detailed error message
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء العملية: ' . $e->getMessage()
            ], 500);
        }
    }


    # Verification notice
    public function verificationNotice()
    {
        return response()->json([
            'message' => 'يرجى التحقق من بريدك الإلكتروني.'
        ]);
    }
}
