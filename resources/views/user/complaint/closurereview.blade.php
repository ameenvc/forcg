@extends('layouts.backend')
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
        border: 1px solid #e1e6e9;
    }
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: .25rem !important;
        background-color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        padding-left: 12px !important;
        font-size: 13px;
        color: #495057;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
        right: 6px;
    }

    .select2-add-footer:hover {
        background: #f5f5f5;
    }
</style>
<link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
<style>
    @media (min-width: 950px) and (max-width: 1100px) {
        .custom-flex-row {
            flex-direction: column !important;
        }
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
                    Closure Review
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
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Propose Action</div>
                        <div class="fw-normal">My Complaints</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Review Action</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Update Progress</div>
                        <div class="fw-normal">My Complaints</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                    <form class="mb-5" method="post" enctype="multipart/form-data">

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
                        <div class="alert alert-primary d-none" id="uploadSuccess" style="font-size: 13px;">

                        </div>
                        <ul class='alert alert-warning d-none' id="uploadFailed">
                        </ul>
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
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="employee" style="font-size: 13px;">Complaint Received By</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{ $employees}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Received Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" name="crdate" id="crdate" style="font-size: 13px;" value="{{ $data->crdate}}" <?= $data->crdate ? 'readonly' : '' ?> required>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="Mode of Compliant" style="font-size: 13px;">Mode of Compliant</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->mode_of_complaint}}" readonly>
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
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-sm-4 {{count($areas)>0 ?'':'d-none'}}" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    @if($data->area_id)
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$areaname}}" readonly>
                                    @endif
                                </div>
                            </div>                  -->
                            {{-- @if(in_array(Auth::user()->email, $issue_reviewers)) --}}
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="financial_loss" style="font-size:13px;">Financial Loss</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;" value="{{ $data->financial_loss}}" placeholder="Enter Financial Loss" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="potential_cost_savings" style="font-size:13px;">Potential Cost Savings</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" readonly style="font-size:13px;" value="{{ $data->potential_cost_savings}}" placeholder="Enter Potential Cost">
                                </div>
                            </div>
                            {{-- @endif --}}
                            <div class="form-group col-sm-12" >
                                <div class="table-responsive">
                                    <div  style="overflow-x:auto; width:100%;">
                                        <table class="table table-bordered table-striped table-vcenter" >
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 50%': '' }}">Correction <span class="text-danger">*</span></th>
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 15%':''}}">Department</th>
                                                    @if($data->division_id)
                                                    <th class="text-center" style="font-size:11px;">Division</th>
                                                    @endif
                                                    @if($data->area_id)
                                                    <th class="text-center" style="font-size:11px;">Sub Division</th>
                                                    @endif
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 15%' :'' }}">Responsibility</th>
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 15%' :'' }}">Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span></th>
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 5%' :'' }}">Status</th>
                                                    <th></th>
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
                                                    <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:13px;" readonly placeholder="Enter Correction">{{ $data->correction }}</textarea></td>
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
                                                        <input type="Date" class="form-control" style="font-size: 13px;" readonly value="{{ $data->tardate}}">
                                                    </td>
                                                    <input type="hidden" name="correction_status" id="cor_status_val" value="{{ $data->correction_status }}" />
                                                    <td class="td-class text-center" id="corr_status">{{ $data->correction_status ? $data->correction_status . '%' : 0 }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatusCorr" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                            <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                                        </a>
                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                            @foreach(config('constants.complaint_management.progresses') as $key => $progress)
                                                            <input type="hidden" id="selected_status{{ $data->id }}" />
                                                            <a href="javascript:void(0)" class="dropdown-item change-status" data-value="{{ $key }}" id="correction_status_{{ $data->id }}" style="font-size:10px;" onclick="changeCorrectionStatus('{{ $key }}')">
                                                                <span class="ml-1">{{ $progress }}</span>
                                                            </a>
                                                            @endforeach

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
                                                    <th class="text-center" style="font-size:11px;width:12%;">Responsibility</th>
                                                    <th class="text-center" style="font-size:11px;width:8%;">Target Date</th>
                                                    <th style="font-size:11px;width:2%;">Status</th>
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
                                                    $corr_tardate = App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('target_date')->toArray();
                                                    $per_status = old('status') ? old('status', []) : App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('percent_status')->toArray();
                                                @endphp
                                                @for($row=0;$row<count($rootcause);$row++)
                                                <tr>
                                                    <input type="hidden" class="rid" name="rid[]" value="{{$rc_ids[$row]}}">
                                                    <td><span style="font-size:11px;">{{++$num}}</span></td>
                                                    <td class="td-class"><textarea class="form-control rootcause" cols="25" name="rootcause[]" id="rootcause{{$num}}" rows="4" style="font-size:12px;" placeholder="Enter Root Cause">{{$rootcause[$row]}}</textarea></td>
                                                    <td class="td-class"><textarea class="form-control corrective_action" cols="25" name="corrective_action[]" id="correctiveaction{{$num}}" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action">{{$correctiveaction[$row]}}</textarea></td>


                                                    <td class="td-class">
                                                        <div class="col-sm-12 mx-0">
                                                            <select class="form-control  department" id="department{{$num}}" name="department[]" style="font-size: 13px;width:100%;">
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

                                                        <div class="col-sm-12 mt-2">
                                                            <select class="form-control responsibility" id="responsibility{{$num}}" name="responsibility[]" style="font-size: 13px;width:100%;">
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
                                                        <input type="date" value="{{$corr_tardate[$row]}}" class="form-control" style="font-size:12px;" readonly>
                                                    </td>
                                                    <input type="hidden" class=".status_values" name="status[]" id="status_val{{ $num }}" value="{{ $per_status[$row] }}" />
                                                    <td class="td-class text-center" id="status{{$num}}" readonly>{{ $per_status[$row] ? $per_status[$row] . '%' : 0 }}</td>

                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                            <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                                        </a>
                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                            @foreach(config('constants.complaint_management.progresses') as $key => $progress)
                                                            <input type="hidden" id="selected_status{{ $data->id }}" />
                                                            <a href="javascript:void(0)" class="dropdown-item change-status" data-value="{{ $key }}" style="font-size:10px;" onclick="changeStatus('{{ $num }}', '{{ $key }}')">
                                                                <span class="ml-1">{{ $progress }}</span>
                                                            </a>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                </tr>

                                                <script>
                                                    $("#rootcause{{$num}}").css("background-color","#e8f0fe");
                                                    $("#correctiveaction{{$num}}").css("background-color","#e8f0fe");
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

                                                </script>
                                                @if($responsibility[$row])
                                                <style>
                                                    #select2-responsibility{{$num}}-container{
                                                        background-color: #e6e6ff;
                                                    }
                                                </style>
                                                @endif
                                                @if($department[$row])
                                                <style>
                                                    #select2-department{{$num}}-container{
                                                        background-color: #e6e6ff;
                                                    }
                                                </style>
                                                @endif
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group col-sm-12">
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
                                                <table  id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
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
                                                                <td style="font-size:13px;">{{ $value1->issue_file}}</td>
                                                                <td style="font-size:11px;">
                                                                    <a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a>
                                                                    @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $data->addedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary remove-issue-attachment" data-fileid="{{ $value1->id }}" style="font-size:11px;">Remove</button>
                                                                    @endif
                                                                </td>
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
                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal5"><strong>View Attachments(Action)</strong> </a>
                                    <div id="viewModal5" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments(Action)</h3>
                                                    <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>

                                                </div>
                                                <table id="actionAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
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
                                                            @if(count($actiondocs) > 0)
                                                            @foreach($actiondocs as $value1)
                                                            <tr>
                                                                <td style="font-size:10px;">{{$sno=$sno+1}}</td>
                                                                <td style="font-size:13px;">{{ $value1->action_file}}</td>
                                                                <td style="font-size:11px;">
                                                                    <a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->action_file)}}" download="{{$value1->action_file}}">Download</a>
                                                                    @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $data->addedby==Auth::user()->employeeid)
                                                                    <button class="btn btn-secondary remove-action-attachment" data-fileid="{{ $value1->id }}" style="font-size:11px;">Remove</button>
                                                                    @endif
                                                                </td>
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
                        <div class="row mt-2">
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Over all implementation status</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="impl_status" style="font-size:13px;" value="{{ $data->average_percent  }}" readonly>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 text-right" id="closecomplaint">
                            <a class='btn btn-small btn-danger' id="needmoreaction" onclick="needMoreAction('{{$data->id}}')" style="font-size:13px;background-color:#d1293d;">Need More Action</a>
                            <a class="btn btn-primary btn-small" id="closecomplaint" onclick="issueAuth()" style="font-size: 13px;background-color:#25499c;">Close Complaint</a>
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
                                <table id="complaintAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                    <div class="row justify-content-center">
                                        <thead>
                                            <tr class="table-secondary">
                                                <th style="width:5%;">S.No</th>
                                                <th style="width:65%;">Attachments</th>
                                                <th style="width:30%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <?php $o=0; ?>
                                        @if(count($files)>0)
                                            <tbody>
                                                @foreach($files as $f)
                                                <tr>
                                                    <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                    <td style="font-size:13px;">{{ $f->files}}</td>
                                                    <td style="font-size:11px;">
                                                        <a style="font-size:11px;" class="btn btn-success" href="{{asset('storage/complaint/'.$f->files)}}" download="{{$f->files}}">Download</a>
                                                        @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $data->addedby==Auth::user()->employeeid)
                                                        <button class="btn btn-secondary remove-attachment" data-fileid="{{ $f->sno }}" style="font-size:11px;" title="Delete">Remove</button>
                                                        @endif
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
</div>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/addcustomer">
                                    <div class="block-content text-center">
                                        <i class="fas fa-plus-square fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Add Customers
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaintmgmt">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">

                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/list">
                                    <div class="block-content text-center">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Customer List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">

                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/my">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Complaints
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <br>
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/closedc"style="margin-top:-16px">
                                    <div class="block-content text-center"style="height:130px">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Closed
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
<div id="investigationModal" class="modal fade bd-example-modal-sm" aria-labelledby="investigationModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-transparent mb-0">
                <div class="block-header bg-primary-darker">
                  <h3 class="block-title text-white">Issue Authenticity</h3>
                  <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                  </div>
                </div>
                <div class="block-content fs-sm">
                    <div class="alert alert-danger d-none w-100" style="font-size:11px;" id="success_investigation">
                        Please select whether the issue authenticity is Genuine or Non Genuine.
                    </div>
                    @if (isset($sourceOfIssueField['enable_field']))
                        <div class="alert alert-danger d-none w-100" style="font-size:11px;" id="source_of_issue_not_selected">
                            Please select {{$sourceOfIssueField['field_display_label']}}.
                        </div>
                    @endif
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;padding-left:0;">Do you believe this complaint is genuine or non genuine? <span class="text-danger">*</span></label>
                            <div class="col-sm-12 row">
                                <input type="button" value="Genuine" class="form-control btn-outline-primary btn-sm col-sm-4" style="font-size:13px;color:black;" id="genuine">
                                &nbsp;
                                <input type="button" value="Non Genuine" class="form-control btn-outline-primary btn-sm col-sm-4" style="font-size:13px;color:black;" id="nongenuine">
                                <input type="hidden" name="authenticity" id="authenticityvalue" value="">
                            </div>
                        </div>
                        @if (isset($sourceOfIssueField['enable_field']))
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;padding-left:0;">{{$sourceOfIssueField['field_display_label']}} @if ($sourceOfIssueField['field_is_mandatory'])<span class="text-danger">*</span>@endif</label>
                                <div>
                                    <select id="source_of_issue" style="font-size: 13px;width:100%;margin-left:0;" data-placeholder="Choose {{$sourceOfIssueField['field_display_label']}}">
                                        <option value="" selected>Choose {{$sourceOfIssueField['field_display_label']}}</option>
                                        @foreach ($sourceOfIssues as $sourceOfIssue)
                                            <option value="{{$sourceOfIssue->title}}">{{$sourceOfIssue->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="block-content block-content-full text-end bg-body text-right">
                  <button type="button" class="btn btn-sm btn-secondary me-1" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-sm btn-primary" onclick="closeComplaint()">Submit</button>
                </div>
              </div>

        </div>
    </div>
</div>
<!-- END Apps Modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/plugins/select2/js/select2.full.min.js')}}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?php
    $admin = DB::table('admins')->where('email',Auth::user()->email)->first();
    $isAdmin = $admin ? true : false;
?>

<script>
    jQuery(function() {
        One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
        //One.helpersOnLoad(['js-ckeditor5']);
    });
    $('.department').select2();
    $('.responsibility').select2();
    $('#source_of_issue').select2({
        dropdownParent: $('#investigationModal')
    });
    $('#source_of_issue').on('select2:open', function () {
        // avoid duplicate footer
        if ($('.select2-add-footer').length === 0) {
            $('.select2-dropdown').append(`
                <div class="select2-add-footer p-2"
                    style="border-top:1px solid #ddd; cursor:pointer; color:#007bff; font-size: 13px; font-weight:600;">
                    <i class="fa fa-plus mr-1"></i>
                    Add New {{$sourceOfIssueField['field_display_label'] ?? ""}}
                </div>
            `);
            $('.select2-add-footer').on('click', function () {
                if ("{{$isAdmin}}") {
                    window.open("/admin/complaint/source-of-issue", "_blank");
                } else {
                    Swal.fire("Warning", "Only admins can add new {{$sourceOfIssueField['field_display_label'] ?? ""}}, so please contact them.", "warning")
                }
                $('#source_of_issue').select2('close');
            });
        }
    });

    let isClicked = false;
    $('#genuine').click(function(){
        $(this).addClass('bg-primary');
        $("#nongenuine").removeClass('bg-primary');
        $("#nongenuine").addClass('bg-alt-primary');
        isClicked = true;
        $('#authenticityvalue').val("Genuine");
    });
    $('#nongenuine').click(function(){
        $(this).addClass('bg-primary');
        $("#genuine").removeClass('bg-primary');
        $("#genuine").addClass('bg-alt-primary');
        isClicked = true;
        $('#authenticityvalue').val("Non Genuine");
    });
    var removedFileIds = [];
    var removedIssueFileIds = [];
    var removedActionFileIds = [];

    $(document).ready(function () {

        $('.remove-attachment').on('click', function () {
            var fileid = $(this).data('fileid');
            $(this).closest('tr').remove();
            $('#complaintAttachmentTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
                $(this).find('td:first').css('font-size', '10px');
            });
            removedFileIds.push(fileid);
        });
        $('.remove-issue-attachment').on('click', function () {
            var fileid = $(this).data('fileid');
            $(this).closest('tr').remove();
            $('#issueAttachmentTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
                $(this).find('td:first').css('font-size', '10px');
            });
            removedIssueFileIds.push(fileid);
        });
        $('.remove-action-attachment').on('click', function () {
            var fileid = $(this).data('fileid');
            $(this).closest('tr').remove();
            $('#actionAttachmentTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
                $(this).find('td:first').css('font-size', '10px');
            });
            removedActionFileIds.push(fileid);
        });

        $('#investigationModal').on('hidden.bs.modal', function () {
            // Reset hidden field
            $('#authenticityvalue').val('');
            isClicked = false;
            // Reset select2
            if ($('#source_of_issue').length) {
                $('#source_of_issue').val(null).trigger('change');
                $('#source_of_issue')
                    .next('.select2-container')
                    .find('.select2-selection__rendered')
                    .html('<span class="select2-selection__placeholder">Choose {{$complaintTemplateSectionFields["source_of_issue"]["field_display_label"] ?? ""}}</span>');
            }

            // Remove active styles if any
            $('#genuine').removeClass('bg-primary').addClass('bg-alt-primary');
            $('#nongenuine').removeClass('bg-primary').addClass('bg-alt-primary');

            // Hide alerts
            $('#success_investigation').addClass('d-none');
            $('#source_of_issue_not_selected').addClass('d-none');

        });
    });
    function attchments(){
        $('<input />').attr('type', 'hidden')
            .attr('name', 'removedFileIds')
            .attr('id', 'removedFileIds')
            .attr('value', JSON.stringify(removedFileIds))
            .appendTo($('#closecomplaint'));
        $('<input />').attr('type', 'hidden')
            .attr('name', 'removedIssueFileIds')
            .attr('id', 'removedIssueFileIds')
            .attr('value', JSON.stringify(removedIssueFileIds))
            .appendTo($('#closecomplaint'));
        $('<input />').attr('type', 'hidden')
            .attr('name', 'removedActionFileIds')
            .attr('id', 'removedActionFileIds')
            .attr('value', JSON.stringify(removedActionFileIds))
            .appendTo($('#closecomplaint'));
        return true;
    }
</script>
<script>
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }
    function changeCorrectionStatus(key) {
        $('#selected_status').val(key);
        let shown_key = "";
        switch (key) {
            case 'not_started':
                shown_key = 0;
                break;
            case 'in_progress_0':
                shown_key = 0;
                break;
            case 'in_progress_25':
                shown_key = 25;
                break;
            case 'in_progress_50':
                shown_key = 50;
                break;
            case 'in_progress_75':
                shown_key = 75;
                break;
            case 'completed':
                shown_key = 100;
                break;
            default:
                shown_key = 0;
                break;

        }
        //sessionStorage.setItem('correction_status', shown_key);
        $('#cor_status_val').val(shown_key);
        $('#corr_status').text(shown_key !== 0 ? shown_key + '%' : 0);

    }
    function changeStatus(id, key) {
        switch (key) {
            case 'not_started':
                shown_key = 0;
                break;
            case 'in_progress_0':
                shown_key = 0;
                break;
            case 'in_progress_25':
                shown_key = 25;
                break;
            case 'in_progress_50':
                shown_key = 50;
                break;
            case 'in_progress_75':
                shown_key = 75;
                break;
            case 'completed':
                shown_key = 100;
                break;
            default:
                shown_key = 0;
                break;

        }
        $('#status_val' + id).val(shown_key);
        $('#status' + id).text(shown_key != 0 ? shown_key + '%' : 0);
    }
    function getvalues(selector) {
        var inps = Array.from(document.getElementsByClassName(selector));
        var arr = [];
        inps.forEach(element => {
            arr.push(element.value);
        });
        return arr;
    }
    function issueAuth(){
        $('#investigationModal').modal('show');
    }
    function closeComplaint() {
        if (!isClicked) {
            $('#success_investigation').removeClass('d-none');
            setTimeout(function () {
                $('#success_investigation').addClass('d-none');
            }, 3000);
            return;
        } else {
            $('#success_investigation').addClass('d-none');
        }

        if (@json(isset($sourceOfIssueField['enable_field']) && $sourceOfIssueField['field_is_mandatory'])) {
            if (!$('#source_of_issue').val()) {
                $('#source_of_issue_not_selected').removeClass('d-none');
                setTimeout(function () {
                    $('#source_of_issue_not_selected').addClass('d-none');
                }, 3000);
                return;
            }
        }

        $('#investigationModal').modal('hide');
        var uploadFailed = $('#uploadFailed');
        var uploadSuccess = $('#uploadSuccess');
        let selected_url = "{{url('/user/complaint/closed')}}";
        let url = "{{url('/user/complaintmgmt')}}";
        let other_status = Array.from(document.getElementsByClassName('.status_values'));
        let status_sum = 0;
        other_status.forEach(element => {
            status_sum+=parseInt(element.value);
        });
        let correction_status = $("#cor_status_val").val();
        status_sum += parseInt(correction_status);
        status_sum = status_sum/(other_status.length+1);
        if (status_sum < 100) {
            Swal.fire({
                text: "All actions should be updated to 100% to close the issue."
                , icon: "error"
                , showCancelButton: true
                , showConfirmButton: false
                , cancelButtonColor: "#d33"
                , cancelButtonText: "Close"
            , });
        } else {
            attchments();
            let rootcause_arr = getvalues("rootcause");
            let correctiveaction_arr = getvalues("corrective_action");
            let dept_arr = getvalues("department");
            let resp_arr = getvalues("responsibility");
            let rid = getvalues("rid");
            let authenticity = $("#authenticityvalue").val();
            let source_of_issue = $('#source_of_issue').val();
            let source_of_issue_field = '@json($sourceOfIssueField)';
            let id = document.getElementById('compid').value;
            Swal.fire({
                text: 'Please wait! Processing your request...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: selected_url,
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    id : id,
                    rootcause: rootcause_arr,
                    correctiveaction: correctiveaction_arr,
                    department: dept_arr,
                    responsibility: resp_arr,
                    rid : rid,
                    authenticity:authenticity,
                    source_of_issue: source_of_issue,
                    source_of_issue_field: source_of_issue_field,
                    removedFileIds : $('#removedFileIds').val(),
                    removedIssueFileIds : $('#removedIssueFileIds').val(),
                    removedActionFileIds : $('#removedActionFileIds').val()
                },
                beforeSend: function() {
                    $('#closecomplaint').prop('disabled', true).css({
                        'cursor': 'not-allowed',
                    });
                },
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        uploadFailed.addClass('d-none');
                        uploadSuccess.addClass('d-none');
                        uploadSuccess.empty().prepend(response.message+'<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                        setTimeout(function() {
                            window.location.href = url;
                        }, 2000);
                    } else {
                        uploadSuccess.addClass('d-none');
                        uploadFailed.addClass('d-none');
                        if (response.errors) {
                            uploadFailed.empty();
                            for (let key in response.errors) {
                                if (response.errors.hasOwnProperty(key)) {
                                    response.errors[key].forEach(function(error) {
                                        uploadFailed.append('<li style="font-size:13px;">' + error + '</li>');
                                    });
                                }
                            }
                            uploadFailed.removeClass('d-none');
                        } else {
                            uploadFailed.empty().prepend('An unexpected error occurred.' + '<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    uploadSuccess.addClass('d-none');
                    uploadFailed.addClass('d-none');
                    uploadFailed.empty().prepend('Some server errors happened, we are trying to fix them soon.'+'<a class="close" data-dismiss="alert">×</a>').removeClass('d-none');
                },
                complete: function() {
                    Swal.close();
                    $('#closecomplaint').prop('disabled', false).css({
                        'cursor': 'pointer',
                    });
                }


            });
        }


    }
    function needMoreAction(id) {
        let other_status = Array.from(document.getElementsByClassName('.status_values'));
        let status_sum = 0;
        let status_array = [];
        let correction_status = $("#cor_status_val").val();
        status_array.push(correction_status);
        other_status.forEach(element => {
            status_sum+=parseInt(element.value);
            status_array.push(element.value);
        });
        status_sum += parseInt(correction_status);
        status_sum = status_sum/(other_status.length+1);
        let all_status_string = status_array.toString();

        let rid = getvalues("rid");
        let rid_string = rid.toString();
        let the_url = "{{ url('user/complaint/changes')}}" + '/' + id + '/' + all_status_string+'/'+rid_string;
        if (status_sum >= 100) {
            Swal.fire({
                text: "Kindly adjust the percentage of at least one action below 100% in order to continue with the 'need more action' process",
                icon: "error",
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonColor: "#d33",
                cancelButtonText: "Close"
            });
        } else {
            window.location.replace(the_url);
        }
    }
</script>
<script>
    function setBlurFocusEvents(selector) {
        $(selector).blur(function(){
            $(this).css("background-color","#e8f0fe");
        }).focus(function(){
            $(this).css("background-color","white");
        });
    }
    setBlurFocusEvents('#rootcause');
    setBlurFocusEvents('#correctiveaction');
    setBlurFocusEvents('#correction');
</script>
@endsection
@section('js_after')
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


