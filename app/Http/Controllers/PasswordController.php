<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        try {
            // Validate the email field
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            // Send the reset link to the provided email
            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'تم إرسال رابط إعادة تعيين كلمة المرور!'], 200)
                : response()->json(['message' => 'تعذر إرسال الرابط. حاول مرة أخرى.'], 500);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء العملية: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            // Validate the input fields
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:8|confirmed',
                'token' => 'required'
            ]);

            // Attempt to reset the password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'تم تغيير كلمة المرور بنجاح.'], 200)
                : response()->json(['message' => 'فشل في إعادة تعيين كلمة المرور.'], 500);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء العملية: ' . $e->getMessage(),
            ], 500);
        }
    }
}
