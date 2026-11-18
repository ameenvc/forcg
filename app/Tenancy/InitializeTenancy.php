<?php

namespace App\Tenancy;

class InitializeTenancy
{
    public function bootstrap($app)
    {
        if (!config('tenancy.enabled')) {
            return;
        }

        $context = $app->make(TenantContext::class);
        $context->configureCentral();
        $request = $app['request'];
        $request->attributes->set('tenancy.initialized', true);
        $host = strtolower($request->getHost());
        $base = strtolower(config('tenancy.base_domain'));
        if (in_array($host, [$base, strtolower(config('tenancy.admin_domain'))], true)) {
            $context->central($host);
            return;
        }

        $slug = Hostname::slug($host, $base, config('tenancy.host_prefix'));
        abort_unless($slug !== null, 404);
        // Eloquent's resolver is installed during provider boot, which has not run yet.
        $record = \Illuminate\Support\Facades\DB::connection(config('tenancy.central_connection'))
            ->table('saas_tenants')->where('slug', $slug)->where('status', 'active')->first();
        abort_unless($record, 404);
        $tenant = new Tenant;
        $tenant->setRawAttributes((array) $record, true);
        $tenant->exists = true;
        $context->activate($tenant);
        $request->attributes->set('tenant', $tenant);
    }
}
