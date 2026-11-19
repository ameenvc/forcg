<?php

namespace App\Tenancy;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class Hosting
{
    public static function nodes()
    {
        $nodes = config('tenancy.hosting_nodes');
        if (!is_array($nodes) || !$nodes) {
            throw new RuntimeException('TENANCY_HOSTING_NODES must be a non-empty JSON object.');
        }
        foreach ($nodes as $id => $node) {
            if (!preg_match('/^[a-z0-9][a-z0-9_-]{0,62}$/D', (string) $id)
                || !is_array($node) || empty($node['name']) || empty($node['region'])
                || !is_string($node['name']) || !is_string($node['region'])) {
                throw new RuntimeException('Each hosting node needs a valid ID, name, and region.');
            }
        }
        if (!isset($nodes[config('tenancy.node_id')])) {
            throw new RuntimeException('TENANCY_NODE_ID must identify a configured hosting node.');
        }
        return $nodes;
    }

    public static function nodeId(Tenant $tenant)
    {
        // Existing installations stay assigned to the original local node.
        return $tenant->hosting_node ?: 'local';
    }

    public static function isLocal(Tenant $tenant)
    {
        return self::nodeId($tenant) === config('tenancy.node_id');
    }

    public static function assertLocal(Tenant $tenant)
    {
        $nodes = self::nodes();
        $id = self::nodeId($tenant);
        if (!isset($nodes[$id]) || !self::isLocal($tenant)) {
            throw new RuntimeException('Tenant ' . $tenant->slug . ' must be operated on its assigned hosting node: ' . $id);
        }
    }

    public static function databaseConfig()
    {
        $name = config('tenancy.tenant_connection') ?: config('tenancy.central_connection');
        return DB::connection($name)->getConfig();
    }
}
