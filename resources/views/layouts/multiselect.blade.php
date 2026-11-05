<!doctype html>
<html lang="en">

<head>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .select2-results__options,
        .select2 {
            font-size: 13px;
        }

        .disabled-color {
            color: #3f4253 !important;
        }

        .cursor-none {
            cursor: inherit !important;
        }

        .hover-text-color {
            color: #25E5D4 !important;
        }

    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    {{-- Script for getting idea on user perspectives --}}
    <script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "fxie009nn8");

    </script>
    {{-- End script for getting idea on user perspectives --}}


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:image" content="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <meta property="og:url" content="{{config('app.url')}}">
    <!-- Fonts and Styles -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}

    <link rel="stylesheet" href="{{asset('js/plugins/dropzone/dist/min/dropzone.min.css')}}">
    <link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/flash_new_tag.css')}}">
    <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">
    @yield('css_multiselect')

    @include('shared.theme.assets')
</head>

<body>

    <div id="page-container" class="sidebar-o sidebar-mini enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <aside id="side-overlay" class="font-size-sm">
            <!-- Side Header -->
            <div class="content-header border-bottom">
                <!-- User Avatar -->
                <a class="img-link mr-1" href="javascript:void(0)">
                    <img class="img-avatar img-avatar32" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                </a>
                <!-- END User Avatar -->

                <!-- User Info -->
                <div class="ml-2">
                    <a class="text-dark font-w600 font-size-sm" href="javascript:void(0)">{{ Auth::user()->name }}</a>
                </div>
                <!-- END User Info -->

                <!-- Close Side Overlay -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="ml-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                    <i class="fa fa-fw fa-times"></i>
                </a>
                <!-- END Close Side Overlay -->
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="content-side">
                <p>
                    Content..
                </p>
            </div>
            <!-- END Side Content -->
        </aside>
        <!-- END Side Overlay -->
        <nav id="sidebar" style="background-color:#191e3b;" aria-label="Main Navigation">
            <!-- Side Header -->
            <div class="content-header bg-white-5">
                <!-- Logo -->
                <a class="font-w600 text-dual" href="/">
                    <span class="smini-visible">
                        <!--<i class="fa fa-circle-notch text-primary"></i>-->
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" style="margin-left:-5px;" viewBox="0 0 24 24" fill="#8451d6">
                            <path d="M12 6.76l1.379 4.246h4.465l-3.612 2.625 1.379 4.246-3.611-2.625-3.612 2.625 1.379-4.246-3.612-2.625h4.465l1.38-4.246zm0-6.472l-2.833 8.718h-9.167l7.416 5.389-2.833 8.718 7.417-5.388 7.416 5.388-2.833-8.718 7.417-5.389h-9.167l-2.833-8.718z" />
                        </svg>
                    </span>
                    <span class="smini-hide font-size-h5 tracking-wider">
                        @if(Session::get('app_name')=='quickms_logo.png')
                        {{Session::get('appname_to_display')}} <span class="font-w400">MS</span>
                        @elseif(Session::get('app_name')=='quickcg_logo.png')
                        {{Session::get('appname_to_display')}} <span class="font-w400">CG</span>
                        @else
                        {{ config('app.name') }}
                        @endif

                    </span>
                </a>
                <!-- END Logo -->

                <!-- Extra -->
                <div>
                    <!-- Options -->
                    <div class="dropdown d-inline-block ml-2">
                        <a class="btn btn-sm btn-dual" id="sidebar-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" style="margin-top:-5px;" viewBox="0 0 24 24" fill="white">
                                <path d="M12 6.76l1.379 4.246h4.465l-3.612 2.625 1.379 4.246-3.611-2.625-3.612 2.625 1.379-4.246-3.612-2.625h4.465l1.38-4.246zm0-6.472l-2.833 8.718h-9.167l7.416 5.389-2.833 8.718 7.417-5.388 7.416 5.388-2.833-8.718 7.417-5.389h-9.167l-2.833-8.718z" />
                            </svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right font-size-sm smini-hide border-0" aria-labelledby="sidebar-themes-dropdown">
                            <!-- Color Themes -->
                            <!-- Layout API, functionality initialized in Template._uiHandleTheme() -->
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="default" href="#">
                                <span>Default</span>
                                <i class="fa fa-circle text-default"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="{{ mix('css/themes/amethyst.css') }}" href="#">
                                <span>Amethyst</span>
                                <i class="fa fa-circle text-amethyst"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="{{ mix('css/themes/city.css') }}" href="#">
                                <span>City</span>
                                <i class="fa fa-circle text-city"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="{{ mix('css/themes/flat.css') }}" href="#">
                                <span>Flat</span>
                                <i class="fa fa-circle text-flat"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="{{ mix('css/themes/modern.css') }}" href="#">
                                <span>Modern</span>
                                <i class="fa fa-circle text-modern"></i>
                            </a>
                            <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" data-toggle="theme" data-theme="{{ mix('css/themes/smooth.css') }}" href="#">
                                <span>Smooth</span>
                                <i class="fa fa-circle text-smooth"></i>
                            </a>
                            <!-- END Color Themes -->

                            <div class="dropdown-divider"></div>

                            <!-- Sidebar Styles -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <a class="dropdown-item font-w500" data-toggle="layout" data-action="sidebar_style_light" href="#">
                                <span>Sidebar Light</span>
                            </a>
                            <a class="dropdown-item font-w500" data-toggle="layout" data-action="sidebar_style_dark" href="#">
                                <span>Sidebar Dark</span>
                            </a>
                            <!-- Sidebar Styles -->

                            <div class="dropdown-divider"></div>

                            <!-- Header Styles -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <a class="dropdown-item font-w500" data-toggle="layout" data-action="header_style_light" href="#">
                                <span>Header Light</span>
                            </a>
                            <a class="dropdown-item font-w500" data-toggle="layout" data-action="header_style_dark" href="#">
                                <span>Header Dark</span>
                            </a>
                            <!-- Header Styles -->
                        </div>
                    </div>
                    <!-- END Options -->

                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="d-lg-none btn btn-sm btn-dual ml-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                        <i class="fa fa-fw fa-times"></i>
                    </a>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Extra -->
            </div>
            <!-- END Side Header -->
            @php
                $currentUrl = url()->current();
                $urlPath = parse_url($currentUrl, PHP_URL_PATH);
                $routeName = \Request::route()->getName();
            @endphp
            <!-- Sidebar Scrolling -->
            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side">
                    <ul class="nav-main">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/user/home' ? 'active' : ''}}" href="/user/home" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-home" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Home</span>
                            </a>
                        </li>
                        @if(Auth::check())
                            @php
                            $userEmail = Auth::user()->email;
                            $adminEmail = App\Admin::where('email', $userEmail)->first();
                            @endphp
                        @if($adminEmail && $userEmail == $adminEmail->email)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/admin/home' ? 'active' : ''}}" href="/admin/home" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon si si-settings fa-2x" style="font-size:15px;font-weight:800;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Admin Dashboard</span>
                            </a>
                        </li>
                        @endif
                        @endif
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/user/my-board' ? 'active' : ''}}" href="/user/my-board" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-clipboard-list" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">My Board</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="{{ $enable_dashboard_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/dashboard' ? 'active' : ''}}" href="{{ $enable_dashboard_auth || $enable_auth ? url('/user/dashboard') : '#' }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-bullseye" style="font-size:15px;"></i>
                                @if($enable_auth || $enable_dashboard_auth)
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Dashboard</span>
                                @else
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#3f4253'" onMouseOut="this.style.color='#888EA8'">Dashboard</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/user/score' ? 'active' : ''}}" href="/user/score" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-star" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">ESG Scoring</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/user/viewuserlist' ? 'active' : ''}}" href="/user/viewuserlist" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class='nav-main-link-icon fa fa-2x fa-user' style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Users</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('user.logout') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class='nav-main-link-icon fas fa-sign-out-alt' style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Logout</span>
                            </a>
                        </li>

                        <li class="nav-main-heading" style="background-color:#191e3b;">MODULES</li>

                        <li class="nav-main-item">

                        @if($model->auditmanagement==1)
                        <li class="nav-main-item {{strpos($routeName,'audit')!==false || strpos($urlPath,'actionsystem')!==false || $urlPath == '/user/checklistlibrary' ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/auditmgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon far fa-edit" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Audit Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $audit_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/auditschedule' ? 'active' : ''}}" href="{{ $audit_auth || $enable_auth ? url('/user/auditschedule') : '#' }}">

                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Audit Schedule</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $audit_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/checklistlibrary' ? 'active' : ''}}" href="{{ $audit_auth || $enable_auth ? url('/user/checklistlibrary') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Checklist Library</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $audit_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/auditmgmt' ? 'active' : ''}}" href="{{ $audit_auth || $enable_auth ? url('/user/auditmgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Open NCRs</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $audit_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/actionsystem/folders' ? 'active' : ''}}" href="{{ $audit_auth || $enable_auth ? url('/user/actionsystem/folders') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed NCRs</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $audit_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/actionsystem/openissue' ? 'active' : ''}}" href="{{ $audit_auth || $enable_auth ? url('/user/actionsystem/openissue') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">My Open NCRs</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $audit_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/internalaudit/auditorslist' ? 'active' : ''}}" href="{{ $audit_auth || $enable_auth ? url('/user/internalaudit/auditorslist') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Auditors List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $audit_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/actionsystem/analysis' ? 'active' : ''}}" href="{{ $audit_analysis_auth || $enable_auth ? url('/user/actionsystem/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Audit Analysis</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                        @if($model->riskmanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'/user/risks/')!==false || strpos($urlPath,'/user/riskmgmt/')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{url('/user/riskmgmt')}}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fas fa-exclamation-triangle" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Enterprise Risk</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $risk_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/riskmgmt' ? 'active' : ''}}" href="{{ $risk_auth || $enable_auth ? url('/user/riskmgmt') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Risk Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $risk_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/riskmgmt/mitigations' ? 'active' : ''}}"
                                       href="{{ $risk_auth || $enable_auth ? route('mitigations.index') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Mitigating Actions</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $risk_all_pages || $enable_all ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/riskmgmt/racm' ? 'active' : ''}}"
                                       href="{{ $risk_all_pages || $enable_all ? route('racm.index') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">RACM</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $risk_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/risks/closedrisks' ? 'active' : ''}}" href="{{ $risk_auth || $enable_auth ? url('/user/risks/closedrisks') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed Risks</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $risk_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/risks/analysis' ? 'active' : ''}}" href="{{ $risk_analysis_auth || $enable_auth ? url('/user/risks/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Risk Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->compliance==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/compliance')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/compliancemgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-gavel" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Compliance Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $compliance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/compliancemgmt' ? 'active' : ''}}" href="{{ $complaint_auth || $enable_auth ? url('/user/compliancemgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Legal Register</span>
                                    </a>
                                </li>
                                {{-- <li class="nav-main-item">
                                    <a class="{{ $compliance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $complaint_auth || $enable_auth ? url('/user/compliance/myactions') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">My Actions</span>
                                    </a>
                                </li> --}}
                                <li class="nav-main-item">
                                    <a class="{{ $compliance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/compliance/licensepermits' ? 'active' : ''}}" href="{{ $compliance_auth || $enable_auth ? url('/user/compliance/licensepermits') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">License / Permits</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $compliance_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/compliance/companalysis' ? 'active' : ''}}" href="{{ $compliance_analysis_auth || $enable_auth ? url('/user/compliance/companalysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif


                        @if($model->documentmanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/doc')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ route('doc.home')}}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-file-upload" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Document Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'doc.governancehub')===0)
                                    <a class="{{ $document_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.governancehub') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $document_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.governancehub') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Governance Hub</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'doc.home')===0)
                                    <a class="{{ $document_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.home') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $document_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.home') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Master List</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'doc.register')===0)
                                    <a class="{{ $document_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $document_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.register') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">DAR Register</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'doc.history')===0)
                                    <a class="{{ $document_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.history') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $document_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? route('doc.history') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Change History</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'doc.explorer')===0)
                                    <a class="{{ $document_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? '/user/doc/explorer#elf_l1_XA' : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $document_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $document_auth || $enable_auth ? '/user/doc/explorer#elf_l1_XA' : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Doc Explorer</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'doc.analysis') === 0)
                                    <a class="{{ $document_analysis_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $document_analysis_auth || $enable_auth ? route('doc.analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $document_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $document_analysis_auth || $enable_auth ? route('doc.analysis') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Analysis</span>
                                        </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->correctiveaction==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/corrective')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/corrective/corrissues') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-exclamation-circle" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">NCR & Corrective Actions</span>
                            </a>
                            <ul class="nav-main-submenu">
                                {{-- <li class="nav-main-item">
                                    <a class="{{ $corrective_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/corrective/ncr-ca-register' ? 'active':''}}" href="{{ $corrective_auth || $enable_auth ? url('/user/corrective/ncr-ca-register') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Open CAs</span>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-main-item">
                                    <a class="{{ $corrective_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/corrective/closedissue' ? 'active':''}}" href="{{ $corrective_auth || $enable_auth ? url('/user/corrective/closedissue') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed CAs</span>
                                    </a>
                                </li> --}}
                                {{-- <li class="nav-main-item">
                                    <a class="{{ $corrective_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/corrective/openissue' ? 'active':''}}" href="{{ $corrective_auth || $enable_auth ? url('/user/corrective/openissue') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">My Open CAs</span>
                                    </a>
                                </li> --}}
                                <li class="nav-main-item">
                                    <a class="{{ $corrective_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/corrective/corrissues' ? 'active':''}}" href="{{ $corrective_auth || $enable_auth ? url('/user/corrective/corrissues') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">NCR & CA Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $corrective_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/corrective/analysis' ? 'active':''}}" href="{{ $corrective_analysis_auth || $enable_auth ? url('/user/corrective/analysis') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">NCR & CA Analysis</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        @endif
                        @if($model->objectives==1)
                        <li class="nav-main-item">
                            <a class="{{ $objective_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{strpos($urlPath,'user/objectives')!==false || strpos($urlPath,'user/objects')!==false ? 'active':''}}" href="{{ $objective_auth || $enable_auth ? url('/user/objectives/folders') : '#' }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-bullseye" style="font-size:15px;"></i>
                                @if($enable_auth || $objective_auth)
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Objectives</span>
                                @else
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#3f4253'" onMouseOut="this.style.color='#888EA8'">Objectives</span>
                                @endif
                            </a>
                        </li>

                        @endif
                        @if($model->opportunity==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/opportunity')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ route('opportunity.oppindex') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-briefcase" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Opportunity</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'opportunity.oppindex')===0)
                                    <a class="{{ $opportunity_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $opportunity_auth || $enable_auth ? route('opportunity.oppindex') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $opportunity_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $opportunity_auth || $enable_auth ? route('opportunity.oppindex') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Opportunity Register</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'opportunity.oppaccomplished')===0)
                                    <a class="{{ $opportunity_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $opportunity_auth || $enable_auth ? route('opportunity.oppaccomplished') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $opportunity_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $opportunity_auth || $enable_auth ? route('opportunity.oppaccomplished') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Accomplished</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'opportunity.oppanalysis')===0)
                                    <a class="{{ $opportunity_analysis_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $opportunity_analysis_auth || $enable_auth ? route('opportunity.oppanalysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $opportunity_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $opportunity_analysis_auth || $enable_auth ? route('opportunity.oppanalysis') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Analysis</span>
                                        </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->managementreview==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/management')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{  route('management.meetings') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-2x fa-users" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Management Review</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'management.meetings')===0)
                                    <a class="{{ $management_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $management_auth || $enable_auth ? route('management.meetings') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $management_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $management_auth || $enable_auth ? route('management.meetings') : '#' }}">
                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Meetings</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $management_analysis_page || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/management/analysis' ? 'active':''}}" href="{{ $management_auth || $enable_auth ? route('management.analysis') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if($model->ohsrisk==1)

                        <li class="nav-main-item {{strpos($urlPath,'user/ohsrisk/')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/ohsrisk/folder') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-bullseye" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Health and Safety Risk</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $hsr_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/ohsrisk/folder' ? 'active' : ''}}" href="{{ $hsr_auth || $enable_auth ? url('/user/ohsrisk/folder') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Departmental</span>
                                    </a>
                                </li>
                                @if($division_count)
                                <li class="nav-main-item">
                                    <a class="{{ $hsr_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/ohsrisk/folder/divisional' ? 'active' : ''}}" href="{{ $hsr_auth || $enable_auth ? url('/user/ohsrisk/folder/divisional') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Divisional</span>
                                    </a>
                                </li>
                                @endif
                                @if($area_count)
                                <li class="nav-main-item">
                                    <a class="{{ $hsr_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/ohsrisk/folder/subdivisional' ? 'active' : ''}}" href="{{ $hsr_auth || $enable_auth ? url('/user/ohsrisk/folder/subdivisional') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Sub-Division</span>
                                    </a>
                                </li>
                                @endif
                            </ul>

                        </li>

                        @endif
                        @if($model->enviaspects==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/enviaspects')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/enviaspects/folder') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-leaf" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Environmental Aspect</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $envi_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/enviaspects/folder' ? 'active' : ''}}" href="{{ $envi_auth || $enable_auth ? url('/user/enviaspects/folder') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Aspects</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $envi_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/enviaspects/all' ? 'active' : ''}}" href="{{ $envi_auth || $enable_auth ? route('enviaspects.all') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">All Aspects</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $envi_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/enviaspects/analysis' ? 'active' : ''}}" href="{{ $envi_analysis_auth || $enable_auth ? url('/user/enviaspects/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->contextmanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/context')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/contextmgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fab fa-contao" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Context Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $context_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/contextmgmt' ? 'active' : ''}}" href="{{ $context_auth || $enable_auth ? url('/user/contextmgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Issue Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $context_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/context/interestparty' ? 'active' : ''}}" href="{{ $context_auth || $enable_auth ? url('/user/context/interestparty') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Interested Party Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $context_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/context/changelogreg' ? 'active' : ''}}" href="{{ $context_auth || $enable_auth ? url('/user/context/changelogreg') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Change Log</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->customer_satisfaction == 1)
                        <li class="nav-main-item {{strpos($urlPath,'user/customer')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="#" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fab fa-speakap" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Customer Satisfaction</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $customer_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/customermgmt' ? 'active' : ''}}" href="{{ $customer_auth || $enable_auth ? route('customer.register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Customers List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $customer_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="#">

                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Survey Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->complaintmanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/complaint')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/complaintmgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-question" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Customer Complaint</span>
                            </a>
                            <ul class="nav-main-submenu">

                                <li class="nav-main-item">
                                    <a class="{{ $complaint_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/complaintmgmt' ? 'active' : ''}}" href="{{ $complaint_auth || $enable_auth ? url('/user/complaintmgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Complaint Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $complaint_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/complaint/list' ? 'active' : ''}}" href="{{ $complaint_auth || $enable_auth ? url('/user/complaint/list') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Customer List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $complaint_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/complaint/my' ? 'active' : ''}}" href="{{ $complaint_auth || $enable_auth ? url('/user/complaint/my') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">My Complaints</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $complaint_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/complaint/closedc' ? 'active' : ''}}" href="{{ $complaint_auth || $enable_auth ? url('/user/complaint/closedc') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed Complaints</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->vendormanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/vendor')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/vendor/register') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-2x fa-people-carry" style="font-size:14px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Vendor Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $vendor_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/vendor/register' ? 'active' : ''}}" href="{{ $vendor_auth || $enable_auth ? url('/user/vendor/register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Vendor Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $vendor_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/vendor/analysis' ? 'active' : ''}}" href="{{ $vendor_analysis_auth || $enable_auth ? route('vendor.analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Vendor Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if($model->incidentreport==1)
                        <li class="nav-main-item {{strpos($urlPath,'incident')!==false || strpos($urlPath,'/user/report/add')!==false || strpos($urlPath,'/user/review/approve/investigation')!==false ?'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/incidents/register') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class='nav-main-link-icon fas fa-car-crash'></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Incident Report</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $incident_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/incidents/register' ? 'active' : ''}}" href="{{ $incident_auth || $enable_auth ? url('/user/incidents/register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Incident Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $incident_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/incidents/closed/incidents' ? 'active' : ''}}" href="{{ $incident_auth || $enable_auth ? url('/user/incidents/closed/incidents') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed Incident</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $incident_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/incident/correactions' ? 'active' : ''}}" href="{{ $incident_auth || $enable_auth ? url('/user/incident/correactions') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Open CAs</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $incident_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/incident/closedcorrections' ? 'active' : ''}}" href="{{ $incident_auth || $enable_auth ? url('/user/incident/closedcorrections') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed CAs</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->hse==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/hseobservation')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/hseobservation/issues') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-fire-extinguisher" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">HSE Observations</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $hse_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/hseobservation/issues' ? 'active' : ''}}" href="{{ $hse_auth || $enable_auth ? url('/user/hseobservation/issues') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Open Observations</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $hse_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/hseobservation/closedissue' ? 'active' : ''}}" href="{{ $hse_auth || $enable_auth ? url('user/hseobservation/closedissue') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Closed Observations</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $hse_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/hseobservation/openissue' ? 'active' : ''}}" href="{{ $hse_auth || $enable_auth ? url('/user/hseobservation/openissue') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">My Open Observations</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $hse_analysis_page || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/hseobservation/analysis' ? 'active' : ''}}" href="{{ $hse_analysis_page || $enable_auth ? url('/user/hseobservation/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->emergencyresponse==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/emergencyresponse')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ route('emergency.register') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-2x fa-blog" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Emergency Response</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $emergency_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $emergency_auth || $enable_auth ? route('emergency.register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Emergency Respose Register</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->nearmiss==1)
                        <li class="nav-main-item {{strpos($routeName,'nearmiss')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ route('nearmiss.index') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-running"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Near Miss</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'nearmiss.index')===0)
                                    <a class="{{ $nearmiss_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $nearmiss_auth || $enable_auth ? route('nearmiss.index') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $nearmiss_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $nearmiss_auth || $enable_auth ? route('nearmiss.index') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Open Near Misses</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'nearmiss.closedissue')===0)
                                    <a class="{{ $nearmiss_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $nearmiss_auth || $enable_auth ? route('nearmiss.closedissue') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $nearmiss_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $nearmiss_auth || $enable_auth ? route('nearmiss.closedissue') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Closed Near Misses</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'nearmiss.openissue')===0)
                                    <a class="{{ $nearmiss_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $nearmiss_auth || $enable_auth ? route('nearmiss.openissue') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white"></i>
                                        @else
                                        <a class="{{ $nearmiss_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $nearmiss_auth || $enable_auth ? route('nearmiss.openissue') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">My Open Near Misses</span>
                                        </a>
                                </li>
                                <li class="nav-main-item">
                                    @if(strpos(\Request::route()->getName(), 'nearmiss.analysis')===0)
                                    <a class="{{ $nearmiss_analysis_auth || $enable_auth ? 'nav-main-link link-active' : 'nav-main-link link-active disabled-color cursor-none'}}" href="{{ $nearmiss_analysis_auth || $enable_auth ? route('nearmiss.analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;color:white;"></i>
                                        @else
                                        <a class="{{ $nearmiss_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}}" href="{{ $nearmiss_analysis_auth || $enable_auth ? route('nearmiss.analysis') : '#' }}">


                                            <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                            @endif
                                            <span class="nav-main-link-name">Analysis</span>
                                        </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->maintainance==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/maintenance')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/maintenancemgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-wrench" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Maintenance Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $maintenance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/maintenancemgmt' ? 'active' : ''}}" href="{{ $maintenance_auth || $enable_auth ? url('/user/maintenancemgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Total Equipment List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $maintenance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/maintenance/pmfolder' ? 'active' : ''}}" href="{{ $maintenance_auth || $enable_auth ? url('/user/maintenance/pmfolder') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">PM Plan and Report</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $maintenance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/maintenance/testfolder' ? 'active' : ''}}" href="{{ $maintenance_auth || $enable_auth ? url('/user/maintenance/testfolder') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Test Plan</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $maintenance_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/maintenance/breakfolder' ? 'active' : ''}}" href="{{ $maintenance_auth || $enable_auth ? url('/user/maintenance/breakfolder') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Breakdown Maintenance</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->calibration==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/calibration')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/calibrationmgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-balance-scale"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Calibration Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $calibration_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/calibrationmgmt' ? 'active' : ''}}" href="{{ $calibration_auth || $enable_auth ? url('/user/calibrationmgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Register</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $calibration_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/calibration/under-list' ? 'active' : ''}}"
                                       href="{{ $calibration_auth || $enable_auth ? url('/user/calibration/under-list') : '#' }}">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Under calibration</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="{{ $calibration_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/calibration/analysis' ? 'active' : ''}}" href="{{ $calibration_analysis_auth || $enable_auth ? url('/user/calibration/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if($model->hr==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/hr')!==false || strpos($urlPath,'/user/employeelist')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/hrjmgmt') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-desktop"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Human Resource</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $hr_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/hrjmgmt' ? 'active' : ''}}" href="{{ $hr_auth || $enable_auth ? url('/user/hrjmgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Job Description</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="{{ $hr_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/hrtmgmt' ? 'active' : ''}}" href="{{ $hr_auth || $enable_auth ? url('/user/hrtmgmt') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Training Schedule</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $hr_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/employeelist' ? 'active' : ''}}" href="{{ $hr_auth || $enable_auth ? url('/user/employeelist') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Employee List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if($model->occhealth==1)
                        <li class="nav-main-item {{strpos($urlPath,'health')!==false || strpos($urlPath,'injury')!==false || strpos($urlPath,'illness')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/occupationhealth/register') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class='nav-main-link-icon fa fa-clinic-medical'></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Occupational Health</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $occhealth_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/occupationhealth/register' ? 'active' : ''}} " href="{{ $occhealth_auth || $enable_auth ? url('/user/occupationhealth/register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Employee Health Record</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $occhealth_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/occupationhealth/injuryrecords' ? 'active' : ''}}" href="{{ $occhealth_auth || $enable_auth ? url('/user/occupationhealth/injuryrecords') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Employee Injury Record</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $occhealth_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/occupationhealth/illnessrecords' ? 'active' : ''}}" href="{{ $occhealth_auth || $enable_auth ? url('/user/occupationhealth/illnessrecords') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Employee Illness Record</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $occhealth_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/occupationhealth/othercases' ? 'active' : ''}}" href="{{ $occhealth_auth || $enable_auth ? url('/user/occupationhealth/othercases') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Add Other Cases</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $occhealth_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/occupationhealth/analysis' ? 'active' : ''}}" href="{{ $occhealth_analysis_auth || $enable_auth ? url('/user/occupationhealth/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->wastemanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'user/waste')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="{{ url('/user/waste/register') }}" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class='nav-main-link-icon fa fa-recycle'></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Waste Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $waste_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/waste/register' ? 'active' : ''}}" href="{{ $waste_auth || $enable_auth ? url('/user/waste/register') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Waste / Scrap List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $waste_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/waste/transferrequest' ? 'active' : ''}}" href="{{ $waste_auth || $enable_auth ? url('/user/waste/transferrequest') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Transfer Request</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="{{ $waste_analysis_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/waste/analysis' ? 'active' : ''}}" href="{{ $waste_analysis_auth || $enable_auth ? url('/user/waste/analysis') : '#' }}">


                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Analysis</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if($model->elearning == 1)
                        <li class="nav-main-item {{strpos($urlPath,'user/elearning')!==false ? 'open':''}}">
                            <a class="nav-main-link nav-main-link-submenu" href="#" data-toggle="submenu" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fas fa-graduation-cap" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">e-Learning</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="{{ $elearning_auth || $enable_auth ? 'nav-main-link' : 'nav-main-link disabled-color cursor-none'}} {{$urlPath == '/user/elearning/courses' ? 'active' : ''}}" href="{{ $elearning_auth || $enable_auth ? route('elearning.courses') : '#' }}">

                                        <i class="nav-main-link-icon fa fa-book" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Courses</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif



                        {{-- </ul> --}}
                        </li>
                        </li>
                    </ul>
                    </li>
                    </ul>
                    </li>
                    </ul>
                    </li>
                    </ul>
                    </li>

                    </ul>
                </div>
                <!-- END Side Navigation -->
            </div>
            <!-- END Sidebar Scrolling -->
        </nav>
        <!-- END Sidebar -->

        <!-- Header -->
        <header id="page-header">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">
                    <!-- Toggle Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <!-- END Toggle Sidebar -->

                    <!-- <button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
                        <a href="/user/auditmgmt"><i class="fa fa-arrow-left" style="color:black;"></i></a>
                    </button> -->

                    <!-- Toggle Mini Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>
                    <!-- END Toggle Mini Sidebar -->

                    <!-- Apps Modal -->
                    <!-- Opens the Apps modal found at the bottom of the page, after footer’s markup -->
                    <button type="button" class="btn btn-sm btn-dual mr-2" data-toggle="modal" data-target="#one-modal-apps">
                        <i class="fa fa-fw fa-cubes"></i>
                    </button>
                    <!-- END Apps Modal -->

                    <!-- Open Search Section (visible on smaller screens) -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-sm btn-dual d-sm-none" data-toggle="layout" data-action="header_search_on">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                    <!-- END Open Search Section -->

                    <!-- Search Form (visible on larger screens) -->
                    <!-- <form class="d-none d-sm-inline-block" action="/dashboard" method="POST">
                            @csrf
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="page-header-search-input2">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-body border-0">
                                        <i class="fa fa-fw fa-search"></i>
                                    </span>
                                </div>
                            </div>
                        </form> -->
                    <!-- END Search Form -->
                </div>
                <!-- END Left Section -->
                @php
                    $user_details= App\User::where('email',Auth::user()->email)->first();
                    $profilePicture =  $user_details ? $user_details->profile_picture : '';
                @endphp
                <!-- Right Section -->
                <div class="d-flex align-items-center">
                    <!-- User Dropdown -->
                    @include('shared.forms.theme-control')
                    <div class="dropdown d-inline-block ml-2">
                        <button type="button" class="btn btn-sm btn-dual d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if($profilePicture != null)
                            <img class="rounded-circle" src="/storage/profile/{{ $profilePicture }}" alt="" style="width: 21px;">
                            @else
                            <img class="rounded-circle" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="" style="width: 21px;">
                            @endif
                            <span class="d-none d-sm-inline-block ml-2">{{ Auth::user()->name }}</span>
                            <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0" aria-labelledby="page-header-user-dropdown">
                            <div class="p-3 text-center bg-primary-dark rounded-top">
                                @if($profilePicture != null)
                                <img class="img-avatar img-avatar48 img-avatar-thumb" src="/storage/profile/{{ $profilePicture }}" alt="">
                                @else
                                <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                                @endif <p class="mt-2 mb-0 text-white font-w500">{{ Auth::user()->name }}</p>
                                <p class="mb-0 text-white-50 font-size-sm">User</p>
                            </div>
                            <div class="p-2">

                                {{-- <div role="separator" class="dropdown-divider"></div> --}}

                                <!-- <div role="separator" class="dropdown-divider"></div> -->
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('profile.edit', ['g' => 'user']) }}">
                                    <span class="font-size-sm font-w500" style="color: #000;">My Profile</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('profile.passwordAndSecurity', ['g' => 'user']) }}">
                                    <span class="font-size-sm font-w500" style="color: #000;">Password & Security</span> {!!getFlashNewTag('user_2fa')!!}
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('user.logout') }}">
                                    <span class="font-size-sm font-w500" style="color: #000;">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- END User Dropdown -->
                    @yield('notification')

                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

            <!-- Header Search -->
            <div id="page-header-search" class="overlay-header bg-white">
                <div class="content-header">
                    <form class="w-100" action="/dashboard" method="POST">
                        @csrf
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                <button type="button" class="btn btn-alt-danger" data-toggle="layout" data-action="header_search_off">
                                    <i class="fa fa-fw fa-times-circle"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Header Search -->

            <!-- Header Loader -->
            <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
            <div id="page-header-loader" class="overlay-header bg-white">
                <div class="content-header">
                    <div class="w-100 text-center">
                        <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                    </div>
                </div>
            </div>
            <!-- END Header Loader -->
        </header>
        <!-- END Header -->

        <!-- Main Container -->

        <!-- Hero -->
        <main id="main-container">
            @yield('content')

        </main>

        <footer id="page-footer" class="bg-white">
            <div class="content py-3">
                <div class="row font-size-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                        Powered by <a class="font-w600" href="#" target="_blank">{{ config('app.name') }}</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                        @if(Session::get('app_name')=='quickms_logo.png')
                        <a class="font-w600" href="#" target="_blank">{{Session::get('appname_to_display')}} MS {{ config('app.ver') }}</a> &copy; <span data-toggle="year-copy"></span>
                        @elseif(Session::get('app_name')=='quickcg_logo.png')
                        <a class="font-w600" href="#" target="_blank">{{Session::get('appname_to_display')}} CG {{ config('app.ver') }}</a> &copy; <span data-toggle="year-copy"></span>
                        @else
                        <a class="font-w600" href="#" target="_blank">{{ config('app.name') }} {{ config('app.ver') }}</a> &copy; <span data-toggle="year-copy"></span>
                        @endif
                    </div>
                </div>
            </div>
        </footer>
        @if (Auth::user())
        <script>
            let lifetime = "{{ config('session.lifetime') }}";
            $(function() {
                setInterval(function checkSession() {
                    $.get('/check-session', function(data) {
                        // if session was expired
                        if (data.guest) {
                            // redirect to login page
                            // location.assign('/auth/login');

                            // or, may be better, just reload page
                            location.reload();
                        }
                    });
                }, lifetime * 60000); // session_lifetime * every minute
            });

        </script>
        @endif


        <script src="{{asset('js/oneui.core.min.js')}}"></script>
        <script src="{{asset('js/oneui.app.min.js')}}"></script>
        {{-- <script src="{{ asset('js/oneui.app.min-5.6.js') }}"></script> --}}

        <script src="{{asset('js/jquery.mask.js')}}"></script>
        {{-- <script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script> --}}

        <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
        <script>
            jQuery(function() {
                One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
                //One.helpersOnLoad(['js-ckeditor5']);
            });

        </script>
        @yield('js')


</body>

</html>
