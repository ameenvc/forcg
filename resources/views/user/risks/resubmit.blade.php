@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')



    <style>
        .text-sm {
            font-size: 13px;
        }

        .text-counter {
            font-size: 12px;
            float: right;
            margin-top: 2px;
            color: gray;
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
    <div class="content risk-form-page">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a class="link-fx" href="/user/riskmgmt" class="text-black">
                            Enterprise Risk
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Resubmit Risk
                    </li>
                </ol>
            </nav>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form class="risk-module-form mb-5" action="{{ url('/user/risks/resubmitrisk') }}" method="post" onsubmit="return validateChips()">
                            {{ csrf_field() }}
                            @if (count($data) > 0)
                                @foreach ($data as $d)
                                    @php
                                        $idd = $d->Rid;
                                    @endphp
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <!-- Risk ID -->
                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                <label class="col-form-label text-sm" for="posted">Risk ID</label>
                                                <div>
                                                    <input type="text" class="form-control text-sm" id="posted"
                                                        name="posted" value="{{ $d->grp_id }}" Readonly="">
                                                </div>
                                            </div>
                                            <!-- Risk Group -->
                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                <label class="col-form-label text-sm" for="riskgroup">Risk
                                                    Group</label>
                                                <div>
                                                    <input type="text" class="form-control text-sm" id="riskgroup"
                                                        name="Riskgp" value="{{ $d->riskgroup }}" Readonly="">
                                                </div>
                                            </div>
                                            <!-- Risk Area -->
                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                <label class="col-form-label text-sm" for="riskarea">Risk Area</label>
                                                <div>
                                                    <input type="text" class="form-control text-sm" id="riskarea"
                                                        name="riskarea"
                                                        value="{{ $d->department ? $d->department->departmentname : '-' }}"
                                                        Readonly="">
                                                </div>
                                            </div>
                                            {{-- Division --}}
                                            @if ($d->division)
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display:inline-block;width:49%;">
                                                    <label class="col-form-label text-sm" for="division">Division</label>
                                                    <div>
                                                        <input type="text" class="form-control text-sm" id="division"
                                                            name="division"
                                                            value="{{ $d->division ? $d->division->name : '-' }}"
                                                            Readonly="">
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- Sub Division --}}
                                            @if ($d->area)
                                                <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display:inline-block;width:49%;">
                                                    <label class="col-form-label text-sm" for="area">Sub Division</label>
                                                    <div>
                                                        <input type="text" class="form-control text-sm" id="area"
                                                            name="area"
                                                            value="{{ $d->area ? $d->area->name : '-' }}"
                                                            Readonly="">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <!-- Risk Name -->
                                            <div class="form-group col-lg-4 col-md-6 col-sm-12" style="display:inline-block;width:49%;">
                                                <label class="col-form-label text-sm" for="riskname">Risk Name<span class="text-danger">*</span></label>
                                                <div>
                                                    <input required type="text" class="form-control text-sm" id="riskname"
                                                        name="riskname" value="{{ $d->riskname }}">
                                                </div>
                                            </div>
                                            <!-- Risk Description -->
                                            <div class="form-group col-sm-12">
                                                <label class="col-form-label text-sm" for="riskdec">Risk
                                                    Description<span class="text-danger">*</span></label>
                                                <div>
                                                    <textarea rows="5" required="true" data-max-character="65435" class="form-control js-counter text-sm"
                                                        id="riskdec" name="riskdec" placeholder="Enter description" maxlength="65435">{{ $d->riskdec }}</textarea>
                                                    <span class="text-counter">0/65435</span>
                                                </div>
                                            </div>
                                            <!-- Risk Drivers -->
                                            <div class="form-group col-sm-12">
                                                <label class="col-form-label text-sm" for="riskdri">Risk
                                                    Drivers</label>
                                                <div>
                                                    <textarea rows="5" data-max-character="65435" class="form-control js-counter text-sm" id="riskdri"
                                                        name="riskdri" placeholder="Enter Ris Drivers" maxlength="65435">{{ $d->riskdri }}</textarea>
                                                    <span class="text-counter">0/65435</span>
                                                    {{-- {{ Form::textarea('riskdri',$d->riskdri,array('class'=>'form-control','required'=>'','rows' => 3 ,'style'=>'font-size: 13px')) }} --}}
                                                </div>
                                            </div>
                                            <!-- Existing controls -->
                                            <div class="form-group col-sm-12">
                                                <label class="col-form-label text-sm"
                                                    for="existing_controls">Existing Controls</label>
                                                <div>
                                                    <textarea rows="5" data-max-character="65435" class="form-control text-sm" id="existing_controls"
                                                        name="existing_controls" placeholder="Enter Existing Controls" maxlength="65435">{{ $d->existing_controls }}</textarea>
                                                    <span class="text-counter">0/65435</span>
                                                </div>
                                            </div>

                                            <!-- Risk Source -->
                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                <label class="col-form-label text-sm" for="riskscr">Risk
                                                    Source</label>
                                                <div>
                                                    <select class="form-control js-select2 text-sm" id="riskscr"
                                                        name="riskscr">
                                                        <option value="">Choose Risk Source<span class="text"></span></option>
                                                        <option value="Internal"
                                                            {{ $d->riskscr == 'Internal' ? 'selected' : '' }}>Internal</option>
                                                        <option value="External"
                                                            {{ $d->riskscr == 'External' ? 'selected' : '' }}>External</option>
                                                        <option value="Internal/External"
                                                            {{ $d->riskscr == 'Internal/External' ? 'selected' : '' }}>
                                                            Internal/External</option>
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- Risk Response Strategy --}}
                                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                                <label class="col-form-label text-sm"
                                                    for="risk_response">Risk Response Strategy</label>
                                                <div>
                                                    <select class="form-control js-select2 text-sm" id="risk_response" name="risk_response"
                                                        style="font-size: 13px;">
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
                                            <!-- Risk Reviewer -->
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
                                            <!-- Impact -->
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
                                            <!-- Likelihood -->
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
                                        {{-- Hidden fields --}}
                                        <input type="hidden" value="{{ auth::user()->email }}" name="post">
                                        <input type="hidden" value="{{ $idd }}" name="riskid">

                                        <!-- Resubmit Risk -->
                                        <div class="form-group d-flex justify-content-end mr-4">
                                            <div class="ms-auto">
                                                <button type="submit" class="btn btn-primary px-5">Resubmit Risk</button>
                                            </div>
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
    <!-- END Horizontal -->


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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
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
