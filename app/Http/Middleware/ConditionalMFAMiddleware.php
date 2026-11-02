<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AppConfiguration;
use PragmaRX\Google2FALaravel\Middleware as Google2FAMiddleware;
use Illuminate\Support\Facades\Session;
use Log;

class ConditionalMFAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $mfa = AppConfiguration::where('meta_key', 'enable_mfa')->first();
        if ($mfa && $mfa->meta_value) {
            if (auth()->check() && empty(auth()->user()->google2fa_secret)) {
                Session::flash('mfa_message', 'Your organization administrator has enabled multi-factor authentication (MFA). Please complete the setup below to continue.');
                return redirect()->route('2fa.setupPage');
            }
            return app(Google2FAMiddleware::class)->handle($request, $next);
        } else {
            if (auth()->check() && auth()->user()->google2fa_secret && auth()->user()->enable_2fa) {
                return app(Google2FAMiddleware::class)->handle($request, $next);
            }
            return $next($request);
        }
    }
}
