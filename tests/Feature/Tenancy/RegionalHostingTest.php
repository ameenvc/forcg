<?php

namespace Tests\Feature\Tenancy;

use App\SuperAdmin;
use App\Tenancy\Hosting;
use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use App\Tenancy\TenantManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RegionalHostingTest extends TestCase
{
    private $temporary;

    public function createApplication()
    {
        foreach ($this->environment() as $key => $value) {
            $_ENV[$key] = $_SERVER[$key] = $value;
        }
        return parent::createApplication();
    }

    private function environment()
    {
        return ['TENANCY_ENABLED' => 'true', 'TENANCY_SOURCE_CONNECTION' => 'sqlite',
            // Deliberately retain the old admin-valued setting: the tenant setting wins.
            'TENANCY_BASE_DOMAIN' => 'devsaas.cgbod.test', 'TENANCY_TENANT_BASE_DOMAIN' => 'cgbod.test',
            'TENANCY_ADMIN_DOMAIN' => 'devsaas.cgbod.test', 'TENANCY_HOST_PREFIX' => '',
            'TENANCY_NODE_ID' => 'local', 'TENANCY_HOSTING_NODES' => json_encode([
                'local' => ['name' => 'Primary server', 'region' => 'Home region'],
                'europe' => ['name' => 'EU server', 'region' => 'Europe'],
            ]), 'TENANCY_SCHEME' => 'http', 'APP_URL' => 'http://devsaas.cgbod.test',
            'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->temporary = sys_get_temp_dir() . '/cgbod-regions-' . bin2hex(random_bytes(8));
        mkdir($this->temporary, 0750, true);
        config(['tenancy.storage_root' => $this->temporary . '/storage']);
        require_once database_path('migrations/central/2026_11_07_000000_create_saas_tenants_table.php');
        (new \CreateSaasTenantsTable)->up();
        require_once database_path('migrations/central/2026_11_19_000000_add_hosting_node_to_saas_tenants.php');
        (new \AddHostingNodeToSaasTenants)->up();
        require_once database_path('migrations/central/2026_11_19_010000_create_saas_operations_table.php');
        (new \CreateSaasOperationsTable)->up();
        require_once database_path('migrations/central/2026_11_19_020000_create_saas_cloudflare_tables.php');
        (new \CreateSaasCloudflareTables)->up();
        Route::middleware(['web', 'tenant.application'])->get('/__region/probe', function () {
            return response()->json(['secret' => DB::table('records')->value('secret'),
                'database_host' => DB::connection()->getConfig('host')]);
        });
    }

    protected function tearDown(): void
    {
        app(TenantContext::class)->end();
        DB::purge('regional_source');
        File::deleteDirectory($this->temporary);
        parent::tearDown();
        foreach (array_keys($this->environment()) as $key) {
            unset($_ENV[$key], $_SERVER[$key]);
        }
    }

    private function tenant($slug, $node = 'local')
    {
        $tenant = app(TenantManager::class)->register(['name' => ucfirst($slug), 'slug' => $slug,
            'owner_email' => 'owner@example.test', 'plan' => 'starter', 'hosting_node' => $node]);
        $database = $this->temporary . '/' . $slug . '.sqlite';
        touch($database);
        config(['database.connections.fixture' => array_merge(config('database.connections.sqlite'), ['database' => $database])]);
        DB::purge('fixture');
        Schema::connection('fixture')->create('records', function (Blueprint $t) { $t->increments('id'); $t->string('secret'); });
        DB::connection('fixture')->table('records')->insert(['secret' => $slug . '-private']);
        DB::purge('fixture');
        $tenant->update(['database_name' => $database, 'status' => 'active', 'provisioned_at' => now()]);
        return $tenant;
    }

    public function test_tenant_address_is_independent_of_admin_host_and_hosting_region()
    {
        $tenant = $this->tenant('waraq', 'europe');
        $this->assertSame('http://waraq.cgbod.test', $tenant->url());
        $this->assertSame('europe', $tenant->hosting_node);
        $this->assertSame('Europe · EU server', $tenant->hostingLabel());
        SuperAdmin::create(['name' => 'Platform', 'email' => 'admin@example.test', 'password' => Hash::make('platform-password')]);
        $this->post('http://devsaas.cgbod.test/login', ['email' => 'admin@example.test', 'password' => 'platform-password'])->assertRedirect('http://devsaas.cgbod.test/tenants');
        $this->get('http://devsaas.cgbod.test/tenants')->assertOk()->assertSee('waraq.cgbod.test')->assertSee('Hosting location')->assertSee('Europe');
        $this->post('http://devsaas.cgbod.test/tenants', ['name' => 'Regional Customer', 'slug' => 'regional-customer', 'owner_email' => 'owner@example.test', 'plan' => 'starter', 'hosting_node' => 'europe'])->assertRedirect();
        $this->assertSame('europe', Tenant::where('slug', 'regional-customer')->value('hosting_node'));
    }

    public function test_wrong_server_never_opens_another_nodes_tenant_database()
    {
        $tenant = $this->tenant('waraq', 'europe');
        $this->get('http://waraq.cgbod.test/__region/probe')->assertNotFound();
        $this->assertFalse(is_dir($this->temporary . '/storage/waraq'));
        $this->expectException(\RuntimeException::class);
        app(TenantContext::class)->activate($tenant);
    }

    public function test_assigned_server_uses_its_configured_tenant_database_connection()
    {
        $tenant = $this->tenant('waraq', 'europe');
        touch($this->temporary . '/source.sqlite');
        config(['tenancy.node_id' => 'europe', 'tenancy.tenant_connection' => 'regional_source',
            'database.connections.regional_source' => array_merge(config('database.connections.sqlite'), [
                'database' => $this->temporary . '/source.sqlite', 'host' => 'regional-database.example.test',
            ])]);
        $this->get('http://waraq.cgbod.test/__region/probe')->assertOk()->assertJson(['secret' => 'waraq-private', 'database_host' => 'regional-database.example.test']);
        $this->assertNull(app(TenantContext::class)->tenant);
        $this->assertSame(':memory:', DB::connection('saas_central')->getDatabaseName());
    }

    public function test_background_tasks_only_visit_tenants_hosted_on_this_node()
    {
        $this->tenant('local-customer');
        $this->tenant('remote-customer', 'europe');
        $this->assertSame(['local-customer'], Tenant::hostedLocally()->pluck('slug')->all());
        config(['tenancy.tenant_commands' => ['test:regional-task']]);
        $seen = [];
        Artisan::command('test:regional-task', function () use (&$seen) { $seen[] = app(TenantContext::class)->tenant->slug; });
        $this->assertSame(0, Artisan::call('tenants:run', ['task' => 'test:regional-task', '--all' => true]));
        $this->assertSame(['local-customer'], $seen);
        $this->assertSame(1, Artisan::call('tenants:migrate', ['--tenant' => 'remote-customer']));
    }

    public function test_remote_provisioning_is_rejected_before_claiming_or_creating_a_database()
    {
        $tenant = app(TenantManager::class)->register(['name' => 'Waraq', 'slug' => 'waraq', 'owner_email' => 'owner@example.test', 'plan' => 'starter', 'hosting_node' => 'europe']);
        $this->assertSame(1, Artisan::call('tenants:provision', ['slug' => 'waraq']));
        $this->assertStringContainsString('hosting node: europe', Artisan::output());
        $this->assertSame('pending', $tenant->fresh()->status);
    }

    public function test_database_queues_are_partitioned_and_tagged_by_node()
    {
        $tenant = $this->tenant('local-customer');
        app(TenantContext::class)->activate($tenant);
        Queue::connection('database')->push(new \stdClass);
        app(TenantContext::class)->end();
        $job = DB::connection('saas_central')->table('jobs')->first();
        $this->assertSame('tenants-local', $job->queue);
        $this->assertSame('local', json_decode($job->payload, true)['hosting_node']);
        $tenant->update(['hosting_node' => 'europe']);
        $this->assertSame(0, Artisan::call('queue:work', ['connection' => 'database', '--once' => true, '--tries' => 1, '--sleep' => 0]));
        $this->assertSame(1, DB::connection('saas_central')->table('jobs')->count());
        $this->assertNull(app(TenantContext::class)->tenant);
    }

    public function test_unconfigured_nodes_and_the_admin_slug_cannot_be_registered()
    {
        try {
            app(TenantManager::class)->register(['name' => 'Reserved', 'slug' => 'devsaas', 'owner_email' => 'owner@example.test', 'plan' => 'starter', 'hosting_node' => 'unknown']);
            $this->fail('Reserved slug and unknown node were accepted.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('slug', $e->errors());
            $this->assertArrayHasKey('hosting_node', $e->errors());
        }
    }
    private function platformLogin()
    {
        $admin = SuperAdmin::create(['name' => 'Platform', 'email' => 'admin@example.test', 'password' => Hash::make('platform-password')]);
        $this->actingAs($admin, 'superadmin');
        return $admin;
    }

    private function ownerTables(Tenant $tenant)
    {
        app(TenantContext::class)->activate($tenant);
        foreach (['users', 'admins'] as $table) {
            Schema::connection('tenant')->create($table, function (Blueprint $t) {
                $t->increments('id'); $t->string('email'); $t->string('password'); $t->string('remember_token')->nullable();
            });
            DB::connection('tenant')->table($table)->insert([
                ['email' => $tenant->owner_email, 'password' => Hash::make('original-owner-password'), 'remember_token' => 'old-token'],
                ['email' => 'employee@example.test', 'password' => 'employee-unchanged', 'remember_token' => 'employee-token'],
            ]);
        }
        app(TenantContext::class)->end();
    }

    public function test_dashboard_registration_queues_hashed_password_for_assigned_server()
    {
        $admin = $this->platformLogin();
        $this->post('http://devsaas.cgbod.test/tenants', ['name' => 'Automatic', 'slug' => 'automatic',
            'owner_email' => 'owner@example.test', 'plan' => 'starter', 'hosting_node' => 'europe',
            'password' => 'new-owner-password-2026', 'password_confirmation' => 'new-owner-password-2026'])->assertRedirect();
        $operation = DB::connection('saas_central')->table('saas_operations')->first();
        $this->assertSame('europe', $operation->hosting_node);
        $this->assertSame('queued', $operation->status);
        $this->assertSame($admin->id, (int) $operation->actor_id);
        $this->assertTrue(Hash::check('new-owner-password-2026', $operation->password_hash));
        $this->assertSame(0, app(\App\Tenancy\TenantOperations::class)->process());
        $this->get('http://devsaas.cgbod.test/tenants')->assertSee('Setup: queued')->assertDontSee($operation->password_hash);
    }

    public function test_automatic_provisioning_executes_once_and_clears_password_material()
    {
        $tenant = app(TenantManager::class)->register(['name' => 'New', 'slug' => 'new-customer', 'owner_email' => 'owner@example.test', 'plan' => 'starter']);
        $id = app(\App\Tenancy\TenantOperations::class)->enqueue($tenant, 'provision', 'new-owner-password');
        $calls = 0;
        $mock = \Mockery::mock(\App\Tenancy\TenantProvisioner::class);
        $mock->shouldReceive('provision')->once()->andReturnUsing(function ($customer, $hash) use (&$calls, $tenant) {
            $calls++; $this->assertSame($tenant->id, $customer->id);
            $this->assertTrue(Hash::check('new-owner-password', $hash));
            $customer->update(['status' => 'active', 'provisioned_at' => now()]);
        });
        $this->app->instance(\App\Tenancy\TenantProvisioner::class, $mock);
        $this->assertSame(1, app(\App\Tenancy\TenantOperations::class)->process());
        $this->assertSame(0, app(\App\Tenancy\TenantOperations::class)->process());
        $operation = DB::connection('saas_central')->table('saas_operations')->find($id);
        $this->assertSame('succeeded', $operation->status);
        $this->assertNull($operation->password_hash);
        $this->assertSame(1, $calls);
    }

    public function test_superadmin_resets_only_selected_owner_and_suspension_is_preserved()
    {
        $a = $this->tenant('customer-a'); $b = $this->tenant('customer-b');
        $this->ownerTables($a); $this->ownerTables($b);
        $a->update(['status' => 'suspended']);
        $admin = $this->platformLogin();
        $this->post('http://devsaas.cgbod.test/tenants/' . $a->id . '/operations', [
            'type' => 'reset_password', 'password' => 'replacement-owner-password', 'password_confirmation' => 'replacement-owner-password',
        ])->assertRedirect();
        $this->assertSame(1, app(\App\Tenancy\TenantOperations::class)->process());
        $this->assertSame('suspended', $a->fresh()->status);
        app(TenantContext::class)->activate($a, true);
        foreach (['users', 'admins'] as $table) {
            $owner = DB::connection('tenant')->table($table)->where('email', $a->owner_email)->first();
            $this->assertTrue(Hash::check('replacement-owner-password', $owner->password));
            $this->assertNotSame('old-token', $owner->remember_token);
            $this->assertSame('employee-unchanged', DB::connection('tenant')->table($table)->where('email', 'employee@example.test')->value('password'));
        }
        app(TenantContext::class)->end();
        app(TenantContext::class)->activate($b);
        $this->assertTrue(Hash::check('original-owner-password', DB::connection('tenant')->table('users')->where('email', $b->owner_email)->value('password')));
        app(TenantContext::class)->end();
        $this->assertTrue(Hash::check('platform-password', SuperAdmin::find($admin->id)->password));
        $this->assertNull(DB::connection('saas_central')->table('saas_operations')->first()->password_hash);
        $this->assertNull(app(TenantContext::class)->tenant);
    }

    public function test_password_actions_require_auth_confirmation_and_no_duplicate_pending_action()
    {
        $tenant = $this->tenant('customer-a');
        $url = 'http://devsaas.cgbod.test/tenants/' . $tenant->id . '/operations';
        $this->post($url, ['type' => 'reset_password', 'password' => 'replacement-password'])->assertRedirect('http://devsaas.cgbod.test/login');
        $this->platformLogin();
        $this->post($url, ['type' => 'reset_password', 'password' => 'replacement-password'])->assertSessionHasErrors('password');
        $this->assertFalse(session()->hasOldInput('password'));
        $data = ['type' => 'reset_password', 'password' => 'replacement-password', 'password_confirmation' => 'replacement-password'];
        $this->post($url, $data)->assertRedirect();
        $this->post($url, $data)->assertSessionHasErrors('operation');
        $this->assertSame(1, DB::connection('saas_central')->table('saas_operations')->count());
    }

    public function test_failed_owner_reset_rolls_back_and_never_retries_automatically()
    {
        $tenant = $this->tenant('customer-a'); $this->ownerTables($tenant);
        app(TenantContext::class)->activate($tenant);
        DB::connection('tenant')->table('admins')->where('email', $tenant->owner_email)->delete();
        app(TenantContext::class)->end();
        app(\App\Tenancy\TenantOperations::class)->enqueue($tenant, 'reset_password', 'replacement-password');
        $this->assertSame(1, app(\App\Tenancy\TenantOperations::class)->process());
        $operation = DB::connection('saas_central')->table('saas_operations')->first();
        $this->assertSame('failed', $operation->status);
        $this->assertNull($operation->password_hash);
        $this->assertSame(0, app(\App\Tenancy\TenantOperations::class)->process());
        app(TenantContext::class)->activate($tenant);
        $this->assertTrue(Hash::check('original-owner-password', DB::connection('tenant')->table('users')->where('email', $tenant->owner_email)->value('password')));
        app(TenantContext::class)->end();
    }

    public function test_reassigned_tenant_operation_cannot_touch_the_wrong_server()
    {
        $tenant = $this->tenant('reassigned'); $this->ownerTables($tenant);
        app(\App\Tenancy\TenantOperations::class)->enqueue($tenant, 'reset_password', 'replacement-password');
        $tenant->update(['hosting_node' => 'europe']);
        $this->assertSame(1, app(\App\Tenancy\TenantOperations::class)->process());
        $operation = DB::connection('saas_central')->table('saas_operations')->first();
        $this->assertSame('failed', $operation->status);
        $this->assertNull($operation->password_hash);
        $this->assertNull(app(TenantContext::class)->tenant);
        $tenant->update(['hosting_node' => 'local']);
        app(TenantContext::class)->activate($tenant);
        $this->assertTrue(Hash::check('original-owner-password', DB::connection('tenant')->table('users')->where('email', $tenant->owner_email)->value('password')));
        app(TenantContext::class)->end();
    }

}
