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
            'pending' => Tenant::whereIn('status', ['pending', 'provisioning', 'failed'])->count(),
            'suspended' => Tenant::where('status', 'suspended')->count()];
        $events = \Illuminate\Support\Facades\DB::connection(config('tenancy.central_connection'))
            ->table('saas_events')->join('saas_tenants', 'saas_tenants.id', '=', 'saas_events.tenant_id')
            ->select('saas_events.*', 'saas_tenants.slug')->orderByDesc('saas_events.id')->limit(10)->get();
        $latestOperations = \Illuminate\Support\Facades\DB::connection(config('tenancy.central_connection'))
            ->table('saas_operations')->selectRaw('MAX(id)')->whereIn('tenant_id', $tenants->pluck('id'))->groupBy('tenant_id');
        $operations = \Illuminate\Support\Facades\DB::connection(config('tenancy.central_connection'))
            ->table('saas_operations')->select('id', 'tenant_id', 'type', 'status', 'created_at')
            ->whereIn('id', $latestOperations)->get()->keyBy('tenant_id');
        $hostingNodes = \App\Tenancy\Hosting::nodes();
        return view('saas.tenants', compact('tenants', 'search', 'counts', 'events', 'hostingNodes', 'operations'));
    }

    public function store(Request $request, TenantManager $manager)
    {
        $data = $request->validate([
            'password' => 'sometimes|required|string|min:12|max:128|confirmed',
            'name' => 'required|string|max:150',
            'slug' => ['required', 'regex:/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/D', Rule::notIn(app(TenantManager::class)->reservedSlugs()), Rule::unique('saas_central.saas_tenants', 'slug')],
            'plan' => ['required', Rule::in(config('tenancy.plans'))],
            'hosting_node' => ['sometimes', 'required', 'string', Rule::in(array_keys(\App\Tenancy\Hosting::nodes()))],
            'owner_email' => 'required|email|max:190',
        ]);
        \Illuminate\Support\Facades\DB::connection(config('tenancy.central_connection'))->transaction(function () use ($manager, $data) {
            $tenant = $manager->register($data);
            if (isset($data['password'])) {
                app(\App\Tenancy\TenantOperations::class)->enqueue($tenant, 'provision', $data['password']);
            }
        });
        return redirect()->route('saas.tenants.index')->with('status', isset($data['password'])
            ? 'Tenant registered. Automatic setup is queued on its hosting server. Refresh to check progress.'
            : 'Tenant registered. Set the owner password using Set up in the tenant directory.');
    }

    public function operation(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $data = $request->validate([
            'type' => ['required', Rule::in(['provision', 'reset_password'])],
            'password' => 'required|string|min:12|max:128|confirmed',
        ]);
        app(\App\Tenancy\TenantOperations::class)->enqueue($tenant, $data['type'], $data['password']);
        return back()->with('status', 'Action queued on the assigned hosting server. Refresh to check progress.');
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
