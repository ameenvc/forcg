@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')

<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
<div class="content risk-form-page risk-form-shell">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Add linked opportunity', 'formDescription' => 'Capture a potential opportunity arising from this risk and assign responsibility.'])
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">

        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form class="risk-module-form mb-5" action="{{url('/user/risks/opportunitysubmit')}}" enctype="multipart/form-data"  method="POST" files="true" onsubmit="return validateChips()">
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
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="reportedby">Reported By</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="reportedby" name="reportedby" value="{{Auth::user()->name}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label"style="font-size:13px;"  for="originated">Originated from risk</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control"style="font-size: 13px;" id="riskgrpid" name="riskgrpid" value="{{$riskdata->grp_id}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="opportunityname">Opportunity Name<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="opportunityname" name="opportunityname" placeholder="Enter Opportunity Name" required>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="oppdec">Opportunity Description<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" class="form-control" style="font-size: 13px;" id="oppdec" name="oppdec" placeholder="Enter Description" required></textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="ohs">Is this OHS related?<span class="text-danger">*</span></label>
                                <div class="custom-control custom-switch col-sm-12" style="padding-left: 14px !important;">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ohs" value="1" required>
                                        <label class="form-check-label" style="font-size: 13px;">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ohs" value="2" required>
                                        <label class="form-check-label" style="font-size: 13px;">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="type">Type<span class="text-danger d-none" id="asterisk">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control" style="font-size: 13px;width:100%;" id="type" name="type[]" data-placeholder="Choose Type" multiple>
                                        @foreach($opptypes as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Responsible Department<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="responsible_department" name="responsible_department" required="true" onchange="chooseDivision($(this).val())">
                                        <option value="" selected disabled>Choose a Department</option>
                                        @if(count($departments)> 0 )
                                        @foreach($departments as $value)
                                        <option value="{{$value->id}}"> {{ $value->departmentname }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group d-none col-sm-4" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="division_select" name="division" required="true" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                        <option value="0">Choose Division</option></br>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group d-none col-sm-4" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control js-select2" id="area_select" name="area" required="true" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                        <option value="0">Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-sm-6 mb-0">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Responsible Person<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <select class="js-select2 form-select w-100" style="font-size: 13px;width:100%;" id="responsible_person" name="responsible_person" required="true">
                                            <option value="" selected disabled>Select Responsible Person</option>
                                            @if(count($users)> 0 )
                                            @foreach($users as $user)
                                            <option value="{{$user->id}}"> {{ $user->name }}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="date">Target Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" style="font-size: 13px;" id="date" name="targetdate" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="action">Action<span class="text-danger">*</span></label>
                                <div class="col-sm-12" name="action">
                                    <textarea type="text" class="form-control" style="font-size: 13px;" id="action" name="action" placeholder="Enter Action" required></textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="likelihood">Likelihood<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="hidden" name="likelihood" id="likelihood" value="">
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
                                    <input type="button" class="btn btn-sm {{$btn_class}} likelihoodbtn" id="{{Str::slug($value->name,'_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                    @endforeach
                                    @endif
                                    <div id="likelihood-error" class="text-danger" style="display:none;font-size:12px;">Please select a likelihood.</div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="benefit">Benefit<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="hidden" name="benefit" id="benefit" value="">
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
                                    <input type="button" class="btn btn-sm {{$btn_class}} benefitbtn" id="{{Str::slug($value->name.'b','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                    @endforeach
                                    @endif
                                    <div id="benefit-error" class="text-danger" style="display:none;font-size:12px;">Please select a benefit.</div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="opp_file">Attach files (avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" style="font-size:13px;" id="opp_file" name="opp_file[]" multiple>
                                </div>
                            </div>
                            <input type="hidden"  id="riskid" name="riskid" value="{{$riskdata->Rid}}" readonly>

                            <div class="col-sm-12 text-right">
                                <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return ddldd()" style="font-size: 13px;" >Submit</button>
                            </div>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body" href="{{ route('risk.index') }}">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Register
                                    </p>
                                </div>
                            </a>
                            </div>
                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/addrisk">
                                <div class="block-content text-center">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Risk
                                    </p>
                                </div>
                            </a>
                                </div>

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('risk.closedrisks') }}">
                                <div class="block-content text-center">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Risks
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('risk.analysis') }}">
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
<script src="{{ asset('js/opportunity/addopportunity_script.js')}}"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
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
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
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
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
    function ddldd(){
        let files = $("#opp_file")[0].files;
        let message = files.length === 0
            ? 'Are you sure to submit without attachment?'
            : 'Are you sure?';
        if (!confirm(message)) {
            return false;
        }
    }
    $('input[name="ohs"]').on('change', function() {
        if ($(this).val() == 1) {
            $('#type').attr('required', true);
            $('#asterisk').removeClass('d-none');
        } else {
            $('#type').attr('required', false);
            $('#asterisk').addClass('d-none');
        }
    });
    function validateChips() {
        if (!$('#likelihood').val()) {
            $('#likelihood-error').show();
            return false;
        }
        if (!$('#benefit').val()) {
            $('#benefit-error').show();
            return false;
        }
        return true;
    }
    $('.likelihoodbtn').click(function(){
        var dval = $(this).val();
        $('#likelihood').val(dval);
        $('#likelihood-error').hide();
        if(dval=="High"){
            $('#high').addClass('btn-alt-danger').css('background-color','#f4cccc').css('border','none');
            $('#moderate').removeClass('btn-alt-warning').css('background-color','').css('border','');
            $('#low').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Moderate"){
            $('#moderate').addClass('btn-alt-warning').css('background-color','#fce5cd').css('border','none');
            $('#high').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#low').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Low"){
            $('#low').addClass('btn-alt-success').css('background-color','#b6d7a8').css('border','none');
            $('#high').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#moderate').removeClass('btn-alt-warning').css('background-color','').css('border','');
        }
    });
    $('.benefitbtn').click(function(){
        var dval = $(this).val();
        $('#benefit').val(dval);
        $('#benefit-error').hide();
        if(dval=="High"){
            $('#highb').addClass('btn-alt-danger').css('background-color','#f4cccc').css('border','none');
            $('#moderateb').removeClass('btn-alt-warning').css('background-color','').css('border','');
            $('#lowb').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Moderate"){
            $('#moderateb').addClass('btn-alt-warning').css('background-color','#fce5cd').css('border','none');
            $('#highb').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#lowb').removeClass('btn-alt-success').css('background-color','').css('border','');
        }else if(dval=="Low"){
            $('#lowb').addClass('btn-alt-success').css('background-color','#b6d7a8').css('border','none');
            $('#highb').removeClass('btn-alt-danger').css('background-color','').css('border','');
            $('#moderateb').removeClass('btn-alt-warning').css('background-color','').css('border','');
        }
    });
</script>
@endsection
