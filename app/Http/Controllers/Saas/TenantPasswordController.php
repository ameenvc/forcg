<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TenantPasswordController extends Controller
{
    public function requestCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $request->session()->forget(['saas_reset_email', 'saas_reset_until', 'saas_reset_hash']);
        if (DB::table('users')->where('email', $request->email)->exists()) {
            $code = (string) random_int(100000, 999999);
            $request->session()->put([
                'verificationemail' => $request->email, 'saas_reset_hash' => Hash::make($code),
                'saas_code_until' => time() + 600, 'saas_code_attempts' => 0,
            ]);
            Mail::to($request->email)->send(new ForgotPassword([
                'value' => '1', 'line1' => 'Hello,',
                'line2' => 'You requested a password reset for ' . config('app.name') . '.',
                'line3' => 'Your verification code is ' . $code . '. It expires in 10 minutes.',
                'line4' => 'If you did not request this reset, no action is required.',
            ]));
        }
        return back()->with('status', 'If this account exists, a verification code has been sent.');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['vcode' => 'required|digits:6']);
        $session = $request->session();
        $attempts = $session->get('saas_code_attempts', 0);
        $session->put('saas_code_attempts', $attempts + 1);
        if ($attempts >= 5 || time() > $session->get('saas_code_until', 0)
            || !$session->get('saas_reset_hash') || !Hash::check((string) $request->vcode, $session->get('saas_reset_hash'))) {
            return back()->withErrors(['vcode' => 'The code is invalid or expired. Request a new code.']);
        }
        $email = $session->get('verificationemail');
        $session->forget(['saas_reset_hash', 'verificationemail']);
        $session->put(['saas_reset_email' => $email, 'saas_reset_until' => time() + 600]);
        $session->regenerate();
        return view('auth.passwords.reset', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $session = $request->session();
        $email = $session->get('saas_reset_email');
        abort_unless($email && $email === $request->email && time() <= $session->get('saas_reset_until', 0), 403);
        $request->validate(['password' => 'required|string|min:12|confirmed']);
        DB::transaction(function () use ($email, $request) {
            $values = ['password' => Hash::make($request->password), 'remember_token' => Str::random(60)];
            DB::table('users')->where('email', $email)->update($values);
            DB::table('admins')->where('email', $email)->update($values);
        });
        $session->forget(['saas_reset_email', 'saas_reset_until']);
        $session->regenerateToken();
        return redirect()->route('login')->with('resetsuccess', 'Password changed. Sign in with your new password.');
    }
}
