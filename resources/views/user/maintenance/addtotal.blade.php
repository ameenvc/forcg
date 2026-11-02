@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<script>
    function validation() {


            if(document.getElementById('type').value == 0){
            alert('Please select equipment type'); 
            document.getElementById('type').focus();      
            return false;
         }
         if(document.getElementById('quest').value == 0){
            alert('Please select reading based or not'); 
            document.getElementById('quest').focus();      
            return false;
         }
         if(document.getElementById('question2').value == 0){
            alert('Please select testing frequency required or not'); 
            document.getElementById('question2').focus();      
            return false;
         }
         if(document.getElementById('question1').value == 0){
            alert('Please select preventive maintenance frequency required or not'); 
            document.getElementById('question1').focus();      
            return false;
         }
         //validation for required field
         if(document.getElementById('question2').value == "1"){
            if(document.getElementById('testfreq').value == 0){
            alert('Please select testing frequency'); 
            document.getElementById('testfreq').focus();      
            return false;
            }
         }
         if(document.getElementById('question2').value == "1"){
            if(document.getElementById('testby').value == 0){
            alert('Please select tested by'); 
            document.getElementById('testby').focus();      
            return false;
            }
         }
         if(document.getElementById('question2').value == "1"){
            if(document.getElementById('lasttestdate').value == 0){
            alert('Please select last test date'); 
            document.getElementById('lasttestdate').focus();      
            return false;
            }
         }
         if(document.getElementById('question2').value == "1"){
            if(document.getElementById('testschedule').value == 0){
            alert('Please select next test schedule'); 
            document.getElementById('testschedule').focus();      
            return false;
            }
         }
        
         if(document.getElementById('question2').value == "1"){
            if(document.getElementById('testcondition').value == 0){
            alert('Please select equipment condition'); 
            document.getElementById('testcondition').focus();      
            return false;
            }
         }
         if(document.getElementById('question1').value == "1"){
            if(document.getElementById('freq').value == 0){
            alert('Please select Preventive maintenance frequency '); 
            document.getElementById('freq').focus();      
            return false;
            }
         }
         if(document.getElementById('question1').value == "1"){
            if(document.getElementById('pmby').value == 0){
            alert('Please select PM by'); 
            document.getElementById('pmby').focus();      
            return false;
            }
         }
         if(document.getElementById('question1').value == "1"){
            if(document.getElementById('lastdate').value == 0){
            alert('Please select last PM date '); 
            document.getElementById('lastdate').focus();      
            return false;
            }
         }
         if(document.getElementById('question1').value == "1"){
            if(document.getElementById('schedule').value == 0){
            alert('Please select next PM schedule '); 
            document.getElementById('schedule').focus();      
            return false;
            }
         }
         if(document.getElementById('question1').value == "1"){
            if(document.getElementById('condition').value == 0){
            alert('Please select equipment condition '); 
            document.getElementById('condition').focus();      
            return false;
            }
         }
            
  // always return false to prevent form from submission.
    }
</script> 
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Maintenance Management</li>
                                     <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href=""> Add</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/maintenancemgmt"> Back</a>
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
                                    <!-- Form Horizontal - Default Style -->
                                    <form class="mb-5" action="{{url('/user/maintenance/totalsubmit')}}" method="POST">
                                        {{csrf_field()}}
                                            
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"style="font-size:13px;"  for="department">Department</label>
                                            <div class="col-sm-8">
                                                <div class="form-group"style="color:#666666">
                                                    <select class="js-select2 form-control"style="font-size: 13px;" id="department" name="department" data-placeholder="Choose one..">
                                                        <option selected="" disabled>Choose a Department</option>
                                                            @if(count($departments)> 0 )
                                                                @foreach($departments as $value)
                                                                    <option value=" {{$value->departmentname}}"> {{ $value->departmentname }}</option></br>
                                                                @endforeach
                                                            @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>     
                                                
                                       
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" style="font-size: 13px;"for="desc">Equipment description</label>
                                            <div class="col-sm-8">
                                                <textarea type="text" style="font-size: 13px;"class="form-control" id="desc" name="desc" placeholder="Enter description"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="type"style="font-size: 13px;">Equipment Type <span class="text-danger">*</span></label>
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                         <select class="js-select2 form-control" style="font-size:13px;"required="true" id="example-select2-multiple" name="type[]" placeholder="Choose Equipment Type"  multiple>
                                                                       
                                                            <option value="Mechanical">Mechanical</option><br>
                                                            <option value="Electrical">Electrical</option><br>
                                                            <option value="Instrumentation">Instrumentation</option><br>
                                                            
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row"> 
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="location">Location/area</label>
                                                <div class="col-sm-8"> 
                                                    <input type="text"style="font-size: 13px;" class="form-control" id="location" name="location" placeholder="Enter..">
                                            
                                          </div>
                                    </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="serial">Serial number</label>
                                            <div class="col-sm-8">
                                                <input type="text"style="font-size: 13px;" class="form-control" id="serial" name="serial" placeholder="Enter..">
                                            </div>
                                        </div>
                                            
                                    <div class="form-group  row" >
                                        <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="make">Make/model</label>
                                        <div class="col-sm-8" id="make"style="font-size: 13px;" name="make">
                                            <input type="text"style="font-size: 13px;" class="form-control" id="make" name="make" placeholder="Enter">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" style="font-size: 13px;"for="quest">Is this reading based?</label>
                                        <div class="col-sm-8">
                                                                             
                                                <select class="form-control" style="font-size: 13px;"  name="quest" id="method"  >
                                                    <option selected="" disabled value="0" >Choose</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                                                                      
                                            </div>
                                    </div>
                                    <div class="form-group  row" >
                                        <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="readfreq"id="readfreq1">Reading frequency</label>
                                        <div class="col-sm-8" id="readfreq"style="font-size: 13px;" name="readfreq">
                                            <input type="text"style="font-size: 13px;" class="form-control" id="readfreq" name="readfreq" placeholder="Enter">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" style="font-size: 13px;"for="question2">Is testing frequency required ?</label>
                                        <div class="col-sm-8">
                                                                             
                                                <select class="form-control" style="font-size: 13px;"  name="question2" id="method2"  >
                                                    <option selected="" disabled value="0" >Choose</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                                                                      
                                            </div>
                                    </div>
                                    <div class="form-group  row" >
                                        <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="testfreq"id="testfreq1">Testing frequency</label>
                                        <div class="col-sm-8"> 
                                            <select class="form-control"  id="testfreq" name = "testfreq" style="font-size: 13px;">
                                                <option value="0" selected="" disabled>Choose Testing Frequency</option> 
                                                {{-- <option value="Not required">Not required</option><br> --}}
                                                  <option value="Weekly">Weekly</option><br>
                                                  <option value="Monthly">Monthly</option><br>
                                                  <option value="Bimonthly">Bimonthly</option><br>
                                                  <option value="Quarterly">Quarterly</option><br>
                                                  <option value="6 Month">6 Month</option><br>
                                                  <option value="Yearly">Yearly</option><br>
                                                  <option value="2 year">2 year</option><br>
                                              </select>
                                    </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="testby"id="testby1"style="font-size: 13px;">Tested by</label>
                                            <div class="col-sm-8"style="font-size: 13px;">
                                                <div class="form-group"style="color:#666666"id="testby">
                                                    <select class="js-select2 form-control"style="font-size: 13px;" id="testby" name="testby" style="width: 100%;" data-placeholder="Choose one..">
                                                        <option value="0" selected="" disabled>Choose Name</option>
                                                        @if(count($users)> 0 )
                                                            @foreach($users as $value)
                                                                <option value=" {{$value->name}}"> {{ $value->name }}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="lasttestdate"id="lasttestdate1"style="font-size: 13px;">Last test date</label>
                                                <div class="col-sm-8">
                                                    <input type="date"style="font-size: 13px;" class="js-datepicker form-control" id="lasttestdate" name="lasttestdate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="testschedule"id="testschedule1"style="font-size: 13px;">Next test schedule</label>
                                                <div class="col-sm-8">
                                                    <input type="date"style="font-size: 13px;" class="js-datepicker form-control" id="testschedule" name="testschedule" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                        </div>

                                   <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="testcondition"id="testcondition1">Equipment condition</label>
                                            <div class="col-sm-8"> 
                                               <select class="form-control" style="font-size: 13px;" id="testcondition" name = "testcondition" >
                                                <option value="0" selected="" disabled>Choose Equipment Condition</option>    
                                                <option value="Excellent">Excellent</option><br>
                                                    <option value="Good">Good</option><br>
                                                    <option value="Satisfactory">Satisfactory</option><br>
                                                    <option value="Poor performance">Poor performance</option><br>
                                                    <option value="Damaged">Damaged</option><br>
                                                
                                                </select>
                                            </div>
                                        </div>
                        
                                        <div class="form-group  row" >
                                            <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="testno"id="testno1">Test report number</label>
                                            <div class="col-sm-8" id="testno"style="font-size: 13px;" name="testno">
                                                <input type="text"style="font-size: 13px;" class="form-control" id="testno" name="testno" placeholder="Enter">
                                           </div>
                                        </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" style="font-size: 13px;"for="question1">Is preventive maintenance frequency required ?</label>
                                        <div class="col-sm-8">
                                                                             
                                                <select class="form-control" style="font-size: 13px;"  name="question1" id="method1"  >
                                                    <option selected="" disabled value="0" >Choose</option>
                                                    <option value="1">Yes</option>
                                                    <option value="2">No</option>
                                                </select>
                                                                                      
                                            </div>
                                    </div>
                                    <div class="form-group  row" >
                                        <label class="col-sm-4 col-form-label"class="form-control"style="font-size: 13px;"  for="freq"id="freq1">Preventive maintenance frequency</label>
                                        <div class="col-sm-8"> 
                                            <select class="form-control"  id="freq" name = "freq" style="font-size: 13px;">
                                                <option value="0" selected="" disabled>Choose Frequency</option> 
                                                {{-- <option value="Not required">Not required</option><br> --}}
                                                  <option value="Weekly">Weekly</option><br>
                                                  <option value="Monthly">Monthly</option><br>
                                                  <option value="Bimonthly">Bimonthly</option><br>
                                                  <option value="Quarterly">Quarterly</option><br>
                                                  <option value="6 Month">6 Month</option><br>
                                                  <option value="Yearly">Yearly</option><br>
                                                  <option value="2 year">2 year</option><br>
                                              </select>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="pmby"id="pmby1"style="font-size: 13px;">PM by</label>
                                            <div class="col-sm-8"style="font-size: 13px;"id="pmby">
                                                <div class="form-group"style="color:#666666"style="font-size: 13px;"id="pmby">
                                                    <select class="js-select2 form-control"style="font-size: 13px;" id="pmby" name="pmby" style="width: 100%;" data-placeholder="Choose one..">
                                                        <option style="font-size: 13px;"value="0" selected="" disabled>Choose Name</option>
                                                        @if(count($users)> 0 )
                                                            @foreach($users as $value)
                                                                <option value=" {{$value->name}}"> {{ $value->name }}</option></br>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="lastdate"id="lastdate1"style="font-size: 13px;">Last PM date</label>
                                                <div class="col-sm-8">
                                                    <input type="date"style="font-size: 13px;" class="js-datepicker form-control" id="lastdate" name="lastdate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label" for="schedule"id="schedule1"style="font-size: 13px;">Next PM schedule</label>
                                                <div class="col-sm-8">
                                                    <input type="date"style="font-size: 13px;" class="js-datepicker form-control" id="schedule" name="schedule" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                                </div>
                                        </div>

                                   <div class="form-group row">
                                            <label class="col-sm-4 col-form-label"style="font-size: 13px;" for="condition"id="condition1">Equipment condition</label>
                                            <div class="col-sm-8"> 
                                               <select class="form-control" style="font-size: 13px;" id="condition" name = "condition" >
                                                <option value="0" selected="" disabled>Choose Equipment Condition</option>   
                                                <option value="Excellent">Excellent</option><br>
                                                    <option value="Good">Good</option><br>
                                                    <option value="Satisfactory">Satisfactory</option><br>
                                                    <option value="Poor performance">Poor performance</option><br>
                                                    <option value="Damaged">Damaged</option><br>
                                                
                                                </select>
                                            </div>
                                        </div>
 
                                        <div style="text-align:center;">
                                            <button type="submit" for="submit" class="btn btn-primary"style="font-size:13px;margin-left:78%;" data-toggle="click-ripple" onclick="return confirm('Are you sure to submit the request?')">Submit</button>
                                            <button for="reset" type="reset" class="btn btn-primary"style="font-size:13px;">Reset</button>
                                        </div>
                                    </form>
                                      </div>
                            </div>
                        </div>
                    {{-- </div> --}}
                    <!-- END Horizontal -->
                    <script type="text/javascript">
                        $(document).ready(function() {
                        
                        $('#sel2').select2({
                          placeholder:'Choose Auditee ',
                        });
                        
                        });
                        
                        </script>
               {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> --}}
             

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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/maintenancemgmt">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Total Equipment List
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/pmfolder">
                                            <div class="block-content text-center"style="height:130px">
                                                <i class="fas fa-address-card fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  PM Plan and Report
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/maintenance/testfolder">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Test Plan
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Payments -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/maintenance/breakfolder">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   Breakdown Maintenance
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
           <script type="text/javascript">
               $(document).ready(function() {
    $('#readfreq1').hide();
    $('#readfreq').hide();

    $('#testfreq1').hide();
    $('#testfreq').hide();
    $('#testby1').hide();
    $('#testby').hide();
     $('#lasttestdate1').hide();
     $('#lasttestdate').hide();
     $('#testschedule1').hide();
     $('#testschedule').hide();
     $('#testcondition1').hide();
     $('#testcondition').hide();
     $('#testno1').hide();
     $('#testno').hide();

   $('#freq1').hide();
   $('#freq').hide();
   $('#pmby1').hide();
   $('#pmby').hide();
    $('#lastdate1').hide();
    $('#lastdate').hide();
    $('#schedule1').hide();
    $('#schedule').hide();
    $('#condition1').hide();
    $('#condition').hide();
   
});
                $("#method").change(function() {
                                       console.log($("#method option:selected").val());
                                       if ($("#method option:selected").val() == 2) {
                                      
                                       $('#readfreq1').hide();
                                       $('#readfreq').hide();
                                       
                                       } else {
                                      
                                       $('#readfreq1').show();
                                       $('#readfreq').show();
                                         
                                       }
                                     });


             $("#method1").change(function() {
                                       console.log($("#method1 option:selected").val());
                                       if ($("#method1 option:selected").val() == 2) {
                                         $('#freq').hide();
                                       $('#pmby').hide();
                                       $('#lastdate').hide();
                                       $('#schedule').hide();
                                       $('#condition').hide();
                                       $('#freq1').hide();
                                       $('#pmby1').hide();
                                       $('#lastdate1').hide();
                                       $('#schedule1').hide();
                                       $('#condition1').hide();
                                       
                                       } else {
                                     $('#freq').show();  
                                       $('#pmby').show();
                                       $('#lastdate').show();
                                       $('#schedule').show();  
                                       $('#condition').show();  
                                       $('#freq1').show();  
                                       $('#pmby1').show();
                                       $('#lastdate1').show();
                                       $('#schedule1').show();  
                                       $('#condition1').show();  

                                       }
                                     });


         $("#method2").change(function() {
                                       console.log($("#method2 option:selected").val());
                                       if ($("#method2 option:selected").val() == 2) {
                                     
                                       $('#testfreq').hide();
                                       $('#testby').hide();
                                       $('#lasttestdate').hide();
                                       $('#testschedule').hide();
                                       $('#testcondition').hide();
                                       $('#testno').hide();
                                       $('#testfreq1').hide();
                                       $('#testby1').hide();
                                       $('#lasttestdate1').hide();
                                       $('#testschedule1').hide();
                                       $('#testcondition1').hide();
                                       $('#testno1').hide();
                                       
                                       } else {
                                     $('#testfreq').show();  
                                       $('#testby').show();
                                       $('#lasttestdate').show();
                                       $('#testschedule').show();  
                                       $('#testcondition').show(); 
                                       $('#testno').show(); 
                                       $('#testfreq1').show();  
                                       $('#testby1').show();
                                       $('#lasttestdate1').show();
                                       $('#testschedule1').show();  
                                       $('#testcondition1').show(); 
                                       $('#testno1').show(); 
                                       }
                                     });
                                  
               </script>
       
    
</html>
@endsection
