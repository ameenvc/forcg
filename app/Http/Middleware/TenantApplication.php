<?php

namespace App\Http\Middleware;

use App\Tenancy\TenantContext;
use Closure;

class TenantApplication
{
    public function handle($request, Closure $next)
    {
        if (config('tenancy.enabled')) {
            abort_unless(app(TenantContext::class)->tenant, 404);
            $action = $request->route()->getActionName();
            abort_if(strpos($action, 'SuperAdminController@') !== false || $request->is('superadmin', 'superadmin/*'), 404);
            // Legacy SQL/ZIP restore accepts arbitrary SQL/paths and is not exposed to tenants.
            abort_if($request->is('backup/restore/*', 'restorefiles', 'confirmation'), 403);
        }
        return $next($request);
    }
}
