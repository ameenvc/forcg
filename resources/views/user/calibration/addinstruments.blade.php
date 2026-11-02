@extends('layouts.multiselect')
@section('content')
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
<div class="content">
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <!-- Header starts -->
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/user/calibrationmgmt" class="text-black">
                        Calibration Management
                    </a>
                </li>
                <li class="breadcrumb-item font-w700" aria-current="page">
                    Add Instrument
                </li>
            </ol>
        </nav>
        <!-- Ends Header -->

        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 pr-5 pl-5">
                    @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class='alert alert-warning'>
                            {{ $error }}
                        </div>
                        @endforeach
                    @endif
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('message')!!}
                        </div>
                    @endif
                    <form class="mb-5" action="{{route('calibration.instruments.store')}}" enctype="multipart/form-data" method="POST" files="true">
                        {{csrf_field()}}
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="radio" value="external" id="typeext" name="type" checked onclick="typedisplay()">
                                    <label class="col-form-label">&nbsp;&nbsp;External</label>&nbsp;&nbsp;
                                <input type="radio" value="internal" id="typeint" name="type" onclick="typedisplay()">
                                    <label class="col-form-label">&nbsp;&nbsp;Internal</label>
                            </div>
                        </div>
                        <!-- External form starts -->
                        <div id="external_form">
                            <div class="row sortable-field-external">
                                @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label"  style="font-size: 13px;">{{$calibrationFieldRules['Instrument_no']['field_display_label']}} @if ($calibrationFieldRules['Instrument_no']['field_is_mandatory'])<span style="color:red">*</span>@endif</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter {{$calibrationFieldRules['Instrument_no']['field_display_label']}}"
                                                id="instno_ext" name="instno_ext" value="{{ old('instno_ext') }}">
                                            <span class="text-danger Instrument_no_invalid" style="font-size: 12px; margin-top: 5px"></span>
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Instru_Manufacturer']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label"  style="font-size: 13px;">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}} @if ($calibrationFieldRules['Instru_Manufacturer']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" type="text" name="instmanu_ext" style="font-size: 13px;" id="instmanu_ext"
                                            placeholder="Enter {{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}" value="{{ old('instmanu_ext') }}">
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Instrument_desc']['enable_field'])
                                    <div class="form-group col-sm-12" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}} @if ($calibrationFieldRules['Instrument_desc']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <textarea type="text" class="form-control" id="instdesc_ext" style="font-size: 13px;" name="instdesc_ext"
                                            placeholder="Enter {{$calibrationFieldRules['Instrument_desc']['field_display_label']}}" maxlength="125">{{ old('instdesc_ext') }}</textarea>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Department <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="department_ext" name="department_ext" style="font-size: 13px;width:100%;" >
                                            <option value="" style="font-size: 13px;">Choose a Department</option>
                                            @if(count($departments)> 0 )
                                            @foreach($departments as $value)
                                            <option value="{{$value->id}}" {{ intval(old('department_ext')) === $value->id ? 'selected' : '' }}> {{ $value->departmentname }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if ($calibrationFieldRules['Holder']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">{{$calibrationFieldRules['Holder']['field_display_label']}} @if ($calibrationFieldRules['Holder']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="insttholder_ext" name="insttholder_ext" style="font-size: 13px;"
                                            placeholder="Enter {{$calibrationFieldRules['Holder']['field_display_label']}}" value="{{ old('insttholder_ext') }}">
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Capacity']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label"  style="font-size: 13px;">{{$calibrationFieldRules['Capacity']['field_display_label']}} @if ($calibrationFieldRules['Capacity']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" type="text" id="instcapacity_ext" placeholder="Enter {{$calibrationFieldRules['Capacity']['field_display_label']}}"
                                            name="instcapacity_ext"  style="font-size: 13px;" value="{{ old('instcapacity_ext') }}">
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Agency']['enable_field'])
                                    <div class="form-group col-sm-6" id="agen" name="agen" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label"  style="font-size: 13px;">{{$calibrationFieldRules['Agency']['field_display_label']}} @if ($calibrationFieldRules['Agency']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size: 13px;" id="instagency_ext"
                                            placeholder="Enter {{$calibrationFieldRules['Agency']['field_display_label']}}" name="instagency_ext" value="{{ old('instagency_ext') }}">
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Frequency <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="instfrequency_ext" name="instfrequency_ext" style="width:100%;font-size: 13px;" >
                                            <option value="" style="font-size: 13px;">Choose frequency</option>
                                            @if(count($freq)> 0 )
                                            @foreach($freq as $value)
                                            <option value="{{$value->frequency}}" {{ old('instfrequency_ext') === $value->frequency ? 'selected' : '' }}> {{ $value->frequency}}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" id="externalfiles" name="externalfiles" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" for="externalfiles" style="font-size: 13px;">Attach files(avoid '#' and '&' in file names)</label>
                                    <div class="col-sm-12">
                                        <input type="file" style="font-size: 13px;" class="form-control" name="externalfiles[]" multiple>
                                    </div>
                                </div>
                                @if ($calibrationFieldRules['Calib_procedure']['enable_field'])
                                    <div class="form-group col-sm-12" data-index="{{array_shift($externalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" for="procedure_ext" style="font-size: 13px;">{{$calibrationFieldRules['Calib_procedure']['field_display_label']}} @if ($calibrationFieldRules['Calib_procedure']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                                <textarea type="text" class="form-control" id="procedure_ext"  style="font-size: 13px;" name="procedure_ext"
                                                            placeholder="Enter {{$calibrationFieldRules['Calib_procedure']['field_display_label']}}">{{ old('procedure_ext') }}</textarea>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" for="standard_edition_ext" style="font-size: 13px;">Standard Edition</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="standard_edition_ext" id="standard_edition_ext" class="form-control" style="font-size: 13px;"
                                                placeholder="Enter Standard Edition" value="{{ old('standard_edition_ext') }}">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" for="acceptance_criteria_ext" style="font-size: 13px;">Acceptance Criteria</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="acceptance_criteria_ext" id="acceptance_criteria_ext"
                                                style="font-size: 13px;" placeholder="Enter Acceptance Criteria" value="{{ old('acceptance_criteria_ext') }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Least Count</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="least_count_ext" name="least_count_ext" style="font-size: 13px;"
                                        placeholder="Enter Least count" value="{{ old('least_count_ext') }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Date of First Use</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" id="date_of_first_use_ext" name="date_of_first_use_ext" style="font-size: 13px;"
                                        placeholder="dd/mm/yyyy" value="{{ old('date_of_first_use_ext') }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" data-index="{{array_shift($externalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="instrument_calibrated_external">
                                    Is this instrument Calibrated <span style="color:red">*</span>
                                    <input type="radio" class="ml-2 mr-2" value="yes" id="yes2" onclick="display2()" name="calibrated_ext" checked><label style="font-size: 13px;">Yes</label>
                                    <input type="radio" class="ml-2 mr-2" value="no" id="no2" onclick="display2()" name="calibrated_ext"><label style="font-size: 13px;">No</label>
                                    </label>
                                     <div class="col-sm-12 p-0" id="yescalibrated">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">Date of Calibration <span style="color:red">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" id="dateofcalibration_ext"
                                                    style="font-size: 13px;" name="dateofcalibration_ext" placeholder="Enter date of calibration" >
                                        </div>
                                    </div>
                                </div>

                                {!!$externalCustomFields!!}
                            </div>

                        </div>
                        <!-- Ends External form -->
                        <!-- Internal form starts -->
                        <div id="internal_form" style="display:none;">
                            <div class="row sortable-field-internal">
                                @if ($calibrationFieldRules['Instrument_no']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">{{$calibrationFieldRules['Instrument_no']['field_display_label']}} @if ($calibrationFieldRules['Instrument_no']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size: 13px;" placeholder="Enter {{$calibrationFieldRules['Instrument_no']['field_display_label']}}"
                                            id="instno_int" name="instno_int" value="{{ old('instno_int') }}">
                                            <span class="text-danger Instrument_no_invalid" style="font-size: 12px; margin-top: 5px"></span>
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Instru_Manufacturer']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label"  style="font-size: 13px;">{{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}} @if ($calibrationFieldRules['Instru_Manufacturer']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input class="form-control " type="text" name="instmanu_int" style="font-size: 13px;"
                                            id="instmanu_int" placeholder="Enter {{$calibrationFieldRules['Instru_Manufacturer']['field_display_label']}}" value="{{ old('instmanu_int') }}">
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Instrument_desc']['enable_field'])
                                    <div class="form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">{{$calibrationFieldRules['Instrument_desc']['field_display_label']}} @if ($calibrationFieldRules['Instrument_desc']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <textarea type="text" class="form-control " id="instdesc_int" style="font-size: 13px;" name="instdesc_int"
                                            placeholder="Enter {{$calibrationFieldRules['Instrument_desc']['field_display_label']}}" maxlength="125">{{ old('instdesc_int') }}</textarea>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Department <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control " id="department_int" name="department_int" style="font-size: 13px;width:100%;" >
                                            <option value="" style="font-size: 13px;">Choose a Department</option>
                                            @if(count($departments)> 0 )
                                            @foreach($departments as $value)
                                            <option value=" {{$value->id}}" {{ intval(old('department_int')) === $value->id ? 'selected' : '' }}> {{ $value->departmentname }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if ($calibrationFieldRules['Holder']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">{{$calibrationFieldRules['Holder']['field_display_label']}} @if ($calibrationFieldRules['Holder']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control " id="insttholder_int" name="insttholder_int"
                                            style="font-size: 13px;"  placeholder="Enter {{$calibrationFieldRules['Holder']['field_display_label']}}" value="{{ old('insttholder_int') }}">
                                        </div>
                                    </div>
                                @endif
                                @if ($calibrationFieldRules['Capacity']['enable_field'])
                                    <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label"  style="font-size: 13px;">{{$calibrationFieldRules['Capacity']['field_display_label']}} @if ($calibrationFieldRules['Capacity']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <input class="form-control " type="text" id="instcapacity_int" placeholder="Enter {{$calibrationFieldRules['Capacity']['field_display_label']}}"
                                            name="instcapacity_int"  style="font-size: 13px;" value="{{ old('instcapacity_int') }}">
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Frequency <span style="color:red">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control " id="instfrequency_int" name="instfrequency_int" style="width:100%;font-size: 13px;" >
                                            <option value="" style="font-size: 13px;">Choose frequency</option>
                                            @if(count($freq)> 0 )
                                            @foreach($freq as $value)
                                            <option value=" {{$value->frequency}}" {{ old('instfrequency_int') === $value->frequency ? 'selected' : '' }}> {{ $value->frequency}}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @if ($calibrationFieldRules['Calib_procedure']['enable_field'])
                                    <div class="form-group col-sm-12" data-index="{{array_shift($internalFieldIndex)}}">
                                        <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">{{$calibrationFieldRules['Calib_procedure']['field_display_label']}} @if ($calibrationFieldRules['Calib_procedure']['field_is_mandatory']) <span style="color:red">*</span> @endif</label>
                                        <div class="col-sm-12">
                                            <textarea type="text" class="form-control" id="procedure_int"  style="font-size: 13px;" name="procedure_int"
                                            placeholder="Enter {{$calibrationFieldRules['Calib_procedure']['field_display_label']}}">{{ old('procedure_int') }}</textarea>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" for="standardedition" style="font-size: 13px;">Standard Edition</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="standard_edition_int" id="standard_edition_int" class="form-control" style="font-size: 13px;"
                                        placeholder="Enter Standard Edition" value="{{ old('standard_edition_int') }}">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" for="acceptancecriteria" style="font-size: 13px;">Acceptance Criteria</label>
                                    <div class="col-sm-12">
                                        <input class="form-control" type="text" name="acceptance_criteria_int" id="acceptance_criteria_int"
                                        style="font-size: 13px;" placeholder="Enter Acceptance Criteria" value="{{ old('acceptance_criteria_int') }}">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Least Count</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="least_count_int" name="least_count_int" style="font-size: 13px;"
                                        placeholder="Enter Least count" value="{{ old('least_count_int') }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Date of First Use</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" id="date_of_first_use_int" name="date_of_first_use_int" style="font-size: 13px;"
                                        placeholder="dd/mm/yyyy" value="{{ old('date_of_first_use_int') }}">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6" data-index="{{array_shift($internalFieldIndex)}}">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="instrument_calibrated_internal">
                                    Is this instrument Calibrated <span style="color:red">*</span>
                                    <input type="radio" class="ml-2 mr-2" value="yes" id="yesint" onclick="display3()" name="calibrated_int" checked><label style="font-size: 13px;">Yes</label>
                                    <input type="radio" class="ml-2 mr-2" value="no" id="noint" onclick="display3()" name="calibrated_int"><label style="font-size: 13px;">No</label>
                                    </label>
                                    <div class="col-sm-12 p-0" id="yescalibratedint">
                                        <label class="col-sm-12 col-form-label" style="font-size: 13px;">Date of Calibration <span style="color:red">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" id="dateofcalibration_int" style="font-size: 13px;" name="dateofcalibration_int"
                                            placeholder="Enter date of Calibration">
                                        </div>
                                    </div>
                                </div>
                                {!!$internalCustomFields!!}
                            </div>
                        </div>
                        <!-- Ends Internal form -->
                        <div class="row">
                            <div class="form-group col-sm-12 text-right mr-2">
                                <input type="hidden" name="calibration_field_rules" value='@json($calibrationFieldRules)' />
                                <button id="ext_store" type="submit" class="btn btn-primary"  style="font-size:13px;" >Submit</button>
                                <button id="int_store" type="submit" class="btn btn-primary d-none"  style="font-size:13px;" >Submit</button>
                                <button for="reset" type="reset" class="btn btn-white text-primary border border-primary" style="font-size:13px;">Cancel</button>
                            </div>
                        <div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let int_store_selector = document.getElementById("int_store");
    let ext_store_selector = document.getElementById("ext_store");
    var ext_custom_fields = {!!$exCustomFields!!};
    var int_custom_fields = {!!$inCustomFields!!};

    function typedisplay() {
        if (document.getElementById('typeext').checked) {
            document.getElementById('external_form').style.display = "";
            document.getElementById('internal_form').style.display = "none";
            ext_store_selector.classList.remove('d-none');
            int_store_selector.classList.add('d-none');
            $('#instno_ext').trigger("change");

            // Remove required from all internal fields
            for (const field in int_custom_fields) {
                document.querySelectorAll(`[id="${field}"]`).forEach(el => {
                    el.removeAttribute('required');
                });
                document.querySelectorAll(`[name="${field}"]`).forEach(el => {
                    if (el.type === "radio") {
                        el.removeAttribute('required');
                    }
                });
            }

            // Add required to external fields if mandatory === 1
            for (const field in ext_custom_fields) {
                document.querySelectorAll(`[id="${field}"]`).forEach(el => {
                    if (ext_custom_fields[field] === 1) {
                        el.setAttribute('required', 'required');
                    } else {
                        el.removeAttribute('required');
                    }
                });
            }

        } else if (document.getElementById('typeint').checked) {
            document.getElementById('internal_form').style.display = "";
            document.getElementById('external_form').style.display = "none";
            ext_store_selector.classList.add('d-none');
            int_store_selector.classList.remove('d-none');
            $('#instno_int').trigger("change");
            console.log(int_custom_fields);
            console.log(ext_custom_fields);

            // Remove required from all external fields
            for (const field in ext_custom_fields) {
                document.querySelectorAll(`[id="${field}"]`).forEach(el => {
                    el.removeAttribute('required');
                });
                document.querySelectorAll(`[name="${field}"]`).forEach(el => {
                    if (el.type === "radio") {
                        el.removeAttribute('required');
                    }
                });
            }

            // Add required to internal fields if mandatory === 1
            for (const field in int_custom_fields) {
                document.querySelectorAll(`[id="${field}"]`).forEach(el => {
                    if (int_custom_fields[field] === 1) {
                        el.setAttribute('required', 'required');
                    } else {
                        el.removeAttribute('required');
                    }
                });
            }
            var savedOrder = @json($indefaultFieldIndex);
            var $container = $('.sortable-field-internal');
            var $allFields = $container.children('.form-group');
            // Map of index to element
            var fieldMap = {};
            $allFields.each(function () {
                var index = $(this).data('index');
                if (index !== undefined) {
                    fieldMap[index] = $(this);
                }
            });
            // Remove all fields from container
            $allFields.detach();
            // Append elements in saved order
            if (Array.isArray(savedOrder)) {
                savedOrder.forEach(function (index) {
                    if (fieldMap[index]) {
                        $container.append(fieldMap[index]);
                        delete fieldMap[index];
                    }
                });
            }
            // Append remaining fields (not in savedOrder)
            $.each(fieldMap, function (index, $el) {
                $container.append($el);
            });
        }
    }
    typedisplay();
    function display2() {
        if (document.getElementById('yes2').checked) {
            document.getElementById('yescalibrated').style.display = "";
            document.getElementById('dateofcalibration_ext').required = true;
        } else if (document.getElementById('no2').checked) {
            document.getElementById('yescalibrated').style.display = "none";
            document.getElementById('dateofcalibration_ext').required = false;
        }
    }

    function display3() {
        if (document.getElementById('yesint').checked) {
            document.getElementById('yescalibratedint').style.display = "";
            document.getElementById('dateofcalibration_int').required = true;
        } else if (document.getElementById('noint').checked) {
            document.getElementById('yescalibratedint').style.display = "none";
            document.getElementById('dateofcalibration_int').required = false;
        }
    }
    let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }
    $(function () {
        $('body').on('input change', '#instno_ext, #instno_int', function() {
            const ele = $(this);
            const invalidEle = $('.Instrument_no_invalid');
            $.ajax({
                url: "{{ route('calibration.instruments.checkinstrumentno') }}"
                , type: "POST"
                , data: {
                    _token: CSRF_TOKEN
                    , instrumentno: $(this).val()
                }
                , success: function(response) {
                    if (response.success == false) {
                        invalidEle.css('display', 'block');
                        invalidEle.html(response.message);
                        ele.addClass('is-invalid');
                    } else {
                        invalidEle.css('display', 'none');
                        ele.removeClass('is-invalid');
                    }
                } , error: function(response) {
                    invalidEle.css('display', 'none');
                    ele.removeClass('is-invalid');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        var exsavedOrder = @json($exdefaultFieldIndex);
        var $excontainer = $('.sortable-field-external');
        var $exallFields = $excontainer.children('.form-group');
        // Map of index to element
        var exfieldMap = {};
        $exallFields.each(function () {
            var index = $(this).data('index');
            if (index !== undefined) {
                exfieldMap[index] = $(this);
            }
        });
        // Remove all fields from container
        $exallFields.detach();
        // Append elements in saved order
        if (Array.isArray(exsavedOrder)) {
            exsavedOrder.forEach(function (index) {
                if (exfieldMap[index]) {
                    $excontainer.append(exfieldMap[index]);
                    delete exfieldMap[index];
                }
            });
        }
        // Append remaining fields (not in savedOrder)
        $.each(exfieldMap, function (index, $el) {
            $excontainer.append($el);
        });
    });
</script>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/calibrationmgmt">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                         Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->

@endsection
