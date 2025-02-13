<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Validation\ValidationException;
use Ichtrojan\Otp\Otp;

class EmailVerificationController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    /**
     * Verify the user's email using OTP.
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        try {
            // Find the user by email
            $user = User::where('email', $request->email)->first();

            // If the user does not exist, return an error response
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.account_not_found')
                ], 404);
            }

            // Check if the email is already verified
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.verified_already')
                ], 200);
            }

            // Verify OTP
            $otpValidation = $this->otp->validate($user->email, $request->otp);
            if (!$otpValidation->status) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.invalid_otp')
                ], 400);
            }

            // Mark the email as verified
            $user->markEmailAsVerified();
            event(new Verified($user));

            return response()->json([
                'success' => true,
                'message' => __('messages.verified_success')
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('exceptions.internal_server_error')
            ], 500);
        }
    }

    /**
     * Resend the email verification OTP.
     */

     public function resendVerificationEmail(Request $request)
     {
         try {
             // Validate request (email is required)
             $request->validate([
                 'email' => 'required|email'
             ]);
     
             // Find the user by email
             $user = User::where('email', $request->email)->first();
     
             // If the user does not exist, return an error response
             if (!$user) {
                 return response()->json([
                     'success' => false,
                     'message' => __('messages.account_not_found')
                 ], 404);
             }
     
             // Check if the email is already verified
             if ($user->email_verified_at) {
                 return response()->json([
                     'success' => false,
                     'message' => __('messages.verified_already')
                 ], 200);
             }
     
             // Send the OTP via email notification
             $user->notify(new EmailVerificationNotification());
     
             return response()->json([
                 'success' => true,
                 'message' => __('messages.otp_resend')
             ], 200);

         } catch (ValidationException $e) {
             return response()->json([
                 'success' => false,
                 'message' => $e->validator->errors()->first() 
             ], 422);

         } catch (\Throwable $e) {
             return response()->json([
                 'success' => false,
                 'message' => __('exceptions.internal_server_error')
             ], 500);
         }
     }
    

    /**
     * Show a verification notice message.
     */
    public function verificationNotice()
    {
        return response()->json([
            'success' => true,
            'message' => __('messages.verify_notice')
        ],200);
    }
}
