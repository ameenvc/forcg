<?php

namespace App\Tenancy;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class TenantContext
{
    public $tenant;
    private $snapshot;
    private $storagePath;
    private $publicPath;

    public function configureCentral()
    {
        $name = config('tenancy.central_connection');
        if (!config('database.connections.' . $name)) {
            $source = config('database.connections.' . config('tenancy.source_connection'));
            if (!$source) {
                throw new RuntimeException('The tenancy source database connection is not configured.');
            }
            config(['database.connections.' . $name => $source]);
        }
    }

    public function central($host = null)
    {
        $this->configureCentral();
        DB::setDefaultConnection(config('tenancy.central_connection'));
        config([
            'session.domain' => null,
            'session.cookie' => 'cgbod_central_session',
            'queue.connections.database.connection' => config('tenancy.central_connection'),
        ]);
    }

    public function activate(Tenant $tenant, $allowInactive = false)
    {
        if ($this->tenant) {
            throw new RuntimeException('A tenant context is already active.');
        }
        if (!$allowInactive && $tenant->status !== 'active') {
            throw new RuntimeException('Tenant is not active.');
        }
        if (!preg_match('/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/D', $tenant->slug)) {
            throw new RuntimeException('Invalid tenant slug.');
        }
        Hosting::assertLocal($tenant);
        $this->configureCentral();
        $central = DB::connection(config('tenancy.central_connection'));
        $connection = Hosting::databaseConfig(); // Resolve this node's database settings, independently of the registry.
        if (!in_array($connection['driver'], ['mysql', 'sqlite'], true)) {
            throw new RuntimeException('Tenancy supports MySQL (and SQLite for tests).');
        }
        if ($tenant->database_name === $central->getDatabaseName() ||
            ($connection['driver'] === 'mysql' && !preg_match('/^[a-zA-Z0-9_]{1,64}$/D', $tenant->database_name))) {
            throw new RuntimeException('Tenant must use its own database.');
        }

        $this->snapshot = config()->all();
        $this->storagePath = app()->storagePath();
        $this->publicPath = app('path.public');
        $this->tenant = $tenant;
        $root = config('tenancy.storage_root') . '/' . $tenant->slug;
        try {
            $this->prepareDirectories($root);
            unset($connection['name']);
            $connection['url'] = null;
            $connection['database'] = $tenant->database_name;
            // Legacy backup code explicitly reads mysql; it must see tenant credentials too.
            config(['database.connections.tenant' => $connection, 'database.connections.mysql' => $connection]);
            DB::purge('tenant');
            DB::purge('mysql');
            DB::setDefaultConnection('tenant');
            app()->useStoragePath($root);
            app()->tenantPublicPath = $root . '/public';
            app()->instance('path.public', $root . '/public');
            config([
                'app.url' => $tenant->url(),
                'app.name' => $tenant->name,
                'session.domain' => null,
                'session.cookie' => 'cgbod_' . $tenant->slug . '_session',
                'session.files' => $root . '/framework/sessions',
                'session.connection' => 'tenant',
                'cache.default' => 'file',
                'cache.prefix' => 'cgbod_' . $tenant->slug,
                'cache.stores.file.path' => $root . '/framework/cache/data',
                'cache.stores.database.connection' => 'tenant',
                'filesystems.default' => 'public',
                'filesystems.disks.local.root' => $root . '/app',
                'filesystems.disks.public.root' => $root . '/public/storage',
                'filesystems.disks.public.url' => $tenant->url() . '/storage',
                'filesystems.disks.s3.root' => 'tenants/' . $tenant->slug,
                'filesystems.disks.azure.prefix' => 'tenants/' . $tenant->slug,
                'queue.connections.database.connection' => config('tenancy.central_connection'),
                'queue.failed.database' => config('tenancy.central_connection'),
                'elfinder.route.middleware' => ['web', 'tenant.application', 'auth:web,admin'],
                'logging.channels.single.path' => $root . '/logs/laravel.log',
                'logging.channels.daily.path' => $root . '/logs/laravel.log',
            ]);
            $this->forgetServices();
            app('url')->forceRootUrl($tenant->url());
            app('url')->forceScheme(config('tenancy.scheme'));
        } catch (\Throwable $e) {
            $this->end();
            throw $e;
        }
    }

    public function end()
    {
        if (!$this->tenant) {
            return;
        }
        DB::purge('tenant');
        DB::purge('mysql');
        foreach ($this->snapshot as $key => $value) {
            config([$key => $value]);
        }
        app()->useStoragePath($this->storagePath);
        app()->tenantPublicPath = null;
        app()->instance('path.public', $this->publicPath);
        app('url')->forceRootUrl(null);
        app('url')->forceScheme(null);
        $this->tenant = null;
        $this->snapshot = null;
        $this->forgetServices();
    }

    private function forgetServices()
    {
        foreach (['auth', 'auth.driver', 'cache', 'cache.store', 'filesystem', 'session', 'session.store', 'mail.manager', 'mailer'] as $service) {
            app()->forgetInstance($service);
            \Illuminate\Support\Facades\Facade::clearResolvedInstance($service);
        }
    }

    private function prepareDirectories($root)
    {
        foreach (['app', 'public/storage', 'public/files', 'public/uploads', 'framework/sessions',
            'framework/cache/data', 'logs'] as $path) {
            if (!File::isDirectory($root . '/' . $path)) {
                File::makeDirectory($root . '/' . $path, 0770, true);
            }
        }
        // public_path() in Laravel 7 reads path.public directly. Shared read-only assets
        // remain available to existing PDF templates and asset version helpers.
        foreach (['css', 'js', 'images', 'fonts', 'packages', 'media', 'mix-manifest.json', 'favicon.ico'] as $asset) {
            $target = base_path('public/' . $asset);
            $link = $root . '/public/' . $asset;
            if (file_exists($target) && !file_exists($link) && !is_link($link)) {
                File::link($target, $link);
            }
        }
        foreach (config('tenancy.upload_directories', []) as $path) {
            if (!File::isDirectory($root . '/public/' . $path)) {
                File::makeDirectory($root . '/public/' . $path, 0770, true);
            }
        }
    }
}
