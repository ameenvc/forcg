@extends('layouts.multiselect')
@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Objectives</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Objectives</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ url('/user/objectives/folders') }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        @if(count($errors) >0 )
        @foreach($errors->all() as $error)
        <div class='alert alert-warning'>
        <a class="close" data-dismiss="alert">×</a>
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
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-9">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/objectives/submit')}}" enctype="multipart/form-data" onsubmit="return validation()" method="POST">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="form-group col-sm-12 objective-category-wrap">
                                    <label class="col-sm-12 col-form-label text-primary" style="font-size: 13px;" for="objectivecategory">Does the objective you are currently defining align with any of the categories listed below? <br>Based on your response, cgbod can offer suggestions tailored to your needs.</label>
                                    <div class="col-sm-12">
                                        @foreach(config('constants.objectives.category') as $key => $item)
                                            <div class="custom-control custom-radio  mr-3" style="display: inline-block; font-size: 12px">
                                                <input type="radio" class="custom-control-input objective-category" value="{{ $item['value'] }}" id="objectivecategory{{ $key }}" name="objectivecategory">
                                                <label class="custom-control-label labelClass label-risk-cat" id="labelobjectivecategory{{ $key }}" for="objectivecategory{{ $key }}">{{ $item['value'] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        {{-- @if($type == 'divisional' || $type == 'subdivisional')--}}
                                        <select class="form-control" id="department" name="department" style="font-size: 13px;width:100%;" placeholder="Choose a Department" onchange="chooseDivision($(this).val())">
                                            {{-- @elseif($type == null)--}}
                                            {{-- <select class="form-control" id="department" name="department" style="font-size: 13px;" placeholder="Choose a Department">--}}
                                            {{-- @endif--}}
                                            <option value="none" selected disabled hidden>
                                                Choose a Department
                                            </option>
                                            @if(count($departments)> 0)
                                            @foreach($departments as $value)
                                            <option value="{{$value->id}}" style="font-size: 13px;"> {{ $value->departmentname }}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objective_type">Objective
                                        type <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" id="objective_type" name="objective_type[]" style="font-size: 13px;width:100%;" data-placeholder="Choose an objective type" required multiple>
                                            @if(count($objective_types)> 0 )
                                                @foreach($objective_types as $item)
                                                    <option value="{{$item->id}}" style="font-size: 13px;"> {{ $item->name }}</option></br>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="objective name" style="font-size: 13px;">Objective name <span style="color:red">*</span></label>
                                    <div class="col-sm-12" style="padding-bottom:10px;"  id="getobjnamedetails">

                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="objectives" style="font-size: 13px;" name="objectives" required placeholder="Enter Objective">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- @if($type == 'divisional' || $type == 'subdivisional')--}}
                                <div class="form-group d-none col-sm-6" id="division">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="division_select">Division</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="division_select" name="division_select" style="width: 100%;font-size: 13px;" data-placeholder="Choose Division">
                                            <option value="0">Choose Division</option>
                                            <br>
                                        </select>
                                    </div>
                                </div>
                                {{--@endif--}}
                                {{-- @if($type == 'subdivisional')--}}
                                <div class="form-group d-none col-sm-6" id="area">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="area_select">Sub-Division</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="area_select" name="area_select" style="width: 100%;font-size: 13px;" data-placeholder="Choose Sub-Division">
                                            <option value="0">Choose Sub-Division</option>
                                            <br>
                                        </select>
                                    </div>
                                </div>
                                {{--@endif--}}
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskdri" style="font-size: 13px;">From<span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="Date" name="fromdate" style="font-size: 13px;" required value="{{ old('duedate') }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskdri">To <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="Date" style="font-size: 13px;" name="todate" required value="{{ old('duedate') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Objective
                                        description <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="description" style="font-size: 13px;" name="description" required placeholder="Enter Description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="baseline">Baseline</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control"
                                            id="baseline" style="font-size: 13px;"
                                            value="{{ old('baseline') }}"
                                            name="baseline" placeholder="Enter Baseline">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskname">Target <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" id="otarget" style="font-size: 13px;" step="any" required name="otarget" placeholder="Enter Target">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="unit">Unit</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="unit" style="font-size: 13px;width:100%;" placeholder="Choose Unit" id="unit">
                                            <option value="2">%</option>
                                            <option value="3">No sign</option>
                                            <option value="4">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="otherunit">
                                        <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;"></label>
                                        <div class="col-sm-12">
                                            <input type="text" id="otherunitid" class="form-control" name="otherunit" style="font-size: 13px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6" id="nature_div">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="nature">Objective
                                        Nature</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="nature" style="font-size: 13px;width:100%;" placeholder="Choose Nature" id="nature">
                                            <option value="0">Choose Target nature</option>
                                            <option value="increase">Increase</option>
                                            <option value="decrease">Decrease</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="hod">Program
                                        Span <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" style="font-size: 13px;width:100%;" name="span" id="span" required>
                                            <option value=""> Choose Program Span</option>
                                            <option value="Short">Short</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Long">Long</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">Target
                                        date <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="Date" name="tardate" required style="font-size: 13px;" value="{{ old('duedate') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Methodology <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="methodology" required style="font-size: 13px;" name="methodology" placeholder="Enter Methodology"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Resources <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" id="resources" required style="font-size: 13px;" name="resources" placeholder="Enter Resources"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Responsibility <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" id="responsibility" style="font-size: 13px;width:100%;" name="responsibility[]" data-placeholder="Choose Responsible Persons" required multiple>
                                            <option value="" disabled>Choose Responsibility</option>
                                            @if(count($users)> 0 )
                                            @foreach($users as $value)
                                            <option value="{{$value->email}}"> {{ $value->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="frequency" style="font-size: 13px;">Frequency
                                        of monitoring <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="frequency" id="frequency" style="font-size: 13px;width:100%;" required>
                                            <option value="" disabled selected>Choose Frequency</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Quarterly">Quarterly</option>
                                            <option value="Half yearly">Half yearly</option>
                                            <option value="Yearly">Yearly</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="reviewer" style="font-size: 13px;">Reviewer <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control selectm select_reviewer" name="reviewers[]" id="reviewers" style="font-size: 13px;width:100%;" multiple required data-placeholder="Choose Reviewers">
                                            @if(count($users)>0)
                                            @foreach($users as $value)
                                            @if(in_array($value->id,$objective_reviewers))
                                            <option value="{{$value->id}}"> {{ $value->name }}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="approver" style="font-size: 13px;">Approver <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="js-select2 form-control" id="approver" style="font-size: 13px;width:100%;" name="approver" required>
                                            <option value="" disabled selected>Choose Approver</option>
                                            @if(count($users)> 0 )
                                            @foreach($users as $value)
                                            @if(in_array($value->id,$objective_approvers))
                                            <option value="{{$value->id}}"> {{ $value->name }}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="file" class="col-sm-12 col-form-label" style="font-size:12px;">File</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" style="font-size:12px;" type="file" name="document[]" id="document" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right" style="">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                                    <button for="reset" type="reset" class="btn btn-primary" style="font-size:13px;">Reset</button>
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
                            <h3 class="block-title">OPTIONS</h3>
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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/objectives/listofobjectives/{{Session::get('objdept')}}/{{Session::get('year')}}">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                <br> Register
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/achieved">
                                        <div class="block-content text-center" style="height:130px">

                                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                <br> Achieved<br>
                                            </p>
                                        </div>
                                    </a>

                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/notachieved">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-times-circle fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Not Achieved
                                            </p>
                                        </div>
                                    </a>

                                </div>


                                <div class="col-6">
                                    <!-- Sales -->
                                    <br>
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/objectives/objreport" style="margin-top:-18px">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Objective report
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
        <!-- END Apps Modal -->
    </div>
    <!-- END Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("#otherunit").hide();

    });

</script>
<script>
    $("#unit").change(function() {
        if ($("#unit option:selected").val() == 2) {
            $('#otherunit').hide();
            $('#nature_div').css('width', '33%')
        } else if ($("#unit option:selected").val() == 3) {
            $('#otherunit').hide();
            $('#nature_div').css('width', '33%')
        } else {
            $('#risk_response').show();
            $('#otherunit').show();
            $('#nature_div').css('width', '49%')
        }
    });
    let category_name = undefined;
    let pre_btn_id = undefined;
    let btn_id = undefined;
    $('.objective-category').click(function () {
        if($(this).prop("checked")) {
            category_name = $(this).val();
            var idValue = $(this).attr('id');
            let labelId = '#label'+idValue;
            $('.labelClass').removeClass('text-primary');
            $(labelId).addClass('text-primary');
            getobjdetails($(this).val())
        }
    });
    function getobjdetails(category_name = ''){
        $.ajax({
            data:{
                "obj_category_name": category_name,
                _token: CSRF_TOKEN
            },
            url: "{{ route('objectives.displayobjectivegroupnameajax')}}",
            type: "POST",
            success:function(response) {
                if(response.status == 200){
                    var namedatalist = response.objnames;
                    var userhtmldatas='';
                    if(namedatalist.length>0){
                        var m = 0;
                        var items_per_row = 2;
                        for(let i=0;i<namedatalist.length;i++)
                            {
                            userhtmldatas += '<button type="button" class="btn btn-sm btn-outline-primary" id="btn-' + i + '" style="margin-right:4px;margin-bottom:10px;font-size:11px;" onclick="passvalue(\'' + namedatalist[i] + '\', ' + i + ');">' + namedatalist[i] + '</button>';
                            m++;
                            if(m % items_per_row == 0){ '<br>'}
                            }
                        }
                    else{
                        userhtmldatas+='<div class="col-12">&nbsp;</div>';
                    }
                    $('#getobjnamedetails').html(userhtmldatas);
                }
            }
        });
    }
    function passvalue(obj_name,index){
        $('#objectives').val(obj_name);
        // Add the 'active' class to the clicked button
        pre_btn_id = btn_id;
        $(pre_btn_id).removeClass('btn-primary');
        $(pre_btn_id).addClass('btn-outline-primary');
        btn_id = '#btn-'+index;
        $(btn_id).removeClass('btn-outline-primary');
        $(btn_id).addClass('btn-primary');
        $.ajax({
            data: {
                    "obj_name": obj_name,
                    'category_name': category_name,
                    _token: CSRF_TOKEN
                },
            url: "{{ route('objectives.displayobjectivegroupdetailsajax')}}",
            type: "POST",
            success:function(response) {
                if(response.status == 200){
                    let obj_desp  = response.obj_desp;
                    let obj_unit = response.obj_unit;
                    let obj_target = response.obj_target;
                    let obj_method = response.obj_method;
                    let obj_resource = response.obj_resource;
                    $('#description').val(obj_desp);
                    $('#otarget').val(obj_target);
                    $('#methodology').val(obj_method);
                    $('#resources').val(obj_resource);
                    if(obj_unit=='%'){
                        $('#unit').val('2');
                        $('#otherunit').hide();
                    }else if(obj_unit==''){
                        $('#unit').val('3');
                        $('#otherunit').hide();
                    }else{
                        $('#unit').val('4');
                        $('#otherunitid').val(obj_unit);
                        $('#otherunit').show();
                    }

                }
            }
        });
    }
</script>

<script>
    function validation() {


        if (document.getElementById('department').value == 'none') {
            alert('Please select a department');
            document.getElementById('department').focus();
            return false;
        } else if (document.getElementById('span').value == 'none') {
            alert('Please select a program span');
            document.getElementById('span').focus();
            return false;
        } else if (document.getElementById('responsibility').value == 'none') {
            alert('Please choose responsibility');
            document.getElementById('responsibility').focus();
            return false;
        } else if (document.getElementById('frequency').value == 'none') {
            alert('Please select a frequency of monitoring');
            document.getElementById('frequency').focus();
            return false;
        }

        // always return false to prevent form from submission.
    }

</script>

<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function chooseDivision(id) {
        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let area_url = '{{ route("admin.departmentAreas.getAreas", ":id") }}';
        area_url = area_url.replace(':id', id);

        let option_text = "";
        let area_option_text = "";

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
                    $('#division_select').empty().append(`<option value="0">Choose Division</option>` + option_text);
                } else {
                    $('#division').addClass('d-none');
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: area_url
            , success: function(resp) {
                if (resp.data.length) {
                    $('#area').removeClass('d-none');
                    $('#area').css('display', 'inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        area_option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#area_select').empty().append(`<option value="0">Choose Sub-Division</option>` + area_option_text);
                } else {
                    $('#area').addClass('d-none');
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });


    }

</script>

@endsection
