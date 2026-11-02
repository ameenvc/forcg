@extends('layouts.multiselect')

@section('content')
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet"> -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
<style> 
 .stepsactive{
    font-size:13px;
    color:#25499c;
    /* background-color: #d9e2f6; */
    background-color: #17a2b8;   
    border-color: #d9e2f6;
    padding:5px 5px 5px 5px;
    border-radius:5px;
} 
.stepsnonactive{
    font-size:13px;
    color:#343a40;
    /* background-color: #edf0f2; */
    /* background-color: #FAEBD7; */
    /* border-color: #edf0f2; */
    background-color: #708090;
    border-color: #FFFFE0;
    padding:5px 5px 5px 5px;
    border-radius:5px;
} 
 .next-arrow {
        font-size: 30px;
        padding-top: 55px; 
        padding-left:20px;
        padding-right:20px;
        /* color: #007bff; */
        color: #17a2b8;
        transform: rotate(0deg);
  }
  .next-arrow2 {
        font-size: 30px;
        padding-top: 55px; 
        padding-left:20px; 
        padding-right:20px;
        color:darkgrey;
        transform: rotate(0deg);
  }
  input,select,textarea,span.select2.select2-container.select2-container--default{
        /* outline: 1px solid #AFEEEE; */
        outline: 1px solid #B0E0E6;
    }
    hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #B0E0E6;
    border-style: dashed;
    margin: 1em 0;
    padding: 0;
}
</style>

    <!-- START Hero -->
    <div class="bg-body-light">
         <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Emergency Response</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Create Drill Report</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('emergency.register') }}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
     <div class="block block-rounded "style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">

        <table width="100%">
             <!-- Step blocks starts -->
            <tr><td style="padding-top:20px;">
               <table align="center">
                    <tr>
                        <td width="25%" class="text-center stepsactive text-white">
                            <b>Step 1<br>Create Report</b>
                        </td>
                        <td><span class="next-arrow">&rarr;</span></td>
                        <td width="25%"class="text-center text-muted stepsnonactive text-white">
                            <b>Step 2<br>Review</b>
                        </td>
                        <td ><span class="next-arrow2">&rarr;</span></td>
                        <td width="25%" class="text-center text-muted stepsnonactive text-white">
                            <b>Step 3<br>Approval</b>
                        </td>
                                                            
                    </tr>
                </table>
           </td></tr>
           </table>
            <!-- End Step blocks -->
            @if(count($errors) >0 )
                                            @foreach($errors->all() as $error)
                                                <div class='alert alert-warning'>
                                                        {{ $error }}
                                                </div>
                                            @endforeach
                                        @endif
        <div class="row" style="margin-top:60px;margin-left:30px;margin-right:30px;">
            <div class="col-lg-12">
              <!-- create drill report form starts --> 
              <form name="frm1" id="frm1" class="mb-5" action="{{route('emergency.insertreportdetails')}}" method="POST" enctype="multipart/form-data">
                 {{csrf_field()}}                       
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Type of Drill<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                      <input type="text" style="font-size:13px;" class="form-control" id="drill_type" name="drill_type"  value="{{$datas->emergency_situation_name}}" readonly>
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;padding-left:29px;" for="drilltype">Location</label>
                                    <div class="col-sm-12">
                                      <input type="text" style="font-size:13px;" class="form-control" id="location" name="location"  value="{{$location_name}}" readonly>
                                      <input type="hidden"  id="location_id" name="location_id"  value="{{$datas->location_id}}">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="drill_date" style="font-size:13px;">Date<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" style="font-size:13px;" class="js-datepicker form-control" required id="drill_date" name="drill_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" value="">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;">Time Alarm Sounded<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                     <input type="time" class="form-control" name="time_alarm" required style="font-size: 13px;" placeholder="Please enter time alarm sounded">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;">Time Drill Conducted<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                     <input type="time" class="form-control" name="time_drill" required style="font-size: 13px;" placeholder="Please enter time alarm sounded">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;">Time taken to Evacuate<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                     <input type="time" class="form-control" name="evacuate_time" required style="font-size: 13px;" placeholder="Please enter time alarm sounded">
                                </div>
                            </div>                           

                            <div class="form-group" style="display:inline-block;width:90%;">
                                <label class="col-sm-12 col-form-label" for="drillteam" style="font-size:13px;">Drill team<span class="text-danger">*</span></label>
                                <!-- <div class="col-lg-8 col-xl-5"> -->
                                <div class="col-sm-6">
                                <select class="js-select2 form-control" name="drill_team[]" id="drill_team" required style="font-size: 13px;" 
                                    multiple data-placeholder="Choose Drill Team" onchange="displayotherdrill()">  
                                    @php
                                        $drillteams = explode(',', $datas->drill_team_id);
                                    @endphp                                   
                                    @foreach($empdetails as $empdetail)
                                    <option value="{{$empdetail->id}}" {{ (collect($drillteams)->contains($empdetail->id)) ? 'selected':'' }}>{{$empdetail->emp_name}}</option>
                                    @endforeach 
                                    <option value="other">Others</option>                                       
                                </select>
                                </div>
                                
                            </div>
                            <div class="form-group" style="display:none;width:49%;" id="drill_other">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;"></label>
                                <div class="col-sm-12">
                                <input type="text" style="font-size:13px;" class="form-control" id="drill_tem_other" name="drill_tem_other"  value="" placeholder="Please specify other drill team members">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:90%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Situation @Start of Drill<span class="text-danger">*</span></label>
                                 <div class="col-sm-6">                              
                                    <select class="js-select2 form-control" name="situation[]" id="situation" required style="font-size: 13px;" multiple data-placeholder="Choose Situation" onchange="displayothersituation()">
                                       @foreach($situationstarts as $situationstart)
                                           <option value="{{$situationstart}}">{{$situationstart}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                    </select>                       
                                </div>
                            </div> 
                            
                            <div class="form-group" style="display:none;width:49%;" id="situation_other">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;"></label>
                                <div class="col-sm-12">
                                  <input type="text" style="font-size:13px;" class="form-control" id="situation_other_value" name="situation_other_value"  value="" placeholder="Please specify other situation">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:90%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Weather Condition<span class="text-danger">*</span></label>
                                 <div class="col-sm-6">                              
                                    <select class="js-select2 form-control" name="weather[]" id="weather" required style="font-size:13px;" multiple data-placeholder="Choose Weather condition" onchange="displayotherweather()">
                                       @foreach($weather as $weath)
                                           <option value="{{$weath}}">{{$weath}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                    </select>                       
                                </div>
                            </div> 
                            
                            <div class="form-group" style="display:none;width:49%;" id="weather_other">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;"></label>
                                <div class="col-sm-12">
                                  <input type="text" style="font-size:13px;" class="form-control" id="weather_other_value" name="weather_other_value"  value="" placeholder="Please specify other weather conditions">
                                </div>
                            </div>
                           
                            <div class="form-group" style="display:inline-block;width:90%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Problem Encountered<span class="text-danger">*</span></label>
                                 <div class="col-sm-6">                              
                                    <select class="js-select2 form-control" name="problems[]" id="problems" required style="font-size:13px;" multiple data-placeholder="Choose Problem encountered" onchange="displayotherproblem()">
                                       @foreach($problems as $problem)
                                           <option value="{{$problem->id}}">{{$problem->name}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                    </select>                       
                                </div>
                            </div> 
                            
                            <div class="form-group" style="display:none;width:49%;" id="problem_other">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;"></label>
                                <div class="col-sm-12">
                                  <input type="text" style="font-size:13px;" class="form-control" id="problem_other_value" name="problem_other_value"  value="" placeholder="Please specify other problems encountered">
                                </div>
                            </div>


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Number of Participants<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                      <input type="number" style="font-size:13px;" class="form-control" id="participants" name="participants"  value="" required placeholder="Please enter number of participants">
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Number of Employees working in that area<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                      <input type="number" style="font-size:13px;" class="form-control" id="employees_work_area" name="employees_work_area" required value="" placeholder="Please enter number of employees working in that area">
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Evacuation Count<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                      <input type="number" style="font-size:13px;" class="form-control" id="evacuation_cnt" name="evacuation_cnt" required value="" placeholder="Please enter evacuation count">
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                     Did all site personnel immediately begin to evacuate and proceed to <br>pre-designated assembly area when alarm sounded?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="evacuate_immediate" id="evacuate_immediate" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                  Did Site Safety Personnel check restrooms and confined areas?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="safety_check" id="safety_check" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Was all personnel are informed of the emergency and that people remain calm?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="personnel_informed" id="personnel_informed" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Did appropriate emergency equipment function properly?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="equipment_function" id="equipment_function" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Did everyone evacuate the work areas?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="evacuate_workareas" id="evacuate_workareas" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Did everyone remain outside and wait for further instructions?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="remain_outside" id="remain_outside" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Is the Emergency Team knowledgeable in their assigned duties?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="knowledge_duties" id="knowledge_duties" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Were the drill conducted in an orderly manner?
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="drill_orderly" id="drill_orderly" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="NA">NA</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                   Speed of Evacuation
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="evacuation_speed" id="evacuation_speed" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="poor">Poor</option>
                                    </select>                       
                                </div>
                            </div>
                            
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                  Effectiveness of Procedures
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="procedure_effective" id="procedure_effective" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="poor">Poor</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                  Communications during drills
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="communication" id="communication" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="poor">Poor</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">
                                  Overall Drill rating
                                 <span class="text-danger">*</span>
                               </label>
                                 <div class="col-sm-12">                              
                                    <select class="js-select form-control" name="drill_rating" id="drill_rating" required style="font-size:13px;">
                                        <option value="">Choose One</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="poor">Poor</option>
                                    </select>                       
                                </div>
                            </div> 

                            <div class="form-group" style="display:inline-block;width:90%;">
                                <label  class="col-sm-12 col-form-label" style="font-size:13px;" for="drilltype">Mitigation/ Plan for improvement <span class="text-danger">*</span></label>
                                 <div class="col-sm-6">                              
                                    <select class="js-select2 form-control" name="mitigation[]" id="mitigation" required style="font-size:13px;" multiple data-placeholder="Choose Mitigation / Plan for improvement" onchange="displayothermitigation()">
                                       @foreach($mitigations as $mitigation)
                                           <option value="{{$mitigation->id}}">{{$mitigation->name}}</option>
                                        @endforeach
                                        <option value="other">Others</option>
                                    </select>                       
                                </div>
                            </div> 
                            
                            <div class="form-group" style="display:none;width:49%;" id="mitigation_other">
                                <label class="col-sm-12 col-form-label" for="time" style="font-size:13px;"></label>
                                <div class="col-sm-12">
                                  <input type="text" style="font-size:13px;" class="form-control" id="mitigation_other_value" name="mitigation_other_value"  value="" placeholder="Please specify other mitigations">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:90%;">
                                <label class="col-sm-12 col-form-label" for="last_drill" style="font-size:13px;">Additional Comments</label>
                                <div class="col-sm-12">
                                    <textarea style="font-size:13px;" class="form-control" id="comments" name="comments"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="mainid" value="{{$datas->id}}">
                            <input type="hidden" name="mainserialnum" value="{{$serialnum}}">

                            <div style="margin-top:50px;margin-bottom:50px;">
                                 <button type="submit" for="submit" class="btn btn-success" style="font-size:13px;margin-left:550px;">Proceed</button> 
                                <!-- <a href="{{route('emergency.adddrillreportimage')}}" 
                                class="btn btn-success" 
                                style="font-size:13px;margin-left:550px;">Proceed</a> -->
                          </div>
                    </form>
              <!-- create drill report form ends -->
            </div>
        </div> 

    
        </div>
      </div>
    </div>
   <!-- End Page Content -->             
    @include('user.emergencyresponse.header_options')                       
    <script>
        window.history.forward();
    </script> 
    <script>
        function displayotherdrill()
        {
           if(document.getElementById('drill_team').value=="other")
           {
                document.getElementById('drill_other').style.display="";
                document.getElementById('drill_tem_other').required= true;
           }
           else{
                document.getElementById('drill_other').style.display="none";
                document.getElementById('drill_tem_other').required= false;
           }
         
        }

        function displayothersituation()
        {
           if(document.getElementById('situation').value=="other")
           {
                document.getElementById('situation_other').style.display="";
                document.getElementById('situation_other_value').required= true;
           }
           else{
                document.getElementById('situation_other').style.display="none";
                document.getElementById('situation_other_value').required= false;
           }
         
        }
        
        function displayotherweather()
        {
           if(document.getElementById('weather').value=="other")
           {
                document.getElementById('weather_other').style.display="";
                document.getElementById('weather_other_value').required= true;
           }
           else{
                document.getElementById('weather_other').style.display="none";
                document.getElementById('weather_other_value').required= false;
           }
         
        }
        
        function displayotherproblem()
        {
           if(document.getElementById('problems').value=="other")
           {
                document.getElementById('problem_other').style.display="";
                document.getElementById('problem_other_value').required= true;
           }
           else{
                document.getElementById('problem_other').style.display="none";
                document.getElementById('problem_other_value').required= false;
           }
         
        }
        function displayothermitigation()
        {
           if(document.getElementById('mitigation').value=="other")
           {
                document.getElementById('mitigation_other').style.display="";
                document.getElementById('mitigation_other_value').required= true;
           }
           else{
                document.getElementById('mitigation_other').style.display="none";
                document.getElementById('mitigation_other_value').required= false;
           }
         
        }
        
    </script> 
@endsection