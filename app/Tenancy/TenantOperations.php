<?php

namespace App\Tenancy;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class TenantOperations
{
    private function table()
    {
        return DB::connection(config('tenancy.central_connection'))->table('saas_operations');
    }

    public function enqueue(Tenant $tenant, $type, $password)
    {
        if (!in_array($type, ['provision', 'reset_password'], true) || strlen($password) < 12) {
            throw new RuntimeException('Invalid tenant operation.');
        }
        $hash = Hash::make($password);
        return DB::connection(config('tenancy.central_connection'))->transaction(function () use ($tenant, $type, $hash) {
            $tenant = Tenant::where('id', $tenant->id)->lockForUpdate()->firstOrFail();
            $allowed = $type === 'provision' ? $tenant->status === 'pending'
                : $tenant->provisioned_at && in_array($tenant->status, ['active', 'suspended'], true);
            if (!$allowed || $this->table()->where('tenant_id', $tenant->id)->whereIn('status', ['queued', 'running'])->exists()) {
                throw ValidationException::withMessages(['operation' => 'This workspace is not ready for this action or already has an action in progress.']);
            }
            $id = $this->table()->insertGetId([
                'tenant_id' => $tenant->id, 'actor_id' => Auth::guard('superadmin')->id(),
                'hosting_node' => Hosting::nodeId($tenant), 'type' => $type, 'password_hash' => $hash,
                'status' => 'queued', 'created_at' => now(), 'updated_at' => now(),
            ]);
            app(TenantManager::class)->audit($tenant, $type . '_requested', ['operation_id' => $id]);
            return $id;
        });
    }

    public function process($limit = 10)
    {
        $ids = $this->table()->where('hosting_node', config('tenancy.node_id'))->where('status', 'queued')
            ->orderBy('id')->limit($limit)->pluck('id');
        foreach ($ids as $id) {
            if (!$this->table()->where('id', $id)->where('status', 'queued')->update([
                'status' => 'running', 'started_at' => now(), 'updated_at' => now(),
            ])) { continue; }
            $operation = $this->table()->where('id', $id)->first();
            $context = app(TenantContext::class);
            $status = 'failed';
            try {
                $tenant = Tenant::findOrFail($operation->tenant_id);
                Hosting::assertLocal($tenant);
                if ($operation->type === 'provision') {
                    app(TenantProvisioner::class)->provision($tenant, $operation->password_hash);
                } elseif ($operation->type === 'reset_password') {
                    if (!$tenant->provisioned_at || !in_array($tenant->status, ['active', 'suspended'], true)) {
                        throw new RuntimeException('Workspace is not provisioned.');
                    }
                    $context->activate($tenant, true);
                    DB::connection('tenant')->transaction(function () use ($tenant, $operation) {
                        // Change only the registered owner; never target another client or platform account.
                        foreach (['users', 'admins'] as $table) {
                            $query = DB::connection('tenant')->table($table)->where('email', $tenant->owner_email);
                            if ($query->count() !== 1) { throw new RuntimeException('Owner account requires operator review.'); }
                            $query->update(['password' => $operation->password_hash, 'remember_token' => Str::random(60)]);
                        }
                    });
                } else { throw new RuntimeException('Unknown tenant operation.'); }
                $status = 'succeeded';
            } catch (\Throwable $e) {
                // No SQL, credential material, or customer data is copied into the dashboard/logs.
                \Illuminate\Support\Facades\Log::warning('Tenant operation requires review', ['operation_id' => $id, 'exception_type' => get_class($e)]);
            } finally {
                $context->end();
                $this->table()->where('id', $id)->update([
                    'status' => $status, 'password_hash' => null, 'finished_at' => now(), 'updated_at' => now(),
                ]);
                DB::connection(config('tenancy.central_connection'))->table('saas_events')->insert([
                    'tenant_id' => $operation->tenant_id, 'actor_id' => $operation->actor_id,
                    'event' => $operation->type . '_' . $status, 'details' => json_encode(['operation_id' => $id]), 'created_at' => now(),
                ]);
            }
        }
        return count($ids);
    }
}
