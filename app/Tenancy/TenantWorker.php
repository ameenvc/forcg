<?php

namespace App\Tenancy;

use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;

class TenantWorker extends Worker
{
    public function process($connectionName, $job, WorkerOptions $options)
    {
        $context = app(TenantContext::class);
        $context->end();
        try {
            $id = $job->payload()['tenant_id'] ?? null;
            if ($id) {
                $tenant = Tenant::find($id);
                if (!$tenant || $tenant->status !== 'active' || !Hosting::isLocal($tenant)) {
                    // Pause without deserializing models or executing job callbacks.
                    $job->release(300);
                    return;
                }
                $context->activate($tenant);
            } else {
                $context->central();
            }
            // Context is active even for logging/failure listeners which deserialize models.
            return parent::process($connectionName, $job, $options);
        } finally {
            $context->end();
        }
    }
}
