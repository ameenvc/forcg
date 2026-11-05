@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')



<style>
    .text-counter {
        font-size: 12px;
        float: right;
        margin-top: 2px;
        color: gray;
    }
    .border-red {
        border-color: red;
    }
    .text-sm {
        font-size: 13px;
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
</style>

    <div class="content risk-form-page">
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a class="link-fx" href="/user/riskmgmt" class="text-black">
                            Enterprise Risk
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Edit Risk
                    </li>
                </ol>
            </nav>

            <div class="block-content block-content-full">
                @include('user.risks.notification-warning')
            @if (Session::has('errmsg'))
                    <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert">×</a>
                        {!! Session::get('errmsg') !!}
                    </div>
                @endif
                @if (Session::has('msg'))
                    <div class="alert alert-success">
                        <a class="close" data-dismiss="alert">×</a>
                        {!! Session::get('msg') !!}
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" style="display:flex; justify-content:flex-end">
                        <div style="FONT-SIZE:8px; margin-right: 1rem;">MS.02.01</div>
                        <button type="button" class="btn btn-dark" style="font-size:12px;width: 86px;"
                            data-toggle="modal" data-target="#viewModal"><strong>Risk Info</strong> </button>
                        <div id="viewModal" class="risk-module-form modal fadebd-example-modal-lg" tabindex="-1" role="dialog"
                            aria-hidden="false">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    @if (count($riskinfo) > 0)
                                        @foreach ($riskinfo as $c)
                                            <img src="{{ url('storage/riskinfo/' . $c->infoname) }}" alt="No Risk Info">
                                        @endforeach
                                    @else
                                        <h3>No info</h3>
                                    @endif
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form class="risk-module-form mb-5" action="{{ url('/user/risks/usereditsubmit') }}" method="post" onsubmit="return validateChips()">
                            {{ csrf_field() }}
                            @if (count($data) > 0)
                                @foreach ($data as $d)
                                    <?php $idd = $d->Rid; ?>
                                    <div class="row col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm" for="posted">Risk ID</label>
                                            <div>
                                                <input type="text" class="form-control text-sm" id="posted" name="posted" value="{{ $d->grp_id }}" Readonly="">
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm" for="riskgroup">Risk Group<span class="text-danger">*</span></label>
                                            <div>
                                                <select required="true" class="form-control js-select2 text-sm" name="riskgroup" style="width:100%;">
                                                    @if (count($rskgrp) > 0)
                                                        <option value="">Choose Risk Group</option>
                                                        @foreach ($rskgrp as $value)
                                                            <option value=" {{ $value->riskgroups }}"
                                                                {{ $d->riskgroup == $value->riskgroups ? 'selected' : '' }}>
                                                                {{ $value->riskgroups }}
                                                            </option></br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm" for="riskarea">Risk Area<span class="text-danger">*</span></label>
                                            <div>
                                                <select class="form-control js-select2 text-sm {{ $d->division ? 'selectedRiskArea' : '' }}" name="riskarea" style="width:100%;" id="riskarea" data-division="{{ $d->division ? $d->division->id : '' }}" data-area="{{ $d->area ? $d->area->id : ''}}" required="true" onchange="chooseDivision($(this).val())">
                                                    {{-- <option value=" {{$d->riskarea }}" style="font-size:13px;"> {{$d->department ? $d->department->departmentname : '-' }}</option></br> --}}
                                                    @if (count($mn) > 0)
                                                        <option value="">Choose Risk Area</option>
                                                        @foreach ($mn as $dep)
                                                            {{-- @if ($d->department != $dep->departmentname) --}}
                                                            <option value="{{ $dep->id }}"
                                                                {{ $d->riskarea == $dep->id ? 'selected' : '' }}
                                                                style="font-size:13px;"> {{ $dep->departmentname }}</option>
                                                            </br>
                                                            {{-- @endif --}}
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group d-none col-lg-4 col-md-6 col-sm-12" id="division">
                                            <label class="col-form-label text-sm">Division</label>
                                            <div>
                                                <select class="form-control js-select2 text-sm {{ $d->area ? 'selectedDivision' : '' }}" id="division_select" name="division" required="true" style="width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                                    <option value="0">Choose Division</option></br>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group d-none col-lg-4 col-md-6 col-sm-12" id="area">
                                            <label class="col-form-label text-sm" >Sub Division</label>
                                            <div>
                                                <select class="form-control js-select2  text-sm" id="area_select" name="area" required="true" style="width:100%;" placeholder="Sub Division">
                                                    <option value="0">Choose Sub Division</option></br>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm" for="riskname">Risk Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control text-sm" required="true"
                                                    id="riskname" name="riskname" value="{{ $d->riskname }}">
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-form-label text-sm"
                                                for="riskdec">Risk Description<span class="text-danger">*</span></label>
                                            <div>
                                                <textarea required="true" type="text" data-max-character="65435" class="form-control js-counter text-sm"
                                                    maxlength="65435" rows="5" id="riskdec" name="riskdec">{{ old('riskdec', $d->riskdec) }}</textarea>
                                                <span class="text-counter">0/65435</span>
                                                @error('riskdec')
                                                    <div class="text-danger" style="font-size: 13px;">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-form-label text-sm"
                                                for="riskdri">Risk Drivers</label>
                                            <div>
                                                <textarea type="text" class="form-control js-counter text-sm" rows="5" data-max-character="65435"
                                                    maxlength="65435" id="riskdri" name="riskdri">{{ old('riskdri', $d->riskdri) }}</textarea>
                                                <span class="text-counter">0/65435</span>
                                                @error('riskdri')
                                                    <div class="text-danger text-sm">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="col-form-label text-sm" style="font-size: 13px;"
                                                for="existing_controls">Existing Controls</label>
                                            <div>
                                                <textarea rows="5" data-max-character="65435" class="form-control js-counter text-sm"
                                                    maxlength="65435" id="existing_controls" name="existing_controls" placeholder="Enter">{{ old('existing_controls', $d->existing_controls) }}</textarea>
                                                <span class="text-counter">0/65435</span>
                                                @error('existing_controls')
                                                    <div class="text-danger" style="font-size: 13px;">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm"
                                                for="riskscr">Risk Source</label>
                                            <div>
                                                <select class="form-control js-select2 text-sm" id="riskscr"
                                                    name="riskscr">
                                                    <option value="">Choose Risk Source</option>
                                                    <option value="Internal"
                                                        {{ $d->riskscr == 'Internal' ? 'selected' : '' }}>Internal</option>
                                                    <br>
                                                    <option value="External"
                                                        {{ $d->riskscr == 'External' ? 'selected' : '' }}>External</option>
                                                    <br>
                                                    <option value="Internal/External"
                                                        {{ $d->riskscr == 'Internal/External' ? 'selected' : '' }}>
                                                        Internal/External</option><br>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm"
                                                for="risk_response">Risk Response Strategy</label>
                                            <div>
                                                <select class="form-control js-select2" id="risk_response" name="risk_response"
                                                    style="font-size: 13px;">
                                                    <option value="">Choose Strategy</option>
                                                    @if (count($strategy) > 0)
                                                        @foreach ($strategy as $value)
                                                            <option value=" {{ $value->name }}"
                                                                {{ $d->risk_response == $value->name ? 'selected' : '' }}>
                                                                {{ $value->name }}</option><br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label text-sm" for="riskreviewer_id">Risk Reviewer<span class="text-danger">*</span></label>
                                            <div>
                                                <select class="form-control js-select2 text-sm" name="riskreviewer_id" required="true" style="width:100%;" placeholder="Select Reviewer">
                                                    <option value="">Choose Reviewer</option>
                                                    @if(count($riskreviewers)> 0 )
                                                        @foreach($riskreviewers as $riskreviewer)
                                                            <option value="{{$riskreviewer->user->id}}" {{ $d->riskreviewer_id == $riskreviewer->user->id ? 'selected' : '' }}>
                                                                {{ $riskreviewer->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('riskreviewer')
                                                    <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label" style="font-size:13px;" for="impact">Impact<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="hidden" name="impa" id="impact" value="">
                                                @if(count($hml)> 0 )
                                                    @foreach($hml as $value)
                                                        @php
                                                            $btn_class = '';
                                                            $clicked_class = '';
                                                        if($value->name == 'High'){
                                                                $btn_class = 'btn-outline-danger';
                                                            }elseif($value->name == 'Moderate'){
                                                                $btn_class = 'btn-outline-warning';
                                                            }elseif($value->name == 'Low'){
                                                                $btn_class = 'btn-outline-success';
                                                            }
                                                            $clicked_class = $d->impa == $value->name ? 'clickedimpact' : '';
                                                        @endphp
                                                        <input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} impactbtn" id="{{Str::slug($value->name.'i','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                                    @endforeach
                                                @endif
                                                <div id="impact-error" class="text-danger" style="display:none;font-size:12px;">Please select an impact.</div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                            <label class="col-form-label" style="font-size:13px;" for="likelihood">Likelihood<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="hidden" name="like" id="likelihood" value="">
                                                @if(count($hml)> 0 )
                                                @foreach($hml as $value)
                                                    @php
                                                        $btn_class = '';
                                                        $clicked_class = '';
                                                        if($value->name == 'High'){
                                                            $btn_class = 'btn-outline-danger';
                                                        }elseif($value->name == 'Moderate'){
                                                            $btn_class = 'btn-outline-warning';
                                                        }elseif($value->name == 'Low'){
                                                            $btn_class = 'btn-outline-success';
                                                        }
                                                        $clicked_class = $d->like == $value->name ? 'clickedlikelihood' : '';
                                                    @endphp
                                                    <input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} likelihoodbtn" id="{{Str::slug($value->name.'l','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                                @endforeach
                                                @endif
                                                <div id="likelihood-error" class="text-danger" style="display:none;font-size:12px;">Please select a likelihood.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ auth::user()->email }}" name="post">
                                    <input type="hidden" value="{{ $idd }}" name="riskid">
                                    <div class="col-lg-12">
                                        <div style="text-align:right;">
                                            <button type="submit" class="btn btn-primary" style="font-size:13px;">Submit</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="risk-module-form modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
        aria-hidden="true">
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

                                <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="/user/risks/closedrisks">
                                    <div class="block-content text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Risks
                                        </p>
                                    </div>
                                </a>

                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                    <div class="block-content text-center">
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


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        function chooseDivision(id) {
            $('#division_select').val(0); //whenever department changes division val is set to 0
            $('#area_select').val(0); // whenever department changes sub divisions value is set to 0

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
                        var divisionID = $('#riskarea').data('division'); //fetching division id from the data, which is use to set selection to selected

                        for (var i = 0; i < resp.data.length; i++) {
                            option_text += `<option value="${resp.data[i].id}"${resp.data[i].id == divisionID ? 'selected' : ''}>${resp.data[i].name}</option>`;
                        }
                        $('#division_select').empty().append('<option value="0">Choose Division</option></br>' + option_text);
                    }
                }
                , error: function(result) {
                    console.log(result);
                }
            });

        }
        $('.selectedRiskArea').first().trigger('change');


        function chooseArea(id) {
            $('#area_select').val(0); // whenever division changes sub divisions value is set to 0
            var newId;
            if(id != 0){
                newId = id;
            } else {
                newId = $('#riskarea').data('division'); // fetching division id from data, helpful during initial loading
            }
            $('#area').addClass('d-none');
            let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ":id") }}';
            the_url = the_url.replace(':id', newId);
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
                        var areaID = $('#riskarea').data('area'); // fetching sub division id from data, which is use to set selection to selected

                        for (var i = 0; i < resp.data.length; i++) {
                            option_text += `<option value="${resp.data[i].id}" ${resp.data[i].id == areaID ? 'selected' : ''}>${resp.data[i].name}</option>`;
                        }
                        $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                    }
                }
                , error: function(result) {
                    console.log(result);
                }
            });
        }
        $('.selectedDivision').first().trigger('change');

        function validateChips() {
            if (!$('#impact').val()) {
                $('#impact-error').show();
                return false;
            }
            if (!$('#likelihood').val()) {
                $('#likelihood-error').show();
                return false;
            }
            return true;
        }

        $('.impactbtn').click(function(){
            var dval = $(this).val();
            $('#impact').val(dval);
            $('#impact-error').hide();
            if(dval=="High"){
                $('#highi').addClass('btn-alt-danger').css('background-color','#f4cccc').css('border','none');
                $('#moderatei').removeClass('btn-alt-warning').css('background-color','').css('border','');
                $('#lowi').removeClass('btn-alt-success').css('background-color','').css('border','');
            }else if(dval=="Moderate"){
                $('#moderatei').addClass('btn-alt-warning').css('background-color','#fce5cd').css('border','none');
                $('#highi').removeClass('btn-alt-danger').css('background-color','').css('border','');
                $('#lowi').removeClass('btn-alt-success').css('background-color','').css('border','');
            }else if(dval=="Low"){
                $('#lowi').addClass('btn-alt-success').css('background-color','#b6d7a8').css('border','none');
                $('#highi').removeClass('btn-alt-danger').css('background-color','').css('border','');
                $('#moderatei').removeClass('btn-alt-warning').css('background-color','').css('border','');
            }
        });

        $('.likelihoodbtn').click(function(){
            var dval = $(this).val();
            $('#likelihood').val(dval);
            $('#likelihood-error').hide();
            if(dval=="High"){
                $('#highl').addClass('btn-alt-danger').css('background-color','#f4cccc').css('border','none');
                $('#moderatel').removeClass('btn-alt-warning').css('background-color','').css('border','');
                $('#lowl').removeClass('btn-alt-success').css('background-color','').css('border','');
            }else if(dval=="Moderate"){
                $('#moderatel').addClass('btn-alt-warning').css('background-color','#fce5cd').css('border','none');
                $('#highl').removeClass('btn-alt-danger').css('background-color','').css('border','');
                $('#lowl').removeClass('btn-alt-success').css('background-color','').css('border','');
            }else if(dval=="Low"){
                $('#lowl').addClass('btn-alt-success').css('background-color','#b6d7a8').css('border','none');
                $('#highl').removeClass('btn-alt-danger').css('background-color','').css('border','');
                $('#moderatel').removeClass('btn-alt-warning').css('background-color','').css('border','');
            }
        });
        $('.clickedimpact').first().trigger('click');
        $('.clickedlikelihood').first().trigger('click');


        const textAreaBehavior = function(element) {
            const charCount = element.val().replace(/\r(?!\n)|\n(?!\r)/g, "\r\n").length;
            const max = element.attr('data-max-character')
            element.closest('div').find('.text-counter').text(charCount + "/" + max);
            if (parseInt(charCount) > parseInt(max)) {
                element.addClass('border-red')
                element.closest('div').find('.text-counter').addClass('text-danger')
            } else {
                element.removeClass('border-red')
                element.closest('div').find('.text-counter').removeClass('text-danger')
            }
        };
        $("#hod_method").change(function() {
            console.log($("#hod_method option:selected").val());
            if ($("#hod_method option:selected").val() == 4) {
                //   $('#shipping_carrier').prop('disabled', 'disabled');
                //   $('#shipping_carrier').val(1);
                $('#risk_response').hide();
                $('#mitigating_action').hide();
            } else {
                //   $('#shipping_carrier').prop('disabled', false);
                $('#risk_response').show();
                $('#mitigating_action').show();
            }
        });
        // $('.js-counter').on('input', function() {
        //     textAreaBehavior($(this))
        // });
        textAreaBehavior($('#riskdec'))
        textAreaBehavior($('#riskdri'))
        textAreaBehavior($('#existing_controls'))

        function preventEnterArea(selector) {
            $(document).ready(function() {
                const $textarea = selector;
                const maxLength = parseInt($textarea.attr('maxlength'));
                var $charCounter = $textarea.closest('div').find('.text-counter');

                function updateText() {
                    const value = $textarea.val();
                    // Normalize newlines to \r\n
                    const normalizedValue = value.replace(/\r(?!\n)|\n(?!\r)/g, "\r\n");
                    const currentLength = normalizedValue.length;

                    // Update the character counter display
                    $charCounter.text(currentLength + '/' + maxLength);

                    // If the current length exceeds the maximum, truncate it
                    if (currentLength > maxLength) {
                        const truncatedValue = normalizedValue.substr(0, maxLength);
                        $textarea.val(truncatedValue);
                        $charCounter.text(maxLength + '/' + maxLength);
                        $textarea.removeClass('border-red')
                        $textarea.closest('div').find('.text-counter').removeClass('text-danger')
                    }
                }

                // Bind events
                $textarea.on('input', updateText);

                // Prevent exceeding maxlength on Enter
                $textarea.on('keydown', function(e) {
                    const value = $textarea.val();
                    const normalizedLength = value.replace(/\r(?!\n)|\n(?!\r)/g, "\r\n").length;
                    if (e.key === 'Enter' && normalizedLength >= maxLength) {
                        e.preventDefault();
                    }
                });
            });
        }
        preventEnterArea($('#riskdec'))
        preventEnterArea($('#riskdri'))
        preventEnterArea($('#existing_controls'))
    </script>


@endsection
