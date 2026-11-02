<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AppConfiguration;

class ProtectMFASetupPage
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
        if ($mfa && $mfa->meta_value == 0) {
            return redirect()->route('user');
        }
        return $next($request);
    }
}
