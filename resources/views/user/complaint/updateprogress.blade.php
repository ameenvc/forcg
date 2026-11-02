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
        border: 1px solid #e1e6e9;
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
                    Update Progress
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
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                    <form class="mb-5" action="{{url('/user/complaint/updateprogresspost')}}" method="post" enctype="multipart/form-data">

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
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="employee" style="font-size: 13px;">Complaint Received By</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{ $employees}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Received Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="crdate" name="crdate" style="font-size: 13px;" value="{{ $data->crdate}}" <?= $data->crdate ? 'readonly' : '' ?> required>
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
                            </div>                 -->
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
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 18%':''}}">Department</th>
                                                    @if($data->division_id)
                                                    <th class="text-center" style="font-size:11px;">Division</th>
                                                    @endif
                                                    @if($data->area_id)
                                                    <th class="text-center" style="font-size:11px;">Sub Division</th>
                                                    @endif
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 15%' :'' }}">Responsibility</th>
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 15%' : ''}}">Target date <span class="text-danger">{{in_array(Auth::user()->email, $issue_reviewers) ? "*" : ''}}<span></th>
                                                    <th class="text-center" style="font-size:11px;{{ $data->division_id==null && $data->area_id==null ? 'width: 5%' : ''}}">Status</th>
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
                                                    <td class="td-class"><textarea class="form-control" cols="25" rows="4" style="font-size:13px;" required name="correction" id="correction" placeholder="Enter Correction">{{ $data->correction }}</textarea></td>
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
                                                    <input type="hidden" name="correction_status" id="cor_status_val{{ $data->id }}" value="{{ $data->correction_status }}" />
                                                    <td class="td-class text-center" id="corr_status{{ $data->id }}">{{ $data->correction_status ? $data->correction_status . '%' : 0 }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatusCorr" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                            <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                                        </a>
                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownChangeStatusCorr">
                                                            @foreach(config('constants.complaint_management.progresses') as $key => $progress)
                                                            <input type="hidden" id="selected_status{{ $data->id }}" />
                                                            <a href="javascript:void(0)" class="dropdown-item change-status" data-value="{{ $key }}" id="correction_status_{{ $data->id }}" style="font-size:10px;" onclick="changeCorrectionStatus('{{ $data->id }}', '{{ $key }}')">
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
                                                    <th class="text-center" style="font-size:11px;width:18%;">Responsibility</th>
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
                                                    $department = App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('department')->toArray();
                                                    $responsibility = App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('responsibility')->toArray();
                                                    $corr_tardate = App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('target_date')->toArray();
                                                    $per_status = App\ComplaintRootCauseCorrectiveActionDetail::where('complaint_id',$data->id)->pluck('percent_status')->toArray();
                                                @endphp
                                                @for($row=0;$row<count($rootcause);$row++)
                                                <tr>
                                                    <input type="hidden" name="rid[]" value="{{$rc_ids[$row]}}">
                                                    <td><span style="font-size:11px;">{{++$num}}</span></td>
                                                    <td class="td-class"><textarea class="form-control" cols="25" name="rootcause[]" id="rootcause{{$num}}" rows="4" style="font-size:12px;" placeholder="Enter Root Cause">{{$rootcause[$row]}}</textarea></td>
                                                    <td class="td-class"><textarea class="form-control" cols="25" name="corrective_action[]" id="correctiveaction{{$num}}" rows="4" style="font-size:12px;" placeholder="Enter Corrective Action">{{$correctiveaction[$row]}}</textarea></td>
                                                    @php
                                                      $dept_value = DB::table('departments')->where('id',$department[$row])->value('departmentname');
                                                      $dept_name = $dept_value?$dept_value :'--';
                                                    @endphp
                                                    <td class="td-class">
                                                        <input type="text" class="form-control" style="font-size: 13px;width:100%;word-wrap: break-word;" value="{{ $dept_name }}" readonly>
                                                        <input type="text" class="form-control mt-1" style="font-size: 13px;width:100%;" value={{App\User::where('id',$responsibility[$row])->value('name')}} readonly>
                                                    </td>
                                                    <td class="td-class">
                                                        <input type="date" value="{{$corr_tardate[$row]}}" class="form-control" style="font-size:12px;" readonly>
                                                    </td>
                                                    <input type="hidden" name="status[]" id="status_val{{ $num }}" value="{{ $per_status[$row] }}" />
                                                    <td class="td-class text-center" id="status{{$num}}" readonly>{{ $per_status[$row] ? $per_status[$row] . '%' : 0 }}</td>

                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatus{{--$data->id --}}" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                            <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                                        </a>
                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownChangeStatus{{--$data->id --}}">
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
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- <div>
                                    @foreach($rootcorrectives as $rootcorrective)
                                    <button type="button" class="btn btn-sm btn-outline-primary"  style="margin-right:4px;margin-bottom:10px;" onclick="return passvalue('{{$rootcorrective->id}}');">{{$rootcorrective->title}}</button>
                                    @endforeach

                                </div> --}}
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="col-sm-12 col-form-label" for="file" style="font-size: 13px;">Attach action related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
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
                                    <a class="btn btn-secondary btn-small text-white" style="font-size:11px;width: 180px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal5"><strong>View Attachments(Action)</strong> </a>
                                    <div id="viewModal5" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments(Action)</h3>
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
                                                            @if(count($actiondocs) > 0)
                                                            @foreach($actiondocs as $value1)
                                                            <tr>
                                                                <td style="font-size:10px; ;">{{$sno=$sno+1}}</td>
                                                                <td style="font-size:13px;width: 100%;">{{ $value1->action_file}}</td>
                                                                <td><a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/complaint/".$value1->action_file)}}" download="{{$value1->action_file}}">Download</a></td>
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
                        <div class="col-sm-12 text-right">
                            <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return confirm('Are you sure to submit the request?')" style="font-size: 13px;" >Update</button>
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
</div>
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
    function changeCorrectionStatus(id, key) {
        $('#selected_status' + id).val(key);
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
        $('#cor_status_val' + id).val(shown_key);
        $('#corr_status' + id).text(shown_key !== 0 ? shown_key + '%' : 0);

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
