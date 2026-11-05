@extends('layouts.multiselect')
@section('content')
@include('shared.forms.assets')
<link rel="stylesheet" href="{{ asset('css/risk-form.css') }}?v={{ filemtime(public_path('css/risk-form.css')) }}">
<script src="{{ asset('js/risk-form.js') }}" defer></script>
<!-- Page Content -->
<div class="content risk-workspace cgbod-form">
    <!-- Horizontal -->
    <div class="block block-rounded risk-form-shell cgbod-form-card">
        <header class="risk-page-header cgbod-form-header">
            <div>
                <a class="risk-back-link" href="{{ route('risk.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Enterprise risk register</a>
                <h1>Add a new risk</h1>
                <p>Capture the concern, assess its significance, and assign a reviewer.</p>
            </div>
            @include('user.risks.ai-trigger')
        </header>
        <nav class="risk-step-nav" aria-label="Form sections">
            <a href="#risk-context"><span>01</span> Context</a>
            <a href="#risk-details"><span>02</span> Risk details</a>
            <a href="#risk-assessment"><span>03</span> Assessment &amp; review</a>
            <small><span aria-hidden="true">*</span> Required fields</small>
        </nav>
        <div class="block-content block-content-full">
            @if(Session::has('errmsg'))
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
            @endif
            @if(Session::has('msg'))
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('msg')!!}
                </div>
            @endif
            <div class="row">

                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form id="risk-entry-form" class="risk-entry-form" action="{{url('/user/risks/submit')}}" method="POST" onsubmit="return validateChips()">
                        {{csrf_field()}}
                        @include('user.risks.ai-assist')
                        <section class="risk-form-section cgbod-form-section" id="risk-context" aria-labelledby="risk-context-title">
                        @include('shared.forms.section-heading', ['number' => '01', 'headingId' => 'risk-context-title', 'title' => 'Set the context', 'description' => 'Choose where this risk belongs in your organization.'])
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label text-sm" for="riskgroup">Risk Group<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control riskgroup-selection js-select2 text-sm" name="riskgroup" id="riskgroup" required="true" style="width:100%;" onchange="getriskdetails(this.value)">
                                        @if(count($rskgrp)> 0 )
                                        <option value="">Choose Risk Group</option>
                                        @foreach($rskgrp as $value)
                                        <option value="{{$value->riskgroups}}"
                                                {{ old('riskgroup') == $value->riskgroups ? 'selected' : '' }}>
                                            {{ $value->riskgroups }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label text-sm" for="riskscr">Risk Source</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2 text-sm" id="riskscr" name="riskscr" style="width:100%;" placeholder="Choose Risk Source">
                                        <option value="">Choose Risk Source</option>
                                        <option value="Internal" {{ old('riskscr') == "Internal" ? 'selected' : '' }}>Internal</option>
                                        <option value="External" {{ old('riskscr') == "External" ? 'selected' : '' }}>External</option>
                                        <option value="Internal/External" {{ old('riskscr') == "Internal/External" ? 'selected' : '' }}>Internal/External</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label text-sm" for="riskarea">Risk Area<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2 text-sm" name="riskarea" id="riskarea" required="true" style="width:100%;" placeholder="Department" onchange="chooseDivision($(this).val())">
                                        <option value="">Choose Department</option>
                                        @if(count($departments)> 0 )
                                            @foreach($departments as $value)
                                                <option value="{{$value->id}}" {{ old('riskarea') == $value->id ? 'selected' : '' }}>
                                                    {{ $value->departmentname }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('riskarea')
                                        <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-sm-4 d-none" id="division">
                                <label class="col-sm-12 col-form-label text-sm">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2 text-sm" id="division_select" name="division" required="true" style="width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option value="0">Choose Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4 d-none" id="area">
                                <label class="col-sm-12 col-form-label text-sm" >Sub Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2  text-sm" id="area_select" name="area" required="true" style="width:100%;" placeholder="Sub Division">
                                        <option value="0">Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 risk-category-wrap d-none" style="margin-bottom:0;">
                                <label class="col-sm-12 col-form-label text-sm" for="riskcategory">Operational risk category</label>
                                <div class="col-sm-12">
                                    @foreach(config('constants.risk_management.category') as $key => $item)
                                        <div class="custom-control custom-radio mb-1 mr-2  text-sm" style="display: inline-block;">
                                            <input type="radio" class="custom-control-input risk-category"
                                                    value="{{ $item['value'] }}"
                                                    id="riskcategory{{ $key }}" name="riskcategory">
                                            <label class="custom-control-label label-risk-cat" for="riskcategory{{ $key }}">{{ $item['value'] }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        </section>
                        <section class="risk-form-section cgbod-form-section" id="risk-details" aria-labelledby="risk-details-title">
                        @include('shared.forms.section-heading', ['number' => '02', 'headingId' => 'risk-details-title', 'title' => 'Describe the risk', 'description' => 'Explain what could happen, why it could happen, and what is already in place.'])
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label text-sm" for="riskname">Risk Name<span class="text-danger">*</span></label>
                                <div class="col-sm-12"  id="getrisknamedetails">

                                </div>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control  text-sm"
                                            value="{{ old('riskname') }}" required="true" id="riskname"
                                            name="riskname" maxlength="190" placeholder="e.g. Production disruption from critical spare-part shortages">
                                    @error('riskname')
                                        <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label text-sm" for="riskdec">Risk Description<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea rows="5"
                                        required="true"
                                        data-max-character="65435"
                                        class="form-control js-counter text-sm" id="riskdec" name="riskdec"
                                        placeholder="Describe the event, its causes, and the potential impact on your objectives." maxlength="65435">{{ old('riskdec') }}</textarea>
                                    <span class="text-counter">0/65435</span>
                                    @error('riskdec')
                                        <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="col-sm-12 col-form-label text-sm" for="riskdri">Risk Drivers</label>
                                <div class="col-sm-12">
                                    <textarea rows="5"
                                                data-max-character="65435"
                                                class="form-control js-counter text-sm" id="riskdri" name="riskdri"
                                                placeholder="e.g. Single-source dependency, long lead times, or limited safety stock" maxlength="65435">{{ old('riskdri') }}</textarea>
                                    <span class="text-counter">0/65435</span>
                                    @error('riskdri')
                                    <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label class="col-sm-12 col-form-label text-sm" for="existing_controls">Existing Controls</label>
                                <div class="col-sm-12">
                                    <textarea rows="5"
                                                style="font-size: 13px;"
                                                class="form-control js-counter"
                                                data-max-character="65435"
                                                id="existing_controls"
                                                maxlength="65435"
                                                name="existing_controls" placeholder="List safeguards that are already implemented. Keep proposed actions separate.">{{ old('existing_controls') }}</textarea>
                                    <span class="text-counter">0/65435</span>
                                    @error('existing_controls')
                                    <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        </section>
                        <section class="risk-form-section cgbod-form-section" id="risk-assessment" aria-labelledby="risk-assessment-title">
                        @include('shared.forms.section-heading', ['number' => '03', 'headingId' => 'risk-assessment-title', 'title' => 'Assess & assign', 'description' => 'Select impact and likelihood using your approved criteria, then choose a reviewer.'])
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label text-sm" for="risk_response">Risk Response Strategy</label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-select w-100 text-sm" id="risk_response" name="risk_response" style="width:100%;">
                                        <option selected="" disabled>Choose Strategy</option>
                                        @if(count($strategy)> 0 )
                                        @foreach($strategy as $value)
                                        <option value="{{$value->name}}" {{ old('risk_response') == $value->name ? 'selected' : '' }}> {{ $value->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" id="risk-impact-label">Impact<span class="text-danger">*</span></label>
                                <div class="col-sm-12" role="group" aria-labelledby="risk-impact-label">
                                    <input type="hidden" name="impa" id="impact" value="{{ old('impa') }}">
                                    @if(count($hml)> 0 )
                                    @foreach($hml as $value)
                                        @php
                                            $btn_class = '';
                                            if($value->name == 'High'){
                                                $btn_class = 'btn-outline-danger';
                                            }elseif($value->name == 'Moderate'){
                                                $btn_class = 'btn-outline-warning';
                                            }elseif($value->name == 'Low'){
                                                $btn_class = 'btn-outline-success';
                                            }
                                        @endphp
                                        <input type="button" class="btn btn-sm {{$btn_class}} impactbtn" id="{{Str::slug($value->name.'i','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                    @endforeach
                                    @endif
                                    <div id="impact-error" role="alert" class="text-danger" style="display:none;font-size:12px;">Please select an impact.</div>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" id="risk-likelihood-label">Likelihood<span class="text-danger">*</span></label>
                                <div class="col-sm-12" role="group" aria-labelledby="risk-likelihood-label">
                                    <input type="hidden" name="like" id="likelihood" value="{{ old('like') }}">
                                    @if(count($hml)> 0 )
                                    @foreach($hml as $value)
                                        @php
                                            $btn_class = '';
                                            if($value->name == 'High'){
                                                $btn_class = 'btn-outline-danger';
                                            }elseif($value->name == 'Moderate'){
                                                $btn_class = 'btn-outline-warning';
                                            }elseif($value->name == 'Low'){
                                                $btn_class = 'btn-outline-success';
                                            }
                                        @endphp
                                        <input type="button" class="btn btn-sm {{$btn_class}} likelihoodbtn" id="{{Str::slug($value->name.'l','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                    @endforeach
                                    @endif
                                    <div id="likelihood-error" role="alert" class="text-danger" style="display:none;font-size:12px;">Please select a likelihood.</div>
                                </div>

                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label text-sm" for="riskreviewer_id">Risk Reviewer<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2 text-sm" name="riskreviewer_id" id="riskreviewer_id" required="true" style="width:100%;" placeholder="Select Reviewer">
                                        <option value="">Choose Reviewer</option>
                                        @if(count($riskreviewers)> 0 )
                                            @foreach($riskreviewers as $riskreviewer)
                                                <option value="{{$riskreviewer->user->id}}" {{ old('riskreviewer_id') == $riskreviewer->user->id ? 'selected' : '' }}>
                                                    {{ $riskreviewer->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('riskreviewer_id')
                                        <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="risk-score-card" id="risk-score-card" data-rating="unassessed" role="status" aria-live="polite">
                                    <span class="risk-score-icon" aria-hidden="true"><i class="fa fa-th"></i></span>
                                    <div><span class="risk-score-eyebrow">YOUR RISK ASSESSMENT</span><strong id="risk-score-value">Not assessed yet</strong><p id="risk-score-description">Choose impact and likelihood to see your 3 × 3 matrix result.</p></div>
                                </div>
                            </div>
                        </div>
                        </section>
                        <div class="risk-form-actions cgbod-form-actions">
                            <p><i class="fa fa-check-circle" aria-hidden="true"></i> Review your entries before submitting.</p>
                            <div>
                                <button type="reset" class="btn risk-reset-button" onclick="resetFormCustom()">Reset form</button>
                                <button type="submit" class="btn btn-primary risk-submit-button" data-toggle="click-ripple" onclick="return confirm('Submit this risk for review?')">Submit risk <i class="fa fa-arrow-right ml-2" aria-hidden="true"></i></button>
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
                                        <div class="block-content text-center" style="height:130px">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                    <div class="block-content text-center" style="height:130px">
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
                                    <div class="block-content text-center" style="height:130px">
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
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $("#hod_method").change(function() {
        console.log($("#hod_method option:selected").val());
        if ($("#hod_method option:selected").val() == 4) {
            $('#risk_response').hide();
            $('#mitigating_action').hide();
        } else {
            $('#risk_response').show();
            $('#mitigating_action').show();
        }
    });

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
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`;
                    }
                    $('#division_select').empty().append('<option value="0">Choose Division</option></br>' + option_text);
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
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`;
                    }
                    $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }

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

    $('.risk-category').click(function () {
        if($(this).prop("checked")) {
            getriskdetails('Operational', $(this).val());
        }
    });

    const textAreaBehavior = function(element, event = null) {
        const charCount = element.val().replace(/\r(?!\n)|\n(?!\r)/g, "\r\n").length;
        const max = element.attr('data-max-character');
        element.closest('div').find('.text-counter').text(charCount + "/" + max);
        if (parseInt(charCount) > parseInt(max)) {
            element.addClass('border-red');
            element.closest('div').find('.text-counter').addClass('text-danger');
        } else {
            element.removeClass('border-red');
            element.closest('div').find('.text-counter').removeClass('text-danger');
        }
    };

    /**---risk group details starts here */
    function getriskdetails(group_name, category_name = '')
    {
        $('#riskname').val('');
        $('#riskdec').val('');
        $('#riskdri').val('');
        $('#mitigating_action_value').val('');
        if(group_name === 'Operational') {
            $('.risk-category-wrap').removeClass('d-none');
        } else {
            $('.risk-category-wrap').addClass('d-none');
        }
        $.ajax({
            data: {
                "risk_group_name": group_name,
                "risk_category_name": category_name,
                _token: CSRF_TOKEN
            }
            , url: "{{ route('risk.displayriskgroupnameajax')}}"
            , type: "POST"
            , success:function(response) {
                if(response.status == 200)
                    {
                    var namedatalist = response.risknames;
                    var riskgroupname = response.riskgroupname;
                    var userhtmldatas='';
                    if(namedatalist.length>0){
                        var m = 0;
                        var items_per_row = 2;
                        for(let i=0;i<namedatalist.length;i++){
                            userhtmldatas+='<button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:2px;margin-left:5px;margin-bottom:10px;font-size:12px;" onclick="passvalue(\''+namedatalist[i]+','+riskgroupname+'\');">'+namedatalist[i]+'</button>';
                            m++;
                            if(m % items_per_row == 0){ '<br>'}
                        }
                    }
                    $('#getrisknamedetails').html(userhtmldatas);

                    }

            }
        });
    }

    function passvalue(details)
    {
        var modules = details;
        var modules_array = modules.split(',');
        var riskgroupname = modules_array[1];
        var riskname = modules_array[0];
        document.getElementById('riskname').value=modules_array[0];
        $.ajax({
            data: {
                "risk_name": riskname,
                "risk_groupname":riskgroupname,
                _token: CSRF_TOKEN
            }
            , url: "{{ route('risk.displayriskgroupdetailsajax')}}"
            , type: "POST"
            , success:function(response) {
                // console.log(response.risk_name)
                // console.log(response.risk_desp)
                // console.log(response.risk_driver)
                // console.log(response.mitigating_action)
                if(response.status == 200){
                    var removespace = new RegExp(/\s{3,}/, 'ig');
                    var riskdriverlist = response.risk_driver;
                    var riskdriverlistdisplay = riskdriverlist.replace(removespace, '\n');
                    document.getElementById('riskdec').value =response.risk_desp;
                    document.getElementById('riskdri').value =riskdriverlistdisplay;
                    textAreaBehavior($('#riskdec'));
                    textAreaBehavior($('#riskdri'));
                }
            }

        });
    }

    function resetFormCustom() {
        const riskdecmax = $('#riskdec').attr('data-max-character')
        $('#riskdec').closest('div').find('.text-counter').text(0 + "/" + riskdecmax);
        $('#riskdec').removeClass('border-red')
        $('#riskdec').closest('div').find('.text-counter').removeClass('text-danger')

        const riskdrimax = $('#riskdri').attr('data-max-character')
        $('#riskdri').closest('div').find('.text-counter').text(0 + "/" + riskdrimax);
        $('#riskdri').removeClass('border-red')
        $('#riskdri').closest('div').find('.text-counter').removeClass('text-danger')

        const existing_controlsmax = $('#existing_controls').attr('data-max-character')
        $('#existing_controls').closest('div').find('.text-counter').text(0 + "/" + existing_controlsmax);
        $('#existing_controls').removeClass('border-red')
        $('#existing_controls').closest('div').find('.text-counter').removeClass('text-danger')
    }
    textAreaBehavior($('#riskdec'));
    textAreaBehavior($('#riskdri'));
    textAreaBehavior($('#existing_controls'));
    // if ($('#riskgroup').val()) {
    //     getriskdetails($('#riskgroup').val())
    // }
    function preventEnterArea(selector)
    {
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
    preventEnterArea($('#riskdec'));
    preventEnterArea($('#riskdri'));
    preventEnterArea($('#existing_controls'));
</script>

@endsection
