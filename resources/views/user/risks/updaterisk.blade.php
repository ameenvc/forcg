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
<div class="content risk-form-page risk-form-shell risk-module-page">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Initial risk review', 'formDescription' => 'Confirm the risk description and assessment, then assign owners before approval.'])
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">


        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Form Horizontal - Default Style -->
                    <form class="risk-module-form mb-5" action="{{url('/user/risks/updaterisk')}}" method="POST" onsubmit="return validateChips()">
                        {{csrf_field()}}
                        @if(count($data)>0)
                        @foreach($data as $dat)

<section class="rf-editor-section rf-context"><div class="rf-section-heading"><h2>Risk context</h2><p>Reference information identifies the record being reviewed.</p></div>                        <div class="form-group">
                            <div class="row col-lg-12">
                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                    <label class="col-form-label text-sm" for="Riskid">Risk ID</label>
                                    <input type="text" class="form-control text-sm" id="Riskid" name="Riskid" value="{{$dat->grp_id}}" Readonly="">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="col-form-label text-sm" for="Riskar">Risk Area</label>
                                    <input type="text" class="form-control" id="Riskar" style="font-size: 13px;" name="Riskar" value="{{$dat->department ? $dat->department->departmentname : '-'}}" Readonly="">
                                </div>
                                @if ($dat->division)
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label text-sm" for="Riskar">Division</label>
                                        <input type="text" class="form-control" id="division" style="font-size: 13px;" value="{{$dat->division ? $dat->division->name : '-'}}" Readonly="">
                                    </div>
                                @endif
                                @if ($dat->area)
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label class="col-form-label text-sm" for="Riskar">Sub Division</label>
                                        <input type="text" class="form-control" id="area" style="font-size: 13px;"  value="{{$dat->area ? $dat->area->name : '-'}}" Readonly="">
                                    </div>
                                @endif
                                <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                    <label class="col-form-label text-sm" for="Riskgp">Risk Group</label>
                                    <input type="text" class="form-control text-sm" id="Riskgp" name="Riskgp" value="{{$dat->riskgroup}}" Readonly="">
                                </div>

                            </div>
                        </div>
</section>
<section class="rf-editor-section "><div class="rf-section-heading"><h2>Risk details</h2><p>Keep the risk statement specific, and distinguish existing controls from proposed actions.</p></div><div class="rf-detail-fields"><div class="form-group col-sm-12 rf-wide">
                                    <label class="col-form-label text-sm" for="riskname">Risk Name<span class="text-danger">*</span></label>
                                    <input required type="text" class="form-control text-sm" id="riskname" name="riskname" value="{{$dat->riskname}}">
                                </div>                        <div class="form-group col-sm-12 rf-wide">
                            <label class="col-form-label text-sm" for="riskdec">Risk Description<span class="text-danger">*</span></label>
                            <div>
                                <textarea rows="5"
                                    required="true"
                                    data-max-character="65435"
                                    class="form-control js-counter text-sm" id="riskdec" name="riskdec"
                                    placeholder="Enter description" maxlength="65435">{{ $dat->riskdec }}</textarea>
                                <span class="text-counter">0/65435</span>
                                @error('riskdec')
                                    <div class="text-danger" style="font-size: 13px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-form-label text-sm" for="riskdri">Risk Drivers</label>
                            <div>
                                <textarea
                                    rows="5"
                                    data-max-character="65435"
                                    type="text"
                                    class="form-control js-counter text-sm" id="riskdri" name="riskdri"
                                    placeholder="Enter drivers" maxlength="65435">{{$dat->riskdri}}</textarea>
                                    <span class="text-counter">0/65435</span>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-form-label text-sm" for="existing_controls">Existing Controls<span class="text-danger">*</span></label>
                            <div>
                                <textarea
                                    rows="5"
                                    required
                                    data-max-character="65435"
                                    type="text"
                                    class="form-control jscounter text-sm"
                                    id="existing_controls"
                                    name="existing_controls"
                                    maxlength="65435">{{$dat->existing_controls}}</textarea>
                                <span class="text-counter">0/65435</span>
                            </div>
                        </div>

</div></section>
<section class="rf-editor-section "><div class="rf-section-heading"><h2>Assess & assign</h2><p>Use the approved criteria for impact and likelihood, then confirm response and ownership.</p></div>                        <div class="row col-lg-12">
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="riskscr">Risk Source<span class="text-danger">*</span></label>
                                <div>
                                    <select required class="form-control js-select2 text-sm" id="riskscr" name="riskscr">
                                        <option value="">Choose source</option>
                                        <option value="Internal" {{$dat->riskscr=='Internal' ? 'selected':''}}>Internal</option><br>
                                        <option value="External" {{$dat->riskscr=='External' ? 'selected':''}}>External</option><br>
                                        <option value="Internal/External" {{$dat->riskscr=='Internal/External' ? 'selected':''}}>Internal/External</option><br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="risk_response">Risk Response Strategy<span class="text-danger">*</span></label>
                                <div>
                                    <select required class="form-control js-select2 text-sm" id="risk_response" name="risk_response">
                                        <option value="">Choose Strategy</option>
                                        @if(count($strategy)> 0 )
                                        @foreach($strategy as $value)
                                        <option value="{{$value->name}}" {{ $dat->risk_response == $value->name ? 'selected' : '' }}> {{ $value->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @php
                                $riskowneremails = $dat->risk_owner ? explode(',',$dat->risk_owner): [];
                            @endphp

                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="riskgroup">Risk Owner<span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-group" style="color:#666666">

                                        <select class="js-select2 form-control w-100 text-sm" id="riskowner" name="riskowner[]" required="true" multiple data-placeholder="Select risk owner">
                                            @if(count($mn)> 0 )
                                            @foreach($mn as $value)
                                            <option value="{{$value->email}}" {{in_array($value->email,$riskowneremails)?'selected':''}}> {{ $value->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label" style="font-size:13px;" for="impact">Impact<span class="text-danger">*</span></label>
                                <div>
                                    <input type="hidden" name="impa" id="impact" value="{{ $dat->impa }}">
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
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label" style="font-size:13px;" for="likelihood">Likelihood<span class="text-danger">*</span></label>
                                <div>
                                    <input type="hidden" name="like" id="likelihood" value="{{ $dat->like }}">
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

</section>
                        <div class="form-group">
                            <input type="hidden" name="posted" value="{{ $dat->Rid}}">
                        </div>
                        <div class="risk-form-actions"><a class="btn btn-outline-primary" href="{{ route('risk.detail', $dat->Rid) }}">Cancel</a>
                            <a href="{{url('user/risks/disapprove?val='.$Rid)}}" class="btn btn-danger text-sm" style="">Disapprove</a>
                            <button type="submit" class="btn btn-primary text-sm" >Approve</button>
                        </div>

                        @endforeach
                        @endif
                    </form>
                    <!-- END Form Horizontal - Default Style -->

                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->



    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="risk-module-form modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
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
