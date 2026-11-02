@extends('layouts.multiselect')
@section('content')
<style>
    .td-class{
        font-size:11px;
        padding:4px !important;
    }
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .table thead th {
        vertical-align: middle;
    }
</style>

<style>
    @media (min-width: 950px) and (max-width: 1100px) {
        .custom-flex-row {
            flex-direction: column !important;
        }
    }
</style>

<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>
<!-- Page Content -->
<div class="content" style="width:100%;">
    @php
        $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
        $isAdminResult = $isAdmin ? true : false;
    @endphp
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/complaintmgmt" class="text-black">
                        Customer Complaint
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Propose Action
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full space-y-3">
            <nav class="d-flex flex-column custom-flex-row flex-lg-row items-center justify-content-between gap-2">
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Add Complaint</div>
                        <div class="fw-normal">Fill all required</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Entry Review</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        3
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Propose Action</div>
                        <div class="fw-normal">My Complaints</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        4
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Review Action</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        5
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Update Progress</div>
                        <div class="fw-normal">My Complaints</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        6
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Closure Review</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>


            </nav>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form class="mb-5" action="{{url ('user/complaint/proposeaction')}}" method="post" enctype="multipart/form-data">

                        {{csrf_field()}}
                        <?php
                            $activeComplaintTemplateId = $data->complaint_template_id;
                            $complaintTemplateSectionFields = json_decode($data->complaint_template_section_field_rules, true);
                        ?>
                        @if(count($errors) >0 )
                        @foreach($errors->all() as $error)
                        <div class='alert alert-warning'>
                            {{ $error }}
                        </div>
                        @endforeach
                        @endif
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        @if(Session::has('err'))
                        <div class='alert alert-warning'>
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('err')!!}
                        </div>
                        @endif

                        <div class="row">
                            <input type="hidden" class="form-control" id="compid" style="font-size: 13px;" name="compid" value="{{$data->id}}">
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Raised By </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{App\User::where('employeeid',$data->addedby)->value('name')}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint ID </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{ $data->trackno}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4" id="crbDiv">
                                <label class="col-sm-12 col-form-label" for="employee" style="font-size: 13px;">Complaint Received By</label>
                                <div class="col-sm-12">
                                    @if($employeename)
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{ $employeename}}" readonly>
                                    @else
                                    <select class="form-control js-select2" id="employee" name="employee" style="font-size: 13px;width:100%;">
                                        <option value="" selected>Choose Employee</option>
                                        @if(count($employees)> 0 )
                                        @foreach($employees as $e)
                                        <option value="{{$e->emp_number}}" <?= $data->employee == $e->emp_number ? 'selected' : '' ?>> {{ $e->emp_name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Received Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="crdate" style="font-size: 13px;" name="crdate" value="{{ $data->crdate}}" <?= $data->crdate ? 'readonly' : '' ?> required>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="Mode of Compliant" style="font-size: 13px;">Mode of Compliant</label>
                                <div class="col-sm-12">
                                    @if($data->mode_of_complaint)
                                        <input type="text" class="form-control" name="modeofcomplaint" style="font-size: 13px;" value="{{$data->mode_of_complaint}}" readonly>
                                    @else
                                    <select class="js-select2 form-control" id="modeofcomplaint" name="modeofcomplaint" style="font-size: 13px;width:100%;">
                                        <option value="" selected>Choose Mode</option>
                                        <option value="online forms" <?= $data->mode_of_complaint =="online forms" ? 'selected' : '' ?>>Online forms</option>
                                        <option value="phone call" <?= $data->mode_of_complaint=="phone call" ? 'selected' : '' ?>>Phone call</option>
                                        <option value="emails" <?= $data->mode_of_complaint=="emails" ? 'selected' : '' ?>>Emails</option>
                                        <option value="fax" <?= $data->mode_of_complaint=="fax" ? 'selected' : '' ?>>Fax</option>
                                        <option value="in-person visits to customer service center" <?= $data->mode_of_complaint=="in-person visits to customer service center" ? 'selected' : '' ?>>In-person visits to customer service center</option>
                                        <option value="direct office" <?= $data->mode_of_complaint=="direct office" ? 'selected' : '' ?>>Direct office</option>
                                        <option value="hot lines" <?= $data->mode_of_complaint=="hot lines" ? 'selected' : '' ?>>Hot lines</option>
                                    </select>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($activeComplaintTemplateId == null)
                            @include('user.complaint.template.readOnlyNoTemplate')
                        @elseif ($activeComplaintTemplateId == 1)
                            @include('user.complaint.template.readOnlyProductTemplate')
                        @elseif ($activeComplaintTemplateId == 2)
                            @include('user.complaint.template.readOnlyProjectTemplate')
                        @endif

                        <div class="row">
                            @php
                                $divisions = App\Division::where('department_id',$data->dept)->get();
                                $areas = App\Area::where('division_id',$data->division_id)->get();
                                $divisionname = App\Division::where('id',$data->division_id)->value('name');
                                $areaname = App\Area::where('id',$data->area_id)->value('name');
                            @endphp
                            <!-- <div class="form-group col-sm-4 {{count($divisions)>0 ?'':'d-none'}}" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    @if($data->division_id)
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$divisionname}}" readonly>
                                    @else
                                    <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option selected value="">Choose Division</option>
                                        @foreach($divisions as $division)
                                        <option value="{{$division->id}}" {{$division->id==$data->division_id ?'selected':''}}>{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-4 {{count($areas)>0 ?'':'d-none'}}" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    @if($data->area_id)
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$areaname}}" readonly>
                                     @else
                                    <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option selected value="">Choose Sub Division</option>
                                        @foreach($areas as $area)
                                        <option value="{{$area->id}}" {{$area->id==$data->area_id ?'selected':''}}>{{$area->name}}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>              -->

                            {{-- @if(in_array(Auth::user()->email, $issue_reviewers)) --}}
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="financial_loss" style="font-size:13px;">Financial Loss</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="financial_loss" style="font-size:13px;" name="financial_loss" value="{{ $data->financial_loss}}" placeholder="Enter Financial Loss">
                                    <span id="error_financial_loss" style="color:red; font-size: 12px; margin-left:10px;"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="potential_cost_savings" style="font-size:13px;">Potential Cost Savings</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="potential_cost_savings" style="font-size:13px;" name="potential_cost_savings" value="{{ $data->potential_cost_savings}}" placeholder="Enter Potential Cost">
                                    <span id="error_potential_cost_savings" style="color:red; font-size: 12px; margin-left:10px;"></span>
                                </div>
                            </div>
                            {{-- @endif --}}
                            <div class="form-group col-sm-12" >
                                <div class="table-responsive">
                                    <div  style="overflow-x:auto; width:100%;">
                                        <table class="table table-bordered table-striped table-vcenter" >
                                            <thead class="bg-white">
                                                <tr>
                                                    <!-- <th class="text-center" style="font-size:11px;width:62%;">Correction <span class="text-danger">*</span></th> -->
                                                    <th class="text-center" style="font-size:11px; {{ $data->division_id==null && $data->area_id==null ? 'width: 62%': '' }}">
                                                               Correction <span class="text-danger">*</span>
                                                    </th>

                                                    <th class="text-center" style="font-size:11px; {{ $data->division_id==null && $data->area_id==null ? 'width: 19%':''}}">
                                                        Department
                                                    </th>
                                                    @if($data->division_id)
                                                    <th class="text-center" style="font-size:11px;">Division</th>
                                                    @endif
                                                    @if($data->area_id)
                                                    <th class="text-center" style="font-size:11px;">Sub Division</th>
                                                    @endif
                                                    <th class="text-center" style="font-size:11px; {{ $data->division_id==null && $data->area_id==null ? 'width: 19%' :'' }}">
                                                       Responsibility
                                                    </th>
                                                    <th class="text-center" style="font-size:11px; {{ $data->division_id==null && $data->area_id==null ? 'width: 15%' : ''}}">
                                                        Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $dept = App\Departments::where('id',$data->dept)->value('departmentname');
                                                    $dept = $dept ? $dept : $data->dept;
                                                    $resp_val = App\User::where('email',$data->responsibility)->value('name');
                                                    $resp = $resp_val?$resp_val:'--';
                                                    $division_val = App\Division::where('id',$data->division_id)->value('name');
                                                    $division_name = $division_val?$division_val:'--';
                                                    $area_val = App\Area::where('id',$data->area_id)->value('name');
                                                    $area_name = $area_val?$area_val:'--';
                                                @endphp
                                                <tr>
                                                    <td class="td-class"><textarea class="form-control" cols="25" name="correction" id="correction" rows="4" style="font-size:13px;" required placeholder="Enter Correction">{{old('correction')}}</textarea></td>
                                                    <td class="td-class">
                                                        <input type="text" value="{{$dept}}" class="form-control" style="font-size:13px;" readonly>
                                                    </td>
                                                    @if($data->division_id)
                                                    <td class="td-class">
                                                       <input type="text" value="{{$division_name}}" class="form-control" style="font-size:13px;" readonly>
                                                    </td>
                                                    @endif
                                                    @if($data->area_id)
                                                    <td class="td-class">
                                                      <input type="text" value="{{$area_name}}" class="form-control" style="font-size:13px;" readonly>
                                                    </td>
                                                    @endif
                                                    <td class="td-class">
                                                        <input type="text" value="{{$resp}}" class="form-control" style="font-size:13px;" readonly>
                                                    </td>
                                                    <td class="td-class">
                                                        <input type="Date" class="form-control" id="correction_date" style="font-size: 13px;" name="date" {{in_array(Auth::user()->email, $issue_reviewers) ? "required" : ''}} value="{{ $data->tardate}}">
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @php
                                $team_leader_name = App\User::where('id',$data->team_lead_id)->value('name');
                                $team_members = App\ComplaintInvestigator::where('complaint_id', $id)->pluck("user_id")->toArray();
                                $team_members_array = App\User::whereIn('id',$team_members)->pluck('name')->toArray();
                                $team_member_name = implode(',', $team_members_array);
                            @endphp
                            <div class="form-group col-sm-12">
                                @if(count($team_members_array) && $team_leader_name)
                                <p class="text-danger font-weight-bold ml-3 font-13">Hi {{Auth::User()->name}}, you need to add correction field for this issue</p>
                                <div class="text-justify text-danger ml-3 font-13 font-weight-bold">
                                    Rootcause and corrective actions for this issue shall be identified by the following investigation team<br>
                                    Team Leader: {{ $team_leader_name }}<br>
                                    Team Members: {{ $team_member_name }}<br>
                                    Target Date: {{ date('d/m/Y',strtotime($data->investigation_date)) }}
                                </div>
                                @elseif(!count($team_members_array) && $team_leader_name)
                                <p class="text-danger font-weight-bold ml-3 font-13">Hi {{Auth::User()->name}}, you need to add correction field for this issue</p>
                                <div class="text-justify text-danger ml-3 font-13 font-weight-bold">
                                    Rootcause and corrective actions for this issue shall be identified by the following investigation team<br>
                                    Team Leader: {{ $team_leader_name }}<br>
                                    Target Date: {{ date('d/m/Y',strtotime($data->investigation_date)) }}
                                </div>
                                @else
                                <p class="text-danger font-weight-bold ml-3 font-13">Hi {{Auth::User()->name}}, you need to add the root cause, corrective action and correction field for this issue</p>
                                <p class="text-danger font-weight-bold font-13 ml-3">No investigation team added..</p>
                                @endif
                            </div>
                            <div class="form-group col-sm-12" >
                                <div class="table-responsive">
                                    <div  style="overflow-x:auto; width:100%;">
                                        <table class="table table-bordered table-striped table-vcenter" >
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="text-center" style="font-size:11px;width:1%;">S #</th>
                                                    <th class="text-center" style="font-size:11px;width:38%;">Root Cause</th>
                                                    <th class="text-center" style="font-size:11px;width:38%;">Corrective Action</th>
                                                    <th class="text-center" style="font-size:11px;width:14%;">Responsibility</th>
                                                    <th class="text-center" style="font-size:11px;width:8%;">Target Date</th>
                                                    <th style="font-size:11px;width:1%;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="dynamictable">
                                                @php
                                                    $num = 0;
                                                    $rc_ids = App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->get() ? App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('id')->toArray() : [];
                                                    $rootcause = old('rootcause') ? old('rootcause', []) : App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('root_cause')->toArray();
                                                    $correctiveaction = old('correctiveaction') ? old('correctiveaction', []) : App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('corrective_action')->toArray();
                                                    $department = old('department') ? old('department', []) : App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('department')->toArray();
                                                    $responsibility = old('responsibility') ? old('responsibility', []) : App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('responsibility')->toArray();
                                                    $corr_tardate = old('corr_tardate') ? old('corr_tardate', []) : App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('target_date')->toArray();
                                                @endphp
                                                @if(count($rootcause)<=0)
                                                <tr>

                                                    <td><span style="font-size:11px;">{{++$num}}</span></td>

                                                    <td class="td-class"><textarea class="form-control" cols="25" name="rootcause[]" id="rootcause" rows="4" style="font-size:12px;" placeholder="Enter Root Cause"></textarea></td>
                                                    <td class="td-class"><textarea class="form-control" cols="25" name="correctiveaction[]" id="correctiveaction" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action"></textarea></td>
                                                    <td class="td-class">

                                                        <div class="col-sm-12">
                                                            <select class="js-select2" id="department" name="department[]" style="font-size: 13px;width:100%;">
                                                                <option value="" selected>Choose Department</option>
                                                                @if(count($departments)>0)
                                                                @foreach($departments as $dep)
                                                                <option value="{{$dep->id}}"> {{$dep->departmentname}}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-12 mt-1">
                                                            <select class="js-select2 form-select w-100" id="responsibility" name="responsibility[]" style="font-size: 13px;width:100%;">
                                                                <option value="" selected>Select Responsible Person</option>
                                                                @if(count($user)> 0 )
                                                                @foreach($user as $use)
                                                                <option value="{{$use->id}}"> {{ $use->name }}</option><br>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>

                                                    </td>
                                                    <td class="td-class">
                                                        <input type="date" id="corr_tardate" class="form-control" name="corr_tardate[]" style="font-size:12px;" placeholder="Enter Sale Return Request">
                                                    </td>
                                                    <td class="td-class">
                                                        <button type="button" name="add" class="btn btn-sm btn-light" style="font-size:11px;" data-toggle="tooltip" title="Add" onclick="addRaw()">
                                                            <i class="fa fa-fw fa-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @else
                                                @for($row=0;$row<count($rootcause);$row++)
                                                <tr>
                                                    <input type="hidden" name="rid[]" value="{{$rc_ids[$row]}}">
                                                    <td><span style="font-size:11px;">{{++$num}}</span></td>
                                                    <td class="td-class"><textarea class="form-control" cols="25" name="rootcause[]" id="rootcause{{$num}}" rows="4" style="font-size:12px;" placeholder="Enter Root Cause">{{$rootcause[$row]}}</textarea></td>
                                                    <td class="td-class"><textarea class="form-control" cols="25" name="correctiveaction[]" id="correctiveaction{{$num}}" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action">{{$correctiveaction[$row]}}</textarea></td>


                                                    <td class="td-class">
                                                        <div class="col-sm-12">
                                                            <select class="form-control js-select2" id="department{{$num}}" name="department[]" style="font-size: 13px;width:100%;">
                                                                <option value="" selected>Choose Department</option>
                                                                <!-- Include active departments -->
                                                                    @if (count($departments) > 0)
                                                                        @foreach ($departments as $dep)
                                                                            <option value="{{ $dep->id }}" style="font-size:13px;"
                                                                                {{ isset($department[$row]) && intval($department[$row]) === $dep->id ? 'selected' : '' }}>
                                                                                {{ $dep->departmentname }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                  <!-- Include inactive department if already selected -->
                                                                    @if (isset($department[$row]))
                                                                        @php
                                                                            $inactiveDept = App\Departments::where('id', $department[$row])
                                                                                ->where('is_active', false)
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($inactiveDept)
                                                                            <option value="{{ $inactiveDept->id }}" style="font-size:13px;" selected>
                                                                                {{ $inactiveDept->departmentname }}
                                                                            </option>
                                                                        @endif
                                                                    @endif
                                                            </select>
                                                        </div>

                                                        <div class="col-sm-12 mt-1">
                                                            <select class="js-select2 form-select w-100" id="responsibility{{$num}}" name="responsibility[]" style="font-size: 13px;width:100%;">
                                                                <option value="" selected>Select Responsible Person</option>
                                                                @if(count($user)> 0 )
                                                                @foreach($user as $use)
                                                                <option value="{{$use->id}}" <?= $responsibility[$row] == $use->id ? 'selected' : '' ?>> {{ $use->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>

                                                    </td>
                                                    <td class="td-class">
                                                        <input type="date" id="corr_tardate{{$num}}" value="{{$corr_tardate[$row]}}" class="form-control" name="corr_tardate[]" style="font-size:12px;" required>
                                                    </td>

                                                    @if($num==1)
                                                    <td class="td-class">
                                                        <button type="button" name="add" class="btn btn-sm btn-light" style="font-size:11px;" data-toggle="tooltip" title="Add" onclick="addRaw()">
                                                            <i class="fa fa-fw fa-plus"></i>
                                                        </button>
                                                    </td>
                                                    @else
                                                    <td class="text-center td-class"><button type="button" name="remove" style="font-size:11px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove">
                                                        <i class="fa fa-fw fa-times text-danger"></i></button>
                                                    </td>
                                                    @endif
                                                </tr>

                                                <script>
                                                    $("#rootcause{{$num}}").blur(function(){
                                                        $(this).css("background-color","#e8f0fe");
                                                    }).focus(function(){
                                                        $(this).css("background-color","white");
                                                    });
                                                    $("#correctiveaction{{$num}}").blur(function(){
                                                        $(this).css("background-color","#e8f0fe");
                                                    }).focus(function(){
                                                        $(this).css("background-color","white");
                                                    });
                                                    $("#corr_tardate{{$num}}").blur(function(){
                                                        $(this).css("background-color","#e8f0fe");
                                                    }).focus(function(){
                                                        $(this).css("background-color","white");
                                                    });
                                                </script>
                                                @endfor
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    @foreach($rootcorrectives as $rootcorrective)
                                    <button type="button" class="btn btn-sm btn-outline-primary"  style="margin-right:4px;margin-bottom:10px;" onclick="return passvalue('{{$rootcorrective->id}}');">{{$rootcorrective->title}}</button>
                                    @endforeach

                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="col-sm-12 col-form-label" for="file" style="font-size: 13px;">Attach action related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" id="file" style="font-size: 13px;" name="file[]" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Attached Files</label>
                                <div class="col-sm-12">
                                    @if(count($files)>0)
                                    <a data-toggle="modal"  data-target="#viewModal"  data-id="{{ $data->id }}" class="btn btn-secondary text-white btn-small col-sm-12" style="font-size:11px;width: 180px;"><strong>View Complaint Files</strong></a>
                                    @endif
                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal10"><strong>View Attachments(Issues)</strong> </a>
                                    <div id="viewModal10" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments(Issues)</h3>
                                                    <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                                                </div>
                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    <div class="row justify-content-center">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th style="width:5%;">S.No</th>
                                                                <th style="width:65%;">Attachments</th>
                                                                <th style="width:30%;">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <?php $sno=0; ?>

                                                        <tbody>
                                                            @if(count($issuedocs) > 0)
                                                            @foreach($issuedocs as $value1)
                                                            <tr>
                                                                <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                <td style="font-size:13px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                <td><a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                            </tr>
                                                            @endforeach
                                                            @else
                                                            <tr>
                                                                <td colspan="3" style="font-size:14px;" class="text-center text-danger">No Files</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>

                                                    </div>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 text-right">
                            <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return confirm('Are you sure to propose the actions?')" style="font-size: 13px;" >Update</button>
                        </div>
                    </form>
                    <!-- Button trigger modal -->
                    <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Attachments</h3>
                                    <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                                </div>
                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                    <div class="row justify-content-center">
                                        <thead>
                                            <tr class="table-secondary">
                                                <th>S.No</th>
                                                <th>Attachment</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <?php $o=0; ?>
                                        @if(count($files)>0)
                                            <tbody>
                                                @foreach($files as $f)
                                                <tr>
                                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                    <td style="font-size:13px;width:70%;">{{ $f}}</td>
                                                    <td style="width:100%;">
                                                        <a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/complaint/'.$f)}}" download="{{$f}}">Download</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        @else
                                        <div class="row justify-content-center">
                                            <h2>No Files</h2>
                                        </div>
                                        @endif
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="js-swal-warning d-none addplant"></button>
    <button type="button" class="js-swal-warning d-none addcategory"></button>
    <button type="button" class="js-swal-warning d-none addcrb"></button>
</div>
<!-- Below include file contains validation logic for financial loss and potential cost saving -->
@include('user.complaint.common.financialLossAndPotentialCostSavingValidation');

<script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
<script>
    !function(e) {
        var n = {};

        function t(o) {
            if (n[o]) return n[o].exports;
            var r = n[o] = {
                i: o,
                l: !1,
                exports: {}
            };
            return e[o].call(r.exports, r, r.exports, t), r.l = !0, r.exports
        }
        t.m = e, t.c = n, t.d = function(e, n, o) {
            t.o(e, n) || Object.defineProperty(e, n, {
                enumerable: !0,
                get: o
            })
        }, t.r = function(e) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                value: "Module"
            }), Object.defineProperty(e, "__esModule", {
                value: !0
            })
        }, t.t = function(e, n) {
            if (1 & n && (e = t(e)), 8 & n) return e;
            if (4 & n && "object" == typeof e && e && e.__esModule) return e;
            var o = Object.create(null);
            if (t.r(o), Object.defineProperty(o, "default", {
                    enumerable: !0,
                    value: e
                }), 2 & n && "string" != typeof e)
                for (var r in e) t.d(o, r, function(n) {
                    return e[n]
                }.bind(null, r));
            return o
        }, t.n = function(e) {
            var n = e && e.__esModule ? function() {
                return e.default
            } : function() {
                return e
            };
            return t.d(n, "a", n), n
        }, t.o = function(e, n) {
            return Object.prototype.hasOwnProperty.call(e, n)
        }, t.p = "", t(t.s = 4)
    }({
        4: function(e, n, t) {
            e.exports = t(5)
        },
        5: function(e, n) {
            function t(e, n) {
                for (var t = 0; t < n.length; t++) {
                    var o = n[t];
                    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, o.key, o)
                }
            }
            var o = function() {
                function e() {
                    ! function(e, n) {
                        if (!(e instanceof n)) throw new TypeError("Cannot call a class as a function")
                    }(this, e)
                }
                var n, o, r;
                return n = e, r = [{
                    key: "sweetAlert2",
                    value: function() {
                        var e = Swal.mixin({
                            buttonsStyling: !1,
                            customClass: {
                                confirmButton: "btn btn-success m-1",
                                cancelButton: "btn btn-danger m-1",
                                input: "form-control"
                            }
                        });
                        jQuery(".addplant").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new plants, so please contact them.", "warning")
                        })),
                        jQuery(".addservice").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new product/service, so please contact them.", "warning")
                        })),
                        jQuery(".addcategory").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new category, so please contact them.", "warning")
                        })),
                        jQuery(".addcrb").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new employee, so please contact them.", "warning")
                        }))
                    }
                }, {
                    key: "init",
                    value: function() {
                        this.sweetAlert2()
                    }
                }], (o = null) && t(n.prototype, o), r && t(n, r), e
            }();
            jQuery((function() {
                o.init()
            }))
        }
    });
</script>
<script>
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }
    function passvalue(id)
    {
        $.ajax({
            data: {
                    "data_id": id,
                    _token: CSRF_TOKEN
                }
            , url: "{{ route('complaint.displayrootcorrectiveajax')}}"
            , type: "POST"
            , success:function(response) {
                if(response.status == 200)
                {
                    var rootlist = response.rootdata;
                    var roothtmldatas='';

                    if(rootlist.length>0){
                        roothtmldatas=rootlist;
                        }
                    else{
                        roothtmldatas=null;
                    }

                    var correctivelist = response.correctdata;
                    var correcthtmldatas='';
                    const removespace = new RegExp(/\s{3,}/, 'ig');
                    const correctivelistdisplay = correctivelist.replace(removespace, '\n');

                    if(correctivelist.length>0){
                        correcthtmldatas=correctivelistdisplay;
                        }
                    else{
                        correcthtmldatas=null;
                    }
                    let lastRow = $('#dynamictable tr:last');
                    lastRow.find('textarea[name="rootcause[]"]').val(roothtmldatas).css('background-color','#e8f0fe');
                    lastRow.find('textarea[name="correctiveaction[]"]').val(correcthtmldatas).css('background-color','#e8f0fe');
                }
            }
        });
    }
    function chooseDivision(id) {
        $('#division').addClass('d-none');
        $('#area').addClass('d-none');
        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#division').removeClass('d-none');
                    $('#division').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#division_select').empty().append('<option value="">Choose Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });

    }

    function chooseArea(id) {
        $('#area').addClass('d-none');
        let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#area').removeClass('d-none');
                    $('#area').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#area_select').empty().append('<option value="">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
    $('#division_select').change(function(){
        if(!($(this).val()=="")){
            $('#select2-division_select-container').css("background-color","#e6e6ff");
        }
    });
    $('#area_select').change(function(){

        if(!($(this).val()=="")){
            $('#select2-area_select-container').css("background-color","#e6e6ff");
        }
    });
</script>
@php
    $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
    $isAdminResult = $isAdmin ? true : false;
    $categoriesJson = json_encode($complaint_cat);
@endphp
<script>
    var clickCount1 = 0;
    var clickCount4 =0;
    var isAdmin = "{{$isAdminResult}}";

    $(document).ready(function(){
        $("#crbDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addEmployeeBtn" target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Employee</i></a></li></ul>`;
            if(clickCount4==0){
                $('#select2-employee-results').after(addBtn);
                clickCount4++;
            }
            $('#addEmployeeBtn').click(function(){
                if(isAdmin){
                    window.open("/admin/addemployees", "_blank");

                }else{
                    $('.addcrb').click();
                }
            });

        });
        $("#plantDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addPlantBtn" target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Plant</i></a></li></ul>`;
            if(clickCount1==0){
                $('#select2-plant-results').after(addBtn);
                clickCount1++;
            }
            $('#addPlantBtn').click(function(){
                if(isAdmin){
                    window.open("/admin/viewPlants", "_blank");

                }else{
                    $('.addplant').click();
                }
            });

        });
        $('#plant').change(function(){
            if(!($(this).val()=="")){
                $('#select2-plant-container').css("background-color","#e6e6ff");
            }
            var plantId = $(this).val();
            $.ajax({
                url: "/user/complaint/getCategoryAjax",
                data: {
                    id: plantId
                },
                success: function(response) {
                    let categories = response.data;
                    globalCategories = response.data;
                    let categories_text = undefined;
                    for(var i = 0; i < categories.length; i++) {
                        categories_text += `<option value="${categories[i].id}">${categories[i].category_name}</option><br>`;
                    }
                    $('.categoryclass').empty().append(categories_text);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error fetching customer data:", textStatus, errorThrown);
                }
            });
        });
        $('#responsibility').change(function(){
            if(!($(this).val()=="")){
                $('#select2-responsibility-container').css("background-color","#e6e6ff");
            }
        });
        $('#department').change(function(){
            if(!($(this).val()=="")){
                $('#select2-department-container').css("background-color","#e6e6ff");
            }
        });
    });
    function isInputNumber(event) {
        let value = event.value;
        let numbers = value.replace(/[^0-9.]/g, "");
        let dotCount = (numbers.match(/\./g) || []).length;
        if (dotCount > 1) {
            numbers = numbers.replace(/\./g, (match, offset, str) => {
            return (str.indexOf(match) === offset) ? '.' : '';
            });
        }

        event.value = numbers;
    }
    function setBlurFocusEvents(selector) {
        $(selector).blur(function(){
            $(this).css("background-color","#e8f0fe");
        }).focus(function(){
            $(this).css("background-color","white");
        });
    }
    setBlurFocusEvents('#financial_loss');
    setBlurFocusEvents('#potential_cost_savings');
    setBlurFocusEvents('#correction');
    setBlurFocusEvents('#rootcause');
    setBlurFocusEvents('#correctiveaction');
    setBlurFocusEvents('#corr_tardate');


</script>
<script>
    var count = {{$num}};
    var divcounter = {{$num}}
    function addRaw() {
        count++;
        let newrow= `<tr>
            <td><span style="font-size:12px;">${count}</span></td>
            <td class="td-class"><textarea class="form-control" cols="25" name="rootcause[]" id="rootcause${count}" rows="4" style="font-size:12px;" placeholder="Enter Root Cause"></textarea></td>
            <td class="td-class"><textarea class="form-control" cols="25" name="correctiveaction[]" id="correctiveaction${count}" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action"></textarea></td>
            <td class="td-class">

                <div class="col-sm-12">
                    <select class="form-control js-select2 dep_select" id="department${count}" name="department[]" style="font-size: 13px;width:100%;">
                        <option value="" selected>Choose Department</option>
                        @if(count($departments)>0)
                        @foreach($departments as $dep)
                        <option value="{{$dep->id}}"> {{$dep->departmentname}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-sm-12 mt-1">
                    <select class="js-select2 form-select res_select" id="responsibility${count}" name="responsibility[]" style="font-size: 13px;width:100%;">
                        <option value="" selected>Select Responsible Person</option>
                        @if(count($user)> 0 )
                        @foreach($user as $use)
                        <option value="{{$use->id}}"> {{ $use->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

            </td>
            <td class="td-class">
                <input type="date" id="corr_tardate${count}" class="form-control" name="corr_tardate[]" style="font-size:12px;">
            </td>
            <td class="text-center td-class"><button type="button" name="remove" style="font-size:11px;" class="btn btn-sm btn-light remove-tr" data-toggle="tooltip" title="Remove">
                <i class="fa fa-fw fa-times text-danger"></i></button>
            </td>
        </tr>`;
        $("#dynamictable").append(newrow);
        $('.dep_select').select2();
        $('.res_select').select2();
        setBlurFocusNewRow('#rootcause'+count);
        setBlurFocusNewRow('#correctiveaction'+count);
        setBlurFocusNewRow('#corr_tardate'+count);
        setColorEvent(count);
        updateCount();
    }
    $('#dynamictable').on('click', '.remove-tr', function () {
        $(this).closest('tr').remove();
        updateCount();
    });
    function updateCount() {
        $('#dynamictable tr').each(function(index) {
            $(this).find('td:first').text(index+1);
        });
    }
    function setBlurFocusNewRow(selector) {
        $(selector).blur(function(){
            $(this).css("background-color","#e8f0fe");
        }).focus(function(){
            $(this).css("background-color","white");
        });
    }
    function setColorEvent(count){

        $('#responsibility'+count).change(function(){
            if(!($(this).val()==null)){
                $('#select2-responsibility'+count+'-container').css("background-color","#e6e6ff");
            }
        });
        $('#department'+count).change(function(){
            if(!($(this).val()==null)){
                $('#select2-department'+count+'-container').css("background-color","#e6e6ff");
            }
        });
        for(let i=2;i<count;i++){
            if(!($('#responsibility'+i).val()=="")){
                $('#select2-responsibility'+i+'-container').css("background-color","#e6e6ff");
            }
            if(!($('#department'+i).val()=="")){
                $('#select2-department'+i+'-container').css("background-color","#e6e6ff");
            }
        }
    }

</script>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ versionedCache('js/complaint/summernote_init.js') }}"></script>
    <script>
        $(document).ready(function() {
            disableTextField('#complaint');
            disableTextField('#action');
            disableTextField('#job_history');
        });
    </script>
@endsection




