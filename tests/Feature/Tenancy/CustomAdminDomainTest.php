<?php

namespace Tests\Feature\Tenancy;

use App\SuperAdmin;
use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomAdminDomainTest extends TestCase
{
    private $temporary;

    public function createApplication()
    {
        foreach (['TENANCY_ENABLED' => 'true', 'TENANCY_SOURCE_CONNECTION' => 'sqlite',
            'TENANCY_BASE_DOMAIN' => 'cgbod.test', 'TENANCY_HOST_PREFIX' => 'tenant.', 'TENANCY_ADMIN_DOMAIN' => 'devsaas.cgbod.test',
            'TENANCY_SCHEME' => 'http', 'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='] as $key => $value) {
            $_ENV[$key] = $_SERVER[$key] = $value;
        }
        return parent::createApplication();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->temporary = sys_get_temp_dir() . '/cgbod-domain-' . bin2hex(random_bytes(8));
        mkdir($this->temporary, 0750, true);
        config(['tenancy.storage_root' => $this->temporary . '/storage']);
        require_once database_path('migrations/central/2026_11_07_000000_create_saas_tenants_table.php');
        (new \CreateSaasTenantsTable)->up();
        require_once database_path('migrations/central/2026_11_19_000000_add_hosting_node_to_saas_tenants.php');
        (new \AddHostingNodeToSaasTenants)->up();
        touch($this->temporary . '/alpha.sqlite');
        Tenant::create(['name' => 'Alpha', 'slug' => 'alpha', 'database_name' => $this->temporary . '/alpha.sqlite',
            'owner_email' => 'alpha@example.test', 'plan' => 'starter', 'status' => 'active', 'provisioned_at' => now()]);
    }

    protected function tearDown(): void
    {
        app(TenantContext::class)->end();
        File::deleteDirectory($this->temporary);
        parent::tearDown();
        foreach (['TENANCY_ENABLED', 'TENANCY_SOURCE_CONNECTION', 'TENANCY_BASE_DOMAIN', 'TENANCY_HOST_PREFIX',
            'TENANCY_ADMIN_DOMAIN', 'TENANCY_SCHEME', 'APP_KEY'] as $key) {
            unset($_ENV[$key], $_SERVER[$key]);
        }
    }

    public function test_custom_platform_hostname_supports_login_and_management()
    {
        $this->get('http://devsaas.cgbod.test/login')->assertOk()->assertSee('Sign in to manage');
        $this->get('http://devsaas.cgbod.test/tenants')->assertRedirect('http://devsaas.cgbod.test/login');
        $this->get('http://devsaas.cgbod.test/api/user')->assertNotFound();
        $this->get('http://admin.cgbod.test/login')->assertNotFound();
        $this->get('http://devsaas.cgbod.test.evil.test/login')->assertNotFound();
        SuperAdmin::create(['name' => 'Platform', 'email' => 'platform@example.test', 'password' => Hash::make('platform-password')]);
        $this->post('http://devsaas.cgbod.test/login', ['email' => 'platform@example.test', 'password' => 'platform-password'])
            ->assertRedirect('http://devsaas.cgbod.test/tenants');
        $this->get('http://devsaas.cgbod.test/tenants')->assertOk()->assertSee('Tenant directory');
        $this->get('http://tenant.alpha.cgbod.test/superadmin/home')->assertNotFound();
        $this->assertSame('alpha', $this->app['request']->attributes->get('tenant')->slug);
        $this->assertNull(app(TenantContext::class)->tenant);
    }
}
