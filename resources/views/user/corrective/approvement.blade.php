@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
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
                        Entry Review
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
                        <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            2
                        </div>
                        <div class="flex-grow-1 text-left ml-2">
                            <div class="font-15">Entry&nbsp;Review</div>
                            <div class="fw-normal">Open CA</div>
                        </div>
                    </a>

                    <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                        <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                    <div class="col-lg-12 px-5">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/corrective/approvement')}}" onsubmit="return approveIssueCorr(event)" enctype="multipart/form-data" method="POST" files="true">
                            {{csrf_field()}}
                            <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                            <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
                            <input type="hidden" name="corrective_id" value="{{$id}}">
                            @include('sweet::alert')


                            @php
                            $rr=$data->id;
                            @endphp
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
                                        <input type="text" class="form-control" id="name" style="font-size:13px;" name="name" value="{{Auth::user()->name}}" readonly="">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span style="color:red;">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" required name="type" required="true" style="font-size:13px;width:100%;" data-placeholder="Choose Type">
                                            <option></option>
                                            @if(count($type)>0)
                                            @foreach($type as $typ)
                                            <option value="{{$typ->order}}" style="font-size:13px;" {{ $data->type == $typ->order ? 'selected' : '' }}> {{$typ->name}}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" id="category-div">
                                    <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Category</label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" name="category" id="category" required="true" style="font-size:13px;width:100%;" data-placeholder="Please select the category name" onchange="showSource($(this).val())">
                                            <option></option></br>
                                            @if($data->category=='Legal Requirement')
                                            <option value="{{$data->category}}" selected>{{$data->category}}</option>
                                            @endif
                                            @if($data->category == 'Incident Investigation')
                                            <option value="{{$data->category}}" selected>{{$data->category}}</option>
                                            @endif
                                            @if(count($categories)>0)
                                                @foreach($categories as $cat)
                                                @if($cat->categoryname != "Internal Audit")
                                                <option value="{{$cat->id}}" {{ intval($data->category) === $cat->id ? 'selected' : '' }}> {{$cat->categoryname}}</option></br>
                                                @endif
                                                @endforeach
                                            @endif
                                            @if($data->cat && $data->cat->deleted_at!='')
                                            <option value="{{$data->cat->id}}" selected>{{$data->cat->categoryname}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group d-none col-sm-6" id="source_field">
                                    <label class="col-sm-12 col-form-label" for="source" style="font-size:13px;">Source</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="source" style="font-size:13px;" name="source" value="{{ $data->source }}">
                                    </div>
                                </div>
                                <div class="form-group d-none col-sm-6" id="detection_stage_field">
                                    <label class="col-sm-12 col-form-label" for="detection_stage_field" style="font-size:13px;">{{$detectionStageLabel ? $detectionStageLabel->name : 'Detection Stage'}} <span class="text-danger">{{in_array(Auth::user()->email,$issue_reviewers) ? '*' : '' }}</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" name="detectionStage" id="detectionStage" style="font-size:13px;width:100%;" data-placeholder="Select where detected">
                                            <option></option>
                                            @if(count($detectionStages)>0)
                                                @foreach($detectionStages as $stage)
                                                <option value="{{$stage->id}}" {{$stage->id==$data->detection_stage ? 'selected' : ''}} style="font-size:13px;">{{$stage->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Responsible Department for correction <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                            {{-- <input type="text" class="form-control" style="font-size: 13px;" value="{{$data->dept ? $data->dept->departmentname : '' }}"> --}}
                                            <select class="form-control js-select2" name="department" id="department" style="font-size:13px;width:100%:" required="true" placeholder="Choose a Department" onchange="chooseDivision($(this).val())">
                                                <option value="0" selected="" disabled style="font-size:13px;">Choose a Department</option>
                                                @if(count($departments)>0)
                                                @foreach($departments as $dep)
                                                <option value="{{$dep->id}}" style="font-size:13px;" {{ $data->correction_department && intval($data->correction_department) === $dep->id ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
                                                @endforeach
                                                @endif
                                            </select>
                                    </div>
                                </div>
                                @php
                                    $divisions = $data->correction_department ? App\Division::where('department_id', $data->correction_department)->get(['id','name']) : [];
                                    $areas = $data->division_id ? App\Area::where('division_id', $data->division_id)->get(['id','name']) : [];
                                @endphp
                                <div class="form-group {{count($divisions)>0 ? '' : 'd-none'}} col-sm-6" id="division">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" id="division_select" name="division" required="true" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                            <option value="0" selected="" style="font-size:13px;">Choose a Division</option>
                                            @if(count($divisions)>0)
                                            @foreach($divisions as $division)
                                            <option value="{{$division->id}}" {{$division->id==$data->division_id?'selected':''}}>{{$division->name}}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group {{count($areas)>0?'':'d-none'}} col-sm-6" id="area">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                    <div class="col-sm-12" id="action_number" name="action">
                                        <select class="form-control js-select2" id="area_select" name="area" required="true" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                            <option value="0" selected="" style="font-size:13px;">Choose Sub Division</option>
                                            @if(count($areas)>0)
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}" {{$area->id==$data->area_id?'selected':''}}>{{$area->name}}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" value="{{$data->title}}" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="observation" name="observation" style="font-size:13px;" placeholder="Enter observation" required>{{$data->observation}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="name" style="font-size:13px;">Is this a previously occurred NCR?</label>
                                    <div class="col-sm-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="ncr_yes" name="ncr_question" value="yes" {{$data->is_previously_occurred=='yes' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="ncr_yes" style="font-size:13px;">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="ncr_no" name="ncr_question" value="no" {{$data->is_previously_occurred=='no' ? 'checked' : ''}}>
                                            <label class="form-check-label" for="ncr_no" style="font-size:13px;">No</label>
                                        </div>   
                                    </div>
                                </div>
                                <div class="form-group {{$data->is_previously_occurred=='yes' ? '' : 'd-none'}} col-sm-6" id="no_of_occurrence_div">
                                    <label class="col-sm-12 col-form-label" for="no_of_occurrence" style="font-size:13px;">No. of Occurrence</label>
                                    <div class="col-sm-12">
                                        <input type="number" min="1" placeholder="Enter No. of Occurrence" class="form-control" id="no_of_occurrence" style="font-size:13px;" name="no_of_occurrence" value="{{$data->no_of_occurrence}}">
                                    </div>
                                </div>
                                <div class="form-group {{$data->is_previously_occurred=='yes' ? '' : 'd-none'}} col-sm-6" id="previous_ncr_reference_div">
                                    <label class="col-sm-12 col-form-label" for="previous_ncr_reference" style="font-size:13px;">Previous NCR Reference</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="previous_ncr_reference" id="previous_ncr_reference" style="font-size:13px;width:100%;">
                                            <option></option> 
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mt-2 {{$data->previous_ncr_reference!=0 || $data->previous_ncr_reference==null ? 'd-none' : ''}}" id="otherNcrReferenceDiv">
                                        <input type="text" class="form-control" id="other_ncr" style="font-size:13px;" name="other_ncr" placeholder="Enter Previous NCR Reference" value="{{$data->other_previous_ncr_reference}}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended action</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction">{{$data->agreedaction}}</textarea>
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
                                {{-- @if(!in_array(Auth::user()->email, $issue_reviewers)) --}}
                                @php
                                $selected_frequency_array = explode(',', $data->frequency);
                                @endphp
                                {{-- <div class="form-group" style="display:inline-block;width:98.5%;"> --}}
                                    {{-- </div> --}}
                            </div>
                            <p style="font-size:14px;" class="font-weight-bold ml-3 text-danger">To be filled by reviewer</p>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Correction Responsibility <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" name="responsibility[]" id="responsibility" style="font-size:13px;width:100%;" required="true" placeholder="Choose Responsibility" multiple>
                                            <option value="0" disabled style="font-size:13px;">Choose Responsibility</option>
                                            @if(count($user)> 0 )
                                            @foreach($user as $use)
                                            <option value="{{$use->email}}" style="font-size:13px;" {{ in_array($use->email, explode(',', $data->correction_responsibility)) ? 'selected' : '' }}> {{ $use->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action:</label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" style="font-size:13px;width:100%;" id="frequency" name="frequency[]" style="width: 100%;" data-placeholder="Choose frequencies" multiple>
                                            <option value="Monthly" style="font-size:13px;" {{ in_array('Monthly', $selected_frequency_array) ? 'selected' : '' }}>Monthly</option><br>
                                            <option value="Weekly" style="font-size:13px;" {{ in_array('Weekly', $selected_frequency_array) ? 'selected' : '' }}>Weekly</option><br>
                                            <option value="2 Days Before" style="font-size:13px;" {{ in_array('2 Days Before', $selected_frequency_array) ? 'selected' : '' }}>2 days before</option><br>
                                        </select>
                                    </div>
                                </div>
                                @php
                                $selected_notification_array = explode(',', $data->notification);
                                @endphp
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;">Send notifications to:</label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" style="font-size:13px;width:100%;" id="example-select2-multiple" name="notification[]" style="width: 100%;" data-placeholder="Choose Persons" multiple>
                                            @if(count($user)> 0 )
                                            @foreach($user as $use)
                                            <option value="{{$use->email}}" style="font-size:13px;" {{ in_array($use->email, $selected_notification_array) ? 'selected' : '' }}>{{ $use->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Recommended due date <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="date" name="correction_target_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" value="{{$data->correction_target_date}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- @endif --}}
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="seperate" style="font-size: 13px;">
                                        <div class="custom-control custom-switch custom-control-success mb-1 mt-2">
                                            Do you need a seperate team to do Rootcause analysis and corrective action? &nbsp;&nbsp;
                                            <input type="checkbox" name="seperate" id="seperate_checkbox" class="custom-control-input" onchange="handleEvent()" {{ $data->team_lead_id != 0 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="seperate_checkbox"></label>
                                        </div>
                                    </label>
                                </div>
                                <br>
                                <div class="form-group d-none col-sm-6" id="lead_block">
                                    <label class="col-sm-12 col-form-label" for="lead" style="font-size:13px;">Team leader for identifying Root Cause & Corrective Action <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-select" name="lead" id="lead" style="font-size:13px;width:100%;" data-placeholder="Choose Team leader">
                                            <option></option>
                                            @if(count($user)> 0 )
                                            @foreach($user as $use)
                                            <option value="{{$use->id}}" style="font-size:13px;" {{ intval($data->team_lead_id) === $use->id ? 'selected' : '' }}>{{ $use->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group d-none col-sm-6" id="investigation_block">
                                    <label class="col-sm-12 col-form-label" for="investigation" style="font-size:13px;">Team Members<span class="text-danger">(if required)</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-select" name="investigation[]" id="investigation" style="font-size:13px;width:100%;" data-placeholder="Choose Investigation Team" multiple>
                                            @if(count($user)> 0 )
                                            @foreach($user as $use)
                                            <option value="{{$use->id}}" style="font-size:13px;" {{ $investigators && in_array($use->id, $investigators) ? 'selected' : '' }}>{{ $use->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group d-none col-sm-6" id="target_date_block">
                                    <label class="col-sm-12 col-form-label" for="investigation_date" style="font-size:13px;">Target date for Investigation <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="js-datepicker form-control" style="font-size:13px;width:100%;" id="investigation_date" name="investigation_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{$data->investigation_date}}">
                                    </div>
                                </div>


                                <div class="form-group col-sm-12" id="attachmentsDiv">
                                    <label class="col-sm-12 col-form-label" for="download" style="font-size:13px;">Attached issues files</label>
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 150px;" data-toggle="modal" data-id="{{ $data->id }}" data-target="#viewModal{{$data->id}}"><strong>Attachments&nbsp(Issues)</strong> </button>
                                        @if( count($actiondocs) > 0 )
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 157px;" data-toggle="modal" data-id="" data-target="#viewModal"><strong>Attachments&nbsp(Actions)</strong> </button>
                                        @endif
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
                                                                    <td>
                                                                        <a style="font-size:11px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a>
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
                                                                <button class="btn btn-secondary remove-action-attachment" data-filename="{{ $value2 }}" style="font-size:11px;">Remove</button>
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
                            </div>
                            <div class="form-group">
                                <button type="submit" for="submit" class='btn btn-primary btn-small' style="font-size:13px;margin-left:74.5%;">Approve</button>
                                <a class='btn btn-primary btn-small' onclick="return confirm('Are You Sure to dissaprove?')" href="{{url('/user/corrective/disapprovereason/'.$rr)}}" style="font-size:13px;">Disapprove</a>
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
<!-- Below include file contains validation logic for financial loss and potential cost saving -->
@include('user.corrective.common.financialLossAndPotentialCostSavingValidation')

@php
    $mode = $data->cat && ($data->cat->is_pnc_cat=='yes' || $data->cat->is_process_cat=='yes' || $data->cat->is_vendor_cat=='yes') ? 'col-sm-6' : 'blank-col-sm-6';
@endphp
<script src="{{ asset("js/jquery.min.js")}}"></script>
<script>
    var previous_ncr_reference = "{{$data->previous_ncr_reference}}";
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function chooseDivision(id) {
        $('#division').addClass('d-none');
        $('#area').addClass('d-none');
        $('#area_select').empty().append('<option value="0" selected>Choose Sub Division</option>');
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
                    $('#division_select').empty().append('<option value="0">Choose Division</option></br>' + option_text);
                } else {
                    $('#division').addClass('d-none');
                    $('#division_select').empty().append('<option value="0" selected>Choose Division</option>');
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
                    $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                }else{
                    $('#area').addClass('d-none');
                    $('#area_select').empty().append('<option value="0" selected>Choose Sub Division</option>');
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
    var categoryId = $('#category').val();
    var detectionStageValue = $('#detectionStage').val();
    showSource(categoryId);
    function showSource(id) {
        let the_url = "{{ route('user.corrective.enableSource', ':id')}}";
        the_url = the_url.replace(':id', id);

        let source_selector = $('#source_field');
        let detection_selector = $('#detection_stage_field');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(response) {
                let source_enabled = response.data;
                if (source_enabled) {
                    source_selector.removeClass('d-none');
                    source_selector.addClass('d-inline-block');
                } else {
                    source_selector.removeClass('d-inline-block');
                    source_selector.addClass('d-none');
                }
                if(response.enable_detection_stage){
                    detection_selector.removeClass('d-none')
                   $('#detectionStage').prop('required',true);
                }else{
                    detection_selector.addClass('d-none');
                    $('#detectionStage').prop('required',false);
                }
                if(categoryId==id){
                    $('#source').val('{{$data->source}}');
                    $('#detectionStage').val(detectionStageValue).trigger('change');
                }else{
                    $('#detectionStage').val('').trigger('change');
                    $('#source').val('');
                }

            }
            , error: function(err) {
                console.log(err);
            }
        });
        const category = id;
        const mode = '{{$mode}}';
        const dataid = '{{$data->id}}';
        $.ajax({
            method: 'GET',
            url: window.location.origin + `/user/corrective/get-product-ncr-fields/${category}/${mode}/${dataid}`,
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

    handleEvent();

    function handleEvent() {
        let yes_checked = $('#seperate_checkbox').is(":checked");
        if (yes_checked) {
            $('#lead_block').removeClass('d-none');
            $('#investigation_block').removeClass('d-none');
            $('#target_date_block').removeClass('d-none');
            $('#investigation_date').prop('required',true);
            $('#lead').prop('required',true);
        } else {
            $('#lead_block').addClass('d-none');
            $('#investigation_block').addClass('d-none');
            $('#target_date_block').addClass('d-none');
            $('#investigation_date').prop('required',false);
            $('#lead').prop('required',false);
        }

    }
    var removedIssueFilenames = [];
    var removedActionFilenames = [];
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
    function approveIssueCorr(event){
        if(confirm('Are you sure to approve?')){
            $('<input />').attr('type', 'hidden')
                .attr('name', 'removedIssueFilenames')
                .attr('value', JSON.stringify(removedIssueFilenames))
                .appendTo($('#attachmentsDiv'));
            $('<input/>').attr('type', 'hidden')
                .attr('name', 'removedActionFilenames')
                .attr('value', JSON.stringify(removedActionFilenames))
                .appendTo($('#attachmentsDiv'));
            return true;
        }
        event.preventDefault();
        return false;
    }
</script>
<script src="{{versionedCache('js/corrective/edit_corrective.js')}}"></script>
</html>
@endsection
@section('js_after')
<script type="text/javascript">
    $(document).ready(function() {
        $('#sel').select2({});
        $('#sel_freq').select2({});
    });


</script>

@endsection
