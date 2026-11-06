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
    <!-- Page Content -->
    <div class="content risk-form-page risk-form-shell">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Periodic risk review', 'formDescription' => 'Review current exposure and controls, then record the updated assessment.'])
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">


            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-12">
                        <form class="risk-module-form mb-5" action="{{ url('/user/risks/riskreview') }}" method="post" onsubmit="return validateChips()">
                            {{ csrf_field() }}
                            @if (count($data) > 0)
                                @foreach ($data as $dat)
                                    {{-- <div class="col-lg-10"> --}}
                                    <div class="row">
                                        <div class="col-12 rf-section-heading rf-section-break"><h2>Risk context</h2><p>Confirm the reference information for this risk.</p></div>
<div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm" for="Riskid">Risk
                                                ID</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control text-sm" id="Riskid"
                                                    name="Riskid" value="{{ $dat->grp_id }}"
                                                    Readonly="">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm" for="Riskar">Risk Area<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <select required
                                                    class="form-control js-select2  text-sm {{ $dat->division ? 'selectedRiskArea' : '' }}"
                                                    style="width: 99%"
                                                    name="riskarea" id="riskarea"
                                                    data-division="{{ $dat->division ? $dat->division->id : '' }}"
                                                    data-area="{{ $dat->area ? $dat->area->id : ''}}"
                                                    placeholder="Department"
                                                    onchange="chooseDivision($(this).val())">
                                                    <option value="">Choose Department</option>
                                                    @if (count($departments) > 0)
                                                        @foreach ($departments as $value)
                                                            <option value="{{ $value->id }}"
                                                                {{ $dat->riskarea == $value->id ? 'selected' : '' }}>
                                                                {{ $value->departmentname }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4 d-none" id="division">
                                            <label class="col-sm-12 col-form-label text-sm">Division</label>
                                            <div class="col-sm-12">
                                                <select class="form-control js-select2 col-sm-12 text-sm {{ $dat->division ? 'selectedDivision' : '' }}"
                                                    style="width: 99%"
                                                    id="division_select"
                                                    name="division" required="true"
                                                    placeholder="Division" onchange="chooseArea($(this).val())">
                                                    <option value="0">Choose Division</option></br>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4 d-none" id="area">
                                            <label class="col-sm-12 col-form-label text-sm">Sub Division</label>
                                            <div class="col-sm-12">
                                                <select class="form-control js-select2 text-sm"
                                                    style="width: 99%"
                                                    id="area_select"
                                                    name="area"
                                                    required="true" placeholder="Sub Division">
                                                    <option value="0">Choose Sub Division</option></br>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm" for="riskgroup">Risk Group<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <select class="form-control riskgroup-selection js-select2 text-sm" name="riskgroup" id="riskgroup" required="true" style="width:100%;" onchange="getriskdetails(this.value)">
                                                    @if(count($rskgrp)> 0 )
                                                    <option value="">Choose Risk Group</option>
                                                    @foreach($rskgrp as $value)
                                                    <option value="{{$value->riskgroups}}"
                                                            {{ $dat->riskgroup == $value->riskgroups ? 'selected' : '' }}>
                                                        {{ $value->riskgroups }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm" for="riskname">RiskName<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <input required type="text" class="form-control text-sm" id="riskname"
                                                    name="riskname" value="{{ $dat->riskname }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 rf-section-heading rf-section-break"><h2>Risk details</h2><p>Review the risk statement, drivers, and existing controls.</p></div>
<div class="form-group col-sm-12">
                                            <label class="col-sm-12 col-form-label text-sm" for="riskdec">Risk Description<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <textarea rows="5"
                                                    required
                                                    type="text"
                                                    data-max-character="65435"
                                                    class="form-control js-counter text-sm" id="riskdec" name="riskdec"
                                                    placeholder="Enter description" maxlength="65435">{{ $dat->riskdec }}</textarea>
                                                <span class="text-counter">0/65435</span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label class="col-sm-12 col-form-label text-sm"
                                                for="riskdri">Risk Drivers</label>
                                            <div class="col-sm-12">
                                                <textarea rows="5"
                                                    type="text"
                                                    data-max-character="65435"
                                                    class="form-control text-sm" id="riskdri" name="riskdri"
                                                    placeholder="Enter Risk Drivers" maxlength="65435">{{ $dat->riskdri }}</textarea>
                                                <span class="text-counter">0/65435</span>
                                            </div>
                                        </div>
                                        <!-- Existing controls -->
                                        <div class="form-group col-sm-12">
                                            <label class="col-sm-12 col-form-label text-sm"
                                                for="existing_controls">Existing Controls<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <textarea rows="5"
                                                    required
                                                    type="text"
                                                    data-max-character="65435"
                                                    class="form-control text-sm" id="existing_controls" name="existing_controls"
                                                    placeholder="Enter Risk Drivers" maxlength="65435">{{ $dat->existing_controls }}</textarea>
                                                <span class="text-counter">0/65435</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 rf-section-heading rf-section-break"><h2>Assessment & responsibility</h2><p>Confirm the response, assessment, and responsible people.</p></div>
<div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm"
                                                for="riskscr">Risk Source<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <select required class="form-control js-select2 text-sm" id="riskscr" name="riskscr"
                                                    required="true"
                                                    placeholder="Choose Risk Source">
                                                    <option value="">Choose Risk Source</option>
                                                    <option value="Internal"
                                                        {{ $dat->riskscr == 'Internal' ? 'selected' : '' }}>Internal</option>
                                                    <option value="External"
                                                        {{ $dat->riskscr == 'External' ? 'selected' : '' }}>External</option>
                                                    <option value="Internal/External"
                                                        {{ $dat->riskscr == 'Internal/External' ? 'selected' : '' }}>
                                                        Internal/External</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm"
                                                for="Riskrs">Risk Response Strategy<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
                                                <select required class="js-select2 form-select w-100 text-sm" id="risk_response"
                                                    name="risk_response">
                                                    <option value="">Choose Strategy</option>
                                                    @if (count($strategy) > 0)
                                                        @foreach ($strategy as $value)
                                                            <option value="{{ $value->name }}"
                                                                {{ $dat->risk_response == $value->name ? 'selected' : '' }}>
                                                                {{ $value->name }}</option><br>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        @php
                                            $riskowneremails = $dat->risk_owner ? explode(',', $dat->risk_owner) : [];
                                            $names = App\User::whereIn('email', $riskowneremails)->pluck('name')->toArray();
                                            $names = implode(',', $names);
                                        @endphp
                                        <div class="form-group col-sm-4">
                                            <label class="col-sm-12 col-form-label text-sm"
                                                for="Riskown">Risk Owner</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control text-sm"
                                                    value="{{ $names }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="impact">Impact<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
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
                                                            $clicked_class = $dat->impa == $value->name ? 'clickedimpact' : '';
                                                        @endphp
                                                        <input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} impactbtn" id="{{Str::slug($value->name.'i','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                                    @endforeach
                                                @endif
                                                <div id="impact-error" class="text-danger" style="display:none;font-size:12px;">Please select an impact.</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="likelihood">Likelihood<span class="text-danger">*</span></label>
                                            <div class="col-sm-12">
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
                                                        $clicked_class = $dat->like == $value->name ? 'clickedlikelihood' : '';
                                                    @endphp
                                                    <input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} likelihoodbtn" id="{{Str::slug($value->name.'l','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                                @endforeach
                                                @endif
                                                <div id="likelihood-error" class="text-danger" style="display:none;font-size:12px;">Please select a likelihood.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="posted" value="{{ $dat->Rid }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12" style="display:flex; justify-content: flex-end">
                                            <button type="submit" class="btn btn-primary" style="font-size: 13px;">Reviewed OK</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </form>

                    </div>
                </div>
                <!-- END Form Horizontal - Default Style -->

            </div>
        </div>
    </div>
    <!-- END Horizontal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        function chooseDivision(id) {
            $('#division_select').val(0); //whenever department changes division val is set to 0
            $('#area_select').val(0); //whenever division changes subdivision val is set to 0
            $('#division').addClass('d-none');
            $('#area').addClass('d-none');
            let the_url = '{{ route('admin.departmentDivisions.getDivisions', ':id') }}';
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
                        var divisionID = $('#riskarea').data('division'); //fetching division id from the data, which is use to set selection to selected
                        console.log('divid:'+divisionID)
                        for (var i = 0; i < resp.data.length; i++) {
                            option_text += `<option value="${resp.data[i].id}"${resp.data[i].id == divisionID ? 'selected' : ''}>${resp.data[i].name}</option>`
                        }
                        $('#division_select').empty().append('<option value="0">Choose Division</option></br>' +
                            option_text);
                    }
                },
                error: function(result) {
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
            console.log('newId'+newId)
            $('#area').addClass('d-none');
            let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ':id') }}';
            the_url = the_url.replace(':id', newId);
            let option_text = "";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                method: 'get',
                url: the_url,
                success: function(resp) {
                    console.log(resp)
                    if (resp.data.length) {
                        $('#area').removeClass('d-none');
                        $('#area').css('display', 'inline-block');
                        var areaID = $('#riskarea').data('area'); // fetching sub division id from data, which is use to set selection to selected

                        for (var i = 0; i < resp.data.length; i++) {
                            option_text += `<option value="${resp.data[i].id}" ${resp.data[i].id == areaID ? 'selected' : ''}>${resp.data[i].name}</option>`
                        }
                        console.log(option_text)
                        $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                    }
                },
                error: function(result) {
                    console.log(result);
                }
            });
        }
        $('.selectedDivision').first().trigger('change');

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

    </script>




    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
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
                            {{-- <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/addrisk">
                                            <div class="block-content text-center">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Risk
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div> --}}
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                    <div class="block-content text-center">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="/user/risks/closedrisks">
                                    <div class="block-content text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Risks
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
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



@endsection
