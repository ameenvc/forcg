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

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
                <input type="hidden" name="paper" id="paper" value="{{ count($table_columns_active) > 13 ? 'a2' : (count($table_columns_active) > 10 ? 'a3': 'a4') }}">
                <input type="hidden" name="orientation" id="orientation" value="{{ count($table_columns_active) < 5 ? 'portrait' : 'landscape' }}">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/corrective/addcorrective')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
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
            min-width: 120px !important;
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
        @media (min-width: 760px) {
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
            
        }
       
        
        /* --------------------------header dropdown arrow css----------------------------------------------- */
        /* arrow css starts */
        .header-arrow {
            position: relative;
            padding-right: 15px; 
        }
        .header-arrow::after {
            content: '\2715'; 
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0; 
            transition: opacity 0.2s ease;
            font-size: 13px;
            color: red;
        }

        .header-arrow:hover::after {
            opacity: 1; 
        }

        .header-arrow.desc::after {
            content: '\2715'; 
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
        .scrollable2 {
            max-height: 60%;
            overflow-x: scroll;
        }
    </style>


    <?php
        $issues2=$issues1=$issues3=[];
    ?>
    <input type="hidden" value="a1" id="tabselected">
    
    <div class="alert alert-primary" id="columnremovaldanger" style="display:none;font-size:12px;">
        Internal server error, Unable to process request 
    </div> 
    <div class="alert alert-danger" id="columnremovaldangernotremoved" style="display:none"></div> 
    <!--Start of Change Log  -->
    <!-- Page Content -->
    <div class="content" style="width:100%">
        <!-- heading part starts -->  
        <div class="block-header block-header-default">
            <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                       <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                        Closed Corrective Actions</span> 
                        <span style="font-weight:normal;font-size:15px;color: gray;"> 
                        Corrective action </span>
            </div>
            <div>
                <table width="100%" style="padding-top:5px;padding-right:15px;">
                    <tr>
                        <td align="right">                       
                            <a href="#" class="btn btn-sm btn-alt-secondary btn-configure-table" data-toggle="dropdown" 
                                data-placement="top">
                                <span data-toggle="tooltip" title="Click here to configure column"></span>
                                <!-- <i class="fa fa-plus mr-1"></i></span>  -->
                                <i class="fa fa-sliders-h text-center" style="font-size:17px;cursor:pointer"></i>
                            </a>
                            <div class="dropdown-menu font-size-sm scrollable2"  aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu">
                                @foreach($table_columns_non_active as $value)
                                  <a href="{{ route('corrective.closed.table.column.activation',['id'=>$value->id]) }}" class="dropdown-item text-dark" style="font-size:14px;" 
                                      id="table_column_value"><i class="si si-plus" style="font-size:11px;"></i>&nbsp;{{ucfirst($value->column_name)}}</a>
                                @endforeach
                                @if($table_columns_names_enabled_by_user>0)
                                <a href="{{ route('corrective.closed.table.column.defaultview') }}" class="btn btn-sm btn-alt-primary" style="font-size:12px;margin-top:10px;margin-left:25px;margin-bottom:10px;">
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
                <div class="table-responsive" id="content">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <thead>
                            <tr style="position: sticky; top: -5px;z-index:100;">
                               <td class="text-center titleheading" style="background-color:#fff;cursor:pointer;" draggable="false"></td>
                               <td class="text-center titleheading" draggable="false" data-orderable="false" style="cursor:pointer;background-color:#fff;">Slno</td>
                               <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Id</td> 
                               <td class="text-center px-1 titleheading titleheading2" draggable="false" data-orderable="false" style="background-color:#fff;cursor:pointer;">Title</td>
                                @foreach($table_columns_active as $value)
                                    @if($value->enabled_by_user!==0)
                                        <td data-orderable="false" class="text-center titleheading px-1 titleheading2"
                                            data-column-id="{{ $value->id }}"
                                           onclick="return removecolumn('{{$value->id}}')" style="background-color:#fff;">
                                           {{$value->column_name}}                                               
                                        </td>
                                    @else
                                        <td class="text-center titleheading px-1 titleheading2"
                                            data-orderable="false" data-column-id="{{ $value->id }}" style="background-color:#fff;">
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
                                <td class="text-center" style="font-size:10px;"><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td>                                
                                <td class="text-center" style="font-size:10px;">{{ $no=$no+1}}</td>
                                <td  style="font-size:12px;word-wrap: break-word;" class="text-center">
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
                                <td class="text-center font-w600 px-1" style="font-size:10px;">
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
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:#0033cc">{{ $cat }}</td>
                                            @elseif($cat=="External Audit")
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:orange">{{$cat}}</td>
                                            @elseif($cat=="Management Review")
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:#33cc33">{{$cat}}</td>
                                            @elseif($cat=="Corrective Action")
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:#ff0066">{{$cat}}</td>
                                            @elseif($issue->category=='Legal Requirement')
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:#ff0066">{{$issue->category}}</td>
                                            @elseif($issue->category=='Incident Investigation')
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:#ff0066">{{$issue->category}}</td>
                                            @elseif($issue->category=="Management Review")
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:#33cc33">{{$issue->category}}</td>
                                            @else
                                            <td class="text-center px-1" style="font-size:10px;width: 5%;color:black">{{$cat}}</td>
                                            @endif
                                        @break
                                        @case('type')
                                            <td class="text-center px-1" style="font-size:10px;">
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
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$dept?$dept:'-'}}
                                            </td>
                                        @break
                                        @case('division')
                                            @if(in_array('division',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$division?$division:'-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('subdivision')
                                            @if(in_array('subdivision',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$sub_division?$sub_division:'-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('raisedby')
                                            @if(in_array('raisedby',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->postedByCorrective ? $issue->postedByCorrective->name : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('financial loss')
                                            @if(in_array('financial loss',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->financial_loss ? $issue->financial_loss : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('potential cost saving')
                                            @if(in_array('potential cost saving',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->potential_cost_savings ? $issue->potential_cost_savings : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('responsibility')
                                            @if(in_array('responsibility',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{ $responsible_names ?  $responsible_names : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('email remainder for action')
                                            @if(in_array('email remainder for action',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->frequency ? $issue->frequency : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('due date')
                                            @if(in_array('due date',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('investigation team lead')
                                            @if(in_array('investigation team lead',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->teamLead ? $issue->teamLead->name : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('investigation target date')
                                            @if(in_array('investigation target date',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            @if($issue->investigation_date!=null)
                                               {{$issue->investigation_date?date('d-m-Y',strtotime($issue->investigation_date)) : '-'}}
                                            @else
                                            --
                                            @endif
                                            </td>
                                            @endif
                                        @break
                                        @case('source')
                                            @if(in_array('source',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->source ? $issue->source : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('added date')
                                            @if(in_array('added date',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->created_at ? date('d-m-Y',strtotime($issue->created_at)) : date('d-m-Y',strtotime($issue->postdate)) }}
                                            </td>
                                            @endif
                                        @break
                                        @case('action reviewed by')
                                            @if(in_array('action reviewed by',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->name : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('action reviewed date')
                                            @if(in_array('action reviewed date',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->action_reviewed_at ? date('d-m-Y',strtotime($issue->action_reviewed_at)) : '-' }}
                                            </td>
                                            @endif
                                        @break
                                        @case('closure reviewed by')
                                            @if(in_array('closure reviewed by',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->closedByCorrective ? $issue->closedByCorrective->name : '-'}}
                                            </td>
                                            @endif
                                        @break
                                        @case('closure reviewed date')
                                            @if(in_array('closure reviewed date',$table_columns_names_active))
                                            <td class="text-center px-1" style="font-size:10px;">
                                            {{$issue->closed ? date('d-m-Y',strtotime($issue->closed)) : '-' }}
                                            </td>
                                            @endif
                                        @break

                                        @default
                                        @break
                                    @endswitch
                                @endforeach
                                <td style="font-size:10px;" class="text-center px-1">
                                    @if($issue->status == 'closed' && $issue->closed <= $issue->correction_target_date) 
                                    <span class="status status-success">{{ucfirst($issue->status)}}</span>
                                    @elseif($issue->status == 'closed' && $issue->closed > $issue->correction_target_date)
                                    <?php $issue->status='Closed After Due' ?>
                                    <span class="status status-warning">{{$issue->status}}</span>
                                    @else
                                    <span class="status status-blue">{{ucfirst($issue->status)}}</span>
                                    @endif
                                </td>
                                <td style="font-size:10px;" class="text-center px-1">
                                    {{-- @if($issue->processstage=="Awaiting actions") --}}
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $issue->average_percent }}%;" aria-valuenow="{{ $issue->average_percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">{{ $issue->average_percent }}%</p>

                                </td>
                                <td class="font-w600 font-size-sm text-center">
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                            @if(in_array(Auth::user()->email, $issuereviewers))
                                            <a href="{{ route('user.corrective.reopen', ['id' => $issue->id])}}" class="dropdown-item" style="font-size:10px;">Reopen</a>
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
                </div>
            </div>
        </div>
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
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{ asset('js/datatable_extension_corrective.js') }}"></script>
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
                        url: '/user/corrective/closed-table/removecolumn',
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
        // ------------------------------------table column filter starts------------------------------------------------//
        let correctivecolumndetails = <?=$table_columns_active?>;
        enableDragDrop('#tabledata', '{{ route("corrective.closed.table.column.updateposition") }}', function () {
            location.reload();
        });

        if(correctivecolumndetails.length > 0) {
            const $preInitData = {
                'category': {
                    'options': "{{ $allcategoriesname }}",
                    'display_name': "Category",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'type': {
                    'options': "{{ $alltypenames }}",
                    'display_name': "Type",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'department': {
                    'options': "{{ $alldeptnames }}",
                    'display_name': "Department",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'division': {
                    'options': "{{ $alldivisionnames }}",
                    'display_name': "Division",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':true
                },
                'subdivision': {
                    'options': "{{ $allsubdivisionnames }}",
                    'display_name': "Subdivision",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':true
                },
                'raisedby': {
                    'options': "{{ $allraisedbynames }}",
                    'display_name': "Raisedby",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'financial loss': {
                    'options': '',
                    'display_name': '',
                    'is_enable_remove': true,
                    'is_enable_filter': false,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'potential cost saving': {
                    'options': '',
                    'display_name': '',
                    'is_enable_remove': true,
                    'is_enable_filter': false,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'responsibility': {
                    'options': "{{ $allusernames }}",
                    'display_name': "Responsibility",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'email remainder for action': {
                    'options': '',
                    'display_name': '',
                    'is_enable_remove': true,
                    'is_enable_filter': false,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'due date': {
                    'options': "{{ $years }}",
                    'display_name': "Due Date",
                    'is_enable_remove': false,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'investigation team lead': {
                    'options': "{{ $allteamleadnames }}",
                    'display_name': "Investigative Team Lead",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'investigation target date': {
                    'options': '',
                    'display_name': '',
                    'is_enable_remove': true,
                    'is_enable_filter': false,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'source': {
                    'options': '{{$sourceNames}}',
                    'display_name': "Source",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':true
                },
                'added date': {
                    'options': "{{ $addeddateyears }}",
                    'display_name': "Added Date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'action reviewed by': {
                    'options': "{{ $allactionreviewednames }}",
                    'display_name': "Action Reviewed by",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'action reviewed date': {
                    'options': "{{ $action_reviewed_date_years }}",
                    'display_name': "Action Reviewed date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'closure reviewed by': {
                    'options': "{{ $allclosurereviewednames }}",
                    'display_name': "Closure Reviewed by",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
                },
                'closure reviewed date': {
                    'options': "{{ $closed_reviewed_date_years }}",
                    'display_name': "Closure Reviewed date",
                    'is_enable_remove': true,
                    'is_enable_filter': true,
                    'filter_postfix': '',
                    'strict_ness':false
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
                            $columnData['options'],
                            $columnData['display_name'],
                            $columnIndex,
                            $columnData['filter_postfix'],
                            $columnData['strict_ness']
                        )
                    }
                }
            }
        }
        // ------------------------------------Ends table column filter------------------------------------------------//
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
            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                var columnIndex = table.column('#statusselectdata').index();
                let allstatusnames = "{{ $allpstages }}".split(',');
                let status_text = "";
                for (var i = 0; i < allstatusnames.length; i++) {
                    status_text += `<option value="${allstatusnames[i]}">${allstatusnames[i]}</option>`;
                }
                let $deptheading = $(`<select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="status_dropdown">
                            <option value="">Process&nbspStage&nbsp;&nbsp;&#9207;</option>` + status_text +
                    `
                        </select>`);
                $('#statusselectdata').html($deptheading);
                $("#status_dropdown").on('change', function() {
                    let selectedValue = $(this).val();
                    let firstOption = $('#status_dropdown option:first');
                    if (selectedValue) {
                        table.columns(columnIndex).search("^" + selectedValue + "$", true, false).draw();
                        $(this).addClass('text-primary');
                        $("#status_dropdown option").css('color', 'black')
                    } else {
                        table.column(columnIndex).search('').draw();
                        $(this).removeClass('text-primary');
                        $('#status_dropdown').val('');
                    }
                    firstOption.html('Process&nbspStage&nbsp;&nbsp;&#9207;');
                });
            });
        });
        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

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
    @include('exporting.forcorrective');

@endsection
