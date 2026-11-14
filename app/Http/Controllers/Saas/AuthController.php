<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function form()
    {
        return view('saas.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        if (!Auth::guard('superadmin')->attempt($credentials, false)) {
            return back()->withErrors(['email' => 'The supplied credentials are invalid.'])->withInput($request->only('email'));
        }
        $request->session()->regenerate();
        return redirect()->route('saas.tenants.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('saas.login');
    }
}
