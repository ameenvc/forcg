@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')

<style>
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
    @include('shared.forms.risk-page-header', ['formTitle' => 'Change risk owners', 'formDescription' => 'Assign the people accountable for monitoring and responding to this risk.'])
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">

        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="risk-module-form mb-5" enctype="multipart/form-data" action="{{route('risk.updateriskowner')}}" method="post" files="true">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$data->Rid}}">
                        @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="Riskid">Risk ID</label>
                                <div>
                                    <input type="text" class="form-control text-sm" id="riskid" name="riskid" value="{{$data->grp_id}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="Riskgroup">Risk Group</label>
                                <div>
                                    <input type="text" class="form-control text-sm" id="risk_group" name="risk_group" value="{{$data->riskgroup}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="Riskname">Risk Name</label>
                                <div>
                                    <input type="text" class="form-control text-sm" id="riskname" name="riskname" value="{{$data->riskname}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="riskdec">Risk Area<span class="text-danger">*</span></label>
                                <select class="form-control text-sm js-select2 {{ $data->division ? 'selectedRiskArea' : '' }}" name="riskarea" id="riskarea" data-division="{{ $data->division ? $data->division->id : '' }}" data-area="{{ $data->area ? $data->area->id : ''}}" required placeholder="Department" onchange="chooseDivision($(this).val())">
                                    <option value="">Choose Department</option></br>
                                    @if(count($departments)> 0 )
                                    @foreach($departments as $value)
                                    <option value="{{$value->id}}" {{ $value->id == $data->riskarea ? 'selected' : ''}}> {{ $value->departmentname }}</option></br>

                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12 d-none" id="division">
                                <label class="col-form-label text-sm" for="riskdec">Division</label>
                                <div>
                                    <select class="form-control js-select2 text-sm {{ $data->area ? 'selectedDivision' : '' }}" id="division_select" name="division" required="true" placeholder="Division" onchange="chooseArea($(this).val())" style="width:100%;">
                                        <option value="0">Choose Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12 d-none" id="area">
                                <label class="col-form-label text-sm" for="riskdec">Sub Division</label>
                                <div>
                                    <select class="form-control js-select2 text-sm" id="area_select" name="area" required="true" placeholder="Sub Division" style="width:100%;">
                                        <option value="0">Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 col-md-6 col-sm-12">
                                <label class="col-form-label text-sm" for="riskowner">Risk Owner<span class="text-danger">*</span></label>
                                <div >
                                    <select class="js-select2 form-control text-sm" name="riskowner[]" id="riskowner" multiple required="true" data-placeholder="Choose RiskOwner">
                                        @php
                                        $riskownersemails = explode(',',$data->risk_owner);
                                        @endphp
                                        @if(count($users)>0)
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}" style="font-size:13px;" {{ (collect($riskownersemails)->contains($user->email)) ? 'selected':'' }}> {{$user->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;" onclick="return confirm('Do you want to proceed ? ')">Submit</button>
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
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/addrisk">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-square fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Add Risk
                                        </p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('risk.closedrisks') }}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
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
</div>
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
                        option_text += `<option value="${resp.data[i].id}"${resp.data[i].id == divisionID ? 'selected' : ''}>${resp.data[i].name}</option>`
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
                        option_text += `<option value="${resp.data[i].id}" ${resp.data[i].id == areaID ? 'selected' : ''}>${resp.data[i].name}</option>`
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

</script>


@endsection
