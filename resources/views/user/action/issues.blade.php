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
            <form method="post" action="{{ url('/user/convert/file/' . 'tvalue1') }}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf
                </button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
                <input type="hidden" name="paper" value="{{ count($table_columns_active) > 18 ? 'a3' : 'a4' }}">
                <input type="hidden" name="orientation" id="orientation" value="{{ count($table_columns_active) <= 12 ? 'portrait' : 'landscape' }}">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
            </form>
        </li>
        <li>
            <button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel
            </button>
        </li>
        <li>
            <button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word
            </button>
        </li>
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
        @media screen and (max-width: 760px),
        (min-device-width: 768px) and (max-device-width: 1366px) {
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                overflow-y: hidden;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
            }

            .pb-1,
            .py-1 {
                /*padding-bottom: .25rem!important;*/
                bottom: 0;
                position: relative;
                display: inline-block;
                clear: both;
            }
        }
        .headingselect {
            white-space: break-spaces;
            line-height: 1rem;
        }
        .status{
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
        td.titleheading.move {
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
            scrollbar-color: #888 transparent; /* Thumb color and track color */
        }
        .dragging.move {
            border: 2px solid #28a745 !important; /* Blue border for the dragging row */
            background-color: #f8f9fa !important; /* Light background for emphasis */
        }

        .drag-over.move {
            border-left: 2px dashed #28a745 !important;
            background-color: #d4edda !important;
        }
        .drag-over.move .filter-container, .drag-over.move .headingselect {
            background-color: #d4edda !important;
        }
    </style>
</head>

<body>
    <?php $issues2 = $issues1 = $issues3 = []; ?>
    <input type="hidden" value="a1" id="tabselected">

    <div class="alert alert-danger" id="columnremovaldangernotremoved" style="display:none"></div>
    <div class="content" style="width:100%">
        <div class="block-header block-header-default">
            <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                       <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                         Register</span>
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
                                    <a href="{{ route('audit.register.table.column.activation',['id'=>$value->id]) }}"
                                       class="dropdown-item text-dark" style="font-size:12px;"
                                       id="table_column_value"><i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                @endforeach
                                @if($table_columns_names_enabled_by_user>0)
                                    <a href="{{ route('audit.register.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                                        Reset to default table
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <thead>
                        <tr>
                            <td class="text-center titleheading freezeheading"></td>
                            @foreach($table_columns_active as $value)
                                @if($value->enabled_by_user!==0)
                                    <td data-orderable="false" class="text-center titleheading freezeheading {{ $value->is_dragdropable ? 'move': '' }}"
                                        @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                                        @if($value->column_name == 'added date')style="width: 8%" @endif
                                        onmouseover="return removecolumn('{{$value->id}}')">
                                        {{$value->column_name}}
                                    </td>
                                @else
                                    <td class="text-center titleheading freezeheading {{ $value->is_dragdropable ? 'move': '' }}"
                                        @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                                        @if($value->column_name == 'due date')style="width: 8%" @endif
                                        data-orderable="false">
                                        {{$value->column_name}}
                                    </td>
                                @endif
                            @endforeach
                            <td class="text-center titleheading freezeheading"> Actions</td>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0; ?>
                            @foreach ($issues as $issue)
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
                                <td><input class="checkbox" type="checkbox" id="{{ $issue->id }}" name="id[]"></td>
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
                                                @if ($issue->processstage == 'Awaiting actions')
                                                    <span class="status status-secondary" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                @elseif($issue->processstage=='Actions Proposed')
                                                    <span class="status status-flat" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                @elseif($issue->processstage=='Action Reviewed, OK')
                                                    <span class="status status-success" style="font-size:10px;">Action Approved</span>
                                                @elseif($issue->processstage=='Action Reviewed, Not OK')
                                                    <span class="status status-red"  style="font-size:10px;">Action Rejected</span>
                                                @elseif($issue->processstage=='Need More Action')
                                                    <span class="status status-warning" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                @elseif($issue->processstage=='Actions Completed')
                                                    <span class="status status-blue" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                @elseif($issue->processstage=='HOD Review awaiting')
                                                    <span class="status status-warning" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                @elseif($issue->processstage=='HOD Rejected')
                                                    <span class="status status-red" style="font-size:10px;">{{ $issue->processstage }}</span>
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
                                <td class="font-w600 font-size-sm">
                                   <div class="btn-group m-auto d-flex justify-content-center">
                                       <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                           @foreach ($auditors as $rep)
                                                @if (Auth::user()->email == $rep)
                                                   <a href="#" class="dropdown-item" style="font-size:10px;" onclick="theAlert({{ $issue->id }})">Add attachment</a>
                                                @if(Gate::allows('audit-responsibility-change'))
                                                   <a href="{{ url('/user/actionsystem/editresp?val=' . $rr) }}" class="dropdown-item" style="font-size:10px;">Responsibility
                                                    change</a>
                                                @endif
                                                @if (Auth::user()->email == $issue->hod)
                                                    <a href="" class="dropdown-item" style="font-size:10px;">

                                                    </a>
                                                @endif
                                                @if ($issue->processstage == 'Actions Proposed')
                                                <a href="{{ url('/user/actionsystem/reviewstatus?val=' . $rr) }}" class="dropdown-item" style="font-size:10px;">Review
                                                    Action</a>
                                                @elseif($issue->processstage=="Actions Completed" OR
                                                $issue->impl_status=="Completed-100%")
                                                <a href="{{ url('/user/actionsystem/closure?val=' . $rr) }}" class="dropdown-item" style="font-size:10px;">Closure
                                                    Review</a>
                                                @endif
                                                @endif
                                            @endforeach
                                            @if ($issue->hodUser && Auth::user()->email == $issue->hodUser->email && $issue->processstage == 'HOD Review awaiting')
                                               <a href="{{ route('user.actionsystem.hodreviewform', $issue->id) }}"
                                                  class="dropdown-item" style="font-size:10px;">HOD review</a>
                                            @endif
                                        </div>
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $issue->id }}" data-toggle="modal" data-placement="top" title="View" data-target="#viewModalDetails{{ $issue->id}}">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                             </a>
                                   </div>


                                    <div id="viewModalDetails{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="padding:0rem;">
                                                    <h2 class="modal-title" style="padding:1rem;">Detailed View</h2>
                                                    <div class="p-1 text-center rounded-top" style="padding:1rem">
                                                        @php
                                                           $resp_user_image = $issue->responsible ? $issue->responsible->profile_picture : '';
                                                           $posted_user_image = $issue->postedBy ? $issue->postedBy->profile_picture: '';
                                                        @endphp
                                                        @if($resp_user_image && File::exists(public_path('/storage/profile/'.$resp_user_image)))
                                                        <img class="img-avatar img-avatar48" src="{{ asset('/storage/profile/'.$resp_user_image) }}">
                                                        @else
                                                        <img class="img-avatar img-avatar48" src=" {{asset('/images/avatar10.jpg')}}" />
                                                        @endif

                                                        @if($posted_user_image && File::exists(public_path('/storage/profile/'.$posted_user_image)))
                                                        <img class="img-avatar img-avatar48" src="{{ asset('/storage/profile/'.$posted_user_image) }}">
                                                        @else
                                                        <img class="img-avatar img-avatar48" src=" {{asset('/images/avatar10.jpg')}}" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Issue id</th>
                                                        <td>
                                                            <span class="badge badge-danger">
                                                             {{ $issue->issueid }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <th>Category</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                        {{ $issue->category }}
                                                        </td>
                                                    </tr> -->
                                                    <tr>
                                                        <th>Department</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                          {{ $dept }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Type</th>
                                                        <td>
                                                        @php
                                                        $switch = 0;
                                                        $type = $issue->typ;
                                                        $switch = $type ? 1 : 0;
                                                        //$type_name = $type_name ? $type_name->name : '';
                                                        @endphp
                                                        {{-- @if ($issue->type == 3)
                                                        <span class="status status-success" style="font-size:10px;">{{ $type_name }}</span>
                                                        @elseif($issue->type == 2)
                                                        <span class="status status-warning" style="font-size:10px;">{{ $issue->type }}</span>
                                                        @elseif($issue->type == 1)
                                                        <span class="status status-red" style="font-size:10px;">{{ $issue->type }}</span>
                                                        @else --}}
                                                        <span class="status" style="font-size:10px;color:{{ $switch ? $type->text_color : '#25499c'}};background-color:{{ $switch ? $type->background_color : '#d9e2f6'}}">{{ $switch ? $type->name : '' }}</span>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Process stage</th>
                                                        <!-- <td>
                                                            @if($issue->processstage == 'Awaiting actions')
                                                            <span class="badge badge-secondary">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='Actions Proposed')
                                                            <span class="badge badge bg-flat" style="color:white">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='Action Reviewed, OK')
                                                            <span class="badge badge-success">Action Approved</span>
                                                            @elseif($issue->processstage=='Action Reviewed, Not OK')
                                                            <span class="badge badge-danger">Action Rejected</span>
                                                            @elseif($issue->processstage=='Need More Action')
                                                            <span class="badge badge-warning">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='Actions Completed')
                                                            <span class="badge badge-info">{{ $issue->processstage }}</span>
                                                            @endif
                                                        </td> -->
                                                        <td>
                                                            @if ($issue->processstage == 'Awaiting actions')
                                                                <span class="status status-secondary" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='Actions Proposed')
                                                                <span class="status status-flat" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='Action Reviewed, OK')
                                                                <span class="status status-success" style="font-size:10px;">Action Approved</span>
                                                            @elseif($issue->processstage=='Action Reviewed, Not OK')
                                                                <span class="status status-red"  style="font-size:10px;">Action Rejected</span>
                                                            @elseif($issue->processstage=='Need More Action')
                                                                <span class="status status-warning" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='Actions Completed')
                                                                <span class="status status-blue" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='HOD Review awaiting')
                                                                <span class="status status-warning" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                            @elseif($issue->processstage=='HOD Rejected')
                                                                <span class="status status-red" style="font-size:10px;">{{ $issue->processstage }}</span>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <th>Title</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                           {{ $issue->title }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Observation</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                           {{ $issue->observation }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Potential risks</th>
                                                        <td>
                                                            @if($issue->related_risks)
                                                                @php
                                                                    $relatedRisks = json_decode($issue->related_risks, true);
                                                                @endphp
                                                                @if(is_array($relatedRisks) || is_object($relatedRisks))
                                                                    @foreach($relatedRisks as $riskId => $riskItem)
                                                                        <span class="badge badge-info">{{ $riskItem }}</span>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Responsibility</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                        {{ $issue->responsible ? $issue->responsible->name : '-' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Agreed action</th>
                                                        <td style="font-weight:lighter;">{{ $issue->agreedaction }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Due date</th>
                                                        <td style="font-weight:lighter;">{{ date('d-m-Y', strtotime($issue->duedate)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Correction</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                          {{ $issue->correction }}
                                                         </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Root cause</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                          {{ $issue->rootcause }}
                                                       </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Corrective action</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;">
                                                          {{ $issue->correctiveaction }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Attachments (issues)</th>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                            <div id="viewModal4{{ $issue->id }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h3 class="modal-title">Attachments</h3>
                                                                        </div>
                                                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                            @if(count($issue_docs_of_one_item) > 0)
                                                                            <div class="row justify-content-center">
                                                                                <thead>
                                                                                    <tr class="table-secondary">
                                                                                        <th>S.No</th>
                                                                                        <th>Attachments</th>
                                                                                        <!-- <th>Image</th> -->
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <?php $sno = 0;?>

                                                                                @foreach($issue_docs_of_one_item as $issue_item)
                                                                                @if( $issue_item->issue_file != null || $issue_item->issue_file != "NULL")
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="font-size:10px;">
                                                                                                {{ $sno = $sno + 1 }}
                                                                                            </td>
                                                                                            <td style="font-size:15px;width: 100%;">
                                                                                                {{ $issue_item->issue_file}}
                                                                                            </td>
                                                                                            <!-- <td>
                                                                                                <img src="/storage/issue_files/{{ $issue_item->issue_file }}" width="100px" height="100px" />
                                                                                            </td> -->
                                                                                            @if(File::exists(public_path('/storage/issue_files/'.$issue_item->issue_file)))
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$issue_item->issue_file)}}" download="{{$issue_item->issue_file}}">Download</a></td>
                                                                                            @else
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="#">Download</a></td>
                                                                                            @endif
                                                                                        </tr>
                                                                                    </tbody>
                                                                                @else
                                                                                <div class="row justify-content-center">
                                                                                    <h2>No Files</h2>
                                                                                </div>
                                                                                @endif
                                                                                @endforeach

                                                                            </div>
                                                                            @else
                                                                            <div class="row justify-content-center">
                                                                                <h2>No Files</h2>
                                                                            </div>
                                                                            @endif

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Attachments (action)</th>
                                                        <td>
                                                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal5{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                            <div id="viewModal5{{ $rr }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h3 class="modal-title">Attachments</h3>
                                                                        </div>
                                                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                            @if(count($action_docs_of_one_item) > 0)
                                                                            <div class="row justify-content-center">
                                                                                <thead>
                                                                                    <tr class="table-secondary">
                                                                                        <th>S.No</th>
                                                                                        <th>Attachments</th>
                                                                                        <!-- <th>Image</th> -->
                                                                                        <th></th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <?php $sno = 0; ?>
                                                                                @foreach($action_docs_of_one_item as $action_item)
                                                                                @if( $action_item->action_file != null || $action_item->action_file != "NULL")
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="font-size:10px;">
                                                                                            {{ $sno = $sno + 1 }}
                                                                                        </td>
                                                                                        <td style="font-size:15px;width: 100%;">
                                                                                            {{ $action_item->action_file}}
                                                                                        </td>
                                                                                        <!-- <td>
                                                                                            <img src="/storage/issue_files/{{ $action_item->action_file }}" width="100px" height="100px" />
                                                                                        </td> -->
                                                                                        @if(File::exists(public_path('/storage/issue_files/'.$action_item->action_file)))
                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$action_item->action_file)}}" download="{{$action_item->action_file}}">Download</a></td>
                                                                                        @else
                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="#">Download</a></td>
                                                                                        @endif
                                                                                    </tr>
                                                                                </tbody>
                                                                                @else
                                                                                <div class="row justify-content-center">
                                                                                    <h2>No Files</h2>
                                                                                </div>
                                                                                @endif

                                                                                @endforeach
                                                                            </div>
                                                                            @else
                                                                            <div class="row justify-content-center">
                                                                                <h2>No Files</h2>
                                                                            </div>

                                                                            @endif
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Closure Remarks</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;" class="text-danger">
                                                          {{ $issue->remarks}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Review action remarks</th>
                                                        <td style="text-transform:capitalize;font-weight:lighter;"class="text-danger">
                                                        {{ $issue->action_remarks}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Posted by & Date</th>
                                                        <td style="font-weight:lighter;">{{$issue->postedBy ? $issue->postedBy->name : '--'}} ({{ $issue->postedby}}) ,{{date('d-m-Y', strtotime($issue->created_at))}}</td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <th>Added date</th>
                                                        <td style="font-weight:lighter;">{{ date('d-m-Y', strtotime($issue->created_at)) }}</td>
                                                    </tr> -->
                                                    <!-- <tr>
                                                        <th>Last reviewed</th>

                                                        <td style="font-weight:lighter;">{{ date('d-m-Y', strtotime($issue->updated_at)) }}</td>
                                                    </tr> -->
                                                    <tr>
                                                        <th>Action Reviewed by & Date</th>
                                                        <td style="font-weight:lighter;">
                                                         @if($issue->reviewedby !=null)
                                                           {{ $issue->reviewedBy ? $issue->reviewedBy->name : '--' }} ({{$issue->reviewedby}}) , 
                                                           {{$issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)) : '--'}}
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
                                                           {{$issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)) : '--'}}
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
                                            <button type="button" class="btn btn-dark" data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    {{-- --------delete request button for doc controllers --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        function hover(element) {
            element.setAttribute('src', element.getAttribute('data-hover'));
        }

        function unhover(element) {
            element.setAttribute('src', element.getAttribute('data-normal'));
        }

        function theAlert(id) {
            let url = '{{ route("saveActionAttachment", ":id") }}';
            url = url.replace(':id', id);
            Swal.fire({
                title: 'Select files'
                , html: '<label>Action Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>' +
                    '<label>Issue Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>'
                , showCancelButton: true
                , confirmButtonText: 'Upload'
                , onBeforeOpen: () => {
                    $(".swal2-file").change(function() {
                        var reader = new FileReader();
                        reader.readAsDataURL(this.files);
                    });
                }
            }).then((file) => {
                if (file.value) {
                    var formData = new FormData();
                    var files = $('.swal2-file')[0].files;
                    var issueFiles = $('.swal2-file')[1].files;
                    $.each(files, function(i, x) {
                        formData.append('fileToUpload[]', x);
                    });
                    $.each(issueFiles, function(i, x) {
                        formData.append('fileToUpload1[]', x);
                    });
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                        , method: 'post'
                        , url: url
                        , data: formData
                        , processData: false
                        , contentType: false
                        , success: function(resp) {
                            Swal.fire({
                                title: 'Uploaded'
                                , text: 'Your files have been uploaded'
                                , icon: 'success'
                            }).then((result) => {
                                // Reload the Page
                                location.reload();
                            });

                        }
                        , error: function() {
                            Swal.fire({
                                title: 'Oops'
                                , text: 'Something went wrong!'
                                , icon: 'error'
                            })
                        }
                    })
                }
            })


        }

        $("#del").click(function() {
            var dataArr = new Array();
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    $.ajax({
                        type: 'get'
                        , url: "{{ route('deleteaction') }}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            window.location.href = "{{ url('/user/auditmgmt') }}";
                        }
                        , error: function(response) {
                            alert(errResponse);
                        }
                    });
                });
            } else {
                alert('No Record Selected');
            }
        });

        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }

        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>
    {{-- ---------edit  button ---------- --}}
    <script>
        $("#edit").click(function() {
            var dataArr = new Array();
            var group = new Array();
            var d;
            var all;
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    all = "" + dataArr;
                    group.push(all); // push all checkbox values into an array
                });
                $.ajax({
                    type: 'get'
                    , url: "{{ route('editaction') }}" + "?val=" + (all)
                    , success: function(response) {
                        window.location.href = "{{ route('editaction') }}" + "?val=" + (all);
                    }
                    , error: function(response) {
                        alert(errResponse);
                    }
                });
            } else {
                alert('No Record Selected');
            }
        });

    </script>
    <script>
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
                    url: '/user/audit/register-table/removecolumn',
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
    <script src="{{ asset('js/datatable_extension_openncrs.js') }}"></script>
    <script>
        const correctivecolumndetails = <?php echo $table_columns_active;?>;
        enableDragDrop('#tabledata', '{{ route('audit.register.table.column.updateposition') }}', function () {
            location.reload()
        });

        if(correctivecolumndetails.length > 0) {
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
                    'option': {},
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
            for (let index = 0; index < correctivecolumndetails.length; index++) {
                if($preInitData[correctivecolumndetails[index]['column_name']]) {
                    const $columnData = $preInitData[correctivecolumndetails[index]['column_name']];
                    const $columnIndex = getColumnIndexByHeaderName('#tabledata', correctivecolumndetails[index]['column_name'])

                    if ($columnData['is_enable_remove']) {
                        enableRemoveColumn('#tabledata', correctivecolumndetails[index]['column_name'], $columnIndex)
                    }
                    if ($columnData['is_enable_filter'] && $columnIndex !== -1) {
                        enableHeaderFilter(
                            '#tabledata',
                            correctivecolumndetails[index]['column_name'].replace(/ /g, '-'),
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

    <!-- END Dynamic Table Full -->




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
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Auditors List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule Analysis
                                        </p>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal" id="modal-learn-module" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="m-dialog" role="document">
            <div class="modal-content">

                <div class="m-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- 16:9 aspect ratio -->

                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ asset('media/videos/hero_sunrise.mp4') }}" id="video" allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @section('sideitem')
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual width-150 video-btn" id="learn_btn" data-toggle="modal" data-src="{{ asset('media/videos/hero_sunrise.mp4') }}" data-target="#modal-learn-module">
            <i class="fa fa-1x fa-video" style="color:grey;"></i><span id="learn" class="ml-2">Learn Module</span>
        </button>

    </div>
    {{-- @foreach ($mr as $mrs) --}}
    {{-- @if (Auth::user()->email == $mrs) --}}
    @if(Gate::allows('edit-audit') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="edit" value=id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if(Gate::allows('delete-audit') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection
    @section('notification')
    <div class="dropdown d-inline-block ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
            <div class="p-2 bg-primary-dark text-center rounded-top">
                <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
            </div>
            <ul class="nav-items mb-0">
                @foreach ($issues as $issue)
                <?php
                            $today = date('d-m-Y');
                            $postdate = $issue->created_at;
                            $date = date('d-m-Y', strtotime($postdate));
                            $t = Date('Y-m-d');
                            $dept = $issue->dept ? $issue->dept->departmentname : '';
                            ?>
                @if ($dept == Auth::user()->deptid)
                @if ($issue->processstage == 'Awaiting actions' && $today == $date)
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-check-circle text-success"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">A new issue raised</div>
                            <span class="font-w500 text-muted">An issue raised in {{ $dept }}
                                department with id {{ $issue->issueid }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if ($issue->status == 'open' && $t > $issue->duedate)
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-times-circle text-danger"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Due</div>
                            <span class="font-w500 text-muted">Due on {{ $issue->issueid }} </span>
                        </div>
                    </a>
                </li>
                @endif
                @if ($issue->impl_status != 'Completed-100%')
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-plus-circle text-primary"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Pending Actions</div>
                            <span class="font-w500 text-muted">Actions pending in
                                {{ $issue->issueid }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @endif
                @endforeach
            </ul>
        </div>
    </div>
    @endsection
    @include('exporting.foractionsystemaudit')
</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/learn_module.js') }}"></script>
<script>
    let video_issues_selector = $('#modal-learn-module');
    let video_issues_id_selector = $('#video');
    displayVideo(video_issues_selector, video_issues_id_selector);
    let learn_issue_selector = $('#learn');
    let learn_issue_btn_selector = $('#learn_btn');
    showOrHide(learn_issue_selector, learn_issue_btn_selector);

</script>
<script>
    $(document).ready(function() {
        $(".viewDetails").click(function() {
            var id = $(this).data('id')
            $.ajax({
                url: "/show/" + id + "/" + "issue"
                , type: 'get'
                , data: {
                    id: id
                }
                , dataType: "json"
                , success: function(response) {
                    console.log(response.data);
                    $('#issueId' + id).html(response.data.issueid);
                    $('#category' + id).html(response.data.category);
                    $('#department' + id).html(response.data.dept ? response.data.dept.departmentname : '');
                    $('#type' + id).html(response.data.type);
                    $('#processstage' + id).html(response.data.processstage);
                    $('#title' + id).html(response.data.title);
                    response.data.responsibleUser.profile_picture ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible' + id).attr('src', '#');
                    response.data.postedByUser.profile_picture ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser' + id).attr('src', '#');
                    $('#observation' + id + ' span').html(response.data.observation);
                    $('#responsibility' + id).html(response.data.responsibility);
                    $('#agreedaction' + id).html(response.data.agreedaction);
                    $('#duedate' + id).html(response.data.duedate);
                    $('#correction' + id).html(response.data.correction);
                    $('#rootcause' + id).html(response.data.rootcause);
                    $('#correctiveaction' + id).html(response.data.correctiveaction);
                    $('#remarks' + id).html(response.data.remarks);
                    //$('#remarks' + id).css('text-danger');
                    $('#action_remarks' + id).html(response.data.action_remarks);
                    $('#postedby' + id).html(response.data.postedByUser?.name);
                    $('#postdate' + id).html(response.data.created_at2);
                    $('#updated_at' + id).html(response.data.updated_at2);
                    $('#approvedby' + id).html(response.data.approvedby.name);
                    $('#closedby' + id).html(response.data.closed_by ? response.data.closed_by.name : '');
                    $('#reviewedby' + id).html(response.data.reviewed_by ? response.data.reviewed_by.name : '')
                    $('#viewModalDetails' + id).modal('show');
                }
            });
        });
    });

</script>
</html>
@endsection
