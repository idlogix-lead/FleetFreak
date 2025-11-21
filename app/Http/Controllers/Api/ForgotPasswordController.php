<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    // public function sendResetLinkEmail(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //         ? response()->json(['message' => __($status)], 200)
    //         : response()->json(['message' => __($status)], 400);
    // }
    public function sendResetOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        $otp = mt_rand(100000, 999999); // Generate a 6-digit OTP
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(15); // OTP expires in 15 minutes
        $user->save();

        // Send OTP to user's email
        Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    public function verifyOtp(Request $request)
    {
        // Validate incoming request data 
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'integer'],
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Extract inputs from the request
        $otp = $request->input('otp');

        // Find the user with the provided OTP that hasn't expired
        $user = User::where('otp', $otp)
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        // If no user found with valid OTP, return error response
        if (!$user) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // Return success message or any relevant data
        return response()->json(['message' => 'OTP verification successful.'], 200);
    }

    public function resetPassword(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Extract inputs from the request
        $password = $request->input('password');

        // Retrieve the user based on previously verified OTP
        $user = User::where('otp', $request->input('otp'))
            ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        // If no user found with valid OTP, return error response
        if (!$user) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // Reset password
        $user->password = Hash::make($password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Return success message
        return response()->json(['message' => 'Password reset successful.'], 200);
    }
}
