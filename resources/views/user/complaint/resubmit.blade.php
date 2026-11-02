@extends('layouts.multiselect')
@section('content')
<style>
    .border-3 {
        border-width: 3px !important;
    }
    .td-class{
        font-size:11px;
        padding:4px !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .font-15{
        font-size:12px !important;
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

    #select2-customer-container{
        background-color: #e6e6ff;
    }
    #select2-complaintcategory-container{
        background-color: #e6e6ff;
    }
    #select2-department-container{
        background-color: #e6e6ff;
    }
    #select2-responsibility-container{
        background-color: #e6e6ff;
    }
    .table thead th {
        vertical-align: middle;
    }
</style>
@if($data->division_id)
<style>
    #select2-division_select-container{
        background-color: #e6e6ff;
    }
</style>
@endif
@if($data->area_id)
<style>
    #select2-area_select-container{
        background-color: #e6e6ff;
    }
</style>
@endif
@if($data->mode_of_complaint )
<style>
    #select2-modeofcomplaint-container{
        background-color: #e6e6ff;
    }
</style>
@endif
@if($data->type)
<style>
    #select2-type-container{
        background-color: #e6e6ff;
    }
</style>
@endif
@if($data->plant)
<style>
    #select2-plant-container{
        background-color: #e6e6ff;
    }
</style>
@endif
@if (isset($data->complaint_category_id))
    <style>
        #select2-complaint_category_id-container{
            background-color: #e6e6ff;
        }
    </style>
@endif
@if (isset($data->previous_complaint_reference))
    <style>
        #select2-previous_complaint_reference-container{
            background-color: #e6e6ff;
        }
    </style>
@endif
@if (isset($data->vendor_id))
    <style>
        #select2-vendor_id-container{
            background-color: #e6e6ff;
        }
    </style>
@endif
@if($data->employee)
<style>
    #select2-employee-container{
        background-color: #e6e6ff;
    }
</style>
@endif
<style>
    @media (min-width: 950px) and (max-width: 1190px) {
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
    <!-- Horizontal -->
    @php
        $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
        $isAdminResult = $isAdmin ? true : false;
    @endphp
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/complaintmgmt" class="text-black">
                        Customer Complaint
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Resubmit Complaint
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full space-y-3">
            <nav class="d-flex custom-flex-row flex-column flex-lg-row items-center justify-content-between gap-2">
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        1
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Resubmit</div>
                        <div class="fw-normal">Fill all required</div>
                    </div>
                </a>
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        2
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Entry Review</div>
                        <div class="fw-normal">Complaint Register</div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                    <form id="complaintForm" class="mb-5" onsubmit="return attachments(event)" action="{{url('/user/complaint/resubmit')}}" method="post" enctype="multipart/form-data">

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
                            <input type="hidden" class="form-control" id="addedby" style="font-size: 13px;" name="addedby" value="{{$data->addedby}}">
                            <input type="hidden" class="form-control" id="compid" style="font-size: 13px;" name="compid" value="{{$data->id}}">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Raised By </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{App\User::where('employeeid',$data->addedby)->value('name')}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint ID </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{ $data->trackno}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-4" id="crbDiv">
                                <label class="col-sm-12 col-form-label" for="employee" style="font-size: 13px;">Complaint Received by</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="employee" name="employee" style="font-size: 13px;width:100%;">
                                        <option value="" selected>Choose Employee</option>
                                        @if(count($employees)> 0 )
                                        @foreach($employees as $e)
                                        <option value="{{$e->emp_number}}" <?= $data->employee == $e->emp_number ? 'selected' : '' ?>> {{ $e->emp_name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size: 13px;">Complaint Received Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="crdate" style="font-size: 13px;" name="crdate" value="{{ $data->crdate}}" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="Mode of Compliant" style="font-size: 13px;">Mode of Compliant</label>
                                <div class="col-sm-12">
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
                                </div>
                            </div>
                        </div>

                        @if ($activeComplaintTemplateId == null)
                            @include('user.complaint.template.editNoTemplate')
                        @elseif ($activeComplaintTemplateId == 1)
                            @include('user.complaint.template.editProductTemplate')
                        @elseif ($activeComplaintTemplateId == 2)
                            @include('user.complaint.template.editProjectTemplate')
                        @endif

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="file" style="font-size: 13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="file" style="font-size: 13px;" name="file[]" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
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
                                                <table id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
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
                                                                <td>
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
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 text-right" id="resubmitcomplaint">
                            <button for="submittl" type="submit" class="btn btn-primary"style="font-size:13px;">Resubmit</button>
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
                                                    <td style="font-size:10px;">{{ $o=$o+1}}</td>
                                                    <td style="font-size:13px;">{{ $f->files}}</td>
                                                    <td style="font-size:11px;">
                                                        <a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/complaint/'.$f->files)}}" download="{{$f->files}}">Download</a>
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
    <button type="button" class="js-swal-warning d-none addplant"></button>
    <button type="button" class="js-swal-warning d-none addservice"></button>
    <button type="button" class="js-swal-warning d-none addcategory"></button>
    <button type="button" class="js-swal-warning d-none addcrb"></button>
    <button type="button" class="js-swal-warning d-none addunit"></button>
</div>
<!-- START Apps Modal -->
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

                        {{-- <p>

                        <div class="col-6">
                            <!-- CRM -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/complaint/add">
                                <div class="block-content text-center">
                                    <i class="si si-speedometer fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                    Add Complaint
                                    </p>
                                </div>
                            </a>
                            <!-- END CRM -->
                        </div> --}}

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
<!-- END Apps Modal -->
<script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
<script>
    if($('#crdate').val()!=''){
        $('#crdate').css("background-color","#e8f0fe");
    }
    $('#saleorder').css("background-color","#e8f0fe");
    $('#title').css("background-color","#e8f0fe");
    $('#complaint').css("background-color","#e8f0fe");
    $('#action').css("background-color","#e8f0fe");
    $('#deliverydate').css("background-color","#e8f0fe");
    $('#customeremail').css("background-color","#e8f0fe");
    $('#deliveryno').css("background-color","#e8f0fe");
    if($('#job_order_no').val()!=''){
        $('#job_order_no').css("background-color","#e8f0fe");
    }
    if($('#product_serial_no').val()!=''){
        $('#product_serial_no').css("background-color","#e8f0fe");
    }
    if($('#project_name').val()!=''){
        $('#project_name').css("background-color","#e8f0fe");
    }
    if($('#job_history').val()!=''){
        $('#job_history').css("background-color","#e8f0fe");
    }
    if($('#date').val()!=''){
        $('#date').css("background-color","#e8f0fe");
    }
    if($('#customerphone').val()!=''){
        $('#customerphone').css("background-color","#e8f0fe");
    }
    if($('#customeraddress').val()!=''){
        $('#customeraddress').css("background-color","#e8f0fe");
    }
    if ($('#no_of_occurrence').val()!='') {
        $('#no_of_occurrence').css("background-color","#e8f0fe");
    }
    if ($('#external_reference').val()!='') {
        $('#external_reference').css("background-color","#e8f0fe");
    }

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
                        jQuery(".addcrb").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new employee, so please contact them.", "warning")
                        })),
                        jQuery(".addunit").on("click", (function(n) {
                            e.fire("Warning", "Only admins can add new unit, so please contact them.", "warning")
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
    var removedFileIds = [];
    var removedIssueFileIds = [];
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
    });
    function attachments(event) {
        if(confirm('Are you sure to resubmit? ')){
            $('<input />').attr('type', 'hidden')
                .attr('name', 'removedFileIds')
                .attr('value', JSON.stringify(removedFileIds))
                .appendTo($('#resubmitcomplaint'));
            $('<input />').attr('type', 'hidden')
                .attr('name', 'removedIssueFileIds')
                .attr('value', JSON.stringify(removedIssueFileIds))
                .appendTo($('#resubmitcomplaint'));
            return true;
        }
        event.preventDefault();
        return false;
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
</script>
@php
    $isAdmin = DB::table('admins')->where('email',Auth::user()->email)->first();
    $isAdminResult = $isAdmin ? true : false;
    $categoriesJson = json_encode($complaint_cat);
@endphp
<script type="text/javascript">

    $(document).ready(function() {
        $('example-select2-multiple').select2({});
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

    var clickCount1 = 0;
    var clickCount2 = 0;
    var clickCount3 = 0;
    var clickCount4 = 0;
    var clickCount5 = 0;
    let globalCategories = @php echo $categoriesJson; @endphp;
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
                $("#modal-block-large").modal('show')
            });

        });
    });
    $(document).ready(function() {
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
        $('#plant').change(function(){
            if(!($(this).val()=="")){
                $('#select2-plant-container').css("background-color","#e6e6ff");
            }

            if("{{$category_plant_dependency->meta_value}}" != "Independent") {
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
        $('#complaint_category_id').change(function() {
            if(!($(this).val()=="")){
                $('#select2-plant-container').css("background-color","#e6e6ff");
            }
        });
        $('#previous_complaint_reference').change(function(){
            if($(this).val()){
                $('#select2-previous_complaint_reference-container').css("background-color","#e6e6ff");
            }
        });
        $('#vendor_id').change(function() {
            if(!($(this).val()=="")){
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

        @if(isset($complaintTemplateSectionFields['job_history']))
            jobHistoryEnabled = true;
            jobHistoryRequired = {{ $complaintTemplateSectionFields['job_history']['field_is_mandatory'] ? 'true' : 'false' }};
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
        });
    </script>
@endsection



