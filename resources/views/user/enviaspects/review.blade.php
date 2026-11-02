@extends('layouts.backend')
@section('css_after')
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

    .ER {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

    .A {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

    .B {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

    .C {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

    .D {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

    .E {
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #e6e6ff !important;
    }

</style>
@endsection
@section('content')
<!doctype html>
<html lang="en">


<body>

    <!-- Page Content -->
    <div class="content">


        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
            <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item font-w700">
                        <a href="/user/enviaspects/listofaspects/{{Session::get('envidept')}}" class="text-black">
                            Environmental Aspect
                        </a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Review
                    </li>
                </ol>
            </nav>


            <div class="block-content block-content-full space-y-3">
                <nav class="d-flex flex-column flex-lg-row items-center justify-content-between gap-2">
                    <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                        <div class="flex-grow-0 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa fa-fw fa-check"></i>
                        </div>
                        <div class="flex-grow-1 text-left ml-2">
                            <div class="font-15">Identify Aspect</div>
                            <div class="fw-normal">All aspects</div>
                        </div>
                    </a>

                    <a href="javascript:void(0)" class="btn btn-lg btn-alt-secondary bg-transparent w-100 text-start fs-sm d-flex align-items-center justify-content-between gap-3">
                        <div class="flex-grow-0 rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
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
                <div class="row">

                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/enviaspects/review')}}" method="POST" onsubmit="return validation()">
                            {{csrf_field()}}

                            @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                {{ $error }}
                            </div>
                            @endforeach
                            @endif

                            @if(count($data)>0)

                            @foreach($data as $dat)


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="activity">Environmental activity</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="activity" readonly value="{{$dat->activity}}">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="department" name="department" readonly value="{{$dat->dept ? $dat->dept->departmentname : '-'}}">
                                </div>
                            </div>
                            @if($dat->division)
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Division</label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->division->name}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($dat->area)
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                                <div class="col-sm-12">
                                    <div class="form-group" style="color:#666666">
                                        <input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->area->name}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Location</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="location" style="font-size: 13px;" name="location" readonly value="{{$dat->location}}">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Team</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="team" id="team" style="font-size: 13px;" readonly value="{{$dat->team}}">
                                </div>
                            </div>


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="date">Date</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" style="font-size: 13px;" name="date" id="date" readonly value="{{$dat->date}}">
                                </div>
                            </div>

                            @endforeach
                            @endif


                            <br>


                            @if(count($data1)>0)

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped table-vcenter " id="dynamicTable">

                                    <tr>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Environmentalnbsp&nbsp&nbspAspects</th>
                                        <th class="text-center" style="font-size:13px;width: 40px;text-transform:capitalize">Emergency&nbsp/&nbspNon&nbspEmergency</th>
                                        <th class="text-center" style="font-size:13px;text-transform:capitalize;padding-right:80px;padding-left:80px;">Impact</th>
                                        <th class="text-center" style="font-size:12px;padding-right:100px;padding-left:100px;text-transform:capitalize">Details</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Significance</th>
                                        <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbspInitial&nbsp&nbsp&nbsp&nbspControl, If Any Required?</th>
                                        <th></th>
                                    </tr>
                                    @php
                                    $no = 0;
                                    @endphp
                                    @foreach($data1 as $dat1)
                                    <?php $rr=$dat1->Eid; $cnt = count($data1) - 1; ?>
                                    <tr id="first_tr{{ $no}}">
                                        <td class="text-center" style="font-size:12px;width: 200px;">
                                            <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" name="aspects[]" id="enviaspects{{$no}}" value="{{$dat1->enviaspects}}">
                                        </td>

                                        <td class="text-center" style="font-size:12px;width: 100px;">
                                            <select class="form-control ER" style="font-size: 13px;" id="ER{{ $no }}" name="er[]" onchange="changeSelectedColor(this)">
                                                <option style="font-size: 13px;" value="Emergency" {{ $dat1->ER == 'Emergency' ? 'selected' : ''}}>Emergency</option><br>
                                                <option style="font-size: 13px;" value="Non Emergency" {{ $dat1->ER == 'Non Emergency' ? 'selected' : ''}}>Non Emergency</option><br>
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" class="form-control" name="potential[]" id="potential{{ $no }}" style="font-size:12px;" value="{{ $dat1->potential }}">
                                        </td>

                                        <td>
                                            <textarea class="form-control" cols="70" name="details[]" id="details{{ $no }}" rows="5" style="font-size:13px;">{{ $dat1->details }}</textarea>
                                        </td>

                                        <td class="text-center" style="font-size:12px;width: 200px;">
                                            <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" readonly value="{{$dat1->HS}}">
                                        </td>
                                        <td class="text-center" style="font-size:12px;width: 200px;">
                                            <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" name="control[]" value="{{$dat1->control}}">
                                        </td>
                                        @if($no + 1 === 1)
                                        <td rowspan="2">
                                            <button type="button" style="font-size:12px;" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add Aspect">
                                                <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>
                                        @else
                                        <td rowspan="2">
                                            <button type="button" name="remove" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove" onclick="remove_tr('{{ $no }}')">
                                                <i class="fa fa-fw remove-tr fa-times text-danger"></i>
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    <tr id="second_tr{{ $no}}">
                                        <td class="text-center" style="font-size:12px;">
                                            <select class="form-control A" style="font-size:13px" id="A{{ $no}}" name="A[]" onchange="changeASelectedColor(this)">
                                                <option style="font-size:13px" value="" selected="selected">Probability</option>
                                                @foreach($enviaspect_probabilities as $ep)
                                                <option value="{{ $ep->weightage }}" {{ $dat1->A == $ep->weightage ? 'selected' : '' }}> {{ showDescriptions($ep->description) }} [{{ $ep->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td class="text-center" style="font-size:12px;">
                                            <select class="form-control B" style="font-size:13px;" id="B{{ $no}}" name="B[]" onchange="changeBSelectedColor(this)">
                                                <option style="font-size:13px" value="">Consequence</option>
                                                @foreach($enviaspect_consequences as $ec)
                                                <option value="{{ $ec->weightage }}" {{ $dat1->B == $ec->weightage ? 'selected' : '' }}> {{ showDescriptions($ec->description) }} [{{ $ec->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center" style="font-size:12px;">
                                            <select class="form-control C" style="font-size: 13px;" id="C{{ $no}}" name="C[]" onchange="changeCSelectedColor(this)">
                                                <option style="font-size:13px" value="">Regulatory</option>
                                                @foreach($enviaspect_regulatory_requirements as $er)
                                                <option value="{{ $er->weightage }}" {{ $dat1->C == $er->weightage ? 'selected' : '' }}>{{ showDescriptions($er->description) }} [{{ $er->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center" style="font-size:12px;">
                                            <select class="form-control D" style="font-size: 13px;" id="D{{ $no}}" name="D[]" onchange="changeDSelectedColor(this)">
                                                <option style="font-size:13px" value="" selected="selected">Quantity</option>
                                                @foreach($enviaspect_quantities as $eq)
                                                <option value="{{ $eq->weightage }}" {{ $dat1->D == $eq->weightage ? 'selected' : '' }}>{{ showDescriptions($eq->description) }} [{{ $eq->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center" style="font-size:12px;" colspan="2">
                                            <select class="form-control E" style="font-size: 13px;" id="E{{ $no}}" name="E[]" onchange="changeESelectedColor(this)">
                                                <option style="font-size:13px" value="" selected="selected">Control&nbsp Measure</option>
                                                @foreach($enviaspect_control_measures as $ecm)
                                                <option value="{{ $ecm->weightage }}" {{ $dat1->E == $ecm->weightage ? 'selected' : '' }}>{{ showDescriptions($ecm->description) }} [{{ $ecm->weightage }}]</option>
                                                @endforeach
                                            </select>
                                        </td>

                                    </tr>
                                    <div class="form-group">

                                        <input type="hidden" name="posted" value="{{ $dat1->Eid}}">
                                        <input type="hidden" name="slno[]" value="{{ $dat1->slno}}">
                                    </div>
                                    @php
                                    $no++;
                                    @endphp
                                    @endforeach
                                </table>
                            </div>

                            <div class="col-sm-12" style="padding-bottom:10px;">
                                @php
                                $m = 0;
                                $items_per_row = 4;
                                @endphp
                                <input type="hidden" id="count_val" value="{{ $cnt }}">
                                @foreach($environmental_impact_details as $item)
                                <button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:4px;margin-left:10px;margin-bottom:10px;" onclick="return passvalue('{{$item->id}}');">{{$item->title}}</button>

                                @php
                                $m++;
                                @endphp

                                @endforeach

                            </div>

                            @endif

                            <div class="form-group row" style="font-size: 13px;">
                                <label class="col-sm-4 col-form-label">Please choose the review status</label>
                                <div class="form-check form-check-inline ml-3">
                                    <input class="form-check-input" type="radio" id="accept" name="question" value="2" checked>
                                    <label class="form-check-label" for="accept">Accept</label>
                                </div>
                                <div class="form-check form-check-inline ml-3">
                                    <input class="form-check-input" type="radio" id="need_improvement" name="question" value="1">
                                    <label class="form-check-label" for="need_improvement">Need Improvement</label>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;" id="b" for="proposal">Reviewer Proposal</label>
                                <div class="col-sm-8">
                                    <input type="text" name="proposal" style="font-size: 13px;" id="proposal" class="form-control" value="{{$dat->proposechanges}}">
                                </div>
                            </div>



                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary" style="font-size: 13px;margin-left:83%" onclick="return confirm('Do you want to proceed?')">Submit</button>
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

</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>
    let select_color = '{{ $select_color }}';
    let first_row_HS_value = '{{ $first_row_HS_value }}';
    let counter = Number('{{ count($data1 )}}') - 1;
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function() {
        $("#otherunit").hide();
    });

</script>
<script>
    $("#hod_method").change(function() {
        if ($("#hod_method option:selected").val() == "4") {
            $('#risk_response').show();
            $('#otherunit').show();
        } else {
            $('#risk_response').hide();
            $('#otherunit').hide();
        }
    });

</script>
<script>
    function validation() {
        if (document.getElementById('question').value == 'none') {
            alert('Please select a review outcome option');
            document.getElementById('question').focus();
            return false;
        }
        // always return false to prevent form from submission.
    }

    function changeSelectedColor(self) {
        if (self.value !== '') {
            $(self).css('background-color', select_color).css('boder-color', select_color);
        } else {
            $(self).css('background-color', '#fff').css('border-color', '#d5dce1');;
        }
    }

    function changeItemColor(item) {

        if ($(item).val() !== '') {
            $(item).css('background-color', select_color).css('boder-color', select_color);
        } else {
            $(item).css('background-color', '#fff').css('border-color', '#d5dce1');;
        }

    }

    function changeASelectedColor(self) {
        changeSelectedColor(self);
    }

    function changeBSelectedColor(self) {
        changeSelectedColor(self);
    }

    function changeCSelectedColor(self) {
        changeSelectedColor(self);
    }

    function changeDSelectedColor(self) {
        changeSelectedColor(self);
    }

    function changeESelectedColor(self) {
        changeSelectedColor(self);
    }


    let count = '{{ count($data1) }}';

    for (let i = 0; i < count; i++) {
        changeItemColor('#ER' + i);
        changeItemColor('#A' + i);
        changeItemColor('#B' + i);
        changeItemColor('#C' + i);
        changeItemColor('#D' + i);
        changeItemColor('#E' + i);
    }

</script>

<script type="text/javascript">
    var i = counter;
    let enviaspect_probabilities_text = '{!! $enviaspect_probabilities_text !!}';
    let enviaspect_consequences_text = '{!! $enviaspect_consequences_text !!}';
    let enviaspect_regulatory_requirements_text = '{!! $enviaspect_regulatory_requirements_text !!}';
    let enviaspect_quantities_text = '{!! $enviaspect_quantities_text !!}';
    let enviaspect_control_measures_text = '{!! $enviaspect_control_measures_text !!}';


    $("#add").click(function() {
        ++i;
        $('#count_val').val(i);
        $("#dynamicTable").append(`<tr id="first_tr${i}">
        <td><input type="text" name="aspects[]" id="enviaspects${i}" style="font-size:13px" class="form-control" /></td>
        <td><select name="er[]" class="form-control ER" style="font-size:13px" id="ER${i}" onchange="changeSelectedColor(this)" required>
                <option style="font-size:13px" value="" selected="selected">Select </option>
                <option value="Emergency">Emergency</option>
                <option value="Non Emergency">Non Emergency</option>
            </select>
        </td>
        <td><input type="text" class="form-control" name="potential[]" id="potential${i}" style="font-size:12px;"></td>
        <td><textarea class="form-control" cols="70" name="details[]" id="details${i}" rows="5" style="font-size:13px;"></textarea></td>
         <td>
             <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" value="${first_row_HS_value}" readonly>
         </td>
         <td>
             <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" name="control[]">
         </td>

        <td rowspan="2"><button type="button" name="remove" id="remove${i}" style="font-size:13px" class="btn btn-sm btn-light text-danger" data-toggle="tooltip" title="Remove Aspect" onclick="remove_tr(${i})"><i class="fa fa-fw remove-tr fa-times"></i></button></td>
        </tr>
        <tr id="second_tr${i}">
            <td><select name="A[]" class="form-control A" style="font-size:13px" id="A${i}" onchange="changeASelectedColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Probability</option>` +
            enviaspect_probabilities_text +
            `</select></td>
            <td><select name="B[]" class="form-control B" style="font-size:13px" id="B${i}" onchange="changeBSelectedColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Consequence</option>` +
            enviaspect_consequences_text +
            `</select></td>
            <td><select name="C[]" class="form-control C" style="font-size:13px" id="C${i}" onchange="changeCSelectedColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Regulatory</option>` +
            enviaspect_regulatory_requirements_text +
            `</select></td>
            <td><select name="D[]" class="form-control D" style="font-size:13px" id="D${i}" onchange="changeDSelectedColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Quantity</option>` +
            enviaspect_quantities_text +
            `</select>
            </td>
            <td colspan="2">
                <select name="E[]" class="form-control E" style="font-size:13px" id="E${i}" onchange="changeESelectedColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Control Measure</option>` +
            enviaspect_control_measures_text +
            `</select></td>
        </tr>
        `);

    });

    function remove_tr(i) {
        //console.log(i);
        $('#count_val').val(i - 1);
        $('#first_tr' + i).remove();
        $('#second_tr' + i).remove();
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
                    $('#potential' + count_val).val(impacthtmldatas);
                    $('#details' + count_val).html(detailshtmldatas);
                    $('#enviaspects' + count_val).val(titlehtmldatas);
                }

            }

        });
    }

</script>


</html>
@endsection
