<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Tenancy\TenantContext;
use Illuminate\Support\Facades\Auth;

class TenantFileController extends Controller
{
    public function show($directory, $path)
    {
        abort_unless(app(TenantContext::class)->tenant, 404);
        abort_if(strpos($path, '\\') !== false || in_array('..', explode('/', $path), true) || strpos($path, chr(0)) !== false, 404);
        // Tenant branding is needed by the login page; other files require a session.
        $branding = $directory === 'storage' && strpos($path, 'clientlogos/') === 0;
        abort_unless($branding || Auth::guard('web')->check() || Auth::guard('admin')->check(), 403);
        $root = realpath(public_path($directory));
        $file = realpath(public_path($directory . '/' . $path));
        abort_unless($root && $file && is_file($file) && strpos($file, $root . DIRECTORY_SEPARATOR) === 0, 404);
        if ($branding) {
            $logos = realpath(public_path('storage/clientlogos'));
            abort_unless($logos && strpos($file, $logos . DIRECTORY_SEPARATOR) === 0, 404);
        }
        $safeInline = in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ico'], true);
        return response()->download($file, basename($file), [
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "sandbox; default-src 'none'",
            'Cache-Control' => 'private, no-store',
        ], $safeInline ? 'inline' : 'attachment');
    }
}
