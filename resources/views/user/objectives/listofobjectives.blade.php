@extends('pages.datatables')

@section('head')
@include('user.objectives.common.page_loader')
@endsection
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0;" onclick="return fnCovertTable('print')">
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
            {{-- <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form> --}}
            <button onclick="onClickExport('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
        </li>
        <li><button onclick="onClickExport('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="onClickExport('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>
<div class="input-group input-group-sm">
    <a href="{{ url('/user/objectives/folders') }}" class="btn btn-secondary" style="font-size:11px;width: 150px;margin-left:9px;">Main Page</a>
</div>
@endsection
@section('css_before')
<style>
    .main-img-aud-container {
        position: relative;
        text-align: center;
    }
    .avatar-dot {
        height: 20px;
        width: 20px;
        background-color: #E5E4E2;
        border-radius: 50%;
        line-height:2.5em;
        font-size:8px;
    }

    .avatar-dropdown {
        position: relative;
        display: inline-block;
    }
    .avatar-dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        padding-bottom:15px;
        padding-right:5px;
        background-color: #ffffff;
        min-width: 220px;
        box-shadow:  0px 0px 15px;background-color: #ffffff;
        z-index: 1;
        font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .avatar-dropdown-content a {
        color: black;
        padding-top:12px;
        padding-left:15px;
        text-decoration: none;
        display: block;
        font-size:12px;
    }
    .avatar-dropdown-content a:hover {background-color: #ffffff;}
    .avatar-dropdown:hover .avatar-dropdown-content {display: block;}
    .avatar-dropdown:hover .avatar-dot {background-color: #E5E4E2;}
    @media screen and (max-width: 2000px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:80px !important;
        }

    }
    @media screen and (max-width: 1800px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:85px !important;
        }

    }
    @media screen and (max-width: 1600px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:65px !important;
        }

    }
    @media screen and (max-width: 1500px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:54px !important;
        }
    }
    @media screen and (max-width: 1400px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:45px !important;
        }
    }
    @media screen and (max-width: 1320px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:42px !important;
        }
    }
    @media screen and (max-width: 1230px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:38px !important;
        }
    }
    @media screen and (max-width: 1090px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:34px !important;

        }
    }

    @media screen and (max-width: 900px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:30px !important;
        }
    }
    @media screen and (max-width: 600px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:30px !important;
        }
    }

    @media screen and (max-width: 300px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:35px !important;
        }
    }
    .avatar-dropdown-content{
        margin-left:120%;
    }
    table tr:first-child .avatar-dropdown-content{
        top: 0;
        margin-left:120%;
    }
    table tr:last-child .avatar-dropdown-content{
        top: -170%;
        margin-left:120%;
    }
    .dataTables_filter {
        float: right !important;
        text-align: right;
    }
</style>
@endsection
@section('css_after')
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    <link rel="stylesheet" href="{{asset('css/objectives/list_of_objective.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" id="css-main" href="{{ mix('/css/oneui.css') }}">

@endsection
@section('content')
<?php
$data1=$data2=$data3=$data4=[];
?>
<!-- Ends loading screen  -->
<input type="hidden" value="a1" id="tabselected">
<div class="content" style="width:100%">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-w700">{{ Session::get('objdept')}}</li>
            <li class="breadcrumb-item">
                Objectives
            </li>
        </ol>
    </nav>
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <!-- <div class="scrollable" id="content"> -->
            <div class="table-responsive" id="content">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                    <thead>
                        <tr>
                            <th style="position:sticky;top:-1px;width:5%;"></th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:5%;">S.No</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:5%;">ID</th>
                            <td id="typeselectdata" class="text-center" data-orderable="false"
                                style="width:8%;text-transform:capitalize;position:sticky;top:-1px;z-index: 1"></td>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:25%;">Objective Name</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:10%;">Target</th>
                            <th class="text-center" style="width:6%;font-size:10px;text-transform:capitalize;position:sticky;top:-1px;">Achieved</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;width:10%;">Target Date</th>
                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px; width:10%">
                                <i class="far fa-user" style="font-size:14px;"></i>
                            </th>

                            <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;z-index: 1;width:5%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0;?>
                        @foreach($data as $value)
                            @php
                                $rr = $value->Oid;
                                $day = $value->tardate;
                                $d = strtotime($day);
                                $date = date('Y-m-d',$d);
                                $dt = $value->tardate;
                                $dat= strtotime($dt);
                                $dates = date('Y-m-d',$dat);
                                $status=$value->status;
                                $ldate = date('Y-m-d');
                                $todate=$value->todate;
                                $objective_main_id = isset($cat) && $cat ? $cat.$value->count : '';
                            @endphp
                        <tr data-id="{{$value->Oid}}">
                            <td><input class="text-center font-size-sm chckbox" type="checkbox" id="{{ $value->Oid }}" data-status="{{ $value->status }}" data-id="{{ $value->Oid }}" name="id[]"></td>
                            <td class="text-center" style="font-size:10px">{{$no=$no+1}}</td>
                            <td class="text-center khar" style="font-size:10px">
                                    @if($value->status=="Rejected")
                                        <span class="badge badge-danger" style="font-size:10px" data-toggle="popover" data-content="Rejected">{{ $value->objective_id }}</span>
                                    @elseif($value->status=="Review Pending")
                                        <span class="badge badge-warning" style="font-size:10px" data-toggle="popover" data-content="Under Review">{{ $value->objective_id }}</span>
                                    @elseif($value->status=="Reviewed")
                                        <span class="badge badge-primary" style="font-size:10px" data-toggle="popover" data-content="Reviewed">{{ $value->objective_id }}</span>
                                    @else
                                        <span class="badge badge-success" style="font-size:10px" data-toggle="popover" data-content="Approved">{{ $value->objective_id }}</span>
                                    @endif
                            </td>
                            <td class="text-center" style="font-size:10px">
                                <em class="text-muted">
                                    @if ($value->objectiveTypes)
                                        {!! $value->objectiveTypes->pluck('name')->implode(',<br>') !!}
                                    @else
                                        {{''}}
                                    @endif
                                </em>
                            </td>
                            <td class="text-center" style="font-size:10px">
                                <a href="{{route('user.objective.detail', ['id' => $value->Oid])}}">
                                    <em >{{$value->objectives}}</em>
                                </a>
                            </td>
                            @if($value->unit=='No sign')
                                <td class="text-center" style="font-size:10px"><span class="status status-blue" style="font-size:10px">{{ $value->otarget}}</span></td>
                            @else
                                <td class="text-center" style="font-size:10px"><span class="status status-blue" style="font-size:10px">{{ $value->otarget}}&nbsp;{{ $value->unit }}</span></td>
                            @endif
                            @if($value->oachieved || $value->oachieved == '0')
                                <td class="text-center" style="font-size:10px"><span class="status status-blue" style="font-size:10px">{{ $value->oachieved}}@if($value->unit != 'No sign')&nbsp;{{ $value->unit }}@endif</span></td>
                            @else
                                <td class='text-center'>--</td>
                            @endif
                            <td class="text-center" style="font-size:10px;">{{date('M/d/Y',strtotime($value->tardate))}}</td>
                            <td class="text-center " style="font-size:10px;">
                                @php
                                    $firstResponsibleUser = null;
                                    $remainingResponsibleUsers = null;
                                    $responsibleUsers = getUsersFromEmailsOrNames($value->responsibility);
                                    if ($responsibleUsers) {
                                        $firstResponsibleUser = $responsibleUsers->first();
                                        if ($responsibleUsers->count() > 1) {
                                            $remainingResponsibleUsers = $responsibleUsers->skip(1);
                                        }
                                    }
                                @endphp
                                <div class="main-img-aud-container">
                                    @if(isset($firstResponsibleUser))
                                        <img
                                            src="{{($firstResponsibleUser->profile_picture && File::exists(public_path('/storage/profile/'.$firstResponsibleUser->profile_picture))) ? asset('storage/profile/'.$firstResponsibleUser->profile_picture) : asset('/images/avatar10.jpg')}}"
                                            class="avatar"
                                            data-toggle="tooltip"
                                            title="{{$firstResponsibleUser->name}}"/>

                                        @if(isset($remainingResponsibleUsers))
                                            <div class="top-right">
                                                <div class="avatar-dropdown">
                                                    <div class="avatar-dot text-center">+{{count($remainingResponsibleUsers)}} </div>
                                                    <div class="avatar-dropdown-content">
                                                        @foreach($remainingResponsibleUsers as $remainingResponsibleUser)
                                                            @if($remainingResponsibleUser)
                                                                <a href="#">
                                                                    <div class="row">
                                                                        <div class="col-3">
                                                                            <img style="margin-bottom:5px;" src="{{($remainingResponsibleUser->profile_picture && File::exists(public_path('/storage/profile/'.$remainingResponsibleUser->profile_picture))) ? asset('storage/profile/'.$remainingResponsibleUser->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$remainingResponsibleUser->name}}"/>
                                                                        </div>
                                                                        <div class="col-9 text-left">
                                                                            <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{ucfirst($remainingResponsibleUser->name)}}</span>
                                                                        </div>
                                                                    </div>
                                                                </a>

                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                    <img src="{{ asset('/images/avatar10.jpg') }}"
                                            class="avatar"
                                            data-toggle="tooltip" data-placement="top" title="--"
                                            alt="No-avatar">
                                    @endif
                                </div>
                            </td>

                            <div class="dropdown font-size-sm">
                                <td>
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->Oid}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->Oid }}">
                                            @php
                                                $pre_by = null;
                                                if($value->postedby!=null && $value->new_postedby==null){
                                                    if(DB::table('admins')->where('employeeid',$value->postedby)->first())
                                                        $pre_by = DB::table('admins')->where('employeeid',$value->postedby)->value('email');
                                                    else
                                                        $pre_by = DB::table('users')->where('employeeid',$value->postedby)->value('email');
                                                }else{
                                                    $pre_by = $value->creator ? $value->creator->email : '';
                                                }
                                            @endphp
                                            @if($pre_by ==Auth::user()->email && $value->status=='Rejected')
                                            <a href="{{ url('/user/objectives/resubmit/'. $type .'/?val='.$rr)}}" style="font-size:10px" class="dropdown-item">Resubmit</a>
                                            @endif
                                            @php
                                                $rev = explode(',',$value->reviewers);
                                                $rev = DB::table('users')->whereIn('id', $rev)->pluck('email')->toArray();
                                                $reviewedEmails = [];
                                                if($value->reviewed_by!=null && $value->new_reviewedby==null){
                                                    $reviewedEmails[] = DB::table('users')->where('employeeid', $value->reviewed_by)->value('email');
                                                }
                                                $newReviewedEmail = explode(',', $value->new_reviewedby);
                                                $reviewedEmails = array_merge($reviewedEmails, DB::table('users')->whereIn('id', $newReviewedEmail)->pluck('email')->toArray());
                                                $reviewedEmails = array_unique($reviewedEmails);
                                                $objective_reviewers = App\UserGate::where('key', 'objective-reviewer')->pluck('users_id')->unique()->toArray();
                                                $objective_approvers = App\UserGate::where('key', 'objective-approver')->pluck('users_id')->unique()->toArray();
                                                $current_user = DB::table('users')->where('email',Auth::user()->email)->value('id');
                                            @endphp
                                            @if($value->status=='Reviewed' && $current_user==$value->approver && in_array($value->approver,$objective_approvers))
                                            <a href="{{ url('/user/objectives/approve/'. $type .'/?val='.$rr)}}" style="font-size:10px" class="dropdown-item">Approve</a>
                                            @endif
                                            @if(in_array($value->status, ['Approved', 'Review Pending', 'Reviewed']))
                                            <a href="{{ url('/user/objectives/add/'. $type .'/?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Add Report</a>
                                                @if(Gate::allows('objective-change-responsible-person'))
                                                   <a href="{{ route('objective.changeresponsibleperson',['id'=>$rr,'type'=>$type])}}" class="dropdown-item" style="font-size:10px;">
                                                      Change Responsible Person
                                                   </a>
                                                @endif
                                            @endif
                                            @if(in_array(Auth::user()->email,$rev))
                                                @if($value->status=='Review Pending' && !(in_array(Auth::user()->email,$reviewedEmails)))
                                                @if(in_array($current_user,$objective_reviewers))
                                                <a href="{{ url('/user/objectives/review/'.$type.'/?val='.$rr)}}" style="font-size:10px;" class="dropdown-item">Review</a>
                                                @endif
                                                @endif
                                            @endif
                                            @if(Auth::user()->email==$pre_by && $value->status=="Review Pending")
                                            <a href="{{ url('/user/objectives/editobjective/'.$type.'?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                            @endif
                                            @if($ldate>=$todate)
                                            <a href="{{ url('/user/objectives/repeat/'. $type .'/?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Repeat</a>
                                            @endif
                                            @if($value->status=='Review Pending' && Gate::allows('change-objective-reviewer-approver'))
                                            <a href="#" class="dropdown-item" style="font-size:10px;" data-toggle="modal" onclick="changeReviewerModal('{{$value->Oid}}','{{$value->objective_id}}','{{$value->reviewers}}')">Change Reviewer</a>
                                            @endif
                                            @if($value->status=='Reviewed' && Gate::allows('change-objective-reviewer-approver'))
                                            <a href="#" class="dropdown-item" style="font-size:10px;" onclick="changeApproverModal('{{$value->Oid}}','{{$value->objective_id}}','{{$value->approver}}')">Change Approver</a>
                                            @endif

                                        </div>
                                        <a href="#" data-route="{{$value->Oid}}" class="btn btn-sm btn-alt-primary btn-view-detail" data-id="{{ $value->Oid }}" data-toggle="tooltip" data-placement="top" title="View">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <a href="{{ url('/user/objectives/viewreport/'.$rr)}}" style="font-size:10px;text-decoration:none" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" data-placement="top" title="Report">
                                            <i class="si si-pie-chart fa-2x font-weight-bold " style="font-size:10px;top:5px !important"></i>
                                        </a>
                                    </div>

                                </td>
                            </div>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="view-modal"></div>

 <!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">OPTIONS</h3>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->
<!-- Change Reviwer Modal -->
<div class="modal fade" id="changeReviewerModal" tabindex="-1" role="dialog" aria-labelledby="changeReviewerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeReviewerModalLabel">Change Reviewer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success" id="successBtn2" style="display:none;font-size:12px;"></strong></div>
            <ul class="alert alert-danger" id="dangerBtn2" style="display:none;font-size:12px;"></ul>
            <form>
                @csrf
                <?php
                        $objective_reviewers = App\UserGate::where('key', 'objective-reviewer')->pluck('users_id')->unique()->toArray();
                ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="hidden" name="Oid1" id="Oid1" value="">
                            <label for="reviewers" class="col-sm-12 col-form-label" style="font-size:13px;">Reviewers <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="changereviewer[]" id="changeReviewer" style="font-size: 12px;width:100%;" multiple required data-placeholder="Choose Reviewers">
                                    @if(count($users)>0)
                                        @foreach($users as $user)
                                            @if(in_array($user->id,$objective_reviewers))
                                            <option value="{{$user->id}}"> {{$user->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" id="changeReviewerSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Chnage Reviewer Modal End -->
<!-- Change Approver Modal -->
<div class="modal fade" id="changeApproverModal" tabindex="-1" role="dialog" aria-labelledby="changeApproverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeApproverModalLabel">Change Approver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-success" id="successBtn1" style="display:none;font-size:12px;"></div>
            <ul class="alert alert-danger" id="dangerBtn1" style="display:none;font-size:12px;"></ul>
            <form>
                @csrf
                <?php
                    $objective_approvers = App\UserGate::where('key', 'objective-approver')->pluck('users_id')->unique()->toArray();
                ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <input type="hidden" name="Oid" id="Oid" value="">
                            <label for="responsible" class="col-sm-12 col-form-label" style="font-size:13px;">Approver <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="changeapprover" id="changeApprover" style="font-size: 12px;width:100%;" required data-placeholder="Select Approver">
                                    <option></option>
                                    @if(count($users)>0)
                                        @foreach($users as $user)
                                            @if(in_array($user->id,$objective_approvers))
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary" style="font-size:12px" id="submitApprover">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Chnage Approver Modal End -->
@include('exporting.forobjectives')
<script src="{{ asset('js/jquery.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script> --}}

<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let review_route = "{{ route('objectives.ajaxReview') }}";
    let approve_route = "{{ route('objectives.ajaxApprove') }}";

    $(document).ready(function() {
        let ids = "{{ $obj_ids }}";
        ids = ids.split(',');
        for (let i = 0; i < ids.length; i++) {
            $('#drop' + ids[i]).tooltip();
        }
    });

    function onClickExport(type) {
        table = $('#tabledata').DataTable();
        headings = [
            "Sl No", "Objective ID", "Objective Type", "Objective Name", "Target",
            "Acheived", "Target Date", "Responsible Person", "Process Stage"
        ];
        var ids = [];
        var department = "{{ request()->route('dept') }}";
        var division = "{{ request()->route('division') }}";
        var subDivision = "{{ request()->route('subdivision') }}";
        var contextData = {
            department,
            division,
            subDivision
        };

        table.rows({ search: 'applied' }).every(function () {
            ids.push($(this.node()).data('id'));
        });

        console.log(ids);
        console.log(contextData);

        $.ajax({
            url: "{{ route('objective.exportData') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                format: type,
                headings: headings,
                ids: ids,
                contextData: contextData
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob, status, xhr) {

                // Get filename from response header
                let filename = "download";

                const disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('filename=') !== -1) {
                    filename = disposition
                        .split('filename=')[1]
                        .replace(/"/g, '');
                }

                // Create download link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');

                a.href = url;
                a.download = filename;

                document.body.appendChild(a);
                a.click();

                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: function(xhr) {
                console.error(xhr);
            }
        });
    }

    $("#del").click(function() {
        let dataArr = [];
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));
                $.ajax({
                    type: 'get'
                    , url: "{{route('deleteobjectives')}}" + "?val=" + $(this).attr('id')
                    , success: function(response) {
                        window.location.href = "{{url('/user/objectives/folders')}}";
                    }
                    , error: function(response) {
                        console.log(response);
                    }
                });
            });

        } else {
            alert('No Record Selected');
        }
    });

    $(function () {
        const createHeadingSelect = (
            headingName = '', headingId = '',
            values = '', dropdownId = '', columnIndex = 0
        ) => {
            let nameArr = values.split(',');
            let optionText = "";
            for (let i = 0; i < nameArr.length; i++) {
                optionText += `<option value="${nameArr[i]}">${nameArr[i]}</option>`;
            }
            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                const heading = $(`<select class="headingselect"
                        style="width:120px;margin-left:10px;" id="${dropdownId}">
                            <option value="">${headingName}&nbsp;&nbsp;&#9207;</option>${optionText}
                        </select>`);
                $('#' + headingId).html(heading);
                $("#" + dropdownId).on('change', function() {
                    if ($(this).val()) {
                        table.columns(columnIndex).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#" + dropdownId +  "option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });
            });
        }

        // Type
        const typeNames = "{{ $allTypeNames }}";
        createHeadingSelect('Type', 'typeselectdata', typeNames, 'type_dropdown', 3);

        // Responsible Person
        // const responsiblePersonNames = "{{-- $allResponsiblePersonNames --}}";
        // createHeadingSelect('Responsible Person', 'responsiblepersonselectdata', responsiblePersonNames, 'responsible_person_dropdown', 10);
    });
    $('body').on('click', '.btn-view-detail', function () {
        const id = $(this).attr('data-route');
        $.ajax({
            url : window.location.origin +`/user/objectives/${id}/show`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.success) {
                    $('#view-modal').html(data.view)
                    const modalEle = $(`#viewModal${data.id}`);
                    modalEle.modal('show');
                } else {
                    alert('Data is not found');
                }
            },
            error: function () {
                alert('Handle error');
            }
        });
    });
</script>

<script>
    $("#editobj").click(function() {
        let dataArr = [];
        let group = [];
        let d;
        let all;
        if ($('input:checkbox:checked').length > 0) {
            $('input:checkbox:checked').each(function() {
                dataArr.push($(this).attr('id'));

                all = "" + dataArr;
                group.push(all); // push all checkbox values into an array


            });
            $.ajax({
                type: 'get'
                , url: "{{route('editobj')}}" + "?val=" + (all)
                , success: function(response) {

                    window.location.href = "{{route('editobj')}}" + "?val=" + (all);

                }
                , error: function(err) {
                    console.log(err);
                }
            });

        } else {
            alert('No Record Selected');
        }
    });

    function unique(array) {
        return array.filter(function(el, index, arr) {
            return index == arr.indexOf(el);
        });
    }

    $(document).ready(function() {
        let status_array = [];
        let selected_ids = [];

        $('.chckbox').on('change', function() {
            let counter = 0;
            status_array = $('input[type="checkbox"]').map(function() {
                let if_checked = $(this).is(':checked');
                status_array[counter] = if_checked ? $(this).data('status') : null;
                counter++;

                return status_array[counter - 1];
            }).get();

            selected_ids = $('input[type="checkbox"]').map(function() {
                let if_checked = $(this).is(':checked');
                selected_ids[counter] = if_checked ? $(this).data('id') : null;
                counter++;

                return selected_ids[counter - 1];
            }).get();


            let status_array_filtered_blue_length = status_array.filter(x => x == 'Reviewed').length;
            let status_array_filtered_yellow_length = status_array.filter(x => x == 'Review Pending').length;
            let approve_button_selector = $('#approve_button');
            let review_button_selector = $('#review_button');

            if (status_array_filtered_yellow_length > 1 && status_array_filtered_blue_length > 1) {
                review_button_selector.addClass('d-none');
                review_button_selector.removeClass('d-inline-block');
                approve_button_selector.addClass('d-none');
                approve_button_selector.removeClass('d-inline-block');
            } else {

                if (status_array_filtered_yellow_length > 1) {
                    review_button_selector.removeClass('d-none');
                    review_button_selector.addClass('d-inline-block');
                    approve_button_selector.addClass('d-none');
                } else {
                    review_button_selector.addClass('d-none');
                    review_button_selector.removeClass('d-inline-block');
                }

                if (status_array_filtered_blue_length > 1) {
                    approve_button_selector.removeClass('d-none');
                    approve_button_selector.addClass('d-inline-block');
                    review_button_selector.addClass('d-none');
                } else {
                    approve_button_selector.addClass('d-none');
                    approve_button_selector.removeClass('d-inline-block');
                }
            }

            approve_button_selector.on('click', function() {
                Swal.fire({
                    text: "Did you carefully review the selected objectives before granting your approval?"
                    , icon: "warning"
                    , showCancelButton: true
                    , confirmButtonColor: "#3085d6"
                    , cancelButtonColor: "#d33"
                    , confirmButtonText: "Yes"
                , }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST'
                            , data: {
                                ids: selected_ids
                                , _token: CSRF_TOKEN
                            }
                            , url: approve_route
                            , success: function() {
                                window.location.reload();
                            }
                            , error: function(err) {
                                console.log(err);
                            }
                        });


                    }
                });

            });

            review_button_selector.on('click', function() {
                Swal.fire({
                    text: "Are you sure you have completed reviewing the selected objectives?"
                    , icon: "warning"
                    , showCancelButton: true
                    , confirmButtonColor: "#3085d6"
                    , cancelButtonColor: "#d33"
                    , confirmButtonText: "Yes"
                , }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST'
                            , data: {
                                ids: selected_ids
                                , _token: CSRF_TOKEN
                            }
                            , url: review_route
                            , success: function() {
                                window.location.reload();
                            }
                            , error: function(err) {
                                console.log(err);
                            }
                        });


                    }
                });

            });


        });



        let years = "{{ $years }}".split(',');
        let year_text = "";
        let dept = "{{ Session::get('objdept')}}"
        let division = "{{ $division_name }}";
        let division_text = "";
        if (division != 0) {
            division_text = `<li class="breadcrumb-item" aria-current="page">
                <a class="link-fx" href="#">${division}</a>
            </li>`;
        }

        let subdivision = "{{ $subdivision_name }}";
        let subdivision_text = "";
        if (subdivision != 0) {
            subdivision_text = `<li class="breadcrumb-item" aria-current="page">
            <a class="link-fx" href="#">${subdivision}</a>
        </li>`;
        }


        for (let i = 0; i < years.length; i++) {
            year_text += `<option value="${years[i]}">${years[i]}</option>`;
        }
        $("#example").one("preInit.dt", function() {
            let table = $('#example').DataTable();
            $button0 = $(`<button type="button" class="btn text-white mr-2 d-none" style="font-size: 13px;background-color:#118987" id="approve_button">Approve</button>`);
            $button_review = $(`<button type="button" class="btn btn-warning text-white mr-2 d-none" style="font-size: 13px;" id="review_button">Review</button>`);
            $button = $(`<select class="form-control" style="font-size: 13px;display: inline-block;width:100px;" id="year_dropdown">
            <option value="0" selected>Year</option>` +
                year_text +
                `</select>`);

            $button1 = $(`
                        <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="font-size: 13px;display: inline-block">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Objectives</li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="link-fx" href="#">${dept}</a>
                                </li>` + division_text + subdivision_text + `</ol>
                        </nav>
                    `);


            $("#example_filter label").prepend($button);
            $("#example_filter label").prepend($button0);
            $("#example_filter label").prepend($button_review);
            // $("#example_length").append($button1);

            $button.button();
            $button0.button();
            $button_review.button();
            $button1.button();

            $("#year_dropdown").on('change', function() {
                if ($(this).val()) {
                    table.columns(8).search($(this).val()).draw();
                } else {
                    table.columns(8).search("").draw();
                }
            });

        });

    });

    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 230);
    function changeApproverModal(id,objective_id,approver=null) {
        $('#changeApprover').select2();
        $('#Oid').val(id);
        $('#changeApprover').val(null).trigger('change');
        if(approver){
            $('#changeApprover').val(approver).trigger('change');
        }
        $('#successBtn1').html('Approver has been successfully changed for Objective Id: <strong>' + objective_id + '</strong>');
        $('#changeApproverModal').modal('show');
    }
    $("#submitApprover").on("click",function changeApprover() {
        var error_selector = $('#dangerBtn1');
        var success_selector = $('#successBtn1');

        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('Oid',$('#Oid').val());
        formData.append('approver',$('#changeApprover').val());
        $.ajax({
            url: "{{ route('objectives.changeApprover') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.error != null) {
                    showError();
                    error_selector.empty();
                    $.each(response.error, function(index, errorMessage) {
                        error_selector.append(`<li>${errorMessage}</li>`);
                    });
                } else {
                    showSuccess();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(err) {
                showError();

                if (err.status === 500) {
                    error_selector.empty().append(
                        `<li>Some server errors happened, we are trying to fix them soon.</li>`
                    )
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }
        });
    });
    function changeReviewerModal(id,objective_id,reviewers=null) {
        $('#changeReviewer').select2();
        $('#Oid1').val(id);
        $('#changeReviewer').val([]).trigger('change');
        let $reviewersArray = reviewers ? reviewers.split(',') : [];
        if(reviewers){
            $('#changeReviewer').val($reviewersArray).trigger('change');
        }
        $('#successBtn2').html('Reviewers have been successfully changed for Objective Id: <strong>' + objective_id + '</strong>');
        $('#changeReviewerModal').modal('show');
    }
    $('#changeReviewerSubmit').on('click',function changeReviewer() {
        var error_selector = $('#dangerBtn2');
        var success_selector = $('#successBtn2');

        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('Oid',$('#Oid1').val());
        formData.append('reviewer',$('#changeReviewer').val());
        $.ajax({
            url: "{{ route('objectives.changeReviewer') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.error != null) {
                    showError();
                    error_selector.empty();
                    $.each(response.error, function(index, errorMessage) {
                        error_selector.append(`<li>${errorMessage}</li>`);
                    });
                } else {
                    showSuccess();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(err) {
                showError();

                if (err.status === 500) {
                    error_selector.empty().append(
                        `<li>Some server errors happened, we are trying to fix them soon.</li>`
                    )
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }
        });
    });
</script>
@endsection
@section('javascript')

@endsection

@section('sideitem')
@if(Gate::allows('edit-objective'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="editobj" style="margin-left:430px;" value=id[]>
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-objective'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection
