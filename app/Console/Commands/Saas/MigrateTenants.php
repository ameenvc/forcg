<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use Illuminate\Console\Command;

class MigrateTenants extends Command
{
    protected $signature = 'tenants:migrate {--tenant=} {--all}';
    protected $description = 'Apply application migrations to selected provisioned tenant databases';

    public function handle(TenantContext $context)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        if ((bool) $this->option('all') === (bool) $this->option('tenant')) {
            $this->error('Choose exactly one of --all or --tenant=slug.'); return 1;
        }
        $tenants = Tenant::whereNotNull('provisioned_at')->when($this->option('tenant'), function ($q) {
            $q->where('slug', $this->option('tenant'));
        })->get();
        if ($tenants->isEmpty()) { $this->error('No provisioned tenants matched.'); return 1; }
        $status = 0;
        foreach ($tenants as $tenant) {
            try {
                $context->activate($tenant, true);
                if ($this->call('migrate', ['--database' => 'tenant', '--force' => true]) !== 0) { $status = 1; }
            } catch (\Throwable $e) {
                $this->error($tenant->slug . ': ' . $e->getMessage()); $status = 1;
            } finally { $context->end(); }
        }
        return $status;
    }
}
