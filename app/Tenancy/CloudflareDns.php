<?php

namespace App\Tenancy;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CloudflareDns
{
    public function settings()
    {
        return DB::connection(config('tenancy.central_connection'))->table('saas_cloudflare')->where('id', 1)->first();
    }

    public function automatic()
    {
        $settings = $this->settings();
        return $settings && $settings->automatic;
    }

    private function request($method, $path, array $options = [])
    {
        $settings = $this->settings();
        if (!$settings) { throw new DnsException('Save the Cloudflare zone and API token first.'); }
        try {
            $token = Crypt::decryptString($settings->encrypted_token);
        } catch (\Throwable $e) {
            throw new DnsException('The Cloudflare token cannot be decrypted. Check application keys on this server or save a new token.');
        }
        try {
            // Fixed API origin, disabled redirects, and bounded waits keep the token on Cloudflare only.
            $response = Http::withToken($token)->acceptJson()->timeout(15)
                ->withOptions(['allow_redirects' => false, 'connect_timeout' => 5])
                ->send($method, 'https://api.cloudflare.com/client/v4/' . $path, $options);
        } catch (\Throwable $e) {
            throw new DnsException('Cloudflare could not be reached. Check network access and retry.');
        }
        $data = $response->json();
        if (!$response->successful() || !is_array($data) || ($data['success'] ?? false) !== true) {
            // Never expose provider response bodies: they may contain credentials or customer data.
            $code = $data['errors'][0]['code'] ?? null;
            $code = is_numeric($code) ? '; code ' . (int) $code : '';
            throw new DnsException('Cloudflare rejected the request (HTTP ' . $response->status() . $code . '). Check the zone, token permissions, and record settings.');
        }
        return $data;
    }

    private function zone()
    {
        $settings = $this->settings();
        if (!$settings || !preg_match('/^[a-f0-9]{32}$/D', $settings->zone_id)) {
            throw new DnsException('Save a valid Cloudflare zone ID first.');
        }
        $data = $this->request('GET', 'zones/' . $settings->zone_id);
        $zone = strtolower($data['result']['name'] ?? '');
        $base = strtolower(config('tenancy.base_domain'));
        if (!$zone || ($base !== $zone && substr($base, -strlen('.' . $zone)) !== '.' . $zone)) {
            throw new DnsException('This Cloudflare zone does not contain the configured tenant domain.');
        }
        if (($data['result']['status'] ?? '') !== 'active') {
            throw new DnsException('The Cloudflare zone is not active. Check its nameservers and activation.');
        }
        return $settings->zone_id;
    }

    public function testConnection()
    {
        $status = 'failed';
        try {
            $zone = $this->zone();
            $this->request('GET', 'zones/' . $zone . '/dns_records', ['query' => ['per_page' => 1]]);
            $status = 'succeeded';
            $message = 'Connected. Zone and DNS read access verified. DNS edit permission is checked when a record is applied.';
        } catch (DnsException $e) { $message = $e->getMessage(); }
        DB::connection(config('tenancy.central_connection'))->table('saas_cloudflare')->where('id', 1)->update([
            'test_status' => $status, 'test_message' => $message, 'tested_at' => now(), 'updated_at' => now(),
        ]);
        return ['status' => $status, 'message' => $message];
    }

    public function target(Tenant $tenant)
    {
        $settings = $this->settings();
        $targets = $settings ? json_decode($settings->targets, true) : [];
        $target = $targets[Hosting::nodeId($tenant)] ?? null;
        if (!$target || empty($target['content'])) { throw new DnsException('Configure a DNS target for this hosting location first.'); }
        return $target;
    }

    public function markQueued(Tenant $tenant)
    {
        $target = $this->target($tenant);
        $this->saveResult($tenant, 'queued', 'Waiting for the assigned hosting server.', $target);
    }

    public function failPending(Tenant $tenant)
    {
        $record = DB::connection(config('tenancy.central_connection'))->table('saas_dns_records')->where('tenant_id', $tenant->id)->first();
        if ($record && in_array($record->status, ['queued', 'running'], true)) {
            $this->saveResult($tenant, 'failed', 'The DNS action could not run. Check the assigned node and server logs.', [], ['checked_at' => now()]);
        }
    }

    private function saveResult(Tenant $tenant, $status, $message, array $target = [], array $extra = [])
    {
        $table = DB::connection(config('tenancy.central_connection'))->table('saas_dns_records');
        $values = array_merge([
            'hostname' => parse_url($tenant->url(), PHP_URL_HOST), 'hosting_node' => Hosting::nodeId($tenant),
            'status' => $status, 'message' => $message, 'updated_at' => now(),
        ], $target, $extra);
        if (!$table->where('tenant_id', $tenant->id)->exists()) { $values['created_at'] = now(); }
        $table->updateOrInsert(['tenant_id' => $tenant->id], $values);
    }

    public function sync(Tenant $tenant)
    {
        $target = [];
        try {
            if (!Hosting::isLocal($tenant)) { throw new DnsException('Run this DNS action on the tenant’s assigned hosting server.'); }
            if (!$tenant->provisioned_at || !in_array($tenant->status, ['active', 'suspended'], true)) { throw new DnsException('Provision the tenant before applying DNS.'); }
            $target = $this->target($tenant);
            $this->saveResult($tenant, 'running', 'Applying the Cloudflare DNS record.', $target);
            $zone = $this->zone();
            $hostname = strtolower(parse_url($tenant->url(), PHP_URL_HOST));
            if ($hostname === strtolower(config('tenancy.admin_domain')) || ($target['type'] === 'CNAME' && $target['content'] === $hostname)) {
                throw new DnsException('The tenant record would conflict with the platform hostname or point to itself.');
            }
            $data = $this->request('GET', 'zones/' . $zone . '/dns_records', ['query' => ['name' => $hostname, 'per_page' => 100]]);
            if (!isset($data['result']) || !is_array($data['result']) || ($data['result_info']['total_pages'] ?? 1) > 1) {
                throw new DnsException('Cloudflare returned an unexpected DNS listing. Review the tenant records.');
            }
            $records = array_values(array_filter($data['result'], function ($r) use ($hostname) {
                return strtolower($r['name'] ?? '') === $hostname && in_array($r['type'] ?? '', ['A', 'AAAA', 'CNAME'], true);
            }));
            if (count($records) > 1) { throw new DnsException('Multiple address records already exist for this tenant. Review them in Cloudflare before retrying.'); }
            $existing = $records[0] ?? null;
            $saved = DB::connection(config('tenancy.central_connection'))->table('saas_dns_records')->where('tenant_id', $tenant->id)->first();
            $payload = ['type' => $target['type'], 'name' => $hostname, 'content' => $target['content'], 'proxied' => (bool) $target['proxied'], 'ttl' => 1];
            $same = $existing && $existing['type'] === $payload['type']
                && strtolower(rtrim($existing['content'], '.')) === strtolower(rtrim($payload['content'], '.'))
                && (bool) $existing['proxied'] === $payload['proxied'];
            if ($existing && !$same && (!$saved || $saved->zone_id !== $zone || $saved->record_id !== $existing['id'])) {
                throw new DnsException('An existing record points elsewhere or has different settings. Review it in Cloudflare; unrelated records are not overwritten.');
            }
            if ($same) {
                $record = $existing; $action = 'Verified existing';
            } elseif ($existing) {
                $response = $this->request('PUT', 'zones/' . $zone . '/dns_records/' . $existing['id'], ['json' => $payload]);
                $record = $response['result'] ?? []; $action = 'Updated';
            } else {
                $response = $this->request('POST', 'zones/' . $zone . '/dns_records', ['json' => $payload]);
                $record = $response['result'] ?? []; $action = 'Created';
            }
            if (!preg_match('/^[a-f0-9]{32}$/D', $record['id'] ?? '') || strtolower($record['name'] ?? '') !== $hostname
                || ($record['type'] ?? '') !== $payload['type']
                || strtolower(rtrim($record['content'] ?? '', '.')) !== strtolower(rtrim($payload['content'], '.'))
                || (bool) ($record['proxied'] ?? false) !== $payload['proxied']) {
                throw new DnsException('Cloudflare returned an unexpected record result. Review the record before retrying.');
            }
            $this->saveResult($tenant, 'succeeded', $action . ' Cloudflare record. DNS propagation and origin HTTPS must still be checked.', $target,
                ['zone_id' => $zone, 'record_id' => $record['id'], 'checked_at' => now()]);
            app(TenantManager::class)->audit($tenant, 'dns_succeeded', ['hostname' => $hostname]);
            return true;
        } catch (DnsException $e) {
            $this->saveResult($tenant, 'failed', $e->getMessage(), $target, ['checked_at' => now()]);
            app(TenantManager::class)->audit($tenant, 'dns_failed');
            return false;
        }
    }
}
