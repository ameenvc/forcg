@extends('layouts.backend')

@section('content')

<style>
    .mainheading{
        font-size:16px;
        font-family:system-ui;
        font-weight:bold;
    }
    .subheading{
        font-size:14px;
        font-family:system-ui;
        font-weight:bold;
    }
    .stylecontentname{
        font-size:13px;
        /* background:#ced4da; */
        background:#e0effa;
        font-family:system-ui;
    }
    .stylecontentvalue{
        font-size:13px;
        font-family:system-ui;
        /* background:#f1f1f1; */
        background:#ffffff;
    }
    .drillimg{
        padding:2px;
        background-color: #edf0f2;
        border: 1px solid #dee2e6;
        border-radius:2px;      
    }
    .drillname-lighter {
  background-color: #ffffff !important;
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
                            <a class="link-fx" href="">Drill Report</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('emergency.viewdrillreport',['id'=>$reports->emergency_details_id]) }}">Back</a>
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
        <div class="row" style="margin-top:30px;margin-left:30px;margin-right:30px;margin-bottom:30px;">
            <div class="col-lg-12">
             <!-- Drill report starts -->
                <table class="table table-borderless table-striped">
                  <tr>
                     <td colspan="6" class="bg-info text-white mainheading text-center">Drill Report</td>
                   </tr>
                    <tr>
                        <td style="width:200px;" class="stylecontentname">Report Number</td>
                        <td style="width:240px;" class="stylecontentvalue">{{$reports->report_number}}</td>
                        <td style="width:200px;" class="stylecontentname">Type of Drill</td>
                        <td style="width:240px;" class="stylecontentvalue">{{$reports->drill_type}}</td>
                        <td style="width:200px;height:35px;"class="stylecontentname">Location</td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue">{{$reports->locationdetails ? $reports->locationdetails->name:'-'}}</td>
                                                          
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td style="width:230px;height:35px;" class="stylecontentname">Date</td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue">{{date('d-m-Y',strtotime($reports->drill_date))}}</td>
                        <td style="width:200px;" class="stylecontentname">Time Alarm Sounded</td>
                        <td style="width:240px;" class="stylecontentvalue">{{$reports->time_alarm_sounded}}</td>
                        <td style="width:200px;height:35px;"class="stylecontentname">Time Drill Conducted</td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue">{{$reports->time_drill_conducted}}</td>                                                 
                    </tr>

                    <tr>
                        <td style="width:230px;height:35px;" class="stylecontentname">Time taken to Evacuate</td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue">{{$reports->time_evacuated}}</td>
                        <td colspan="4">&nbsp;</td>                                                   
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                      <td colspan="6" class="bg-white text-dark subheading">Drill Data</td>
                    </tr>

                    <tr>
                        <td style="width:230px;height:35px;" class="stylecontentname">Drill team</td>
                        <td colspan="5"  class="stylecontentvalue ">
                           <div class="row items-push js-gallery"> 
                           @if($reports->drill_team=='other')
                             <?php
                               $teamothers = explode(',',$reports->drill_team_other);
                             ?>
                              @foreach($teamothers as $teamother)                           
                                <div class="col-lg-2 col-md-2 col-xl-1">
                                <div class="item item-circle bg-primary-lighter mx-auto mb-3">
                                  <img src="{{asset('/images/avatar10.jpg')}}" class="avatar"/>
                                </div>              
                                <div class="drillname-lighter mx-auto mb-3 text-center" style="border-radius:5%;">
                                    {{$teamother}}
                                </div>
                                </div>                                                                                      
                                @endforeach 
                           @else
                            <?php
                               $teams = explode(',',$reports->drill_team);
                               foreach($teams as $team)
                               {
                                 $employee = DB::table('employee_details')->where('id',$team)->first(); 
                                 $user= DB::table('users')->where('employeeid',$employee->emp_number)->first();                            
                             ?>
                              @if($user)                                                         
                                <div class="col-lg-2 col-md-2 col-xl-1">
                                <div class="item item-circle bg-primary-lighter mx-auto mb-3">
                                  <img src="{{($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture))) ? asset("storage/profile/".$user->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar"/>
                                </div>              
                                <div class="drillname-lighter mx-auto mb-3 text-center" style="border-radius:5%;">
                                    {{ucfirst($user->name)}}
                                </div>
                                </div>
                               @else
                               <div class="col-lg-2 col-md-2 col-xl-1">
                                <div class="item item-circle bg-primary-lighter mx-auto mb-3">
                                <img src="{{asset('/images/avatar10.jpg')}}" class="avatar"/>
                                </div>              
                                <div class="drillname-lighter mx-auto mb-3 text-center" style="border-radius:5%;">
                                    {{ucfirst($employee->emp_name)}}
                                </div>
                                </div>
                               @endif 
                               <?php } ?>                          
                            @endif
                           </div>                              
                        </td>                   
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;">
                        <td style="width:200px;" class="stylecontentname">Situation @Start of Drill</td>
                        <td style="width:240px;" class="stylecontentvalue">
                          @if($reports->drill_start_situation=='other')
                             {{$reports->drill_start_situation_other}}
                          @else
                          {{$reports->drill_start_situation}}
                          @endif
                        </td>
                        <td style="width:200px;height:35px;"class="stylecontentname">Number of Participants</td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue">{{$reports->participants_number}}</td> 

                        <td style="width:200px;height:35px;"class="stylecontentname">Weather Condition</td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue">
                            @if($reports->weather=='other')
                                {{$reports->weather_other}}
                            @else
                            {{$reports->weather}}
                            @endif
                        </td>
                    </tr>
                    <tr style="border-top:3px solid #f1f1f1;"> 
                        <td style="width:200px;height:35px;"class="stylecontentname">Problem Encountered</td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue" colspan="3">
                            @if($reports->problem_encountered=='other')
                                {{$reports->problem_encountered_other}}
                            @else
                            <ul>
                            <?php
                              $repotprobs = explode(',',$reports->problem_encountered);
                              foreach($repotprobs as $repotprob)
                              {
                                $probs = DB::table('emergency_response_problem_details')
                                                       ->where('id',$repotprob)
                                                       ->value('name');                                                          
                            ?>
                             <li style="position: relative;left: -20px;">{{$probs}} </li>
                            <?php } ?>
                            </ul>
                            @endif
                        </td>         
                    </tr>
                                  
                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                      <td colspan="6" class=" bg-white text-dark subheading">Evacuation Outcome</td>
                    </tr>
                    
                    <tr>
                        <td style="width:230px;height:35px;" class="stylecontentname">Number of Employees working in that area</td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue"> {{$reports->employees_working_area_num}}</td>
                        <td style="width:200px;" class="stylecontentname">Evacuation Count</td>
                        <td colspan="3" style="width:240px;" class="stylecontentvalue"> {{$reports->evacuation_count}} </td>                    
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                      <td colspan="6" class=" bg-white text-dark subheading">Drill Evaluation</td>
                    </tr>

                    <tr>
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                            Did all site personnel immediately begin to evacuate and proceed to
                            pre-designated assembly area when alarm sounded?
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->evacuate_immediately)}}</td>
                                                                                  
                    </tr>
                     
                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                           Did Site Safety Personnel check restrooms and confined areas?
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->checked_confined_areas)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                        Was all personnel are informed of the emergency and that people remain calm?
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->emergency_informed)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                          Did appropriate emergency equipment function properly? 
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->equipment_function)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                          Did everyone evacuate the work areas? 
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->evacuated_all)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                         Did everyone remain outside and wait for further instructions?  
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->remain_outside)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                          Is the Emergency Team knowledgeable in their assigned duties?   
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->emergency_team_knowledgeable)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="3"style="width:230px;height:35px;" class="stylecontentname">
                          Were the drill conducted in an orderly manner?  
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue"> {{ucfirst($reports->drill_conducted_orderly)}}</td>                                                                                 
                    </tr>

                    <tr style="border-top:4px solid #cccccc;border-bottom:3px solid #f1f1f1;">
                        <td style="width:230px;height:35px;" class="stylecontentname">
                          Speed of Evacuation 
                        </td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue bg-white"> {{ucfirst($reports->evacuation_speed)}}</td>
                        <td style="width:230px;height:35px;" class="stylecontentname">
                          Effectiveness of Procedures 
                        </td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue bg-white"> {{ucfirst($reports->procedure_effectiveness)}}</td>
                        <td style="width:230px;height:35px;"class="stylecontentname">Communications during drills </td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue bg-white">{{ucfirst($reports->drill_communication)}}</td>
                    </tr>
    
                    <tr>
                        <td  style="width:230px;height:35px;" class="stylecontentname">
                          Mitigation/ Plan for improvement  
                        </td>
                        <td colspan="2" style="width:270px;height:35px;" class="stylecontentvalue"> 
                            @if($reports->mitigation=='other')
                                {{$reports->mitigation_other}}
                            @else
                            <ul>
                            <?php
                              $mitigations = explode(',',$reports->mitigation);
                              foreach($mitigations as $mitigation)
                              {
                                $plans = DB::table('emergency_response_mitigation_details')
                                                       ->where('id',$mitigation)
                                                       ->value('name');                                                          
                            ?>
                             <li style="margin-bottom:5px;position: relative;left: -20px;">{{$plans}} </li>
                            <?php } ?>
                            </ul>
                            @endif
                        </td> 
                    </tr>
                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td  style="width:230px;height:35px;" class="stylecontentname">Additional Comments </td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue bg-white">
                           {{$reports->comments ? ucfirst($reports->comments) : '--'}}
                        </td>                                                                                
                    </tr>
                    
                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td  style="width:230px;height:35px;background:#e0effa;" class="text-danger subheading">
                           Overall Drill rating
                        </td>
                        <td style="width:270px;height:35px;" class="stylecontentvalue text-danger"> <b>{{ucfirst($reports->drill_rating)}}</b></td>
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                      <td colspan="6" class=" bg-white text-dark subheading">Drill Images</td>
                    </tr>

                    <tr style="border-top:3px solid #f1f1f1;border-bottom:3px solid #f1f1f1;">
                        <td colspan="5" class="bg-white">                      
                            @if(count($drillimages) > 0)
                            <div class="row items-push js-gallery">
                                @foreach($drillimages as $drillimage)                      
                                    <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
                                      <a class="img-link img-link-zoom-in img-thumb img-lightbox" href="{{ asset('storage/emergency/'.$drillimage->drill_images) }}">
                                        <img class="drillimg" src="{{ asset('storage/emergency/'.$drillimage->drill_images) }}" alt="" width="200" height="150">
                                      </a>
                                    </div>                     
                                    @endforeach                         
                                </div>
                            @else
                                <span class="stylecontentvalue text-danger text-center">No Images</span>
                            @endif
                        </td>
                    </tr>                  

                    <tr style="border-top:4px solid #cccccc;border-bottom:3px solid #f1f1f1;">
                        <td  style="width:230px;height:35px;" class="stylecontentname">
                           Corrective Actions
                        </td>
                        <td colspan="3" style="width:270px;height:35px;" class="stylecontentvalue bg-white">
                          @if(count($correctives)>0)
                            @foreach($correctives as $corrective)
                                 <a href="{{route('corrective.viewlist',['type'=>$corrective->id])}}" class="badge bg-success text-white">{{$corrective->issueid}}</a>                            
                            @endforeach
                          @else
                            <span class="stylecontentvalue text-danger text-center">No Corrective Actions added</span>
                          @endif
                        </td>      
                        <td style="width:230px;height:35px;"class="stylecontentname bg-white">&nbsp; </td>
                        <td style="width:240px;height:35px;" class="stylecontentvalue bg-white">&nbsp;</td>
                    </tr>
    

                </table>        
             <!-- Drill report ends  -->
            </div>
        </div>
        </div>
      </div>
    </div>
   <!-- End Page Content -->             
    @include('user.emergencyresponse.header_options')                       
     
@endsection