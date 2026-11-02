@extends('layouts.simple')
@section('content')
<!doctype html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>WaraqCG-Setilas</title>

        <meta name="description" content="Setilas">
        <meta name="author" content="Setilas">
        <meta name="robots" content="noindex, nofollow">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <link rel="shortcut icon" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
        <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/Setilas_logo.jfif') }}">

        <!-- Fonts and Styles -->

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
        <link rel="stylesheet" id="css-main" href="{{ asset( 'css/oneui.min.css')}}">
        <link rel="stylesheet" href="{{ asset( 'js/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet" id="css-main" href="{{ asset( 'css/oneui.min.css')}}">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
        <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">

        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body>

    <div id="page-container" class="sidebar-o enable-page-overlay sidebar-dark side-scroll page-header-fixed main-content-narrow">

            <nav id="sidebar" aria-label="Main Navigation">
                <!-- Side Header -->
                <div class="content-header bg-white-5">
                    <!-- Logo -->
                    <a class="font-w600 text-dual" href="/">
                        <span class="smini-visible">
                            <i class="fa fa-circle-notch text-primary"></i>
                        </span>
                        <span class="smini-hide font-size-h5 tracking-wider">
                            Waraq<span class="font-w400">CG</span>
                        </span>
                    </a>
                    <!-- END Logo -->

                    <!-- Extra -->
                    <div>
                        <!-- Options -->
                        <div class="dropdown d-inline-block ml-2">
                            <a class="btn btn-sm btn-dual" id="sidebar-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                                <i class="si si-drop"></i>
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

                <!-- Sidebar Scrolling -->
                <div class="js-sidebar-scroll">
                    <!-- Side Navigation -->
                    <div class="content-side">
                        <ul class="nav-main">
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="/user/home">
                                   <i class="nav-main-link-icon si si-layers"></i>
                                    <span class="nav-main-link-name">Home</span>
                                </a>
                            </li>
                            <li class="nav-main-item">
                                <a class="nav-main-link" href="{{ route('user.logout') }}">
                                    <i class="nav-main-link-icon si si-speedometer"></i>
                                <span class="nav-main-link-name">Logout</span>
                                </a>
                            </li>





                            <li class="nav-main-heading" style="background-color:rgb(50,57,66);">MODULES</li>


                            <li class="nav-main-item">
                                {{-- <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                    <i class="nav-main-link-icon si si-badge"></i>
                                    <span class="nav-main-link-name">Modules</span>
                                </a>
                                <ul class="nav-main-submenu"> --}}
                                @if($model->auditmanagement==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/actionsystem/issues') }}">
                                            <span class="nav-main-link-name">Audit Management</span>
                                        </a>
                                    </li>
                                @endif
                                @if($model->riskmanagement==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link"  href="{{url('/user/risks/risk')}}">
                                            <span class="nav-main-link-name">Enterprise Risk</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->compliance==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/compliance/register') }}">
                                            <span class="nav-main-link-name">Compliance Management</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->documentmanagement==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{url('/user/doc/view')}}" >
                                            <span class="nav-main-link-name">Document Management</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->objectives==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/objectives/listofobjectives') }}">
                                            <span class="nav-main-link-name">Objectives</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->opportunity==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/opportunity/register') }}">
                                            <span class="nav-main-link-name">Opportunity</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->correctiveaction==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/corrective/corrissues') }}">
                                            <span class="nav-main-link-name">Corrective Action</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->ohsrisk==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/ohsrisk/list') }}">
                                            <span class="nav-main-link-name">Health and Safety Risk</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->enviaspects==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/enviaspects/listofaspects') }}">
                                            <span class="nav-main-link-name">Environmental Aspect</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->contextmanagement==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/context/issuereg') }}">
                                            <span class="nav-main-link-name">Context Management</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->complaintmanagement==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/complaint/register') }}" >
                                            <span class="nav-main-link-name">Customer Complaint</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($model->vendormanagement==1)
                                    <li class="nav-main-item">
                                        <a class="nav-main-link" href="{{ url('/user/vendor/register') }}" >
                                            <span class="nav-main-link-name">Vendor Management</span>
                                        </a>
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
                        <div class="input-group input-group-sm">
                        <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="window.print('%SCRIPTUAL{view}%/%BASEWEB%/%/%BASETOPIC%?cover=print'); return false;">

                            <i class="fa fa-print  fa-1x" style="color:grey"></i>

                        </a>
                        </div>

                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div class="d-flex align-items-center">
                        <!-- User Dropdown -->
                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn btn-sm btn-dual d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 21px;">
                                <span class="d-none d-sm-inline-block ml-2">{{ Auth::user()->name }}</span>
                                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0" aria-labelledby="page-header-user-dropdown">
                                <div class="p-3 text-center bg-primary-dark rounded-top">
                                    <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
                                    <p class="mt-2 mb-0 text-white font-w500">{{ Auth::user()->name }}</p>
                                    @if(Session::get('type')==0)

                                    <p class="mb-0 text-white-50 font-size-sm">User</p>
                                    @elseif(Session::get('type')==1)
                                    <p class="mb-0 text-white-50 font-size-sm">Admin</p>
                                    @endif
                                </div>
                                <div class="p-2">
                                  {{--  <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span class="font-size-sm font-w500">Inbox</span>
                                        <span class="badge badge-pill badge-primary ml-2">3</span>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span class="font-size-sm font-w500">Profile</span>
                                        <span class="badge badge-pill badge-primary ml-2">1</span>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span class="font-size-sm font-w500">Settings</span>
                                    </a>
                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span class="font-size-sm font-w500">Lock Account</span>
                                    </a>--}}
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="/user/home">
                                        <span class="font-size-sm font-w500">Home</span>

                                    </a>

                                    <!-- <div role="separator" class="dropdown-divider"></div> -->
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="/user/change/password">
                                        <span class="font-size-sm font-w500">Change Password</span>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('user.logout') }}">
                                        <span class="font-size-sm font-w500">Logout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- END User Dropdown -->

                        <!-- Notifications Dropdown -->
                        {{-- <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-fw fa-bell"></i>
                                <span class="text-primary">•</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-2 bg-primary-dark text-center rounded-top">
                                    <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
                                </div>
                                <ul class="nav-items mb-0">
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-2 ml-3">
                                                <i class="fa fa-fw fa-check-circle text-success"></i>
                                            </div>
                                            <div class="media-body pr-2">
                                                <div class="font-w600">You have a new follower</div>
                                                <span class="font-w500 text-muted">15 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-2 ml-3">
                                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                                            </div>
                                            <div class="media-body pr-2">
                                                <div class="font-w600">1 new sale, keep it up</div>
                                                <span class="font-w500 text-muted">22 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-2 ml-3">
                                                <i class="fa fa-fw fa-times-circle text-danger"></i>
                                            </div>
                                            <div class="media-body pr-2">
                                                <div class="font-w600">Update failed, restart server</div>
                                                <span class="font-w500 text-muted">26 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-2 ml-3">
                                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                                            </div>
                                            <div class="media-body pr-2">
                                                <div class="font-w600">2 new sales, keep it up</div>
                                                <span class="font-w500 text-muted">33 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-2 ml-3">
                                                <i class="fa fa-fw fa-user-plus text-success"></i>
                                            </div>
                                            <div class="media-body pr-2">
                                                <div class="font-w600">You have a new subscriber</div>
                                                <span class="font-w500 text-muted">41 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-2 ml-3">
                                                <i class="fa fa-fw fa-check-circle text-success"></i>
                                            </div>
                                            <div class="media-body pr-2">
                                                <div class="font-w600">You have a new follower</div>
                                                <span class="font-w500 text-muted">42 min ago</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="p-2 border-top">
                                    <a class="btn btn-sm btn-light btn-block text-center" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-arrow-down mr-1"></i> Load More..
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        <!-- END Notifications Dropdown -->

                        <!-- Toggle Side Overlay -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-sm btn-dual ml-2" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="fa fa-fw fa-list-ul fa-flip-horizontal"></i>
                        </button>
                        <!-- END Toggle Side Overlay -->
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
            <main id="main-container">
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                            <h1 class="flex-sm-fill h3 my-2">
                                REGISTER <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted"></small>
                            </h1>
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Objectives</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">My objectives</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <!-- Page Content -->
                <div class="content" style="width:1250px">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded">
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full">

                        @if( count($data) > 0)
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full" style="width:100px">
                                <thead>
                                    <tr>

                                        <th class="text-center">Sl.No</th>
                                        <th class="text-center"   style="width:5%">Objectives ID</th>
                                        <th >Department</th>
                                        <th class="d-none d-sm-table-cell text-center">Objectives<br> Name</th>
                                        <th class="d-none d-sm-table-cell text-center">Objectives<br> Description</th>
                                        <th class="d-none d-sm-table-cell" >Target</th>

                                        <th style="width:5%"> Span</th>
                                        <th style="width:5%">Target date</th>
                                        <th  style="width:5%">Frequency</th>
                                        <!-- <th style="width:5%" >Reviewed date</th> -->
                                        <th style="width:5%">View</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=0;?>
                                    @foreach($data as $value)
                                    <?php
                                                    $rr=$value->Oid;
                                                    $day = $value->tardate;
                                                    $d = strtotime($day);
                                                    $date = date('Y-m-d',$d);
                                                    $dt = $value->tardate;
                                                    $dat=strtotime($dt);
                                                    $dates = date('Y-m-d',$dat);
                                                    $cats=$value->department;
                                                    $cat=strtoupper(substr($cats,0,3));
                                                    ?>
                                                    <?php $status=$value->status;
                                                    $color=null;
                                                    ?>
                                    <tr>

                                        <td class="text-center font-size-sm"  style="width:5%">{{$no=$no+1}}</td>
                                        <!-- <td class="text-center font-size-sm">{{ $value->Oid}}</td> -->


                                        @if($value->status=="Rejected")
                                                                <td class="text-center "style="background-color:red;font-size:12px;width: 100px;color:white;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @elseif($value->status=="Review Pending")
                                                                <td class="text-center" style="background-color:orange;font-size:12px;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @elseif($value->status=="Reviewed")
                                                                <td class="text-center "style="background-color:yellow;font-size:12px;width: 100px;color:black;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @else
                                                                <td class="text-center "style="background-color:#a6ff00;font-size:12px;width: 100px;color:black;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @endif
                                      {{--  @if($value->status=="Rejected")
                                                                <td class="text-center "style="background-color:red;font-size:12px;width: 100px;color:white;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @elseif($value->status=="Review Pending")
                                                                <td class="text-center "style="background-color:orange;font-size:12px;width: 100px;color:black;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @elseif($value->status=="Reviewed")
                                                                <td class="text-center "style="background-color:yellow;font-size:12px;width: 100px;color:black;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @else
                                                                <td class="text-center "style="background-color:#a6ff00;font-size:12px;width: 100px;color:black;width:5%"><?=$cat?>{{ $value->count }}</td>
                                                            @endif --}}
                                        <td style="width:5%">
                                            <em class="text-muted font-size-sm">{{ $value->department }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell font-size-sm" style="width:5%">
                                           <em class="text-muted">{{$value->objectives}}</em>
                                        </td>

                                        <td class="d-none d-sm-table-cell font-size-sm" style="width:5%">
                                           <em class="text-muted">{{$value->description}}</em>
                                        </td>
                                        @if($value->unit=='No sign')

                                        <td style="width:5%">
                                            <em class="text-muted font-size-sm">{{ $value->otarget }}</em>
                                        </td>
                                        @else
                                        <td style="width:5%">
                                            <em class="text-muted font-size-sm">{{ $value->otarget }}  {{ $value->unit }}</em>
                                        </td>

                                        @endif


                                       {{-- <td class="d-none d-sm-table-cell">
                                            @if($value->action_status=='Active Risk')
                                            <span class="badge badge-success">{{$value->action_status}}</span>
                                            @elseif($value->action_status=='Action under review')
                                            <span class="badge badge-info">{{$value->action_status}}</span>
                                            @elseif($value->action_status=='Completed')
                                            <span class="badge badge-primary">{{$value->action_status}}</span>
                                            @elseif($value->action_status=='Rejected')
                                            <span class="badge badge-danger">{{$value->action_status}}</span>
                                            @elseif($value->action_status=='Under review')
                                            <span class="badge badge-warning">{{$value->action_status}}</span>
                                            @endif
                                        </td> --}}

                                        <td>
                                            <em class="text-muted font-size-sm">{{ $value->span }}</em>
                                        </td>
                                        <td>
                                            <em class="text-muted font-size-sm">{{ $value->tardate }}</em>
                                        </td>
                                        <td>
                                            <em class="text-muted font-size-sm">{{ $value->frequency}}</em>
                                        </td>

                                        <td style="width:5%">
                                            <em class="text-muted font-size-sm"></em>
                                        </td>




                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>


                            @else
                                            <div class="row justify-content-center">
                                                <h2>No Data</h2>
                                            </div>
                                        @endif
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->


                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="bg-body-light">
                <div class="content py-3">
                    <div class="row font-size-sm">
                        <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                            Crafted with <i class="fa fa-heart text-danger"></i> by <a class="font-w600" href="https://1.envato.market/ydb" target="_blank">pixelcave</a>
                        </div>
                        <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                            <a class="font-w600" href="https://1.envato.market/AVD6j" target="_blank">OneUI 4.7</a> &copy; <span data-toggle="year-copy"></span>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Apps</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">
                                    <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/addobjectives">
                                            <div class="block-content text-center">
                                                <i class="si si-speedometer fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Objective
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                            <div class="block-content text-center">
                                                <i class="si si-rocket fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 <br> Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/achieved">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 <br>  Achieved<br>
                                                </p>
                                            </div>
                                        </a>

                                      </div>
                                      <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/notachieved">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Not Achieved
                                                </p>
                                            </div>
                                        </a>

                                      </div>



                                      <div class="col-6">
                                        <!-- Sales -->
                                        <br>
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/viewreport">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Objective report
                                                </p>
                                            </div>
                                        </a>

                                      </div>

                                        <!-- END Payments -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->
        </div>
        <!-- END Page Container -->

        <!--
            OneUI JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the assets/js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            assets/js/core/jquery.min.js
            assets/js/core/bootstrap.bundle.min.js
            assets/js/core/simplebar.min.js
            assets/js/core/jquery-scrollLock.min.js
            assets/js/core/jquery.appear.min.js
            assets/js/core/js.cookie.min.js
        -->
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/oneui.core.min.js')}}"></script>

        <!--
            OneUI JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/oneui.app.min.js')}}"></script>

        <!-- Page JS Plugins -->
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/buttons/dataTables.buttons.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/buttons/buttons.print.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/buttons/buttons.html5.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/buttons/buttons.flash.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/plugins/datatables/buttons/buttons.colVis.min.js')}}"></script>

        <!-- Page JS Code -->

        <script src= "{{ asset( '02 - OneUI Source (HTML)/src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
    </body>
</html>
 @endsection
