@extends('layouts.simple')
@section('content')

<div class="content" style="width:90%;">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full" >
                <div class="row no-gutters justify-content-center">
                    <div class="col-sm-12 col-xl-12">
                        <form class="mb-5" action="{{ route('nearmiss.submit') }}" enctype="multipart/form-data" method="POST" files="true">
                            {{csrf_field()}}
                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif
                            {{-- @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif --}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary alert-dismissible fade show auto-close-alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {!! Session::get('msg') !!}
                            </div>
                            @endif
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="reporterType" style="font-size:13px;">
                                        Reporter Type <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-12">
                                        <input type="hidden" name="reporterType" id="reporterType" value="">

                                        <input type="button" id="employee"
                                            class="btn btn-sm btn-outline-primary reporterTypeBtn mr-2"
                                            value="Employee">

                                        <input type="button" id="guest"
                                            class="btn btn-sm btn-outline-primary reporterTypeBtn"
                                            value="Guest">

                                        <div id="reporterType-error"
                                            class="text-danger mt-1"
                                            style="display:none;font-size:12px;">
                                            Please select a reporter type.
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 employeeReporter d-none">
                                    <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Employee Id<span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">
                                        <input type="hidden" id="employeeVerified" value="0">
                                        <input type="text" placeholder="Enter Employee ID" class="form-control" id="employeeid" style="font-size:13px;width:100%;" name="employeeid">
                                        <div id="employeeError"
                                            class="text-danger"
                                            style="display:none;font-size:12px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 guestReporter d-none">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Reporter Name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="reporterName" name="reporterName" style="font-size:13px;" placeholder="Enter reporter name">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 d-none employeeDetails">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Employee Name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control"  id="emp_name" name="emp_name" value="" style="font-size:13px;" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 d-none employeeDetails">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Department</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="department" name="department" value="" style="font-size:13px;" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 guestReporter d-none">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Mobile Number</label>
                                    {{-- <div class="col-sm-12">
                                        <input type="text" class="form-control" id="mobileNumber" name="mobileNumber" style="font-size:13px;" placeholder="Enter mobile number">
                                    </div> --}}
                                    <div class="col-sm-12">
                                        <input type="tel"
                                            class="form-control"
                                            id="mobileNumber"
                                            name="mobileNumber"
                                            style="font-size:13px;"
                                            placeholder="e.g. +91 9876543210"
                                            maxlength="16"
                                            autocomplete="tel">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 guestReporter d-none">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Company Name<span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="companyName" name="companyName" style="font-size:13px;" placeholder="Enter Company Name">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 guestReporter d-none">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Email Address<span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">
                                        <input type="email" class="form-control" id="email" name="email" style="font-size:13px;" placeholder="Enter Email Address">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 d-none commanField">
                                    <label class="col-sm-4 col-form-label" for="title" style="font-size:13px;">Title<span class="text-danger">*</span> </label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="title" name="title" style="font-size:13px;" placeholder="Enter Title" required="true">
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 d-none commanField">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="observation">Observation<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" required="true"></textarea>
                                    </div>
                                </div>
                            
                                <div class="form-group col-sm-12 d-none commanField">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="agreedaction">Recommended action</label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" style="font-size:13px;" id="agreedaction" name="agreedaction" placeholder="Enter recommended action"></textarea>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 d-none commanField">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="issue_file">Attach issue related files (avoid '#' and '&' in file names)</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" style="font-size:13px;" id="issue_file" name="issue_file[]" multiple>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 text-right d-none commanField">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>
<script src="{{url('/assets/js/oneui.core.min.js')}}"></script>
<script src="{{url('/assets/js/oneui.app.min.js')}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $('.reporterTypeBtn').click(function(){
        var dval = $(this).val();
        $('#reporterType').val(dval);
        $('#reporterType-error').hide();
        $('.commanField').removeClass('d-none');
        if(dval=="Employee"){
            $('#employee').addClass('btn-alt-primary').css('background-color','#5179d6').css('border','none').css('color','white');
            $('#guest').removeClass('btn-alt-primary').css('background-color','').css('border','').css('color','#5179d6');
            $('.guestReporter').addClass('d-none');
            $('.employeeReporter').removeClass('d-none');
            $('#employeeid').prop('required',true);
            $('#companyName').val('').prop('required',false);
            $('#email').val('').prop('required',false);
            $('#mobileNumber').val('');
            $('#reporterName').val('');
        }else if(dval=="Guest"){
            $('#guest').addClass('btn-alt-primary').css('background-color','#5179d6').css('border','none').css('color','white');
            $('#employee').removeClass('btn-alt-primary').css('background-color','').css('border','').css('color','#5179d6');
            $('.employeeReporter').addClass('d-none');
            $('.guestReporter').removeClass('d-none');
            $('#employeeid').val('').prop('required',false);
            $('#companyName').prop('required',true);
            $('#email').prop('required',true);
            $('.employeeDetails').addClass('d-none');
        }
    });
    
    $('#employeeid').on('blur', function () {
        let employeeid = $(this).val().trim();
        $('#employeeVerified').val(0);
        if (employeeid == '') {
            $('#employeeError').hide();
            return;
        }
        $.ajax({
            url: "{{ route('nearmiss.employee.verify') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                employeeid: employeeid
            },
            success: function (response) {

                if (response.status) {

                    $('#employeeVerified').val(1);
                    $('#employeeError').hide();
                    $('#emp_name').val(response.data.name);
                    $('#department').val(response.data.department);
                    $('.employeeDetails').removeClass('d-none');
                } else {
                    $('.employeeDetails').addClass('d-none');
                    $('#employeeVerified').val(0);
                    $('#emp_name').val('');
                    $('#department').val('');
                    $('#employeeError')
                        .text('Invalid Employee ID.')
                        .show();
                }
            }
        });
    });
    $('form').on('submit', function (e) {
        if ($('.employeeReporter').hasClass('d-none')) {
            return true;
        }
        e.preventDefault();
        if ($('#employeeVerified').val() != "1") {
            $('#employeeError')
                .text('Please enter a valid Employee ID.')
                .show();
            $('#employeeid').focus();
            return false;
        }
        $(this).off('submit').submit();
    });
    $(document).ready(function () {
        setTimeout(function () {
            $('.auto-close-alert').fadeOut('slow', function () {
                $(this).alert('close');
            });
        }, 2000);
    });
    $('#mobileNumber').on('input', function () {
        let value = $(this).val();
        value = value.replace(/(?!^\+)[^\d]/g, '');
        value = value.replace(/^\++/, '+');
        if (value.length > 1) {
            value = value.charAt(0) + value.substring(1).replace(/\+/g, '');
        }
        $(this).val(value);
    });
</script>
@endsection    