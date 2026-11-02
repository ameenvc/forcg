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
                    Initial Review
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12">
                    <form class="mb-5" action="{{url('/user/opportunity/update')}}" method="post" onsubmit="return validateChips()">
                        {{csrf_field()}}
                        @if(count($data)> 0 )
                            @foreach($data as $dat)
                                @php
                                    $dept = DB::table('departments')->where('id',$dat->department)->value('departmentname');
                                @endphp
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="reportedby">Reported By</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size: 13px;" id="reportedby" name="reportedby" value="{{App\User::where('employeeid',$dat->postedby)->value('name')}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="col-sm-12 col-form-label" for="posted" style="font-size:13px;">Opportunity ID</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" style="font-size:13px;" id="posted" name="posted" value="{{$dat->dep_id}}" Readonly="">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="oppname">Opportunity Name<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="oppname" style="font-size:13px;" name="oppname" value="{{$dat->oppname}}" required placeholder="Enter Opportunity Name">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="oppdes">Opportunity Description<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <textarea type="text" class="form-control" style="font-size: 13px;" id="oppdes" name="oppdes" required placeholder="Enter Description">{{$dat->oppdes}}</textarea>
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
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="type">Type<span class="text-danger {{$dat->ohs=="Yes"?'':'d-none'}}" id="asterisk">*</span></label>
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
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Responsible Person<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <select class="js-select2 form-select w-100" style="font-size: 13px;width:100%;" id="responsible_person" name="responsible_person" required="true">
                                                <option value="" selected disabled>Select Responsible Person</option>
                                                @if(count($user)> 0 )
                                                @foreach($user as $u)
                                                <option value="{{$u->email}}" {{$dat->responsibility==$u->email?'selected':''}}> {{ $u->name }}</option></br>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="tardate">Target Date<span class="text-danger">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="date" class="form-control" style="font-size:13px;" id="date" name="tardate" required value="{{ $dat->tardate }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label class="col-sm-12 col-form-label" style="font-size:13px;" for="action">Action<span class="text-danger">*</span></label>
                                        <div class="col-sm-12" name="action">
                                            <textarea type="text" class="form-control" required placeholder="Enter Action" style="font-size: 13px;" id="action" name="action">{{$dat->action=="NULL" ? '' : $dat->action}}</textarea>
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

                                    
                                    <?php
                                        $docs=explode(',',$dat->file);
                                    ?>
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-4 col-form-label" for="download" style="font-size:13px;">Attached file</label>
                                        <div class="col-sm-8">
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
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-12 col-form-label" for="additional" style="font-size:13px;">Additional email notification</label>
                                        <div class="col-sm-12">
                                            <select class="js-select2 form-control" id="example-select2-multiple" style="font-size:13px;width:100%;" name="additional[]" multiple data-placeholder="Choose Users">
                                                {{-- <option value="0" selected="" disabled>Select</option> --}}
                                                @foreach($user as $u)
                                                <option value=" {{$u->email}}"> {{ $u->name }}</option></br>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="posted" value="{{ $dat->id}}">
                                    <div class="col-sm-12 text-right pr-4">
                                        <a href="{{ route('opportunity.disapproveopp', ["id" => $id])}}"  class="btn btn-outline-danger" style="font-size: 13px;">Disapprove</a>
                                        <button type="submit" for="submit"  class="btn btn-primary" data-toggle="click-ripple"  onclick="return confirm('Are you sure?')" style="font-size: 13px;">Approve</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>

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
</script>
@endsection