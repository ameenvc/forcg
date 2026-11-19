<?php

// Runs in a fresh PHP process so Eloquent has not already been booted by PHPUnit.
require __DIR__ . '/../../vendor/autoload.php';
foreach (['APP_ENV' => 'testing', 'APP_DEBUG' => 'false',
    'APP_KEY' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
    'DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => $argv[1],
    'TENANCY_ENABLED' => 'true', 'TENANCY_SOURCE_CONNECTION' => 'sqlite',
    'TENANCY_BASE_DOMAIN' => 'cgbod.test', 'TENANCY_HOST_PREFIX' => 'tenant.', 'TENANCY_SCHEME' => 'http',
    'TENANCY_STORAGE_ROOT' => $argv[2]] as $key => $value) {
    $_ENV[$key] = $_SERVER[$key] = $value;
}
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('http://tenant.alpha.cgbod.test/superadmin/home');
$response = $kernel->handle($request);
$tenant = $request->attributes->get('tenant');
echo json_encode(['status' => $response->getStatusCode(), 'tenant' => $tenant ? $tenant->slug : null]);
$kernel->terminate($request, $response);
