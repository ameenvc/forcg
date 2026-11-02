@extends('layouts.multiselect')
@section('css_multiselect')
<style>
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
</style>
@endsection
@section('content')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    function validation() {


        if (document.getElementById('department').value == 0) {
            alert('Please select Department');
            document.getElementById('department').focus();
            return false;
        }
        if (document.getElementById('type').value == 0) {
            alert('Please select Type');
            document.getElementById('type').focus();
            return false;
        }
        if (document.getElementById('responsibility').value == 0) {
            alert('Please select Responsibility');
            document.getElementById('responsibility').focus();
            return false;
        }

        //validation for due date calendar required field

        if (document.getElementById('date').value == 0) {
            alert('Please select Recommended due date');
            document.getElementById('date').focus();
            return false;
        }

        if(document.getElementById("financial_loss").length > 32) {
            alert('Max 32 characters allowed for Financial Loss');
            document.getElementById('financial_loss').focus();
            return false;
        }

        if(document.getElementById("potential_cost_savings").length > 32) {
            alert('Max 32 characters allowed for Financial Loss');
            document.getElementById('potential_cost_savings').focus();
            return false;
        }


   // always return false to prevent form from submission.
    }
</script>
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="{{ route('nearmiss.index')}}" class="text-black">
                       Near Miss
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Initial Review
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 pr-5 pl-5">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{url('/user/nearmiss/initial_reviewed_ok')}}" enctype="multipart/form-data" onsubmit="return validation()" method="POST" files="true">
                        {{csrf_field()}}
                        @include('sweet::alert')
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Issue ID</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;"  value="{{$data->issueid}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="raised" style="font-size:13px;">Reporter Type</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;"  value="{{$data->reporter_type}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 {{$data->reporter_type=='Guest' ? 'd-none' : ''}}">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Employee Id<span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <input type="text" placeholder="Enter Employee ID" class="form-control" id="employeeid" style="font-size:13px;width:100%;" name="employeeid" value="{{ $data->employeeid }}" readonly>
                                    <div id="employeeError"
                                        class="text-danger"
                                        style="display:none;font-size:12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 {{$data->reporter_type=='Guest' ? '' : 'd-none'}}">
                                <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Reporter Name</label>
                                <div class="col-sm-12">
                                    <input type="text" value="{{ $data->reporter_name }}" class="form-control" id="reporterName" name="reporterName" style="font-size:13px;" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 {{$data->reporter_type=='Guest' ? '' : 'd-none'}}">
                                <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Mobile Number</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" style="font-size:13px;" placeholder="Enter mobile number" value="{{ $data->mobile_number }}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 {{$data->reporter_type=='Guest' ? '' : 'd-none'}}">
                                <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Company Name<span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="companyName" name="companyName" style="font-size:13px;" placeholder="Enter Company Name" value="{{ $data->company_name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 {{$data->reporter_type=='Guest' ? '' : 'd-none'}}">
                                <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Email Address<span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <input type="email" class="form-control" id="email" name="email" style="font-size:13px;" placeholder="Enter Email Address" value="{{ $data->email}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="type" id="type" required="true" style="font-size:13px;" data-placeholder="Choose Type">
                                        <option value=""></option>
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        <option value="{{$typ->name}}"> {{$typ->name}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="title" style="font-size:13px;" name="title" placeholder="Enter title.." value="{{ $data->title }}" required>
                                </div>
                            </div>
                            
                            
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="observation" style="font-size:13px;">Observation<span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" required>{{ $data->observation }}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6" id="department_div">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Department<span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" name="department" id="department" style="font-size:13px;" required="true" data-placeholder="Choose a Department" onchange="chooseDivision($(this).val())">
                                        <option></option>
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" style="font-size:13px;" {{ intval(old('department')) === $dep->id ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none col-sm-6" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" required="true" style="width:100%;font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option value="0">Choose Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none col-sm-6" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="area" required="true" style="width:100%;font-size: 13px;" placeholder="Sub Division">
                                        <option value="0">Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="responsibility">Responsibility <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control w-100" id="responsibility" name="responsibility" data-placeholder="Choose Responsibility" required="true" style="font-size:13px;">
                                        <option value=""></option>
                                        @if(count($user)> 0)
                                        @foreach($user as $use)
                                        <option value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="targetdate" style="font-size:13px;">Target Date <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="targetdate" style="font-size:13px;" name="targetdate" required>
                                </div>
                            </div>
                            
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="agreedaction" style="font-size:13px;">Recommended Action</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" id="agreedaction" style="font-size:13px;" name="agreedaction" placeholder="Enter recommended action">{{ $data->agreedaction=='NULL' ? '' : $data->agreedaction}}</textarea>
                                </div>
                            </div>
                            {{-- <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="issue_file" style="font-size:13px;">Attach issue related files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="issue_file" style="font-size:13px;" name="issue_file[]" multiple>
                                </div>
                            </div> --}}
                            @php
                                $issuedocs=explode(',',$data->file);
                            @endphp
                            <div class="form-group col-sm-12">
                                <label class="col-sm-6 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal1"><strong>Attachments&nbsp(Issues)</strong> </button>
                                    <div id="viewModal1" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments</h3>
                                                </div>
                                                <table class="table table-responsive table-hover table-bordered table-striped"
                                                    style="font-size:100%;font-family:Arial, Helvetica, sans-serif;">
                                                    <thead>
                                                        <tr class="table-secondary">
                                                            <th width="8%">S.No</th>
                                                            <th>Attachment</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @php $n = 1; @endphp

                                                        @forelse($issuedocs as $value)

                                                            @if($value && $value != "NULL")

                                                                @php
                                                                    $filePath = File::exists(public_path('storage/nearmiss/'.$value))
                                                                        ? asset('storage/nearmiss/'.$value)
                                                                        : asset('storage/issue_files/'.$value);
                                                                @endphp

                                                                <tr>
                                                                    <td>{{ $n++ }}</td>

                                                                    <td>{{ $value }}</td>

                                                                    <td class="text-center">
                                                                        <a href="{{ $filePath }}"
                                                                        class="btn btn-success btn-sm"
                                                                        download="{{ $value }}">
                                                                            Download
                                                                        </a>
                                                                    </td>
                                                                </tr>

                                                            @endif

                                                        @empty

                                                            <tr>
                                                                <td colspan="3" class="text-center">
                                                                    <strong>No Files Available</strong>
                                                                </td>
                                                            </tr>

                                                        @endforelse

                                                    </tbody>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="reviewauthority" style="font-size:13px;">Action Review Authority<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="reviewauthority" name="reviewauthority[]" style="font-size:13px;" multiple data-placeholder="Select Action Review Authority" required>
                                        @if(count($issuereviewers)> 0 )
                                        @foreach($issuereviewers as $reviewers)
                                        <option value=" {{$reviewers->email}}" style="font-size:13px;"> {{ $reviewers->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="closureauthority" style="font-size:13px;">Closure Review Authority<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="closureauthority" style="font-size:13px;" name="closureauthority[]" multiple data-placeholder="Select Closure Review Authority" required>
                                        @if(count($issuereviewers)> 0 )
                                        @foreach($issuereviewers as $reviewers)
                                        <option value="{{$reviewers->email}}" style="font-size:13px;"> {{ $reviewers->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <a class='btn btn-danger'  style="font-size:13px;background-color:#d1293d;" href="{{url('/user/nearmiss/initial_reviewed_notok/'.$data->id)}}">Reject</a>
                                <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple" style="font-size: 13px;background-color:#25499c;" >Accept</button>
                            </div>
                        </div>

                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>
<!-- Apps Modal -->
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
                            <a class="block block-rounded block-link-shadow bg-body" href="{{ route('nearmiss.index') }}">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.closedissue')}}">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.openissue')}}">
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
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.analysis')}}">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
<script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
<script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    jQuery(function() {
        One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#sel_not').select2({});
        $('#sel').select2({});
        $('#page-header-user-dropdown').click(function(event) {
            var dropdownEle = $('div.dropdown-menu[aria-labelledby="page-header-user-dropdown"]');
            dropdownEle.toggleClass('show');
            event.stopPropagation(); // Prevent the event from bubbling up to the body
        });

        // Click event on body to hide the dropdown
        $('body').click(function() {
            var dropdownEle = $('div.dropdown-menu[aria-labelledby="page-header-user-dropdown"]');
            if (dropdownEle.hasClass('show')) {
                dropdownEle.removeClass('show');
            }
        });
    });
</script>
<script>
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
            },
            method: 'get',
            url: the_url,
            success: function(resp) {
                if (resp.data.length) {
                    $('#division').removeClass('d-none');
                    $('#division').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`;
                    }
                    $('#division_select').empty().append('<option value="0">Choose Division</option></br>' + option_text);
                } else {
                    $('#division').addClass('d-none');
                    $('#division_select').empty().append('<option value="0" selected>Choose Division</option>');
                }
            },
            error: function(result) {
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
</script>
@endsection
