<?php

namespace Tests\Feature\Tenancy;

use App\SuperAdmin;
use App\Tenancy\CloudflareDns;
use App\Tenancy\Tenant;
use App\Tenancy\TenantContext;
use App\Tenancy\TenantManager;
use App\Tenancy\TenantOperations;
use App\Tenancy\TenantProvisioner;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CloudflareDnsTest extends TestCase
{
    private $zone = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    private $record = 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb';
    private $token = 'cloudflare-test-only-token-never-live';

    public function createApplication()
    {
        foreach ($this->environment() as $key => $value) { $_ENV[$key] = $_SERVER[$key] = $value; }
        return parent::createApplication();
    }

    private function environment()
    {
        return ['TENANCY_ENABLED' => 'true', 'TENANCY_SOURCE_CONNECTION' => 'sqlite',
            'TENANCY_BASE_DOMAIN' => 'cgbod.test', 'TENANCY_TENANT_BASE_DOMAIN' => 'cgbod.test',
            'TENANCY_ADMIN_DOMAIN' => 'devsaas.cgbod.test', 'TENANCY_HOST_PREFIX' => '',
            'TENANCY_NODE_ID' => 'local', 'APP_URL' => 'https://devsaas.cgbod.test',
            'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='];
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['tenancy.hosting_nodes' => ['local' => ['name' => 'Primary', 'region' => 'Middle East'], 'europe' => ['name' => 'EU server', 'region' => 'Europe']]]);
        foreach ([
            '2026_11_07_000000_create_saas_tenants_table.php' => 'CreateSaasTenantsTable',
            '2026_11_19_000000_add_hosting_node_to_saas_tenants.php' => 'AddHostingNodeToSaasTenants',
            '2026_11_19_010000_create_saas_operations_table.php' => 'CreateSaasOperationsTable',
            '2026_11_19_020000_create_saas_cloudflare_tables.php' => 'CreateSaasCloudflareTables',
        ] as $file => $class) { require_once database_path('migrations/central/' . $file); (new $class)->up(); }
        $this->fakeHttp(['*' => $this->response(['success' => false], 500)]);
    }

    protected function tearDown(): void
    {
        app(TenantContext::class)->end();
        parent::tearDown();
        foreach (array_keys($this->environment()) as $key) { unset($_ENV[$key], $_SERVER[$key]); }
    }

    private function response(array $body, $status = 200)
    {
        // Laravel 7's response helper uses promise_for(), removed in this app's Guzzle Promises 2.
        return new \GuzzleHttp\Promise\FulfilledPromise(new \GuzzleHttp\Psr7\Response(
            $status, ['Content-Type' => 'application/json'], json_encode($body)
        ));
    }

    private function fakeHttp($callback)
    {
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::fake($callback);
    }

    private function login()
    {
        $admin = SuperAdmin::create(['name' => 'Platform', 'email' => 'admin@example.test', 'password' => Hash::make('platform-password')]);
        $this->actingAs($admin, 'superadmin');
    }

    private function data()
    {
        return ['zone_id' => $this->zone, 'api_token' => $this->token, 'automatic' => 1, 'targets' => [
            'local' => ['type' => 'A', 'content' => '203.0.113.25', 'proxied' => 1],
            'europe' => ['type' => 'CNAME', 'content' => 'eu-ingress.example.test', 'proxied' => 0],
        ]];
    }

    private function settings($automatic = true)
    {
        DB::connection('saas_central')->table('saas_cloudflare')->insert([
            'id' => 1, 'zone_id' => $this->zone, 'encrypted_token' => Crypt::encryptString($this->token),
            'automatic' => $automatic, 'targets' => json_encode($this->data()['targets']), 'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function tenant($active = true)
    {
        $tenant = app(TenantManager::class)->register(['name' => 'Waraq', 'slug' => 'waraq', 'owner_email' => 'owner@example.test', 'plan' => 'starter']);
        if ($active) { $tenant->update(['status' => 'active', 'provisioned_at' => now()]); }
        return $tenant;
    }

    private function recordData(array $overrides = [])
    {
        return array_merge(['id' => $this->record, 'name' => 'waraq.cgbod.test', 'type' => 'A', 'content' => '203.0.113.25', 'proxied' => true], $overrides);
    }

    private function provider(array $records = [])
    {
        // Fakes match the fixed Cloudflare origin; no test ever uses a real token or DNS zone.
        $this->fakeHttp(function ($request) use ($records) {
            if ($request->url() === 'https://api.cloudflare.com/client/v4/zones/' . $this->zone) {
                return $this->response(['success' => true, 'result' => ['name' => 'cgbod.test', 'status' => 'active']]);
            }
            if (strpos($request->url(), '/dns_records') !== false) {
                if ($request->method() === 'GET') { return $this->response(['success' => true, 'result' => $records, 'result_info' => ['total_pages' => 1]]); }
                return $this->response(['success' => true, 'result' => $this->recordData($request->data())]);
            }
            return $this->response(['success' => false], 500);
        });
    }

    public function test_settings_require_platform_auth_and_keep_tokens_encrypted_and_out_of_forms()
    {
        $url = 'https://devsaas.cgbod.test/settings/cloudflare';
        $this->get($url)->assertRedirect('https://devsaas.cgbod.test/login');
        $this->post($url, $this->data())->assertRedirect('https://devsaas.cgbod.test/login');
        $this->login();
        $this->post($url, $this->data())->assertRedirect($url);
        $saved = app(CloudflareDns::class)->settings();
        $this->assertNotSame($this->token, $saved->encrypted_token);
        $this->assertSame($this->token, Crypt::decryptString($saved->encrypted_token));
        $this->get($url)->assertOk()->assertDontSee($this->token)->assertDontSee($saved->encrypted_token)->assertSee('Hosting server targets');
        $changed = $this->data(); $changed['api_token'] = ''; $changed['automatic'] = 0;
        $this->post($url, $changed)->assertRedirect($url);
        $this->assertSame($saved->encrypted_token, app(CloudflareDns::class)->settings()->encrypted_token);
        $invalid = $this->data(); $invalid['zone_id'] = 'invalid';
        $this->post($url, $invalid)->assertSessionHasErrors('zone_id');
        $this->assertFalse(session()->hasOldInput('api_token'));
        Http::assertNothingSent();
    }

    public function test_invalid_server_target_cannot_be_saved()
    {
        $this->login(); $data = $this->data(); $data['targets']['local']['content'] = 'https://127.0.0.1/path';
        $this->post('https://devsaas.cgbod.test/settings/cloudflare', $data)->assertSessionHasErrors('targets.local.content');
        $this->assertNull(app(CloudflareDns::class)->settings());
        $this->assertFalse(session()->hasOldInput('api_token'));
    }

    public function test_connection_test_displays_sanitized_result_and_zone_must_match_tenant_domain()
    {
        $this->settings(); $this->login(); $this->provider();
        $this->post('https://devsaas.cgbod.test/settings/cloudflare/test')->assertRedirect();
        $this->assertSame('succeeded', app(CloudflareDns::class)->settings()->test_status);
        $this->get('https://devsaas.cgbod.test/settings/cloudflare')->assertSee('Zone and DNS read access verified');
        $this->fakeHttp(['*' => $this->response(['success' => true, 'result' => ['name' => 'another.test', 'status' => 'active']])]);
        $result = app(CloudflareDns::class)->testConnection();
        $this->assertSame('failed', $result['status']);
        $this->assertStringContainsString('does not contain', $result['message']);
    }

    public function test_creates_record_on_fixed_cloudflare_origin_and_records_result()
    {
        $this->settings(); $tenant = $this->tenant(); $this->provider();
        $this->assertTrue(app(CloudflareDns::class)->sync($tenant));
        Http::assertSent(function ($request) {
            return $request->method() === 'POST' && $request->url() === 'https://api.cloudflare.com/client/v4/zones/' . $this->zone . '/dns_records'
                && $request['name'] === 'waraq.cgbod.test' && $request['content'] === '203.0.113.25' && $request['proxied'] === true
                && $request->hasHeader('Authorization', 'Bearer ' . $this->token);
        });
        $record = DB::connection('saas_central')->table('saas_dns_records')->first();
        $this->assertSame('succeeded', $record->status);
        $this->assertSame($this->record, $record->record_id);
        $this->assertStringContainsString('Created', $record->message);
    }

    public function test_matching_record_is_idempotent_and_unrelated_conflicts_are_not_overwritten()
    {
        $this->settings(); $tenant = $this->tenant(); $this->provider([$this->recordData()]);
        $this->assertTrue(app(CloudflareDns::class)->sync($tenant));
        Http::assertNotSent(function ($request) { return $request->method() !== 'GET'; });
        $this->provider([$this->recordData(['id' => 'cccccccccccccccccccccccccccccccc', 'content' => '198.51.100.22'])]);
        $this->assertFalse(app(CloudflareDns::class)->sync($tenant));
        Http::assertNotSent(function ($request) { return $request->method() !== 'GET'; });
        $record = DB::connection('saas_central')->table('saas_dns_records')->first();
        $this->assertSame('failed', $record->status);
        $this->assertStringContainsString('not overwritten', $record->message);
    }

    public function test_previously_managed_record_can_be_updated_to_new_server_target()
    {
        $this->settings(); $tenant = $this->tenant(); $this->provider([$this->recordData()]);
        $this->assertTrue(app(CloudflareDns::class)->sync($tenant));
        $targets = $this->data()['targets']; $targets['local']['content'] = '203.0.113.50';
        DB::connection('saas_central')->table('saas_cloudflare')->update(['targets' => json_encode($targets)]);
        $this->provider([$this->recordData()]);
        $this->assertTrue(app(CloudflareDns::class)->sync($tenant));
        Http::assertSent(function ($r) { return $r->method() === 'PUT' && $r['content'] === '203.0.113.50'; });
        $this->assertStringContainsString('Updated', DB::connection('saas_central')->table('saas_dns_records')->first()->message);
    }

    public function test_api_errors_do_not_expose_response_bodies_or_tokens()
    {
        $this->settings(); $tenant = $this->tenant();
        $this->fakeHttp(['*' => $this->response(['success' => false, 'errors' => [['code' => 10000, 'message' => 'secret=' . $this->token]]], 403)]);
        $this->assertFalse(app(CloudflareDns::class)->sync($tenant));
        $record = DB::connection('saas_central')->table('saas_dns_records')->first();
        $this->assertStringContainsString('HTTP 403; code 10000', $record->message);
        $this->assertStringNotContainsString($this->token, $record->message);
        $this->login();
        $this->get('https://devsaas.cgbod.test/tenants')->assertSee('DNS result')->assertSee('HTTP 403; code 10000')->assertDontSee($this->token);
    }

    public function test_dns_action_queues_without_password_and_runs_only_on_assigned_node()
    {
        $this->settings(); $tenant = $this->tenant(); $this->login(); $this->provider();
        $this->post('https://devsaas.cgbod.test/tenants/' . $tenant->id . '/operations', ['type' => 'sync_dns'])->assertRedirect();
        $this->assertSame('queued', DB::connection('saas_central')->table('saas_dns_records')->first()->status);
        $this->assertNull(DB::connection('saas_central')->table('saas_operations')->first()->password_hash);
        config(['tenancy.node_id' => 'europe']);
        $this->assertSame(0, app(TenantOperations::class)->process());
        Http::assertNothingSent();
        config(['tenancy.node_id' => 'local']);
        $this->assertSame(1, app(TenantOperations::class)->process());
        $this->assertSame('succeeded', DB::connection('saas_central')->table('saas_operations')->first()->status);
        $this->assertSame('succeeded', DB::connection('saas_central')->table('saas_dns_records')->first()->status);
    }

    public function test_automatic_dns_failure_keeps_provisioned_tenant_active()
    {
        $this->settings(); $tenant = $this->tenant(false);
        $mock = \Mockery::mock(TenantProvisioner::class);
        $mock->shouldReceive('provision')->once()->andReturnUsing(function ($t) { $t->update(['status' => 'active', 'provisioned_at' => now()]); });
        $this->app->instance(TenantProvisioner::class, $mock);
        app(TenantOperations::class)->enqueue($tenant, 'provision', 'owner-password-2026');
        $this->assertSame(1, app(TenantOperations::class)->process());
        $this->assertSame('active', $tenant->fresh()->status);
        $this->assertSame('succeeded', DB::connection('saas_central')->table('saas_operations')->first()->status);
        $this->assertSame('failed', DB::connection('saas_central')->table('saas_dns_records')->first()->status);
    }
    public function test_regional_cname_target_and_automatic_success_are_applied()
    {
        $this->settings(); $tenant = $this->tenant(false); $tenant->update(['hosting_node' => 'europe']);
        config(['tenancy.node_id' => 'europe']);
        $mock = \Mockery::mock(TenantProvisioner::class);
        $mock->shouldReceive('provision')->once()->andReturnUsing(function ($t) { $t->update(['status' => 'active', 'provisioned_at' => now()]); });
        $this->app->instance(TenantProvisioner::class, $mock); $this->provider();
        app(TenantOperations::class)->enqueue($tenant, 'provision', 'owner-password-2026');
        app(TenantOperations::class)->process();
        Http::assertSent(function ($r) { return $r->method() === 'POST' && $r['type'] === 'CNAME' && $r['content'] === 'eu-ingress.example.test' && $r['proxied'] === false; });
        $record = DB::connection('saas_central')->table('saas_dns_records')->first();
        $this->assertSame('succeeded', $record->status);
        $this->assertSame('europe', $record->hosting_node);
    }

    public function test_disabled_automatic_mode_does_not_make_dns_requests()
    {
        $this->settings(false); $tenant = $this->tenant(false);
        $mock = \Mockery::mock(TenantProvisioner::class);
        $mock->shouldReceive('provision')->once()->andReturnUsing(function ($t) { $t->update(['status' => 'active', 'provisioned_at' => now()]); });
        $this->app->instance(TenantProvisioner::class, $mock);
        app(TenantOperations::class)->enqueue($tenant, 'provision', 'owner-password-2026');
        app(TenantOperations::class)->process();
        Http::assertNothingSent();
        $this->assertSame(0, DB::connection('saas_central')->table('saas_dns_records')->count());
    }

    public function test_network_timeout_and_multiple_address_records_have_visible_failures()
    {
        $this->settings(); $tenant = $this->tenant();
        $this->fakeHttp(function () { throw new \Illuminate\Http\Client\ConnectionException('secret-provider-diagnostic'); });
        $this->assertFalse(app(CloudflareDns::class)->sync($tenant));
        $record = DB::connection('saas_central')->table('saas_dns_records')->first();
        $this->assertStringContainsString('could not be reached', $record->message);
        $this->assertStringNotContainsString('secret-provider-diagnostic', $record->message);
        $this->provider([$this->recordData(), $this->recordData(['id' => 'cccccccccccccccccccccccccccccccc', 'type' => 'AAAA', 'content' => '2001:db8::1'])]);
        $this->assertFalse(app(CloudflareDns::class)->sync($tenant));
        $this->assertStringContainsString('Multiple address records', DB::connection('saas_central')->table('saas_dns_records')->first()->message);
        Http::assertNotSent(function ($r) { return $r->method() !== 'GET'; });
    }

}
