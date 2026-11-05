<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <style>
        .select2-results__options,
        .select2 {
            font-size: 13px;
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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


    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:image" content="{{ asset('media/favicons/Setilas_logo.jfif') }}">
    <meta property="og:url" content="{{config('app.url')}}">
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">

    <!-- Dropezone plugins css -->
    <link rel="stylesheet" href="{{ asset('js/plugins/dropzone/dist/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="/css/sweetalert/sweetalert2.min.css">

    <!-- Editor css -->
    <link rel="stylesheet" href="{{ asset('js/plugins/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{ asset('js/plugins/simplemde/simplemde.min.css') }}">
    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">
    <link rel="stylesheet" href="/css/sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/flash_new_tag.css')}}">


    @yield('css_after')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
    <script src="/js/sweetalert/sweetalert2.all.min.js"></script>

    @include('shared.theme.assets')
</head>

<body>

    <div id="page-container" class="sidebar-o sidebar-mini enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->

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
                        {{Session::get('appname_to_display')}}<span class="font-w400"> MS</span>
                        @elseif(Session::get('app_name')=='quickcg_logo.png')
                        {{Session::get('appname_to_display')}}<span class="font-w400"> CG</span>
                        @else
                        {{ config('app.name') }}
                        {{-- Quick<span class="font-w400">QM</span> --}}
                        @endif
                    </span>
                </a>
                <!-- END Logo -->

                <!-- Extra -->
                <div>
                    <!-- Options -->
                    <div class="dropdown d-inline-block ml-2">
                        <a class="btn btn-sm btn-dual" id="sidebar-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                            <!--<i class="si si-drop"></i>-->
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
            @php
                $currentUrl = url()->current();
                $urlPath = parse_url($currentUrl, PHP_URL_PATH);
                $routeName = \Request::route()->getName();
            @endphp
            <div class="js-sidebar-scroll">
                <!-- Side Navigation -->
                <div class="content-side">
                    <ul class="nav-main">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/user/home" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!--<i class="nav-main-link-icon si si-home"></i>-->
                                <i class="nav-main-link-icon fa fa-home" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Home</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/admin/home' ? 'active' : ''}}" href="/admin/home" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon si si-settings fa-2x" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Admin Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ route('admin.logout') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!--<i class="nav-main-link-icon si si-logout"></i>-->
                                <i class='nav-main-link-icon fas fa-sign-out-alt' style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Logout</span>
                            </a>
                        </li>


                        <li class="nav-main-heading" style="background-color:#191e3b;">Admin Interface</li>


                        {{-- <li class="nav-main-item"> --}}


                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath == '/admin/viewAdmins' ? 'active' : ''}}" href="{{ url('/admin/viewAdmins') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-2x fa-user-shield" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Admins</span>
                            </a>
                        </li>
                        <li class="nav-main-item {{$urlPath=='/user/approval' || strpos($urlPath,'/showResponsibilities')!==false || strpos($urlPath,'admin/displayeditUser')!==false || strpos($urlPath,'admin/manageRoles')!==false || strpos($urlPath,'admin/viewUsers')!==false || strpos($urlPath,'admin/enable-disable-module')!==false ? 'open' : ''}}">

                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                                <i class="nav-main-link-icon fa fa-2x fa-user" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">User Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/user/approval' ? 'active' : ''}}" href="/user/approval" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">User Approval</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/viewUsers' ? 'active' : ''}}" href="/admin/viewUsers" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Users</span>
                                    </a>
                                </li>
                            </ul>

                        </li>

                        {{-- <li class="nav-main-item">

                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                                <i class="nav-main-link-icon fa fa-2x fa-user" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Mock sidemenu</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="/user/approval" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-user"></i>-->
                                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">User Approval</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="/admin/viewUsers" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-users"></i>-->
                                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Users</span>
                                    </a>
                                </li>
                            </ul>

                        </li> --}}



                        <li class="nav-main-item {{strpos($urlPath,'Department')!==false || strpos($urlPath,'admin/addhodassistants')!==false || strpos($urlPath,'admin/standards')!==false || strpos($urlPath,'admin/viewPlants')!==false || strpos($urlPath,'product')!==false || strpos($urlPath,'/admin/viewclientprofile')!==false ? 'open' : ''}}">
                            {{-- <a class="nav-main-link" href="{{ url('/admin/viewDepartments') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!--<i class="nav-main-link-icon fa fa-2x fa-bezier-curve" style="font-size:15px;"></i>-->
                            <i class="nav-main-link-icon fa fa-landmark" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Departments</span>
                            </a> --}}



                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                                <i class="nav-main-link-icon fas fa-sitemap" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Organization</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/viewDepartments' ? 'active' : ''}}" href="/admin/viewDepartments" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-user"></i>-->
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Departments</span>
                                    </a>
                                </li>
                                <!-- <li class="nav-main-item">
                                    <a class="nav-main-link" href="/admin/departmentDivisions" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Divisions</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="/admin/departmentAreas" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Sub Divisions</span>
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="/admin/locations" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Locations</span>
                                    </a>
                                </li> -->

                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/standards' ? 'active' : ''}}" href="/admin/standards" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Standards</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/viewPlants' ? 'active' : ''}}" href="/admin/viewPlants" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Plants</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/viewproduct' ? 'active' : ''}}" href="/admin/viewproduct" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Products / Services</span>
                                    </a>
                                </li>

                                <!-- <li class="nav-main-item">
                                    <a class="nav-main-link" href="" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Reports</span>
                                    </a>
                                </li> -->

                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/viewclientprofile' ? 'active' : ''}}" href="{{route('admin.client.profile')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Profile</span>
                                    </a>
                                </li>

                            </ul>

                        </li>
                        <li class="nav-main-item {{$urlPath=='/admin/security/MFA' ? 'open' : ''}}">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fas fa-shield-alt" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Security</span>{!!getFlashNewTag('mfa')!!}
                            </a>
                            <ul class="nav-main-submenu">
                                <li>
                                    <a class="nav-main-link {{$urlPath=='/admin/security/MFA' ? 'active' : ''}}" href="{{route('admin.security.mfa')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-user"></i>-->
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Multi-Factor Authentication</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @php
                            $enable = DB::table('customfield_enadisable')->first();
                            $enable = $enable ? $enable->is_enable : false;
                        @endphp
                        @if($enable)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{strpos($urlPath,'admin/custom_field/custom-field-configuration')!==false ? 'active' : ''}}" href="{{ route('admin.custom_field.custom_field_configuration') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-newspaper" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Custom Field Configuration</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-main-item {{$urlPath=='/admin/dynamic/audit/reports' ? 'open' : ''}}">
                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon far fa-2x fa-list-alt" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Customized Reports</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/dynamic/audit/reports' ? 'active' : ''}}" href="{{route('admin.audit.dynamicreports')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Audit Management</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-main-item {{strpos($urlPath,'employee')!==false || strpos($urlPath,'admin/multipleempaction')!==false ? 'open' : ''}}">

                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                                <i class="nav-main-link-icon fas fa-users" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Employees</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/employeelist' ? 'active' : ''}}" href="/admin/employeelist" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-user"></i>-->
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Employee Details</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/addemployees' ? 'active' : ''}}" href="{{ url('/admin/addemployees') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-users"></i>-->
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Add Employees</span>
                                    </a>
                                </li>
                            </ul>

                        </li>

                        <li class="nav-main-item">
                            <a class="nav-main-link {{strpos($urlPath,'emailnotification')!==false ? 'active' : ''}}" href="{{ route('admin.emailnotificationlist') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon far fa-bell" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Email Notification List</span>
                            </a>
                        </li>

                        <li class="nav-main-item {{strpos($urlPath,'/admin/system-management/backup')!==false ? 'open' : ''}}">

                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                                <i class="nav-main-link-icon fas fa-server" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">System Management</span>{!!getFlashNewTag('mfa')!!}
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/system-management/backup' ? 'active' : ''}}" href="/admin/system-management/backup" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <!--<i class="nav-main-link-icon si si-user"></i>-->
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Backup</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!--
                                    <li class="nav-main-item">

                                       <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">

                                           <i class="nav-main-link-icon fa fa-balance-scale" style="font-size:15px;"></i>
                                           <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Calibration Management</span>
                                       </a>
                                       <ul class="nav-main-submenu">
                                           <li class="nav-main-item">
                                               <a class="nav-main-link" href="/admin/calibrationlist" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">

                                                   <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Calibration Master List</span>
                                               </a>
                                           </li>
                                           <li class="nav-main-item">
                                               <a class="nav-main-link" href="{{ url('/admin/addcalibration') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">

                                                   <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Add Calibration Master List</span>
                                               </a>
                                           </li>
                                           <li class="nav-main-item">
                                              <a class="nav-main-link" href="{{ url('/admin/viewfreq') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">

                                               <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'"> Add Frequency</span>
                                              </a>
                                           </li>


                                        </ul>

                                   </li>

            -->




                        @if($model->auditmanagement==1)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{strpos($urlPath,'/admin/viewCategories')!==false || strpos($urlPath,'admin/editType')!==false || strpos($urlPath,'/admin/editisoclauses')!==false || strpos($urlPath,'conformance')!==false || strpos($urlPath,'/admin/addrootcorrective')!==false || strpos($urlPath,'/admin/editrootcorrective')!==false ? 'active' : ''}}" href="{{ url('/admin/viewCategories') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon far fa-edit" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.01 Audit Management</span>
                            </a>
                        </li>
                        @endif
                        @if($model->riskmanagement==1)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{strpos($urlPath,'/admin/riskinfo')!==false ? 'active' : ''}}" href="{{url('/admin/riskinfo')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!--<i class="nav-main-link-icon si si-exclamation"></i>-->
                                <i class="nav-main-link-icon fas fa-exclamation-triangle" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.02 Enterprise Risk</span>
                            </a>
                        </li>
                        @endif
                        @if($model->compliance==1)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath=='/admin/compliance/index' ? 'active' : ''}}"  href="/admin/compliance/index" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-gavel" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.03 Compliance Management</span>
                            </a>
                        </li>
                        @endif
                        @if($model->documentmanagement==1)
                        <li class="nav-main-item {{strpos($urlPath,'document')!==false || strpos($urlPath,'doctype')!==false ? 'open' : ''}}">

                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-file-upload" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.04 Document Management</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/documentmasterlist' ? 'active' : ''}}" href="{{ url('/admin/documentmasterlist') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Document Master List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/adddocumentmaster' ? 'active' : ''}}" href="{{ url('/admin/adddocumentmaster') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">Add Document Master List</span>
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{$urlPath=='/admin/doctypes' ? 'active' : ''}}" href="{{ url('/admin/doctypes') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name"> Add Types</span>
                                    </a>
                                </li>


                            </ul>

                        </li>

                        @endif
                        @if($model->correctiveaction==1)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{$urlPath=='/admin/viewTypes' || strpos($urlPath,'admin/editCategory')!==false || strpos($urlPath,'editTypeCorAction')!==false || strpos($urlPath,'admin/addcorrectiverootcorrective')!==false || strpos($urlPath,'admin/editcorrectiverootcorrective')!==false ? 'active' : ''}}" href="{{ url('/admin/viewTypes') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!--<i class="nav-main-link-icon si si-check"></i>-->
                                <i class="nav-main-link-icon fa fa-exclamation-circle" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.05 NCR & Corrective Actions</span>
                            </a>
                        </li>
                        @endif
                        @if($model->ohsrisk==1)
                        <li class="nav-main-item">
                            <a class="nav-main-link {{strpos($urlPath,'/admin/ohsinfo')!==false ? 'active' : ''}}" href="{{ url('/admin/ohsinfo') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <!--<i class='nav-main-link-icon fas fa-clinic-medical'></i>-->
                                <i class='nav-main-link-icon fa fa-clinic-medical' style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.06 Health and Safety Risk</span>
                            </a>
                        </li>



                        {{-- <li class="nav-main-item">

                            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <i class="nav-main-link-icon fa fa-file-upload" style="font-size:15px;"></i>
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.05 Health and Safety Risk</span>
                            </a>
                            <ul class="nav-main-submenu">
                                <li class="nav-main-item">
                                    <a class="nav-main-link" href="{{ url('/admin/ohsinfo') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                        <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Ohs Info</span>
                        </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="{{ url('/admin/ohsinfo/locations') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">Locations</span>
                            </a>
                        </li>

                    </ul>

                    </li> --}}

                    @endif
                    @if($model->enviaspects==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{strpos($urlPath,'/admin/enviinfo')!==false ? 'active' : ''}}" href="{{ url('/admin/enviinfo') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!--<i class="nav-main-link-icon si si-globe"></i>-->
                            <i class="nav-main-link-icon fa fa-leaf" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.07 Environmental Aspect</span>
                        </a>
                    </li>
                    @endif
                    @if($model->contextmanagement==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{strpos($urlPath,'viewPurpose')!==false || strpos($urlPath,'editPurpose')!==false || strpos($urlPath,'editcat')!==false || strpos($urlPath,'editSeverity')!==false ? 'active' : ''}}" href="{{ url('/admin/viewPurpose') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!--<i class="nav-main-link-icon si si-settings"></i>-->
                            <i class="nav-main-link-icon fab fa-contao" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.08 Context Management</span>
                        </a>
                    </li>
                    @endif
                    @if($model->customer_satisfaction == 1)
                    <li class="nav-main-item {{strpos($urlPath,'admin/customer-satisfaction')!==false ? 'open' : ''}}">

                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <i class="nav-main-link-icon fa fa-file-upload" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.09 Customer Satisfaction</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/customer-satisfaction/evaluationDetails' ? 'active' : ''}}" href="{{ route('admin.customer.evaluationDetails') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Evaluation Details</span>
                                </a>
                            </li>
                        </ul>

                    </li>

                    @endif

                    @if($model->complaintmanagement==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!--<i class="nav-main-link-icon si si-question"></i>-->
                            <i class="nav-main-link-icon fa fa-question" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.10 Customer Complaint</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/complaint/complaint-form-configuration' ? 'active' : ''}}" href="{{ route('admin.complaint.complaintFormConfiguration') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Complaint Form Configuration</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{strpos($urlPath,'complaint/category')!==false ? 'active' : ''}}" href="{{ route('admin.complaint.category') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Complaint Categories</span>
                                </a>
                            </li>
                            @if (App\Helpers\Complaint\ComplaintFormConfigurationHelper::getActiveComplaintTemplate()->id == 2 && App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel())
                                <li class="nav-main-item">
                                    <a class="nav-main-link {{strpos($urlPath,'complaint/source-of-issue')!==false ? 'active' : ''}}" href="{{ route('admin.complaint.source-of-issue.index') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                        <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                        <span class="nav-main-link-name">{{App\Helpers\Complaint\ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel()}}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-main-item">
                                <a class="nav-main-link {{strpos($urlPath,'complaint/unit')!==false ? 'active' : ''}}" href="{{ route('admin.complaint.unit') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Complaint Units</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @if($model->vendormanagement==1)

                    <li class="nav-main-item {{strpos($urlPath,'Qstns')!==false || strpos($urlPath,'vendorEvaluation')!==false || strpos($urlPath,'admin/editres')!==false ? 'open' : ''}}">

                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <i class="nav-main-link-icon fa fa-file-upload" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.11 Vendor Management</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/vendorEvaluationDetails' ? 'active' : ''}}" href="{{ url('/admin/vendorEvaluationDetails') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Evaluation Details</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/vendorEvaluationResult' ? 'active' : ''}}" href="{{ url('/admin/vendorEvaluationResult') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Action Comment</span>
                                </a>
                            </li>
                        </ul>

                    </li>

                    @endif
                    @if($model->incidentreport==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{strpos($urlPath,'incidentelements')!==false ? 'active' : ''}}" href="{{ url('/admin/addupdate/incidentelements') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!--<i class='nav-main-link-icon fas fa-car-crash'></i>-->
                            <i class='nav-main-link-icon fas fa-car-crash' style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.12 Incident Report</span>
                        </a>
                    </li>
                    @endif
                    @if($model->hse==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{strpos($urlPath,'hse')!==false ? 'active' : ''}}" href="{{ url('/admin/viewhseCategories') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!--<i class="nav-main-link-icon si si-magnifier"></i>-->
                            <i class="nav-main-link-icon fa fa-fire-extinguisher" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.13 HSE Observations</span>
                        </a>
                    </li>
                    @endif
                    <!--
                                    @if($model->calibration==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/admin/viewfreq') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                            <i class="nav-main-link-icon fa fa-balance-scale"></i>
                                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.12 Calibration Management</span>
                                        </a>
                                    </li>
                                    @endif -->
                    @if($model->calibration==1)
                    <li class="nav-main-item {{strpos($urlPath,'admin/multiplecalaction')!==false || strpos($urlPath,'calibration')!==false || strpos($urlPath,'admin/viewfreq')!==false  ? 'open' : ''}}">

                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                            <i class="nav-main-link-icon fa fa-balance-scale" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.14 Calibration Management</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/calibration/calibration-field-configuration' ? 'active' : ''}}" href="/admin/calibration/calibration-field-configuration" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-user"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Calibration Field Configuration</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/calibrationlist' ? 'active' : ''}}" href="/admin/calibrationlist" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-user"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Calibration Master List</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/addcalibration' ? 'active' : ''}}" href="{{ url('/admin/addcalibration') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-users"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Add Calibration Master List</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/viewfreq' ? 'active' : ''}}" href="{{ url('/admin/viewfreq') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon fa fa-balance-scale"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name"> Add Frequency</span>
                                </a>
                            </li>


                        </ul>

                    </li>

                    @endif

                    @if($model->occhealth==1)
                    <!--<li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/admin/employeedetails') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                            <i class='nav-main-link-icon fa fa-clinic-medical'></i>
                                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.13 Occupational Health</span>
                                        </a>
                                    </li>-->
                    <li class="nav-main-item {{strpos($urlPath,'/health')!==false  ? 'open' : ''}}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <i class="nav-main-link-icon fa fa-clinic-medical" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.15 Occupational Health</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/health/category' ? 'active' : ''}}" href="{{ url('/admin/health/category') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Category</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/health/treatment' ? 'active' : ''}}" href="{{ url('/admin/health/treatment') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Treatments</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/health/injury' ? 'active' : ''}}" href="{{ url('/admin/health/injury') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Injury</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/health/illness' ? 'active' : ''}}" href="{{ url('/admin/health/illness') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name"> Illness</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/health/injurynature' ? 'active' : ''}}" href="{{ url('/admin/health/injurynature') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name"> Nature of Injury</span>
                                </a>
                            </li>

                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/health/illnessnature' ? 'active' : ''}}" href="{{ url('/admin/health/illnessnature') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name"> Nature of Illness</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if($model->wastemanagement==1)
                    {{-- <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/admin/wastedesc') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                    <i class="nav-main-link-icon fa fa-recycle" style="font-size:15px;"></i>
                    <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.14 Waste Management</span>
                    </a>
                    </li> --}}
                    <li class="nav-main-item {{strpos($urlPath,'waste')!==false  ? 'open' : ''}}">

                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <!-- <i class="nav-main-link-icon si si-bag"></i> -->
                            <i class="nav-main-link-icon fa fa-recycle" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.16 Waste Management</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/wastetypes' ? 'active' : ''}}" href="{{ route('admin.waste.showWasteType') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-users"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Waste Types</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/wastedesc' ? 'active' : ''}}" href="{{route('admin.waste.wastedesc')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-user"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Waste Descriptions</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/wastetargets' ? 'active' : ''}}" href="{{route('admin.waste.showWasteTarget')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-user"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Waste Targets</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/wasteunits' ? 'active' : ''}}" href="{{route('admin.waste.showWasteUnit')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-user"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Waste Units</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/wastedisposalmethods' ? 'active' : ''}}" href="{{route('admin.waste.showWasteDisposalMethod')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <!--<i class="nav-main-link-icon si si-user"></i>-->
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Waste Disposal Methods</span>
                                </a>
                            </li>
                        </ul>

                    </li>
                    @endif
                    @if($model->hr==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{strpos($urlPath,'/admin/addjobcode')!==false ? 'active' : ''}}" href="{{ url('/admin/addjobcode') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <i class="nav-main-link-icon fa fa-desktop" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.17 Human Resource</span>
                        </a>
                    </li>
                    @endif
                    @if($model->nearmiss==1)
                    <li class="nav-main-item {{strpos($urlPath,'nearmiss')!==false ? 'open' : ''}}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <i class="nav-main-link-icon fa fa-running" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.18 Near Miss</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/viewnearmissTypes' ? 'active' : ''}}" href="{{ url('/admin/viewnearmissTypes') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Types</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/nearmiss/qr' ? 'active' : ''}}" href="{{ url('/admin/nearmiss/qr') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">QR Code Management</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if($model->opportunity==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{strpos($urlPath,'opportunity')!==false ? 'active' : ''}}" href="{{ url('/admin/viewopportunitytypes') }}">
                            <i class="nav-main-link-icon fa fa-briefcase" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.19 Opportunity</span>
                        </a>
                    </li>
                    @endif
                    @if($model->objectives==1)
                    <li class="nav-main-item">
                        <a class="nav-main-link {{$urlPath == '/admin/viewobjectivetypes' || strpos($urlPath,'admin/objective')!==false ? 'active' : ''}}" href="{{ url('/admin/viewobjectivetypes') }}">
                            <i class="nav-main-link-icon fa fa-briefcase" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.20 Objective</span>
                        </a>
                    </li>
                    @endif

                    @if($model->managementreview==1)
                    <li class="nav-main-item {{strpos($urlPath,'admin/management')!==false  ? 'open' : ''}}">
                        <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                            <i class="nav-main-link-icon fa fa-2x fa-users" style="font-size:15px;"></i>
                            <span class="nav-main-link-name" style="color:#888EA8;" onMouseOver="this.style.color='#25E5D4'" onMouseOut="this.style.color='#888EA8'">MS.21 Management Review</span>
                        </a>
                        <ul class="nav-main-submenu">
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/management/agendatypes' ? 'active' : ''}}" href="{{route('management.agendatypes')}}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Agenda Type</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/management/agenda' ? 'active' : ''}}" href="{{ route('management.agenda') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Agenda</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link {{$urlPath=='/admin/management/location' ? 'active' : ''}}" href="{{ route('management.location') }}" onMouseOver="this.style.backgroundColor='#212643'" onMouseOut="this.style.backgroundColor='#191e3b'">
                                    <i class="nav-main-link-icon fa fa-circle" style="font-size:10px;"></i>
                                    <span class="nav-main-link-name">Location</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif

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

                    <!-- Toggle Mini Sidebar -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                    <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
                        <i class="fa fa-fw fa-ellipsis-v"></i>
                    </button>

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
                                @endif
                                <p class="mt-2 mb-0 text-white font-w500">{{ Auth::user()->name }}</p>

                                <p class="mb-0 text-white-50 font-size-sm">Admin</p>

                            </div>
                            <div class="p-2">
                                {{-- <a class="dropdown-item d-flex align-items-center justify-content-between"
                                   style="color: #343a40"
                                   href="/admin/home">
                                    <span class="font-size-sm font-w500">Home</span>
                                    <!-- <span class="badge badge-pill badge-primary ml-2">3</span> -->
                                </a> --}}

                                <!-- <div role="separator" class="dropdown-divider"></div> -->
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                   style="color: #343a40"
                                   href="{{ route('profile.edit', ['g' => 'admin']) }}">
                                    <span class="font-size-sm font-w500">My Profile</span>
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                   style="color: #343a40"
                                   href="{{route('profile.passwordAndSecurity', ['g' => 'admin'])}}">
                                    <span class="font-size-sm font-w500">Password & Security</span>{!!getFlashNewTag('user_2fa')!!}
                                </a>
                                <a class="dropdown-item d-flex align-items-center justify-content-between"
                                   style="color: #343a40"
                                   href="{{ route('admin.logout') }}">
                                    <span class="font-size-sm font-w500">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END Right Section -->
            </div>

            <div id="page-header-search" class="overlay-header bg-white">
                <div class="content-header">
                    <form class="w-100" action="/superadmin/home" method="POST">
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
        <main id="main-container">
            @yield('content')
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-white">
            <div class="content py-3">
                <div class="row font-size-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                        Powered by <a class="font-w600" href="#" target="_blank">{{ config('app.name') }}</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                        <a class="font-w600" href="#" target="_blank">{{ config('app.name') }} {{ config('app.ver') }}</a> &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>




    </div>
    <script src="assets/js/oneui.core.min.js"></script>
    <script src="assets/js/oneui.app.min.js"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- OneUI Core JS -->
    <script src="{{ mix('js/oneui.app.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>
    <!-- DROPEZONE JS PLUGIN -->
    <script src="{{ asset('js/plugins/dropzone/dropzone.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Page JS Plugins -->
    <script src="{{ asset('js/plugins/easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
    <script src="assets/js/pages/be_comp_charts.min.js"></script>
    <!-- Editor JS Plugins -->
    <script src="{{ asset('js/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/simplemde/simplemde.min.js') }}"></script>
    <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
    <!-- Page JS Helpers (Easy Pie Chart + jQuery Sparkline Plugins) -->
    <script>
        jQuery(function() {
            One.helpers(['easy-pie-chart', 'sparkline','select2']);
        });
    </script>


    @yield('js_after')
</body>

</html>
