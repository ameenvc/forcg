<?php

namespace App\Http\Middleware;

use App\Tenancy\InitializeTenancy;
use Closure;

class EnsureTenantContext
{
    public function handle($request, Closure $next)
    {
        // Laravel's test kernel may already be bootstrapped by the console kernel.
        if (config('tenancy.enabled') && !$request->attributes->get('tenancy.initialized')) {
            app(InitializeTenancy::class)->bootstrap(app());
        }
        return $next($request);
    }
}
