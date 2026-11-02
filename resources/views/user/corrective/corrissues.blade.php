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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
                <input type="hidden" name="paper" id="paper" value="{{ count($table_columns_active) > 13 ? 'a2' : (count($table_columns_active) > 10 ? 'a3': 'a4') }}">
                <input type="hidden" name="orientation" id="orientation" value="{{ count($table_columns_active) < 5 ? 'portrait' : 'landscape' }}">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/corrective/addcorrective')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/workflow.css') }}">
    <style>
        @media screen and (max-width: 760px),
        (min-device-width: 768px) and (max-device-width: 1366px) {
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                overflow-y: auto;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
            }

            .pb-1,
            .py-1 {
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

        .breadcrumb>li+li:before {
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

        .status-red {
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }

        .status-red-light {
            color: #e27e7e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }

        .status-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }

        .status-gray {
            color: #737373;
            background-color: #e6e6e6;
            border-color: #fbf4ea;
        }

        .status-blue {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }

        .status-success-light {
            color: #488a71;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }


        .status {
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-size: 75%;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .w-49 {
            width: 49% !important;
        }

        .w-100 {
            width: 98.5% !important;
        }

        .titleheading {
            font-size: 10px !important;
            text-transform: capitalize !important;
            position: sticky !important;
            position: relative !important;
            top: 2px !important;
            font-weight: 600 !important;
        }
        .titleheading2{
            min-width: 110px !important;
            background-color: white;
        }

        .freezeheading {
            font-size: 10px;
            text-transform: capitalize;
            /* position: sticky !important; */
            top: -1px;
            background: white;
            /* z-index: 100; */
            border-top: 1px solid #e1e6e9 !important;
            box-shadow: 1px 1px 1px #e1e6e9;
        }

        .non-draggable-title-heading {
            font-size: 10px !important;
            text-transform: capitalize !important;
            /* position: sticky !important;
            position: relative !important; */
            top: 2px !important;
            font-weight: 600 !important;
        }

        .td-class {
            text-align: center !important;
            font-size: 10px !important;
        }

        .disabled-link {
            pointer-events: none;
            color: #999 !important;
            text-decoration: none;
        }
        .scrollable{
            height:100%;
            overflow-x: scroll;
            overflow-y: scroll;
        }
        .badge-class {
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
        .even{
            background-color:#fff !important;
        }
        /* @media (min-width: 760px) {
            #tabledata_filter {
                position: fixed !important;
                right: 70px !important;
                text-align: right !important;
            }
            #tabledata_length{
                position: fixed !important;
                text-align: left !important;
            }
            div.table-responsive > div.dataTables_wrapper > div.row {
                margin-bottom: 2.5rem !important;
            }
            
        } */
       
        
        /* --------------------------header dropdown arrow css----------------------------------------------- */
        /* arrow css starts */
           .header-arrow {
                position: relative;
                padding-right: 15px; 
            }
            .header-arrow::after {
                content: '\2191'; 
                position: absolute;
                right: 5px;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0; 
                transition: opacity 0.2s ease;
            }

            .header-arrow:hover::after {
                opacity: 1; 
            }

            .header-arrow.desc::after {
                content: '\2193'; 
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
                padding: 5px 5px;
            }
            #dropdownMenucol li a {
                text-decoration: none;
                color: black;
                display: block;
            }
            #dropdownMenucol li a:hover {
                background-color: #f0f0f0;
            }
        .modal-second.show {
            background: #80808096;
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
        td.non-draggable-title-heading {
            cursor: none;
        }
        .scrollable2 {
        height: 60%;
        overflow-x: scroll;
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
       
    </style>
</head>
<body>
    <?php
    $issues2=$issues1=$issues3=[];
    ?>
    <input type="hidden" value="a1" id="tabselected">
   
    <div class="alert alert-primary" id="columnremovaldanger" style="display:none;font-size:12px;">
        Internal server error, Unable to process request 
    </div> 
    <div class="alert alert-danger" id="columnremovaldangernotremoved" style="display:none"></div> 
    <!-- Page Content -->
    <div class="content" style="width:100%;">
        @if(Session::has('msg'))
        <div class="alert alert-warning" style="font-size:14px; position: relative;">
            <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
            {{ Session::get('msg') }}
        </div>
        @endif
        <!-- heading part starts -->  
        <div class="block-header block-header-default">
            <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                       <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                         Open Corrective Actions</span> 
                        <span style="font-weight:normal;font-size:15px;color: gray;"> 
                         Corrective action </span>
            </div>
            <div>
                <table width="100%" style="padding-top:5px;padding-right:15px;">
                    <tr>
                        <td align="right">                       
                            <a href="#" class="btn btn-sm btn-alt-secondary btn-configure-table" data-toggle="dropdown" 
                                data-placement="top">
                                <span data-toggle="tooltip" title="Click here to configure column">
                                <!-- <i class="fa fa-plus mr-1"></i></span>  -->
                                    <i class="fa fa-sliders-h text-center" style="font-size:17px;cursor:pointer"></i></span>
                            </a>
                            <div class="dropdown-menu font-size-sm scrollable2"  aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                                @foreach($table_columns_non_active as $value)
                                  <a href="{{ route('corrective.register.table.column.activation',['id'=>$value->id]) }}" class="dropdown-item text-dark" style="font-size:14px;" 
                                      id="table_column_value"><i class="si si-plus" style="font-size:12px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                @endforeach
                                @if($table_columns_names_enabled_by_user>0)
                                <a href="{{ route('corrective.register.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
                                  Reset to default table
                                </a>
                                @endif
                            </div>
                        </td>                    
                    </tr>
                </table>
            </div>
        </div>
         <!-- Ends heading part  -->

        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <!--shift header to white background-->
                <div class="table-responsive" id="content">
                    <!--end of shift header to white background-->
                    {{-- <div style="overflow-y:auto;overflow-x:auto;"> --}}
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                            <thead>
                                <tr style="position: sticky; top: -5px;z-index:100;">
                                   <td class="text-center titleheading sorting_disabled" style="background-color:#fff;"></td>
                                   <td class="text-center titleheading" draggable="false" data-orderable="false" style="cursor:pointer;background-color:#fff;">Slno</td>
                                    <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Id</td>
                                    <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Title</td>
                                    @foreach($table_columns_active as $value)
                                        @if($value->enabled_by_user!==0)
                                            <td data-orderable="false" class="text-center titleheading px-1 titleheading2"
                                              @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                                              @if($value->column_name == 'due date')style="width: 12%" @endif
                                              @if($value->column_name == 'added date')style="width: 8%" @endif
                                              @if($value->column_name == 'action reviewed date')style="width: 8%" @endif
                                              @if($value->column_name == 'correction target date')style="width: 8%" @endif
                                              @if($value->column_name == 'closure reviewed date')style="width: 8%" @endif
                                              onmouseover="return removehidecolumn('{{$value->id}}')">
                                               {{$value->column_name}}                                               
                                            </td>
                                        @else
                                            <td class="text-center titleheading px-1 titleheading2"
                                                @if($value->is_dragdropable)data-column-id="{{ $value->id }}" @endif
                                                style="@if($value->column_name == 'due date')width: 10%; @elseif($value->column_name == 'correction target date') min-width: 150px !important;@endif"
                                                data-orderable="false">
                                                {{$value->column_name}}
                                            </td>
                                        @endif
                                    @endforeach
                                    <td id="statusselectdata" class="text-center titleheading px-1 titleheading2" draggable="false" style="background-color:#fff;cursor:pointer;" data-orderable="false">
                                        Process Stage
                                    </td>
                                    <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Action Status</td>
                                    <td class="text-center titleheading px-1 titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;"> Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 0;
                                    $isCorrectiveResponsibilityChangeAuthor = Gate::allows('corrective-responsibility-change');
                                    $isEditOrEditAllAuthor = Gate::allows('edit-corrective') || Gate::allows('edit-all');
                                @endphp
                                @foreach($issues as $issue)
                                @php
                                    $rr=$issue->id;
                                    $date = $issue->duedate;
                                    $today = Date("Y-m-d");
                                    $issuedocs=explode(',',$issue->file);
                                    $actiondocs=explode(',',$issue->action_file);
                                    $dept = $issue->correctionDept ? $issue->correctionDept->departmentname : '';
                                    $division = $issue->division ? $issue->division->name : '';
                                    $sub_division = $issue->area ? $issue->area->name : '';
                                    $responsible_names = $correctionResponsibilities->whereIn('email', explode(',', $issue->correction_responsibility))->pluck('name')->toArray();
                                    $responsible_names = implode(',', $responsible_names);
                                    $team_leader = $issue->teamLead;
                                    $team_leader_email = $team_leader ? $team_leader->email : null;
                                    $investigation_team = [];
                                    foreach($issue->investigators as $investigator) {
                                        $investigation_team[] = $investigator->email;
                                    }
                                    $investigation_array = ['Actions Completed', 'rejected'];
                                @endphp
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td>                                
                                    <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @php
                                            $target_date = $issue->correction_target_date ? $issue->correction_target_date : $today ;

                                        @endphp
                                        @if(($issue->status == 'open' || $issue->status == 'NULL')&& $today > $target_date)
                                        <span class="status status-red" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                                        @else
                                        <span class="status status-success">{{$issue->issueid}}</span>
                                        @endif
                                    </td>
                                    @php
                                    $cat = $issue->cat ? $issue->cat->categoryname : '';
                                    @endphp
                                    <td class=" font-w600 " style="font-size:10px;">
                                        @if($cat =="Incident Investigation")
                                        <a href="{{ route('corrective.detail', $issue->id) }}">Incident # {{$issue->title}}</a>
                                        @else
                                        <a href="{{ route('corrective.detail', $issue->id) }}">{{$issue->title}}</a>
                                        @endif
                                    </td>
                                    @foreach($table_columns_active as $value)
                                        @switch($value->column_name)
                                            @case('category')
                                                @if($cat=="Internal Audit")
                                                <td class="text-center" style="font-size:10px;width: 5%;color:#0033cc">{{ $cat }}</td>
                                                @elseif($cat=="External Audit")
                                                <td class="text-center" style="font-size:10px;width: 5%;color:orange">{{$cat}}</td>
                                                @elseif($cat=="Management Review")
                                                <td class="text-center" style="font-size:10px;width: 5%;color:#33cc33">{{$cat}}</td>
                                                @elseif($cat=="Corrective Action")
                                                <td class="text-center" style="font-size:10px;width: 5%;color:#ff0066">{{$cat}}</td>
                                                @elseif($issue->category=='Legal Requirement')
                                                <td class="text-center" style="font-size:10px;width: 5%;color:#ff0066">{{$issue->category}}</td>
                                                @elseif($issue->category=='Incident Investigation')
                                                <td class="text-center" style="font-size:10px;width: 5%;color:#ff0066">{{$issue->category}}</td>
                                                @elseif($issue->category=="Management Review")
                                                <td class="text-center" style="font-size:10px;width: 5%;color:#33cc33">{{$issue->category}}</td>
                                                @else
                                                <td class="text-center" style="font-size:10px;width: 5%;color:black">{{$cat}}</td>
                                                @endif
                                            @break
                                            @case('type')
                                                <td class="text-center" style="font-size:12px;">
                                                    @if($issue->type=='' || $issue->type=='-')
                                                    <span class="status status-blue">-</span>
                                                    @elseif($issue->typ)
                                                    <span style="font-size:10px;background-color:{{ $issue->typ->background_color }};color:{{ $issue->typ->text_color}}" class="badge-class">{{$issue->typ ? $issue->typ->name : ''}}</span>
                                                    @else
                                                    <span class="status status-blue"></span>
                                                    @endif
                                                </td>
                                            @break
                                            @case('department')
                                                <td class="text-center" style="font-size:10px;">
                                                {{$dept?$dept:'-'}}
                                                </td>
                                            @break
                                            @case('division')
                                                @if(in_array('division',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$division?$division:'-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('subdivision')
                                                @if(in_array('subdivision',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$sub_division?$sub_division:'-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('raisedby')
                                                @if(in_array('raisedby',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->postedByCorrective ? $issue->postedByCorrective->name : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('financial loss')
                                                @if(in_array('financial loss',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->financial_loss ? $issue->financial_loss : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('potential cost saving')
                                                @if(in_array('potential cost saving',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->potential_cost_savings ? $issue->potential_cost_savings : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('responsibility')
                                                @if(in_array('responsibility',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{ $responsible_names ?  $responsible_names : '-'}}
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
                                            @case('due date')
                                                @if(in_array('due date',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->duedate ? date('d-m-Y',strtotime($issue->duedate)) : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('investigation team lead')
                                                @if(in_array('investigation team lead',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->teamLead ? $issue->teamLead->name : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('investigation target date')
                                                @if(in_array('investigation target date',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                @if($issue->investigation_date!=null)
                                                    {{ $issue->investigation_date ? date('d-m-Y',strtotime($issue->investigation_date)) :'--' }}
                                                @else
                                                --
                                                @endif
                                                </td>
                                                @endif
                                            @break
                                            @case('correction target date')
                                                @if(in_array('correction target date',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                @if($issue->correction_target_date!=null)                                                                                    
                                                    {{ $issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) :'--' }}                                                                                       
                                                @else
                                                --
                                                @endif
                                                </td>
                                                @endif
                                            @break
                                            @case('source')
                                                @if(in_array('source',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->source ? $issue->source : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('added date')
                                                @if(in_array('added date',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->created_at ? date('d-m-Y',strtotime($issue->created_at)) : date('d-m-Y',strtotime($issue->postdate)) }}
                                                </td>
                                                @endif
                                            @break
                                            @case('action reviewed by')
                                                @if(in_array('action reviewed by',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->name : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('action reviewed date')
                                                @if(in_array('action reviewed date',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->action_reviewed_at ? date('d-m-Y',strtotime($issue->action_reviewed_at)) : '-' }}
                                                </td>
                                                @endif
                                            @break
                                            @case('closure reviewed by')
                                                @if(in_array('closure reviewed by',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->closedByCorrective ? $issue->closedByCorrective->name : '-'}}
                                                </td>
                                                @endif
                                            @break
                                            @case('closure reviewed date')
                                                @if(in_array('closure reviewed date',$table_columns_names_active))
                                                <td class="text-center" style="font-size:10px;">
                                                {{$issue->closed ? date('d-m-Y',strtotime($issue->closed)) : '-' }}
                                                </td>
                                                @endif
                                            @break
                                            @default
                                            @break
                                        @endswitch
                                    @endforeach
                                    <td class="d-sm-table-cell text-center" style="font-size:13px;">
                                                @if($issue->processstage=='Awaiting actions')
                                                <span class="status status-gray">Awaiting actions</span>
                                                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                                                @elseif($issue->processstage == 'Correction Proposed- RC Awaiting' || $issue->processstage == 'Actions Proposed- Review Awaiting' || $issue->processstage == 'RC Updated- Correction Awaiting')
                                                <span class="status status-blue">{{$issue->processstage}}</span>
                                                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                                                @elseif($issue->processstage=='Action Reviewed, OK')
                                                <span class="status status-success">Action Approved</span>
                                                <div class="d-none">{{ 'e_'. 'Action Approved'.'_f'  }}</div>
                                                @elseif($issue->processstage=='Action Reviewed, Not OK' /*|| $issue->processstage=='rejected'*/)
                                                <span class="status status-red">Action Rejected</span>
                                                <div class="d-none">{{ 'e_'. 'Action Rejected'.'_f'  }}</div>
                                                @elseif($issue->processstage=='rejected')
                                                <span class="status status-red">Entry Rejected</span>
                                                <div class="d-none">{{ 'e_'. 'Entry Rejected'.'_f'  }}</div>
                                                @elseif($issue->processstage=='Need More Action' || $issue->processstage=='Entry Under Review')
                                                <span class="status status-warning">{{$issue->processstage}}</span>
                                                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                                                @elseif($issue->processstage=='Actions Completed')
                                                <span class="status status-blue">{{$issue->processstage}}</span>
                                                <div class="d-none">{{ $issue->processstage ? 'e_'. $issue->processstage . '_f' : '-' }}</div>
                                                {{-- @elseif($issue->processstage=='Approved')
                                                    <span class="status status-success">{{$issue->processstage}}</span> --}}
                                                @endif
                                                
                                    </td>
                                    <td class="d-sm-table-cell " style="font-size:10px;">
                                        {{-- @if($issue->processstage=="Awaiting actions") --}}
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $issue->average_percent }}%;" aria-valuenow="{{ $issue->average_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">{{ $issue->average_percent }}%</p>

                                        {{-- @endif
                                            @if($issue->impl_status=="Progress-25%" || $issue->processstage=="Action Reviewed, OK" && $issue->impl_status!="Partially Completed-50%" )
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">25%</p>
                                            @endif
                                            @if($issue->impl_status=="Partially Completed-50%")
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">50%</p>
                                            @endif
                                            @if($issue->impl_status=="Completed-100%")
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">100%</p>
                                            @endif
                                            @if($issue->processstage=="Action Reviewed, Not OK" || $issue->processstage=="rejected" )
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">10%</p>
                                            @endif
                                            @if($issue->processstage=="Actions Proposed" && $issue->impl_status=="Not Started-0%")
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">0%</p>
                                            @endif --}}
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            @php
                                                $disableLinkAttr = [
                                                    'add_attachment' => ['rejected'],
                                                    'change_investigation_team' => ['Actions Completed', 'rejected'],
                                                    'responsible_change' => ['Actions Completed', 'rejected'],
                                                ]
                                            @endphp
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                                @foreach($issuereviewers as $rep)
                                                    @if(Auth::user()->email==$rep)
                                                        @if($issue->processstage != 'Entry Under Review')
                                                            <!--// Add attachment //-->
                                                            <a href="#" class="dropdown-item {{ in_array($issue->processstage, $disableLinkAttr['add_attachment']) ? 'disabled-link' : ''}}"
                                                               style="font-size:10px;" onclick="theAlert({{ $issue->id }})">
                                                                <i class="si si-paper-clip" style="font-size:10px;"></i> Add attachment
                                                            </a>
                                                            <!--// Change investigation team //-->
                                                            @if($issue->is_req_rc_analysis_management_review=="yes")
                                                            <button class="js-change-investigation-team dropdown-item {{ in_array($issue->processstage, $disableLinkAttr['change_investigation_team']) ? 'disabled-link' : ''}}"
                                                                    data-url="{{ route('corrective.change.investigation.team', $issue->id) }}"
                                                                    style="font-size:10px;"
                                                                    {{ $issue->processstage == 'Actions Completed' ? 'disabled' : ''}}>
                                                                <i class="fa fa-users" style="font-size:10px;"></i> Change investigation team
                                                            </button>
                                                            @endif
                                                        @endif
                                                        <!--// Responsibility change //-->
                                                        @if($isCorrectiveResponsibilityChangeAuthor && $issue->processstage != 'Entry Under Review')
                                                            <button class="dropdown-item {{ in_array($issue->processstage, $disableLinkAttr['responsible_change']) ? 'disabled-link' : ''}}"
                                                                    style="font-size:10px;"
                                                                    onclick="respChange('{{ $rr}}')"
                                                                    {{ $issue->processstage == 'Actions Completed' ? 'disabled' : ''}}>
                                                                <i class="fa fa-user-edit" style="font-size:10px;"></i> Responsibility change
                                                            </button>
                                                        @endif
                                                        <!--// Review //-->
                                                        @if($issue->processstage == 'Entry Under Review')
                                                            <a href="{{url('/user/corrective/approve?val='.$rr)}}"
                                                               class="dropdown-item" style=" font-size:10px;">
                                                                <i class="fas fa-redo-alt" style="font-size:10px;"></i> Review
                                                            </a>
                                                        @endif
                                                        <!--// Review Action //-->
                                                        @if( $issue->processstage == 'Actions Proposed- Review Awaiting' && $issue->processstage != 'Entry Under Review')
                                                            <a href="{{url('/user/corrective/reviewstatus?val='.$rr)}}"
                                                               class="dropdown-item" style="font-size:10px;">
                                                                <img src="{{ asset('images/icons/review.svg') }}"> Review Action
                                                            </a>
                                                        <!--// Closure Review //-->
                                                        @elseif( $issue->processstage == 'Actions Completed' && $issue->processstage != 'Entry Under Review')
                                                            <a href="{{url('/user/corrective/closure?val='.$rr)}}"
                                                               class="dropdown-item" style="font-size:10px;">
                                                                <i class="si si-close" style="font-size:10px;"></i> Closure Review
                                                            </a>
                                                        @endif
                                                        <!--// Add Investigation Report //-->
                                                        @if($issue->team_lead_id || count($issue->investigators) && $issue->processstage != 'Entry Under Review')
                                                            <button class="dropdown-item {{ in_array($issue->processstage, $investigation_array) || ($auth_email !== $team_leader_email && !in_array($auth_email, $investigation_team)) ? 'disabled-link' : ''}}"
                                                                    style="font-size:10px;"
                                                                    {{ $issue->processstage == 'Actions Completed' ? 'disabled disabled-link' : ''}}
                                                                onclick="showInvestigationModal('{{ $issue->id }}', '{{ route('corrective.investigation', $issue->id) }}')">
                                                                <i class="si si-plus" style="font-size:10px;"></i> Add Investigation Report
                                                            </button>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                <!--// Add Investigation Report //-->
                                                @if(Auth::user()->email === $team_leader_email && !in_array(Auth::user()->email, $issuereviewers))
                                                    <button class="dropdown-item" style="font-size:10px;"
                                                        onclick="showInvestigationModal('{{ $issue->id }}', '{{ route('corrective.investigation', $issue->id) }}')">
                                                        <i class="si si-plus" style="font-size:10px;"></i> Add Investigation Report
                                                    </button>
                                                @endif
                                                <!--// Resubmit Issue //-->
                                                @if(Auth::user()->employeeid === $issue->postedby && $issue->processstage=='rejected' && $issue->processstage != 'Entry Under Review')
                                                    <a onclick="cll('Are u sure to resubmit?')"
                                                       href="{{url('/user/corrective/resubmit?val='.$rr)}}"
                                                       class="dropdown-item" style="font-size:10px;">
                                                        <i class="fas fa-paper-plane" style="font-size:10px;"></i> Resubmit Issue
                                                    </a>
                                                @endif
                                                @if($issue->processstage != 'Entry Under Review')
                                                    @if($isEditOrEditAllAuthor)
                                                        @if($issue->processstage == 'Awaiting actions')
                                                            <button style="font-size:10px;"
                                                                    class="dropdown-item"
                                                                    onclick="editItem('{{ $rr }}')">
                                                                <i class="fas fa-edit" style="font-size:10px;"></i> Edit
                                                            </button>
                                                        @else
                                                            <button style="font-size:10px;"
                                                                    class="dropdown-item {{ $issue->processstage == 'Actions Completed' || $issue->processstage == 'rejected' ? 'disabled-link' : ''}}"
                                                                    onclick="editProposedItem('{{ $rr }}')" {{ $issue->processstage == 'Actions Completed' ? 'disabled' : ''}}>
                                                                <i class="fas fa-edit" style="font-size:10px;"></i> Edit
                                                            </button>
                                                        @endif
                                                   @endif
                                                @endif
                                            </div>
                                            <a href="#" class="btn btn-sm btn-alt-primary btn-view-detail viewDetails"
                                               data-route="{{ $issue->id }}">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    {{-- </div> --}}
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
        <div class="corrective-area"></div>
        <div id="view-detail"></div>
        <!-- Ends Column Header Dropdown Menu-->

        {{----------delete request button for doc controllers --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="{{ asset('js/datatable_extension_corrective_open.js') }}"></script>
        <script>
            function removehidecolumn(id){
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
                            url: '/user/corrective/register-table/removecolumn',
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
        <script>
            var removedIssueFilenames = [];
            var removedActionFilenames = [];
            $(document).on('click', '.remove-action-attachment', function () {
                var filename = $(this).data('filename');
                $(this).closest('tr').remove();
                removedActionFilenames.push(filename);
                $('#actionAttachmentTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                    $(this).find('td:first').css('font-size', '10px');
                });
            });
            $(document).on('click', '.remove-issue-attachment', function () {
                var filename = $(this).data('filename');
                $(this).closest('tr').remove();
                removedIssueFilenames.push(filename);
                $('#issueAttachmentTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                    $(this).find('td:first').css('font-size', '10px');
                });
            });
        </script>
        <script>
            $('body').on('click', '.view-moc-detail', function () {
                const id = $(this).attr('data-route');
                $.ajax({
                    url : window.location.origin+`/user/context/changelog/${id}/show`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('#view-detail').html(data.view)
                            const modalEle = $(`#viewModal${data.id}`);
                            modalEle.modal('show');
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            });
        </script>
        <script>
            $(document).ready(function() {
                let issue_ids = "{{ $issue_ids }}";
                issue_ids = issue_ids.split(',');

                for (let i = 0; i < issue_ids.length; i++) {
                    let id = `${issue_ids[i]}`;

                    $(`#factors` + id).select2({
                        dropdownParent: $(`#factors_append` + id)
                    });
                    $(`#influence` + id).select2({
                        dropdownParent: $(`#influence_append` + id)
                    });
                    $(`#team` + id).select2({
                        dropdownParent: $(`#team_append` + id)
                    });
                    $(`#lead` + id).select2({
                        dropdownParent: $(`#lead_append` + id)
                    });
                
                    function getvalues(id) {
                        var inps = document.querySelectorAll('[data-foo=r' + id + ']');
                        var rootcause_arr = [];
                        for (var i = 0; i < inps.length; i++) {
                            var inp = inps[i];
                            rootcause_arr[i] = inp.value;
                        }

                        return rootcause_arr;
                    }

                    function getcorrectiveactionvalues(id) {
                        var inps = document.querySelectorAll('[data-foo=c' + id + ']');
                        var arr = [];
                        for (var i = 0; i < inps.length; i++) {
                            var inp = inps[i];
                            arr[i] = inp.value;
                        }
                        return arr;
                    }

                    function getdeptvalues(id) {
                        var inps = document.querySelectorAll('[data-foo=d' + id + ']');
                        var arr = [];
                        for (var i = 0; i < inps.length; i++) {
                            var inp = inps[i];
                            arr[i] = inp.value;
                        }
                        return arr;
                    }


                    function getrespvalues(id) {
                        var inps = document.querySelectorAll('[data-foo=resp' + id + ']');
                        var arr = [];
                        for (var i = 0; i < inps.length; i++) {
                            var inp = inps[i];
                            arr[i] = inp.value;
                        }
                        return arr;
                    }


                    function gettargetdatevalues(id) {
                        var inps = document.querySelectorAll('[data-foo=tar' + id + ']');
                        var arr = [];
                        for (var i = 0; i < inps.length; i++) {
                            var inp = inps[i];
                            arr[i] = inp.value;
                        }
                        return arr;
                    }


                }

                $('body').on('click', '.headingselect', function () {
                    if (!$(this).find('.filter-content').length) {
                        $('.filter-content').hide();
                    }
                });

                $('body').on('click', '.btn-submit-investigation', function (e) {
                    e.preventDefault();
                    const id = $(this).attr('data-id');
                    const submitButton = $(this);
                    const modal = $(this).closest('.investigation-modal')
                    let error_investigation_selector = modal.find('#error_investigation' + id);
                    let validation_error_investigation_selector = modal.find('#validation_error_investigation' + id);
                    let success_investigation_selector = modal.find('#success_investigation' + id);
                    let occurence_selector = $("input[name='occurrence']");
                    let recent_changes_selector = $("input[name='recent_changes']");
                    let follow_procedures_selector = $("input[name='follow_procedures']");
                    let issue_foreseeable_selector = $("input[name='issue_foreseeable']");
                    let rootcause_arr = getvalues(id);
                    let corr_arr = getcorrectiveactionvalues(id);
                    let dept_arr = getdeptvalues(id);
                    let resp_arr = getrespvalues(id);
                    let tar_arr = gettargetdatevalues(id);
                    let formData = new FormData();
                    formData.append('corrective_id', $('#corrective_id' + id).val());
                    formData.append('occurrence', occurence_selector.is(":checked") ? occurence_selector.filter(":checked").val() : 0);
                    formData.append('system_related_problems', $("#system_related_problems" + id).val());
                    formData.append('recent_changes', recent_changes_selector.is(":checked") ? recent_changes_selector.filter(":checked").val() : 0);
                    formData.append('follow_procedures', follow_procedures_selector.is(":checked") ? follow_procedures_selector.filter(":checked").val() : 0);
                    formData.append('issue_foreseeable', issue_foreseeable_selector.is(":checked") ? issue_foreseeable_selector.filter(":checked").val() : 0);
                    formData.append('prevent_reason', $('#prevent_reason' + id).val());
                    formData.append('factors_details', $("#factors_details_val" + id).val());
                    formData.append('influence_details', $("#influence_details_val" + id).val());
                    formData.append('removedIssueFilenames',JSON.stringify(removedIssueFilenames));
                    formData.append('removedActionFilenames',JSON.stringify(removedActionFilenames))
                    rootcause_arr.forEach(function(item) {
                        formData.append('rootcause[]', item);
                    });
                    corr_arr.forEach(function(item) {
                        formData.append('correctiveaction[]', item);
                    });
                    dept_arr.forEach(function(item) {
                        formData.append('department[]', item);
                    });
                    resp_arr.forEach(function(item) {
                        formData.append('responsibility[]', item); 
                    });
                    tar_arr.forEach(function(item) {
                        formData.append('target_date[]', item);
                    });
                    let issueFiles = $('#issue_file')[0].files;
                    for (let i = 0; i < issueFiles.length; i++) {
                        formData.append('issue_file[]', issueFiles[i]);
                    }
                    let actionFiles = $('#action_file')[0].files;
                    for (let i = 0; i < actionFiles.length; i++) {
                        formData.append('action_file[]', actionFiles[i]);
                    }
                    var factors = $('#factors' + id).val().length ? $('#factors' + id).val() : '';
                    var influence = $('#influence' + id).val().length ? $('#influence' + id).val() : '';
                    if(factors){
                        factors.forEach(function(item) {
                            formData.append('contributing_factors[]', item);
                        });
                    }else{
                        formData.append('contributing_factors[]', "");
                        formData.append('external_factors', "");
                    }
                    if(influence){
                        influence.forEach(function(item) {
                            formData.append('external_factors[]', item);
                        });
                    }else{
                        formData.append('external_factors[]', "");
                    }
                    $.ajax({
                        type: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                        , url: '{{ route("user.corrective.submit_investigation_form") }}', 
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function () {
                            submitButton.html('<i class="fa fa-spinner fa-spin"></i> Submit');
                        }
                        , success: function(response) {
                            error_investigation_selector.removeClass('d-block');
                            error_investigation_selector.addClass('d-none');

                            validation_error_investigation_selector.removeClass('d-block')
                            validation_error_investigation_selector.addClass('d-none');

                            success_investigation_selector.removeClass('d-none');
                            success_investigation_selector.addClass('d-block');

                            setTimeout(function() {
                                submitButton.html('Submit');
                                window.location.reload();
                            }, 2000);
                        }
                        , error: function(err) {
                            submitButton.html('Submit');
                            if (err.status === 500) {
                                error_investigation_selector.removeClass('d-none');
                                error_investigation_selector.addClass('d-block');

                                validation_error_investigation_selector.removeClass('d-block')
                                validation_error_investigation_selector.addClass('d-none');

                                success_investigation_selector.removeClass('d-block');
                                success_investigation_selector.addClass('d-none');
                            } else if (err.status === 422) {
                                success_investigation_selector.removeClass('d-block');
                                success_investigation_selector.addClass('d-none');

                                error_investigation_selector.removeClass('d-block');
                                error_investigation_selector.addClass('d-none');

                                validation_error_investigation_selector.addClass('d-block')
                                validation_error_investigation_selector.removeClass('d-none');

                                var error_text = '';
                                $.each(err.responseJSON.errors, function(i) {
                                    $.each(err.responseJSON.errors[i], function(key, val) {
                                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                                    });
                                });
                                validation_error_investigation_selector.html(error_text);


                            }
                        }
                    });
                }).on('click', '.btn-change-investigation', function (e) {
                    e.preventDefault();
                    const id = $(this).attr('data-id');
                    const submitButton = $(this);
                    const modal = $(this).closest('.change-investigation-modal')
                    let error_investigation_selector = modal.find('#error_change_investigation' + id);
                    let validation_error_investigation_selector = modal.find('#validation_error_change_investigation' + id);
                    let success_investigation_selector = modal.find('#success_change_investigation' + id);
                    let lead_id = $("#lead" + id).val();
                    let team_members = $("#team" + id).val();

                    $.ajax({
                        type: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                        , url: '{{ route("user.corrective.submit_change_investigation_form") }}'
                        , data: {
                            'corrective_id': id
                            , 'lead': lead_id
                            , 'team_members': team_members
                        },
                        beforeSend: function () {
                            submitButton.html('<i class="fa fa-spinner fa-spin"></i> Submit');
                        }
                        , success: function(response) {
                            error_investigation_selector.removeClass('d-block');
                            error_investigation_selector.addClass('d-none');

                            validation_error_investigation_selector.removeClass('d-block')
                            validation_error_investigation_selector.addClass('d-none');

                            success_investigation_selector.removeClass('d-none');
                            success_investigation_selector.addClass('d-block');

                            setTimeout(function() {
                                submitButton.html('Submit');
                                window.location.reload();
                            }, 2000);
                        }
                        , error: function(err) {
                            submitButton.html('Submit');
                            if (err.status === 500) {
                                error_investigation_selector.removeClass('d-none');
                                error_investigation_selector.addClass('d-block');

                                validation_error_investigation_selector.removeClass('d-block')
                                validation_error_investigation_selector.addClass('d-none');

                                success_investigation_selector.removeClass('d-block');
                                success_investigation_selector.addClass('d-none');
                            } else if (err.status === 422) {
                                success_investigation_selector.removeClass('d-block');
                                success_investigation_selector.addClass('d-none');

                                error_investigation_selector.removeClass('d-block');
                                error_investigation_selector.addClass('d-none');

                                validation_error_investigation_selector.addClass('d-block')
                                validation_error_investigation_selector.removeClass('d-none');

                                var error_text = '';
                                $.each(err.responseJSON.errors, function(i) {
                                    $.each(err.responseJSON.errors[i], function(key, val) {
                                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                                    });
                                });
                                validation_error_investigation_selector.html(error_text);


                            }
                        }
                    });
                })
            });
            // ------------------------------------table column filter starts------------------------------------------------//
            let correctivecolumndetails = <?php echo $table_columns_active_for_js;?>;
            enableDragDrop('#tabledata', '{{ route("corrective.register.table.column.updateposition") }}', function () {
                location.reload();
            });


            if(correctivecolumndetails.length > 0) {
                const $preInitData = {
                    'category': {
                        'options': "{{ $allcategoriesname }}",
                        'display_name': "Category",
                        'is_enable_remove': false,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'type': {
                        'options': "{{ $alltypenames }}",
                        'display_name': "Type",
                        'is_enable_remove': false,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'department': {
                        'options': "{{ $alldeptnames }}",
                        'display_name': "Department",
                        'is_enable_remove': false,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'division': {
                        'options': "{{ $alldivisionnames }}",
                        'display_name': "Division",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':true
                    },
                    'subdivision': {
                        'options': "{{ $allsubdivisionnames }}",
                        'display_name': "Subdivision",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':true
                    },
                    'raisedby': {
                        'options': "{{ $allraisedbynames }}",
                        'display_name': "Raisedby",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'financial loss': {
                        'options': '',
                        'display_name': '',
                        'is_enable_remove': true,
                        'is_enable_filter': false,
                        'option': {},
                        'strict_ness':false
                    },
                    'potential cost saving': {
                        'options': '',
                        'display_name': '',
                        'is_enable_remove': true,
                        'is_enable_filter': false,
                        'option': {},
                        'strict_ness':false
                    },
                    'responsibility': {
                        'options': "{{ $allusernames }}",
                        'display_name': "Responsibility",
                        'is_enable_remove': false,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'email remainder for action': {
                        'options': '',
                        'display_name': '',
                        'is_enable_remove': true,
                        'is_enable_filter': false,
                        'option': {},
                        'strict_ness':false
                    },
                    'due date': {
                        'options': "{{ $dueDateYears }}",
                        'display_name': "Due Date",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {'is_date_filter' : true},
                        'strict_ness':false
                    },
                    'investigation team lead': {
                        'options': "{{ $allteamleadnames }}",
                        'display_name': "Investigative Team Lead",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':true
                    },
                    'investigation target date': {
                        'options': '{{ $investigtaion_target_dates }}',
                        'display_name': "Investigative Target date",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option':  {'is_date_filter' : true},
                        'strict_ness':false
                    },
                    'process stage': {
                         'options': "{{ $allpstages }}",
                         'display_name': "Process Stage",
                         'is_enable_remove': false,
                         'is_enable_filter': true,
                        'option': {'filter_postfix' : '_f'},
                        'strict_ness':false
                    },
                    'correction target date': {
                        'options': '{{ $correctiontargetDateYears }}',
                        'display_name': "Correction Target date",
                        'is_enable_remove': false,
                        'is_enable_filter': true,
                        'option':  {'is_date_filter' : true},
                        'strict_ness':false
                    },
                    'source': {
                        'options': '{{$sourceNames}}',
                        'display_name': "Source",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':true
                    },
                    'added date': {
                        'options': "{{ $addDateYears }}",
                        'display_name': "Added Date",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {'is_date_filter' : true},
                        'strict_ness':false
                    },
                    'action reviewed by': {
                        'options': "{{ $allactionreviewednames }}",
                        'display_name': "Action Reviewed by",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'action reviewed date': {
                        'options': "{{ $actionReviewedDateYears }}",
                        'display_name': "Action Reviewed date",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {'is_date_filter' : true},
                        'strict_ness':false
                    },
                    'closure reviewed by': {
                        'options': "{{ $allclosurereviewednames }}",
                        'display_name': "Closure Reviewed by",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {},
                        'strict_ness':false
                    },
                    'closure reviewed date': {
                        'options': "{{ $closureReviewedDateYears }}",
                        'display_name': "Closure Reviewed date",
                        'is_enable_remove': true,
                        'is_enable_filter': true,
                        'option': {'is_date_filter' : true},
                        'strict_ness':false
                    },
                    // 'action status': {
                    //     'options': "",
                    //     'display_name': "Action status",
                    //     'is_enable_remove': false,
                    //     'is_enable_filter': false,
                    //     'filter_postfix': '',
                    // },
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
                                $columnData['option'],
                                $columnData['strict_ness']
                            )
                        }
                    }
                }
            }


            // ------------------------------------Ends table column filter------------------------------------------------//
        </script>

        <script>
            $(document).ready(function(){
                $("#tabledata").one("preInit.dt", function() {
                    let table = $('#tabledata').DataTable();
                    var columnIndex = table.column('#processstageshead').index();
                    let allProcessstages = "{{ $allpstages}}".split(',');
                    let processstagesOptions = allProcessstages.map(processstages => `<option value="${processstages}">${processstages}</option>`).join('');
                    let processstagesDropdown = $(`
                        <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="processstages_dropdown">
                            <option value="">Process&nbspStage&nbsp;&nbsp;&#9207;</option>
                            ${processstagesOptions}
                        </select>
                    `);
                    $('#processstageshead').html(processstagesDropdown);
                    $("#processstages_dropdown").on('change', function() {
                        let selectedValue = $(this).val();
                        let firstOption = $('#processstages_dropdown option:first');
                        if (selectedValue) {
                            table.columns(columnIndex).search('e_'+selectedValue + '_f').draw();
                            $(this).addClass('text-primary');
                            $("#processstages_dropdown option").css('color', 'black');
                        } else {
                        table.column(columnIndex).search('').draw();
                        $(this).removeClass('text-primary');
                        $('#processstages_dropdown').val('');
                        }
                        firstOption.html('Process&nbspStage&nbsp;&nbsp;&#9207;');
                    });
                });
            });
        </script>

        <script>
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            function theAlert(id) {
                let url = '{{ route("corrective.saveActionAttachment", ":id") }}';
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
                                })
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

        </script>

        <script>
            $("#del").click(function() {
                var dataArr = new Array();
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        $.ajax({
                            type: 'get'
                            , url: "{{route('deletecorr')}}" + "?val=" + $(this).attr('id')
                            , success: function(response) {
                                window.location.href = "{{url('/user/corrective/corrissues')}}";
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

        </script>
        <script>
            $(document).ready(function() {
                $(".viewDetails").on('click', function() {
                    var id = $(this).attr('data-route');
                    $.ajax({
                        url: window.location.protocol + "//" + window.location.host+"/show/" + id + "/" + "corrective"
                        , type: 'get'
                        , data: {
                            id: id
                        }
                        , dataType: "json"
                        , success: function(response) {
                            response.data.responsibleUser ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                            response.data.postedByUser ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                        }
                    });
                });
            });

        </script>
        {{-----------edit button ------------}}
        <script>
            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 100);

            function ifChecked(id) {
                if ($('#foreseeable_yes_' + id).is(":checked")) {
                    $('#prevent_block_' + id).removeClass('d-none');
                    $('#prevent_block_' + id).addClass('d-inline-block');
                } else if ($('#foreseeable_no_' + id).is(":checked")) {
                    $('#prevent_block_' + id).addClass('d-none');
                    $('#prevent_block_' + id).removeClass('d-inline-block');
                }

            }
                 
            function in_array(needle, haystack) {
                var length = haystack.length;
                for (var i = 0; i < length; i++) {
                    if (haystack[i] == needle)
                        return true;
                }
                return false;
            }


            function displayDetails(id) {
                let factors_details_selector = $('#factors_details' + id);
                if (in_array('others', $('#factors' + id).val())) {
                    factors_details_selector.removeClass('d-none');
                    factors_details_selector.addClass('d-inline-block');
                } else {
                    factors_details_selector.removeClass('d-inline-block');
                    factors_details_selector.addClass('d-none');
                }
            }

            function displayInfluenceDetails(id) {
                let influence_details_selector = $('#influence_details' + id);
                if (in_array('others', $('#influence' + id).val())) {
                    influence_details_selector.removeClass('d-none');
                    influence_details_selector.addClass('d-inline-block');
                } else {
                    influence_details_selector.removeClass('d-inline-block');
                    influence_details_selector.addClass('d-none');
                }

            }

            function respChange(id) {
                let url = `{{url("/user/corrective/editresp?val=")}}` + id;
                window.location.assign(url);

            }

            function editItem(id) {
                let url = `{{route('user.corrective.edit', ':id')}}`;
                url = url.replace(':id', id);
                window.location.assign(url);

            }

            function editProposedItem(id) {
                let url = `{{route('user.corrective.edit_proposed', ':id')}}`;
                url = url.replace(':id', id);
                window.location.assign(url);

            }

            $('body').on('click', '.js-change-investigation-team', function () {
                const url = $(this).attr('data-url')
                $.ajax({
                    url,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.corrective-area').html(data.view)
                            const modalEle = $(`#changeInvestigationModal${data.id}`);
                            modalEle.modal('show');
                            modalEle.find('.js-select2').each(function() {
                                const limitSelection = $(this).attr('data-limit-selection')
                                const isMultiple = $(this).attr('data-multiple')
                                $(this).select2({
                                    width: '100%',
                                    tags: true,
                                    multiple: isMultiple !== "false",
                                    maximumSelectionLength: limitSelection ? limitSelection : 0
                                });
                            });
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            })
            function showInvestigationModal(id, url) {
                $.ajax({
                    url,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.corrective-area').html(data.view)
                            const modalEle = $(`#investigationModal${data.id}`);
                            modalEle.modal('show');
                            let factors_array = $('#factors' + id).val();
                            let influence_array = $("#influence" + id).val();
                            let factors_details_selector = $('#factors_details' + id);
                            let influence_details_selector = $('#influence_details' + id);

                            if (in_array('others', factors_array)) {
                                factors_details_selector.removeClass('d-none').addClass('d-inline-block');
                            } else {
                                factors_details_selector.removeClass('d-inline-block').addClass('d-none');
                            }

                            if (in_array('others', influence_array)) {
                                influence_details_selector.removeClass('d-none').addClass('d-inline-block');
                            } else {
                                influence_details_selector.removeClass('d-inline-block').addClass('d-none');
                            }

                            if ($('#foreseeable_yes_' + id).is(":checked")) {
                                $('#prevent_block_' + id).removeClass('d-none');
                                $('#prevent_block_' + id).addClass('d-inline-block');
                            } else if ($('#foreseeable_no_' + id).is(":checked")) {
                                $('#prevent_block_' + id).addClass('d-none');
                                $('#prevent_block_' + id).removeClass('d-inline-block');
                            }
                            modalEle.find('.js-select2').each(function() {
                                const limitSelection = $(this).attr('data-limit-selection')
                                $(this).select2({
                                    width: '100%',
                                    tags: true,
                                    multiple: true,
                                    maximumSelectionLength: limitSelection ? limitSelection : 0
                                });
                            });
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            }

        </script>
        <script>
            $('body').on('click', '.btn-view-detail', function () {
                const id = $(this).attr('data-route');
                $.ajax({
                    url : window.location.protocol + "//" + window.location.host+`/user/corrective/corrissues/${id}/show`,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.corrective-area').html(data.view)
                            const modalEle = $(`#viewModal${data.id}`);
                            modalEle.modal('show');
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            });
        </script>

    </div>
    <!-- END Page Content -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis'>
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
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
    <!-- END Apps Modal -->

    @section('sideitem')
    {{-- @if(Gate::allows('edit-corrective') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif --}}
    @if(Gate::allows('delete-corrective') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
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

                @foreach($issues as $issue)
                <?php $today = date('d-m-Y');
                $postdate =$issue->postdate;
                $date=date('d-m-Y',strtotime($postdate));
                $t = Date("Y-m-d");
                $dept = $issue->correctionDept ? $issue->correctionDept->departments : '';?>
                @if($dept==Auth::user()->deptid)
                @if($issue->status=="open" && $today==$date)
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-check-circle text-success"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">A new issue raised</div>
                            <span class="font-w500 text-muted">An issue raised in {{$dept}} department with id {{$issue->issueid}}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if($issue->status == 'open' && $t > $issue->duedate)
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-times-circle text-danger"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Due</div>
                            <span class="font-w500 text-muted">Due on {{$issue->issueid}} </span>
                        </div>
                    </a>
                </li>
                @endif
                @if($issue->impl_status!="Completed-100%")
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-plus-circle text-primary"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Pending Actions</div>
                            <span class="font-w500 text-muted">Actions pending in {{$issue->issueid}}</span>
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
    @include('exporting.forcorrective')
</body>
</html>
@endsection
