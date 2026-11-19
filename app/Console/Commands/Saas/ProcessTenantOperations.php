<?php

namespace App\Console\Commands\Saas;

use App\Tenancy\TenantOperations;
use Illuminate\Console\Command;

class ProcessTenantOperations extends Command
{
    protected $signature = 'tenants:process-operations';
    protected $description = 'Process dashboard provisioning and owner password actions assigned to this node';

    public function handle(TenantOperations $operations)
    {
        if (!config('tenancy.enabled')) { $this->error('Tenancy is disabled.'); return 1; }
        $this->info('Processed ' . $operations->process() . ' tenant operations.');
        return 0;
    }
}
