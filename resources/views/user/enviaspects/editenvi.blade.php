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
                                        <a class="link-fx" href="">Edit</a>
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
                    <div class="block block-rounded">

                        <div class="block-content block-content-full">
                            <div class="row">

                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <form class="mb-5" action="{{url('/user/enviaspects/editrequest_envi')}}" method="POST" onsubmit="return validation()">
                                        {{csrf_field()}}

                                        @if(count($errors) >0 )
                                    @foreach($errors->all() as $error)
                                        <div class='alert alert-warning'>
                                                {{ $error }}
                                        </div>
                                   @endforeach
                                @endif


<br>





@if(count($data)>0)

                 <table class="table table-responsive table-bordered table-striped table-vcenter " style="width:900px" id="dynamicTable">

                         <tr>
                             <th class="text-center" style="font-size:13px;width: 50px;text-transform:capitalize">Environmental&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Aspects&nbsp&nbsp</th>
                             <th class="text-center" style="font-size:13px;width: 40px;text-transform:capitalize">Emergency/ &nbsp&nbsp&nbsp&nbsp&nbsp&nbspNon&nbsp&nbspEmergency&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
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


                         @foreach($data as $dat1)
                         <?php $rr=$dat1->Eid;
                         //$slno[]=$dat1->slno;
                         //dd($slno); ?>
                         <tr>


                             <td  class="text-center "style="font-size:12px;width: 200px;">
                             <input style="font-size: 13px;"  type="text" style="width: 120px;" class="form-control" id="aspects[]" name="aspects[]"   value="{{$dat1->enviaspects}}">
                            </td>

                             <td  class="text-center "style="font-size:12px;width: 100px;">


                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "er[]"  >
                                <option value="{{$dat1->ER}}"> {{ $dat1->ER}}</option><br>

                                        <option style="font-size: 13px;"  value=" {{'Emergency'}}"> Emergency</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Non Emergency'}}">Non Emergency</option><br>

                                    </select>
                            </td>


                             <td  class="text-center "style="font-size:12px;width: 250px;">

                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "potential[]"  >
                                <option value="{{$dat1->potential}}"> {{ $dat1->potential}}</option><br>

                                        <option style="font-size: 13px;"  value=" {{'Resource Depletion'}}"> Resource Depletion</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Air Pollution'}}">Air Pollution</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Water Pollution'}}">Water Pollution</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Noise and Vibrational Effect'}}">Noise and Vibrational Effect</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Land Contamination'}}">Land Contamination</option><br>
                                        <option style="font-size: 13px;"  value=" {{'Ozone Depletion'}}">Ozone Depletion</option><br>
                                    </select>
                           </td>
                             <td  class="text-center "style="font-size:12px;width: 200px;">

                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "A[]"  >
                                <option value="{{$dat1->A}}"> {{ $dat1->A}}</option><br>

                                        <option style="font-size: 13px;"  value=" {{'1'}}"> 1</option><br>
                                        <option style="font-size: 13px;"  value=" {{'2'}}">2</option><br>
                                        <option style="font-size: 13px;"  value=" {{'3'}}">3</option><br>
                                        <option style="font-size: 13px;"  value=" {{'4'}}">4</option><br>

                                    </select>

                                    </td>






                             <td  class="text-center "style="font-size:12px;width: 200px;">

                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "B[]"  >
                                <option value="{{$dat1->B}}"> {{ $dat1->B}}</option><br>


                                <option style="font-size: 13px;"  value=" {{'0.1'}}"> 0.1</option><br>
                                        <option style="font-size: 13px;"  value=" {{'1'}}">1</option><br>
                                        <option style="font-size: 13px;"  value=" {{'3'}}">3</option><br>
                                        <option style="font-size: 13px;"  value=" {{'5'}}">5</option><br>
                                        <option style="font-size: 13px;"  value=" {{'10'}}">10</option><br>

                                    </select>




                             </td>
                             <td  class="text-center "style="font-size:12px;width: 200px;">


                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "C[]"  >
                                <option value="{{$dat1->C}}"> {{ $dat1->C}}</option><br>



                                        <option style="font-size: 13px;"  value=" {{'1'}}">1</option><br>
                                        <option style="font-size: 13px;"  value=" {{'2'}}">2</option><br>

                                    </select>



                           </td>
                             <td  class="text-center "style="font-size:12px;width: 200px;">

                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "D[]"  >
                                <option value="{{$dat1->D}}"> {{ $dat1->D}}</option><br>


                                <option style="font-size: 13px;"  value=" {{'1'}}"> 1</option><br>
                                        <option style="font-size: 13px;"  value=" {{'2'}}">2</option><br>
                                        <option style="font-size: 13px;"  value=" {{'3'}}">3</option><br>
                                        <option style="font-size: 13px;"  value=" {{'4'}}">4</option><br>
                                        <option style="font-size: 13px;"  value=" {{'5'}}">5</option><br>


                                    </select>

                            </td>
                             <td  class="text-center "style="font-size:12px;width: 200px;">
                             <select class="form-control" id="span" style="font-size: 13px;width:100px"  name = "E[]"  >
                                <option value="{{$dat1->E}}"> {{ $dat1->E}}</option><br>
                             <option style="font-size: 13px;"  value=" {{'1'}}">1</option><br>

                             <option style="font-size: 13px;"  value=" {{'2'}}">2</option><br>

                             <option style="font-size: 13px;"  value=" {{'3'}}">3</option><br>
                             </select>
                             </td>


                              <td  class="text-center "style="font-size:12px;width: 200px;">


                                 <input style="font-size: 13px;"  type="text" style="width: 120px;" class="form-control" id="HS[]" name="HS[]" readonly  value="{{$dat1->HS}}">

                             </td>
                             <td  class="text-center "style="font-size:12px;width: 200px;">

                             <input style="font-size: 13px;"  type="text" style="width: 120px;" class="form-control" id="control[]" name="control[]"   value="{{$dat1->control}}">

                           </td>


                         </tr>
                         <div class="form-group">

                                 <input type="hidden" name="posted" value="{{ $dat1->Eid}}">
                                 <input type="hidden" name="slno[]" value="{{ $dat1->slno}}">
                               </div>
                         @endforeach
                     </table>
                     @endif




                <button for="submittl" type="submit" onclick=" return confirm('Do you want to proceed?')" class="btn btn-primary"style="font-size:13px;margin-left:800px;">Submit</button>

                     </div>


                                    <!-- END Form Horizontal - Default Style -->

                                    </form>

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
$(document).ready(function(){
  $("#otherunit").hide();
  
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


    if(document.getElementById('question').value == 'none'){
            alert('Please select a review outcome option');
            document.getElementById('question').focus();
            return false;
         }



  // always return false to prevent form from submission.
}
</script>

</html>
@endsection
