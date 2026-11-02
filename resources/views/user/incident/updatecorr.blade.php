@extends('layouts.multiselect')
@section('content')
           
      
   
              
      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Incident Report</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Update Report</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/incident/correactions">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="content" style="">
                   <!-- Horizontal -->
                    <div class="block block-rounded">                       
                        <div class="block-content block-content-full">
                            <div class="row">                                
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <?php $f=0; ?> 
                        <form class="mb-5" action="{{route('corrupdate')}}" method="POST" enctype="multipart/form-data">
              
                            @csrf
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif

                                        <div class="col-lg-10">
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="rid"style="font-size: 13px;">Report ID</label>
                                                    <div class="col-sm-12">
                                                    <input type="text"style="font-size: 13px;" class="form-control" id="rid" name="rid" value="{{$incident->report_no}}" readonly>
                                                        </div>
                                                </div>	
                                               
                                                <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="addedby"style="font-size: 13px;">Reported By</label>
                                                    <div class="col-sm-12">
                                                    <input type="text"style="font-size: 13px;" class="form-control" id="addedby" name="addedby" value="{{$incident->addedby}}" readonly>
                                                        </div>
                                                </div>
                                                <?php
                                                $today=Carbon\Carbon::now()->toDateTimeString();
                                                $today=Carbon\Carbon::parse($today);
                                                ?>
                                                <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="addeddate"style="font-size: 13px;">Reporting Date</label>
                                                    <div class="col-sm-12">
                                                    <input type="text"style="font-size: 13px;" class="form-control" id="addeddate" name="addeddate" value="{{$incident->addeddate}}" readonly>
                                                        </div>
                                                </div>
                                          
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Department</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                                    <input type="text"style="font-size: 13px;" class="form-control"  name="department" value="{{$incident->department}}" readonly>
                                                            </div>
                                                        </div>
                                               

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="area"style="font-size: 13px;">Area</label>
                                                    <div class="col-sm-12">
                                                    <input type="text"style="font-size: 13px;" value="{{$incident->area}}" required class="form-control" id="area" name="area" readonly>
                                                        </div>
                                                </div>	
                                    
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Type</label>
                                            <div class="col-sm-12">
                                            <input type="text"style="font-size: 13px;" value="{{$incident->incident_type}}" required class="form-control" id="type" name="type" readonly>
                                                
                                            </div>
                                        </div>
                                        <script type="text/javascript">
    $(document).ready(function() {
    $('#sel_priority').select2({
    });
    });
</script>

                                                       
<div class="form-group row">
                                                    <label  class="col-sm-12 col-form-label" for="person"style="font-size: 13px;">Name of the injured / suffered person</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                                        <input type="text"style="font-size: 13px;" readonly class="form-control" id="injper" name="injper" value="{{$incident->injuredperson}}">
                                                            </div>
                                                            <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;" readonly class="form-control" id="injempid" name="injempid" value="{{$incident->empid}}" >
                                                    </div>
                                                        </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="hospital"style="font-size: 13px;">Name of Hospital Injured / Suffered Person attended/ Admitted</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;" value="{{$incident->hospital}}" required class="form-control" id="hospital" name="hospital" readonly>
                                                    </div>
                                            </div>
                                            
                                            
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="riskdec"style="font-size: 13px;">Date and Time of Incident</label>
                                                <div class="col-sm-12">
                                                 <input type="text" class="js-datepicker form-control"  id="date" name="incidentdate" data-week-start="1" data-autoclose="true" value="{{$incident->incidentdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" readonly>
                                               </div>
                                             <div class="col-sm-12">
                                             <input type="time" class="form-control" name="time" style="font-size: 13px;" placeholder="Time" value="{{$incident->time}}" readonly>
                                            </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="place"style="font-size: 13px;">Place of the Incident</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;" class="form-control" id="place" name="place" readonly value="{{$incident->incidentplace}}">
                                                    </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="employer"style="font-size: 13px;">Employer of the injured / suffered person (state if labour supply)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" required style="font-size: 13px;"class="form-control" id="employer" name="employer" readonly value="{{$incident->emplyoer}}"></textarea>
                                                    </div>
                                            </div>
                                            
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="trade"style="font-size: 13px;"> Trade or occupation</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->trade_or_occupation}}"></textarea>
                                                    </div>
                                            </div>

                                                <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="desc"style="font-size: 13px;"> Description</label>
                                                    <div class="col-sm-12">
                                                        <textarea   type="text" required style="font-size: 13px;"class="form-control" id="desc" name="desc" readonly>{{$incident->incidentdesc}}</textarea>
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="action"style="font-size: 13px;"> Action Taken</label>
                                                    <div class="col-sm-12">
                                                        <textarea type="text" required style="font-size: 13px;"class="form-control" id="action" name="action" readonly>{{$incident->actions}}</textarea>
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Reported under local regulation</label>
                                            <div class="col-md-12">
                                            <input type="text" style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->local_regulation}}">
                                            </div>
                                            </div>
                                         
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Evaluation of control measures</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->eval}}">
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Public reaction</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->pubrec}}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Probability of occurrence</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->probablity}}">
                                                          
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Severity in terms of financial loss</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->finloss}}">
                                                           
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Effect on community</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->efcom}}">
                                                            
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Severity in terms of human trauma</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                                                <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->humtur}}">

                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Potential For Loss</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                                                <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->potloss}}">

                                                            </div>
                                                        </div>
                                                 
                                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Severity of potential loss</label>
                                                        <div class="col-sm-12">
                                                            {{-- <div class="form-group"style="color:#666666"> --}}
                                                                {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->sevpotloss}}">
                                                          
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">Probability of reoccurrence</label>
                                                        <div class="col-sm-12">
                                                        <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$incident->porbocc}}">

                                                            </div>
                                                        </div>
                                                        
                                          
                                            <div class="form-group" id="tleader" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="person"style="font-size: 13px;">Team Leader</label>
                                                        <div class="col-sm-12">  
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$investigator->tleadername}}">
                                                        
                                                            </div>
                                            </div>
                                            <div class="form-group" id="tmember" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="members"style="font-size: 13px;">Team Members</label>
                                                        <div class="col-sm-12">  
                                            <input type="text" required style="font-size: 13px;"class="form-control" id="trade" name="trade" readonly value="{{$investigator->tmembersnames}}">
                                                        </div>
                                            </div>
  
                                            
                                            <div class="form-group" id="tdate" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="targetdate"style="font-size: 13px;">Target date to complete investigation</label>
                                                <div class="col-sm-12">
                                                 <input type="date" class="js-datepicker form-control"  id="targetdate" name="targetdate" data-week-start="1" data-autoclose="true" value="{{$investigator->targetdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" readonly>
                                               </div>
                                            </div>

                                            <!--Fileds filled by team leader-->
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                            @if($incident->riskassessment=='yes')
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was there a Risk Assessment?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->riskassessment}}" readonly>
                                            </div>
                                            <div class="col-sm-12">  
                                            <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->riskassess}}" readonly>
                                             </div>
                                             @else
                                             <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was there a Risk Assessment?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->riskassessment}}" readonly>
                                            </div>
                                             @endif
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                            @if($incident->toolbox=='yes')
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was there a Pre-Task/Tool Box Briefing?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->toolbox}}" readonly>
                                            </div>
                                            <div class="col-sm-12">  
                                            <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->tooldate}}" readonly>
                                             </div>
                                             @else
                                             <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was there a Risk Assessment?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->toolbox}}" readonly>
                                            </div>
                                             @endif
                                            </div>
                                            <div class="form-group"  id="trd" style="display:inline-block;width:49%;">
                                            @if($incident->training=='yes')
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was the Person Trained to do the Job?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->training}}" readonly>
                                            </div>
                                            <div class="col-sm-12">  
                                            <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->traindate}}" readonly>
                                             </div>
                                             @else
                                             <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was there a Risk Assessment?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->training}}" readonly >
                                            </div>
                                             @endif
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;"> 
                                            @if($incident->autherised=='yes')
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was the Person Authorised to do the Works?</label>
                                                <div class="col-md-12">
                                                <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->autherised}}" readonly>
                                                </div>
                                            @else
                                            <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;"> Was the Person Authorised to do the Works?</label>
                                            <div class="col-md-12">
                                            <input type="text"  style="font-size: 13px;"class="form-control" value="{{$incident->autherised}}" readonly >
                                            </div>
                                            @endif

                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="activity"style="font-size: 13px;">Activity carried out at time of Incident: </label>
                                                    <div class="col-sm-12">
                                                        <textarea type="text"  style="font-size: 13px;"class="form-control" readonly>{{$incident->activity}}</textarea>
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="investigatordisc"style="font-size: 13px;">Description of Incident: </label>
                                                    <div class="col-sm-12">
                                                        <textarea type="text"  style="font-size: 13px;"class="form-control" readonly>{{$incident->investigatordisc}}</textarea>
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="bodypart"style="font-size: 13px;">Parts of Body affected, if any </label>
                                                        <div class="col-sm-12">  
                                                    <input type="text" style="font-size: 13px;"class="form-control" value="{{$incident->bodypart}}" readonly>
                                                        </div>
                                            </div>

                                          
                                            <div class="form-group" style="display:inline-block;width:49%;"> 
                                            @if($incident->unsafework=='yes')
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;">Was the incident caused by an unsafe work condition?</label>
                                                <div class="col-md-12">
                                                <input   style="margin-right:4px;font-size: 13px;" type="text"  class="form-control" value="{{$incident->unsafework}}" readonly>
                                            </div>
                                            <div class="col-md-12">
                                            <input   style="margin-right:4px;font-size: 13px;" type="text" class="form-control"  value="{{$incident->workunsafe}}" readonly>
                                            </div>
                                            @else
                                            <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;">Was the incident caused by an unsafe work condition? (i.e. faulty equipment, unsafe ventilation, insufficient training) </label>
                                            <div class="col-md-12">
                                                <input   style="margin-right:4px;font-size: 13px;" class="form-control" type="text"   value="{{$incident->unsafework}}" readonly>
                                            </div>
                                            @endif
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;"> 
                                            @if($incident->unsafework=='yes')
                                                <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;">Was the incident caused by an unsafe act?</label>
                                                <div class="col-md-12">
                                                <input   style="margin-right:4px;font-size: 13px;" type="text" class="form-control"  value="{{$incident->unsafeact}}" readonly>
                                            </div>
                                            <div class="col-md-12">
                                            <input   style="margin-right:4px;font-size: 13px;" type="text" class="form-control"   value="{{$incident->actunsafe}}" readonly>
                                            </div>
                                            @else
                                            <label class="col-sm-12 col-form-label" for="local"style="font-size: 13px;">Was the incident caused by an unsafe work condition?</label>
                                            <div class="col-md-12">
                                                <input   style="margin-right:4px;font-size: 13px;" type="text"  class="form-control" value="{{$incident->unsafeact}}" readonly>
                                            </div>
                                            @endif
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="ppkit"style="font-size: 13px;">What personal protective equipment was being used (if any)? </label>
                                                        <div class="col-sm-12">  
                                                        <input   style="margin-right:4px;font-size: 13px;" type="text" class="form-control"  value="{{$incident->ppkit}}" readonly>
                                                            </div>
                                                            <div style="margin-left:850px;margin-top:-40px;FONT-SIZE:8px">MS.10.03</div>
                                                        </div>
                                                        @php 
                                                            $otherPreventedReason = explode(',',$incident->prevent);    
                                                            if(in_array("Other", $otherPreventedReason)){ 
                                                                $otherPreventedReason[] = $incident->prevent_other;
                                                                $reason = implode(',',array_diff($otherPreventedReason,['Other']));
                                                            }else{
                                                                $reason = $incident->prevent;
                                                            }
                                                        @endphp
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="riskgroup"style="font-size: 13px;">How can future incident / spread be prevented</label>
                                                        <div class="col-sm-12">
                                                        <input  style="margin-right:4px;font-size: 13px;" type="text"class="form-control"   value="{{$reason}}" readonly>
                                                            </div>
                                                            <div style="margin-left:850px;margin-top:-35px;FONT-SIZE:8px">MS.10.02</div>
                                                        </div>
            
                                        
<br/>
   @if(count($witnesses)>0)
                            <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable" style="width:148%" >
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">SL No.</th>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Witness Name</th>
                                         <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Witness Address/Mobile No.</th>
                                         <th class="text-center"  style="font-size:13px;width: 150px;text-transform:capitalize">Statement Provided?</th>
                                 
                                    </tr>
                                </thead>
                                <tbody> <?php $num=1;?>
                                @foreach($witnesses as $wit)
                                        <tr> 
                                        <td style="font-size: 13px;" class="text-center">
                                        {{$num++}}
                                             </td>
                                             <td class="text-center" style="font-size: 13px;">
                                            {{$wit->name}}
                                             </td>
                                             <td class="text-center" style="font-size: 13px;">
                                             {{$wit->phone_or_address}}
                                            </td><td class="text-center" style="font-size: 13px;">
                                               {{$wit->statement}}
                                             </td>
                                        </tr> 
                                @endforeach
                                </tbody>
                            </table>
                           
                                    @endif
                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="investigatordisc"style="font-size: 13px;">Target Date</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" style="font-size: 13px;"class="form-control" value="{{$incident->currective_targdate}}" readonly>
                                                    </div>
                                            </div>
                                    <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="investigatordisc"style="font-size: 13px;">Root Cause</label>
                                                    <div class="col-sm-12">
                                                        <textarea type="text" name='root' style="font-size: 13px;"class="form-control" >{{$incident->rootcause}}</textarea>
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="investigatordisc"style="font-size: 13px;">Corrective Action </label>
                                                    <div class="col-sm-12">
                                                        <textarea type="text" name="corrective" style="font-size: 13px;"class="form-control" >{{$incident->corrective_act}}</textarea>
                                                    </div>
                                            </div>
                                            {{-- <br> --}}
                                    {{-- </div> --}}
                                    <div class="form-group"  id="corrfiles" name="corrfiles" style="display:inline-block;width:49%;">
                                            <label class="col-sm-12 col-form-label" for="corrfiles" style="font-size: 13px;">Attach files(avoid '#' and '&' in file names)</label>
                                            <div class="col-sm-12"> 
                                            <input type="file"style="font-size: 13px;" class="form-control" name="corrfiles[]"  multiple>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $status=['Not Started-0%','Progress-25%','Partially Completed-50%','Completed-100%'] ;
                                            ?>
                                             <div class="form-group" style="display:inline-block;width:49%;">
                                                    <label  class="col-sm-12 col-form-label" for="number"style="font-size: 13px;">Implementation Status</label>
                                                        <div class="col-sm-12">
                                                            <select class="form-control"  name = "impl_status" required style="font-size: 13px;margin-left:-34px">     
                                                            @foreach($status as $stu) 
                                                            @if($incident->corr_implement_status==$stu) 
                                                            <option  value="{{$stu}}" selected>{{$stu}}</option>
                                                            @else
                                                            <option  value="{{$stu}}">{{$stu}}</option>
                                                            @endif
                                                            @endforeach    
                                                            </select>
                                                            </div>
                                            </div>

                                    <div style="text-align:center;">
                                        <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Update</button>
                                        <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;float:right;margin-right:1%; ">Reset</button>
                                    </div>
                      
                        </form>
                        
                        </div>
                </div>
            </div>
        </div>
  
              <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
              <!-- Opens from the modal toggle button in the header -->
              <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">More</h3>
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/register">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Incident Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/closed/incidents">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Incident
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/incident/correactions">
                                            <div class="block-content text-center">
                                                <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    My Open Corrective Actions
                                                </p>
                                            </div>
                                        </a>
                                       
                                    </div>
                                    <!--<div class="col-6">
                                        
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                            <div class="block-content text-center">
                                                <i class="si si-plane fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Risks
                                                </p>
                                            </div>
                                        </a>
                                       
                                    </div>
                                    <div class="col-6">
                                       
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
                                            <div class="block-content text-center">
                                                <i class="si si-wallet fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
                                                </p>
                                            </div>
                                        </a>
                                   
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->
        <!-- END Page Container -->

<script>                                                               
   </script> 
   @endsection 