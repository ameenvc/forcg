<?php

namespace App\Providers;

use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class TenancyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(TenantContext::class);
        if (config('tenancy.enabled')) {
            $this->app->make(TenantContext::class)->configureCentral();
            config(['elfinder.route.middleware' => ['web', 'tenant.application', 'auth:web,admin']]);
        }
    }

    public function boot()
    {
        if (!config('tenancy.enabled')) {
            return;
        }
        $this->app['events']->listen(\Illuminate\Console\Events\CommandStarting::class, function ($event) {
            if (in_array($event->command, config('tenancy.tenant_commands'), true)
                && !app(TenantContext::class)->tenant) {
                throw new RuntimeException('Run company tasks with tenants:run and an explicit tenant selection.');
            }
        });
        \App\Tenancy\Hosting::nodes();
        // Shared transport, tenant context restored before queued models are deserialized.
        config([
            'queue.connections.database.connection' => config('tenancy.central_connection'),
            'queue.failed.database' => config('tenancy.central_connection'),
            'queue.connections.database.queue' => 'tenants-' . config('tenancy.node_id'),
        ]);
        Queue::createPayloadUsing(function () {
            $tenant = app(TenantContext::class)->tenant;
            return ['tenant_id' => $tenant ? $tenant->id : null,
                'hosting_node' => $tenant ? \App\Tenancy\Hosting::nodeId($tenant) : config('tenancy.node_id')];
        });
        $this->app->singleton('queue.worker', function ($app) {
            return new \App\Tenancy\TenantWorker(
                $app['queue'], $app['events'], $app[\Illuminate\Contracts\Debug\ExceptionHandler::class],
                function () use ($app) { return $app->isDownForMaintenance(); }
            );
        });
    }
}
