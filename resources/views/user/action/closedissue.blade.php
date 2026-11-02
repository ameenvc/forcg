@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue2" name="tvalue2" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>


@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    <link rel="stylesheet" href="{{ asset('css/workflow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">

    <style>
        body {
            overflow-y: hidden;
        }

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

        .headingselect {
            white-space: break-spaces;
            line-height: 1rem;
        }

        .header {
            margin-left: 7%;
            margin-bottom: -3.6%;
        }
        
        .status-blue{
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-red{
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .status-warning{
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .status-secondary{
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .status-success{
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .status-flat{
            color: #44b3ab;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .breadcrumb > li + li:before {
            display: none !important;
        }
        .breadcrumb {
            font-size: 15px;
            padding: 0.75rem 1.25rem;
            background: #F6F7F9;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .breadcrumb li:first-child {
            font-size: 1rem;
            text-transform: uppercase;
        }
        .breadcrumb li:last-child {
            font-size: 1rem;
            color: gray;
        }
        .scrollable {
            height: 500px !important;
            overflow-x: scroll;
            overflow-y: scroll;
        }
        /*---------- arrow css ends -----------*/
        #dropdownMenucol {
            display: none;
            position: absolute;
            z-index: 1000;
            background-color: white;
            list-style: none;
            margin: 0;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: .2rem;
        }
        #dropdownMenucol li {
            padding: 5px 10px;
        }
        #dropdownMenucol li a {
            text-decoration: none;
            color: black;
            display: block;
        }
        #dropdownMenucol li a:hover {
            background-color: #f0f0f0;
        }
        .btn-configure-table {
            border-radius: 50%;
            background: white;
            border: 2px solid;
        }
        .btn-configure-table:hover {
            background: #0a0a0a;
            border: 2px solid white;
        }
        .dragging {
            opacity: 0.5;
            background-color: #f0f0f0; /* Light gray background when dragging */
            border: 2px solid #aaa; /* Border to indicate dragging */
        }
        td.titleheading {
            cursor: move;
        }
        .freezeheading {
            font-size: 10px;
            text-transform: capitalize;
            position: sticky !important;
            top: -1px;
            background: white;
            z-index: 100;
            border-top: 1px solid #e1e6e9 !important;
            box-shadow: 1px 1px 1px #e1e6e9;
        }
        table.dataTable {
            border-collapse: separate !important;
        }
        .nested {
            display: none;
            padding-left: 20px;
        }
        .filter-container {
            background-color: #fff;
        }
        .filter-header {
            cursor: pointer;
            font-size: 10px;
        }
        .filter-content {
            display: none;
            position: absolute;
            background: white;
            width: 100%;
            border: 1px solid #e1e6e9;
            box-shadow: 2px 2px 2px #e1e6e9;
            max-height: 200px;
            overflow: scroll;
        }
        .date-filter {
            padding: 5px 5px;
            cursor: pointer;
            text-align: left;
            transition: background-color 0.3s;
            position: relative;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        .date-filter.month {
            padding: 5px 15px;
            border-left: 1px dashed;
        }
        .date-filter.day {
            padding: 5px 30px;
            border-left: 1px dashed;
        }
        .date-filter.month::before,.date-filter.day::before {
            content: " ";
            border-bottom: 1px dashed black;
            position: absolute;
            left: 0px;
            top: 12px;
        }
        .date-filter.month::before {
            width: 15px;
        }
        .date-filter.day::before {
            width: 29px;
        }
        .expand-icon {
            padding: 0.5px 5px;
            border: 1px solid #d9e2f6;
            margin-right: 2px;
            color: #24499c;
        }
        .nested {
            display: none;
            padding-left: 15px;
        }
        .nested:not(.year) {
            border-left: 1px dashed;
        }
        .cke-filter {
            margin-right: 5px;
        }
        .dropdown-menu.show {
            max-height: 250px;
            overflow-y: scroll;
        }
        /* For WebKit browsers */
        ::-webkit-scrollbar {
            width: 8px; /* Width of the scrollbar */
            height: 5px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888; /* Color of the scrollbar thumb */
            border-radius: 10px; /* Rounded corners */
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1; /* Background of the scrollbar track */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555; /* Color of the scrollbar thumb on hover */
        }
        /* For Firefox */
        .scrollable {
            scrollbar-width: thin; /* Makes scrollbar thin */
        }
        
        @media (min-width: 760px) {
        #tabledata_filter {
            position: fixed !important;
            right: 70px !important;
            text-align: right !important;
        }
    }
    </style>

</head>

<body>
    <?php
    $issues2=$issues=$issues3=[];
    ?>
    <input type="hidden" value="a2" id="tabselected">

    <!--Start of Change Log  -->
    <!-- Page Content -->
        <div class="content" style="width:100%;">
                <div class="block-header block-header-default">
                    <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                            <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                            Closed Issues</span>
                        <span style="font-weight:normal;font-size:15px;color: gray;">
                                Audit Management </span>
                    </div>
                    <div>
                        <table width="100%" style="padding-top:5px;padding-right:15px;">
                            <tr>
                                <td align="right">
                                    <a href="#" class="btn btn-sm btn-alt-secondary btn-configure-table"
                                    data-toggle="dropdown"
                                    data-placement="top">
                                        <span data-toggle="tooltip" title="Click here to configure column.">
                                            <img src="{{ asset('images/icons/filter_black.png') }}"
                                                data-hover="{{ asset('images/icons/filter_white.svg') }}"
                                                data-normal="{{ asset('images/icons/filter_black.png') }}"
                                                onmouseover="hover(this);"
                                                onmouseout="unhover(this);"
                                                width="24px" height="24px">
                                        </span>
                                    </a>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                                        @foreach($table_columns_non_active as $value)
                                            <a href="{{ route('audit.closed.register.table.column.activation',['id'=>$value->id]) }}"
                                            class="dropdown-item text-dark" style="font-size:12px;"
                                            id="table_column_value"><i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                        @endforeach
                                        @if($table_columns_names_enabled_by_user>0)
                                            <a href="{{ route('audit.closed.register.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                                                Reset to default table
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            <!-- Dynamic Table Full -->
            <div class="block block-rounded">
                <div class="block-content block-content-full">                 
                    <div class="scrollable" id="content">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                            <thead>
                                <tr>
                                    <!-- <td class="text-center titleheading freezeheading"></td> -->
                                        @foreach($table_columns_active as $value)
                                            @if($value->enabled_by_user!==0)
                                                <td data-orderable="false" class="text-center titleheading freezeheading"
                                                    @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                                                    @if($value->column_name == 'due date')style="min-width: 100px !important;" @endif
                                                    @if($value->column_name == 'added date')style="min-width: 120px !important;" @endif
                                                    @if($value->column_name == 'action reviewed date')style="min-width: 120px !important;" @endif
                                                    @if($value->column_name == 'hOD reviewed date')style="min-width: 120px !important;" @endif
                                                    @if($value->column_name == 'closure reviewed date')style="min-width: 120px !important;" @endif
                                                    onmouseover="return removecolumn('{{$value->id}}')">
                                                    {{$value->column_name}}
                                                </td>
                                            @else
                                                <td class="text-center titleheading freezeheading"
                                                @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                                                    @if($value->column_name == 'due date')style="min-width: 100px !important;" @endif
                                                    data-orderable="false">
                                                    {{$value->column_name}}                                      
                                                </td>
                                            @endif
                                        @endforeach
                                    <td class="text-center titleheading freezeheading"> Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @if(count($issues1))
                                @foreach ($issues1 as $issue)
                                @if($issue)
                                @php 
                                    $rr = $issue->id;
                                    $date = $issue->duedate;
                                    $today = Date('Y-m-d');
                                    $issue_docs_of_one_item = $issue->issueFiles;
                                    $action_docs_of_one_item = $issue->actionFiles;
                                    $dept = $issue->dept ? $issue->dept->departmentname : '';
                                    $responsible_names = isset($responsibles) ?
                                                $responsibles
                                                    ->whereIn('email', explode(',', $issue->responsibility))
                                                    ->pluck('name')->toArray()
                                                : [];
                                    $responsible_names = implode(',', $responsible_names);
                                    $issueType = $issue->typ ? $issue->typ : null;
                                @endphp
                                <tr>
                                    <!-- <td><input class="checkbox" type="checkbox" id="{{ $issue->id }}" name="id[]"></td> -->
                                    @foreach($table_columns_active as $value)
                                        @switch($value->column_name)
                                            @case('slno')
                                                <td class="text-center " style="font-size:10px;">{{ $no = $no + 1 }}</td>
                                                @break
                                            @case('id')
                                                <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                                    @if ($issue->status == 'open' && $today > $date)
                                                        <span class="status status-red" data-toggle="popover"
                                                            style="font-size:10px;"
                                                            data-placement="right" data-content="Due">
                                                            {{ $issue->issueid }}
                                                        </span>
                                                    @else
                                                        <span class="status status-success" style="font-size:10px;">
                                                            {{ $issue->issueid }}
                                                        </span>
                                                    @endif
                                                </td>
                                                @break
                                            @case('title')
                                                <td class=" font-w600 " style="font-size:10px;">
                                                    <a href="{{ route('user.actionsystem.detailopen', $issue->id) }}">{{ $issue->title }}</a>
                                                </td>
                                                @break
                                            @case('type')
                                                <td class="text-center" style="font-size:10px;">
                                                    @if ($issueType)
                                                    <span class="status"
                                                        style="font-size:10px;color:{{ $issueType->text_color }};background-color:{{ $issueType->background_color }}">
                                                        {{ $issueType->name }}
                                                    </span>
                                                    @endif
                                                </td>
                                                @break
                                            @case('department')
                                                <td class="text-center" style="font-size:10px;">
                                                    {{ $dept }}
                                                </td>
                                                @break
                                            @case('responsibility')
                                                <td class="text-center" style="font-size:10px;">
                                                    {{ $issue->responsible ? $issue->responsible->name : '-' }}
                                                    <div class="d-none">{{ $issue->responsible ? $issue->responsible->name . '_f' : '-' }}</div>
                                                </td>
                                                @break
                                            @case('due date')
                                                <td class="text-center" style="font-size:10px;">
                                                    {{ date('d-m-Y', strtotime($issue->duedate)) }}
                                                </td>
                                                @break
                                            @case('raisedby')
                                                @if(in_array('raisedby',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{$issue->postedBy ? $issue->postedBy->name : '-'}}
                                                    </td>
                                                @endif
                                                @break
                                            @case('division')
                                                @if(in_array('division',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->division ? $issue->division->name : '-' }}
                                                    </td>
                                                @endif
                                                @break
                                            @case('subdivision')
                                                @if(in_array('subdivision',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->area ? $issue->area->name : '-' }}
                                                    </td>
                                                @endif
                                                @break
                                            @case('email remainder for action')
                                                @if(in_array('email remainder for action',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{$issue->frequency ? $issue->frequency : '-'}}
                                                    </td>
                                                @endif
                                                @break
                                            @case('added date')
                                                @if(in_array('added date',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ date('d-m-Y', strtotime($issue->created_at)) }}
                                                    </td>
                                                @endif
                                                @break
                                            @case('action reviewed by')
                                                @if(in_array('action reviewed by',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{$issue->reviewedBy ? $issue->reviewedBy->name : '-'}}
                                                        <div class="d-none">{{ $issue->reviewedBy ? $issue->reviewedBy->name . '_rw' : '-' }}</div>
                                                    </td>
                                                @endif
                                                @break
                                            @case('action reviewed date')
                                                @if(in_array('action reviewed date',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)) : '' }}
                                                    </td>
                                                @endif
                                                @break
                                            @case('hOD reviewed by')
                                                @if(in_array('hOD reviewed by',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->hodreviewedBy ? $issue->hodreviewedBy->name : '-'}}
                                                        <div class="d-none">{{ $issue->hodreviewedBy ? $issue->hodreviewedBy->name . '_hrw' : '-' }}</div>
                                                    </td>
                                                @endif
                                                @break
                                            @case('hOD reviewed date')
                                                @if(in_array('hOD reviewed date',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)) : '' }}
                                                    </td>
                                                @endif
                                                @break
                                            @case('closure reviewed by')
                                                @if(in_array('closure reviewed by',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->closedBy ? $issue->closedBy->name : '-'}}
                                                        <div class="d-none">{{ $issue->closedBy ? $issue->closedBy->name . '_cb' : '-' }}</div>
                                                    </td>
                                                @endif
                                                @break
                                            @case('closure reviewed date')
                                                @if(in_array('closure reviewed date',$table_columns_names_active))
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{ $issue->closed ? date('d-m-Y', strtotime($issue->closed)) : '' }}
                                                    </td>
                                                @endif
                                                @break
                                            @case('process stage')
                                                <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                                    @if ($issue->status == 'closed' && $issue->closed <= $date)
                                                     <span class="status status-success" style="font-size:10px;">Closed</span>
                                                     <div class="d-none">{{ 'e_'. 'Closed'.'_f'  }}</div>
                                                    @elseif($issue->status == 'closed' && $issue->closed > $date)
                                                    <span class="status status-warning" style="font-size:10px;">
                                                        Closed after due
                                                    </span>
                                                    <div class="d-none">{{ 'e_'. 'Closed after due'.'_f'  }}</div>
                                                    @else
                                                    <span class="status status-blue" style="font-size:10px;">{{ $issue->status }}</span>
                                                    @endif
                                                </td>
                                                @break
                                            @case('action status')
                                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                                    @if($issue->processstage == "Awaiting actions" || $issue->processstage == 'HOD Review awaiting' || $issue->processstage == 'HOD Rejected')
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0">0%</p>
                                                    @elseif($issue->impl_status=="Progress-25%" || $issue->processstage=="Action Reviewed, OK" && $issue->impl_status!="Partially Completed-50%" )
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0">25%</p>
                                                    @elseif($issue->impl_status=="Partially Completed-50%")
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0">50%</p>
                                                    @elseif($issue->impl_status=="Completed-100%")
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0">100%</p>
                                                    @elseif($issue->processstage=="Action Reviewed, Not OK")
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0">10%</p>
                                                    @elseif($issue->processstage=="Actions Proposed" && $issue->impl_status=="Not Started-0%")
                                                        <div class="progress mb-1" style="height: 5px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0">0%</p>
                                                    @endif
                                                </td>
                                                @break
                                            @default
                                                @break
                                        @endswitch
                                @endforeach
                                    <!-- <div class="dropdown font-size-sm">
                                        <td class="font-w600 font-size-sm">
                                            <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">

                                                @if(in_array(Auth::user()->email,$mr) && $issue->processstage == "Accomplished")
                                                <a href="{{url('/user/actionsystem/reopen?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Reopen</a>
                                                @endif
                                            </div>
                                        </td>
                                    </div> -->
                                    <td class="font-w600 font-size-sm">
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                                    <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                                @if(in_array(Auth::user()->email,$mr) && $issue->processstage == "Accomplished")
                                                  <a href="{{url('/user/actionsystem/reopen?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Reopen</a>
                                                @endif
                                            </div>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $issue->id }}" data-toggle="modal" data-placement="top" title="View" data-target="#viewModalDetails{{ $issue->id}}">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                             </a>
                                        </div>
                                    
                                       
                                        <div id="viewModalDetails{{ $issue->id }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Issue id</th>
                                                            <td>{{$issue->issueid}}</td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th>Category</th>
                                                            <td>{{$issue->category}}</td>
                                                        </tr> -->
                                                        <tr>
                                                            <th>Department</th>
                                                            <td>{{$dept}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Type</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @php
                                                                $switch = 0;
                                                                $type = App\Type::where('order', $issue->type)->first();
                                                                $switch = $type ? 1 : 0;
                                                                @endphp
                                                                <span class="status" style="font-size:10px;color:{{ $switch ? $type->text_color : '#25499c'}};background-color:{{ $switch ? $type->background_color : '#d9e2f6'}}">{{ $switch ? $type->name : '' }}</span>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <th>Title</th>
                                                            <td>{{$issue->title}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Observation</th>
                                                            <td><?php echo nl2br($issue->observation )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Responsibility</th>
                                                            <td>{{$issue->responsibility}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Agreed action</th>
                                                            <td><?php echo nl2br($issue->agreedaction)?></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Due date</th>
                                                            <td>{{date('M/d/Y',strtotime($issue->duedate))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Correction</th>
                                                            <td><?php echo nl2br($issue->correction )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Root cause</th>
                                                            <td><?php echo nl2br($issue->rootcause )?></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Corrective action</th>
                                                            <td><?php echo nl2br($issue->correctiveaction )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Attachments (issues)</th>
                                                            <td>
                                                                <div class="col-sm-8">
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                                    <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments</h3>
                                                                                </div>
                                                                                @php
                                                                                    $n = 0;                                               
                                                                                    $issuefiles = DB::table('multipleissues')
                                                                                                                ->where('id', $issue->id)
                                                                                                                ->whereNotNull('issue_file')
                                                                                                                ->where('issue_file','!=','')                   
                                                                                                                ->get();
                                                                                @endphp
                                                                                @if(count($issuefiles) > 0 )
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th>S.No</th>
                                                                                                <th>Attachments</th>
                                                                                                <th></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $sno=0; ?>       
                                                                                        @foreach($issuefiles as $value1)                                             
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:10px;">{{ $sno=$sno+1}}</td>
                                                                                                <td style="font-size:15px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </table>
                                                                                @else
                                                                                    <div class="row justify-content-center">
                                                                                        <h4 class="text-danger">No Files</h4>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Attachments (action)</th>
                                                            <td>
                                                                <div class="col-sm-8">
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal6{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                                    <div id="viewModal6{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments</h3>
                                                                                </div>
                                                                                @php
                                                                                    $actionfiles = DB::table('multipleactions')
                                                                                                        ->where('id', $issue->id)
                                                                                                        ->whereNotNull('action_file')
                                                                                                        ->where('action_file', '!=', '')
                                                                                                        ->get();
                                                                                @endphp
                                                                                @if(count($actionfiles) > 0 )
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th>S.No</th>
                                                                                                <th>Attachments</th>
                                                                                                <th></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php $n=0; ?>                                
                                                                                        @foreach($actionfiles as $value2)                                                                                  
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:10px;">{{ $n=$n+1}}</td>
                                                                                                <td style="font-size:15px;width: 100%;">{{ $value2->action_file}}</td>
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2->action_file)}}" download="{{$value2->action_file}}">Download</a></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </table>
                                                                                @else
                                                                                    <div class="row justify-content-center">
                                                                                      <h4 class="text-danger">No Files</h4>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Remarks</th>
                                                            <td><?php echo nl2br($issue->remarks)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Review action remarks</th>
                                                            <td><?php echo nl2br($issue->action_remarks)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Posted by & Date</th>
                                                            <td>
                                                                <!-- {{$issue->postedby}}&nbsp; {{ $issue->postedBy ? $issue->postedBy->name : ''}} -->
                                                                {{$issue->postedBy ? $issue->postedBy->name : ''}} ({{ $issue->postedby}}) ,{{date('d-m-Y', strtotime($issue->created_at))}}
                                                            </td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th>Added date</th>
                                                            <td>{{$issue->created_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Last reviewed</th>
                                                            <td>{{$issue->updated_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Approved by</th>
                                                            <td>
                                                                {{$issue->approvedby}}&nbsp; {{ $issue->approvedBy ? $issue->approvedBy->name : ''}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Issue closed by</th>
                                                            <td>
                                                                {{$issue->closedby}}&nbsp; {{ $issue->closedBy ? $issue->closedBy->name : ''}}
                                                            </td>
                                                        </tr> -->
                                                        <tr>
                                                        <th>Action Reviewed by & Date</th>
                                                        <td style="font-weight:lighter;">
                                                         @if($issue->reviewedby !=null)
                                                           {{ $issue->reviewedBy ? $issue->reviewedBy->name : '--' }} ({{$issue->reviewedby}}) , 
                                                           {{$issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)) : ''}}
                                                         @else
                                                         --
                                                         @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Hod Reviewed by & Date</th>
                                                        <td style="font-weight:lighter;">
                                                         @if($issue->hod_reviewed_by_date != NULL)
                                                           {{ $issue->hodreviewedBy ? $issue->hodreviewedBy->name : '--' }} 
                                                           ({{ $issue->hodreviewedBy?$issue->hodreviewedBy->employeeid:'--' }}) , 
                                                           {{$issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)) : ''}}
                                                         @else
                                                         --
                                                         @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Issue Closed by & Date</th>
                                                        <td style="font-weight:lighter;">
                                                           @if($issue->closedby !=null)
                                                            {{ $issue->closedBy ? $issue->closedBy->name : '-' }} 
                                                            ({{ $issue->closedby}}) ,
                                                            {{ $issue->closed ?date('d-m-Y', strtotime($issue->closed)) : '' }}
                                                           @else
                                                              --
                                                           @endif
                                                        </td>
                                                    </tr>
                                                    </table>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END Dynamic Table Full -->
        </div>
    <!-- Column Header Dropdown Menu  starts-->
        <div id="dropdownMenucol">
            <form id="remove_column_ajax" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="remove_column_id" id="remove_column_id"/>

                <button type="submit" class="btn btn-sm btn-danger" style="font-size:10px;">
                    <i class="fa fa-fw fa-times"></i> Remove field
                </button>
            </form>
        </div>
    <!-- Ends Column Header Dropdown Menu--> 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        function hover(element) {
            element.setAttribute('src', element.getAttribute('data-hover'));
        }

        function unhover(element) {
            element.setAttribute('src', element.getAttribute('data-normal'));
        }

        function removecolumn(id){
            $('#remove_column_id').val(id);

        }
        //-----remove datatable column  starts----//
        $(document).ready(function() {
            $('#remove_column_ajax').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/user/audit/closed/register-table/removecolumn',
                    data: formData
                    ,success: function(response) {
                        if(response.status == 200){
                            window.location.reload();
                        }
                    }
                    ,error: function(err) {
                        if(err.status === 500) {
                            $('#columnremovaldanger').css('display', 'block');
                        }
                        else if (err.status === 422) {
                            var error_text='';
                            $.each(err.responseJSON.errors, function(i) {
                                $.each(err.responseJSON.errors[i], function(key, val) {
                                    error_text += '<li style="font-size:12px;">' + val + '</li>';
                                });
                            });
                            $('#columnremovaldangernotremoved').html(error_text);
                            $('#columnremovaldanger').hide();
                        }
                    }
                });
            });
        });

        //---------ends remove datatable column  ----//
    </script>
     <script src="{{ asset('js/datatable_extension_audit_closed.js') }}"></script>
    <script>
        const auditclosedcolumndetails = <?php echo $table_columns_active;?>;
        enableDragDrop('#tabledata', '{{ route("audit.closed.register.table.column.updateposition") }}', function () {
            location.reload()
        });

        if(auditclosedcolumndetails.length > 0) {
            const $preInitData = {
                'type': {
                    'options': "{{ $alltypenames }}",
                    'display_name': "Type",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'option': {},
                },
                'department': {
                    'options': "{{ $alldeptnames }}",
                    'display_name': "Department",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'option': {},
                },
                'division': {
                    'options': "{{ $alldivisionnames }}",
                    'display_name': "Division",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {},
                },
                'subdivision': {
                    'options': "{{ $allsubdivisionnames }}",
                    'display_name': "Subdivision",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {},
                },
                'raisedby': {
                    'options': "{{ $allraisedbynames }}",
                    'display_name': "Raisedby",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {},
                },
                'responsibility': {
                    'options': "{{ $allusernames }}",
                    'display_name': "Responsibility",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'option': {'filter_postfix' : '_f'},
                },
                'due date': {
                    'options': "{{ $dueDateYears }}",
                    'display_name': "Due Date",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'option': {'is_date_filter' : true},
                },
                'process stage': {
                    'options': "{{ $allpstages }}",
                    'display_name': "Process Stage",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'option': {'filter_postfix' : '_f'},
                },
                'email remainder for action': {
                    'options': '',
                    'display_name': '',
                    'is_enable_remove': true,
                    'is_enable_filter': false,
                    'option': {},
                },
                'action status': {
                    'options': "",
                    'display_name': "Action status",
                    'is_enable_remove': false,
                    'is_enable_filter': false,
                    'option': {},
                },
                'added date': {
                    'options': "{{ $addDateYears }}",
                    'display_name': "Added Date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'is_date_filter' : true},
                },
                'action reviewed by': {
                    'options': "{{ $allreviewernames }}",
                    'display_name': "Action Reviewed By",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'filter_postfix' : '_rw'},
                },
                'action reviewed date': {
                    'options': "{{ $actionReviewedDateYears }}",
                    'display_name': "Action reviewed date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'is_date_filter' : true},
                },
                'hOD reviewed by': {
                    'options': "{{ $allhodreviewernames }}",
                    'display_name': "HOD reviewed by",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'filter_postfix' : '_hrw'},
                },
                'hOD reviewed date': {
                    'options': "{{ $hodReviewedDateYears }}",
                    'display_name': "HOD reviewed date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'is_date_filter' : true},
                },
                'closure reviewed by': {
                    'options': "{{ $allcloseBynames }}",
                    'display_name': "Closure reviewed by",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'filter_postfix' : '_cb'},
                },
                'closure reviewed date': {
                    'options': "{{ $issueClosedDateYears }}",
                    'display_name': "Closure reviewed date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'option': {'is_date_filter' : true},
                },
            };
            for (let index = 0; index < auditclosedcolumndetails.length; index++) {
                if($preInitData[auditclosedcolumndetails[index]['column_name']]) {
                    const $columnData = $preInitData[auditclosedcolumndetails[index]['column_name']];
                    const $columnIndex = getColumnIndexByHeaderName('#tabledata', auditclosedcolumndetails[index]['column_name'])

                    if ($columnData['is_enable_remove']) {
                        enableRemoveColumn('#tabledata', auditclosedcolumndetails[index]['column_name'], $columnIndex)
                    }
                    if ($columnData['is_enable_filter'] && $columnIndex !== -1) {
                        enableHeaderFilter(
                            '#tabledata',
                            auditclosedcolumndetails[index]['column_name'].replace(/ /g, '-'),
                            $columnData['options'],
                            $columnData['display_name'],
                            $columnIndex,
                            $columnData['option']
                        )
                    }
                }
            }
        }
    </script>
        

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
        <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="block block-rounded block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Options</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="row gutters-tiny">

                                <div class="col-6">
                                    <!-- Products -->
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Register
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/folders">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-square fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Closed Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open NCRs
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/actionsystem/analysis">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>


                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Audit Schedule Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- </div>-->



        </div>

        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/oneui.core.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/oneui.app.min.js')}}"></script>
        <script src="{{ asset( '02 - OneUI Source (HTML)/src/assets/js/pages/be_tables_datatables.min.js')}}"></script>
        <script>
            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 240);

        </script>
        <!-- Page JS Plugins -->
        @include('exporting.foractionsystem')
</body>
</html>
@endsection
