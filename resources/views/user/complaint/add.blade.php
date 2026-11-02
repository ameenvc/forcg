@extends('layouts.multiselect')

@section('content')
<!-- Hero -->
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
@if(!in_array(Auth::user()->email, $issue_reviewers))
<style>
    @media (min-width: 950px) and (max-width: 1120px) {
        .custom-flex-row {
            flex-direction: column !important;
        }
    }
</style>
@endif
<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

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
                    Add Complaint
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full space-y-3">
            <nav class="d-flex flex-column flex-lg-row items-center custom-flex-row justify-content-between gap-3">
                <a href="javascript:void(0)" class="btn btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        1
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Add Complaint</div>
                        <div class="fw-normal">Fill all required</div>
                    </div>
                </a>
                @if(!in_array(Auth::user()->email, $issue_reviewers))
                <a href="javascript:void(0)" class="btn btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        2
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Entry Review</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>
                @endif

                <a href="javascript:void(0)" class="btn btn-sm btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 3 : 2}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Propose Action</div>
                        <div class="fw-normal">My Complaints</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 4 : 3}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Review Action</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 5 : 4}}
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Update Progress</div>
                        <div class="fw-normal">My Complaints</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        {{!in_array(Auth::user()->email, $issue_reviewers) ? 6 : 5}}
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

                    <form id="complaintForm" class="mb-5" action="{{url('/user/complaint/insertcomplaint')}}" method="post" enctype="multipart/form-data">

                        {{csrf_field()}}

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
                            <input type="hidden" class="form-control" id="addedby" style="font-size: 13px;" name="addedby" value="{{Auth::user()->name}}">
                            <input type="hidden" class="form-control" id="complaint_template_id" style="font-size: 13px;" name="complaint_template_id" value="{{$activeComplaintTemplate->id}}">
                            <input type="hidden" class="form-control" id="complaint_template_section_field_rules" style="font-size: 13px;" name="complaint_template_section_field_rules" value='@json($complaintTemplateSectionFields)'>
                            <input type="hidden" class="form-control" id="category_plant_dependency" style="font-size: 13px;" name="category_plant_dependency" value='{{$category_plant_dependency->meta_value}}'>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Raised By </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{Auth::user()->name}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3" id="crbDiv">
                                <label class="col-sm-12 col-form-label" for="employee" style="font-size: 13px;">Complaint Received by</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="employee" name="employee" style="font-size: 13px;width:100%;">
                                        <option value="" selected>Choose Employee</option>
                                        @if(count($employees)> 0 )
                                        @foreach($employees as $e)
                                        <option value="{{$e->emp_number}}" <?= old('employee')==$e->emp_number ? 'selected' : '' ?>> {{ $e->emp_name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Received Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="crdate" style="font-size: 13px;" name="crdate" value="{{ old('crdate')}}" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="Mode of Compliant" style="font-size: 13px;">Mode of Compliant</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="modeofcomplaint" name="modeofcomplaint" style="font-size: 13px;width:100%;">
                                        <option value="" selected>Choose Mode</option>
                                        <option value="online forms" <?= old('modeofcomplaint')=="online forms" ? 'selected' : '' ?>>Online forms</option>
                                        <option value="phone call" <?= old('modeofcomplaint')=="phone call" ? 'selected' : '' ?>>Phone call</option>
                                        <option value="emails" <?= old('modeofcomplaint')=="emails" ? 'selected' : '' ?>>Emails</option>
                                        <option value="fax" <?= old('modeofcomplaint')=="fax" ? 'selected' : '' ?>>Fax</option>
                                        <option value="in-person visits to customer service center" <?= old('modeofcomplaint')=="in-person visits to customer service center" ? 'selected' : '' ?>>In-person visits to customer service center</option>
                                        <option value="direct office" <?= old('modeofcomplaint')=="direct office" ? 'selected' : '' ?>>Direct office</option>
                                        <option value="hot lines" <?= old('modeofcomplaint')=="hot lines" ? 'selected' : '' ?>>Hot lines</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if ($complaintTemplateSectionFields['customer']['enable_field'] || $complaintTemplateSectionFields['customerphone']['enable_field'] || $complaintTemplateSectionFields['customeremail']['enable_field'] || $complaintTemplateSectionFields['customeraddress']['enable_field'] || $complaintTemplateSectionFields['product']['enable_field'] )
                            <div class="row p-0">
                                <fieldset class="col-sm-12 row p-0 ml-4" style="border:2px solid #dfe4e8;max-width:95%;">
                                    <legend style="font-size:15px;width:90px;text-weight:bold;" class="bg-primary text-white p-1">Customer</legend>
                                    @if ($complaintTemplateSectionFields['customer']['enable_field'])
                                        <div class="form-group col-sm-12 col-md-4 px-0" id="customerDiv">
                                            <label class="col-sm-12 col-form-label" for="Customer Name" style="font-size: 13px;">{{$complaintTemplateSectionFields['customer']['field_display_label']}} @if($complaintTemplateSectionFields['customer']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                                            <div class="col-sm-12">
                                                <select data-placeholder="Select {{$complaintTemplateSectionFields['customer']['field_display_label']}}" class="form-control" name="customer" id="customer" style="font-size: 13px;width:100%;" {{$complaintTemplateSectionFields['customer']['field_is_mandatory'] ? 'required' : ''}}></select>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($complaintTemplateSectionFields['customerphone']['enable_field'])
                                        <div class="form-group col-sm-12 col-md-4 px-0">
                                            <label class="col-sm-12 col-form-label" for="Customer Phone" style="font-size: 13px;">{{$complaintTemplateSectionFields['customerphone']['field_display_label']}} @if($complaintTemplateSectionFields['customerphone']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" style="font-size: 13px;" id="customerphone" name="customerphone" value="{{old('customerphone')}}" placeholder="Enter {{$complaintTemplateSectionFields['customerphone']['field_display_label']}}" {{$complaintTemplateSectionFields['customerphone']['field_is_mandatory'] ? 'required' : ''}}>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($complaintTemplateSectionFields['customeremail']['enable_field'])
                                        <div class="form-group col-sm-12 col-md-4 px-0">
                                            <label class="col-sm-12 col-form-label" for="Customer Email" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeremail']['field_display_label']}}@if($complaintTemplateSectionFields['customeremail']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                                            <div class="col-sm-12">
                                                <input type="email" class="form-control" style="font-size: 13px;" id="customeremail" name="customeremail" value="{{old('customeremail')}}" placeholder="Enter {{$complaintTemplateSectionFields['customeremail']['field_display_label']}}" {{$complaintTemplateSectionFields['customeremail']['field_is_mandatory'] ? 'required' : ''}}>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($complaintTemplateSectionFields['customeraddress']['enable_field'])
                                        <div class="form-group col-sm-12 col-md-4 px-0">
                                            <label class="col-sm-12 col-form-label" for="Customer Address" style="font-size: 13px;">{{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}@if($complaintTemplateSectionFields['customeraddress']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control" style="font-size: 13px;" id="customeraddress" name="customeraddress" placeholder="Enter {{$complaintTemplateSectionFields['customeraddress']['field_display_label']}}" {{$complaintTemplateSectionFields['customeraddress']['field_is_mandatory'] ? 'required' : ''}}>{{old('customeraddress')}}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($complaintTemplateSectionFields['product']['enable_field'])
                                        <div class="form-group col-sm-12 col-md-4 px-0" id="productServiceDiv">
                                            <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">{{$complaintTemplateSectionFields['product']['field_display_label']}} @if($complaintTemplateSectionFields['product']['field_is_mandatory'])<span class="text-danger">*</span> @endif</label>
                                            <div class="col-sm-12">
                                                <select data-placeholder="Select {{$complaintTemplateSectionFields['product']['field_display_label']}}" class="form-control js-select2" id="product" name="product[]" style="font-size: 13px; width: 100%;" {{$complaintTemplateSectionFields['product']['field_is_mandatory'] ? 'required' : ''}} multiple>
                                                    @if(count($product) > 0)
                                                        @foreach($product as $value)
                                                            <option value="{{ $value->id }}" {{ in_array($value->id, old('product', [])) ? 'selected' : '' }}>
                                                                {{ $value->product }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </fieldset>
                            </div>
                        @endif

                        @if ($activeComplaintTemplate->title == "Product Template")
                            @include('user.complaint.template.addProductTemplate')
                        @elseif ($activeComplaintTemplate->title == "Project Template")
                            @include('user.complaint.template.addProjectTemplate')
                        @endif

                        <div class="row">
                            @if(in_array(Auth::user()->email, $issue_reviewers))
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size: 13px;">Email reminder for action:</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="sel_freq" name="frequency[]" style="font-size: 13px;width:100%;" multiple>
                                        <option value="Monthly" {{ in_array('Monthly', old('frequency', [])) ? 'selected' : '' }}>Monthly</option><br>
                                        <option value="Weekly" {{ in_array('Weekly', old('frequency', [])) ? 'selected' : '' }}>Weekly</option><br>
                                        <option value="2 Days Before" {{ in_array('2 Days Before', old('frequency', [])) ? 'selected' : '' }}>2 Days Before</option><br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="notification" style="font-size: 13px;">Send notifications to:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="example-select2-multiple" name="notification[]" style="font-size: 13px;width:100%;" multiple>
                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value="{{$use->email}}" <?= old('notification') && in_array($use->email, old('notification')) ? 'selected' : '' ?>>{{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="financial_loss" style="font-size:13px;">Financial Loss</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control"  id="financial_loss" style="font-size:13px;" name="financial_loss" value="{{ old('financial_loss')}}" placeholder="Enter Financial Loss">
                                    <span id="error_financial_loss" style="color:red; font-size: 12px; margin-left:10px;"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                <label class="col-sm-12 col-form-label" for="potential_cost_savings" style="font-size:13px;">Potential Cost Savings</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="potential_cost_savings" style="font-size:13px;" name="potential_cost_savings" value="{{ old('potential_cost_savings')}}" placeholder="Enter Potential Cost">
                                    <span id="error_potential_cost_savings" style="color:red; font-size: 12px; margin-left:10px;"></span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-6 col-lg-4">
                                <label class="col-sm-12 col-form-label" for="file" style="font-size: 13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="file" value="{{old('file')}}" style="font-size: 13px;" name="file[]" multiple>
                                </div>
                            </div>
                        </div>
                        @if(in_array(Auth::user()->email, $issue_reviewers))
                            <div class="row">
                                <div class="form-group col-sm-12 mb-0">
                                    <label class="col-sm-8 col-form-label" for="seperate" style="font-size: 13px;">
                                    Do you need investigation team? <span class="text-danger">*</span>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="seperate_yes" name="seperate" onchange="handleEvent()"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="seperate_no" name="seperate" onchange="handleEvent()" checked><label style="font-size: 13px;">No</label>
                                    </label>
                                </div>

                                <div class="form-group d-none col-sm-4" id="lead_block">
                                    <label class="col-sm-12 col-form-label" for="lead" style="font-size:13px;">Team leader <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-select w-100" name="lead" id="lead" style="font-size:13px;width:100%;" required="true" placeholder="Choose Team leader">
                                            <option selected disabled value="">Choose Team Leader</option>
                                            @if(count($user)> 0 )
                                            @foreach($user as $use)
                                            <option value="{{$use->id}}" <?= old('lead')==$use->id ? 'selected' : '' ?> style="font-size:13px;">{{ $use->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group d-none col-sm-4" id="investigation_block">
                                    <label class="col-sm-12 col-form-label" for="investigation" style="font-size:13px;">Team Members<span>(if required)</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-select w-100" name="investigation[]" id="investigation" style="font-size:13px;width:100%;" placeholder="Choose Investigation Team" multiple>
                                            @if(count($user)> 0 )
                                            @foreach($user as $use)
                                            <option value="{{$use->id}}" style="font-size:13px;" {{ old('investigation') && in_array($use->id, old('investigation')) ? 'selected' : '' }}>{{ $use->name }}</option><br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group d-none col-sm-4" id="target_date_block">
                                    <label class="col-sm-12 col-form-label" for="investigation_date" style="font-size:13px;">Target date for investigation <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="investigation_date" name="investigation_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{ old('investigation_date')}}">

                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary" onclick="return ddldd()" style="font-size:13px; margin-left:84%; ">Add</button>
                        </div>
                    </form>
                    <script>
                        function ddldd() {
                            if ($("#file").val() == '') {
                                mess2 = 'Are you sure to submit without attachment?';
                                y = confirm(mess2);
                                if (y == true) {} else {
                                    return false;
                                }
                            } else {
                                mess1 = "Are you sure?";
                                x = confirm(mess1);
                                if (x == true) {} else {
                                    return false;
                                }
                            }
                        }

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
                                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/complaint/closedc" style="margin-top:-16px">
                                                    <div class="block-content text-center" style="height:130px">
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
                    <!-- Button trigger modal -->
                    <!-- Large Block Modal -->
                    <div class="modal" id="modal-block-large" tabindex="-1" role="dialog" aria-labelledby="modal-block-large" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="block block-rounded block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">Add Customer</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">Customer added successfully!</div>
                                    <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                                    <div class="block-content font-size-sm">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="customer" style="font-size: 13px;">Customer name <span class="text-danger">*</span></label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;" class="form-control" id="customerajax" name="customerajax" placeholder="Enter Name" required value="{{ old('customer') }}">
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="product" style="font-size: 13px;">Product/service <span class="text-danger">*</span></label>
                                                <div class="col-sm-12">
                                                    <select class="js-select2 form-control" style="font-size: 13px;width:100%;" id="productajax" name="productajax[]" data-placeholder="Choose a Product/Service" multiple required>
                                                        @if(count($product)> 0 )
                                                        @foreach($product as $value)
                                                        <option value="{{$value->id}}"> {{ $value->product }}</option></br>
                                                        @endforeach
                                                        @endif
                                                    </select>

                                                </div>
                                            </div>


                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="country" style="font-size: 13px;">Country <span class="text-danger">*</span></label>
                                                <div class="col-sm-12">
                                                    <select class="js-select2 form-control" style="font-size: 13px;width: 100%;" id="country" name="country" onchange="showStates()" required>
                                                       <option value="" selected disabled>Choose Country</option>
                                                        @if(count($countries)> 0 )
                                                        @foreach($countries as $value)
                                                        <option value="{{$value->id}}">{{ $value->country_name }}</option>
                                                        @endforeach
                                                        @endif
                                                        <option value="0">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 mt-1 d-none" id="otherCountry">
                                                    <input type="text" style="font-size: 13px;" placeholder="Enter Country Name" class="form-control" id="other_country" name="other_country" value="{{ old('other_country') }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="state" style="font-size: 13px;">State</label>
                                                <div class="col-sm-12">
                                                    <select class="js-select2 form-control" style="font-size: 13px;width: 100%;" id="state" name="state" data-placeholder="Choose a state" onchange="showOther()">
                                                    </select>

                                                </div>
                                                <div class="col-sm-12 mt-1 d-none" id="otherState">
                                                    <input type="text" style="font-size: 13px;" placeholder="Enter State Name" class="form-control" id="other_state" name="other_state" value="{{ old('other_state') }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="location" style="font-size: 13px;">Location</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;" class="form-control" id="location" placeholder="Enter Location" name="location" value="{{ old('location') }}" />
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="pin" style="font-size: 13px;">Customer Code <span class="text-danger">*</span></label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;" class="form-control" id="pinajax" name="pinajax" placeholder="Enter Pin" value="{{ old('pin') }}" required/>
                                                </div>
                                            </div>


                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="email" style="font-size: 13px;">Email</label>
                                                <div class="col-sm-12">
                                                    <input type="email" style="font-size: 13px;" class="form-control" id="emailajax" name="emailajax" placeholder="Enter Email" value="{{ old('email') }}"/>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="col-sm-12 col-form-label" for="phone" style="font-size: 13px;">Phone</label>
                                                <div class="col-sm-12">
                                                    <input type="number" style="font-size: 13px;" class="form-control" id="phoneajax" name="phoneajax" placeholder="Enter Phone" value="{{ old('phone') }}" min="0">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label class="col-sm-12 col-form-label" for="customer_survey" style="font-size: 13px;">Customer email addresses to contact for survey</label>
                                                <div class="col-sm-12">
                                                    <textarea style="font-size: 13px;" class="form-control" id="customer_survey" placeholder="Customer email addresses to contact for survey" name="customer_survey">{{ old('customer_survey') }}</textarea>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="block-content block-content-full text-right border-top">
                                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="addCustomer()">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Large Block Modal -->
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="js-swal-warning d-none addplant"></button>
    <button type="button" class="js-swal-warning d-none addservice"></button>
    <button type="button" class="js-swal-warning d-none addcategory"></button>
    <button type="button" class="js-swal-warning d-none addcrb"></button>
    <button type="button" class="js-swal-warning d-none addunit"></button>
</div>
<!-- END Page Container -->
<!-- Below include file contains validation logic for financial loss and potential cost saving -->
@include('user.complaint.common.financialLossAndPotentialCostSavingValidation');

<script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $('.cgbodselect').select2();
        // when select2 is opened, store the select element
        $('.cgbodselect').on('select2:open', function () {
            currentSelect = $(this);
        });

        $(document).on('input', '.select2-search__field', function () {
            let value = $(this).val();
            if(currentSelect){
                let table = currentSelect.data('table');
                let valueColumn = currentSelect.data('value-column');
                let textColumn = currentSelect.data('text-column');
                let sortColumn = currentSelect.data('sort-column');
                let sortDirection = currentSelect.data('sort-direction');
                $.ajax({
                    url: "/user/complaint/getDataFromTableAjax",
                    data: {
                        table: table,
                        value_column: valueColumn,
                        text_column: textColumn,
                        sort_column: sortColumn,
                        sort_direction: sortDirection,
                        search: value
                    },
                    success: function(response) {
                        console.log(response.data)
                        currentSelect.empty(); // remove old options

                        response.data.forEach(function(item){

                            let option = new Option(
                                item[textColumn],   // option text
                                item[valueColumn],  // option value
                                false,
                                false
                            );
                            currentSelect.append(option);
                        });
                        currentSelect.trigger('change.select2'); //
                    },
                    error: function(e) {
                        console.error("Error");
                    }
                });
            }
        });
    });
</script>
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
                        jQuery(".addunit").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new unit, so please contact them.", "warning")
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
<?php
    $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
    $isAdminResult = $isAdmin ? true : false;
    $categoriesJson = json_encode($complaint_cat);
?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#example-select2-multiple').select2({});
        $('#customer').select2({
            ajax: {
                url: window.location.protocol + "//" + window.location.host +"/user/complaint/getAllCustomersForAddComplaint",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data.items, function (item) {
                            return {
                                id: item.id,
                                text: item.customer
                            };
                        }),
                        pagination: {
                            more: data.hasMore
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Choose Customer'
        });
    });
    $(document).ready(function() {
        $('#sel_freq').select2({});
    });
    // let globalCategories = [];
    let globalCategories = @php echo $categoriesJson; @endphp;
    var clickCount1 = 0;
    var clickCount2 = 0;
    var clickCount3 = 0;
    var clickCount4 = 0;
    var clickCount5 = 0;
    var isAdmin = "{{$isAdminResult}}";

    $(document).ready(function(){
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

        $("#complaintCategoryDiv").click(function() {
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addComplaintCategoryBtn" target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Category</i></a></li></ul>`;
            if(clickCount1==0){
                $('#select2-complaint_category_id-results').after(addBtn);
                clickCount1++;
            }
            $('#addComplaintCategoryBtn').click(function(){
                if(isAdmin){
                    window.open("{{ route('admin.complaint.category') }}", "_blank");
                }else{
                    $('.addcategory').click();
                }
            });
        });

        $("#productServiceDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addProductBtn" target="_blank" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Product/Service</i></a></li></ul>`;
            if(clickCount2==0){
                $('#select2-product-results').after(addBtn);
                clickCount2++;
            }
            $('#addProductBtn').click(function(){
                if(isAdmin){
                    window.open("/admin/viewproduct", "_blank");

                }else{
                    $('.addservice').click();
                }
            });

        });

        $("#categoryDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addCategoryBtn" target="_blank" style="font-size:11px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Category</i></a></li></ul>`;
            if(clickCount3==0){
                $('#select2-complaintcategory-results').after(addBtn);
                clickCount3++;
            }
            $('#addCategoryBtn').click(function(){
                if(isAdmin){
                    window.open("{{ route('admin.complaint.category') }}", "_blank");

                }else{
                    $('.addcategory').click();
                }
            });

        });
        $("#unitDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addUnitBtn" target="_blank" style="font-size:11px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Unit</i></a></li></ul>`;
            if(clickCount3==0){
                $('#select2-unit-results').after(addBtn);
                clickCount3++;
            }
            $('#addUnitBtn').click(function(){
                if(isAdmin){
                    window.open("{{ route('admin.complaint.category') }}", "_blank");

                }else{
                    $('.addunit').click();
                }
            });

        });
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
        $("#customerDiv").click(function(){
            var addBtn = `<hr style="margin:auto 0;border:solid 1px;"><ul style="list-style-type:none;padding:0;margin:0;cursor:pointer;"><li class="select2-results__option select2-results__option"><a id="addCustomerBtn" style="font-size:13px;" class="text-primary"><i class="fa fa-2x fa-plus" style="font-size:13px;">&nbsp;New Customer</i></a></li></ul>`;
            if(clickCount5==0){
                $('#select2-customer-results').after(addBtn);
                clickCount5++;
            }
            $('#addCustomerBtn').click(function(){
                $('.select2-dropdown').css('z-index','1');
                $("#modal-block-large").modal('show');
            });

        });
    });
    $(document).ready(function() {
        // let category_plant_dependency = '{{$category_plant_dependency->meta_value}}';
        // if (category_plant_dependency == 'Independent') {
        //     $.ajax({
        //         url: "/user/complaint/getCategoryAjax",
        //         data: {
        //             id: ''
        //         },
        //         success: function(response) {
        //             let categories = response.data;
        //             console.log(categories);
        //             globalCategories = response.data;
        //             let categories_text = undefined;
        //             for(var i = 0; i < categories.length; i++) {
        //                 categories_text += `<option value="${categories[i].id}">${categories[i].category_name}</option><br>`;
        //             }
        //             $('.categoryclass').empty().append(categories_text);

        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.error("Error fetching customer data:", textStatus, errorThrown);
        //         }
        //     });
        // }

        $('#customer').on("change", function() {
            $('#customerphone').css("background-color","#fff");
            $('#customeremail').css("background-color","#fff");
            $('#customeraddress').css("background-color","#fff");

            var customerId = $(this).val();
            $.ajax({
            url: "/user/complaint/getCustomerAjax",
            data: {
                id: customerId
            },
            success: function(response) {
                setCustomerValues(response);
                $('#select2-customer-container').css("background-color","#e6e6ff");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching customer data:", textStatus, errorThrown);
            }
            });
        });
        setBlurFocusEvents('#itemcode');
        setBlurFocusEvents('#productdesc');
        setBlurFocusEvents('#idno');
        setBlurFocusEvents('#totalquant');
        setBlurFocusEvents('#affectquant');
        setBlurFocusEvents('#unit');
        setBlurFocusEvents('#costimpact');
        setBlurFocusEvents('#sizeofitem');
        setBlurFocusEvents('#salereturnreq');
        setBlurFocusEvents('#reworkreq');
        setBlurFocusEvents('#deliveryno');
        setBlurFocusEvents('#crdate');
        setBlurFocusEvents('#customerphone');
        setBlurFocusEvents('#customeremail');
        setBlurFocusEvents('#customeraddress');
        setBlurFocusEvents('#saleorder');
        setBlurFocusEvents('#no_of_occurrence');
        setBlurFocusEvents('#external_reference');
        setBlurFocusEvents('#title');
        setBlurFocusEvents('#complaint');
        setBlurFocusEvents('#action');
        setBlurFocusEvents('#deliverydate');
        setBlurFocusEvents('#job_order_no');
        setBlurFocusEvents('#product_serial_no');
        setBlurFocusEvents('#project_name');
        setBlurFocusEvents('#job_history');
        setBlurFocusEvents('#date');
        setBlurFocusEvents('#financial_loss');
        setBlurFocusEvents('#potential_cost_savings');
        setBlurFocusEvents('#investigation_date');
        setBlurFocusEvents('#file');

        $('#employee').change(function(){
            $('#select2-employee-container').css("background-color","#e6e6ff");
        });
        $('#modeofcomplaint').change(function(){
            if(!($(this).val()=="")){
                $('#select2-modeofcomplaint-container').css("background-color","#e6e6ff");
            }
        });
        $('#complaintcategory').change(function(){
            if(!($(this).val()=="")){
                $('#select2-complaintcategory-container').css("background-color","#e6e6ff");
            }
        });
        $('#type').change(function(){
            if(!($(this).val()=="")){
                $('#select2-type-container').css("background-color","#e6e6ff");
            }
        });
        $('#unit').change(function(){
            if($(this).val()){
                $('#select2-unit-container').css("background-color","#e6e6ff");
            }
        });
        $('#plant').change(function(){
            if(!($(this).val()=="")){
                $('#select2-plant-container').css("background-color","#e6e6ff");
            }
            if ("{{$category_plant_dependency->meta_value}}" != 'Independent') {

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
                        let categories_text2 = `<option value="">Select Complaint Category</option><br>`;

                        for(var i = 0; i < categories.length; i++) {
                            categories_text += `<option value="${categories[i].id}">${categories[i].category_name}</option><br>`;
                            categories_text2 += `<option value="${categories[i].id}">${categories[i].category_name}</option><br>`;
                        }
                        $('.categoryclass').empty().append(categories_text);
                        $('#complaint_category_id').empty().append(categories_text2);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error fetching customer data:", textStatus, errorThrown);
                    }
                });

            }
        });
        $('#complaint_category_id').change(function(){
            if($(this).val()){
                $('#select2-complaint_category_id-container').css("background-color","#e6e6ff");
            }
        });
        $('#previous_complaint_reference').change(function(){
            if($(this).val()){
                $('#select2-previous_complaint_reference-container').css("background-color","#e6e6ff");
            }
        });

        $('#vendor_id').change(function(){
            if($(this).val()){
                $('#select2-vendor_id-container').css("background-color","#e6e6ff");
            }
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
        $('#lead').change(function(){
            if(!($(this).val()=="")){
                $('#select2-lead-container').css("background-color","#e6e6ff");
            }
        });
        $('#country').change(function(){
            $('#select2-country-container').css("background-color","#e8f0fe");
        });
        $('#state').change(function(){
            $('#select2-state-container').css("background-color","#e8f0fe");
        });
        function setBlurFocusEvents(selector) {
            $(selector).blur(function(){
                $(this).css("background-color","#e8f0fe");
            }).focus(function(){
                $(this).css("background-color","white");
            });
        }
        setBlurFocusEvents('#customerajax');
        setBlurFocusEvents('#location');
        setBlurFocusEvents('#pinajax');
        setBlurFocusEvents('#emailajax');
        setBlurFocusEvents('#phoneajax');
        setBlurFocusEvents('#customer_survey');
    });

    function setCustomerValues(response){
        $('#customerphone').val(response.data.phone);
        if(response.data.phone!=null){
            $('#customerphone').css("background-color","#e8f0fe");
        }
        $('#customeremail').val(response.data.email);
        $('#customeremail').css("background-color","#e8f0fe");
        $('#customeraddress').val(response.data.location);
        if(response.data.location!=null){
            $('#customeraddress').css("background-color","#e8f0fe");
        }
        let products = response.products;
        let remainproduct = response.remainproduct;
        let product_text = undefined;
        if(typeof(products)=="object"){
            for(var i = 0; i < products.length; i++) {
                product_text += `<option value="${products[i].id}" selected>${products[i].product}</option><br>`;
            }
        }else{
            product_text += `<option value="${products}" selected>${products}</option><br>`;
        }

        for(let i=0;i<remainproduct.length;i++){
            product_text += `<option value="${remainproduct[i].id}">${remainproduct[i].product}</option><br>`;
        }
        $('#product').empty().append(product_text);

    }
    function chooseDivision(id) {
        $('#division_select').val("");
        $('#area_select').val("");
        $('#division').addClass('d-none');
        $('#area').addClass('d-none');
        // $('#division_select').removeAttr('required');
        // $('#area_select').removeAttr('required');
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
                    // $('#division_select').attr('required',true);
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
        $('#area_select').val("");
        $('#area').addClass('d-none');
        // $('#area_select').removeAttr('required');
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
                    // $('#area_select').attr('required',true);
                    $('#area_select').empty().append('<option value="">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function showStates() {
        let states = [];
        let country_id = $('#country').val();
        let states_text = "<option value='' selected>Choose a state</option>";
        let states_url = '{{ route("country.states", ":id") }}';
        states_url = states_url.replace(':id', country_id);
        if(country_id!='0'){
            $('#otherCountry').addClass('d-none');
            $('#other_country').removeAttr('required');
            $('#otherState').addClass('d-none');
            $.ajax({
                url: states_url
                , type: "GET"
                , success: function(result) {
                    states = result.data;
                    for (var i = 0; i < states.length; i++) {
                        states_text += `<option value="${states[i].id}">${states[i].state_name}</option><br>`
                    }
                    states_text+=`<option value="0">Other</option>`;
                    $('#state').empty().append(states_text);
                }
                , error: function(result) {
                    console.log('error', result);
                }
            });
        }else{
            states_text+=`<option value="" selected>Choose a state</option><option value="0">Other</option>`;
            $('#state').empty().append(states_text);
            $('#otherCountry').removeClass('d-none');
            $('#other_country').attr('required', 'required');
        }
    }
    function showOther(){
        let statevalue = $('#state').val();
        if(statevalue=='0'){
            $('#otherState').removeClass('d-none');
        }else{
            $('#otherState').addClass('d-none');
        }
    }
</script>
<script>
    handleEvent();

    function handleEvent() {
        let yes_checked = $('#seperate_yes').is(":checked");
        if (yes_checked) {
            $('#lead_block').removeClass('d-none').addClass('d-inline-block');
            $('#investigation_block').removeClass('d-none').addClass('d-inline-block');
            $('#target_date_block').removeClass('d-none').addClass('d-inline-block');
            $('#lead').attr('required',true);
            $("#investigation_date").attr('required',true);
        } else {
            $('#lead_block').removeClass('d-inline-block').addClass('d-none');
            $('#investigation_block').removeClass('d-inline-block').addClass('d-none');
            $('#target_date_block').removeClass('d-inline-block').addClass('d-none');
            $('#lead').removeAttr('required');
            $("#investigation_date").removeAttr('required');
        }

    }
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
</script>

<script>
    function addCustomer(){
        var error_selector = $('#dangerBtn');
        var success_selector = $('#successBtn');

        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
            $(error_selector).fadeIn('slow').delay(2000);
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
            $(success_selector).fadeIn('slow').delay(2000);

        }
        var formData = new FormData();
        formData.append('customerajax', $('#customerajax').val());
        formData.append('productajax', $('#productajax').val());
        formData.append('country', $('#country').val());
        formData.append('state', $('#state').val());
        formData.append('location', $('#location').val());
        formData.append('pinajax', $('#pinajax').val());
        formData.append('emailajax', $('#emailajax').val());
        formData.append('phoneajax', $('#phoneajax').val());
        formData.append('customer_survey',$('#customer_survey').val());
        formData.append('other_state',$('#other_state').val());
        formData.append('other_country',$('#other_country').val());
        $.ajax({
            url: "/user/complaint/addcustomerajax",
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
                    if(response.error=="This customer already exists."){
                        error_selector.append(`<li>${response.error}</li>`);
                    }else{
                        $.each(response.error, function(index, errorMessage) {
                            error_selector.append(`<li>${errorMessage}</li>`);
                        });
                    }
                } else {
                    showSuccess();
                    setTimeout(function() {
                        $('#customerajax').val("");
                        $('#location').val("");
                        $('#pinajax').val("");
                        $('#emailajax').val("");
                        $('#phoneajax').val("");
                        $('#customer_survey').val("");
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
    }
</script>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ versionedCache('js/complaint/summernote_init.js') }}"></script>
    <script>
        let jobHistoryEnabled = false;
        let jobHistoryRequired = false;

        @if (isset($complaintTemplateSectionFields['job_history']))
            jobHistoryEnabled = true;
            jobHistoryRequired = {{ $complaintTemplateSectionFields['job_history']['field_is_mandatory'] }};
        @endif

        function moveToErrorMessage($fieldId) {
            document.querySelector($fieldId)
                .closest('.form-group')
                .scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
        }
        $(document).ready(function() {
            initTextField('#complaint', true);
            initTextField('#action', true);
            if (jobHistoryEnabled) {
                initTextField('#job_history', jobHistoryRequired);
            }
        });

        $('#complaintForm').submit(function(e) {
            let value = $('#warranty_coverage').val();

            if ($('#warranty_coverage').length && !value) {
                e.preventDefault(); // stop form submission
                document.getElementById('warranty_coverage-error').style.display = 'block';
                // scroll to field
                moveToErrorMessage('#warranty_coverage')
                // document.querySelector('#warranty_coverage')
                // .closest('.form-group')
                // .scrollIntoView({
                //     behavior: 'smooth',
                //     block: 'center'
                // });
                return false;
            }
            if (jobHistoryRequired) {
                var validateJobHistory = validateSummerNoteTextField('#job_history')
                if (!validateJobHistory) {
                    e.preventDefault();
                    moveToErrorMessage('#job_history')
                    return false;
                }
            }
            var validateDescription = validateSummerNoteTextField('#complaint')
            if (!validateDescription) {
                e.preventDefault();
                moveToErrorMessage('#complaint')
                return false;
            }
            var validateRecommendedAction = validateSummerNoteTextField('#action')
            if (!validateRecommendedAction) {
                e.preventDefault();
                moveToErrorMessage('#action')
                return false;
            }
            // if (jobHistoryRequired) {
                // if ($('#job_history').summernote('isEmpty')) {

                //     e.preventDefault();
                //     document.getElementById('job_history-error').style.display = 'block';

                //     document.querySelector('#job_history')
                //     .closest('.form-group')
                //     .scrollIntoView({
                //         behavior: 'smooth',
                //         block: 'center'
                //     });
                //     return false;
                // }
            // }
            // return false;
            // if ($('#complaint').summernote('isEmpty')) {
            //     e.preventDefault();
            //         document.getElementById('job_history-error').style.display = 'block';

            //         document.querySelector('#job_history')
            //         .closest('.form-group')
            //         .scrollIntoView({
            //             behavior: 'smooth',
            //             block: 'center'
            //         });
            //         return false;
            // }
            // if ($('#action').summernote('isEmpty')) {
            //     e.preventDefault();
            //     alert('action is required');
            //     return false;
            // }
        });
    </script>
@endsection
