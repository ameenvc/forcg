@extends('admin.datatable')

@section('css_before')
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="/css/sweetalert/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

@endsection

@section('content')
    <style>
        .status {
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .status-blue {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-red {
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .status-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .status-secondary {
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .status-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .status-flat {
            color: #44b3ab;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .table-custom thead th {
            font-weight: 600;
            font-size: 12px;
            text-transform: none;
            letter-spacing: 0;
            text-align: center;
            vertical-align: middle;
        }
        .table-custom tbody td {
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            /* padding: .25rem; */
        }
        .table-custom tbody td:nth-child(3) {
            text-align: left;
        }
        .table-custom {
            /* border: 1px solid #dee2e6; */
        }
        .table-custom thead tr th {
            background: #252945;
            color: white;
            border: none;
        }
        .table-custom thead tr th:not(:last-child) {
            border-right: 1px solid #e1e6e9;
        }
        .table-custom thead tr th:first-child {
            border-radius: 10px 0 0 0;
        }
        .table-custom thead tr th:last-child {
            border-radius: 0 10px 0 0;
        }
        .btn-export:focus {
            box-shadow: none !important;
        }
        .user-tabs .nav-link {
            border: none;
            background: transparent;
            color: #6c757d;
            font-weight: 500;
            font-size: 14px;
        }
        .user-tabs .nav-link.active {
            color: #252945;
            border-bottom: 2px solid #252945;
            background: transparent;
        }
        .search-container {
            position: relative;
            margin-bottom: 0 !important;
        }
        .search-container .fa-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .search-container input {
            font-size: 14px;
            padding-left: 35px;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: #6c757d;
            height: calc(1.5em + .5rem + 2px);
        }
        .search-container input:focus {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            box-shadow: none;
        }
        .table-custom tbody tr {
            border-bottom: 1px solid #dee2e6;
        }
        .table-custom tbody tr:last-child {
            border-bottom: none;
        }
        .table-custom tbody td {
            border-right: 1px solid #dee2e6;
        }
        .table-custom tbody td:last-child {
            /* border-right: none; */
        }
        .table-custom thead th,
        .table-custom tbody td {
            border: 1px solid #CFCFCF;
        }
        .table-custom tbody tr {
            background-color: #f8f9fa;
        }
        .table-custom tbody tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }
        .action-buttons {
            display: flex;
            gap: .75rem;
            justify-content: center;
        }
        .action-buttons .btn {
            padding: 0.15rem 0.25rem;
            font-size: 0.5rem;
        }
        .btn-protected {
            background-color: #25499C3D !important;
            /* border-color: #25499C3D !important; */
            color: #25499C !important;
            padding: 0.35rem 0.35rem;
            margin-bottom: 0 !important;
        }
        .btn-protected:disabled {
            background-color: #e9ecef !important;
            color: #6c757d !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }
        /* .btn-protected:hover {
            background-color: #25499C !important;
            border-color: #25499C !important;
            color: #252945 !important;
        } */
        .btn-edit {
            background-color: #25499C3D !important;
            border-color: #25499C3D !important;
            color: #252945 !important;
        }
        .btn-edit:hover {
            background-color: #25499C3D !important;
            border-color: #25499C3D !important;
            color: #25499C !important;
        }
        .btn-manage {
            background-color: #EE9F273D !important;
            border-color: #EE9F273D !important;
            color: #EE9F27 !important;
        }
        .btn-manage:hover {
            background-color: #EE9F273D !important;
            border-color: #EE9F273D !important;
            color: #EE9F27 !important;
        }
        .btn-delete {
            background-color: #AF1E1E3D !important;
            border-color: #AF1E1E3D !important;
            color: #AF1E1E !important;
        }
        .btn-delete:hover {
            background-color: #AF1E1E3D !important;
            border-color: #AF1E1E3D !important;
            color: #AF1E1E !important;
        }
        .btn-restore {
            background-color: #EE9F273D !important;
            border-color: #EE9F273D !important;
            color: #EE9F27 !important;
        }
        .btn-restore:hover {
            background-color: #EE9F273D !important;
            border-color: #EE9F273D !important;
            color: #EE9F27 !important;
        }
        .action-icon-restore {
            width: 16px !important;
            height: auto !important;
        }
        .emp-id-deleted {
            color: #dc3545;
            font-weight: bold;
        }
        .action-icon {
            width: 10px;
            height: 10px;
            vertical-align: middle;
        }
        .page-content-container {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            box-shadow: 0px 2px 14px rgba(0, 0, 0, 0.14);
            overflow: hidden;
        }
        .table-custom tbody td:nth-child(3),
        .table-custom tbody td:nth-child(4) {
            text-align: center;
        }
        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            display: inline-block;
        }
        .img-avatar48 {
            width: 48px !important;
            height: 48px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
        }
        .img-avatar24 {
            width: 24px !important;
            height: 24px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
        }
        .action-buttons a {
            text-decoration: none;
        }
        .action-buttons a:hover {
            text-decoration: none;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5rem 0.75rem;
            margin-left: 2px;
            border: 1px solid #dee2e6;
            background: #fff;
            color: #007bff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef !important;
            border-color: #adb5bd;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #007bff !important;
            border-color: #007bff;
            color: #fff !important;
        }
        .dataTables_wrapper .dataTables_length select {
            padding: 0.25rem 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .pagination-info {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        .pagination {
            margin-bottom: 0;
        }
        .pagination .page-link {
            color: #252945;
            border: 1px solid #e1e6e9;
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 12px;
            transition: all 0.3s ease;
            background-color: #fff;
        }
        .pagination .page-link:hover {
            color: #fff;
            background-color: #252945;
            border-color: #252945;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(37, 41, 69, 0.2);
        }
        .pagination .page-item.active .page-link {
            background-color: #252945;
            border-color: #252945;
            color: #fff;
            box-shadow: 0 2px 4px rgba(37, 41, 69, 0.3);
        }
        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            cursor: not-allowed;
        }
        .pagination .page-item.disabled .page-link:hover {
            color: #adb5bd;
            background-color: #f8f9fa;
            border-color: #e9ecef;
            transform: none;
            box-shadow: none;
        }
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 6px;
        }
        .pagination .page-item .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(37, 41, 69, 0.25);
            outline: none;
        }
        .pagination .page-item .page-link:active {
            transform: translateY(0);
        }
        .pagination .page-item.disabled .page-link:focus {
            box-shadow: none;
        }
        .pagination-container {
            margin-bottom: 2rem;
        }
        .chckbox {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #e1e6e9;
            background-color: #fff;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            position: relative;
            transition: all 0.2s ease;
        }
        .chckbox:hover {
            border-color: #252945;
            box-shadow: 0 2px 4px rgba(37, 41, 69, 0.1);
        }
        .chckbox:checked {
            background-color: #252945;
            border-color: #252945;
            box-shadow: 0 2px 4px rgba(37, 41, 69, 0.3);
        }
        .chckbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        .chckbox[data-id="all"]:checked {
            background-color: #252945;
            border-color: #252945;
            box-shadow: 0 3px 6px rgba(37, 41, 69, 0.4);
        }
        .chckbox[data-id="all"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }
        .chckbox:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(37, 41, 69, 0.2);
        }

        /* Print Styles */
        @media print {
            /* Page Setup */
            @page {
                size: A4 landscape;
                margin: 0.5in;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            /* Hide UI elements */
            .btn, .dropdown, .search-container, .pagination-container,
            .border-right, #page-limit-selector {
                display: none !important;
            }

            /* Container styles */
            .content {
                padding: 0 !important;
                margin: 0 !important;
            }

            .page-content-container {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Header section */
            .d-flex-sx {
                background: #F5F5F5 !important;
                padding: 10px 15px !important;
                margin-bottom: 10px !important;
            }

            /* Main content area */
            .mt-4 {
                margin-top: 0 !important;
                padding: 0 15px !important;
            }

            /* Table container */
            .table-responsive {
                overflow: visible !important;
                border: none !important;
            }

            /* Table layout */
            .table-custom {
                width: 100% !important;
                font-size: 9px !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                page-break-inside: auto !important;
            }

            .table-custom thead {
                display: table-header-group !important;
            }

            .table-custom thead th {
                font-size: 9px !important;
                background: #252945 !important;
                color: white !important;
                padding: 6px 3px !important;
                border: 1px solid #CFCFCF !important;
                font-weight: 600 !important;
                word-wrap: break-word !important;
            }

            .table-custom tbody {
                display: table-row-group !important;
            }

            .table-custom tbody tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
                border-bottom: 1px solid #ddd !important;
            }

            .table-custom tbody td {
                font-size: 8px !important;
                padding: 4px 3px !important;
                border: 1px solid #CFCFCF !important;
                word-wrap: break-word !important;
                overflow: hidden !important;
            }

            .table-custom tbody tr:nth-child(even) {
                background-color: #f8f9fa !important;
            }

            .table-custom tbody tr:nth-child(odd) {
                background-color: white !important;
            }

            /* Hide checkboxes and action buttons */
            .action-buttons,
            .chckbox {
                display: none !important;
            }

            /* Hide first (checkbox) and last (actions) columns */
            .table-custom thead th:first-child,
            .table-custom tbody td:first-child,
            .table-custom thead th:last-child,
            .table-custom tbody td:last-child {
                display: none !important;
            }

            /* Ensure visible columns display properly */
            .table-custom thead th:nth-child(2),
            .table-custom thead th:nth-child(3),
            .table-custom thead th:nth-child(4),
            .table-custom thead th:nth-child(5),
            .table-custom thead th:nth-child(6),
            .table-custom thead th:nth-child(7),
            .table-custom thead th:nth-child(8),
            .table-custom thead th:nth-child(9),
            .table-custom tbody td:nth-child(2),
            .table-custom tbody td:nth-child(3),
            .table-custom tbody td:nth-child(4),
            .table-custom tbody td:nth-child(5),
            .table-custom tbody td:nth-child(6),
            .table-custom tbody td:nth-child(7),
            .table-custom tbody td:nth-child(8),
            .table-custom tbody td:nth-child(9) {
                display: table-cell !important;
            }

            /* Column widths for better layout */
            .table-custom thead th:nth-child(2),
            .table-custom tbody td:nth-child(2) {
                width: 4% !important;
            }

            .table-custom thead th:nth-child(3),
            .table-custom tbody td:nth-child(3) {
                width: 9% !important;
            }

            .table-custom thead th:nth-child(4),
            .table-custom tbody td:nth-child(4) {
                width: 8% !important;
            }

            .table-custom thead th:nth-child(5),
            .table-custom tbody td:nth-child(5) {
                width: 18% !important;
            }

            .table-custom thead th:nth-child(6),
            .table-custom tbody td:nth-child(6) {
                width: 20% !important;
            }

            .table-custom thead th:nth-child(7),
            .table-custom tbody td:nth-child(7) {
                width: 15% !important;
            }

            .table-custom thead th:nth-child(8),
            .table-custom tbody td:nth-child(8) {
                width: 13% !important;
            }

            .table-custom thead th:nth-child(9),
            .table-custom tbody td:nth-child(9) {
                width: 13% !important;
            }

            /* Avatar images */
            .img-avatar24 {
                width: 18px !important;
                height: 18px !important;
                border-radius: 50% !important;
            }

            /* Page title */
            h5 {
                font-size: 14px !important;
                margin-bottom: 10px !important;
                page-break-after: avoid !important;
            }
        }

        /* Mobile Responsive */
        @media only screen and (max-width: 786px) {
            .px-5-sm {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .d-flex-sm {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            .btn-protected {
                width: 100% !important;
            }
            .user-tabs {
                margin-bottom: 1rem !important;
            }
            .search-container {
                width: 100% !important;
            }
            .search-container input {
                width: 100% !important;
            }
            .col-md-2-sm {
                flex: 0 0 12%;
                max-width: 12%;
            }
            .table-responsive {
                font-size: 11px;
            }
            .action-buttons {
                /* flex-direction: column !important;
                gap: 10px !important; */
            }
            .action-buttons .btn {
                font-size: 0.6rem !important;
                padding: 0.2rem 0.3rem !important;
            }
            .pagination-container {
                flex-direction: column !important;
                align-items: center !important;
                gap: 1rem;
            }
            .pagination {
                justify-content: center !important;
            }
            .table-custom tbody td,
            .table-custom thead th {
                white-space: nowrap !important;
            }
        }

        @media only screen and (max-width: 576px) {
            .d-flex-sx {
                display: inline-block !important;
                width: 100%;
            }
            .btn-protected {
                width: 100% !important;
                display: block;
            }
            .search-container {
                width: 77% !important;
                display: inline-block;
            }
            .col-md-2-sm {
                max-width: 21% !important;
                margin-left: 0 !important;
                width: 25% !important;
                display: inline-block !important;
            }
            .table-custom {
                font-size: 10px !important;
            }
            .table-custom thead th {
                /* font-size: 10px !important; */
                white-space: nowrap !important;
                /* padding: 0.3rem !important; */
            }
            .table-custom tbody td {
                font-size: 10px !important;
                white-space: nowrap !important;
                /* padding: 0.3rem !important; */
            }
            .action-buttons .btn {
                font-size: 0.5rem !important;
                padding: 0.1rem 0.2rem !important;
            }
            .action-icon {
                width: 12px !important;
                height: 12px !important;
            }
            .search-container {
                margin-right: 0.5rem !important;
            }
        }
        @media only screen and (max-width: 430px) {
            .d-flex-tiny {
                display: inline-block !important;
                width: 100%;
            }
            .search-container {
                margin-top: 0.5rem !important;
                margin-right: 0 !important;
            }
        }
    </style>

    <!-- Page Content -->
    <div class="content px-0" style="-webkit-print-color-adjust: exact">
        <!-- Horizontal -->
        <div class="block block-rounded page-content-container" style="background-color: #ffffff;">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center d-flex-sx" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 px-5 px-5-sm align-items-center user-management-header">
                    <span class="mr-2 font-weight-bold">User Management</span>
                    <span style="font-size: 1rem; color: grey; padding-top: 0.2rem;">User Logs</span>
                </div>
                <div class="d-flex-right p-2 d-flex d-block px-5-sm">
                    <div class="d-flex mr-4 border-right">
                        @php
                            $user_details= App\User::where('email',Auth::user()->email)->first();
                            $profilePicture =  $user_details ? $user_details->profile_picture : '';
                        @endphp
                        @if ($profilePicture && file_exists(public_path('/storage/profile/'. $profilePicture)))
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/' . $profilePicture) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src=" {{ asset('/images/avatar10.jpg') }}" />
                        @endif
                    </div>
                    <div class="d-flex align-items-center d-block-400">
                        <div class="mr-2">
                            <button class="btn btn-primary px-3" onclick="printPage();"
                                    style="background: #252945; border-color:#252945; color: white; font-size: 12px">
                                <i class="fa fa-print mr-2"></i> Print
                            </button>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn btn-success btn-export dropdown-toggle"
                                    id="dropdown-export" style="background: #252945; border-color:#252945; color: white; font-size: 12px"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-file-download mr-2"></i> Export
                            </button>
                            <div class="dropdown-menu font-size-sm" style="min-width: 5rem" aria-labelledby="dropdown-export">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="exportUsers('pdf')">PDF</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="exportUsers('excel')">Excel</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="exportUsers('word')">Word</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="mt-4 px-5 px-5-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0" style="font-size: 1rem;">All Users</h5>
                </div>

                <!-- User Type Tabs and Controls -->
                <div class="d-flex justify-content-between align-items-center mb-4 d-flex-sm">
                    <ul class="nav nav-tabs user-tabs mb-0" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="active-users-tab" data-toggle="tab" href="#active-users" role="tab" aria-controls="active-users" aria-selected="true">Active Users({{ $users->count()}})</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="deleted-users-tab" data-toggle="tab" href="#deleted-users" role="tab" aria-controls="deleted-users" aria-selected="false">Deleted Users({{ \App\User::onlyTrashed()->get()->count()}})</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center d-flex-tiny">
                        <button class="btn btn-protected mr-3 mb-3" id="action-button" style="font-size: 14px; font-weight: 500;" onclick="changeHref()" disabled>
                            <img src="{{ asset('images/icons/enable_disable_module.png') }}" class="action-icon mr-1"
                            style="width: 12; height: 12;"
                             alt="Enable/Disable"> Enable/Disable Modules
                        </button>
                        <div class="search-container mb-3">
                            <i class="fa fa-search"></i>
                            <input type="text" class="form-control" placeholder="Search Users..." style="width: 10rem;">
                        </div>
                        <div class="ml-3 col-md-2-sm mb-3" style="margin-bottom: 0 !important;">
                            <select id="page-limit-selector" class="form-control form-control-sm" style="font-size: 14px; height: 32px">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="userTabsContent">
                @if(Session::has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        <strong>Success!</strong> {!!Session::get('message')!!}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                    <!-- Active Users Tab -->
                    <div class="tab-pane fade show active" id="active-users" role="tabpanel" aria-labelledby="active-users-tab">
                        <div class="table-responsive">
                            <table class="table table-custom" id="users-table">
                                <thead>
                                    <tr>
                                        <th width="3%">
                                            <input type="checkbox" class="chckbox" data-id="all">
                                        </th>
                                        <th width="5%">No</th>
                                        <th width="8%">EMP ID</th>
                                        <th width="10%">Profile Image</th>
                                        <th width="15%">Name</th>
                                        <th width="15%">Email</th>
                                        <th width="12%">Department</th>
                                        <th width="10%">User Access</th>
                                        <th width="10%">Registered On</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 1; ?>
                                    @foreach($users as $u)
                                        {{csrf_field()}}
                                        <?php
                                            $dept = $u->deptid;
                                            $email = $u->email;
                                            $empid = $u->employeeid;
                                            $name = $u->name;
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="chckbox" data-id="{{ $u->id }}">
                                            </td>
                                            <td>{{ $number }}</td>
                                            <td>{{ $u->employeeid == null ? 'Not Set' : $u->employeeid }}</td>
                                            <td>
                                                <img class="img-avatar img-avatar24" src="{{ ($u->profile_picture && File::exists(public_path('/storage/profile/'.$u->profile_picture))) ? asset('storage/profile/'.$u->profile_picture) : asset('/images/avatar10.jpg') }}" alt="Profile">
                                            </td>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td>{{ $u->deptid == NULL ? 'Not Set' : $u->deptid }}</td>
                                            <td>{{ $u->type == 1 ? 'Admin' : 'User' }}</td>
                                            <td>{{ $u->created_at ? date('d-m-Y', strtotime($u->created_at)) : '-' }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ url('/admin/displayeditUsers?val='.$u->id.'&value='.$email) }}" class="btn btn-sm btn-edit" title="Edit">
                                                        <img src="{{ asset('images/icons/stylus.png') }}" class="action-icon" alt="Edit">
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-delete" title="Delete" onclick="deleteUser({{ $u->id }})">
                                                        <img src="{{ asset('images/icons/delete.png') }}" class="action-icon" alt="Delete">
                                                    </button>
                                                    <a href="{{ url('/admin/manageRoles?val='.$u->id.'&value='.$u->email) }}" class="btn btn-sm btn-manage" title="Manage">
                                                        <img src="{{ asset('images/icons/manageuser.png') }}" class="action-icon" alt="Manage">
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $number++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Custom Pagination for Active Users -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pagination-container">
                            <div class="pagination-info">
                                <span>Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span id="total-records">{{ count($users) }}</span> entries</span>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0" id="pagination">
                                    <!-- Pagination will be generated by JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- Deleted Users Tab -->
                    <div class="tab-pane fade" id="deleted-users" role="tabpanel" aria-labelledby="deleted-users-tab">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th width="3%">
                                            <input type="checkbox" class="chckbox" data-id="all">
                                        </th>
                                        <th width="5%">No</th>
                                        <th width="8%">EMP ID</th>
                                        <th width="10%">Profile Image</th>
                                        <th width="15%">Name</th>
                                        <th width="15%">Email</th>
                                        <th width="12%">Department</th>
                                        <th width="10%">User Access</th>
                                        <th width="12%">Deleted On</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $deletedUsers = \App\User::onlyTrashed()->orderBy('name', 'asc')->get();
                                        $deletedNumber = 1;
                                    @endphp
                                    @if($deletedUsers && count($deletedUsers) > 0)
                                        @foreach($deletedUsers as $du)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="chckbox" data-id="{{ $du->id }}">
                                                </td>
                                                <td>{{ $deletedNumber }}</td>
                                                <td class="emp-id-deleted">{{ $du->employeeid == 0 ? 'Not Set' : $du->employeeid }}</td>
                                                <td>
                                                    <img class="img-avatar img-avatar24" src="{{ ($du->profile_picture && File::exists(public_path('/storage/profile/'.$du->profile_picture))) ? asset('storage/profile/'.$du->profile_picture) : asset('/images/avatar10.jpg') }}" alt="Profile">
                                                </td>
                                                <td>{{ $du->name }}</td>
                                                <td>{{ $du->email }}</td>
                                                <td>{{ $du->deptid == NULL ? 'Not Set' : $du->deptid }}</td>
                                                <td>{{ $du->type == 1 ? 'Admin' : 'User' }}</td>
                                                <td>{{ $du->deleted_at ? date('d-m-Y', strtotime($du->deleted_at)) : '-' }}</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button type="button" class="btn btn-sm btn-restore" title="Restore" onclick="restoreUser({{ $du->id }})">
                                                            <img src="{{ asset('images/icons/settings_backup_restore_user.png') }}" class="action-icon" alt="Restore">
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-delete" title="Delete Permanently" onclick="permanentDeleteUser({{ $du->id }})">
                                                            <img src="{{ asset('images/icons/delete.png') }}" class="action-icon" alt="Delete">
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $deletedNumber++; @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center text-muted">No deleted users found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Custom Pagination for Deleted Users -->
                        <div class="d-flex justify-content-between align-items-center mt-3 pagination-container">
                            <div class="pagination-info">
                                <span>Showing <span id="showing-start-deleted">1</span> to <span id="showing-end-deleted">25</span> of <span id="total-records-deleted">{{ count($deletedUsers ?? []) }}</span> entries</span>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0" id="pagination-deleted">
                                    <!-- Pagination will be generated by JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>

    function deleteUser(id) {
        // Redirect directly to showResponsibilities page (that page serves as the confirmation)
        window.location.href = '{{ route("admin.showResponsibilities", ":id") }}'.replace(':id', id);
    }

    function restoreUser(id) {
        Swal.fire({
            title: 'Restore User?'
            , text: "This will restore the user and make them active again!"
            , icon: 'question'
            , showCancelButton: true
            , confirmButtonColor: '#EE9F27'
            , cancelButtonColor: '#d33'
            , confirmButtonText: 'Yes, restore user!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Restoring User...',
                    text: 'Please wait while we restore the user.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make AJAX call to restore the user
                $.ajax({
                    url: '{{ url("/admin/restoreUser") }}/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'User has been restored successfully.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to restore user.',
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        let errorMessage = 'An error occurred while restoring the user.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }
        })
    }

    function permanentDeleteUser(id) {
        Swal.fire({
            title: 'Permanently Delete User?',
            text: "This action cannot be undone! The user will be permanently removed from the system.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#AF1E1E',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete permanently!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting User...',
                    text: 'Please wait while we permanently delete the user.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make AJAX call to permanently delete the user
                $.ajax({
                    url: '{{ url("/admin/permanentDeleteUser") }}/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'User has been permanently deleted from the system.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to delete user permanently.',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        let errorMessage = 'An error occurred while deleting the user.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        })
    }

</script>
@endsection
@section('js_new')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    let selected_ids = [];
    let enable_disable_module_url = '{{ route("admin.enable_disable_module", ":ids") }}';
    let ids = [];

    $('.chckbox').on('change', function() {
        let isSelectAll = $(this).data('id') === 'all';

        if (isSelectAll) {
            // Handle "Select All" checkbox
            let isChecked = $(this).is(':checked');
            $('.chckbox').not('[data-id="all"]').prop('checked', isChecked);
        } else {
            // Handle individual checkbox - update header checkbox state
            updateHeaderCheckboxState();
        }

        // Get all checked checkboxes (excluding the "select all" checkbox)
        let checkedBoxes = $('.chckbox:checked').not('[data-id="all"]');

        // Update selected_ids array with only checked items
        selected_ids = checkedBoxes.map(function() {
            return $(this).data('id');
        }).get();

        // Enable/disable the action button based on whether any items are selected
        if (selected_ids.length > 0) {
            $('#action-button').prop('disabled', false);
        } else {
            $('#action-button').prop('disabled', true);
        }

        // Update the ids string for the URL
        ids = selected_ids.join(',');

        console.log('Selected IDs:', selected_ids);
        console.log('Action button disabled:', $('#action-button').prop('disabled'));
    });

    function updateHeaderCheckboxState() {
        let totalCheckboxes = $('.chckbox').not('[data-id="all"]').length;
        let checkedCheckboxes = $('.chckbox:checked').not('[data-id="all"]').length;

        let headerCheckbox = $('.chckbox[data-id="all"]');

        if (checkedCheckboxes === 0) {
            // None checked - uncheck header
            headerCheckbox.prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            // All checked - check header
            headerCheckbox.prop('checked', true);
        } else {
            // Some checked - uncheck header (partial selection)
            headerCheckbox.prop('checked', false);
        }
    }


    // Custom pagination and search functionality
    let currentPage = 1;
    let itemsPerPage = 25;
    let allRows = [];
    let filteredRows = [];

    $(document).ready(function() {
        // Initialize pagination
        initializePagination();

        // Initialize header checkbox state
        updateHeaderCheckboxState();

        // Enable/Disable Modules button is already in HTML, no need to add dynamically

        // Handle page limit selector change
        $('#page-limit-selector').on('change', function() {
            itemsPerPage = parseInt($(this).val());
            currentPage = 1;
            showPage(1);
            generatePagination();
        });

        // Handle page limit selector for deleted users
        $('#page-limit-selector').on('change', function() {
            deletedItemsPerPage = parseInt($(this).val());
            deletedCurrentPage = 1;
            if ($('#deleted-users').hasClass('active')) {
                showDeletedUsersPage(1);
                generateDeletedUsersPagination();
            }
        });

        // Auto-hide success alert after 10 seconds
        if ($('#success-alert').length) {
            setTimeout(function() {
                $('#success-alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 10000); // 10 seconds
        }
    });

    function initializePagination() {
        // Get all table rows (excluding header)
        allRows = $('#users-table tbody tr').toArray();
        filteredRows = [...allRows];

        // Show first page
        showPage(1);
        generatePagination();
    }

    function showPage(page) {
        currentPage = page;
        let startIndex = (page - 1) * itemsPerPage;
        let endIndex = startIndex + itemsPerPage;

        // Hide all rows first
        $('#users-table tbody tr').hide();

        // Show rows for current page
        for (let i = startIndex; i < endIndex && i < filteredRows.length; i++) {
            $(filteredRows[i]).show();
        }

        // Update pagination info
        updatePaginationInfo();
    }

    function generatePagination() {
        let totalPages = Math.ceil(filteredRows.length / itemsPerPage);
        let paginationHtml = '';

        // Previous button
        paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1}); return false;">Previous</a>
        </li>`;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${i}); return false;">${i}</a>
                </li>`;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                paginationHtml += `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
        }

        // Next button
        paginationHtml += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1}); return false;">Next</a>
        </li>`;

        $('#pagination').html(paginationHtml);
    }

    function changePage(page) {
        let totalPages = Math.ceil(filteredRows.length / itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            showPage(page);
            generatePagination();
        }
    }

    function updatePaginationInfo() {
        let startIndex = (currentPage - 1) * itemsPerPage + 1;
        let endIndex = Math.min(currentPage * itemsPerPage, filteredRows.length);

        $('#showing-start').text(startIndex);
        $('#showing-end').text(endIndex);
        $('#total-records').text(filteredRows.length);
    }

    // Custom search functionality
    $('.search-container input').on('keyup', function() {
        let searchValue = $(this).val().toLowerCase();

        // Check which tab is active
        if ($('#active-users').hasClass('active')) {
            // Filter active users rows
            filteredRows = allRows.filter(function(row) {
                let rowText = $(row).text().toLowerCase();
                return rowText.indexOf(searchValue) > -1;
            });

            // Reset to first page and show results
            currentPage = 1;
            showPage(1);
            generatePagination();
        } else if ($('#deleted-users').hasClass('active')) {
            // Filter deleted users rows
            deletedFilteredRows = deletedAllRows.filter(function(row) {
                let rowText = $(row).text().toLowerCase();
                return rowText.indexOf(searchValue) > -1;
            });

            // Reset to first page and show results
            deletedCurrentPage = 1;
            showDeletedUsersPage(1);
            generateDeletedUsersPagination();
        }
    });

    function changeHref() {
       console.log(ids);

       // Check if we're on deleted users tab
       let currentTab = $('#userTabs .nav-link.active').attr('href');
       let isDeletedUsers = currentTab === '#deleted-users';

       if (isDeletedUsers) {
           // Handle restore users functionality
           if (ids && ids.length > 0) {
               restoreMultipleUsers(ids);
           } else {
               Swal.fire({
                   title: 'No Users Selected',
                   text: 'Please select users to restore.',
                   icon: 'warning',
                   showConfirmButton: false,
                   timer: 3000
               });
           }
       } else {
           // Handle enable/disable modules functionality
       enable_disable_module_url = enable_disable_module_url.replace(':ids', ids);
       window.location.replace(enable_disable_module_url);
    }
    }

    function restoreMultipleUsers(ids) {
        Swal.fire({
            title: 'Restore Multiple Users?',
            text: "This will restore all selected users and make them active again!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#EE9F27',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore users!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Restoring Users...',
                    text: 'Please wait while we restore the users.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make AJAX call to restore multiple users
                $.ajax({
                    url: '{{ url("/admin/restoreMultipleUsers") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_ids: ids.split(',')
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Users have been restored successfully.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to restore users.',
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while restoring the users.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            }
        });
    }

    function printPage() {
        // Hide elements that shouldn't be printed
        $('.btn, .dropdown, .search-container, .pagination-container, #page-limit-selector').hide();

        $('.user-management-header').removeClass('px-5');

        // Show all table rows before printing
        $('#users-table tbody tr').show();

        // Print the page
        window.print();

        // Show elements again and restore pagination after printing
        setTimeout(function() {
            $('.btn, .dropdown, .search-container, .pagination-container, #page-limit-selector').show();
            $('.user-management-header').addClass('px-5');
            // Restore the current page view
            showPage(currentPage);
        }, 1000);
    }


    // Handle tab switching and button text changes
    $(document).ready(function() {
        // Initialize tooltips for all buttons with title attribute
        $('[title]').tooltip({
            placement: 'top',
            trigger: 'hover',
            container: 'body'
        });

        $('#userTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            var actionButton = $('#action-button');

            if (target === '#deleted-users') {
                actionButton.html('<img src="{{ asset("images/icons/groupuser.png") }}" class="action-icon mr-2 action-icon-restore" alt="Restore"> Restore Users');
                // Initialize deleted users pagination
                initializeDeletedUsersPagination();
            } else if (target === '#active-users') {
                actionButton.html('<img src="{{ asset("images/icons/enable_disable_module.png") }}" class="action-icon mr-2" alt="Enable/Disable"> Enable/Disable Modules');
                // Re-initialize active users pagination
                initializePagination();
            }
        });
    });

    // Deleted users pagination
    let deletedCurrentPage = 1;
    let deletedItemsPerPage = 25;
    let deletedAllRows = [];
    let deletedFilteredRows = [];

    function initializeDeletedUsersPagination() {
        // Get all deleted users table rows
        deletedAllRows = $('#deleted-users table tbody tr').toArray();
        deletedFilteredRows = [...deletedAllRows];

        // Show first page
        showDeletedUsersPage(1);
        generateDeletedUsersPagination();
    }

    function showDeletedUsersPage(page) {
        deletedCurrentPage = page;
        let startIndex = (page - 1) * deletedItemsPerPage;
        let endIndex = startIndex + deletedItemsPerPage;

        // Hide all rows first
        $('#deleted-users table tbody tr').hide();

        // Show rows for current page
        for (let i = startIndex; i < endIndex && i < deletedFilteredRows.length; i++) {
            $(deletedFilteredRows[i]).show();
        }

        // Update pagination info
        updateDeletedUsersPaginationInfo();
    }

    function generateDeletedUsersPagination() {
        let totalPages = Math.ceil(deletedFilteredRows.length / deletedItemsPerPage);
        let paginationHtml = '';

        // Previous button
        paginationHtml += `<li class="page-item ${deletedCurrentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changeDeletedUsersPage(${deletedCurrentPage - 1}); return false;">Previous</a>
        </li>`;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= deletedCurrentPage - 2 && i <= deletedCurrentPage + 2)) {
                paginationHtml += `<li class="page-item ${i === deletedCurrentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changeDeletedUsersPage(${i}); return false;">${i}</a>
                </li>`;
            } else if (i === deletedCurrentPage - 3 || i === deletedCurrentPage + 3) {
                paginationHtml += `<li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>`;
            }
        }

        // Next button
        paginationHtml += `<li class="page-item ${deletedCurrentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="javascript:void(0)" onclick="changeDeletedUsersPage(${deletedCurrentPage + 1}); return false;">Next</a>
        </li>`;

        $('#pagination-deleted').html(paginationHtml);
    }

    function changeDeletedUsersPage(page) {
        let totalPages = Math.ceil(deletedFilteredRows.length / deletedItemsPerPage);
        if (page >= 1 && page <= totalPages) {
            showDeletedUsersPage(page);
            generateDeletedUsersPagination();
        }
    }

    function updateDeletedUsersPaginationInfo() {
        let startIndex = (deletedCurrentPage - 1) * deletedItemsPerPage + 1;
        let endIndex = Math.min(deletedCurrentPage * deletedItemsPerPage, deletedFilteredRows.length);

        $('#showing-start-deleted').text(startIndex);
        $('#showing-end-deleted').text(endIndex);
        $('#total-records-deleted').text(deletedFilteredRows.length);
    }

</script>

@include('exporting.forusermanagement')

@endsection
