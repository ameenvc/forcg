<?php

namespace App\Tenancy;

class Hostname
{
    public static function slug($host, $baseDomain, $prefix = 'tenant.')
    {
        $pattern = '/^' . preg_quote($prefix, '/') . '([a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)\.'
            . preg_quote(strtolower($baseDomain), '/') . '$/D';

        return preg_match($pattern, strtolower($host), $matches) ? $matches[1] : null;
    }
}
