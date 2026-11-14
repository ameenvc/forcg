<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Tenancy\Tenant;
use App\Tenancy\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $search = substr((string) $request->query('search', ''), 0, 100);
        $tenants = Tenant::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('slug', 'like', '%' . $search . '%');
            });
        })->orderByDesc('id')->paginate(20);
        $counts = ['total' => Tenant::count(), 'active' => Tenant::where('status', 'active')->count(),
            'pending' => Tenant::whereIn('status', ['pending', 'provisioning', 'failed'])->count()];
        $events = \Illuminate\Support\Facades\DB::connection(config('tenancy.central_connection'))
            ->table('saas_events')->join('saas_tenants', 'saas_tenants.id', '=', 'saas_events.tenant_id')
            ->select('saas_events.*', 'saas_tenants.slug')->orderByDesc('saas_events.id')->limit(10)->get();
        return view('saas.tenants', compact('tenants', 'search', 'counts', 'events'));
    }

    public function store(Request $request, TenantManager $manager)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => ['required', 'regex:/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/D', 'not_in:admin,www,tenant,api', Rule::unique('saas_central.saas_tenants', 'slug')],
            'plan' => ['required', Rule::in(config('tenancy.plans'))],
            'owner_email' => 'required|email|max:190',
        ]);
        $tenant = $manager->register($data);
        return redirect()->route('saas.tenants.index')->with('status', 'Tenant registered. Provision its database with: php artisan tenants:provision ' . $tenant->slug);
    }

    public function update(Request $request, $id, TenantManager $manager)
    {
        $tenant = Tenant::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'plan' => ['required', Rule::in(config('tenancy.plans'))],
            'status' => ['sometimes', Rule::in(['active', 'suspended'])],
        ]);
        if (array_key_exists('status', $data)) {
            abort_unless($tenant->provisioned_at, 422, 'Provision the tenant database before activation.');
        }
        $manager->update($tenant, $data);
        return back()->with('status', 'Tenant updated.');
    }
}
