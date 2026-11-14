<?php

namespace App\Console\Commands\Saas;

use App\SuperAdmin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class InstallSaas extends Command
{
    protected $signature = 'saas:install {email} {--name=Platform administrator}';
    protected $description = 'Install the central SaaS registry and create a platform administrator';

    public function handle()
    {
        if (!config('tenancy.enabled')) {
            $this->error('Configure a dedicated central database and enable TENANCY_ENABLED first.');
            return 1;
        }
        if (!filter_var($this->argument('email'), FILTER_VALIDATE_EMAIL)) {
            $this->error('A valid email is required.');
            return 1;
        }
        $schema = \Illuminate\Support\Facades\Schema::connection(config('tenancy.central_connection'));
        if ($schema->hasTable('users') || $schema->hasTable('admins')) {
            $this->error('The platform database contains company tables. Use a dedicated empty database and adopt the company separately.');
            return 1;
        }
        $result = $this->call('migrate', ['--database' => config('tenancy.central_connection'), '--path' => 'database/migrations/central', '--force' => true]);
        if ($result !== 0) {
            return $result;
        }
        if (SuperAdmin::where('email', $this->argument('email'))->exists()) {
            $this->info('Platform administrator already exists.');
            return 0;
        }
        $password = $this->secret('New platform password (at least 12 characters)');
        if (strlen((string) $password) < 12) {
            $this->error('Password must have at least 12 characters.');
            return 1;
        }
        SuperAdmin::create(['name' => $this->option('name'), 'email' => $this->argument('email'), 'password' => Hash::make($password)]);
        $this->info('Platform ready: ' . config('tenancy.scheme') . '://admin.' . config('tenancy.base_domain') . '/login');
        return 0;
    }
}
