<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Tenancy\CloudflareDns;
use App\Tenancy\Hosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CloudflareController extends Controller
{
    public function index(CloudflareDns $dns)
    {
        $settings = $dns->settings();
        // Never send the encrypted token or a decrypted token to a view.
        $configured = (bool) $settings;
        $zoneId = $settings->zone_id ?? '';
        $automatic = $settings->automatic ?? false;
        $targets = $settings ? json_decode($settings->targets, true) : [];
        $test = $settings ? ['status' => $settings->test_status, 'message' => $settings->test_message, 'at' => $settings->tested_at] : null;
        $nodes = Hosting::nodes();
        $counts = ['total' => \App\Tenancy\Tenant::count()];
        return view('saas.cloudflare', compact('configured', 'zoneId', 'automatic', 'targets', 'test', 'nodes', 'counts'));
    }

    public function save(Request $request, CloudflareDns $dns)
    {
        $settings = $dns->settings();
        $rules = ['zone_id' => 'required|regex:/^[a-f0-9]{32}$/D',
            'api_token' => ($settings ? 'nullable' : 'required') . '|string|min:20|max:512|regex:/^[A-Za-z0-9_-]+$/D',
            'automatic' => 'sometimes|boolean', 'targets' => 'required|array'];
        foreach (Hosting::nodes() as $id => $node) {
            $rules['targets.' . $id . '.type'] = 'required|in:A,AAAA,CNAME';
            $rules['targets.' . $id . '.content'] = 'nullable|string|max:253';
            $rules['targets.' . $id . '.proxied'] = 'sometimes|boolean';
        }
        $data = $request->validate($rules);
        $targets = [];
        foreach (Hosting::nodes() as $id => $node) {
            $input = $data['targets'][$id];
            $value = strtolower(rtrim(trim($input['content'] ?? ''), '.'));
            $type = $input['type'];
            $valid = !$value || ($type === 'A' && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
                || ($type === 'AAAA' && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
                || ($type === 'CNAME' && !filter_var($value, FILTER_VALIDATE_IP)
                    && strpos($value, '.') !== false && filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME));
            if (!$valid) {
                throw ValidationException::withMessages(['targets.' . $id . '.content' => 'Enter a valid ' . $type . ' target without a URL, port, or path.']);
            }
            if ($value && in_array($type, ['A', 'AAAA'], true)) { $value = inet_ntop(inet_pton($value)); }
            $targets[$id] = ['type' => $type, 'content' => $value, 'proxied' => !empty($input['proxied'])];
        }
        $values = ['zone_id' => $data['zone_id'], 'automatic' => !empty($data['automatic']), 'targets' => json_encode($targets),
            'test_status' => null, 'test_message' => null, 'tested_at' => null, 'updated_at' => now()];
        if (!empty($data['api_token'])) { $values['encrypted_token'] = Crypt::encryptString($data['api_token']); }
        if (!$settings) { $values['created_at'] = now(); }
        DB::connection(config('tenancy.central_connection'))->table('saas_cloudflare')->updateOrInsert(['id' => 1], $values);
        return redirect()->route('saas.cloudflare.index')->with('status', 'Cloudflare settings saved. Test the connection, then apply DNS for a tenant.');
    }

    public function test(CloudflareDns $dns)
    {
        $result = $dns->testConnection();
        return redirect()->route('saas.cloudflare.index')->with('status', $result['message']);
    }
}
