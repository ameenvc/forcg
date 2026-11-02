<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web,admin');
        $this->middleware('protectMFASetupPage')->only(['setup2faPage']);
    }

    public function sendOtpToMail()
    {
        $user = auth()->user();
        try {
            $otp = random_int(100000, 999999);

            $user->mail_otp = $otp;
            $user->save();

            Mail::to(auth()->user()->email)->send(new TwoFactorOtpMail($otp));

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully.'
            ]);

        } catch (\Exception $e) {

            Log::error('OTP mail sending failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Unable to send OTP. Please try after some time.'
            ], 200);
        }
    }

    public function verifyMailOtp(Request $request)
    {
        $user = auth()->user();
        $mail_otp = $request->mail_otp;

        if ($user->mail_otp == $mail_otp) {
            return response()->json([
                'status' => true,
                'message' => 'Email Verified.'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email Not Verified.'
            ], 200);
        }
    }

    public function setup2fa(Request $request)
    {
        $user = auth()->user();
        $google2fa = app('pragmarx.google2fa');

        // Generate a new secret if the user doesn't have one
        if (!$user->google2fa_setup_secret) {
            $user->google2fa_setup_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_setup_secret
        );

        return response()->json(['QR_Image' => $QR_Image, 'secret' => $user->google2fa_setup_secret]);
    }

    public function setupComplete(Request $request)
    {
        $user = auth()->user();

        // Validate the entered OTP before completing setup
        $request->validate([
            'one_time_password' => 'required',
        ]);

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->google2fa_setup_secret, $request->one_time_password);


        if ($valid) {
            // Set the session variable so they don't have to verify again immediately
            $user->google2fa_secret = $user->google2fa_setup_secret;
            $user->google2fa_setup_secret = null;
            $user->enable_2fa = true;
            $user->save();
            Log::info('valid saved');
            $request->session()->put(config('google2fa.session_var'), true);
            return response()->json(['status' => 1, 'message' => 'MFA setup is complete. Enter the 6-digit verification code from your authenticator app to complete your sign-in.']);
        }

        return response()->json(['status' => 0, 'message' => 'Invalid OTP. Please try again.']);
    }

    public function userEnableDisable2FA(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'enable_2fa' => 'required',
        ]);
        $enable2FA = filter_var($request->enable_2fa, FILTER_VALIDATE_BOOLEAN);
        $user->enable_2fa = $enable2FA;
        $user->save();

        if ($enable2FA) {
            $google2fa = app('pragmarx.google2fa');
            $request->session()->put(config('google2fa.session_var'), true);
            return response()->json(['status' => 1, 'message' => 'Enabled Two Factor Authentication']);
        } else {
            return response()->json(['status' => 0, 'message' => 'Disabled Two Factor Authentication']);
        }
    }

    public function setup2faPage(Request $request)
    {

        return view('google2fa.initial_setup');
    }

    public function login(Request $request)
    {
        $user = auth()->user();

        // Validate the entered OTP before completing setup
        $request->validate([
            'one_time_password' => 'required',
        ]);

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password);


        if ($valid) {
            // Set the session variable so they don't have to verify again immediately
            Log::info('valid saved');
            $request->session()->put(config('google2fa.session_var'), true);
            Log::info(session()->all());
            return response()->json(['status' => 1, 'message' => '2FA Setup Complete!']);
        }

        return response()->json(['status' => 0, 'message' => 'Invalid OTP. Please try again.']);
    }
}
