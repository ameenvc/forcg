@extends('layouts.multiselect')
@section('content')
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
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="{{ route('opportunity.oppindex') }}" class="text-black">
                        Opportunity
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Resubmit
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">

                    <form class="mb-5" action="{{url('/user/opportunity/resubmit')}}" method="post" enctype="multipart/form-data" files="true" onsubmit="return validateChips()">
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
                        {{csrf_field()}}
                        @if(count($data)> 0 )
                        @foreach($data as $dat)
                            @php $idd=$dat->id;
                                $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');
                            @endphp
                            <div class="row">
                                <input type="hidden" value="{{auth::user()->email}}" name="post">
                                <input type="hidden" value="{{$idd}}" name="id">
                                <div class="form-group col-sm-4">
                                    {{-- App\User::where('employeeid',$dat->postedby)->value('name') --}}
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="reportedby">Reported By</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="reportedby" name="reportedby" value="{{Auth::user()->name}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="posted">Opportunity ID</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="posted" name="posted" value="{{$dat->dep_id}}" Readonly="">
                                    </div>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="oppname">Opportunity Name<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size: 13px;" id="oppname" name="oppname" value="{{$dat->oppname}}" required>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="oppdec">Opportunity Description<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea type="text" class="form-control" placeholder="Enter Description" style="font-size: 13px;" id="oppdes" name="oppdes" required>{{$dat->oppdes}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="ohs">Is this OHS related?<span class="text-danger">*</span></label>
                                    <div class="custom-control custom-switch col-sm-12" style="padding-left: 14px !important;">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ohs" value="Yes" required {{$dat->ohs=="Yes"?'checked':''}}>
                                            <label class="form-check-label" style="font-size: 13px;">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ohs" value="No" required {{$dat->ohs=="No"?'checked':''}}>
                                            <label class="form-check-label" style="font-size: 13px;">No</label>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $storedTypes = $dat->type ? explode(',',$dat->type) : [];
                                @endphp
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="type">Type <span class="text-danger {{$dat->ohs=="Yes"?'':'d-none'}}" id="asterisk">*</span></label>
                                    <div class="col-sm-12" id="shipping_number" name="type">
                                        <select class="form-control js-select2" style="font-size: 13px;width:100%;" id="type" name="type[]" data-placeholde="Choose Type" multiple data-placeholder="Choose Type" {{$dat->ohs=="Yes"?'required':''}}>
                                            @foreach($types as $type)
                                            <option value="{{$type->id}}" {{in_array($type->id,$storedTypes) ? 'selected' : ''}}>{{$type->name}}</option>
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
                                            <option value="{{$value->id}}" {{$dat->department==$value->id?'selected':''}}> {{ $value->departmentname }}</option></br>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @php
                                    if($dat->department){
                                        $divisions = App\Division::where('department_id',$dat->department)->where('is_deleted', false)->get();
                                    }
                                    if($dat->division_id!=0 && $divisions){
                                        $subdivisions = App\Area::where('division_id',$dat->division_id)->where('is_deleted', false)->get();
                                    }else{
                                        $subdivisions = null;
                                    }
                                @endphp
                                <div class="form-group col-sm-4 {{count($divisions)>0 ? '' : 'd-none'}}" id="division">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                    <div class="col-sm-12">
                                        <select class="form-control js-select2" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val())">
                                            <option value="0">Choose Division</option></br>
                                            @if($divisions)
                                                @foreach($divisions as $division)
                                                    <option value="{{$division->id}}" {{$dat->division_id==$division->id?'selected':''}}> {{ $division->name }}</option></br>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group col-sm-4 {{$divisions && $dat->department && $dat->division_id!=0 && count($subdivisions)>0 ? '' : 'd-none'}}" id="area">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                    <div class="col-sm-12" id="action_number" name="action">
                                        <select class="form-control js-select2" id="area_select" name="area" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                            <option value="0">Choose Sub Division</option></br>
                                            @if($subdivisions)
                                                @foreach($subdivisions as $subdivision)
                                                    <option value="{{$subdivision->id}}" {{$dat->area_id==$subdivision->id?'selected':''}}> {{ $subdivision->name }}</option></br>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6 mb-1">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="responsibility">Responsible Person<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div class="form-group" style="color:#666666">
                                            <select class="js-select2 form-control" id="responsibility" style="font-size:13px;width:100%;" name="responsibility" required>
                                                <option selected="" disabled>Select Responsibility</option>
                                                @if(count($users)> 0 )
                                                @foreach($users as $value)
                                                <option value="{{$value->email}}" {{ $value->email === $dat->responsibility ? 'selected' : '' }}> {{ $value->name }}</option></br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="tardate">Target Date<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" style="font-size: 13px;" id="date" name="targetdate" required value="{{ $dat->tardate }}">
                                    </div>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="action">Action<span class="text-danger">*</span></label>
                                    <div class="col-sm-12" id="action_number" name="action">
                                        <textarea type="text" class="form-control" style="font-size: 13px;" id="action" name="action" required>{{$dat->action}}</textarea>
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
                                            $clicked_class = '';
                                            if($value->name == 'High'){
                                                $btn_class = 'btn-outline-danger';
                                            }elseif($value->name == 'Moderate'){
                                                $btn_class = 'btn-outline-warning';
                                            }elseif($value->name == 'Low'){
                                                $btn_class = 'btn-outline-success';
                                            }
                                            $clicked_class = $dat->likelihood == $value->name ? 'clicked' : '';
                                        @endphp
                                        <input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} likelihoodbtn" id="{{Str::slug($value->name,'_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
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
                                            $clicked_class = '';
                                            if($value->name == 'High'){
                                                $btn_class = 'btn-outline-danger';
                                            }elseif($value->name == 'Moderate'){
                                                $btn_class = 'btn-outline-warning';
                                            }elseif($value->name == 'Low'){
                                                $btn_class = 'btn-outline-success';
                                            }
                                            $clicked_class = $dat->benefit == $value->name ? 'clickedbenefit' : '';
                                        @endphp
                                        <input type="button" class="btn btn-sm {{$btn_class}} {{$clicked_class}} benefitbtn" id="{{Str::slug($value->name.'b','_')}}" style="margin-right:2px;margin-bottom:10px;font-size:12px;" value="{{$value->name}}">
                                        @endforeach
                                        @endif
                                        <div id="benefit-error" class="text-danger" style="display:none;font-size:12px;">Please select a benefit.</div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 mb-0">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="opportunityapprover_id">Opportunity Approver<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div class="form-group" style="color:#666666">
                                            <select class="js-select2 form-select w-100" style="font-size: 13px;width:100%;" id="opportunityapprover_id" name="opportunityapprover_id" required="true">
                                                <option value="" selected disabled>Select Approver</option>
                                                @if(count($opportunityapprovers)> 0 )
                                                    @foreach($opportunityapprovers as $opportunityapprover)
                                                        <option value="{{$opportunityapprover->user->id}}" {{ $dat->opportunityapprover_id == $opportunityapprover->user->id ? 'selected' : '' }}> {{ $opportunityapprover->user->name }}</option></br>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6 mb-0">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="closurereviewer_id">Closure Reviewer<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <div class="form-group" style="color:#666666">
                                            <select class="js-select2 form-select w-100" style="font-size: 13px;width:100%;" id="closurereviewer_id" name="closurereviewer_id" required="true">
                                                <option value="" selected disabled>Select Reviewer</option>
                                                @if(count($closurereviewers)> 0 )
                                                    @foreach($closurereviewers as $closurereviewer)
                                                        <option value="{{$closurereviewer->user->id}}" {{ $dat->closurereviewer_id == $closurereviewer->user->id ? 'selected' : '' }}> {{ $closurereviewer->user->name }}</option></br>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="opp_file">Attach files (avoid '#' and '&' in file names)</label>
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" style="font-size:13px;" id="opp_file" name="opp_file[]" multiple>
                                    </div>
                                </div>
                                <?php
                                    $docs=explode(',',$dat->file);
                                ?>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-4 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary btn-small" style="font-size:12px;width: 155px;" data-toggle="modal" data-id="" data-target="#viewModal1"><strong>Attachments</strong> </button>
                                        <div id="viewModal1" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>
                                                    <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <div class="row justify-content-center">
                                                            <thead>
                                                                <tr class="table-secondary">
                                                                    <th>S.No</th>
                                                                    <th>Attachments</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <?php $sno=0; ?>

                                                            @if( count($docs) > 0 )
                                                            @foreach($docs as $value1)

                                                            @if( $value1!="NULL" && $value1!="")
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                    <td style="font-size:15px;width: 100%;">{{ $value1}}</td>
                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/opportunity/".$value1)}}" download="{{$value1}}">Download</a></td>
                                                                </tr>
                                                            </tbody>
                                                            @else
                                                            <div class="row justify-content-center">
                                                                <h2>No Files</h2>
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                            @else
                                                            <div class="row justify-content-center">
                                                                <h2>No Files</h2>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </table>


                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-right pr-4">
                                    <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return confirm('Are you sure to resubmit?')" style="font-size: 13px;" >Resubmit</button>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="{{ route('opportunity.oppindex') }}">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/{{ route('opportunity.oppaccomplished') }}">
                                <div class="block-content text-center">
                                    <i class="fas fa-check-circle fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Accomplished
                                    </p>
                                </div>
                            </a>
                        </div>

                        <div class="col-6">
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('opportunity.oppanalysis') }}'>
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
    $('input[name="ohs"]').on('change', function() {
        if ($(this).val() == "Yes") {
            $('#type').attr('required', true);
            $('#asterisk').removeClass('d-none');
        } else {
            $('#type').attr('required', false);
            $('#asterisk').addClass('d-none');
        }
    });
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
    $('.clicked').first().trigger('click');
    $('.clickedbenefit').first().trigger('click');
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
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    function chooseDivision(id) {
        $('#division').addClass('d-none');
        $('#area').addClass('d-none');
        $('#division_select').val('');
        $('#area_select').val('');
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
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#division_select').empty().append('<option value="0">Choose Division</option></br>' + option_text);
                }else{
                    $('#division_select').val('');
                    $('#area_select').val('');
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
                    for (var i = 0; i < resp.data.length; i++) {
                        option_text += `<option value="${resp.data[i].id}">${resp.data[i].name}</option>`
                    }
                    $('#area_select').empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                }
                else{
                    $('#area_select').val('');
                }
            }
            , error: function(result) {
                console.log(result);
            }
        });
    }
</script>
@endsection
