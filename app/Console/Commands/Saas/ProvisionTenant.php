<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\Hosting;
use App\Tenancy\Tenant;
use App\Tenancy\TenantProvisioner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ProvisionTenant extends Command
{
    protected $signature = 'tenants:provision {slug} {--schema= : Sanitized baseline SQL file with schema and reference data}';
    protected $description = 'Create and initialize an empty database for a pending tenant';

    public function handle(TenantProvisioner $provisioner)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        $tenant = Tenant::where('slug', $this->argument('slug'))->firstOrFail();
        if (!Hosting::isLocal($tenant)) { $this->error('Run provisioning on hosting node: ' . Hosting::nodeId($tenant)); return 1; }
        if ($tenant->status !== 'pending') { $this->error('Only pending tenants can be provisioned.'); return 1; }
        $password = $this->secret('Tenant owner password (at least 12 characters)');
        if (strlen((string) $password) < 12) { $this->error('Password is too short.'); return 1; }
        try {
            $provisioner->provision($tenant, Hash::make($password), $this->option('schema'));
            $this->info('Tenant ready: ' . $tenant->url());
            return 0;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            $this->warn('Any partially created database was preserved for inspection.');
            return 1;
        }
    }
}
