<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\TenantManager;
use Illuminate\Console\Command;

class RegisterTenant extends Command
{
    protected $signature = 'tenants:create {slug} {name} {email} {--plan=starter} {--node= : Assigned hosting node ID}';
    protected $description = 'Register a pending tenant for database provisioning';

    public function handle(TenantManager $manager)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        $tenant = $manager->register(['slug' => $this->argument('slug'), 'name' => $this->argument('name'),
            'owner_email' => $this->argument('email'), 'plan' => $this->option('plan'),
            'hosting_node' => $this->option('node') ?: config('tenancy.node_id')]);
        $this->info('Registered. Next: php artisan tenants:provision ' . $tenant->slug);
        return 0;
    }
}
