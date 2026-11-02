@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

<body>

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Environmental Aspect</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Resubmit</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/enviaspects/listofaspects/{{Session::get('envidept')}}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">


        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">

            <div class="block-content block-content-full">
                <div class="row">

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/enviaspects/resubmit')}}" method="POST" onsubmit="return validation()">
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
                            <input type="hidden" value="{{ $dat->department }}" id="department_val"/>
                            <input type="hidden" value="{{ $dat->area_id }}" id="area_id_val" />


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objid">Environmental activity</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="activity" name="activity" readonly value="{{$dat->activity}}">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="department">Department</label>
                                <div class="col-sm-12">

                                    <select class="form-control" id="department" selected={{$dep}} name="department" style="width: 100%;font-size:13px" placeholder={{$dep}} onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                        @if(count($departments)> 0)

                                        @foreach($departments as $value)
                                        <option value="{{$value->id}}" {{ $value->id == $dat->department ? 'selected' : '' }}> {{ $value->departmentname }}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>
                            <input type="hidden" id="division_val" value="{{ $dat->division_id }}" />
                            <input type="hidden" id="area_val" value="{{ $dat->area_id }}" />

                            <div class="form-group d-inline-block" style="width:49%;" id="division">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="division_select" name="division" style="font-size: 13px;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                        <option value="0" disabled>Choose Division</option></br>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group d-inline-block" style="width:49%;" id="area">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;" for="area">Sub Division</label>
                                <div class="col-sm-12" id="action_number" name="action">
                                    <select class="form-control" id="area_select" name="area" style="font-size: 13px;" placeholder="Sub Division">
                                        <option value="0" disabled>Choose Sub Division</option></br>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskdec">Location</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="location" style="font-size: 13px;" name="location" readonly value="{{$dat->location}}">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskdri">Team</label>
                                <div class="col-sm-12">

                                    <select class="form-control" id="team" value="{{$dat->team}}" name="team[]" style="font-size: 13px;" required="">
                                        @if(count($users)> 0 )
                                        <option selected> {{ $team }}</option>
                                        @foreach($users as $value)
                                        <option value="{{$value->name}}"> {{ $value->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskdri">Date</label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="date" name="date" data-week-start="1" data-autoclose="true" style="font-size: 13px;" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value={{ $dat->date}}>
                                </div>
                            </div>

                            @endforeach
                            @endif



                            <br>


                            @if(count($data1)>0)

                            <table class="table table-responsive table-bordered table-striped table-vcenter " id="dynamicTable">

                                <tr>
                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Environmental&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Aspects&nbsp&nbsp</th>
                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Emergency/ &nbsp&nbsp&nbsp&nbsp&nbsp&nbspNon&nbsp&nbspEmergency&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Potential &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEnvironmental&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Occurrence Probability (A)</th>
                                    <!-- <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Frequency of Detection (B)</th> -->
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Nature of Consequence (B)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Legal/ Regulatory Requirement (C)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">&nbsp&nbspQuantity&nbsp&nbsp (D)</th>
                                    <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Control&nbsp Measure&nbsp&nbsp&nbsp (E)</th>
                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Significance</th>

                                    <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">&nbsp&nbsp&nbsp&nbspInitial&nbsp&nbsp&nbsp&nbspControl, If Any Required?</th>
                                    {{-- <th></th> --}}

                                </tr>
                                @foreach($data1 as $dat1)
                                <?php $rr=$dat1->Eid;
                         //$slno[]=$dat1->slno;
                         //dd($slno); ?>
                                <tr>


                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="aspects[]" name="aspects[]" value="{{$dat1->enviaspects}}">
                                    </td>

                                    <td class="text-center " style="font-size:12px;width: 200px;">


                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="er[]">
                                            <option value="{{$dat1->ER}}"> {{ $dat1->ER}}</option><br>

                                            <option style="font-size: 13px;" value=" {{'Emergency'}}"> Emergency</option><br>
                                            <option style="font-size: 13px;" value=" {{'Non Emergency'}}">Non Emergency</option><br>

                                        </select>
                                    </td>


                                    <td class="text-center " style="font-size:12px;width: 250px;">

                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="potential[]">
                                            <option value="{{$dat1->potential}}"> {{ $dat1->potential}}</option><br>

                                            <option style="font-size: 13px;" value=" {{'Resource Depletion'}}"> Resource Depletion</option><br>
                                            <option style="font-size: 13px;" value=" {{'Air Pollution'}}">Air Pollution</option><br>
                                            <option style="font-size: 13px;" value=" {{'Water Pollution'}}">Water Pollution</option><br>
                                            <option style="font-size: 13px;" value=" {{'Noise and Vibrational Effect'}}">Noise and Vibrational Effect</option><br>
                                            <option style="font-size: 13px;" value=" {{'Land Contamination'}}">Land Contamination</option><br>
                                            <option style="font-size: 13px;" value=" {{'Ozone Depletion'}}">Ozone Depletion</option><br>
                                        </select>
                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="A[]">
                                            <option value="{{$dat1->A}}"> {{ $dat1->A}}</option><br>

                                            <option style="font-size: 13px;" value=" {{'1'}}"> 1</option><br>
                                            <option style="font-size: 13px;" value=" {{'2'}}">2</option><br>
                                            <option style="font-size: 13px;" value=" {{'3'}}">3</option><br>
                                            <option style="font-size: 13px;" value=" {{'4'}}">4</option><br>

                                        </select>

                                    </td>



                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="B[]">
                                            <option value="{{$dat1->B}}"> {{ $dat1->B}}</option><br>


                                            <option style="font-size: 13px;" value=" {{'0.1'}}"> 0.1</option><br>
                                            <option style="font-size: 13px;" value=" {{'1'}}">1</option><br>
                                            <option style="font-size: 13px;" value=" {{'3'}}">3</option><br>
                                            <option style="font-size: 13px;" value=" {{'5'}}">5</option><br>
                                            <option style="font-size: 13px;" value=" {{'10'}}">10</option><br>

                                        </select>

                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">


                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="C[]">
                                            <option value="{{$dat1->C}}"> {{ $dat1->C}}</option><br>



                                            <option style="font-size: 13px;" value=" {{'1'}}">1</option><br>
                                            <option style="font-size: 13px;" value=" {{'2'}}">2</option><br>

                                        </select>



                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="D[]">
                                            <option value="{{$dat1->D}}"> {{ $dat1->D}}</option><br>


                                            <option style="font-size: 13px;" value=" {{'1'}}"> 1</option><br>
                                            <option style="font-size: 13px;" value=" {{'2'}}">2</option><br>
                                            <option style="font-size: 13px;" value=" {{'3'}}">3</option><br>
                                            <option style="font-size: 13px;" value=" {{'4'}}">4</option><br>
                                            <option style="font-size: 13px;" value=" {{'5'}}">5</option><br>


                                        </select>

                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <select class="form-control" id="span" style="font-size: 13px;width:100px" name="E[]">
                                            <option value="{{$dat1->E}}"> {{ $dat1->E}}</option><br>
                                            <option style="font-size: 13px;" value=" {{'1'}}">1</option><br>

                                            <option style="font-size: 13px;" value=" {{'2'}}">2</option><br>

                                            <option style="font-size: 13px;" value=" {{'3'}}">3</option><br>
                                        </select>
                                    </td>


                                    <td class="text-center " style="font-size:12px;width: 200px;">


                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat1->HS}}">

                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="control[]" name="control[]" value="{{$dat1->control}}">

                                    </td>


                                </tr>
                                <div class="form-group">

                                    <input type="hidden" name="posted" value="{{ $dat1->Eid}}">
                                    <input type="hidden" name="slno[]" value="{{ $dat1->slno}}">
                                </div>
                                @endforeach
                            </table>
                            @endif






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

</script>

</html>
@endsection
@section('js_after')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = $('#department_val').val();
    let area_id = $('#area_id_val').val();
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#area');
    let area_select_selector = $('#area_select');
    let division_selector = $('#division');
    let division_select_selector = $('#division_select');
    let division_select = '';
    area_select_selector.val(area_id);
    area_selector.removeClass('d-none').addClass('d-inline-block');
    chooseDivision($('#department').val(), $('#division_val').val());

    function chooseDivision(id, division_id) {
        division_select_selector.empty();
        let get_areas_url = '{{ route("admin.departmentAreas.getAreas", ":id") }}';
        get_areas_url = get_areas_url.replace(':id', id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: get_areas_url
            , async: false
            , success: function(resp) {
                show_area = resp.data.length;
                area_data = resp.data;
            }
            , error: function(err) {
                console.log(err);
            }
        });

        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        let option_text1 = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , async: false
            , success: function(resp) {
                if (!resp.data.length) {
                    division_selector.removeClass('d-inline-block').addClass('d-none');
                    area_selector.removeClass('d-inline-block').addClass('d-none');
                    area_select_selector.val(0);
                    division_select_selector.val(0);
                } else {
                    if (show_area) {
                        area_selector.removeClass('d-none');
                        area_selector.addClass('d-inline-block');
                        for (var i = 0; i < area_data.length; i++) {
                            let if_selected = area_data[i].id === Number(area_id) ? 'selected' : '';
                            option_text1 += `<option value="${area_data[i].id}" ${if_selected}>${area_data[i].name}</option>`
                        }
                        area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text1);

                    } else {
                        area_selector.removeClass('d-inline-block');
                        area_selector.addClass('d-none');
                        area_select_selector.val(0);
                    }

                    division_selector.removeClass('d-none').addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id == Number(division_id) ? 'selected' : '';
                        //if (id != department) if_selected = '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
                    }
                    division_select_selector.empty().append(`<option value="0">Choose Division</option></br>` + option_text);
                    division_select = division_select_selector.val();
                }

            }
            , error: function(err) {
                console.log(err);
            }
        });

    }


    chooseArea(division_select, $('#area_val').val());

    function chooseArea(id, area_id) {
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
                if (!resp.data.length) {
                    area_selector.removeClass('d-inline-block');
                    area_selector.addClass('d-none');
                    area_select_selector.val(0);
                } else {
                    area_selector.removeClass('d-none');
                    area_selector.addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id === Number(area_id) ? 'selected' : '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
                    }
                    area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                    let area_select = area_select_selector.val();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

</script>


@endsection
