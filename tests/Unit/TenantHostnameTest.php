<?php

namespace Tests\Unit;

use App\Tenancy\Hostname;
use PHPUnit\Framework\TestCase;

class TenantHostnameTest extends TestCase
{
    public function test_single_level_tenant_addresses_are_the_default()
    {
        $this->assertSame('waraq', Hostname::slug('waraq.cgbod.com', 'cgbod.com'));
        $this->assertNull(Hostname::slug('waraq.devsaas.cgbod.com', 'cgbod.com'));
        $this->assertNull(Hostname::slug('tenant.waraq.cgbod.com', 'cgbod.com'));
        $this->assertNull(Hostname::slug('waraq.cgbod.com.evil.test', 'cgbod.com'));
    }

    public function test_exact_tenant_hostname_is_required()
    {
        $this->assertSame('leanston', Hostname::slug('tenant.leanston.cgbod.com', 'cgbod.com', 'tenant.'));
        $this->assertSame('0', Hostname::slug('tenant.0.cgbod.com', 'cgbod.com', 'tenant.'));
        $this->assertSame('leanston', Hostname::slug('TENANT.LEANSTON.CGBOD.COM', 'cgbod.com', 'tenant.'));
        foreach (['leanston.cgbod.com', 'tenant.leanston.cgbod.com.evil.com', 'admin.cgbod.com',
            'tenant..cgbod.com', 'tenant.-bad.cgbod.com', 'tenant.bad-.cgbod.com',
            'tenant.a.b.cgbod.com', 'tenant.' . str_repeat('a', 64) . '.cgbod.com'] as $host) {
            $this->assertNull(Hostname::slug($host, 'cgbod.com', 'tenant.'));
        }
    }
}
