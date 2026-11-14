<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use Cache;
use Carbon\Carbon;
class LastUserActivity
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
        if (config('tenancy.enabled') && !app(\App\Tenancy\TenantContext::class)->tenant) {
            return $next($request);
        }

        if(Auth::check()){
            $expires=Carbon::now()->addMinutes(3);
            Cache::put('user-is-online-'.Auth::user()->email,true,$expires);
        }
        return $next($request);
    }
}
