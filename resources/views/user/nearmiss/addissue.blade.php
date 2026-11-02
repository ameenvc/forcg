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
                    Add Issue
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form class="mb-5" action="{{ route('nearmiss.insertissue') }}" enctype="multipart/form-data" method="POST" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="posted" value="{{Auth::user()->email}}">
                        <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">
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
                        <input type="hidden" class="form-control" id="name" style="font-size:13px;" name="name" value="{{Auth::user()->name}}" readonly="">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="ques">Is this near miss responsible to you? <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" style="font-size: 13px;width:100%;" name="ques" id="ques" onchange="isBelong($(this).val())" data-placeholder="Choose" required>
                                        <option></option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4" id="department_div">
                                <label class="col-sm-12 col-form-label" for="department" style="font-size:13px;">Responsible Department<span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" name="department" id="department" style="font-size:13px;width:100%;" required="true" data-placeholder="Choose a Department" onchange="chooseDivision($(this).val())">
                                        <option></option>
                                        @if(count($departments)>0)
                                        @foreach($departments as $dep)
                                        <option value="{{$dep->id}}" style="font-size:13px;" {{ intval(old('department')) === $dep->id ? 'selected' : '' }}> {{$dep->departmentname}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none col-sm-4" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" required="true" style="width:100%;font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option value="0">Choose Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-none col-sm-4" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="area" required="true" style="width:100%;font-size: 13px;" placeholder="Sub Division">
                                        <option value="0">Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4" id="theArea">
                                <label class="col-sm-12 col-form-label" for="area" style="font-size:13px;">Area<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;" required="true" id="area_str" name="area_str" placeholder="Enter Area" value="{{ old('area') }}">
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="type" required="true" data-placeholder="Choose Type" style="font-size:13px;width:100%;">
                                        <option></option>
                                        @if(count($type)>0)
                                        @foreach($type as $typ)
                                        <option value=" {{$typ->name}}"> {{$typ->name}}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" for="title" style="font-size:13px;">Title<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size:13px;" id="title" name="title" placeholder="Enter title.." required="true">
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="observation">Observation<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="observation" name="observation" placeholder="Enter observation" required="true"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 d-none" id="correction_div">
                                <label class="col-sm-12 col-form-label" for="correction" style="font-size:13px;">Correction <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="3" class="form-control" id="correction" name="correction" style="font-size:13px;" placeholder="Enter correction"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 d-none" id="rootcause_div">
                                <label class="col-sm-12 col-form-label" for="rootcause" style="font-size:13px;">Root Cause<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="3" class="form-control" id="rootcause" placeholder="Enter root cuase" name="rootcause" style="font-size:13px;"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 d-none" id="correctiveaction_div">
                                <label class="col-sm-12 col-form-label" for="correctiveaction" style="font-size:13px;">Corrective Action</label>
                                <div class="col-sm-12">
                                    <textarea rows="3" class="form-control" id="correctiveaction" name="correctiveaction" style="font-size:13px;" placeholder="Enter corrective action"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="targetdate" style="font-size:13px;">Target Date <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" style="font-size:13px;" id="targetdate" name="targetdate" required="true" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="responsibility">Responsibility <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="responsibility" name="responsibility" data-placeholder="Choose Responsibility" required="true" style="font-size:13px;width:100%;">
                                        <option></option>
                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value=" {{$use->email}}"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="issue_file">Attach issue related files (avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" style="font-size:13px;" id="issue_file" name="issue_file[]" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 d-none" id="agreedaction_div">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="agreedaction">Recommended action</label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size:13px;" id="agreedaction" name="agreedaction" placeholder="Enter recommended action"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for action:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="frequency" name="frequency[]" style="font-size:13px;" multiple data-placeholder="Choose Frequency">
                                        <option></option>
                                        <option value=" Monthly"> Monthly</option><br>
                                        <option value=" Weekly"> Weekly</option><br>
                                        <option value=" 2 Days Before"> 2 Days Before</option><br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="notification" style="font-size:13px;">Send notifications to:</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="example-select2-multiple" style="font-size:13px;" name="notification[]" multiple data-placeholder="Choose Users">

                                        @if(count($user)> 0 )
                                        @foreach($user as $use)
                                        <option value=" {{$use->email}}" style="font-size:13px;"> {{ $use->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="reviewauthority" style="font-size:13px;">Action Review Authority<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="reviewauthority" name="reviewauthority[]" style="font-size:13px;" multiple data-placeholder="Choose Action Review Authority" required>
                                        @if(count($issuereviewers)> 0 )
                                        @foreach($issuereviewers as $reviewers)
                                        <option value=" {{$reviewers->email}}" style="font-size:13px;"> {{ $reviewers->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="closureauthority" style="font-size:13px;">Closure Review Authority<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" id="closureauthority" style="font-size:13px;" name="closureauthority[]" multiple data-placeholder="Choose Closure Review Authority" required>
                                        @if(count($issuereviewers)> 0 )
                                        @foreach($issuereviewers as $reviewers)
                                        <option value=" {{$reviewers->email}}" style="font-size:13px;"> {{ $reviewers->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-group col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary" onclick="return ddldd()" style="font-size:13px; ">Add</button>
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
<!-- END Apps Modal -->
<script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
<script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
<script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    jQuery(function() {
        One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
    });
</script>
<script>
    let route = "{{ route('nearmiss.ajax_get_info')}}";
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function ddldd() {
        if ($("#issue_file").val() == "") {
            mess2 = "Are you sure to submit without attachment?";
            y = confirm(mess2);
            if (y == true) {
            } else {
                return false;
            }
        } else {
            mess1 = "Are you sure?";
            x = confirm(mess1);
            if (x == true) {
            } else {
                return false;
            }
        }
    }
    function isBelong(value) {
        if (value == 2) {
            $('#department').val(null).trigger('change');
            $("#correction_div").addClass('d-none');
            $("#rootcause_div").addClass('d-none');
            $("#correctiveaction_div").addClass('d-none');
            $("#agreedaction_div").removeClass('d-none');
            $("#correction").val('').prop('required', false);
            $("#rootcause").val('').prop('required', false);
            $("#correctiveaction").val('');
        } else {
            $('#department').val('{{ $currentUser->department ? $currentUser->department->id : $currentUser->deptid }}').trigger('change');
            $("#correction_div").removeClass('d-none');
            $("#rootcause_div").removeClass('d-none');
            $("#correctiveaction_div").removeClass('d-none');
            $("#agreedaction_div").addClass('d-none');
            $("#correction").prop('required', true);
            $("#rootcause").prop('required', true);
            $("#agreedaction").val('');
        }
    }
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
