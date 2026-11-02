@extends('layouts.multiselect')
@section('content')
<style>
    .paragraph-style {
        font-weight: bold;
        font-size: 20px;
        color: #191e3b;
        margin-top: 30px;
        margin-left: -1px;
    }

</style>

<!-- Hero -->
<!-- <div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <p class="paragraph-style">Audit Schedule Form</p>
        </div>
    </div>
</div> -->
<div class="bg-body-light">
         <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Audit Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Add Audit</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('user.auditschedule') }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
<!-- END Hero -->

@foreach($mrs as $mr)

@if(Auth::user()->email==$mr)
<!-- Page Content -->
<div class="content">

    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">

        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-10">
                    <form class="mb-5" action="{{url('/user/internalaudit/submit')}}" method="post" enctype="multipart/form-data">
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


                        <!-- <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department<span class="text-danger">*</span></label>
                            <div class="col-lg-12 col-form-label">
                                <div class="form-group" style="color:#666666">
                                    <select class="form-control" style="font-size: 13px;" onchange="selectdept(this.value,'{{$departments}}')" id="department" name="department" required="true" style="font-size: 13px;" data-placeholder="Choose a Department">
                                        <option value="0">Choose a Department</option>
                                        @if(count($departments)> 0 )
                                        @foreach($departments as $value)
                                        <option value="{{$value->id}}"> {{ $value->departmentname }}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="department" style="font-size: 13px;">Department<span class="text-danger">*</span></label>
                            <div class="col-lg-12 col-form-label">
                                <div class="form-group" style="color:#666666">
                                    <select class="js-select2 form-control" style="font-size: 13px;" onchange="selectdept(this.value)" id="department" name="department" required="true" style="font-size: 13px;" data-placeholder="Choose a Department">
                                        <option value="">Choose a Department</option>
                                        @if(count($departments)> 0 )
                                        @foreach($departments as $value)
                                        <option value="{{$value->id}}"> {{ $value->departmentname }}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;" id="division_id_frm">
                            <label class="col-sm-12 col-form-label" for="division" style="font-size: 13px;">Division</label>
                            <div class="col-lg-12 col-form-label">
                                <div class="form-group" style="color:#666666">
                                    <select class="js-select2 form-control" style="font-size: 13px;" onchange="selectdivision(this)" 
                                          id="division" name="division[]"  style="font-size: 13px;" multiple>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;"  id="area_id_frm">
                            <label class="col-sm-12 col-form-label" for="area" style="font-size: 13px;">Subdivision</label>
                            <div class="col-lg-12 col-form-label">
                                <div class="form-group" style="color:#666666">
                                    <select class="js-select2 form-control" style="font-size: 13px;" onchange="selectareachecklist(this)" 
                                        id="area" name="area[]"  style="font-size: 13px;" multiple>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="contact" style="font-size: 13px;">Auditee<span class="text-danger">*</span></label>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <select class="js-select2 form-control contact" style="font-size:13px;" required="true" id="contact" name="contact[]" placeholder="Choose Auditee" multiple>
                                        @if(count($users)> 0 )
                                        @foreach($users as $value)
                                        <option value="{{$value->name}}" {{ (collect(old('contact'))->contains($value->name)) ? 'selected' : '' }}> {{ $value->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:98.5%;margin-top:-30px;">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;">Scope of audit</label>
                            <div class="col-sm-12">
                                <div class="form-check">
                                    @foreach($standards as $standard)
                                    <label class="form-check-label" style="font-size:13px;">
                                        <input class="form-check-input" type="checkbox" value="{{$standard->name}}" name="scope[]" @if(is_array(old('scope')) && in_array($standard->name, old('scope'))) checked @endif>{{$standard->name}}
                                    </label><br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="auditors" style="font-size: 13px;">Auditors<span class="text-danger">*</span></label>
                            <div class="col-lg-12 col-form-label">
                                <div class="form-group">
                                    <select class="js-select2 form-control" style="font-size:13px;" required="true" id="auditors" name="auditors[]" placeholder="Choose Auditors" multiple>
                                        @if(count($auditors)> 0 )
                                        @foreach($auditors as $value)
                                        <option value=" {{$value->auditorsname}}" {{ (collect(old('auditors'))->contains($value->auditorsname)) ? 'selected':'' }}> {{ $value->auditorsname }}</option></br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="quarter" style="font-size: 13px;">Quarter<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" id="quarter" name="quarter" style="font-size: 13px;" required="true" placeholder="Choose Quarter">
                                    <option value="Q1" {{ old('quarter') == 'Q1' ? 'selected' : ''}}>Q1</option><br>
                                    <option value="Q2" {{ old('quarter') == 'Q2' ? 'selected' : ''}}>Q2</option><br>
                                    <option value="Q3" {{ old('quarter') == 'Q3' ? 'selected' : ''}}>Q3</option><br>
                                    <option value="Q4" {{ old('quarter') == 'Q4' ? 'selected' : ''}}>Q4</option><br>
                                </select>
                            </div>
                        </div> -->

                        <div class="form-group" style="display:inline-block;width:49%;margin-top:-20px;vertical-align: middle">
                            <label class="col-sm-12 col-form-label" for="date" style="font-size: 13px;">From date<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="date" style="font-size: 13px;" class="js-datepicker form-control js-date-valid" required="true" id="date" name="date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{ old('date') }}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;margin-top:-20px;vertical-align: middle">
                            <label class="col-sm-12 col-form-label" for="enddate" style="font-size: 13px;">To date<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="date" style="font-size: 13px;" class="js-datepicker form-control js-date-valid" required="true" id="enddate" name="enddate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="{{ old('enddate') }}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="time" style="font-size: 13px;">Time<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="time" class="form-control" style="font-size: 13px;" name="time" id="time" required="true" value="{{ old('time') != null ? date('H:i', strtotime(old('time'))) : '00:00' }}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="budgeted" style="font-size: 13px;">Budgeted hours (Audit Duration)<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" style="font-size: 13px;" class="form-control" id="budgeted" required="true" name="budgeted" value="{{ old('budgeted') }}" placeholder="Enter Budgeted Hours">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.7%;">
                            <label class="col-sm-12 col-form-label" for="focus" style="font-size: 13px;">Focus/standard requirement/chapter/clause<span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <textarea type="text" style="font-size: 13px;" rows="5" class="form-control" id="focus" required="true" name="focus" placeholder="Enter">{{ old('focus') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:98.7%;">
                            <label class="col-sm-12 col-form-label" for="focus" style="font-size: 13px;">Select the checklist required for this audit</label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-control" style="font-size:13px;"  id="checklist" name="checklist[]" placeholder="Choose Checklist" multiple>
                                   
                                </select>
                            </div>
                        </div>

                        <div>
                            <button type="submit" for="submit" class="btn btn-primary js-btn-submit" style="font-size:13px; margin-left:86.8%;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                        </div>

                    </form>

                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    </main>

    </body>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sel').select2({
                placeholder: 'Choose Auditors '
            , });
        });

    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sel2').select2({
                placeholder: 'Choose Auditee '
            , });
        });

    </script>

    <script>
        let CSRF_TOKEN = "";
        window.onload = function() {
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function(){
                $('#division_id_frm').hide();
                $('#area_id_frm').hide();
            });
        }

        // function selectdept(value, details) {
        //     details = details.replace(/\r\n/g, '~');
        //     datas = JSON.parse(details);
        //     for (var key in datas) {
        //         let isoclauses = datas[key].isoclauses;
        //         if (isoclauses) {
        //             isoclauses = isoclauses.replace(/~/g, "\n");
        //             if (datas[key].id == value) {
        //                 document.getElementById('focus').value = isoclauses;
        //                 $.each($(".contact"), function() {
        //                     $(this).select2().val(datas[key].hodname).trigger("change");
        //                 });
        //             }
        //         }

        //     }
        // }

        function selectdept(value) {
           $('#division_id_frm').hide();
           $('#area_id_frm').hide();
                $.ajax({
                    data: {
                            "department_id_value": value,
                            _token: CSRF_TOKEN
                        }
                    , url: "{{ route('user.selectdeptdivisiondetails')}}"
                    , type: "POST"
                    , success:function(response) {
                        if(response.status == 200)
                         {
                            var divisions = response.divisions;
                            var isoclauses = response.isoclauses;
                            var hodname = response.hodname;
                            var checklist = response.checklists;
                            if(isoclauses){
                                isoclauses = isoclauses.replace(/~/g, "\n");
                                document.getElementById('focus').value = isoclauses;
                            }else{
                                $('#focus').val("");
                            }
                            $.each($(".contact"), function() {
                                $(this).select2().val(hodname).trigger("change");
                            });

                             if(divisions.length>0){
                                $('#division_id_frm').show();
                                var selectElement = $('#division');
                                selectElement.empty(); 
                                //selectElement.append('<option value="">Choose a Division</option></br>');
                                $.each(response.divisions, function (index, option) {
                                    var optionElement = $('<option>', {
                                        value: option.id,
                                        text: option.name
                                    });

                                    selectElement.append(optionElement);
                                });
                             }
                             else{
                                $('#division_id_frm').hide();
                             }

                             if(checklist.length>0){
                                var selectElement = $('#checklist');
                                selectElement.empty(); 
                                //selectElement.append('<option value="">Choose Checklist</option></br>');
                                $.each(response.checklists, function (index, option) {
                                    var optionElement = $('<option>', {
                                        value: option.id,
                                        text: option.checklist_name
                                    });
                                    selectElement.append(optionElement);
                                });
                             }
                             else{
                                var selectElement = $('#checklist');
                                selectElement.empty(); 
                                //selectElement.append('<option>No checklist created</option></br>');
                                selectElement.append('<option disabled>No checklists available</option>'); 
                             }
                         }
                    }
                    ,error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
            });
        }

        function selectdivision(selectElement)
        {
            $('#checklist').empty(); 
            const selecteddivValues = Array.from(selectElement.selectedOptions).map(option => option.value);
            $.ajax({
                    data: {
                            "division_id_value": selecteddivValues,
                            _token: CSRF_TOKEN
                        }
                , url: "{{ route('user.selectdivisionareadetails')}}"
                , type: "POST"
                , success:function(response) {
                    console.log(response.area)
                    if(response.status == 200)
                    {
                        if(response.area.length>0){
                            $('#area_id_frm').show();
                            var selectElement = $('#area');
                            selectElement.empty();
                            //selectElement.append('<option value="">Choose a SubDivision</option></br>');
                            $.each(response.area, function (index, option) {
                                var optionElement = $('<option>', {
                                    value: option.id,
                                    text: option.name
                                });

                                selectElement.append(optionElement);
                            });
                        }
                        else{
                            $('#area_id_frm').hide();
                        }

                        if(response.checklists.length>0){
                            var selectElement = $('#checklist');
                            selectElement.empty(); 
                            //selectElement.append('<option value="">Choose Checklist</option></br>');
                            $.each(response.checklists, function (index, option) {
                                var optionElement = $('<option>', {
                                    value: option.id,
                                    text: option.checklist_name
                                });
                                selectElement.append(optionElement);
                            });
                        }
                        else{
                            var selectElement = $('#checklist');
                            selectElement.empty(); 
                            //selectElement.append('<option>No checklist created</option></br>');
                            selectElement.append('<option disabled>No checklists available</option>');
                        }
                    }
                }
            });
        }

        function selectareachecklist(selectareaElement)
        {
            $('#checklist').empty(); 
            const selectedareaValues = Array.from(selectareaElement.selectedOptions).map(option => option.value);
            $.ajax({
                data: {
                        "area_id_value": selectedareaValues,
                        _token: CSRF_TOKEN
                    }
                , url: "{{ route('user.selectareachecklistdetails')}}"
                , type: "POST"
                , success:function(response) {
                    if(response.status == 200)
                    {
                        if(response.checklists.length>0){
                            var selectElement = $('#checklist');
                            selectElement.empty(); 
                            //selectElement.append('<option value="">Choose Checklist</option></br>');
                            $.each(response.checklists, function (index, option) {
                                var optionElement = $('<option>', {
                                    value: option.id,
                                    text: option.checklist_name
                                });
                                selectElement.append(optionElement);
                            });
                        }
                        else{
                            var selectElement = $('#checklist');
                            selectElement.empty(); 
                            //selectElement.append('<option>No checklist created</option></br>');
                            selectElement.append('<option disabled>No checklists available</option>');
                        }
                    }
                }
            });
        } 
        
        $('body').on('change', '.js-date-valid', function () {
            $(this).parent('div').find('.text-danger').remove();
            $('.invalid-start-end').remove();
            const $date = new Date($(this).val());
            const $currentDate = new Date();
            const $id = this.id;
            let message = '';
            let isValid = true;

            if ($date.setHours(0, 0, 0, 0) < $currentDate.setHours(0, 0, 0, 0)) {
                if($id == 'enddate') {
                    message = 'The To date cannot be in the past. Please select valid future dates.'
                } else {
                    message = 'The From date cannot be in the past. Please select valid future dates.'
                }
                $(this).after(`<span class="text-danger invalid-with-past" style="font-size: 12px">${message}</span>`)
                isValid = false;
            } else {
                const $startDate = new Date($('#date').val());
                const $endDate = new Date($('#enddate').val());

                if ($startDate.setHours(0, 0, 0, 0) > $endDate.setHours(0, 0, 0, 0)) {
                    $(this).after(`<span class="text-danger invalid-start-end" style="font-size: 12px">The To date must be greater than or equal to the From date.</span>`)
                    isValid = false;
                }
            }

            if(!isValid) {
                $('.js-btn-submit').attr('disabled', true);
            } else {
                $('.js-btn-submit').attr('disabled', false);
            }
        })
    </script>

    </html>


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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                    <div class="block-content text-center">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Auditors List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                    <div class="block-content text-center">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Audit Log
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                    <div class="block-content text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Audits
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
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

    @endsection

