<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use App\Tenancy\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AdoptTenant extends Command
{
    protected $signature = 'tenants:adopt {slug} {name} {database} {email} {--files= : Existing public/storage directory to COPY}';
    protected $description = 'Attach an existing company database without deleting or rewriting its records';

    public function handle(TenantContext $context, TenantManager $manager)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        $database = $this->argument('database');
        if (!preg_match('/^[a-zA-Z0-9_]{1,64}$/D', $database) || Tenant::where('database_name', $database)->exists()) {
            $this->error('Database name is invalid or already attached.'); return 1;
        }
        if ($this->option('files') && !is_dir($this->option('files'))) {
            $this->error('The files directory does not exist.'); return 1;
        }
        $tenant = $manager->register(['slug' => $this->argument('slug'), 'name' => $this->argument('name'),
            'owner_email' => $this->argument('email'), 'plan' => 'starter']);
        $tenant->update(['database_name' => $database]);
        try {
            $context->activate($tenant, true);
            foreach (['users', 'admins', 'companysize', 'client_details', 'modenadisable'] as $table) {
                if (!Schema::hasTable($table)) { throw new \RuntimeException('Existing database is missing: ' . $table); }
            }
            if ($this->option('files')) {
                $source = realpath($this->option('files'));
                $destination = public_path('storage');
                if ($source === realpath($destination) || strpos(realpath($destination), $source . DIRECTORY_SEPARATOR) === 0) { throw new \RuntimeException('Source and destination directories must differ.'); }
                if (!File::copyDirectory($source, $destination)) { throw new \RuntimeException('Could not copy the company uploads.'); }
            }
            $context->end();
            $tenant->update(['status' => 'active', 'provisioned_at' => now()]);
            $manager->audit($tenant, 'adopted');
            $this->info('Existing company ready: ' . $tenant->url());
            return 0;
        } catch (\Throwable $e) {
            $context->end();
            $tenant->update(['status' => 'failed']);
            $manager->audit($tenant, 'adoption_failed');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
