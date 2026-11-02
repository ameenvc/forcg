@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
<head>
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
</head>


<body>

    <!-- Page Content -->
    <div class="content">


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
                        Add Aspect
                    </li>
                </ol>
            </nav>


            <div class="block-content block-content-full">
                <!-- Form Horizontal - Default Style -->
                <form class="mb-5" action="{{url('/user/enviaspects/adddcrr')}}" method="POST" onsubmit="return validation()">
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


                        @foreach($data as $dat)


                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="objid">Environmental activity</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;" id="objid" name="objid" readonly value="{{$dat->activity}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="department">Department</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" style="font-size: 13px;" id="department" name="department" readonly value="{{$dat->dept ? $dat->dept->departmentname : '-'}}">
                            </div>
                        </div>
                        @if($dat->division)
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size:13px;" for="division">Division</label>
                            <div class="col-sm-8">
                                <div class="form-group" style="color:#666666">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->division->name}}" readonly>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($dat->area)
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                            <div class="col-sm-8">
                                <div class="form-group" style="color:#666666">
                                    <input type="text" class="form-control" style="font-size: 13px;" value="{{$dat->area->name}}" readonly>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="riskdec">Location</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="location" style="font-size: 13px;" name="location" readonly value="{{$dat->location}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="riskdri">Team</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="team" id="team" style="font-size: 13px;" readonly value="{{$dat->team}}">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="riskdri">Date</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" style="font-size: 13px;" name="date" id="date" readonly value="{{$dat->date}}">
                            </div>

                            <input class="form-control" type="hidden" style="font-size: 13px;" name="id" id="id" readonly value="{{$dat->Eid}}">
                        </div>

                        @endforeach

                        <br>

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped table-vcenter" id="dynamicTable">
                                <thead style="background-color:#FFFFFF;">

                                    <tr>
                                        <th class="text-center" style="font-size:12px;padding-right:20px;padding-left:20px;text-transform:capitalize">Environmental&nbsp&nbspAspects</th>
                                        <th class="text-center" style="font-size:12px;padding-right:20px;padding-left:20px;text-transform:capitalize">Emergency&nbsp/&nbspNon&nbspEmergency</th>
                                        <th class="text-center" style="font-size:12px;padding-right:80px;padding-left:80px;text-transform:capitalize">Impact</th>
                                        <th class="text-center" style="font-size:12px;padding-right:250px;padding-left:250px;text-transform:capitalize">Details</th>
                                        {{-- <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Occurrence Probability (A)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Nature of Consequence (B)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Legal/ Regulatory Requirement (C)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">&nbsp&nbspQuantity&nbsp&nbsp (D)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Control&nbsp Measure&nbsp&nbsp&nbsp (E)</th> --}}
                                        <th class="text-center" style="font-size:13px;padding-left:20px;padding-right:20px;text-transform:capitalize">Add&nbspAspect</th>
                                    </tr>
                                </thead>
                                @foreach($data2 as $dat2)
                                <?php  $a=$dat2->slno; ?>

                                <tr>
                                    <td class="text-center " style="font-size:12px;">
                                        <input style="font-size: 13px;" type="text" class="form-control" readonly value="{{$dat2->enviaspects}}">
                                    </td>


                                    <td class="text-center " style="font-size:12px;">
                                        <input style="font-size: 13px;" type="text" class="form-control" readonly value="{{$dat2->ER}}">
                                    </td>


                                    <td class="text-center " style="font-size:12px;width: 250px;">
                                        <input style="font-size: 13px;" type="text" class="form-control" readonly value="{{$dat2->potential}}">
                                    </td>
                                    <td>
                                        <textarea class="form-control" cols="70" rows="5" style="font-size:13px;" readonly>{{ $dat2->details }}</textarea>
                                    </td>
                                    <td rowspan="2"></td>
                                </tr>
                                <tr>

                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" readonly value="{{$dat2->A}}">
                                    </td>


                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" readonly value="{{$dat2->B}}">
                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" readonly value="{{$dat2->C}}">
                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <div class="row">
                                            <div class="col">
                                                <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" readonly value="{{$dat2->D}}">
                                            </div>
                                            <div class="col">
                                                <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" readonly value="{{$dat2->E}}">
                                            </div>
                                        </div>

                                    </td>
                                    {{-- <td class="text-center " style="font-size:12px;width: 200px;">

                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->E}}">
                                    </td> --}}
                                </tr>


                                <div class="form-group">
                                    @php
                                    $Eid=$dat2->Eid;
                                    $slno=$dat2->slno;
                                    @endphp
                                    <input type="hidden" name="id" value="{{$Eid}}">
                                </div>
                                @endforeach
                                <tr id="first_tr0">
                                    <td><input type="text" name="doc[0][enviaspects]" id="enviaspects0" class="form-control" required style="font-size: 13px;" /></td>

                                    <td><select name="doc[0][ER]" id="ER" class="form-control" style="font-size: 13px;">
                                            <option style="font-size:13px" value="" selected="selected">Select </option>
                                            <option value="Emergency">Emergency</option>
                                            <option value="Non Emergency">Non Emergency</option>
                                        </select></td>
                                    <td><input type="text" name="doc[0][potential]" id="potential0" class="form-control" required style="font-size: 13px;" /></td>
                                    <td>
                                        <textarea class="form-control" cols="70" id="details0" name="doc[0][details]" rows="5" style="font-size:13px;"></textarea>
                                    </td>
                                    <td rowspan="2" class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light" style="font-size:13px" data-toggle="tooltip" title="Add Aspect">
                                            <i class="fa fa-fw fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr id="second_tr0">
                                    <td><select name="doc[0][A]" id="A" class="form-control" style="font-size: 13px;">
                                            <option style="font-size:13px" value="" selected="selected">Probability</option>
                                            @foreach($enviaspect_probabilities as $ep)
                                            <option value="{{ $ep->weightage }}"> {{ showDescriptions($ep->description) }} [{{ $ep->weightage }}]</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><select name="doc[0][B]" id="B" class="form-control" style="font-size: 13px;">
                                            <option style="font-size:13px" value="" selected="selected">Consequence</option>
                                            @foreach($enviaspect_consequences as $ec)
                                            <option value="{{ $ec->weightage }}">{{ showDescriptions($ec->description) }} [{{ $ec->weightage }}]</option>
                                            @endforeach
                                        </select></td>
                                    <td><select name="doc[0][C]" id="C" class="form-control" style="font-size: 13px;">
                                            <option style="font-size:13px" value="" selected="selected">Regulatory</option>
                                            @foreach($enviaspect_regulatory_requirements as $er)
                                            <option value="{{ $er->weightage }}">{{ showDescriptions($er->description) }} [{{ $er->weightage }}]</option>
                                            @endforeach
                                        </select></td>

                                    <td>
                                        <div class="row">
                                            <div class="col">
                                                <select name="doc[0][D]" id="D" class="form-control" style="font-size: 13px;">
                                                    <option style="font-size:13px" value="" selected="selected">&nbsp&nbspQuantity&nbsp&nbsp</option>
                                                    @foreach($enviaspect_quantities as $eq)
                                                    <option value="{{ $eq->weightage }}">{{ showDescriptions($eq->description) }} [{{ $eq->weightage }}]</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select name="doc[0][E]" id="E" class="form-control" style="font-size: 13px;">
                                                    <option style="font-size:13px" value="" selected="selected">Control&nbsp Measure&nbsp&nbsp&nbsp</option>
                                                    @foreach($enviaspect_control_measures as $ecm)
                                                    <option value="{{ $ecm->weightage }}">{{ showDescriptions($ecm->description) }} [{{ $ecm->weightage }}]</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </table>

                        </div>

                        <div class="col-sm-12" style="padding-bottom:10px;">
                            @php
                            $m = 0;
                            $items_per_row = 4;
                            @endphp
                            <input type="hidden" id="count_val" value="0">
                            @foreach($environmental_impact_details as $item)
                            <button type="button" class="btn btn-sm btn-outline-primary" style="margin-right:4px;margin-left:10px;margin-bottom:10px;" onclick="return passvalue('{{$item->id}}');">{{$item->title}}</button>

                            @php
                            $m++;
                            @endphp

                            @endforeach

                        </div>



                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size: 13px;margin-left:83%" onclick="return confirm('Do you want to proceed?')">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END Form Horizontal - Default Style -->
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
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function() {
        $("#otherunit").hide();
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
        $("#dynamicTable").append(`<tr id="first_tr${i}">
            <td><input type="text" name="doc[${i}][enviaspects]" id="enviaspects${i}" style="font-size:13px" class="form-control" /></td>
            <td>
                <select name="doc[${i}][ER]" class="form-control" style="font-size:13px" onchange="changeERColor(this)">
                    <option style="font-size:13px" value="" selected="selected">Select </option>
                    <option value="Emergency">Emergency</option>
                    <option value="Non Emergency">Non Emergency</option>
                </select>
            </td>
            <td><input name="doc[${i}][potential]" id="potential${i}" type="text" style="font-size: 13px;" class="form-control"></td>
            <td>
                <textarea class="form-control" cols="70" id="details${i}" name="doc[${i}][details]"" rows="5" style="font-size:13px;"></textarea>
            </td>
            <td rowspan="2" class="text-center"><button type="button" name="add" id="add" style="font-size:13px" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove Aspect" onclick="remove_tr(${i})"><i class="fa fa-fw remove-tr fa-times"></i></button></td>
            </tr>
            <tr id="second_tr${i}">
            <td><select name="doc[${i}][A]" class="form-control" style="font-size:13px" onchange="changeAColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Probability</option>` +
            enviaspect_probabilities_text +
            `
                </select></td>
            <td><select name="doc[${i}][B]" class="form-control" style="font-size:13px" onchange="changeBColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Consequence</option>` +
            enviaspect_consequences_text +
            `
                </select></td>
            <td><select name="doc[${i}][C]" class="form-control" style="font-size:13px" onchange="changeCColor(this)" required>
                    <option style="font-size:13px" value="" selected="selected">Regulatory</option>` +
            enviaspect_regulatory_requirements_text +
            `
                </select></td>
            <td>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <select name="doc[${i}][D]" class="form-control" style="font-size:13px" onchange="changeDColor(this)" required>
                            <option style="font-size:13px" value="" selected="selected">Quantity</option>` +
            enviaspect_quantities_text +
            `
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <select name="doc[${i}][E]" class="form-control" style="font-size:13px" onchange="changeEColor(this)" required>
                            <option style="font-size:13px" value="" selected="selected">Control Measure</option>` +
            enviaspect_control_measures_text +
            `
                        </select>
                    </div>

                </div>
            </td>

            </tr>`);

    });

    function remove_tr(i) {
        $('#count_val').val(i - 1);
        $('#first_tr' + i).remove();
        $('#second_tr' + i).remove();
    }

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
    }

    function changeAddedRowSelectedColor(self) {
        if (self.value !== '') {
            $(self).css('background-color', select_color).css('boder-color', select_color);
        } else {
            $(self).css('background-color', '#fff').css('border-color', '#d5dce1');;
        }
    }

    function changeERColor(self) {
        changeAddedRowSelectedColor(self);
    }

    function changeAColor(self) {
        changeAddedRowSelectedColor(self);
    }

    function changeBColor(self) {
        changeAddedRowSelectedColor(self);
    }

    function changeCColor(self) {
        changeAddedRowSelectedColor(self);
    }

    function changeDColor(self) {
        changeAddedRowSelectedColor(self);
    }

    function changeEColor(self) {
        changeAddedRowSelectedColor(self);
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
                    //console.log(impacthtmldatas, detailshtmldatas, titlehtmldatas);
                    $('#potential' + count_val).val(impacthtmldatas);
                    $('#details' + count_val).html(detailshtmldatas);
                    $('#enviaspects' + count_val).val(titlehtmldatas);
                }

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

</html>
@endsection
