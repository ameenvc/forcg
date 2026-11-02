@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">
   
    <body>
        
                <!-- Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">
                          
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Calibration Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Edit Details</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/calibrationmgmt">Back</a>
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
                                    <form class="mb-5" action="{{url('user/calibration/update2')}}" onsubmit="return validation()" method="POST">
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
         @endforeach
         @endif
         
                
                 @if(count($data)>0)
                 <table class="table table-responsive table-bordered table-striped table-vcenter " style="width:940px" id="dynamicTable">
              
                     <thead style="background-color:#FFFFFF">
                         <tr>

                       {{--<th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">&nbsp&nbsp&nbspid&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>--}}
                                 <th class="text-center" style="font-size:13px;width: 5px;text-transform:capitalize">Instrument No</th>
                         {{-- <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Description</th> --}}
                                <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Department</th>
                               
                               
                               
                                <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Holder</th>
                                <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Capacity</th>
                                <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Type</th>
                                <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Procedure</th>
                               
                                <th class="text-center" style="font-size:13px;width: 10px;text-transform:capitalize">Frequency</th>
                              
                                
                             
                             
                         </tr>
                     </thead>
                     
                             
                     <tbody>
                         @foreach($data as $dat1)
                         <tr> 
                     

                             <td>
                             <input  type="text" style="font-size: 13px;"  class="form-control" id="id[]" name="id[]"   value="{{$dat1->Instrument_no}}" readonly> 
                                
                             


                                </td> 

                                <td> 
                                <!-- <input  type="text" style="font-size: 13px;"  style="width: 600px;" class="form-control" id="Department[]" name="Department[]"  value="{{$dat1->Department}}">    -->
                                <select class="form-control" id="select_type" style="font-size: 13px;width: 150px;"  name = "Department[]"  >
                                        <option value=" {{$dat1->Department}}"> {{ $dat1->Department }}</option><br>
                                        @if(count($department)> 0 )
                                            @foreach($department as $d)                                  
                                                @if($d->departmentname != $dat1->Department)
                                                    <option style="font-size: 13px;"  value=" {{$d->departmentname}}"  > {{ $d->departmentname }}</option><br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </td>   
                                <td class="text-center "style="font-size:13px;width: 70px;">
                                <input  type="text" style="font-size: 13px;"  style="width: 600px;" class="form-control" id="Holder[]" name="Holder[]"   value="{{$dat1->Holder}}"> 
                                </td>  
                                <td class="text-center "style="font-size:13px;width: 70px;">
                                <input  type="text" style="font-size: 13px;"  style="width: 600px;" class="form-control" id="Capacity[]" name="Capacity[]"   value="{{$dat1->Capacity}}"> 
                                </td>  
                              
                                
                                <td class="text-center "style="font-size:13px;width: 70px;">
                                <!-- <input  type="text" style="font-size: 13px;"  style="width: 600px;" class="form-control" id="Type[]" name="Type[]"   value="{{$dat1->Type}}">  -->
                               
                                <select class="form-control" id="Type[]" style="font-size: 13px;width:100px"  name = "Type[]"  >
                                <option value="{{$dat1->Type}}"> {{ $dat1->Type}}</option><br>
                              
                                        <option style="font-size: 13px;"  value=" {{'Internal'}}"> Internal</option><br>                                       
                                        <option style="font-size: 13px;"  value=" {{'External'}}"  >External</option><br>
                                       
                                    </select>
                               
                                </td> 


                                <td class="text-center "style="font-size:13px;width: 70px;">
                                <input  type="text" style="font-size: 13px;"  style="width: 600px;" class="form-control" id="Calib_procedure[]" name="Calib_procedure[]"   value="{{$dat1->Calib_procedure}}"> 
                                </td> 
                               
                               
                                <td class="text-center "style="font-size:13px;width: 70px;">
                                <!-- <input  type="text" style="font-size: 13px;"  style="width: 600px;" class="form-control" id="Frequency[]" name="Frequency[]" value="{{$dat1->Frequency}}">  -->
                                <select class="form-control" id="Frequency[]" style="font-size: 13px;width:100px"  name = "Frequency[]"  >
                                <option value="{{$dat1->Frequency}}"> {{ $dat1->Frequency}}</option><br>
                              
                                @if(count($freq)> 0 )
                                            @foreach($freq as $d)                                  
                                                @if($d->frequency != $dat1->Frequency)
                                                    <option style="font-size: 13px;"  value=" {{$d->frequency}}"  > {{ $d->frequency }}</option><br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                               
                               
                                </td> 
                              
                              <div class="form-group">
                               
                                <input type="hidden" name="sno[]" value="{{ $dat1->Instrument_no}}">  
                                 
                                 
                               
                                   
                               </div>
                         </tr> 
                        
                         @endforeach                           
                     </tbody>
                     
                 </table>
                 @endif

                                      

                                        
                                            <div style="text-align:center;">
                                                <button type="submit" class="btn btn-primary"  style="font-size:13px;margin-left:70%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed?')">Submit</button>
                                                <button for="reset" type="reset" class="btn btn-primary"style="font-size:13px;margin-left:88%;margin-top:-60px">Reset</button>
                                          
                                           
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/calibrationmgmt">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                 <br> Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
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
                           if ($("#hod_method option:selected").val() == 2) {
                           //   $('#shipping_carrier').prop('disabled', 'disabled');
                           //   $('#shipping_carrier').val(1);
                         
                             $('#otherunit').hide();
                           } 
                           
                           else if ($("#hod_method option:selected").val() == 3) {
                           //   $('#shipping_carrier').prop('disabled', 'disabled');
                           //   $('#shipping_carrier').val(1);
                          
                             $('#otherunit').hide();
                           }else {
                           //   $('#shipping_carrier').prop('disabled', false);
                           $('#risk_response').show();
                             $('#otherunit').show();
                           }
                         });
                      
   </script> 

<script>
function validation() {


    if(document.getElementById('department').value == 'none'){
            alert('Choose a Department'); 
            document.getElementById('department').focus();      
            return false;
         }
        
        else if(document.getElementById('span').value == 'none'){
            alert('Choose Program Span'); 
            document.getElementById('span').focus();      
            return false;
         }
        else if(document.getElementById('responsibility').value == 'none'){
            alert('Choose Responsibility'); 
            document.getElementById('responsibility').focus();      
            return false;
         }
         else if(document.getElementById('frequency').value == 'none'){
            alert('Choose Frequency'); 
            document.getElementById('frequency').focus();      
            return false;
         }

  // always return false to prevent form from submission.
}
</script>
</html>
@endsection
