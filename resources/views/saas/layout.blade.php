<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Platform overview · CGBOD')</title>
    <link rel="icon" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="stylesheet" href="{{ asset('css/oneui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/saas-admin.css') }}">
    <script defer src="{{ asset('js/jquery.min.js') }}"></script>
    <script defer src="{{ asset('js/oneui.app.js') }}"></script>
    <script defer src="{{ asset('js/saas-admin.js') }}"></script>
</head>
@php($loginPage = request()->routeIs('saas.login'))
<body class="saas-app {{ $loginPage ? 'saas-auth' : '' }}">
@if($loginPage)
    <div id="page-container">
        <main id="main-container">@yield('content')</main>
    </div>
@else
    <div id="page-container" class="sidebar-o sidebar-dark side-scroll page-header-fixed main-content-boxed">
        <nav id="sidebar" aria-label="Platform navigation">
            <div class="content-header saas-side-brand">
                <a class="saas-brand text-white" href="{{ route('saas.tenants.index') }}" aria-label="CGBOD platform overview">
                    <img class="saas-cgbod-logo" src="{{ asset('media/photos/logo_cgbod.png') }}" alt="CGBOD" width="120" height="40">
                </a>
                <button type="button" class="btn btn-sm btn-dual d-lg-none" data-toggle="layout" data-action="sidebar_close" aria-label="Close navigation"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <div class="saas-side-caption">SUPER ADMINISTRATION</div>
            <div class="js-sidebar-scroll">
                <div class="content-side">
                    <ul class="nav-main">
                        <li class="nav-main-heading">Workspace</li>
                        <li class="nav-main-item"><a class="nav-main-link active" data-section-link href="{{ route('saas.tenants.index') }}#overview"><i class="nav-main-link-icon si si-speedometer" aria-hidden="true"></i><span class="nav-main-link-name">Overview</span></a></li>
                        <li class="nav-main-item"><a class="nav-main-link" data-section-link href="{{ route('saas.tenants.index') }}#tenants"><i class="nav-main-link-icon si si-organization" aria-hidden="true"></i><span class="nav-main-link-name">Tenants</span><span class="nav-main-link-badge badge badge-primary">{{ $counts['total'] ?? 0 }}</span></a></li>
                        <li class="nav-main-item">@if(request()->routeIs('saas.tenants.index'))<button type="button" class="nav-main-link saas-nav-button" data-toggle="modal" data-target="#createTenantModal"><i class="nav-main-link-icon si si-plus" aria-hidden="true"></i><span class="nav-main-link-name">Register a tenant</span></button>@else<a class="nav-main-link" href="{{ route('saas.tenants.index') }}#register"><i class="nav-main-link-icon si si-plus" aria-hidden="true"></i><span class="nav-main-link-name">Register a tenant</span></a>@endif</li>
                        <li class="nav-main-heading">Platform</li>
                        <li class="nav-main-item"><a class="nav-main-link {{ request()->routeIs('saas.cloudflare.*') ? 'active' : '' }}" href="{{ route('saas.cloudflare.index') }}"><i class="nav-main-link-icon si si-globe" aria-hidden="true"></i><span class="nav-main-link-name">Cloudflare DNS</span></a></li>
                        <li class="nav-main-item"><a class="nav-main-link" data-section-link href="{{ route('saas.tenants.index') }}#activity"><i class="nav-main-link-icon si si-clock" aria-hidden="true"></i><span class="nav-main-link-name">Activity log</span></a></li>
                        <li class="nav-main-item">@if(request()->routeIs('saas.tenants.index'))<button type="button" class="nav-main-link saas-nav-button" data-toggle="modal" data-target="#setupGuideModal"><i class="nav-main-link-icon si si-book-open" aria-hidden="true"></i><span class="nav-main-link-name">Workspace setup</span></button>@else<a class="nav-main-link" href="{{ route('saas.tenants.index') }}#setup"><i class="nav-main-link-icon si si-book-open" aria-hidden="true"></i><span class="nav-main-link-name">Workspace setup</span></a>@endif</li>
                    </ul>
                    <div class="saas-side-help"><i class="si si-bulb" aria-hidden="true"></i><h3>A new space to grow.</h3><p>Bring your next organization onto CGBOD.</p>@if(request()->routeIs('saas.tenants.index'))<button class="btn btn-sm btn-alt-primary btn-block" type="button" data-toggle="modal" data-target="#createTenantModal">Add an organization <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></button>@else<a class="btn btn-sm btn-alt-primary btn-block" href="{{ route('saas.tenants.index') }}#register">Add an organization <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></a>@endif</div>
                </div>
            </div>
            <div class="saas-side-footer"><span class="saas-online-dot"></span> CGBOD control center <span class="badge badge-dark ml-auto">SaaS</span></div>
        </nav>
        <header id="page-header">
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-dual mr-3" data-toggle="layout" data-action="sidebar_toggle" aria-label="Toggle navigation"><i class="fa fa-bars" aria-hidden="true"></i></button>
                    <nav aria-label="Breadcrumb"><ol class="breadcrumb saas-breadcrumb mb-0"><li class="breadcrumb-item d-none d-sm-block">Platform</li><li class="breadcrumb-item active" aria-current="page">{{ request()->routeIs('saas.cloudflare.*') ? 'Cloudflare DNS' : 'Overview' }}</li></ol></nav>
                </div>
                <div class="d-flex align-items-center">
                    <span class="saas-environment d-none d-md-inline-flex mr-4"><i class="si si-globe mr-2" aria-hidden="true"></i>{{ request()->getHost() }}</span>
                    <div class="dropdown">
                        <button type="button" class="btn saas-account-toggle" id="accountMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="saas-avatar saas-avatar-sm">{{ mb_strtoupper(mb_substr(auth('superadmin')->user()->name, 0, 1)) }}</span>
                            <span class="d-none d-sm-block text-left mx-2"><strong>{{ auth('superadmin')->user()->name }}</strong><small>Platform administrator</small></span><i class="fa fa-angle-down font-size-sm text-muted ml-1" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right font-size-sm" aria-labelledby="accountMenu"><div class="px-3 py-2 text-muted saas-account-email">{{ auth('superadmin')->user()->email }}</div><div class="dropdown-divider"></div><form method="post" action="{{ route('saas.logout') }}">@csrf<button type="submit" class="dropdown-item"><i class="si si-logout mr-2" aria-hidden="true"></i>Sign out</button></form></div>
                    </div>
                </div>
            </div>
        </header>
        <main id="main-container">
            <div class="content saas-content">
                @include('saas.partials.alerts')
                @yield('content')
            </div>
        </main>
        <footer id="page-footer" class="bg-white"><div class="content py-3 d-sm-flex justify-content-between"><span class="font-size-sm text-muted">© {{ date('Y') }} <strong>CGBOD</strong>. All rights reserved.</span><span class="saas-powered-by"><span>Powered by</span><img src="{{ asset('images/setilas.png') }}" alt="Setilas" width="110" height="33"></span></div></footer>
    </div>
@endif
<div id="saas-feedback" class="saas-feedback" role="status" aria-live="polite" hidden></div>
</body>
</html>
