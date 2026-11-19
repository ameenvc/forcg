<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use Illuminate\Console\Command;

class RunForTenants extends Command
{
    protected $signature = 'tenants:run {task} {--tenant=} {--all}';
    protected $description = 'Run a scheduled application task with explicit tenant context';

    public function handle(TenantContext $context)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        if (!in_array($this->argument('task'), config('tenancy.tenant_commands'), true)) {
            $this->error('Task is not in tenancy.tenant_commands.'); return 1;
        }
        if ((bool) $this->option('all') === (bool) $this->option('tenant')) {
            $this->error('Choose exactly one of --all or --tenant=slug.'); return 1;
        }
        $tenants = Tenant::hostedLocally()->where('status', 'active')->when($this->option('tenant'), function ($q) {
            $q->where('slug', $this->option('tenant'));
        })->get();
        if ($tenants->isEmpty()) { $this->error('No active tenants matched.'); return 1; }
        $status = 0;
        foreach ($tenants as $tenant) {
            try {
                $context->activate($tenant);
                if ($this->call($this->argument('task')) !== 0) { $status = 1; }
            } catch (\Throwable $e) {
                $this->error($tenant->slug . ': ' . $e->getMessage());
                $status = 1;
            } finally { $context->end(); }
        }
        return $status;
    }
}
