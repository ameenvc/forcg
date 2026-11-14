<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SaasAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('superadmin')->check()) {
            return redirect()->route('saas.login');
        }
        Auth::shouldUse('superadmin');
        return $next($request);
    }
}
