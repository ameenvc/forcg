@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<head>
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
        .scrollable{
            height:100%;
            overflow-x: scroll;
            overflow-y: scroll;
        }
        /* Title font size */
        .swal-title-sm {
            font-size: 17px !important;
            font-weight: 600;
        }

        /* Input label font size */
        .swal-input-label-lg {
            font-size: 16px !important;
            font-weight: 500;
        }
        .swal-input-label-lg::after{
            content: ' *' !important;
            color: #E93A2D !important;
        }
        .swal2-textarea {
            font-size: 17px;
        }

        /* Target the placeholder of your custom class */
        .custom-swal-textarea::placeholder {
            font-size: 14px; 
            font-style: italic;
            color: #888;  
        }
        .custom-swal-textarea {
            font-size: 16px; 
        }
        .swal-text-md {
            font-size: 12px !important;
        }
    </style>

</head>

<body>
    <!-- Hero -->
    
    <div id="view-detail"></div>

    <!-- Page Content -->
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
                        Closure Review
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
                        <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-fw fa-check"></i>
                        </div>
                        <div class="flex-grow-1 text-left ml-2">
                            <div class="font-15">ProposeAction</div>
                            <div class="fw-normal">My Open CA</div>
                        </div>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                        <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-fw fa-check"></i>
                        </div>
                        <div class="flex-grow-1 text-left ml-2">
                            <div class="font-15">Review&nbsp;Action</div>
                            <div class="fw-normal">Open CA</div>
                        </div>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                        <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-fw fa-check"></i>
                        </div>
                        <div class="flex-grow-1 text-left ml-2">
                            <div class="font-15">Update&nbsp;Progress</div>
                            <div class="fw-normal">My Open CA</div>
                        </div>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                        <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" enctype="multipart/form-data" files="true">
                            {{csrf_field()}}
                            <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                            <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
                            @php
                            $rr=$data->id;
                            $dept = DB::table('departments')->where('id',$data->department)->value('departmentname');
                            @endphp
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Raised by</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" style="font-size:13px;" readonly="">
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

                                @if($enable_source)
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Source</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size:13px;" value="{{$data->source}}" readonly>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group col-sm-6 {{$data->categoryDetectionStage ? '' : 'd-none'}}">
                                    <label class="col-sm-12 col-form-label" for="detection_stage_field" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} </label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->categoryDetectionStage ? $data->categoryDetectionStage->name :'' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                                    <div class="col-sm-12">
                                            @if($data->type=='' || $data->type=='-')
                                            <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->type ? $data->type : '' }}" readonly>
                                            @else
                                            <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->typ ? $data->typ->name : '' }}" readonly>
                                            @endif
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Title</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size:13px;" value="{{$data->title}}" readonly>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Observation</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" style="font-size:13px;" readonly>{{$data->observation}}</textarea>
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
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Recommended action</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" style="font-size:13px;" readonly>{{$data->agreedaction}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Financial Loss</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size:13px;" value="{{ $data->financial_loss }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Potential Cost Savings</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size:13px;" value="{{ $data->potential_cost_savings }}" readonly>
                                    </div>
                                </div>


                                <div class="form-group col-sm-12">
                                    <div class="table-responsive ml-3 mt-4">

                                        <table class="table table-bordered table-striped table-vcenter">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 50%': 'width: 25%' }}">Correction</th>
                                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 19%': '' }}">Department</th>
                                                    @if($data->division)
                                                    <th class="text-center titleheading">Division</th>   
                                                    @endif  
                                                    @if($data->area)                                           
                                                    <th class="text-center titleheading">Sub Division</th>
                                                    @endif
                                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 19%': '' }}">Responsibility</th>
                                                    <th class="text-center titleheading" style="{{ $data->division==null && $data->area==null ? 'width: 10%': '' }}">Target Date</th>
                                                    <th class="text-center titleheading">Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            @php
                                                $responsibilities = explode(',', $data->responsibility);
                                                $correction_responsibilities = explode(',', $data->correction_responsibility);
                                                $depts = explode(',', $data->department);
                                                $dept_text_array = [];
                                                foreach($depts as $department) {
                                                $dept_text_array[] = App\Departments::where('id', $department)->value('departmentname');
                                                }
                                                $resp_text_array = [];
                                                foreach($responsibilities as $responsibility) {
                                                $resp_text_array[] = App\User::where('email', $responsibility)->value('name');
                                                }

                                                $correction_resp_text_array = [];
                                                foreach($correction_responsibilities as $responsibility) {
                                                $correction_resp_text_array[] = App\User::where('email', $responsibility)->value('name');
                                                }

                                                $target_date = explode(',', $data->target_date);
                                                $responsible_persons = explode(',', $data->responsibility);
                                            @endphp
                                            <tbody>
                                                <tr>
                                                    <td class="td-class px-1">
                                                        <textarea rows="3" cols="50" class="form-control" name="correction" id="correction" style="font-size:13px;" {{ !in_array(Auth::user()->email, $responsible_persons) ? 'readonly' : '' }}>{{$data->correction}}</textarea>
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
                                                    <td class="td-class px-1">
                                                        <input class="form-control" style="font-size:13px;" value="{{ implode(',', $correction_resp_text_array) }}" readonly />
                                                    </td>
                                                    <td class="td-class px-1">
                                                        <input type="date" id="target_date{{ $data->id }}" value="{{ $data->correction_target_date }}" name="correction_target_date" class="js-datepicker form-control" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" disabled />
                                                    </td>
                                                    <input type="hidden" name="correction_status" id="cor_status_val{{ $data->id }}" value="{{ $data->correction_status }}" />
                                                    <td class="td-class" id="corr_status{{ $data->id }}">{{ $data->correction_status ? $data->correction_status . '%' : 0 }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatusCorr" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                            <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                                        </a>
                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownChangeStatusCorr">
                                                            @foreach(config('constants.corrective_management.progresses') as $key => $progress)
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
                                @php
                                    $rootcause = explode('$', $data->rootcause);
                                    $cnt = count($rootcause) + 1;
                                    $correctiveaction = explode('$', $data->correctiveaction);
                                    $responsibility = explode(',', $data->responsibility);
                                    $department = explode(',', $data->department);
                                    $percent_status = explode(',', $data->percent_status);
                                    $count = 1;
                                @endphp
                                @if($data->is_req_rc_analysis_management_review=="yes")
                                <div class="form-group col-sm-12">
                                    <div class="table-responsive ml-3 mt-4">

                                        <table class="table table-bordered table-striped table-vcenter" id="root_cause_table{{ $data->id }}">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="text-center titleheading">S #</th>
                                                    <th class="text-center titleheading" style="width:420px;">Root Cause</th>
                                                    <th class="text-center titleheading" style="width:420px;">Corrective Action</th>
                                                    <th class="text-center titleheading" style="width:270px;">Responsibility</th>
                                                    <th class="text-center titleheading">Target Date</th>
                                                    <th class="text-center titleheading">Status</th>
                                                    <th></th>
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
                                                    <td class="td-class"><textarea class="form-control" cols="70" data-foo="r{{$data->id}}" name="rootcause[]" id="rootcause{{ $count - 1}}{{ $data->id }}" rows="5" style="font-size:13px;">{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : '' }}</textarea></td>
                                                    <td class="td-class"><textarea class="form-control" cols="70" data-foo="c{{$data->id}}" name="correctiveaction[]" id="correctiveaction{{ $count - 1 }}{{ $data->id }}" rows="5" style="font-size:13px;">{{ isset($correctiveaction[$count - 1]) ? $correctiveaction[$count - 1]: ''}}</textarea></td>
                                                    <td class="td-class">
                                                        <select data-foo="d{{$data->id}}" class="form-control" name="department[]" style="font-size:13px;" required="true" placeholder="Choose Department">
                                                            <option value="0" disabled style="font-size:13px;">Choose Department</option>
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
                                                        <select data-foo="resp{{$data->id}}" class="form-control mt-2" name="responsibility[]" style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                                                            <option value="0" disabled style="font-size:13px;">Choose Responsibility</option>
                                                            @if(count($users)> 0 )
                                                            @foreach($users as $item)
                                                            <option value="{{$item->email}}" style="font-size:13px;" {{ isset($responsibility[$count - 1]) && $responsibility[$count - 1] == $item->email ? 'selected' : '' }}>{{ $item->name }}</option><br>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </td>

                                                    <td class="td-class">
                                                        <input type="hidden" class="form-control" style="font-size:13px;" value="{{ isset($target_date[$count - 1]) ? $target_date[$count - 1] : ''}}" name="target_date[]" />
                                                        <input type="date" id="target_date{{ $count - 1}}{{ $data->id }}" class="js-datepicker form-control" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ isset($target_date[$count - 1]) ? $target_date[$count - 1] : ''}}" disabled />
                                                    </td>
                                                    <td class="td-class" id="status{{ $data->id }}{{$count}}">{{ $data->percent_status && isset($percent_status[$count - 1]) ? $percent_status[$count - 1] . '%' : 0 }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatus{{ $count }}" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                            <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                                                        </a>
                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownChangeStatus{{ $count }}">
                                                            @foreach(config('constants.corrective_management.progresses') as $key => $progress)
                                                            <input type="hidden" id="selected_status{{ $data->id }}{{ $count }}" />
                                                            <a href="javascript:void(0)" class="dropdown-item change-status" data-value="{{ $key }}" style="font-size:10px;" onclick="changeStatus('{{ $data->id }}', '{{ $key }}', '{{ $count }}')">
                                                                <span class="ml-1">{{ $progress }}</span>
                                                            </a>
                                                            @endforeach
                                                        </div>
                                                    </td>

                                                </tr>
                                                @php
                                                $count++;
                                                @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group col-sm-12">
                                    <label class="col-sm-4 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                                    <div class="col-sm-8" id="attachmentsDiv">
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 157px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
                                        <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>

                                                    <table id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
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
                                                                    <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                    <td style="font-size:12px;">{{ $value1}}</td>
                                                                    <td><a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a>
                                                                        @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $data->postedby==Auth::user()->employeeid)
                                                                        <button class="btn btn-secondary remove-issue-attachment" data-filename="{{ $value1 }}" style="font-size:11px;">Remove</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            @else
                                                            <div class="row justify-content-center">
                                                                <h2>No Files</h2>
                                                            </div>
                                                            @endif
                                                            {{-- @endif  --}}
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

                                            <table id="actionAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
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
                                                            <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                            <td style="font-size:12px;">{{ $value2}}</td>
                                                            <td>
                                                                <a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}">Download</a>
                                                                @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $data->postedby==Auth::user()->employeeid)                                                                    
                                                                <button class="btn btn-secondary remove-action-attachment" data-filename="{{ $value2 }}" style="font-size:11px;">Remove</button>
                                                                @endif
                                                            </td>
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
                                            <input class="form-check-input" type="radio" name="is_moc_required" id="moc_yes" value="yes" {{ $data->is_moc_required === 'yes' ? 'checked' : 'disabled' }}>
                                            <label class="form-check-label" for="moc_yes" style="font-size:13px;">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_moc_required" id="moc_no" value="no" {{ $data->is_moc_required === 'no' ? 'checked' : 'disabled' }}>
                                            <label class="form-check-label" for="moc_no" style="font-size:13px;">No</label>
                                        </div>
                                    </div>
                                </div>
                                @if($data->is_moc_required === 'yes')
                                <div class="form-group col-sm-4">
                                    <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">MOC Title <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;"  value="{{$data->moc->title}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Status</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;"  value="{{$data->moc->status}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">MOC ID</label>
                                    <div class="col-sm-12">
                                        <a href="#" class="view-moc-detail" style="font-size:13px;" data-toggle="tooltip" title="Click to view MOC details"
                                            data-route="{{ $data->moc->id }}">
                                            {{$data->moc->crid}}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Change description(specific to the NCR/CA)</label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control" style="font-size:13px;" readonly>{{$data->change_description}}</textarea>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Over all implementation status</label>
                                    <div class="col-sm-12">
                                        {{-- <input type="hidden" class="form-control" id="average_percent" name="average_percent" style="font-size:13px;"> --}}
                                        <input type="text" class="form-control" id="impl_status" style="font-size:13px;" value="{{ $data->average_percent  }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 text-right">
                                    <a class='btn btn-primary btn-small' onclick="needMoreAction('{{ $rr}}')" style="font-size:13px;">Need More Action</a>
                                    <button type="button" class='btn btn-primary btn-small' style="font-size:13px;" id="closeissuebtn" onclick="closeIssue('{{$rr}}')">Close Issue</button>
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

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@php 
    $rc_analysis_req = $data->is_req_rc_analysis_management_review == "no" ;
    if($data->moc && $data->moc->crid){
        $msg = "This Corrective Action cannot be closed because the associated MOC (ID: {$data->moc->crid}) is not approved. Please ensure the MOC is approved before proceeding with closure review.";
        $isApproved = in_array($data->moc->status, ["Completed","Closed","Approved"]) ? 'yes' : 'no';
    }else{
        $msg = '';
        $isApproved = 'yes';
    }
@endphp
<script type="text/javascript">
    var removedIssueFilenames = [];
    var removedActionFilenames = [];
    $(document).ready(function() {
        
        const category = '{{$data->category}}';
        const mode = 'readonly';
        const id = '{{$data->id}}';
        $.ajax({ 
            method: 'GET', 
            url: window.location.origin + `/user/corrective/get-product-ncr-fields/${category}/${mode}/${id}`, 
            success: function(response) {
                $('.productNcrFieldClass').remove();
                if(response.fields_exist){
                    $('#category-div').after(response.fields);
                    $('.dropdown-ncr-field').select2();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    });
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

    function attchments(){
        $('<input />').attr('type', 'hidden').attr('id','issuefileid')
            .attr('name', 'removedIssueFilenames')
            .attr('value', JSON.stringify(removedIssueFilenames))
            .appendTo($('#attachmentsDiv'));
        $('<input/>').attr('type', 'hidden').attr('id','actionfileid')
            .attr('name', 'removedActionFilenames')
            .attr('value', JSON.stringify(removedActionFilenames))
            .appendTo($('#attachmentsDiv'));
        return true;
    }

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




    let corrective_first_url = "{{ route('corrective.viewlist') }}";
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let warningMessage = @json($msg);
    let isApproved = @json($isApproved);
    async function closeIssue(id) {    
        let selected_url = '{{ route("user.corrective.closed", ":id") }}';
        let rc_analysis_req = "{{$rc_analysis_req}}";
        selected_url = selected_url.replace(":id", id);
        let corrective_status = $("#cor_status_val" + id).val();
        let hasLessThan100 = null;
        if(rc_analysis_req){
            hasLessThan100 = corrective_status < 100;
        }else{
            let all_status_array = other_status.concat(corrective_status);
            hasLessThan100 = all_status_array.some(function(num) {
                return num < 100;
            });
        }
        if (hasLessThan100) {
            Swal.fire({
                text: "All actions should be updated to 100% to close the issue."
                , icon: "error"
                , showCancelButton: true
                , showConfirmButton: false
                , cancelButtonColor: "#d33"
                , cancelButtonText: "Close"
            , });

        } else {
            let vRemarks = null;
            if(isApproved=='no'){
                Swal.fire({
                    title: 'Warning',
                    text: warningMessage,
                    icon: 'warning',
                    customClass: {
                        title: 'swal-title-sm',
                        text: 'swal-text-md',
                    },
                });
                return; 
            }else{
                const result = await Swal.fire({
                    input: "textarea",
                    title: 'Final Verification Before Closure',
                    inputLabel: "Verification Remarks",
                    inputPlaceholder: "Enter Verification Remarks here...",
                    confirmButtonText: 'Close',
                    inputAttributes: {
                        "aria-label": "Enter Verification Remarks here...",
                        "required" : true
                    },
                    inputValidator: (value) => {
                        if (!value || !value.trim()) {
                            return 'Verification Remarks is required';
                        }
                    },
                    showCancelButton: true,
                    customClass: {
                        title: 'swal-title-sm',
                        inputLabel: 'swal-input-label-lg',
                        input: 'custom-swal-textarea'
                    },
                });
                if (!result.isConfirmed) {
                    return;
                }
                vRemarks = result.value.trim();
            }
            attchments();
            let rootcause_arr = getvalues(id);
            let correctiveaction_arr = getcorrectiveactionvalues(id);
            let dept_arr = getdeptvalues(id);
            let resp_arr = getrespvalues(id);
            $.ajax({
                url: selected_url
                , type: 'POST'
                , data: {
                    _token: CSRF_TOKEN
                    , rootcause: rootcause_arr
                    , correctiveaction: correctiveaction_arr
                    , department: dept_arr
                    , responsibility: resp_arr
                    , correction: $('#correction').val()
                    , issueFiles : $('#issuefileid').val()
                    , actionFiles : $('#actionfileid').val(),
                    vRemarks : vRemarks
                },
                beforeSend: function() {
                    $('#closeissuebtn').prop('disabled', true).css({
                        'cursor': 'not-allowed',                   
                    });
                }, 
                success: function() {
                    window.location.assign(corrective_first_url);
                },
                complete: function() {
                    $('#closeissuebtn').prop('disabled', false).css({            
                        'cursor': 'pointer',                          
                    });
                }
            });
        }


    }

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
<script type="text/javascript">
    let counter = '{{ $cnt }}';
    let departments_text = "{!! $departments_text !!}";
    let users_text = "{!! $users_text !!}";

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
        $('#cor_status_val' + id).val(shown_key);
        $('#corr_status' + id).text(shown_key !== 0 ? shown_key + '%' : 0);

    }

    let var_array = [];

    let progress_status = '{{ $data->percent_status }}';

    let other_status = progress_status.split(',');


    function changeStatus(id, key, count) {
        id = id.toString();
        key = key.toString();
        $('#selected_status' + id + count).val(key);
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
        other_status[count - 1] = shown_key;
        $('#percent_status').val(other_status.join(','));

        $('#status' + id + count).text(shown_key !== 0 ? shown_key + '%' : 0);
    }

    function needMoreAction(id) {
        let corrective_status = $("#cor_status_val" + id).val();
        let rc_analysis_req = "{{$rc_analysis_req}}";
        let the_url = "{{ url('user/corrective/changes')}}" + '/' + id + '/';
        if(rc_analysis_req){
            the_url = the_url+corrective_status;
            if (corrective_status==100) {
                Swal.fire({
                    text: "Kindly adjust the percentage of Correction below 100% in order to continue with the 'need more action' process"
                    , icon: "error"
                    , showCancelButton: true
                    , showConfirmButton: false
                    , cancelButtonColor: "#d33"
                    , cancelButtonText: "Close"
                , });

            } else {
                window.location.replace(the_url);
            }
        }else{
            let all_status_array = other_status.concat(corrective_status);
            const allEqual = all_status_array => all_status_array.every(v => v === all_status_array[0])
            let all_status_string = all_status_array.join(',');
            the_url = the_url + all_status_string;

            if (all_status_array[0] == 100 && allEqual(all_status_array)) {
                Swal.fire({
                    text: "Kindly adjust the percentage of at least one action below 100% in order to continue with the 'need more action' process"
                    , icon: "error"
                    , showCancelButton: true
                    , showConfirmButton: false
                    , cancelButtonColor: "#d33"
                    , cancelButtonText: "Close"
                , });

            } else {
                window.location.replace(the_url);
            }
        }


    }

</script>



</html>
@endsection
