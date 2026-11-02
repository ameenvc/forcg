<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UserVerify;
use Illuminate\Support\Facades\Auth;
//use Response;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['checkSession']]);
    }


    public function checkSession()
    {
        return response()->json(['guest' => Auth::guest()]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if ($user && !$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }

        return redirect()->route('login')->with('message', $message);
    }
}
