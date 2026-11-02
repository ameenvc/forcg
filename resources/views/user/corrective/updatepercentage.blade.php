@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
        .tooltip-inner {
            font-size: 12px;
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
        }

        .tooltip-arrow {
            display: none;
        }
        .scrollable{
            height:100%;
            overflow-x: scroll;
            overflow-y: scroll;
        }

    </style>

</head>

<body>
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
                        Update Progress
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
                        <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{route ('user.corrective.post_update_percentage')}}" enctype="multipart/form-data" method="post" files="true">
                            {{csrf_field()}}

                    @include('sweet::alert')

                    @if(Session::has('msg'))
                    <div class="alert alert-primary">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                    @endif
                    <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                    <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">

                    @php $rr=$data->id ;
                    $dept = DB::table('departments')->where('id',$data->department)->value('departmentname');
                    @endphp
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Issue id</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$data->issueid}}" readonly="">
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Type</label>
                            <div class="col-sm-12">
                                @if($data->type=='-' || $data->type=='')
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$data->type ? $data->type : ''}}" readonly="">
                                @else
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$data->typ ? $data->typ->name : ''}}" readonly="">
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-sm-6" id='category-div'>
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Category</label>
                            <div class="col-sm-12">
                                @if($data->category=='Legal Requirement')
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$data->category ? $data->category : '' }}" readonly="">
                                @elseif($data->category=='Incident Investigation')
                                <input type="text" class="form-control" id="category" style="font-size:13px;" name="category" value="{{$data->category ? $data->category : '' }}" readonly="">
                                @elseif($data->category=='Management Review')
                                <input type="text" class="form-control" id="category" style="font-size:13px;" name="category" value="{{$data->category ? $data->category : '' }}" readonly="">
                                @else
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$data->cat ? $data->cat->categoryname : '' }}" readonly="">
                                @endif
                            </div>
                        </div>

                        @php
                        $category = App\Categories::where('id', $data->category)->first();
                        $enable_source = $category ? $category->enable_source : null;
                        @endphp

                        @if($enable_source)
                        <div class="form-group col-sm-6 {{$enable_source ? '' : 'd-none'}}" id="source_field">
                            <label class="col-sm-12 col-form-label" for="source" style="font-size:13px;">Source</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size:13px;" value="{{ $data->source }}" readonly>
                            </div>
                        </div>
                        @endif
                        <div class="form-group col-sm-6 {{$data->categoryDetectionStage ? '' : 'd-none'}}">
                            <label class="col-sm-12 col-form-label" for="detection_stage_field" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}}</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->categoryDetectionStage ? $data->categoryDetectionStage->name : '' }}" readonly>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size:13px;" value="{{$data->title}}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Observation</label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" style="font-size:13px;" placeholder="Enter observation" readonly>{{$data->observation}}</textarea>
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
                                @endphp
                                <tbody>
                                    <tr>
                                        <td class="td-class px-1">
                                            <textarea cols="50" rows="5" class="form-control" name="correction" style="font-size:13px;" {{ !in_array(Auth::user()->email, $responsible_persons) ? 'readonly' : '' }}>{{$data->correction}}</textarea>
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
                                            @if(count($correction_resp_text_array)>=2)
                                            <textarea class="form-control" style="font-size:13px;" rows="3" readonly>{{implode(', ',$correction_resp_text_array)}}</textarea>
                                            @else 
                                            <input class="form-control" style="font-size:13px;" value="{{ implode(',',$correction_resp_text_array) }}" readonly />
                                            @endif
                                        </td>
                                        <td class="td-class px-1">
                                            <input type="date" name="correction_target_date" id="target_date{{ $data->id }}" value="{{ $data->correction_target_date }}" class="js-datepicker form-control" style="font-size:13px;" placeholder="dd/mm/yy" readonly/>
                                        </td>
                                        <input type="hidden" name="correction_status" id="cor_status_val{{ $data->id }}" value="{{ $data->correction_status }}" />
                                        <td class="td-class px-1" id="corr_status{{ $data->id }}">{{ $data->correction_status ? $data->correction_status . '%' : 0 }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatusCorr" style="border-radius: 0;">
                                                <span class="allTimeTooltip" data-toggle="tooltip" data-placement="top" title="Click here to update status"><i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i></span>
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
                            {{-- </div> --}}
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
                        <div class="form-group mt-4 col-sm-12">
                            {{-- <div class="table-responsive ml-3 mt-4"> --}}

                            <table class="table table-bordered table-striped table-vcenter" id="root_cause_table{{ $data->id }}">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="text-center titleheading" style="width:40px !important;">S #</th>
                                        <th class="text-center titleheading" style="width:240px !important;">Root Cause</th>
                                        <th class="text-center titleheading" style="width:240px !important;">Corrective Action</th>
                                        <th class="text-center titleheading" style="width:140px !important;">Responsibility</th>
                                        <th class="text-center titleheading" style="width:70px !important;">Target Date</th>
                                        <th class="text-center titleheading" style="width:20px !important;">Status</th>
                                        <th style="width:20px;"></th>
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
                                        <td class="td-class"><textarea class="form-control" cols="60" name="rootcause[]" id="rootcause{{ $count - 1}}{{ $data->id }}" rows="5" style="font-size:13px;">{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : ''}}</textarea></td>

                                        <td class="td-class"><textarea class="form-control" cols="60" name="correctiveaction[]" id="correctiveaction{{ $count - 1 }}{{ $data->id }}" rows="5" style="font-size:13px;">{{ isset($correctiveaction[$count - 1]) ? $correctiveaction[$count - 1] : ''}}</textarea></td>

                                        <td class="td-class">
                                            <input type="hidden" class="form-control" style="font-size:13px;" value="{{ isset($department[$count - 1]) ? $department[$count - 1] : ''}}" />

                                            <input class="form-control" style="font-size:13px;" value="{{ $dept }}" readonly />
                                            <input type="hidden" class="form-control" style="font-size:13px;" value="{{ isset($responsibility[$count - 1]) ? $responsibility[$count - 1] : '' }}" />

                                            <input class="form-control mt-2" style="font-size:13px;" value="{{ $resp }}" readonly />
                                        </td>

                                        <td class="td-class">
                                            <input type="date" id="target_date{{ $count - 1}}{{ $data->id }}" class="js-datepicker form-control" style="font-size:13px;" data-week-start="1" data-autoclose="true" data-today-highlight="true" placeholder="dd/mm/yy" data-date-format="dd/mm/yy" value="{{ isset($target_date[$count - 1]) ? $target_date[$count - 1] : ''}}" name="target_date[]" disabled/>
                                        </td>
                                        <td class="td-class" id="status{{ $data->id }}{{ $count}}" readonly>{{ $data->percent_status && isset($percent_status[$count - 1]) ? $percent_status[$count - 1] . '%' : 0 }}</td>

                                        <td>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatus{{ $data->id }}" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                                                <span class="allTimeTooltip" data-toggle="tooltip" data-placement="top" title="Click here to update status"><i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i></span>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownChangeStatus{{ $data->id }}">
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
                                    <input type="hidden" name="percent_status" id="percent_status" value="{{ $data->percent_status }}" />
                                </tbody>
                            </table>
                            {{-- </div> --}}
                        </div>
                        @endif
                        <div class="form-group col-sm-12">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Over all implementation status</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="impl_status" style="font-size:13px;" value="{{ $data->average_percent  }}" readonly>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="action_file" style="font-size:13px;">Attach action related files(avoid '#' and '&' in file names)</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="action_file" style="font-size: 13px;" name="action_file[]" multiple>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Attached file</label>
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 157px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
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
                                                    <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
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
                        <div class="form-group col-sm-12" style="margin-bottom:10px;">
                            <label class="col-sm-6 col-form-label" for="action_file" style="font-size:13px;">Is MOC Required? <span class="text-danger">*</span></label>
                            <div class="col-sm-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_moc_required" id="moc_yes" value="yes" {{ $data->is_moc_required === 'yes' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="moc_yes" style="font-size:13px;">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_moc_required" id="moc_no" value="no" {{ $data->is_moc_required === 'no' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="moc_no" style="font-size:13px;">No</label>
                                </div>
                            </div>
                        </div>
                        @php
                            $dnoneClass = $data->is_moc_required === 'yes' ? '' : 'd-none';
                        @endphp
                        <div class="form-group moc-detail {{$dnoneClass}} col-sm-4">
                            <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">MOC Title <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" placeholder="Enter Title" style="font-size: 13px;" id="mocTitle" name="mocTitle" value="{{$data->is_moc_required === 'yes' && $data->moc ? $data->moc->title : $data->title}}">
                            </div>
                        </div>
                        @if($data->is_moc_required === 'yes')
                        <div class="form-group moc-detail {{$dnoneClass}} col-sm-4">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Status</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;"  value="{{$data->moc ? $data->moc->status : ''}}" readonly>
                            </div>
                        </div>
                        <div class="form-group moc-detail {{$dnoneClass}} col-sm-4">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">MOC ID</label>
                            <div class="col-sm-12">
                                <a href="#" class="view-moc-detail" style="font-size:13px;" data-toggle="tooltip" title="Click to view MOC details"
                                    data-route="{{ $data->moc ? $data->moc->id : ''}}">
                                    {{$data->moc ? $data->moc->crid : ''}}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($data->is_moc_required === 'no')
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
                        @endif
                        <div class="form-group moc-detail {{$dnoneClass}} col-sm-12">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Change description(specific to the NCR/CA)<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <textarea class="form-control" id="changeDescription" placeholder="Enter change description" name="changeDescription" style="font-size:13px;">{{$data->moc ? $data->change_description : ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group text-right col-sm-12">
                            <button type="submit" for="submit" class="btn btn-primary" data-toggle="click-ripple" style="font-size:13px;margin-left:66%;">Update</button>
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
    <div id="view-detail"></div>
</body>
<!-- Below include file contains validation logic for financial loss and potential cost saving -->
@include('user.corrective.common.financialLossAndPotentialCostSavingValidation')

<script src="{{ asset('js/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    let counter = '{{ $cnt }}';
    let departments_text = "{!! $departments_text !!}";
    let users_text = "{!! $users_text !!}";

    function addRaw(id) {

        //let cnt = $(`#first_td${id}${counter - 1}`).text();
        //console.log(Number(cnt), counter);
        //let row_count = $("#root_cause_table" + id + " tr").length;
        //console.log(row_count);
        let progresses = @json($corrective_config);
        let progress_text = "";

        Object.keys(progresses).forEach(key => {
            key = key.toString();
            progress_text += `<input type="hidden" id="selected_status${id}${counter}" /><a href='javascript:void(0)' class='dropdown-item change-status' data-value='${key}' style='font-size:10px;' onclick="changeStatus(${id}, '${key}', ${counter})"><span class='ml-1'>${progresses[key]}</span></a>`;
        });


        let text_to_append_to_table = `<tr id="tr${id}${counter}">
            <td class="td-class" id="first_td${id}${counter}">${counter++}</td>
            <td><textarea class="form-control" name="rootcause[]" id="rootcause${counter}${id}" cols="30" rows="5" style="font-size:13px;"></textarea></td>
            <td><textarea class="form-control" name="correctiveaction[]" id="correctiveaction${counter}${id}" cols="30" rows="5" style="font-size:13px;"></textarea></td>
            <td>
                <select class="form-control department" style="font-size:13px;" required="true" placeholder="Choose a Department">
                    <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                    ` + departments_text +
            `
                </select>
            </td>
            <td class="td-class">
                <select class="form-control" style="font-size:13px;" required="true" placeholder="Choose Responsibility">
                    <option value="0" selected="" disabled style="font-size:13px;">Choose Responsibility</option>
                    ` + users_text +
            `
                </select>
            </td>
            <td>
                <input type="date" class="js-datepicker form-control" style="font-size:13px;" name="target_date[]" id="target_date${counter}${id}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
            </td>
            <td class="td-class" id="status${id}${counter - 1}" readonly>0</td>

            <td class="text-center"><button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr(${id},${counter})">
                <i class="fa fa-fw remove-tr fa-times text-danger"></i></button>
            </td>

             <td>

                 <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" id="dropdownChangeStatus${counter}${id}" style="border-radius: 0;" data-placement="top" data-toggle-second="tooltip" title="Click here to update status">
                     <i class="fas fa-tasks" style="font-size:10px;cursor:pointer"></i>
                 </a>
                 <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownChangeStatus${counter}${id}">
                     ` + progress_text + `
                 </div>

             </td>

        </tr>`;

        $("#root_cause_table" + id).append(text_to_append_to_table);

    }

    function remove_tr(id, index_param) {
        $('#tr' + id + (index_param - 1)).remove();
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
    //let correction_status = sessionStorage.getItem('correction_status');

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


    // let other_status_val = sessionStorage.getItem('other_status');
    // let other_status_val_array = JSON.parse(other_status_val);
    //$('#percent_status').val(other_status.join(','));


    // function average(array) {
    //     var total = 0;
    //     var count = 0;

    //     jQuery.each(array, function(index, value) {
    //         total += value;
    //         count++;
    //     });

    //     return total / count;
    // }

    // let avg = average(other_status_val_array.concat(Number(correction_status)));

    //$('#impl_status').val(Math.round(avg) + '%');
    //$('#average_percent').val(Math.round(avg));

</script>
<script>
    $(document).ready(function(){
        $('.allTimeTooltip').tooltip('show');
        setTimeout(function() {
            $('.allTimeTooltip').tooltip('hide');
        }, 6000);
        const category = '{{$data->category}}';
        const mode = 'readonly';
        const id = '{{$data->id}}';
        $.ajax({
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
    });
</script>
@php
    $statusOfMoc = $data->moc ? $data->moc->status : '';
    $title = $data->moc ? $data->moc->title : $data->title;
    $changeDesc = $data->change_description;
@endphp
<script>
    const statusOfMoc = '{{$statusOfMoc}}';
    $('input[name="is_moc_required"]').on('change', function () {
    const selectedValue = $(this).val();
    if (selectedValue === 'no' && (statusOfMoc === 'Reviewed' || statusOfMoc === 'Approved')) {

       Swal.fire({
            title: 'Warning',
            text: 'This MOC has already progressed beyond the Draft stage and cannot be deleted. Please close or cancel the MOC from the Change Management module.',
            icon: 'warning',
        });

        $('input[name="is_moc_required"][value="yes"]').prop('checked', true);

        $('.moc-detail').removeClass('d-none');
        $('#mocTitle, #changeDescription')
            .attr('required', true);
        return;
    }

    // Normal behavior
    if (selectedValue === 'yes') {
        $('.moc-detail').removeClass('d-none');
        $('#mocTitle, #reviewAuthority, #approveAuthority, #changeDescription')
            .attr('required', true);
        $('#mocTitle').val(@json($title));
        $('#changeDescription').val(@json($changeDesc));
        $('#reviewAuthority, #approveAuthority').val(null).trigger('change');
    } else {
        $('.moc-detail').addClass('d-none');
        $('#mocTitle, #reviewAuthority, #approveAuthority, #changeDescription')
            .attr('required', false);
    }
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


</html>
@endsection
