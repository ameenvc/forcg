<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'saas_tenants';
    protected $guarded = ['id'];

    public function getConnectionName()
    {
        return config('tenancy.central_connection');
    }

    public function url()
    {
        $port = parse_url(config('app.url'), PHP_URL_PORT);
        return config('tenancy.scheme') . '://' . config('tenancy.host_prefix')
            . $this->slug . '.' . config('tenancy.base_domain') . ($port ? ':' . $port : '');
    }
}
