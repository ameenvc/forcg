@extends('layouts.multiselect')
@section('css_multiselect')
<style>
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        top: 2px !important;
        font-weight: 600 !important;
    }

    .td-class {
        text-align: center !important;
        font-size: 10px !important;
    }

    .swal-modal {
        width: 600px !important;
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

    .risk-span-class {
        display: block;
        font-weight: bold;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 13px;
    }

    .high-rank {
        background: #F8D7D6;
        color: #E93A2D;
    }

    .moderate-rank {
        background: #FDF3D1;
        color: #F5C342;
    }

    .other-rank {
        background: #E5F0DB;
        color: #4EAD5B;
    }

    .border-3 {
        border-width: 3px !important;
    }

    .font-15 {
        font-size: 12px !important;
    }

</style>
@endsection
@section('content')
<div class="content">

    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/corrective/corrissues" class="text-black">
                        NCR & Corrective Actions
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Propose Action
                </li>
            </ol>
        </nav>

        <div class="block-content block-content-full space-y-3">
            <nav class="d-flex flex-column flex-lg-row items-center justify-content-between gap-2">
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Add&nbsp;Issue</div>
                        <div class="fw-normal">Fill all required</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa fa-fw fa-check"></i>
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Entry&nbsp;Review</div>
                        <div class="fw-normal">Open CA</div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        3
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">ProposeAction</div>
                        <div class="fw-normal">My Open CA</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        4
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Review&nbsp;Action</div>
                        <div class="fw-normal">Open CA</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        5
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Update&nbsp;Progress</div>
                        <div class="fw-normal">My Open CA</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        6
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Closure&nbsp;Review</div>
                        <div class="fw-normal">Open CA</div>
                    </div>
                </a>


            </nav>
        </div>


        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 ">
                @include('sweet::alert')
                <form class="mb-5" action="{{route ('user.corrective.updateopenissue')}}" enctype="multipart/form-data" method="post" files="true">
                    {{csrf_field()}}
                    @php
                    $rr=$data->id ;
                    $dept = DB::table('departments')->where('id',$data->department)->value('departmentname');
                    $correction_dept = DB::table('departments')->where('id',$data->correction_department)->value('departmentname');
                    @endphp
                    <input type="hidden" name="posted" value="{{$data->id}}">
                    <div class="row">
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Issue id</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" style="font-size:13px;" value="{{$data->issueid}}" readonly="">
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" style="font-size:13px;" value="{{$data->postedby}}  {{ $data->postedByCorrective ? '[' . $data->postedByCorrective->name . ']' : '' }}" readonly="">
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" style="font-size:13px;" value="{{$data->title}}" readonly="">
                        </div>
                    </div>

                    <div class="form-group col-sm-6" id="category-div">
                        <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                        <div class="col-sm-12">
                            @if($data->category=='Legal Requirement')
                            <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$data->category ? $data->category : ''}}" readonly="">
                            @elseif($data->category=='Incident Investigation')
                            <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$data->category ? $data->category : ''}}" readonly="">
                            @elseif($data->category=='Management Review')
                            <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$data->category ? $data->category : ''}}" readonly="">
                            @else
                            <input type="text" class="form-control" id="category" name="category" style="font-size:13px;" value="{{$data->cat ? $data->cat->categoryname : ''}}" readonly="">
                            @endif
                        </div>
                    </div>
                    @php
                    $category = App\Categories::where('id', $data->category)->first();
                    $enable_source = $category ? $category->enable_source : null;
                    @endphp

                    {{-- @if($enable_source) --}}
                    <div class="form-group col-sm-6 {{$enable_source ? '' :'d-none'}}" id="source_field">
                        <label class="col-sm-12 col-form-label" for="source" style="font-size:13px;">Source</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="source" style="font-size:13px;" name="source" value="{{$data->source}}">
                        </div>
                    </div>
                    {{-- @endif --}}
                    <div class="form-group col-sm-6 {{$data->categoryDetectionStage ? '' : 'd-none'}}">
                        <label class="col-sm-12 col-form-label" for="detection_stage_field" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}}</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->categoryDetectionStage ? $data->categoryDetectionStage->name : '' }}" readonly>
                        </div>
                    </div>
                    {{-- <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="department" style="font-size:13px;" value="{{$correction_dept}}" readonly="">
                        </div>
                    </div> --}}

                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                        <div class="col-sm-12">
                            @if($data->type=='' || $data->type=='-')
                            <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$data->type ? $data->type : ''}}" readonly>
                            @else
                            <input type="text" class="form-control" id="type" name="type" style="font-size:13px;" value="{{$data->typ ? $data->typ->name : ''}}" readonly>
                            @endif
                        </div>
                    </div>


                    <div class="form-group col-sm-12">
                        <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation</label>
                        <div class="col-sm-12">
                            <textarea type="text" class="form-control" id="observation" style="font-size:13px;" placeholder="Enter observation" readonly="">{{$data->observation}}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred NCR?</label>
                        <div class="col-sm-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="yes" {{$data->is_previously_occurred=='yes' ? 'checked' : 'disabled'}}>
                                <label class="form-check-label" for="ncr_yes" style="font-size:13px;">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="no" {{$data->is_previously_occurred=='no' ? 'checked' : 'disabled'}}>
                                <label class="form-check-label" for="ncr_no" style="font-size:13px;">No</label>
                            </div>   
                        </div>
                    </div>
                    <div class="form-group {{$data->is_previously_occurred=='yes' ? '' : 'd-none'}} col-sm-6">
                        <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No. of Occurrence</label>
                        <div class="col-sm-12">
                            <input type="number" placeholder="Enter No. of Occurrence" class="form-control" style="font-size:13px;"  value="{{$data->no_of_occurrence}}" readonly>
                        </div>
                    </div>
                    @php 
                        $valueNcr = null;
                        if($data->previousNcrReferenceRelation)
                            $valueNcr = $data->previousNcrReferenceRelation->issueid.' - '.$data->previousNcrReferenceRelation->title;
                        elseif($data->previous_ncr_reference==0)
                            $valueNcr = "Other";
                    @endphp
                    <div class="form-group {{$data->is_previously_occurred=='yes' ? '' : 'd-none'}} col-sm-6">
                        <label class="col-sm-12 col-form-label" for="previous_ncr_reference" style="font-size:13px;">Previous NCR Reference</label>
                        <div class="col-sm-12">
                            <input type="text" placeholder="Previous NCR Reference" class="form-control" style="font-size:13px;"  value="{{$valueNcr}}" readonly>
                        </div>
                        <div class="col-sm-12 mt-2 {{$data->previous_ncr_reference!=0 || $data->previous_ncr_reference==null ? 'd-none' : ''}}" id="otherNcrReferenceDiv">
                            <input readonly type="text" class="form-control" id="other_ncr" style="font-size:13px;" name="other_ncr" placeholder="Enter Previous NCR Reference" value="{{$data->other_previous_ncr_reference}}">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action</label>
                        <div class="col-sm-12">
                            <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" readonly="">{{$data->agreedaction}}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="financial_loss" style="font-size:13px;">Financial Loss</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="financial_loss" style="font-size:13px;" name="financial_loss" value="{{ $data->financial_loss }}" {{!in_array(Auth::user()->email, $issue_reviewers) ? 'readonly' : ''}}>
                            <span id="error_financial_loss" style="color:red; font-size: 12px; margin-left:10px;"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="potential_cost_savings" style="font-size:13px;">Potential Cost Savings</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="potential_cost_savings" style="font-size:13px;" name="potential_cost_savings" value="{{ $data->potential_cost_savings }}" {{!in_array(Auth::user()->email, $issue_reviewers) ? 'readonly' : ''}}>
                            <span id="error_potential_cost_savings" style="color:red; font-size: 12px; margin-left:10px;"></span>
                        </div>
                    </div>


                    <div class="form-group col-sm-12">
                        {{-- <div class="table-responsive ml-3 mt-4"> --}}

                        <table class="table table-bordered table-striped table-vcenter ml-3 mr-3" style="width:1369px !important;">
                            <thead class="bg-white">
                                <tr>
                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 62%': 'width: 28%' }}">Correction</th>
                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 19%': '' }}">Department</th>
                                    @if($data->division)
                                    <th class="text-center titleheading">Division</th>   
                                    @endif  
                                    @if($data->area)                                           
                                    <th class="text-center titleheading">Sub Division</th>
                                    @endif
                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 19%': '' }}">Responsibility</th>
                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 15%': '' }}">Target Date</th>
                                </tr>
                            </thead>
                            @php
                                $responsibilities = explode(',', $data->responsibility);
                                $depts = explode(',', $data->department);
                                $dept_text_array = [];
                                foreach($depts as $department) {
                                $dept_text_array[] = App\Departments::where('id', $department)->value('departmentname');
                                }
                                $resp_text_array = [];
                                foreach($responsibilities as $responsibility) {
                                $resp_text_array[] = App\User::where('email', $responsibility)->value('name');
                                }
                                $target_date = explode(',', $data->target_date);
                            @endphp
                            <tbody>
                                <tr>
                                    <td class="td-class px-1">
                                        <textarea rows="3" cols="50" class="form-control" name="correction" style="font-size:13px;" required>{{$data->correction}}</textarea>
                                    </td>
                                    <td class="td-class px-1">
                                        <input class="form-control" style="font-size:13px;" value="{{ $data->correctionDept ? $data->correctionDept->departmentname : '' }}" readonly />
                                    </td>
                                    @if($data->division)
                                        <td class="td-class px-1">
                                        <input type="text" value="{{ $data->division->name}}" class="form-control" style="font-size:13px;" readonly>  
                                        </td> 
                                    @endif                                                       
                                    @if($data->area)       
                                        <td class="td-class px-1">
                                        <input type="text" value="{{ $data->area->name}}" class="form-control" style="font-size:13px;" readonly>
                                        </td>
                                    @endif
                                    @php 
                                    @endphp
                                    <td class="td-class px-1">
                                        @if(count(explode(',',$responsible_name))>=2)
                                        <textarea class="form-control" style="font-size:13px;" rows="3" readonly>{{$responsible_name}}</textarea>
                                        @else 
                                        <input class="form-control" style="font-size:13px;" value="{{$responsible_name }}" readonly />
                                        @endif
                                    </td>
                                    <td class="td-class px-1">
                                        <input type="date" id="target_date{{ $data->id }}" value="{{ $data->correction_target_date }}" name="correction_target_date" class="js-datepicker form-control" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" />
                                    </td>
                                    <input type="hidden" name="correction_status" id="cor_status_val{{ $data->id }}" value="{{ $data->correction_status }}" />

                                </tr>
                            </tbody>
                        </table>
                        {{-- </div> --}}
                    </div>

                    <div class="col-sm-12">
                    <p class="text-danger font-weight-bold font-13 ml-3 mt-1">Hi {{ $responsible_name }}, you need to add correction for this issue.</p>
                    @if(count($team_member_array) && $team_leader_name)
                    <div class="text-justify text-danger ml-3 font-13 font-weight-bold" style="position: relative;bottom:12px;">
                        Rootcause and corrective actions for this issue shall be identified by the following investigation team<br>
                        Team Leader: {{ $team_leader_name }}<br>
                        Team Members: {{ $team_member_name }}<br>
                        Target Date: {{ $data->investigation_date ? date('d-m-Y',strtotime($data->investigation_date)):'' }}
                    </div>
                    @elseif(!count($team_member_array) && $team_leader_name)
                    <div class="text-justify text-danger ml-3 font-13 font-weight-bold" style="position: relative;bottom:12px;">
                        Rootcause and corrective actions for this issue shall be identified by the following investigation team<br>
                        Team Leader: {{ $team_leader_name }}<br>
                        Target Date: {{ $data->investigation_date ? date('d-m-Y',strtotime($data->investigation_date)):'' }}
                    </div>
                    @else
                    <p class="text-danger font-weight-bold font-13 ml-3" style="position: relative;bottom:12px;">No investigation team added..</p>
                    @endif
                    </div>
                    @php
                        $rootcause = explode('$', $data->rootcause);
                        $cnt = count($rootcause) + 1;
                        $correctiveaction = explode('$', $data->correctiveaction);
                        $responsibility = explode(',', $data->responsibility);
                        $department = explode(',', $data->department);
                        $target_date = explode(',', $data->target_date);
                        $count = 1;
                    @endphp
                    @if($data->is_req_rc_analysis_management_review=="yes")
                    <div class="form-group col-sm-12">
                        {{-- <div class="table-responsive ml-3 mt-4"> --}}
                        <table class="table table-bordered table-striped table-vcenter ml-3 mt-4" id="root_cause_table{{$data->id}}" style="width:1369px !important;">
                            <thead class="bg-white">
                                <tr>
                                    <th class="text-center titleheading" style="width:40px !important;">S #</th>
                                    <th class="text-center titleheading" style="width:240px !important;">Root Cause</th>
                                    <th class="text-center titleheading" style="width:240px !important;">Corrective Action</th>
                                    <th class="text-center titleheading" style="width:140px !important;">Responsibility</th>
                                    <th class="text-center titleheading" style="width:70px !important;">Target Date</th>
                                    <th style="width:20px !important;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rootcause as $item)
                                <tr id="tr{{ $data->id }}{{ $count }}">
                                    @php
                                    $dept = isset($department[$count - 1]) ? App\Departments::where('id', $department[$count - 1])->value('departmentname') : '';
                                    $resp = isset($responsibility[$count - 1]) ? App\User::where('email', $responsibility[$count - 1])->value('name') : '';
                                    @endphp
                                    <td class="td-class" id="first_td{{ $data->id }}{{ $count }}">{{ $count }}</td>
                                    <td class="td-class"><textarea class="form-control" cols="70" name="rootcause[]" id="rootcause{{ $count - 1}}{{ $data->id }}" rows="5" style="font-size:13px;">{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : ''}}</textarea></td>
                                    <td class="td-class"><textarea class="form-control" cols="70" name="correctiveaction[]" id="correctiveaction{{ $count - 1 }}{{ $data->id }}" rows="5" style="font-size:13px;">{{ isset($correctiveaction[$count - 1]) ? $correctiveaction[$count - 1] : '' }}</textarea></td>
                                    <td class="td-class">
                                        <select class="form-control" name="department[]" style="font-size:13px;" required="true" placeholder="Choose a Department">
                                            <option value="0" style="font-size:13px;" selected>Choose a Department</option>
                                             <!-- Include active departments -->
                                                @if (count($departments) > 0)
                                                    @foreach ($departments as $dep)
                                                        <option value="{{ $dep->id }}" style="font-size:13px;"
                                                            {{ isset($department[$count - 1]) && intval($department[$count - 1]) === $dep->id ? 'selected' : '' }}>
                                                            {{ $dep->departmentname }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                                <!-- Include inactive department if already selected -->
                                                @if (isset($department[$count - 1]))
                                                    @php
                                                        $inactiveDept = App\Departments::where('id', $department[$count - 1])
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
                                        <select class="form-control mt-2" name="responsibility[]" style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                                            <option value="0" style="font-size:13px;" selected>Choose Responsibility</option>
                                            @if(count($user)> 0 )
                                            @foreach($user as $item)
                                            <option value="{{$item->email}}" style="font-size:13px;" {{ isset($responsibility[$count - 1]) && $responsibility[$count - 1] == $item->email ? 'selected' : '' }}>{{ $item->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>

                                    <td class="td-class">
                                        <input type="date" id="target_date{{ $count - 1}}{{ $data->id }}" class="js-datepicker form-control" name="target_date[]" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ isset($target_date[$count - 1]) ? $target_date[$count - 1] : '' }}" />
                                    </td>
                                    @if($count === 1)
                                    <td class="td-class">
                                        <button type="button" name="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add" onclick="addRaw('{{ $data->id }}', '{{ $cnt }}')">
                                            <i class="fa fa-fw fa-plus"></i>
                                        </button>
                                    </td>
                                    @else
                                    <td class="text-center">
                                        <button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr('{{ $data->id }}', '{{ $count + 1 }}')">
                                            <i class="fa fa-fw remove-tr fa-times text-danger"></i>
                                        </button>
                                    </td>

                                    @endif

                                </tr>
                                @php
                                $count++;
                                @endphp
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="col-sm-12" style="padding-bottom:10px;">
                        @php
                        $m = 0;
                        $items_per_row = 4;
                        @endphp
                        <input type="hidden" id="suggestion_val" />
                        <input type="hidden" id="count_val" value="0">
                        @foreach($rootcorrectives as $rootcorrective)
                        <button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:4px;margin-left:10px;margin-bottom:10px;" onclick="return passvalue('{{$rootcorrective->id}}', '{{ $data->id }}');">{{$rootcorrective->title}}</button>

                        @php
                        $m++;
                        @endphp

                        @endforeach

                    </div>
                    @endif

                    {{-- </div> --}}
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action files(avoid '#' and '&' in file names)</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="action_file" style="font-size: 13px;" name="action_file[]" multiple>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">Attached files</label>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                            @if(count($actiondocs)>0)
                            <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 157px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
                            @endif
                            <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachments</h3>
                                        </div>

                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                            <div class="row justify-content-center">
                                                <thead>
                                                    <tr class="table-secondary">
                                                        <th style="width:5%;">S.No</th>
                                                        <th style="width:65%;">Attachments (Issues)</th>
                                                        <th style="width:30%;">Actions</th>

                                                    </tr>
                                                </thead>
                                                <?php $sno=0; ?>

                                                @if( count($issuedocs) > 0 )
                                                @foreach($issuedocs as $value1)

                                                @if( $value1!="NULL" )
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:10px;">{{ $sno=$sno+1}}</td>
                                                        <td style="font-size:12px;">{{ $value1}}</td>
                                                        <td><a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a></td>
                                                    </tr>
                                                </tbody>
                                                @else
                                                <div class="row justify-content-center">
                                                    <h2>No Files</h2>
                                                </div>
                                                @endif
                                                @endforeach
                                                @else
                                                <div class="row justify-content-center">
                                                    <h2>No Files</h2>
                                                </div>
                                                @endif

                                        </table>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Attachments</h3>
                                </div>

                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                    <div class="row justify-content-center">
                                        <thead>
                                            <tr class="table-secondary">
                                                <th style="width:5%;">S.No</th>
                                                <th style="width:65%;">Attachments (actions)</th>
                                                <th style="width:30%;">Actions</th>

                                            </tr>
                                        </thead>
                                        <?php $n=0; ?>

                                        @if( count($actiondocs) > 0 )
                                        @foreach($actiondocs as $value2)

                                        @if( $value2!="NULL" && $value2!="")
                                        <tbody>
                                            <tr>
                                                <td style="font-size:10px;">{{ $n=$n+1}}</td>
                                                <td style="font-size:12px;">{{ $value2}}</td>
                                                <td><a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}">Download</a></td>
                                            </tr>
                                        </tbody>
                                        @else
                                        <div class="row justify-content-center">
                                            <h2>No Files</h2>
                                        </div>
                                        @endif

                                        @endforeach
                                        @else
                                        <div class="row justify-content-center">
                                            <h2>No Files</h2>
                                        </div>
                                        @endif
                                </table>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="col-sm-6 col-form-label" for="action_file" style="font-size:13px;">Is MOC Required? <span class="text-danger">*</span></label>
                        <div class="col-sm-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_moc_required" id="moc_yes" value="yes" {{ $data->is_moc_required === 'yes' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="moc_yes" style="font-size:13px;">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_moc_required" id="moc_no" value="no" {{ $data->is_moc_required === 'no' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="moc_no" style="font-size:13px;">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group moc-detail d-none col-sm-4">
                        <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">MOC Title <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="mocTitle" style="font-size: 13px;" name="mocTitle" placeholder="Enter MOC Title" value="{{$data->title}}">
                        </div>
                    </div>
                    <div class="form-group moc-detail d-none col-sm-4">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;">Review Authority <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select class="js-select2 form-control" name="reviewAuthority" id="reviewAuthority"  style="font-size:13px;width:100%;" data-placeholder="Choose Review Authority">
                                <option value=""></option>
                                @if(count($reviewers)>0)
                                @foreach($reviewers as $reviewer)
                                <option value="{{$reviewer->id}}">{{$reviewer->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group moc-detail d-none col-sm-4">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;">Approve Authority <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select class="js-select2 form-control" name="approveAuthority" id="approveAuthority"  style="font-size:13px;width:100%;" data-placeholder="Choose Approve Authority">
                                <option value=""></option>
                                @if(count($approvers)>0)
                                @foreach($approvers as $approver)
                                <option value="{{$approver->id}}">{{$approver->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group moc-detail d-none col-sm-12">
                        <label class="col-sm-12 col-form-label" style="font-size:13px;">Change description(specific to the NCR/CA)<span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <textarea class="form-control" style="font-size:13px;" name="changeDescription" id="changeDescription" placeholder="Enter Change Description"></textarea>
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-sm-12 text-right">
                        <button type="submit" for="submit" class="btn btn-primary" data-toggle="click-ripple" onclick="return confirm('Are you sure to propose the actions?')" style="font-size: 13px;margin-left:88%;">Update</button>
                    </div>
                    </div>
                </form>
                <!-- END Form Horizontal - Default Style -->

            </div>
        </div>
    </div>
</div>
<!-- END Horizontal -->



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

                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' style="margin-top:6px">
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

<!-- Below include file contains validation logic for financial loss and potential cost saving -->
@include('user.corrective.common.financialLossAndPotentialCostSavingValidation')

<script>
    $('input[name="is_moc_required"]').on('change', function() {
        if ($(this).val() === 'yes') {
            $('.moc-detail').removeClass('d-none');
            $('#mocTitle').attr('required',true);
            $('#reviewAuthority').attr('required',true);
            $('#approveAuthority').attr('required',true);
            $('#changeDescription').attr('required',true);
        } else {
            $('.moc-detail').addClass('d-none');$('#mocTitle').attr('required',true);
            $('#mocTitle').attr('required',false);
            $('#reviewAuthority').attr('required',false);
            $('#approveAuthority').attr('required',false);
            $('#changeDescription').attr('required',false);
        }
    });
</script>
<script>
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        const category = '{{$data->category}}';
        const mode = 'readonly';
        const id = '{{$data->id}}';
        $.ajax({
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
            method: 'GET',
            url: window.location.origin + `/user/corrective/get-product-ncr-fields/${category}/${mode}/${id}`,
            success: function(response) {
                $('.productNcrFieldClass').remove();
                if(response.fields_exist){
                    $('#source_field').after(response.fields);
                    $('.dropdown-ncr-field').select2();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

    let counter = Number('{{ $cnt }}');
    let departments_text = "{!! $departments_text !!}";
    let users_text = "{!! $users_text !!}";

    function passvalue(id, issue_id) {
        $('#suggestion_val').val(id);
        let count_val = $('#count_val').val();
        $.ajax({
            data: {
                "data_id": id
                , _token: CSRF_TOKEN
            }
            , url: "{{ route('corrective.displayrootcorrectiveajax')}}"
            , type: "POST"
            , success: function(response) {
                if (response.status == 200) {
                    var rootlist = response.rootdata;
                    var roothtmldatas = '';

                    if (rootlist.length > 0) {
                        roothtmldatas = rootlist;
                    } else {
                        roothtmldatas = null;
                    }

                    var correctivelist = response.correctdata;
                    var correcthtmldatas = '';
                    const removespace = new RegExp(/\s{3,}/, 'ig');
                    const correctivelistdisplay = correctivelist.replace(removespace, '\n');

                    if (correctivelist.length > 0) {
                        correcthtmldatas = correctivelistdisplay;
                    } else {
                        correcthtmldatas = null;
                    }
                    $('#rootcause' + (count_val) + issue_id).html(roothtmldatas);
                    $('#correctiveaction' + (count_val) + issue_id).html(correcthtmldatas);

                }

            }

        });
    }
    function addRaw(id, cnt) {
        $('#count_val').val(counter - 1);

        let text_to_append_to_table = `<tr id="tr${id}${counter}">
            <td class="td-class" id="first_td${id}${counter}">${counter++}</td>
            <td><textarea class="form-control" name="rootcause[]" id="rootcause${counter - 2}${id}" cols="70" rows="5" style="font-size:13px;"></textarea></td>
            <td><textarea class="form-control" name="correctiveaction[]" id="correctiveaction${counter - 2}${id}" cols="70" rows="5" style="font-size:13px;"></textarea></td>
            <td>
                <select class="form-control department" name="department[]" id="department${counter - 2}${id}" style="font-size:13px;" required="true" placeholder="Choose a Department">
                    <option value="0" selected style="font-size:13px;">Choose a Department</option>
                    ` + departments_text +
                `
                </select>
                <select class="form-control mt-2" name="responsibility[]" id="responsibility${counter - 2}${id}" style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                    <option value="0" selected style="font-size:13px;">Choose Responsibility</option>
                    ` + users_text +
                `
                </select>
            </td>
            <td>
                <input type="date" class="js-datepicker form-control" style="font-size:13px;" name="target_date[]" id="target_date${counter - 2}${id}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
            </td>

            <td class="text-center"><button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr(${id},${counter})">
                    <i class="fa fa-fw remove-tr fa-times text-danger"></i></button>
            </td>
        </tr>`;

        $("#root_cause_table" + id).append(text_to_append_to_table);

    }

    function remove_tr(id, index_param) {
        $('#count_val').val(index_param - 3);
        $('#tr' + id + (index_param - 1)).remove();
        counter -= 1;
    }
</script>
@endsection
