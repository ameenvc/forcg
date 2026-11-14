<?php

namespace App\Tenancy;

class Application extends \Illuminate\Foundation\Application
{
    public $tenantPublicPath;

    public function publicPath($path = '')
    {
        $path = ltrim($path, '/');
        // Keep shared application assets, but isolate every legacy upload path.
        if ($this->tenantPublicPath && ($path === '' || preg_match('#^(storage|files|uploads)(/|$)#', $path))) {
            return $this->tenantPublicPath . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path);
        }

        return parent::publicPath() . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path);
    }
}
