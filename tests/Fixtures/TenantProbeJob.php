<?php

namespace Tests\Fixtures;

use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TenantProbeJob implements ShouldQueue
{
    use SerializesModels;

    public $user;
    public $fail;

    public function __construct(User $user, $fail = false)
    {
        $this->user = $user;
        $this->fail = $fail;
    }

    public function handle()
    {
        if ($this->fail) { throw new \RuntimeException('Expected test job failure'); }
        DB::connection('saas_central')->table('probe_results')->insert([
            'slug' => app(\App\Tenancy\TenantContext::class)->tenant->slug,
            'secret' => DB::table('records')->where('id', 1)->value('secret'),
            'email' => $this->user->email,
            'url' => url('/user/home'),
        ]);
    }
}
