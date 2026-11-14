<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use App\Tenancy\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class ProvisionTenant extends Command
{
    protected $signature = 'tenants:provision {slug} {--schema= : Sanitized baseline SQL file with schema and reference data}';
    protected $description = 'Create and initialize an empty database for a pending tenant';

    public function handle(TenantContext $context, TenantManager $manager)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        $tenant = Tenant::where('slug', $this->argument('slug'))->firstOrFail();
        if ($tenant->status !== 'pending') { $this->error('Only pending tenants can be provisioned.'); return 1; }
        $password = $this->secret('Tenant owner password (at least 12 characters)');
        if (strlen((string) $password) < 12) { $this->error('Password is too short.'); return 1; }
        if ($this->option('schema') && !is_file($this->option('schema'))) { $this->error('Baseline SQL file does not exist.'); return 1; }
        // Atomic claim prevents concurrent provisioning. Never reuse or drop an existing database.
        $claimed = Tenant::where('id', $tenant->id)->where('status', 'pending')->update(['status' => 'provisioning']);
        if (!$claimed) { $this->error('Another process claimed this tenant.'); return 1; }
        try {
            if (!preg_match('/^[a-zA-Z0-9_]{1,64}$/D', $tenant->database_name)) { throw new RuntimeException('Invalid database name.'); }
            $central = DB::connection(config('tenancy.central_connection'));
            if ($central->getDriverName() !== 'mysql') { throw new RuntimeException('Provisioning requires MySQL.'); }
            $central->statement('CREATE DATABASE `' . $tenant->database_name . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            $context->activate($tenant, true);
            if ($this->option('schema')) {
                // Operator-controlled trusted baseline; never accepts a tenant-supplied upload.
                $sql = file_get_contents($this->option('schema'));
                if (preg_match('/\b(USE|CREATE\s+DATABASE|DROP\s+DATABASE)\b/i', $sql)) {
                    throw new RuntimeException('Baseline must not select or create/drop databases.');
                }
                DB::connection('tenant')->getPdo()->exec($sql);
            } else {
                if ($this->call('migrate', ['--database' => 'tenant', '--force' => true]) !== 0 ||
                    $this->call('db:seed', ['--database' => 'tenant', '--force' => true]) !== 0) {
                    throw new RuntimeException('Legacy schema initialization failed. Use a validated baseline or adopt an initialized database.');
                }
            }
            DB::table('super_admins')->delete(); // Never inherit seeded platform credentials.
            $values = ['name' => $tenant->name . ' administrator', 'email' => $tenant->owner_email,
                'password' => Hash::make($password), 'type' => 1, 'approved' => 1,
                'employeeid' => 'OWNER', 'logdevices' => 0, 'is_email_verified' => 1,
                'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()];
            foreach (['admins', 'users'] as $table) {
                if (DB::table($table)->exists()) { throw new RuntimeException('Baseline must not contain user or admin accounts.'); }
                $row = array_intersect_key($values, array_flip(Schema::getColumnListing($table)));
                if ($table === 'admins') { $row['appname'] = $tenant->slug; $row['appname_to_display'] = $tenant->name; }
                DB::table($table)->insert($row);
            }
            DB::table('companysize')->where('id', 1)->update(['name' => $tenant->name, 'email' => $tenant->owner_email, 'loginsperperson' => 5]);
            $context->end();
            $tenant->update(['status' => 'active', 'provisioned_at' => now()]);
            $manager->audit($tenant, 'provisioned');
            $this->info('Tenant ready: ' . $tenant->url());
            return 0;
        } catch (\Throwable $e) {
            $context->end();
            $tenant->update(['status' => 'failed']);
            $manager->audit($tenant, 'provisioning_failed');
            $this->error($e->getMessage());
            $this->warn('Any partially created database was preserved for inspection.');
            return 1;
        }
    }
}
