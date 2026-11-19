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

    public function scopeHostedLocally($query)
    {
        return $query->where(function ($q) {
            $q->where('hosting_node', config('tenancy.node_id'));
            if (config('tenancy.node_id') === 'local') {
                $q->orWhereNull('hosting_node');
            }
        });
    }

    public function hostingLabel()
    {
        $id = Hosting::nodeId($this);
        $nodes = Hosting::nodes();
        return isset($nodes[$id]) ? $nodes[$id]['region'] . ' · ' . $nodes[$id]['name'] : $id . ' (not configured)';
    }

    public function url()
    {
        $port = parse_url(config('app.url'), PHP_URL_PORT);
        return config('tenancy.scheme') . '://' . config('tenancy.host_prefix')
            . $this->slug . '.' . config('tenancy.base_domain') . ($port ? ':' . $port : '');
    }
}
