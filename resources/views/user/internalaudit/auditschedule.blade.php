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
            <!-- <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form> -->
            <form method="post" action="{{ url('/user/convert/file/' . 'tvalue1') }}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf
                </button>
                <input type="hidden" id="tvalue1" name="tvalue1" />
                <!-- <input type="hidden" name="paper" value="{{ count($data) > 18 ? 'a3' : 'a4' }}">
                <input type="hidden" name="orientation" id="orientation" value="{{ count($data) <= 12 ? 'portrait' : 'landscape' }}"> -->
                <input type="hidden" name="paper" value="a3">
                <input type="hidden" name="orientation" id="orientation" value="portrait">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
            </form>
        </li>
        <li>
            <button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel
            </button>
        </li>
        <li>
            <button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word
            </button>
        </li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/internalaudit/addaudit')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    <title>auditschedule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    <link rel="stylesheet" href="{{ asset('css/workflow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register_stage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">

    <style>
        body {
            overflow-y: auto;
        }

        .top-right {
            position: relative;
            top: -54px;
            margin-left: 35% !important;
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



        /* @media screen and (max-width: 1800px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 70px !important;
            }
        }

        @media screen and (max-width: 1400px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 30px !important;
            }
        }

        @media screen and (max-width: 1200px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 30px !important;
            }
        }

        @media screen and (max-width: 1020px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 30px !important;

            }
        }

        @media screen and (max-width: 900px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 20px !important;
            }
        }

        @media screen and (max-width: 600px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 20px !important;
            }
        }

        @media screen and (max-width: 300px) {
            .top-right {
                position: absolute;
                top: -14px;
                margin-left: 40px !important;
            }
        } */
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

       
        .dropdown_checklist {
            position: relative;
            display: inline-block;
        }

        .dropdown_checklist-item {
            width: 150px;
            color:#333 !important;
            cursor: pointer;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s ease;
            position: relative;
        }

        .dropdown_checklist-item:hover {
            background-color:#f1f1f1;
        }
        .submenu {
            display: none;
            position: absolute;
            top: 0;
            left: -140px;
            background-color:#fff;
            /* padding: 5px 0; */
            padding: 0;
            list-style-type: none;
            margin: 0;
            border-radius: 4px;
            width: 150px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            
        }

        .dropdown_checklist:hover .submenu {
            display: block;
            opacity: 1;
            visibility: visible;
        }
        
        .submenu li {
            padding: 0; 
            margin: 0;
        }
        .submenu li a {
            text-decoration: none;
            color: #333;
            padding:2px 2px 10px 10px;
            display: block;
            transition: background-color 0.3s ease;
            font-size: 11px;
            text-align: left;
            /* border-bottom: 1px solid #ddd;  */
        }     
        .submenu li:last-child a {
            border-bottom: none;
        }
   
        .submenu li a:hover {
            background-color: #f1f1f1;
        }

        .dropdown_checklist-item i {
            cursor: pointer;
        }

    </style>
</head>
<body>
    <input type="hidden" value="a1" id="tabselected">
    <div class="content" style="width:100%">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">Audit Schedule</li>
                <li class="breadcrumb-item">
                    Audit Management
                </li>
            </ol>
        </nav>
        <div class="block">
            <div class="block-content block-content-full">
                @if(Session::has('errmsg'))
                    <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('errmsg')!!}
                    </div>
                @endif
                @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" style="right: 10px; top: 5px; cursor: pointer" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
                <div class="scrollable" style="height:450px;overflow-y:scroll;" id="content">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="maintable">

                        <thead style="z-index:5;">
                            <tr>
                                <th style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1;"></th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width: 5%;position:sticky;top:-1;">S.No
                                </th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1;">Audit
                                    ID
                                </th>
                                <th id="deptselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                <th class="text-center" style="font-size:10px;z-index:1;text-transform:capitalize;width:20%;position:sticky;top:-1;">
                                    <i class="far fa-user" style="font-size:14px;">
                                </th>
                                <th id="auditorsselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                <th id="quarterselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;top:-1px;"></th>

                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                    From&nbsp&nbspDate&nbsp
                                </th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                    To&nbsp&nbspDate&nbsp&nbsp&nbsp&nbsp
                                </th>
                                <th class="text-center" style="font-size:10px;z-index:1;text-transform:capitalize;width:20%;position:sticky;top:-1;">
                                    <i class="far fa-user" style="font-size:14px;">
                                </th>
                                <th id="auditeeselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                <th id="stageselectdata" data-orderable="false" class="text-center" style="width:5%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                <th class="text-center titleheading" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;width:200px !important;">Audit&nbsp;Status</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;z-index: 1;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;?>
                            @foreach($data as $value)
                            <?php $rr = $value->id;
                                $fullauditors = $value->email;
                                $explode = explode(',', $fullauditors);
                                $department_hod = DB::table('departments')->where('id', $value->department)->value('hod');
                                ?>

                            <tr>
                                <td><input class="text-center font-size-sm" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->aud_id}}</td>

                                <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                    <em class="text-muted">{{ $value->departmentObj ? $value->departmentObj->departmentname : '' }}</em>
                                </td>

                                <td>
                                    @php
                                    $auditors_arrs = explode(',',$value->auditors);
                                    $auditor_exists = array();
                                    $auditor_not_exists = array();
                                    @endphp
                                    @foreach($auditors_arrs as $auditors_arr)
                                    @php
                                    $found = App\User::where('name',$auditors_arr)->first();
                                    if($found){
                                    array_push($auditor_exists,$found->name);
                                    }
                                    else{
                                    array_push($auditor_not_exists,$auditors_arr);
                                    }
                                    @endphp
                                    @endforeach

                                    @php
                                    $auditor_imp = implode(',',$auditor_exists);
                                    $auditor_exists_arr = explode(',',$auditor_imp);
                                    $first_auditor = App\User::where('name',$auditor_exists_arr[0])->first();
                                    @endphp
                                    <div class="text-center /*main-img-aud-container*/">
                                        @if($first_auditor)
                                        <img style="margin-bottom:5px;" src="{{($first_auditor->profile_picture && File::exists(public_path('/storage/profile/'.$first_auditor->profile_picture))) ? asset("storage/profile/".$first_auditor->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$first_auditor->name}}" />
                                        @if(count($auditors_arrs) > 1)
                                        <div class="top-right">
                                            <div class="avatar-dropdown">
                                                <div class="avatar-dot text-center">+{{count($auditors_arrs) - 1}} </div>
                                                {{-- <div class="avatar-dot text-center">345 </div> --}}
                                                <div class="avatar-dropdown-content">
                                                    @foreach($auditors_arrs as $auditors_arr)
                                                    @if($auditors_arr!=$first_auditor->name)
                                                    @php
                                                    $auditor_lists = App\User::where('name',$auditors_arr)->first();
                                                    @endphp
                                                    @if($auditor_lists)
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <img style="margin-bottom:5px;" src="{{($auditor_lists->profile_picture && File::exists(public_path('/storage/profile/'.$auditor_lists->profile_picture))) ? asset("storage/profile/".$auditor_lists->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditor_lists->name}}" />
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{ucfirst($auditor_lists->name)}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @else
                                                    @foreach($auditor_not_exists as $auditor_not_exist)
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditor_not_exist}}" />
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">
                                                                    {{$auditor_not_exist}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @endforeach
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @else
                                        @php
                                        $auditor_not_imp = implode(',',$auditor_not_exists);
                                        $auditor_not_exists_arr = explode(',',$auditor_not_imp);
                                        @endphp
                                        @if(count($auditor_not_exists_arr) > 1)
                                        <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditor_not_exists_arr[0]}}" />
                                        <div class="top-right">
                                            <div class="avatar-dropdown">
                                                <div class="avatar-dot text-center">+{{count($auditor_not_exists_arr) - 1}} </div>
                                                {{-- <div class="avatar-dot text-center">24</div> --}}
                                                <div class="avatar-dropdown-content">
                                                    @foreach($auditor_not_exists as $auditor_not_exist)
                                                    @if($auditor_not_exist!=$auditor_not_exists_arr[0])
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditor_not_exist}}" />
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{$auditor_not_exist}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditor_not_exists_arr[0]}}" />
                                        @endif
                                        @endif
                                    </div>
                                </td>
                                <td style="font-size:10px;" class="text-center">
                                    {{$value->auditors}}
                                    @if($value->auditors)
                                        @php
                                            $auditors = explode(",", $value->auditors);
                                        @endphp
                                        @foreach($auditors as $item)
                                            <span style="display: none;">{{ $item . "_f" }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell text-center" style="font-size:10px;">
                                    <em class="text-muted">{{$value->quarter}}</em>
                                </td>
                                <td class="text-center " style="font-size:10px;">{{date('d-m-Y',strtotime($value->date))}} </td>
                                <td class="text-center " style="font-size:10px;">{{date('d-m-Y',strtotime($value->enddate))}}</td>
                                <td>
                                    @php
                                    $auditee_arrs = explode(',',$value->contact);
                                    $auditee_exists = array();
                                    $auditee_not_exists = array();
                                    @endphp
                                    @foreach($auditee_arrs as $auditee_arr)
                                    @php
                                    $auditee_found = App\User::where('name',$auditee_arr)->first();
                                    if($auditee_found){
                                    array_push($auditee_exists,$auditee_found->name);
                                    }
                                    else{
                                    array_push($auditee_not_exists,$auditee_arr);
                                    }
                                    @endphp
                                    @endforeach

                                    @php
                                    $auditee_imp = implode(',',$auditee_exists);
                                    $auditee_exists_arr = explode(',',$auditee_imp);
                                    $first_auditee = App\User::where('name',$auditee_exists_arr[0])->first();
                                    @endphp
                                    <div class="text-center /*main-img-aud-container*/">
                                        @if($first_auditee)
                                        <img style="margin-bottom:5px;" src="{{($first_auditee->profile_picture && File::exists(public_path('/storage/profile/'.$first_auditee->profile_picture))) ? asset("storage/profile/".$first_auditee->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$first_auditee->name}}" />
                                        @if(count($auditee_arrs) > 1)
                                        <div class="top-right">
                                            <div class="avatar-dropdown">
                                                <div class="avatar-dot text-center">+{{count($auditee_arrs) - 1}} </div>
                                                <div class="avatar-dropdown-content">
                                                    @foreach($auditee_arrs as $auditee_arr)
                                                    @if($auditee_arr!=$first_auditee->name)
                                                    @php
                                                    $auditee_lists = App\User::where('name',$auditee_arr)->first();
                                                    @endphp
                                                    @if($auditee_lists)
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <img style="margin-bottom:5px;" src="{{($auditee_lists->profile_picture && File::exists(public_path('/storage/profile/'.$auditee_lists->profile_picture))) ? asset("storage/profile/".$auditee_lists->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditee_lists->name}}" />
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{ucfirst($auditee_lists->name)}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @else
                                                    @foreach($auditee_not_exists as $auditee_not_exist)
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditee_not_exist}}" />
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">
                                                                    {{$auditee_not_exist}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @endforeach
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @else
                                        @php
                                        $auditee_not_imp = implode(',',$auditee_not_exists);
                                        $auditee_not_exists_arr = explode(',',$auditee_not_imp);
                                        @endphp
                                        @if(count($auditee_not_exists_arr) > 1)
                                        <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditee_not_exists_arr[0]}}" />
                                        <div class="top-right">
                                            <div class="avatar-dropdown">
                                                <div class="avatar-dot text-center">+{{count($auditee_not_exists_arr) - 1}} </div>
                                                <div class="avatar-dropdown-content">
                                                    @foreach($auditee_not_exists as $auditee_not_exist)
                                                    @if($auditee_not_exist!=$auditee_not_exists_arr[0])
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditee_not_exist}}" />
                                                            </div>
                                                            <div class="col-md-9 text-left">
                                                                <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{$auditee_not_exist}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="{{$auditee_not_exists_arr[0]}}" />
                                        @endif
                                        @endif
                                    </div>
                                </td>
                                <td style="font-size:10px;" class="text-center">
                                    {{$value->contact}}
                                    @if($value->contact)
                                        @php
                                            $auditees = explode(",", $value->contact);
                                        @endphp
                                        @foreach($auditees as $item)
                                            <span style="display: none;">{{ $item . "_f" }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell text-center" style="font-size:12px;">
                                    @if($value->status=='Accepted')
                                    <span class="status status-success">{{$value->status}}</span>
                                    @elseif($value->status=='Completed')
                                    <span class="status status-blue">{{$value->status}}</span>

                                    @elseif($value->status=='Rejected')
                                    <span class="status status-red">{{$value->status}}</span>
                                    @elseif($value->status=='Planned')
                                    <span class="status status-warning">{{$value->status}}</span>
                                    @else
                                    <span class="status status-blue">{{$value->status}}</span>
                                    @endif
                                </td>
                                @php
                                $all_checklist_percentage = DB::table('audit_checklist_new_records')->where('audit_schedule_id', $value->id)->pluck('completed_percentage')->toArray();
                                $average_percent = $all_checklist_percentage ? round(array_sum($all_checklist_percentage) / count($all_checklist_percentage), 2) : 0;
                                @endphp
                                <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                    <div class="progress mb-1" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{$average_percent}}%;" aria-valuenow="{{$average_percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0">{{$average_percent}}%</p>
                                </td>

                                <td style="font-size:10px" class="text-left">
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                            @if($value->status=='Planned')
                                            @php
                                            $auditees_arrs = explode(',',$value->contact);
                                            $auditees = array();
                                            foreach($auditees_arrs as $auditees_arr){
                                            $audexists = App\User::where('name',$auditees_arr)->first();
                                            if($audexists){
                                            array_push($auditees,$audexists->email);
                                            }
                                            }
                                            @endphp
                                            @if($department_hod == Auth::user()->email || in_array(Auth::user()->email,$auditees))
                                            <a href="{{ url('/user/internalaudit/accept?val='.$rr)}}" class="dropdown-item" style="font-size:10px;" onclick="return confirm('Are you sure to submit?')">
                                                <i class="far fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;Accept
                                            </a>
                                            <a href="{{ url('/user/internalaudit/propose?val='.$rr)}}" class="dropdown-item" style="font-size:10px;" onclick="return confirm('Are you sure to submit?')">
                                                <i class="far fa-share-square" style="font-size:10px;"></i>&nbsp;&nbsp;Propose another date
                                            </a>
                                            @endif
                                            @endif
                                            @if($value->status!='Planned')
                                            @if($value->status!='Completed')
                                            @if(in_array(auth()->user()->email, $auditorList) && $value->status=='Rejected')
                                                <a href="{{ url('/user/internalaudit/update?val='.$rr)}}" class="dropdown-item" style="font-size:10px;" onclick="return confirm('Are you sure to submit?')">
                                                <i class="fa fa-edit" style="font-size:10px;"></i>&nbsp;&nbsp;Update</a>
                                            @endif

                                            @foreach($explode as $test)
                                            @if(Auth::user()->email==$test && $value->status=='Accepted')
                                            @if($average_percent == 100)
                                            <a href="{{ url('/user/internalaudit/complete?val='.$rr)}}" class="dropdown-item" style="font-size:10px;width: 90px;">
                                                <i class="far fa-2x fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;Completed
                                            </a>
                                            @else
                                            <a href="#" class="button" style="font-size:10px;color:#888888;margin-left:12px;">
                                                <i class="far fa-2x fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;Completed
                                            </a>
                                            @endif
                                            @endif
                                            @endforeach
                                            @endif
                                            @endif

                                            @if($value->status!='Rejected')
                                                <a href="{{url('/user/actionsystem/addauditobservation?val='.$value->id)}}" class="dropdown-item" style="font-size:10px;width: 150px;">
                                                    <i class="far fa-list-alt" style="font-size:10px;"></i>&nbsp;&nbsp;Add Audit Observation</a>
                                                <?php
                                                    // $records = DB::table('audit_checklist_new_records')->where('audit_schedule_id', $value->id)->get();
                                                    $records = App\AuditChecklistNewRecord::where('audit_schedule_id', $value->id)->get();
                                                    $checklist_created = App\AuditScheduleChecklist::where('audit_id',$value->id)->get();                                
                                                ?>
                                               
                                                @if(count($checklist_created)>0 && (count($records)>0))
                                                    <div class="dropdown_checklist">
                                                        <a href="#" class="dropdown_checklist-item" style="font-size:10px;padding-top:10px;padding-bottom:10px;">
                                                            <i class="far fa-eye" style="font-size:10px;cursor:pointer;padding-left:12px;"></i>&nbsp;
                                                            <span style="padding-right:12px;"> View Checklist</span>
                                                            <ul class="submenu"> 
                                                                @foreach($checklist_created as $item) 
                                                                @php
                                                                    $check_records_exists = App\AuditChecklistNewRecord::where('audit_schedule_id', $item->audit_id)
                                                                                                                        ->where('checklist_id',$item->checklist_id)
                                                                                                                        ->get();
                                                                @endphp                        
                                                                    <li>
                                                                        @if(count($check_records_exists)>0)
                                                                            @foreach($check_records_exists as $check_records_exist)
                                                                                <a href="{{route('checklistnewrecordsdisplay',['id'=>$check_records_exist->id])}}">
                                                                            @break
                                                                            @endforeach
                                                                        @elseif(count($check_records_exists)==0)
                                                                            <a href="{{route('createchecknewrecord',['id'=>$item->checklist_id,'auditid'=>$value->id])}}">
                                                                        @endif
                                                                            <i class="far fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;
                                                                            {{ $item->getChecklistnames ? ucfirst($item->getChecklistnames->checklist_name):'---'}}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </a>
                                                    </div>
                                                @elseif((count($records)==0) && count($checklist_created)>0)
                                                    <div class="dropdown_checklist">
                                                        <a href="#" class="dropdown_checklist-item" style="font-size:10px;padding-top:10px;padding-bottom:10px;">
                                                            <i class="far fa-eye" style="font-size:10px;cursor:pointer;padding-left:12px;"></i>&nbsp;
                                                            <span style="padding-right:12px;"> View Checklist</span>
                                                            <ul class="submenu"> 
                                                                @foreach($checklist_created as $item)                         
                                                                    <li><a href="{{route('createchecknewrecord',['id'=>$item->checklist_id,'auditid'=>$value->id])}}"><i class="far fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;
                                                                            {{ $item->getChecklistnames ? ucfirst($item->getChecklistnames->checklist_name):'---'}}
                                                                        </a>
                                                                    </li>
                                                                @endforeach  
                                                            </ul>
                                                        </a>
                                                    </div>
                                                @elseif((count($records)>0) && count($checklist_created)==0)
                                                    <div class="dropdown_checklist">
                                                        <a href="#" class="dropdown_checklist-item" style="font-size:10px;padding-top:10px;padding-bottom:10px;">
                                                            <i class="far fa-eye" style="font-size:10px;cursor:pointer;padding-left:12px;"></i>&nbsp;
                                                            <span style="padding-right:12px;"> View Checklist</span>
                                                            <ul class="submenu"> 
                                                                @foreach($records as $record)                         
                                                                    <li><a href="{{route('checklistnewrecordsdisplay',['id'=>$record->id])}}"><i class="far fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;
                                                                        {{ $record->getChecklistnames ? ucfirst($record->getChecklistnames->checklist_name):'---'}}
                                                                        </a>
                                                                    </li>
                                                                @endforeach  
                                                            </ul>
                                                        </a>
                                                    </div>
                                                @else
                                                    <a href="#" class="button dropdown-item" data-toggle="modal" data-target="#NorecordsModal" style="font-size:10px;color:#888888;">
                                                        <i class="far fa-eye" style="font-size:10px;cursor:pointer"></i>&nbsp;&nbsp;View Checklist
                                                    </a>
                                                @endif
                                            @endif
                                            @if($value->status != 'Completed' && in_array(auth()->user()->email, $auditorList))
                                                <a href="{{route('user.internalaudit.linkchecklist',['id'=> $value->id ])}}"
                                                   class="dropdown-item" style="font-size:10px;width: 150px;padding-top:13px;">
                                                    <i class="fa fa-external-link-alt" style="font-size:10px;cursor:pointer"></i>&nbsp;&nbsp;Link and Unlink Checklist
                                                </a>
                                            @else
                                                <a href="#" class="button dropdown-item" style="font-size:10px;color:#888888;padding-top:13px;">
                                                    <i class="fa fa-external-link-alt" style="font-size:10px;cursor:pointer"></i>&nbsp;&nbsp;Link and Unlink Checklist
                                                </a>
                                            @endif
                                            @if($value->status == 'Accepted' && in_array(auth()->user()->email, $auditorList))
                                                <a href="{{route('user.internalaudit.reschedule',['id'=> $value->id ])}}"
                                                   class="dropdown-item" style="font-size:10px;width: 150px;">
                                                    <i class="fas fa-reply" style="font-size:10px;cursor:pointer"></i>&nbsp;&nbsp;Reschedule
                                                </a>
                                            @else
                                                <a href="#" class="button dropdown-item" style="font-size:10px;color:#888888;">
                                                    <i class="fas fa-reply" style="font-size:10px;cursor:pointer"></i>&nbsp;&nbsp;Reschedule
                                                </a>
                                            @endif

                                        </div>
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $value->id }}" data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{$value->id}}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                    </div>

                                    <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped" style="font-size:13px;">
                                                    <tr>
                                                        <th style="width:70px">Audit id</th>
                                                        <td>{{ $value->aud_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <td class="text-primary">{{ $value->departmentObj ? $value->departmentObj->departmentname : '' }}</td>
                                                    </tr>
                                                    @if($value->division_id!=null)
                                                        <?php
                                                            $divison_name = DB::table('divisions')
                                                                            ->where('id',$value->division_id)
                                                                            ->value('name');
                                                        ?>
                                                        <tr>
                                                            <th>Division</th>
                                                            <td class="text-dark">
                                                                <span style="color:#808080;">{{$divison_name}}</span>
                                                            </td>
                                                        </tr>
                                                    @elseif($value->division_id==0)
                                                      @php
                                                        $getdivisiondetails = \App\AuditScheduleDivision::where('audit_id',$value->id)->get();
                                                      @endphp
                                                        <tr>
                                                            <th>Division</th>
                                                            <td class="text-dark">
                                                                <span style="color:#808080;">
                                                                  @foreach($getdivisiondetails as $getdivisiondetail)
                                                                     @php
                                                                       $div_name = \App\Division::where('id',$getdivisiondetail->division_id)->value('name');
                                                                     @endphp
                                                                     {{$div_name}}<br/>
                                                                  @endforeach
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @else
                                                    --
                                                    @endif
                                                    @if($value->area_id!=null)
                                                        <?php
                                                        $area_name = DB::table('areas')
                                                                            ->where('id',$value->area_id)
                                                                            ->value('name');
                                                    ?>
                                                        <tr>
                                                            <th>Subdivision</th>
                                                            <td><span style="color:#b5651d">{{$area_name}}</span></td>
                                                        </tr>
                                                    @elseif($value->division_id==0)
                                                      @php
                                                        $getareadetails = \App\AuditScheduleArea::where('audit_id',$value->id)->get();
                                                      @endphp
                                                        <tr>
                                                            <th>Subdivision</th>
                                                            <td class="text-dark">
                                                                <span style="color:#b5651d;">
                                                                  @foreach($getareadetails as $getareadetail)
                                                                     @php
                                                                       $area_multiple_name = \App\Area::where('id',$getareadetail->area_id)->value('name');
                                                                     @endphp
                                                                     {{$area_multiple_name}}<br/>
                                                                  @endforeach
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @else
                                                    --
                                                    @endif
                                                    <tr>
                                                        <th>Scope of audit</th>
                                                        <td><?php echo nl2br($value->scope) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Auditors</th>
                                                        <td>{{ $value->auditors }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Auditee</th>
                                                        <td>{{ $value->contact }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>From date</th>
                                                        <td>{{$value->date}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Time</th>
                                                        <td>{{$value->time}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>To date</th>
                                                        <td>{{$value->enddate}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Focus</th>
                                                        <td><?php echo nl2br($value->focus) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Budgeted hours</th>
                                                        <td>{{$value->budgeted}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Quarter</th>
                                                        <td>{{$value->quarter}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Checklist New Records ID</th>
                                                        @php
                                                        $records = DB::table('audit_checklist_new_records')
                                                        ->where('audit_schedule_id', $value->id)
                                                        ->get();
                                                        @endphp
                                                        <td>
                                                            @if(count($records)>0)
                                                            @foreach($records as $record)
                                                            <span class="badge badge-info">
                                                                <a href="{{route('checklistnewrecordsdisplay',['id'=>$record->id])}}" class="badge badge-info  font-weight-bold" style="font-size:10px;">
                                                                    {{$record->audit_checklist_id}}
                                                                </a>
                                                            </span>
                                                            @endforeach
                                                            @else
                                                            --
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Process stage</th>
                                                        <td>
                                                            @if($value->status=='Accepted')
                                                            <span class="status status-success">{{$value->status}}</span>
                                                            @elseif($value->status=='Completed')
                                                            <span class="status status-blue">{{$value->status}}</span>

                                                            @elseif($value->status=='Rejected')
                                                            <span class="status status-red">{{$value->status}}</span>
                                                            @elseif($value->status=='Planned')
                                                            <span class="status status-warning">{{$value->status}}</span>
                                                            @else
                                                            <span class="status status-blue">{{$value->status}}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Added at</th>
                                                        <td>{{$value->created_at}}</td>
                                                    </tr>
                                                    @php
                                                    $dept = DB::table('departments')->where('id', $value->department)->value('id');
                                                    $year = $value->year;
                                                    $total_observations_under_same_department = DB::table('issues')->where('department', $dept)->where('year', $year)->where('category', 'Internal Audit')->count();

                                                    $observations = DB::table('issues')->where('department', $dept)->where('year', $year)->where('auditid', $value->id)->where('category', 'Internal Audit')->get();
                                                    $ocount = count($observations);

                                                    $admintype = DB::table('types')->pluck('name');
                                                    $admintypes = DB::table('types')->orderBy('order', 'ASC')->get();
                                                    $openobservations = DB::table('issues')->where('department', $dept)->where('year', $year)->where('category', 'Internal Audit')->where('status', 'open')->get();
                                                    $opcount = count($openobservations);
                                                    $closedobservations = DB::table('issues')->where('department', $dept)->where('year', $year)->where('category', 'Internal Audit')->where('status', 'closed')->get();
                                                    $clcount = count($closedobservations);
                                                    $ncrid = DB::table('issues')
                                                    ->where('department', $dept)->where('year', $year)
                                                    ->where('category', 'Internal Audit')
                                                    ->where('auditid', $value->id)
                                                    ->pluck('issueid');
                                                    $ncrid_count = count($ncrid);
                                                    $ncr_status_array = [];
                                                    $switch = '';

                                                    @endphp
                                                    <tr>
                                                        <th>Total Observations</th>
                                                        {{-- <td>{{$ocount}}/{{$total_observations_under_same_department}}
                                </td> --}}
                                <td>{{$ocount}}</td>
                            </tr>
                            @foreach($admintypes as $n)
                            @php
                            $alltype = DB::table('issues')->where('type', $n->order)->where('department', $dept)->where('year', $year)->where('auditid', $value->id)->where('category', 'Internal Audit')->get();
                            $totalalltype = DB::table('issues')->where('type', $n->order)->where('department', $dept)->where('year', $year)->where('category', 'Internal Audit')->count();
                            $count = count($alltype);
                            @endphp
                            <tr>
                                <th>{{$n->name}} Observations</th>
                                {{-- <td>{{$count}}/{{$totalalltype}}</td> --}}
                                <td>{{$count}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <th>NCR Id</th>
                                <td>
                                    @foreach($ncrid as $nc)
                                    @php
                                    $status = DB::table('issues')->where('issueid', $nc)->pluck('status');
                                    foreach ($status as $s) {
                                    $st = $s;
                                    $ncr_status_array[] = $st;
                                    }
                                    if(count($ncr_status_array)) {
                                    if(count(array_unique($ncr_status_array)) === 1 && $ncr_status_array[0] === 'open') {
                                    $switch = 'all_open';
                                    } else if(count(array_unique($ncr_status_array)) === 1 && $ncr_status_array[0] === 'closed') {
                                    $switch = 'all_closed';
                                    } else if(count(array_unique($ncr_status_array)) !== 1 && in_array('open', $ncr_status_array)) {
                                    $switch = 'at_least_one_is_open';
                                    }
                                    }

                                    @endphp

                                    @if($st=='closed')
                                    <span class="status status-success">{{$nc}}</span>
                                    @else
                                    <span class="status status-red">{{$nc}}</span>
                                    @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>NCR Status</th>
                                <td>
                                    {{-- @if($opcount > 0)
                                    <span class="status status-red">This audit remains open because there are still a few non-conformance reports (NCRs) in progress.</span>
                                    @endif
                                    @if($ocount == $clcount)
                                    <span class="status status-success">This audit is closed</span>
                                    @endif --}}

                                    @if(!$ncrid_count)
                                    <span>--</span>
                                    @endif

                                    @switch($switch)

                                    @case('all_open')
                                    <span class="status status-red">This audit remains open because there are still a few NCRs in progress.</span>
                                    @break
                                    @case('all_closed')
                                    <span class="status status-success">This Audit is Closed.</span>
                                    @break
                                    @case('at_least_one_is_open')
                                    <span class="status status-red">This audit remains open because there are still a few NCRs in progress.</span>
                                    @break
                                    @endswitch

                                </td>
                            </tr>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" style="font-size:11px;" data-dismiss="modal">Close
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
    <!-- END Dynamic Table Full -->
    </div>
    <!--Norecords modal starts-->
    <div class="modal fade" id="NorecordsModal" tabindex="-1" role="dialog" aria-labelledby="NorecordsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">No records have been added yet to view this
                        checklist</p>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Norecords modal  ends-->
    <div class="modal" id="modal-learn-audit-schedule-module" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="m-dialog" role="document">
            <div class="modal-content">

                <div class="m-body">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- 16:9 aspect ratio -->

                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ asset('media/videos/hero_sunrise.mp4') }}" id="audit_video" allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @section('sideitem')
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual width-150 video-btn" id="learn_btn" data-toggle="modal" data-src="{{ asset('media/videos/hero_sunrise.mp4') }}" data-target="#modal-learn-audit-schedule-module">
            <i class="fa fa-1x fa-video" style="color:grey;"></i><span id="learn" class="ml-2">Learn Module</span>
        </button>

    </div>
    {{-- @foreach($mrs as $r) --}}
    {{-- @if(Auth::user()->email==$r) --}}
    @if(Gate::allows('edit-audit') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="/*margin-left:450px;*/" class="btn btn-sm btn-dual" id="edit" value=id[]>
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
    @include('exporting.forauditscheduledetails')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/learn_module.js') }}"></script>
    <script>
        let v_selector = $('#modal-learn-audit-schedule-module');
        let v_id_selector = $('#audit_video');
        displayVideo(v_selector, v_id_selector);
        let learn_audit_selector = $('#learn');
        let learn_audit_btn_selector = $('#learn_btn');
        showOrHide(learn_audit_selector, learn_audit_btn_selector);

    </script>
    <script>
        $("#del").click(function() {
            var dataArr = new Array();
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    $.ajax({
                        type: 'get'
                        , url: "{{route('deleteaudit')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            window.location.href = "{{url('/user/auditschedule')}}";
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
    {{-- --------edit  button ------------}}
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
                    , url: "{{route('editschedule')}}" + "?val=" + (all)
                    , success: function(response) {

                        window.location.href = "{{route('editschedule')}}" + "?val=" + (all);

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
        $(document).ready(function() {
            $("#maintable").one("preInit.dt", function() {
                let table = $('#maintable').DataTable();
                let alldeptnames = "{{ $alldeptnames }}".split(',');
                let dept_text = "";
                for (var i = 0; i < alldeptnames.length; i++) {
                    dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
                }

                $deptheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="dept_dropdown">
                    <option value="">Department&nbsp;&nbsp;&#9207;</option>` + dept_text + `</select>`);

                $('#deptselectdata').html($deptheading);
                $("#dept_dropdown").on('change', function() {
                    if ($(this).val()) {
                        table.columns(3).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#dept_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });

                let allpstages = "{{ $allpstages }}".split(',');
                let stage_text = "";
                for (var i = 0; i < allpstages.length; i++) {
                    stage_text += `<option value="${allpstages[i]}">${allpstages[i]}</option>`;
                }

                $stageheading = $(`<select class="headingselect" style="width:120px;" id="stage_dropdown">
                         <option value="">Process Stage&nbsp;&nbsp;&#9207;</option>` + stage_text + `</select>`);

                $('#stageselectdata').html($stageheading);
                $("#stage_dropdown").on('change', function() {
                    if ($(this).val()) {
                        table.columns(11).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#stage_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });


                let allquarters = "{{ $allquarters }}".split(',');
                let quarters_text = "";
                for (var i = 0; i < allquarters.length; i++) {
                    quarters_text += `<option value="${allquarters[i]}">${allquarters[i]}</option>`;
                }

                $quarterheading = $(`<select class="headingselect" style="width:120px;" id="quarter_dropdown">
                        <option value="">Quarter&nbsp;&nbsp;&#9207;</option>` + quarters_text + `
                    </select>`);

                $('#quarterselectdata').html($quarterheading);
                $("#quarter_dropdown").on('change', function() {
                    if ($(this).val()) {
                        table.columns(6).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#quarter_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });


                let allauditors = "{{ $auditorForFilters }}".split(',');
                let auditors_text = "";
                for (var i = 0; i < allauditors.length; i++) {
                    auditors_text += `<option value="${allauditors[i]}">${allauditors[i]}</option>`;
                }

                $auditorsheading = $(`<select class="headingselect" style="width:120px;" id="auditors_dropdown">
                        <option value="">Auditors&nbsp;&nbsp;&#9207;</option>` + auditors_text + `
                    </select>`);

                $('#auditorsselectdata').html($auditorsheading);
                $("#auditors_dropdown").on('change', function() {
                    if ($(this).val()) {
                        table.columns(5).search($(this).val() + "_f").draw();
                        $(this).addClass('text-primary');
                        $("#auditors_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });

                let allauditees = "{{ $auditeesForFilters }}".split(',');
                let auditees_text = "";
                for (var i = 0; i < allauditees.length; i++) {
                    auditees_text += `<option value="${allauditees[i]}">${allauditees[i]}</option>`;
                }

                $auditeeheading = $(`<select class="headingselect" style="width:120px;" id="auditee_dropdown">
                    <option value="">Auditee&nbsp;&nbsp;&#9207;</option>` + auditees_text + `
                </select>`);

                $('#auditeeselectdata').html($auditeeheading);
                $("#auditee_dropdown").on('change', function() {
                    if ($(this).val()) {
                        table.columns(10).search($(this).val() + "_f").draw();
                        $(this).addClass('text-primary');
                        $("#auditee_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });

            });


        });

    </script>



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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
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
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Audit Log
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Audits
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
    </div>
    <!-- END Apps Modal -->


</body>
</html>
@endsection
