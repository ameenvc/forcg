<?php

namespace App\Tenancy;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TenantManager
{
    public function register(array $data)
    {
        $data['hosting_node'] = $data['hosting_node'] ?? config('tenancy.node_id');
        Validator::make($data, [
            'name' => 'required|string|max:150',
            'slug' => ['required', 'regex:/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/D', Rule::notIn($this->reservedSlugs()), Rule::unique('saas_central.saas_tenants', 'slug')],
            'hosting_node' => ['required', 'string', Rule::in(array_keys(Hosting::nodes()))],
            'owner_email' => 'required|email|max:190',
            'plan' => ['required', Rule::in(config('tenancy.plans'))],
        ])->validate();
        $database = config('tenancy.database_prefix') . str_replace('-', '_', $data['slug']);
        Validator::make(['database' => $database], ['database' => 'required|regex:/^[a-zA-Z0-9_]{1,64}$/D'])->validate();
        return DB::connection(config('tenancy.central_connection'))->transaction(function () use ($data, $database) {
            $tenant = Tenant::create([
                'name' => $data['name'], 'slug' => $data['slug'], 'owner_email' => $data['owner_email'],
                'plan' => $data['plan'], 'hosting_node' => $data['hosting_node'], 'database_name' => $database, 'status' => 'pending',
            ]);
            $this->audit($tenant, 'registered', ['hosting_node' => $tenant->hosting_node]);
            return $tenant;
        });
    }

    public function reservedSlugs()
    {
        $reserved = ['admin', 'www', 'api', 'tenant'];
        if (config('tenancy.host_prefix') === '') {
            $suffix = '.' . strtolower(config('tenancy.base_domain'));
            $admin = strtolower(config('tenancy.admin_domain'));
            if (substr($admin, -strlen($suffix)) === $suffix) {
                $reserved[] = substr($admin, 0, -strlen($suffix));
            }
        }
        return $reserved;
    }

    public function update(Tenant $tenant, array $data)
    {
        return DB::connection(config('tenancy.central_connection'))->transaction(function () use ($tenant, $data) {
            $before = $tenant->only(['name', 'plan', 'status']);
            $tenant->update($data);
            $this->audit($tenant, 'updated', ['before' => $before, 'after' => $tenant->only(['name', 'plan', 'status'])]);
        });
    }

    public function audit(Tenant $tenant, $event, array $details = [])
    {
        DB::connection(config('tenancy.central_connection'))->table('saas_events')->insert([
            'tenant_id' => $tenant->id, 'actor_id' => Auth::guard('superadmin')->id(),
            'event' => $event, 'details' => json_encode($details), 'created_at' => now(),
        ]);
    }
}
