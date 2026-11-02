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
                            <a class="link-fx" href="">Delete Aspect</a>
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

                        @if(count($data)>0)

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
                                <input type="text" class="form-control" style="font-size: 13px;" id="department" name="department" readonly value="{{$dat->dept ? $dat->dept->departmentname : ''}}">
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
                        </div>

                        @endforeach
                        @endif

                        <br>


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
                                <th class="text-center" style="font-size:13px;width: 200px;text-transform:capitalize">Delete Aspect</th>
                                {{-- <th></th> --}}

                            </tr>
                            @foreach($data2 as $dat2)
                            <?php  $a=$dat2->slno; ?>

                            <tr>
                                <form class="mb-5" action="{{route('aspect.delete')}}" method="POST" onsubmit="return validation()">
                                    {{csrf_field()}}

                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="aspects[]" name="objid" readonly value="{{$dat2->enviaspects}}">
                                    </td>

                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="ER[]" name="objid" readonly value="{{$dat2->ER}}">


                                    </td>


                                    <td class="text-center " style="font-size:12px;width: 250px;">

                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->potential}}">

                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->A}}">

                                    </td>





                                    <td class="text-center " style="font-size:12px;width: 200px;">
                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->B}}">
                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->C}}">



                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">


                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->D}}">



                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly value="{{$dat2->E}}">

                                    </td>



                                    {{-- <td  class="text-center "style="font-size:12px;width: 200px;">


                                 <input style="font-size: 13px;"  type="text" style="width: 120px;" class="form-control" id="objid" name="objid" readonly  value="{{$dat1->HS}}">

                                    </td>
                                    <td class="text-center " style="font-size:12px;width: 200px;">

                                        <input style="font-size: 13px;" type="text" style="width: 120px;" class="form-control" id="control[]" name="control[]" value="{{$dat1->control}}">

                                    </td>
                                    --}}


                                    <td>
                                        <input type="hidden" name="slno" value="{{$dat2->slno}}" id="slid">
                                        <input type="hidden" name="Envid" value="{{$dat2->Eid}}" id="Eid">
                                        <button onclick="return confirm('Do you really want to delete environmental aspect {{$dat2->enviaspects}}')" href="" style="font-size:13px" class="btn btn-danger">Delete</button></td>
                                </form>
                            </tr>
                            <div class="form-group">
                                <?php
                    $Eid=$dat2->Eid;
                    $slno=$dat2->slno;
                    ?>
                                <input type="hidden" name="Pid" value="{{$Eid}}">
                            </div>
                            @endforeach

                        </table>
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

<script type="text/javascript">
    var i = 0;
    $("#add").click(function() {
        ++i;
        $("#dynamicTable").append('<tr><td><input type="text" style="font-size:13px" name="doc[' + i + '][enviaspects]"  class="form-control" /></td><td><select name="doc[' + i + '][ER]"  style="font-size:13px" class="form-control" ><option value ="0" selected = "selected">Select </option><option value="Emergency" >Emergency</option><option value="Non Emergency">Non Emergency</option></select></td><td><select name="doc[' + i + '][potential]" class="form-control" style="font-size:13px" ><option value ="0" selected = "selected">Select </option><option value="Resource Depletion" >Resource Depletion</option><option value="Air Pollution">Air Pollution</option><option value="Water Pollution">Water Pollution</option><option value="Noise and Vibrational Effect" >Noise and Vibrational Effect</option><option value="Land Contamination" >Land Contamination</option><option value="Ozone Depletion" >Ozone Depletion</option></select></td><td><select name="doc[' + i + '][A]" class="form-control" style="font-size:13px" ><option value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select></td><td><select name="doc[' + i + '][B]" class="form-control" style="font-size:13px"><option value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select></td><td><select name="doc[' + i + '][C]" class="form-control" style="font-size:13px" ><option value ="0" selected = "selected">Select </option><option value="0.1" >0.1</option><option value="1" >1</option><option value="3">3</option><option value="5">5</option><option value="10">10</option></select></td><td><select name="doc[' + i + '][D]" class="form-control" style="font-size:13px" ><option value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option></select></td><td><select name="doc[' + i + '][E]" class="form-control" style="font-size:13px" ><option value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option></select></td><td><select name="doc[' + i + '][F]" class="form-control" style="font-size:13px" ><option value ="0" selected = "selected">Select </option><option value="1" >1</option><option value="2">2</option><option value="3" >3</option></select></td><td><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" style="font-size:13px" title="Remove Aspect"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');


    });
    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });

</script>
<script>
    $("#hod_method").change(function() {
        console.log($("#hod_method option:selected").val());
        if ($("#hod_method option:selected").val() == "4") {
            //   $('#shipping_carrier').prop('disabled', 'disabled');
            //   $('#shipping_carrier').val(1);
            $('#risk_response').show();
            $('#otherunit').show();
        }

        //    else if ($("#hod_method option:selected").val() == 3) {
        //    //   $('#shipping_carrier').prop('disabled', 'disabled');
        //    //   $('#shipping_carrier').val(1);
        //    $('#risk_response').hide();
        //      $('#otherunit').hide();
        //    }
        else {
            //   $('#shipping_carrier').prop('disabled', false);
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
