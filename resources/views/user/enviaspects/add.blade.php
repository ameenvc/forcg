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

    .risk-span-class {
        display: block;
        font-weight: bold;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 13px;
    }

    .high-rank {
        background: #F8D7D6;
        color: #E93A2D;
    }

    .moderate-rank {
        background: #FDF3D1;
        color: #F5C342;
    }

    .other-rank {
        background: #E5F0DB;
        color: #4EAD5B;
    }

    .border-3 {
        border-width: 3px !important;
    }

    .font-15 {
        font-size: 12px !important;
    }

    .form-control:focus {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

</style>
<!-- Main Container -->

<!-- Page Content -->
<div class="content" style="width: 98%;">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/enviaspects/folder" class="text-black">
                        Environmental Aspect
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Add
                </li>
            </ol>
        </nav>

        <div class="block-content block-content-full space-y-3">
            <nav class="d-flex flex-column flex-lg-row items-center justify-content-between gap-2">
                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        1
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Identify Aspect</div>
                        <div class="fw-normal">All aspects</div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        2
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Review Aspect</div>
                        <div class="fw-normal">by reviewer</div>
                    </div>
                </a>

                <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                    <div class="flex-grow-0 rounded-circle bg-body-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        3
                    </div>
                    <div class="flex-grow-1 text-left ml-2">
                        <div class="font-15">Approve Aspect</div>
                        <div class="fw-normal">by Approver</div>
                    </div>
                </a>
            </nav>
        </div>

        <div class="block-content block-content-full">

            <!-- Form Horizontal - Default Style -->
            <form class="mb-5" action="{{url('/user/enviaspects/adddcr')}}" onsubmit="return validation()" method="POST">
                {{csrf_field()}}
                <div class="col-lg-12">
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
                            <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">Date</label>
                            <div class="col-sm-12">
                                <input type="date" style="font-size:13px;" class="js-datepicker form-control" id="date" name="date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="<?php echo date("Y-m-d");?>" readonly>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-12 col-form-label" for="category" style="font-size:13px;">Team</label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-select" id="example-select2-multiple" name="team[]" style="font-size:13px;width:100%;" data-placeholder="Choose Team" multiple>
                                    <option></option>
                                    @if(count($users)> 0 )
                                    @foreach($users as $use)
                                    <option value=" {{$use->name}}">{{ $use->name }}</option><br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskgroup">Department</label>
                            <div class="col-sm-12">

                                <select class="form-control" id="department" name="department" style="font-size: 13px;" onchange="chooseDivision($(this).val(), this)">
                                    <option value="none" selected disabled hidden>
                                        Choose a Department
                                    </option>
                                    @if(count($departments)> 0 )
                                    @foreach($departments as $value)

                                    <option value=" {{$value->id}}" style="font-size: 13px;"> {{ $value->departmentname }}</option></br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-none col-sm-4" id="division">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                            <div class="col-sm-12">
                                <select class="form-control" id="division_select" name="division" required="true" style="font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val())">
                                    <option value="0">Choose Division</option></br>
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-none col-sm-4" id="area">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                            <div class="col-sm-12" id="action_number" name="action">
                                <select class="form-control" id="area_select" name="area" required="true" style="font-size: 13px;" placeholder="Sub Division">
                                    <option value="0">Choose Sub Division</option></br>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-12 col-form-label" for="riskname" style="font-size: 13px;">Location <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="location" style="font-size: 13px;" required placeholder="Enter location">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Environmental activity <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <textarea type="text" class="form-control" id="activity" style="font-size: 13px;" name="activity" required placeholder="Enter description"></textarea>
                            </div>
                        </div>
                        <div class="table-responsive col-sm-12">
                            <table class="table /*table-responsive*/ table-bordered table-striped table-vcenter" id="dynamicTable">
                                <thead style="background-color:#FFFFFF;">
                                    <tr>
                                        <th class="text-center" style="font-size:12px;text-transform:capitalize;width:17%;">Environmental Aspects</th>
                                        <th class="text-center" style="font-size:12px;text-transform:capitalize;width:15%;">Emergency / Non Emergency</th>
                                        <th class="text-center" style="font-size:12px;text-transform:capitalize;width:18%;">Impact</th>
                                        <th colspan="3" class="text-center" style="font-size:12px;text-transform:capitalize;width:49%;">Details</th>
                                        <th style="width:1%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-1"><input type="text" name="doc[0][enviaspects]" id="enviaspects0" style="font-size:12px;" required class="form-control enviaspects" /></td>
                                        <td class="px-1">
                                            <select name="doc[0][ER]" id="ER" class="form-control" style="font-size:12px;cursor:pointer;" required>
                                                <option style="font-size:13px" value="" selected>Select </option>
                                                <option value="Emergency">Emergency</option>
                                                <option value="Non Emergency">Non Emergency</option>
                                            </select>
                                        </td>
                                        <td class="px-1">
                                            <input type="text" class="form-control potential" name="doc[0][potential]" id="potential0" style="font-size:12px;">
                                        </td>
                                        <td colspan="3" class="px-1">
                                            <textarea class="form-control details" cols="70" name="doc[0][details]" id="details0" rows="5" style="font-size:13px;"></textarea>
                                        </td>
                                        <td rowspan="2" class="px-1">
                                            <button type="button" style="font-size:12px;" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add Aspect">
                                                <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-1"><select name="doc[0][A]" id="A" class="form-control" style="font-size:12px;width:100%;cursor:pointer;" required>
                                                <option style="font-size:13px" value="" selected="selected">Probability</option>
                                                @foreach($enviaspect_probabilities as $ep)
                                                <option value="{{ $ep->weightage }}"> {{ showDescriptions($ep->description) }} [{{ $ep->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1"><select name="doc[0][B]" id="B" class="form-control" style="font-size:12px;width:100%;cursor:pointer;" required>
                                                <option style="font-size:13px" value="" selected="selected">Consequence</option>
                                                @foreach($enviaspect_consequences as $ec)
                                                <option value="{{ $ec->weightage }}">{{ showDescriptions($ec->description) }} [{{ $ec->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1"><select name="doc[0][C]" id="C" class="form-control" style="font-size:12px;width:100%;cursor:pointer;" required>
                                                <option style="font-size:13px" value="" selected="selected">Regulatory</option>
                                                @foreach($enviaspect_regulatory_requirements as $er)
                                                <option value="{{ $er->weightage }}">{{ showDescriptions($er->description) }} [{{ $er->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1">
                                            <select name="doc[0][D]" id="D" class="form-control" style="font-size:12px;width:100%;cursor:pointer;" required>
                                                <option style="font-size:13px" value="" selected="selected">Quantity</option>
                                                @foreach($enviaspect_quantities as $eq)
                                                <option value="{{ $eq->weightage }}">{{ showDescriptions($eq->description) }} [{{ $eq->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1">
                                            <select name="doc[0][E]" id="E" class="form-control" style="font-size:12px;width:100%;cursor:pointer;" required>
                                                <option style="font-size:13px" value="" selected="selected">Control&nbsp Measure&nbsp&nbsp&nbsp</option>
                                                @foreach($enviaspect_control_measures as $ecm)
                                                <option value="{{ $ecm->weightage }}">{{ showDescriptions($ecm->description) }} [{{ $ecm->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-1">
                                            <div id="F" class="btn btn-alt-success" style="font-size:11px;width:100%;color:#000;pointer-events:none;">
                                            </div>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="col-sm-12" style="padding-bottom:10px;">
                            @php
                            $m = 0;
                            $items_per_row = 5;
                            @endphp
                            <input type="hidden" id="count_val" value="0">
                            @foreach($environmental_impact_details as $item)
                            <button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:4px;margin-bottom:10px;" onclick="return passvalue('{{$item->id}}');">{{$item->title}}</button>

                            @php
                            $m++;
                            @endphp

                            @endforeach

                        </div>
                    </div>
                    <div class="row">
                        <div class="text-right col-sm-12">
                            <button for="reset" type="reset" class="btn btn-white border-primary border-2 text-primary" style="font-size:13px;">Reset</button>
                            <button type="submit" class="btn btn-primary ml-auto" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- END Form Horizontal - Default Style -->
        </div>
    </div>
</div>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/enviaspects/folder">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>




                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/enviaspects/analysis">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br> Analysis
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
<!-- END Apps Modal -->

<script src="{{ asset( 'js/oneui.core.min.js')}}"></script>
<script src="{{ asset( 'js/oneui.app.min.js')}}"></script>
<script src="{{ asset( 'js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    jQuery(function() {
        One.helpers(['flatpickr', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider']);
    });

</script>
<script type="text/javascript">
    let select_color = '{{ $select_color }}';
    var i = 0;
    $("#addc").click(function() {
        ++i;
        $("#dynamicTable").append(`
            <tr>
                <td><input type="text" name="doc[${i}][enviaspects]" class="form-control enviaspects" /></td>
                <td>
                    <select name="doc[${i}][ER]" class="form-control" style="font-size:12px;">
                    <option value="Emergency">Emergency</option>
                    <option value="Non Emergency">Non Emergency</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][potential]" class="form-control potential">
                    <option style="font-size:13px" value="0" selected="selected">Select</option>
                    <option value="Resource Depletion">Resource Depletion</option>
                    <option value="Air Pollution">Air Pollution</option>
                    <option value="Water Pollution">Water Pollution</option>
                    <option value="Noise and Vibrational Effect">Noise and Vibrational Effect</option>
                    <option value="Land Contamination">Land Contamination</option>
                    <option value="Ozone Depletion">Ozone Depletion</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][A]" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][B]" class="form-control">
                    <option value="0.1">0.1</option>
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][C]" class="form-control">
                    <option value="0.1">0.1</option>
                    <option value="1">1</option>
                    <option value="3">3</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][D]" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][E]" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    </select>
                </td>
                <td>
                    <select name="doc[${i}][F]" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    </select>
                </td>
                <td><input type="text" name="doc[${i}][control]" class="form-control" /></td>
                <td>
                    <button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect">
                    <i class="fa fa-fw remove-tr fa-times"></i>
                    </button>
                </td>
            </tr>
        `);
    });
    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });
</script>
<script type="text/javascript">
    var i = 0;
    let enviaspect_probabilities_text = '{!! $enviaspect_probabilities_text !!}';
    let enviaspect_consequences_text = '{!! $enviaspect_consequences_text !!}';
    let enviaspect_regulatory_requirements_text = '{!! $enviaspect_regulatory_requirements_text !!}';
    let enviaspect_quantities_text = '{!! $enviaspect_quantities_text !!}';
    let enviaspect_control_measures_text = '{!! $enviaspect_control_measures_text !!}';
    $("#add").click(function() {
        ++i;
        $('#count_val').val(i);
        $("#dynamicTable").append(`
            <tr id="first_tr${i}">
                <td class="px-1">
                    <input type="text" name="doc[${i}][enviaspects]" id="enviaspects${i}" style="font-size:13px" class="form-control enviaspects" />
                </td>
                <td class="px-1">
                    <select name="doc[${i}][ER]" class="form-control" style="font-size:12px" onchange="changeERColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Select</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Non Emergency">Non Emergency</option>
                    </select>
                </td>
                <td class="px-1">
                    <input type="text" class="form-control potential" name="doc[${i}][potential]" id="potential${i}" style="font-size:12px;">
                </td>
                <td class="px-1" colspan="3">
                    <textarea class="form-control details" cols="70" name="doc[${i}][details]" id="details${i}" rows="5" style="font-size:13px;"></textarea>
                </td class="px-1">
                <td rowspan="2" class="px-1">
                    <button type="button" name="remove" id="remove${i}" style="font-size:13px" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect" onclick="remove_tr(${i})">
                    <i class="fa fa-fw remove-tr fa-times"></i>
                    </button>
                </td>
            </tr>
            <tr id="second_tr${i}">
                <td class="px-1">
                    <select name="doc[${i}][A]" id="A${i}" class="form-control" style="font-size:12px;cursor:pointer;" onchange="changeAColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Probability</option>
                    ${enviaspect_probabilities_text}
                    </select>
                </td>
                <td class="px-1">
                    <select name="doc[${i}][B]" id="B${i}" class="form-control" style="font-size:12px;cursor:pointer;" onchange="changeBColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Consequence</option>
                    ${enviaspect_consequences_text}
                    </select>
                </td>
                <td class="px-1">
                    <select name="doc[${i}][C]" id="C${i}" class="form-control" style="font-size:12px;cursor:pointer;" onchange="changeCColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Regulatory</option>
                    ${enviaspect_regulatory_requirements_text}
                    </select>
                </td>
                <td class="px-1">
                    <select name="doc[${i}][D]" id="D${i}" class="form-control" style="font-size:12px;cursor:pointer;" onchange="changeDColor(this)" required>
                        <option style="font-size:13px" value="" selected="selected">Quantity</option>
                        ${enviaspect_quantities_text}
                    </select>      
                </td>
                <td class="px-1">
                    <select name="doc[${i}][E]" id="E${i}" class="form-control" style="font-size:12px;cursor:pointer;" onchange="changeEColor(this)" required>
                        <option style="font-size:13px" value="" selected="selected">Control Measure</option>
                        ${enviaspect_control_measures_text}
                    </select>
                </td>
                <td class="px-1">
                    <div id="F${i}" class="btn btn-outline-primary" style="font-size:11px;width:100%;color:#000;pointer-events:none;">
                        <span class="p-2">Significance</span>
                    </div>
                </td>
            </tr>
        `);
    });
    function remove_tr(i) {
        $('#first_tr' + i).remove();
        $('#second_tr' + i).remove();
    }
    $('#A, #B, #C, #D, #E').on('change', function(){
        let A = $('#A').val();
        let B = $('#B').val();
        let C = $('#C').val();
        let D = $('#D').val();
        let E = $('#E').val();
        var result = A * B * C * D * E;
        var significance = '';
        if(result > 0 && result < 100){
            significance = `<span class="px-1" style="background-color: rgba(169, 180, 219, 0.3) !important;border-radius:20%;color:#3657a4;transform: rotate(180deg); display: inline-block;font-size:16px;margin-top:2px;">&#8893;</span><span class="p-2">Non Significant (NS)</span>`;
            $('#F').removeClass().addClass('btn btn-alt-primary').html(significance);
        }
        else if(result >=100 && result <= 200){
            significance = `<span class="px-1" style="background-color: rgba(40, 167, 69, 0.2) !important;border-radius:20%;color:#186145;font-size:16px;">&equals;</span><span class="p-2">Moderate Significant (MS)</span>`;
            $('#F').removeClass().addClass('btn btn-alt-success').html(significance);
        }
        else if(result > 200){
            significance = `<span class="px-1" style="background-color: rgba(221, 160, 160, 0.3) !important;border-radius:20%;color:#b22828;font-size:16px;">&#8893;</span><span class="p-2">High Significant (HS)</span>`;
            $('#F').removeClass().addClass('btn btn-alt-danger').html(significance);
        }else{
            var significance = `<span class="p-2">Significance</span>`;
            $('#F').removeClass().addClass('btn btn-outline-primary').html(significance);
        }
    });
    $('#A, #B, #C, #D, #E').trigger('change');
</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function chooseDivision(id, self) {
        changeAddedRowSelectedColor(self);
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


    function passvalue(id) {
        let count_val = $('#count_val').val();
        $.ajax({
            data: {
                "data_id": id
                , _token: CSRF_TOKEN
            }
            , url: "{{ route('enviaspects.displayimpactdetailajax')}}"
            , type: "POST"
            , success: function(response) {
                if (response.status == 200) {
                    var titlelist = response.title;
                    var titlehtmldatas = '';

                    if (titlelist.length > 0) {
                        titlehtmldatas = titlelist;
                    } else {
                        titlehtmldatas = null;
                    }


                    var impactlist = response.impact;
                    var impacthtmldatas = '';

                    if (impactlist.length > 0) {
                        impacthtmldatas = impactlist;
                    } else {
                        impacthtmldatas = null;
                    }

                    var detailslist = response.details;
                    var detailshtmldatas = '';
                    const removespace = new RegExp(/\s{3,}/, 'ig');
                    const detailslistdisplay = detailslist.replace(removespace, '\n');

                    if (detailslist.length > 0) {
                        detailshtmldatas = detailslistdisplay;
                    } else {
                        detailshtmldatas = null;
                    }
                    let lastRow = $('#dynamicTable tr').eq(-2);
                    console.log(lastRow);
                    lastRow.find('input.potential').val(impacthtmldatas).css('background-color','#e8f0fe');
                    lastRow.find('textarea.details').val(detailshtmldatas).css('background-color','#e8f0fe'); 
                    lastRow.find('input.enviaspects').val(titlehtmldatas).css('background-color','#e8f0fe'); 
                }

            }

        });
    }

    function changeAddedRowSelectedColor(self) {
        if (self.value !== '') {
            $(self).css('background-color', select_color).css('boder-color', select_color);
        } else {
            $(self).css('background-color', '#fff').css('border-color', '#d5dce1');;
        }
    }
    function calculateSignificance(self) {
        let id = self.id;
        let number = id.match(/\d+/)[0];
        let A = $(`#A${number}`).val();
        let B = $(`#B${number}`).val();
        let C = $(`#C${number}`).val();
        let D = $(`#D${number}`).val();
        let E = $(`#E${number}`).val();
        var result = A * B * C * D * E;
        var significance = '';
        if(result > 0 && result < 100){
            significance = `<span class="px-1" style="background-color: rgba(169, 180, 219, 0.3) !important;border-radius:20%;color:#3657a4;transform: rotate(180deg); display: inline-block;font-size:16px;margin-top:2px;">&#8893;</span><span class="p-2">Non Significant (NS)</span>`;
            $(`#F${number}`).removeClass().addClass('btn btn-alt-primary').html(significance);
        }
        else if(result >=100 && result <= 200){
            significance = `<span class="px-1" style="background-color: rgba(40, 167, 69, 0.2) !important;border-radius:20%;color:#186145;font-size:16px;">&equals;</span><span class="p-2">Moderate Significant (MS)</span>`;
            $(`#F${number}`).removeClass().addClass('btn btn-alt-success').html(significance);
        }
        else if(result > 200){
            significance = `<span class="px-1" style="background-color: rgba(221, 160, 160, 0.3) !important;border-radius:20%;color:#b22828;font-size:16px;">&#8893;</span><span class="p-2">High Significant (HS)</span>`;
            $(`#F${number}`).removeClass().addClass('btn btn-alt-danger').html(significance);
        }else{
            var significance = `<span class="p-2">Significance</span>`;
            $(`#F${number}`).removeClass().addClass('btn btn-outline-primary').html(significance);
        }
    }
    function changeERColor(self) {
        changeAddedRowSelectedColor(self);
    }

    function changeAColor(self) {
        changeAddedRowSelectedColor(self);
        calculateSignificance(self);
    }

    function changeBColor(self) {
        changeAddedRowSelectedColor(self);
        calculateSignificance(self);
    }

    function changeCColor(self) {
        changeAddedRowSelectedColor(self);
        calculateSignificance(self);
    }

    function changeDColor(self) {
        changeAddedRowSelectedColor(self);
        calculateSignificance(self);
    }

    function changeEColor(self) {
        changeAddedRowSelectedColor(self);
        calculateSignificance(self);
    }


    function changeSelectedColor(item) {
        $(item).on('change', function() {

            if ($(this).val() !== '') {
                $(this).css('background-color', select_color).css('boder-color', select_color);
            } else {
                $(this).css('background-color', '#fff').css('border-color', '#d5dce1');;
            }
        });

    }

    changeSelectedColor('#ER');
    changeSelectedColor('#A');
    changeSelectedColor('#B');
    changeSelectedColor('#C');
    changeSelectedColor('#D');
    changeSelectedColor('#E');
</script>
@endsection