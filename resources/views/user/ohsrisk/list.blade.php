@extends('pages.datatables')
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>

<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; "
        data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')"
                    style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
                <input type="hidden" name="paper" id="paper" value="a1">
                <input type="hidden" name="orientation" id="orientation" value="portrait">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{route('addohsrisk', ['type' => $type])}}" class="btn btn-sm btn-dual"
        style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <a href="{{ url('/user/ohsrisk/folder') }}" class="btn btn-secondary"
        style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>
</div>
@endsection
@section('content')

{{--<style>
    .header {
        margin-left: 7%;
        margin-bottom: -3.6%;
    }
</style>--}}
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
<link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">
<link rel="stylesheet" href="{{asset('css/style.css')}}">

<link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">


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
            /*padding-bottom: .25rem!important;*/
            bottom: 0;
            position: relative;
            display: inline-block;
            clear: both;
        }


    }
</style>
<?php 
    $data3=[]; 
    $data1Ids = $data1->pluck('id')->toArray();
    $data2 = $data2->whereIn('ohs_id', $data1Ids);    
?>
<input type="hidden" value="a1" id="tabselected">
<div class="tab-pane active" id="Register" role="tabpanel">
    <div class="tab-pane active" id="Register" role="tabpanel">
        <!-- Page Content -->

        <div class="content" style="width:100%;">
            <!-- Dynamic Table Full -->
            <div class="block block-rounded" style="margin-left:-1%;">
                {{-- <div class="block-header">
                    <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                </div> --}}
                <div class="block-content block-content-full" style="/*height:100vh;*/" id="main_div">
                    <div style="oveflow-x:auto;oveflow-y:auto;">
                        <table class="move" width="15%" style="margin-left:670px">
                            <tr>

                                <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                        <ol class="breadcrumb breadcrumb-alt"
                                            style="margin-right:-200px;margin-bottom:-250px;">
                                            <li class="breadcrumb-item" style="margin-left:-678px;margin-top:-8px">
                                                Department</li>
                                            <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                <a class="link-fx" href="">{{$dept}}</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </th>
                            </tr>


                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                            <table class="table table-bordered table-striped table-vcenter /*js-dataTable-full*/"
                                style="position:relative;top: 35px">
                                @foreach($data1 as $value)
                                <?php $no=0;?>

                                <?php $rr=$value->id;
                               //giving unique id to each + button
                               $btnid=$value->actid."1";
                               $spnid=$value->actid."2";
                               ?>
                                <!-- Normal Block Modal -->
                                <div class="modal" id="exampleModal{{$rr}}" role="dialog"
                                    aria-labelledby="modal-block-normal" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="block block-rounded block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Change Risk owners</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="block-content font-size-sm">
                                                    <div class="alert alert-success d-none" id="success{{ $rr }}">
                                                        <a class="close" data-dismiss="alert">×</a>
                                                        Successfully inserted
                                                    </div>
                                                    <ul class="alert alert-danger d-none" id="error{{ $rr }}">
                                                        <a class="close" data-dismiss="alert">×</a>
                                                    </ul>

                                                    @php
                                                    $ohs_riskowners = App\OhsRiskowner::where('ohs_id',
                                                    $rr)->pluck('user_id');
                                                    $logged_in_user = App\User::where('email',
                                                    auth()->user()->email)->first()->id;
                                                    @endphp
                                                    <select class="form-control" id="owner{{ $rr }}"
                                                        style="font-size: 13px;" multiple required>
                                                        @foreach($users as $u)
                                                        <option value="{{$u->id}}" {{ ($ohs_riskowners->
                                                            contains($u->id)) ? 'selected':'' }}>{{$u->name}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="block-content block-content-full text-right border-top">
                                                    <button type="button" class="btn btn-alt-primary mr-1"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="changeRiskowner({{$rr}})">Ok</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Normal Block Modal -->

                                <thead>
                                    <tr>

                                        <th colspan="11"
                                            style="font-size:10px;width: 75px;text-transform:capitalize;position:sticky;top:-1">
                                            <label style="font-size: 12px;font-weight:bold;">Activity:</label><label
                                                style="font-size:11px;color:gray;">{{ $value->activity }}</label>
                                        </th>
                                        <div class="dropdown">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                    style="font-size:10px;" id="dropdown-dropleft-outline-dark"
                                                    data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu font-size-sm"
                                                    aria-labelledby="dropdown-dropleft-outline-dark">
                                                    <a class='dropdown-item'
                                                        href="{{ url('/user/ohsrisk/addmore/'.$type.'/?val='.$rr)}}"
                                                        style="font-size:10px;">Add Risk</a>
                                                    {{-- <a class='dropdown-item' href="#"
                                                        style="font-size:10px;">Change Risk Owner</a> --}}
                                                    <button class='dropdown-item' style="font-size:10px;"
                                                        data-toggle="modal" data-target="#exampleModal{{$rr}}">Change
                                                        Risk Owner</button>

                                                    @if(Auth::user()->employeeid==$value->postedby &&
                                                    $value->risk_status=='Rejected')
                                                    <a class='dropdown-item'
                                                        href="{{url('/user/ohsrisk/resubmit/'.$type.'/?val='.$rr)}}"
                                                        style="font-size:10px;">Resubmit </a>
                                                    @endif
                                                    @if(Auth::user()->employeeid==$value->postedby &&
                                                    $value->risk_status=='Review Pending')
                                                    <a class='dropdown-item'
                                                        href="{{url('/user/ohsrisk/deleteactivity?val='.$rr)}}"
                                                        style="font-size:10px;"
                                                        onclick="return confirm('Are you sure you want to delete entire activity');">Delete
                                                        Activity</a>
                                                    @endif
                                                    @foreach($hses as $h)
                                                    @if(Auth::user()->email==$h && $value->risk_status!='Review
                                                    Pending')
                                                    <a class='dropdown-item'
                                                        href="{{url('/user/ohsrisk/deleteactivity?val='.$rr)}}"
                                                        style="font-size:10px;"
                                                        onclick="return confirm('Are you sure you want to delete entire activity');">Delete
                                                        Activity</a>
                                                    @endif
                                                    @endforeach
                                                    <!-- @foreach($mr as $m)
                                                        <a class='dropdown-item'  href="{{url('/user/ohsrisk/deleterisk?val='.$rr)}}" style="font-size:10px;">Delete Risk</a>
                                                        <a class='dropdown-item'  href="{{url('/user/ohsrisk/deleteactivity?val='.$rr)}}"  style="font-size:10px;" onclick="return confirm('Are you sure you want to delete entire activity');">Delete Activity</a>

                                                    @endforeach   -->
                                                    {{-- @foreach($dep as $d) --}}
                                                    @if(/*in_array($value->department, $dep) &&
                                                    */$value->risk_status=='Reviewed' && (in_array($logged_in_user,
                                                    $ohs_riskowners->toArray()) ||
                                                    Gate::allows('ohsrisk-secondary-review')))
                                                    <a class='dropdown-item'
                                                        href="{{url('/user/ohsrisk/secondaryreview/'.$type.'/?val='.$rr)}}"
                                                        style="font-size:10px;">Periodic Review</a>
                                                    @endif
                                                    {{--
                                                    <!-- <a class='dropdown-item'  href="{{url('/user/ohsrisk/deleterisk?val='.$rr)}}" style="font-size:10px;">Delete Risk</a>
                                                    <a class='dropdown-item' href="{{url('/user/ohsrisk/deleteactivity?val='.$rr)}}" style="font-size:10px;" onclick="return confirm('Are you sure you want to delete entire activity');">Delete Activity</a>
                                                    -->--}}
                                                    {{-- @endforeach --}}
                                                    @if($value->risk_status=='Review Pending')
                                                    @foreach($hses as $h)
                                                    @if(Auth::user()->email==$h)
                                                    <a class='dropdown-item'
                                                        href="{{url('/user/ohsrisk/review/'. $type .'/?val='.$rr)}}"
                                                        style="font-size:10px;">Review</a>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                            <th style="position:sticky;top:-1;width: 15px;">
                                                <div class="input-group input-group-sm">
                                                    <a type="button" href="{{ url('/user/ohsrisk/addmore?val='.$rr)}}"
                                                        class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
                                                        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
                                                    </a>
                                                </div>
                                            </th>
                                    </tr>



                                    <tr>
                                        <th style="position:sticky;top:-1;width: 15px;"></th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No
                                        </th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            OHS&nbspRisk Id</th>
                                        <!-- <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Activity</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1" >Department</th> -->
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Hazard</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Original Risk</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Residual Risk</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Category</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Type
                                            of Risk</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            R/NR/EM</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Monitoring</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Process Stage</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                            Action</th>
                                        <th class="text-center"
                                            style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($data2 as $dat)
                                    <?php
                                             $cats=$value->department;
                                             $cat=strtoupper(substr($cats,0,3));
                                             //changed $value->HS to $dat->HS

                                             $count=$value->count;
                                      ?>
                                    @if( $dat->ohs_id==$value->id)
                                    <?php
                                            if( $dat->RR > 0 && $dat->RR < 4 ){
                                                $category = 'Low';
                                            }
                                            elseif( $dat->RR > 3 && $dat->RR < 7 )	{
                                                $category = 'Medium';
                                            }
                                            elseif( $dat->RR > 7 ){
                                                $category = 'High';
                                            }

                                          ?>
                                    @if ($category == "High" || $category == "Medium")
                                    <?php  $flag="1"; ?>
                                    @else
                                    <?php  $flag="0"; ?>
                                    @endif
                                    <tr style="background-color:white;">
                                        <td class="text-center"><input class="checkbox" type="checkbox"
                                                id="{{ $dat->id}}" name="id[]"></td>
                                        <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}<b><button
                                                    style="font-weight:bold;color:blue;border:none;background:none;outline:none"
                                                    id="{{$btnid}}" value="+"
                                                    onclick="show_hide('{{$value->actid}}','{{$btnid}}','{{$spnid}}')"><span
                                                        id="{{$spnid}}"
                                                        class="fa fa-angle-right text-muted"></span></button></b></td>
                                        <td class="text-center " style="font-size:10px;color:primary">
                                            <?=$cat?>{{ $value->count }}/{{ $dat->riskid}}
                                        </td>
                                        <!-- <td ></td> -->


                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted" style="font-size:10px;">{{ $dat->hazards }}</em>
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            @if( $dat->OrR > 0 && $dat->OrR < 4 ) <span style="margin-left:30px;"
                                                class="badge badge-success">R: {{ $dat->OrR }}</span>
                                                @elseif( $dat->OrR > 3 && $dat->OrR < 7 ) <span
                                                    style="margin-left:30px;" class="badge badge-warning">R:{{ $dat->OrR
                                                    }}</span>
                                                    @elseif( $dat->OrR > 7 )
                                                    <span style="margin-left:30px;" class="badge badge-danger">R: {{
                                                        $dat->OrR }}</span>
                                                    @endif
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            @if( $dat->RR > 0 && $dat->RR < 4 ) <span style="margin-left:30px;"
                                                class="badge badge-success">R: {{ $dat->RR }}</span>
                                                @elseif( $dat->RR > 3 && $dat->RR < 7 ) <span style="margin-left:30px;"
                                                    class="badge badge-warning">R: {{ $dat->RR }}</span>
                                                    @elseif( $dat->RR > 7 )
                                                    <span style="margin-left:30px;" class="badge badge-danger">R: {{
                                                        $dat->RR }}</span>
                                                    @endif
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $category }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $dat->type}}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted">{{ $dat->R_NR_EM }}</em>
                                        </td>

                                        <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                            <em class="text-muted" style="font-size:10px;">{{ $dat->monitor }}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                            @if($dat->risk_status=="Rejected")
                                            <span class="badge badge-danger">{{ $dat->risk_status }}</span>
                                            @elseif($dat->risk_status=="Review Pending")
                                            <span class="badge badge-warning">{{ $dat->risk_status }}</span>
                                            @else
                                            <span class="badge badge-success">{{ $dat->risk_status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary" style="font-size:10px;"
                                                data-toggle="modal" data-id="{{ $dat->id }}"
                                                data-target="#viewModal2{{$dat->id}}"><strong>View</strong> </button>
                                            <div id="viewModal2{{$dat->id}}" class="modal fadebd-example-modal-lg"
                                                tabindex="-1" role="dialog" aria-hidden="false">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-title">Detailed View</h2>
                                                        </div>
                                                        <table class="table table-striped">
                                                            <tr>
                                                                <th class="text-left" style="width:70px;">Activity</th>
                                                                <td class="text-left">{{ $dat->activity }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Added at</th>
                                                                <td class="text-left">{{ $dat->created_at }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Last Reviewed</th>
                                                                <td class="text-left">{{ $dat->reviewed != null ?
                                                                    date('d-m-Y h:i:s A', strtotime($dat->reviewed))
                                                                    : '' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Department</th>
                                                                <td class="text-left">{{ $dat->dept ?
                                                                    $dat->dept->departmentname : '-'}}</td>
                                                            </tr>
                                                            @if($type == 'divisional' || $type == 'subdivisional')
                                                            <tr>
                                                                <th class="text-left">Division</th>
                                                                <td class="text-left">{{ $dat->division ?
                                                                    $dat->division->name : ''}}</td>
                                                            </tr>
                                                            @endif
                                                            @if($type == 'subdivisional')
                                                            <tr>
                                                                <th class="text-left">SubDivision</th>
                                                                <td class="text-left">{{ $dat->area ? $dat->area->name :
                                                                    ''}}</td>
                                                            </tr>

                                                            @endif
                                                            <tr>
                                                                <th class="text-left">Monitoring</th>
                                                                <td class="text-left">{{ $dat->monitor }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Type of Risk</th>
                                                                <td class="text-left">{{ $dat->type }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Legal</th>
                                                                <td class="text-left">{{ $dat->legal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Location</th>
                                                                <td class="text-left">{{ $dat->loc ? $dat->loc->name :
                                                                    '-'}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Risk Assessment Team</th>
                                                                <td class="text-left">
                                                                    @php
                                                                    $ohs = App\Ohs::where('id', $dat->ohs_id)->first();
                                                                    @endphp
                                                                    @foreach($ohs->employees as $item)
                                                                    {{ $item->employee ? $item->employee->emp_name :
                                                                    '-'}},
                                                                    @endforeach</td>

                                                            </tr>

                                                            <tr>
                                                                <th class="text-left">Risk Owners</th>
                                                                <td class="text-left">
                                                                    @php
                                                                    $ohs1 = App\Ohs::where('id', $dat->ohs_id)->first();
                                                                    @endphp
                                                                    @foreach($ohs1->riskowners as $item)
                                                                    {{ $item->user ? $item->user->name : '-'}},
                                                                    @endforeach
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th class="text-left">Posted By</th>
                                                                <td class="text-left">{{$dat->postedby}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">R/NR/EM</th>
                                                                <td class="text-left">{{ $dat->R_NR_EM }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Hazards and their<br> sources</th>
                                                                <td class="text-left">{{ $dat->hazards }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">How Will It Affect<br> And Whom?
                                                                </th>
                                                                <td class="text-left">{{ $dat->affect }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Risk Control Measures<br> Already
                                                                    In Use</th>
                                                                <td class="text-left">{{ $dat->control }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Original Risk With<br> Present
                                                                    Controls</th>
                                                                <td class="text-left">
                                                                    <span style="margin-left:30px;">S: {{ $dat->OrS
                                                                        }}</span>
                                                                    <span style="margin-left:30px;">L: {{ $dat->OrL
                                                                        }}</span>
                                                                    @if( $dat->OrR > 0 && $dat->OrR < 4 ) <span
                                                                        style="margin-left:30px;"
                                                                        class="badge badge-success">R: {{ $dat->OrR
                                                                        }}</span>
                                                                        @elseif( $dat->OrR > 3 && $dat->OrR < 7 ) <span
                                                                            style="margin-left:30px;"
                                                                            class="badge badge-warning">R:{{ $dat->OrR
                                                                            }}</span>
                                                                            @elseif( $dat->OrR > 7 )
                                                                            <span style="margin-left:30px;"
                                                                                class="badge badge-danger">R: {{
                                                                                $dat->OrR }}</span>
                                                                            @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Additional Control<br> Measures
                                                                    Needed</th>
                                                                <td class="text-left">{{ $dat->additional }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Residual Risk With<br> Additional
                                                                    Controls</th>
                                                                <td class="text-left"><span style="margin-left:30px;">S:
                                                                        {{ $dat->RS }}</span>
                                                                    <span style="margin-left:30px;">L: {{ $dat->RL
                                                                        }}</span>

                                                                    @if( $dat->RR > 0 && $dat->RR < 4 ) <span
                                                                        style="margin-left:30px;"
                                                                        class="badge badge-success">R: {{ $dat->RR
                                                                        }}</span>
                                                                        @elseif( $dat->RR > 3 && $dat->RR < 7 ) <span
                                                                            style="margin-left:30px;"
                                                                            class="badge badge-warning">R: {{ $dat->RR
                                                                            }}</span>
                                                                            @elseif( $dat->RR > 7 )
                                                                            <span style="margin-left:30px;"
                                                                                class="badge badge-danger">R: {{
                                                                                $dat->RR }}</span>
                                                                            @endif
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Category</th>
                                                                <td class="text-left">{{ $category }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Proposed Change</th>
                                                                <td class="text-left">{{ $dat->proposechange }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Procedure Number</th>
                                                                <td class="text-left">{{ $dat->proc_no}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Procedure</th>
                                                                <td class="text-left">{{ $dat->procedur }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-left">Process<br>Stage</th>
                                                                </th>
                                                                <td class="text-left" class="d-none d-sm-table-cell">
                                                                    @if($dat->risk_status=="Rejected")
                                                                    <span class="badge badge-danger">{{
                                                                        $dat->risk_status }}</span>
                                                                    @elseif($dat->risk_status=="Review Pending")
                                                                    <span class="badge badge-warning">{{
                                                                        $dat->risk_status }}</span>
                                                                    @else
                                                                    <span class="badge badge-success">{{
                                                                        $dat->risk_status }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-dark"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($hses as $h)
                                            @if(Auth::user()->email==$h)
                                            @if($flag=="1")
                                            <a href="{{ url('/user/ohsrisk/action?val='.$dat->id)}}"
                                                class="btn btn-primary"
                                                style="font-size:10px;">Control&nbsp;&nbsp;Procedure</a>
                                            @endif
                                            @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="13"> </td>
                                    </tr>
                                </tbody>
                                {{-- <ul style="list-style-type: none">
                                    <li colspan="13"></li>
                                </ul> --}}
                                @endforeach
                            </table>
                    </div>

                </div>
            </div>
            <!-- END Dynamic Table Full -->
        </div>
    </div>
    <!-- END Page Content -->
    <!--Delete Button -->

    <!--Delete Button -->

    <!--End of Delete Button -->
    {{----------delete request button ----------}}

    @section('sideitem')
    {{-- @foreach($mr as $mrs) --}}
    {{-- @if(Auth::user()->email==$mrs) --}}
    @if(Gate::allows('edit-health') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" id="editohs" value=id[] class="btn btn-sm btn-dual">
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if(Gate::allows('delete-health') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" id="deleteohs" value=id[] name="delete" data-name="" class="btn btn-sm btn-dual">
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection

    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>

    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let ids = "{{ $ids }}";
        ids = ids.split(",");
        for (var i = 0; i < ids.length; i++) {
            $("#owner" + ids[i]).select2({
                dropdownParent: $("#exampleModal" + ids[i])
                , width: '100%'
            , });

        }

        function changeRiskowner(id) {
            let error_text = "";
            let error_selector = $('#error' + id);
            let success_selector = $('#success' + id);
            $.ajax({
                type: 'post'
                , data: {
                    'ohs_id': id
                    , 'users': $('#owner' + id).val()
                    , '_token': CSRF_TOKEN
                }
                , url: "{{route('ohsriskowner.change')}}"
                , headers: {
                      'accept': 'application/json'
                }
                , success: function(response) {
                    success_selector.removeClass('d-none');
                    error_selector.addClass('d-none');
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
                , error: function(err) {
                    error_selector.removeClass('d-none');
                    success_selector.addClass('d-none');
                    if (err.status === 500) {
                    error_text += `<li>Some server errors happened, we are trying to fix them soon.</li>`;
                    } else if (err.status === 422) {
                      $.each(err.responseJSON.errors, function(i) {
                         $.each(err.responseJSON.errors[i], function(key, val) {
                          error_text += '<li>' + val + '</li>';
                       });
                       });
                   }
                   error_selector.empty().append(error_text);
                }
            });

        }

    </script>

    <script>
        $("#deleteohs").click(function() {
            var dataArr = new Array();
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    $.ajax({
                        type: 'get'
                        , url: "{{route('deleteohs')}}" + "?val=" + $(this).attr('id')
                        , success: function(response) {
                            // alert(response);
                            window.location.href = "{{url('/user/ohsrisk/folder')}}";
                            //  redirect('/user/doc/masterlist')->withData($data);
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



    {{-----------edit button ------------}}

    <script>
        $("#editohs").click(function() {
            var dataArr = new Array();
            var group = new Array();
            var d;
            var all;
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    //  console.log(dataArr);

                    all = "" + dataArr;
                    group.push(all); // push all checkbox values into an array
                    //  console.log(group);


                });
                $.ajax({
                    type: 'get'
                    , url: "{{route('editohs')}}" + "?val=" + (all)
                    , success: function(response) {
                        // alert(response);

                        window.location.href = "{{route('editohs')}}" + "?val=" + (all);
                        // console.log(response);

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
    <!-- End of Interested Party register-->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
        aria-hidden="true">
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

                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="/user/ohsrisk/folder">
                                    <div class="block-content text-center">
                                        <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <!-- <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/ohsrisk/masterlist">
                                            <div class="block-content text-center">
                                                <i class="fa fa-2x fa-id-card-alt text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Master List
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="/user/ohsrisk/analysis">
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
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

@include('exporting.forohsrisk')
<!-- END Apps Modal -->
<script>
    //javascript function for showing and hiding ohs risks
    function show_hide(id, bid, sid) {
        //for display the hidden elements
        if (document.getElementById(bid).value == "+") {
            document.getElementById(bid).value = "-";
            document.getElementById(sid).className = "fa fa-angle-down text-muted";
            document.getElementById(id).style.display = "";
        }
        //for hide the tbody elements
        else {
            document.getElementById(bid).value = "+";
            document.getElementById(sid).className = "fa fa-angle-right text-muted";
            document.getElementById(id).style.display = "none";
        }
    }

</script>
<!--  added for mouseover  -->
<script>
    $(".tiptext").mouseover(function() {
        $(this).children(".description").show();
    }).mouseout(function() {
        $(this).children(".description").hide();
    });

</script>




<!--  till here for mouseover  -->
@endsection
