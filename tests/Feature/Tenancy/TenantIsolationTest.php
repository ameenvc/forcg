<?php

namespace Tests\Feature\Tenancy;

use App\SuperAdmin;
use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use App\Tenancy\TenantManager;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    private $temporary;
    private $alpha;
    private $beta;

    public function createApplication()
    {
        foreach (['TENANCY_ENABLED' => 'true', 'TENANCY_SOURCE_CONNECTION' => 'sqlite',
            'TENANCY_BASE_DOMAIN' => 'cgbod.test', 'TENANCY_HOST_PREFIX' => 'tenant.', 'TENANCY_SCHEME' => 'http',
            'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='] as $key => $value) {
            $_ENV[$key] = $_SERVER[$key] = $value;
        }
        return parent::createApplication();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->temporary = sys_get_temp_dir() . '/cgbod-test-' . bin2hex(random_bytes(8));
        mkdir($this->temporary, 0750, true);
        config(['tenancy.storage_root' => $this->temporary . '/storage']);
        require_once database_path('migrations/central/2026_11_07_000000_create_saas_tenants_table.php');
        (new \CreateSaasTenantsTable)->up();
        require_once database_path('migrations/central/2026_11_19_000000_add_hosting_node_to_saas_tenants.php');
        (new \AddHostingNodeToSaasTenants)->up();
        require_once database_path('migrations/central/2026_11_19_010000_create_saas_operations_table.php');
        (new \CreateSaasOperationsTable)->up();
        Schema::connection('saas_central')->create('probe_results', function (Blueprint $t) {
            $t->increments('id'); $t->string('slug'); $t->string('secret'); $t->string('email'); $t->string('url');
        });
        $this->alpha = $this->fixture('alpha', 'Alpha private record');
        $this->beta = $this->fixture('beta', 'Beta private record');
        Route::middleware(['web', 'tenant.application'])->get('/__tenant/probe', function () {
            return response()->json(['record' => DB::table('records')->where('id', 1)->value('secret'),
                'cookie' => config('session.cookie')]);
        });
    }

    protected function tearDown(): void
    {
        app(TenantContext::class)->end();
        DB::purge('fixture');
        File::deleteDirectory($this->temporary);
        parent::tearDown();
        foreach (['TENANCY_ENABLED', 'TENANCY_SOURCE_CONNECTION', 'TENANCY_BASE_DOMAIN', 'TENANCY_HOST_PREFIX', 'TENANCY_SCHEME', 'APP_KEY'] as $key) {
            unset($_ENV[$key], $_SERVER[$key]);
        }
    }

    private function fixture($slug, $secret)
    {
        $path = $this->temporary . '/' . $slug . '.sqlite';
        touch($path);
        config(['database.connections.fixture' => array_merge(config('database.connections.sqlite'), ['database' => $path])]);
        DB::purge('fixture');
        $schema = Schema::connection('fixture');
        $schema->create('records', function (Blueprint $t) { $t->increments('id'); $t->string('secret'); });
        $schema->create('users', function (Blueprint $t) {
            $t->increments('id'); $t->string('name'); $t->string('email'); $t->string('password');
            $t->rememberToken(); $t->softDeletes();
        });
        $schema->create('admins', function (Blueprint $t) {
            $t->increments('id'); $t->string('email'); $t->string('password'); $t->rememberToken();
        });
        DB::connection('fixture')->table('records')->insert(['id' => 1, 'secret' => $secret]);
        DB::connection('fixture')->table('users')->insert(['id' => 1, 'name' => $slug, 'email' => $slug . '@example.test', 'password' => Hash::make('tenant-password')]);
        return Tenant::create(['name' => ucfirst($slug), 'slug' => $slug, 'database_name' => $path,
            'owner_email' => $slug . '@example.test', 'plan' => 'starter', 'status' => 'active', 'provisioned_at' => now()]);
    }

    public function test_subdomains_use_separate_databases_even_when_record_ids_match()
    {
        $this->get('http://tenant.alpha.cgbod.test/__tenant/probe')->assertOk()->assertJson(['record' => 'Alpha private record', 'cookie' => 'cgbod_alpha_session']);
        $this->get('http://tenant.beta.cgbod.test/__tenant/probe')->assertOk()->assertJson(['record' => 'Beta private record', 'cookie' => 'cgbod_beta_session']);
        $this->assertNull(app(TenantContext::class)->tenant);
        $this->assertSame(':memory:', DB::connection('saas_central')->getDatabaseName());
    }

    public function test_unknown_suspended_and_spoofed_hosts_are_rejected()
    {
        $this->get('http://tenant.unknown.cgbod.test/__tenant/probe')->assertNotFound();
        $this->get('http://tenant.alpha.cgbod.test.evil.test/__tenant/probe')->assertNotFound();
        $this->alpha->update(['status' => 'suspended']);
        $this->get('http://tenant.alpha.cgbod.test/__tenant/probe')->assertNotFound();
    }

    public function test_tenant_cannot_authenticate_using_another_tenants_user()
    {
        $context = app(TenantContext::class);
        $context->activate($this->alpha);
        $this->assertTrue(Auth::guard('web')->attempt(['email' => 'alpha@example.test', 'password' => 'tenant-password']));
        $context->end();
        $context->activate($this->beta);
        $this->assertFalse(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('web')->attempt(['email' => 'alpha@example.test', 'password' => 'tenant-password']));
        $this->assertTrue(Auth::guard('web')->attempt(['email' => 'beta@example.test', 'password' => 'tenant-password']));
    }

    public function test_cache_uploads_and_legacy_paths_are_isolated_and_reset()
    {
        $context = app(TenantContext::class);
        $original = public_path();
        $context->activate($this->alpha);
        Cache::put('shared-key', 'Alpha', 60);
        Storage::disk('public')->put('same.txt', 'Alpha');
        File::put(public_path('storage/direct.txt'), 'Alpha direct');
        $alphaPath = public_path('storage/same.txt');
        $context->end();
        $this->assertSame($original, public_path());
        $context->activate($this->beta);
        $this->assertNull(Cache::get('shared-key'));
        $this->assertFalse(Storage::disk('public')->exists('same.txt'));
        $this->assertFalse(file_exists(public_path('storage/direct.txt')));
        Storage::disk('public')->put('same.txt', 'Beta');
        $this->assertNotSame($alphaPath, public_path('storage/same.txt'));
        $context->end();
        $context->activate($this->alpha);
        $this->assertSame('Alpha', Cache::get('shared-key'));
        $this->assertSame('Alpha', Storage::disk('public')->get('same.txt'));
        $this->assertSame('Alpha direct', File::get(public_path('storage/direct.txt')));
    }

    public function test_central_and_tenant_routes_are_separated()
    {
        $this->get('http://admin.cgbod.test/login')->assertOk()->assertSee('Sign in to manage');
        $this->get('http://admin.cgbod.test/tenants')->assertRedirect('http://admin.cgbod.test/login');
        $this->get('http://admin.cgbod.test/__tenant/probe')->assertNotFound();
        $this->get('http://tenant.alpha.cgbod.test/superadmin/home')->assertNotFound();
        $this->get('http://tenant.alpha.cgbod.test/tenants')->assertNotFound();
    }

    public function test_file_serving_cannot_escape_the_current_tenant_root()
    {
        $context = app(TenantContext::class);
        $context->activate($this->alpha);
        File::put(public_path('storage/private.txt'), 'Private alpha');
        File::put(public_path('storage/clientlogos/brand.png'), 'Brand alpha');
        symlink($this->temporary . '/alpha.sqlite', public_path('storage/clientlogos/escape.png'));
        $context->end();
        $this->get('http://tenant.alpha.cgbod.test/storage/private.txt')->assertForbidden();
        $this->get('http://tenant.alpha.cgbod.test/storage/clientlogos/brand.png')->assertOk();
        $this->get('http://tenant.beta.cgbod.test/storage/clientlogos/brand.png')->assertNotFound();
        $this->get('http://tenant.alpha.cgbod.test/storage/clientlogos/escape.png')->assertNotFound();
    }

    public function test_tenant_registration_is_persisted_audited_and_cannot_be_activated_early()
    {
        $manager = app(TenantManager::class);
        $tenant = $manager->register(['name' => 'Leanston', 'slug' => 'leanston', 'owner_email' => 'owner@example.test', 'plan' => 'starter']);
        $this->assertSame('pending', $tenant->status);
        $this->assertSame('cgbod_leanston', $tenant->database_name);
        $this->assertSame(1, DB::connection('saas_central')->table('saas_events')->where('tenant_id', $tenant->id)->count());
        $this->get('http://tenant.leanston.cgbod.test/__tenant/probe')->assertNotFound();
    }

    public function test_central_admin_can_register_suspend_and_reactivate_tenants()
    {
        SuperAdmin::create(['name' => 'Platform', 'email' => 'platform@example.test', 'password' => Hash::make('platform-password')]);
        $this->post('http://admin.cgbod.test/login', ['email' => 'platform@example.test', 'password' => 'platform-password'])
            ->assertRedirect('http://admin.cgbod.test/tenants');
        $this->get('http://admin.cgbod.test/tenants')->assertOk()->assertSee('Tenant directory');
        $this->post('http://admin.cgbod.test/tenants', ['name' => 'Leanston', 'slug' => 'leanston', 'owner_email' => 'owner@example.test', 'plan' => 'professional'])
            ->assertRedirect('http://admin.cgbod.test/tenants');
        $pending = Tenant::where('slug', 'leanston')->firstOrFail();
        $this->patch('http://admin.cgbod.test/tenants/' . $pending->id, ['name' => 'Leanston', 'plan' => 'professional', 'status' => 'active'])
            ->assertStatus(422);
        $this->patch('http://admin.cgbod.test/tenants/' . $this->alpha->id, ['name' => 'Alpha', 'plan' => 'starter', 'status' => 'suspended'])->assertRedirect();
        $this->assertSame('suspended', $this->alpha->fresh()->status);
        $this->get('http://tenant.alpha.cgbod.test/__tenant/probe')->assertNotFound();
    }

    public function test_queued_models_are_restored_under_the_correct_tenant_and_context_is_cleaned_up()
    {
        $context = app(TenantContext::class);
        foreach ([$this->alpha, $this->beta] as $tenant) {
            $context->activate($tenant);
            Queue::connection('database')->push(new \Tests\Fixtures\TenantProbeJob(\App\User::findOrFail(1)));
            $context->end();
        }
        for ($i = 0; $i < 2; $i++) {
            $this->assertSame(0, \Illuminate\Support\Facades\Artisan::call('queue:work', ['connection' => 'database', '--once' => true, '--tries' => 1, '--sleep' => 0]));
            $this->assertNull($context->tenant, substr((string) DB::connection('saas_central')->table('failed_jobs')->value('exception'), 0, 1000));
        }
        $results = DB::connection('saas_central')->table('probe_results')->orderBy('id')->get();
        $this->assertCount(2, $results);
        $this->assertSame('Alpha private record', $results[0]->secret);
        $this->assertSame('alpha@example.test', $results[0]->email);
        $this->assertSame('http://tenant.alpha.cgbod.test/user/home', $results[0]->url);
        $this->assertSame('Beta private record', $results[1]->secret);
        $this->assertSame('beta@example.test', $results[1]->email);
    }

    public function test_worker_cleans_context_after_a_failed_job()
    {
        $context = app(TenantContext::class);
        $context->activate($this->alpha);
        Queue::connection('database')->push(new \Tests\Fixtures\TenantProbeJob(\App\User::findOrFail(1), true));
        $context->end();
        $this->assertSame(0, \Illuminate\Support\Facades\Artisan::call('queue:work', ['connection' => 'database', '--once' => true, '--tries' => 1, '--sleep' => 0]));
        $this->assertNull($context->tenant, substr((string) DB::connection('saas_central')->table('failed_jobs')->value('exception'), 0, 1000));
        $this->assertSame(1, DB::connection('saas_central')->table('failed_jobs')->count());
    }

    public function test_password_reset_requires_a_verified_expiring_single_use_code()
    {
        $context = app(TenantContext::class);
        $context->activate($this->alpha);
        \Illuminate\Support\Facades\Mail::fake();
        $session = app('session.store');
        $controller = new \App\Http\Controllers\Saas\TenantPasswordController;
        $request = \Illuminate\Http\Request::create('/verify/email', 'POST', ['email' => 'alpha@example.test']);
        $request->setLaravelSession($session);
        $controller->requestCode($request);
        $message = \Illuminate\Support\Facades\Mail::sent(\App\Mail\ForgotPassword::class)->first();
        preg_match('/code is ([0-9]{6})/', $message->data['line3'], $matches);
        $this->assertNotSame($matches[1], $session->get('saas_reset_hash'));
        $verify = \Illuminate\Http\Request::create('/reset/password', 'POST', ['vcode' => $matches[1]]);
        $verify->setLaravelSession($session);
        $controller->verifyCode($verify);
        $this->assertNull($session->get('saas_reset_hash'));
        $reset = \Illuminate\Http\Request::create('/reset/password/confirm', 'POST', ['email' => 'alpha@example.test',
            'password' => 'new-secure-password', 'password_confirmation' => 'new-secure-password']);
        $reset->setLaravelSession($session);
        $controller->resetPassword($reset);
        $this->assertTrue(Hash::check('new-secure-password', DB::table('users')->where('id', 1)->value('password')));
        $this->assertNull($session->get('saas_reset_email'));
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $controller->resetPassword($reset);
    }

    public function test_password_reset_cannot_be_called_without_verification()
    {
        $this->post('http://tenant.beta.cgbod.test/reset/password/confirm', ['email' => 'beta@example.test',
            'password' => 'attacker-password', 'password_confirmation' => 'attacker-password'])->assertForbidden();
        app(TenantContext::class)->activate($this->beta);
        $this->assertTrue(Hash::check('tenant-password', DB::table('users')->where('id', 1)->value('password')));
    }

    public function test_tenant_resolution_works_in_a_fresh_http_process_before_eloquent_provider_boot()
    {
        $central = $this->temporary . '/central.sqlite';
        DB::connection('saas_central')->statement('VACUUM INTO ?', [$central]);
        $process = new \Symfony\Component\Process\Process([PHP_BINARY,
            base_path('tests/Fixtures/tenant-http-bootstrap.php'), $central, $this->temporary . '/fresh-storage']);
        $process->mustRun();
        $this->assertSame(['status' => 404, 'tenant' => 'alpha'], json_decode($process->getOutput(), true), $process->getErrorOutput());
    }

    public function test_suspended_tenant_jobs_are_paused_without_running()
    {
        $context = app(TenantContext::class);
        $context->activate($this->alpha);
        Queue::connection('database')->push(new \Tests\Fixtures\TenantProbeJob(\App\User::findOrFail(1)));
        $context->end();
        $this->alpha->update(['status' => 'suspended']);
        $this->assertSame(0, \Illuminate\Support\Facades\Artisan::call('queue:work', ['connection' => 'database', '--once' => true, '--tries' => 1, '--sleep' => 0]));
        $this->assertNull($context->tenant);
        $this->assertSame(0, DB::connection('saas_central')->table('probe_results')->count());
        $this->assertSame(1, DB::connection('saas_central')->table('jobs')->count());
    }

    public function test_sync_jobs_preserve_the_outer_tenant_context()
    {
        $context = app(TenantContext::class);
        $context->activate($this->alpha);
        Queue::connection('sync')->push(new \Tests\Fixtures\TenantProbeJob(\App\User::findOrFail(1)));
        $this->assertSame('alpha', $context->tenant->slug);
        $this->assertSame('Alpha private record', DB::connection('saas_central')->table('probe_results')->value('secret'));
    }

    public function test_company_tasks_run_for_each_tenant_and_require_explicit_selection()
    {
        config(['tenancy.tenant_commands' => ['test:tenant-task']]);
        \Illuminate\Support\Facades\Artisan::command('test:tenant-task', function () {
            $tenant = app(TenantContext::class)->tenant;
            DB::connection('saas_central')->table('probe_results')->insert(['slug' => $tenant->slug,
                'secret' => DB::table('records')->value('secret'), 'email' => 'task@example.test', 'url' => url('/')]);
        });
        $this->assertSame(1, \Illuminate\Support\Facades\Artisan::call('tenants:run', ['task' => 'test:tenant-task']));
        $this->assertSame(0, \Illuminate\Support\Facades\Artisan::call('tenants:run', ['task' => 'test:tenant-task', '--all' => true]));
        $this->assertSame(['alpha', 'beta'], DB::connection('saas_central')->table('probe_results')->orderBy('id')->pluck('slug')->all());
        $this->assertNull(app(TenantContext::class)->tenant);
        $this->expectException(\RuntimeException::class);
        \Illuminate\Support\Facades\Artisan::call('test:tenant-task');
    }

    public function test_platform_installer_refuses_a_company_database()
    {
        Schema::connection('saas_central')->create('users', function (Blueprint $t) { $t->increments('id'); });
        $this->assertSame(1, \Illuminate\Support\Facades\Artisan::call('saas:install', ['email' => 'platform@example.test']));
        $this->assertSame(2, Tenant::count());
    }

    public function test_tenant_urls_retain_the_configured_development_port()
    {
        config(['app.url' => 'http://admin.cgbod.test:8000']);
        $this->assertSame('http://tenant.alpha.cgbod.test:8000', $this->alpha->url());
        app(TenantContext::class)->activate($this->alpha);
        $this->assertSame('http://tenant.alpha.cgbod.test:8000/user/home', url('/user/home'));
    }

    public function test_a_tenant_cannot_point_to_the_central_database()
    {
        $this->alpha->database_name = ':memory:';
        $this->expectException(\RuntimeException::class);
        app(TenantContext::class)->activate($this->alpha);
    }
}
