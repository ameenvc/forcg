@extends('layouts.multiselect')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Occupational Health</li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="link-fx" href="">Update Other Cases Injury Record</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="link-fx" href="/user/occupationhealth/othercases">Back</a>
                                </li>
                        </ol>
                </nav>
        </div>
    </div>
</div>


<div class="content">
     <div class="block block-rounded" >
        <div class="block-content block-content-full">
                   <div class="row">
                        <div class="col-lg-10">
                            <!-- update form starts here -->
                            <form  action="{{route('updateotherinjuryrecords')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                       @if(Session::has('msg'))
                                            <div class="alert alert-primary">
                                                <a class="close" data-dismiss="alert">×</a>
                                                {!!Session::get('msg')!!}
                                            </div>
                                        @endif

                                       <?php
                                         $today=Carbon\Carbon::now()->toDateTimeString();
                                         $today=Carbon\Carbon::parse($today); 
                                        ?>
                                        <input type="hidden" value="{{$record->id}}" name="recid">
                                        <input type="hidden" value="{{$record->status}}" name="db_status">
                                        <input type="hidden"  class="form-control" name = "createdby" id="createdby" value="{{$record->creator_name}}" style="font-size: 13px;" > 
                                        <input type="hidden" value="{{$today->format('d-m-Y')}}" class="form-control" name = "createddate" id="createddate" value="{{$record->created_date}}" style="font-size: 13px;" > 
                                        <input type="hidden" class="form-control" name = "createrposition" id="createrposition" value="{{$creat_dep}}" style="font-size: 13px;"  > 
                                        <input type="hidden" class="form-control" name = "createrposition" id="createrposition" value="{{$record->creator_position}}" style="font-size: 13px;">  

                                        <div class="col-lg-10">

                                        <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="createrposition"style="font-size: 13px;padding-left:29px;">Report Number</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name = "report_num" id="report_num" value="{{$record->report_num}}" style="font-size: 13px;" readonly>     
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                                <label  class="col-sm-4 col-form-label" for="riskgroup"style="font-size: 13px;padding-left:29px;">Category </label>
                                                <div class="col-sm-8">
                                                  <select class="form-control" name = "category" required="true" style="font-size: 13px;">
                                                    <option value="" style="font-size: 13px;">Choose Category</option>
                                                    @foreach($healthcategory as $cat)
                                                    <option value="{{$cat->id}}" <?php if($record->category_id==$cat->id){ ?> selected <?php }?>>{{$cat->name}}</option>
                                                    @endforeach
                                                  </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" style="font-size: 13px;padding-left:29px;">Injured Person</label>
                                                <div class="col-sm-8">
                                                   <input style="margin-right:4px;margin-left:10px;" type="radio" value="contractor" id="contractor"  name="injured_person_type" <?php if($record->injured_person_type=='contractor'){ ?>checked <?php } ?> onclick="displaypersontype()" ><label style="font-size: 13px;">Contract Worker</label>
                                                   <input style="margin-right:4px;margin-left:10px;" type="radio" value="no_contractor" id="no_contractor"  name="injured_person_type" <?php if($record->injured_person_type=='other'){ ?>checked <?php } ?> onclick="displaypersontype()"><label style="font-size: 13px;">Others</label>
                                                </div>
                                            </div>
                                            
                                        @if($record->injured_person_type=='contractor')
                                        <div class="form-group row" id="contractordetails">
                                         <div class="col-lg-12">

                                              <div class="form-group row" >
                                                    <label  class="col-sm-4 col-form-label" for="name"style="font-size: 13px;padding-left:29px;"> Contract Employee Name </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "contractor_name" id="contractor_name"  value="{{$record->e_name}}" style="font-size: 13px;">
                                                        </div>
                                                </div>

                                                <div class="form-group row">
                                                <label  class="col-sm-4 col-form-label" for="number"style="font-size: 13px;padding-left:29px;">Contract Employee Number(if Available)</label>
                                                    <div class="col-sm-8">        
                                                        <input type="text" class="form-control" name = "number" id="number" value="{{$record->e_number}}"  style="font-size: 13px;">
                                                    </div>
                                                </div>


                                                    <div class="form-group row" >
                                                        <label class="col-sm-4 col-form-label" for="dob"style="font-size: 13px;padding-left:29px;"> Contract Employee Date of Birth</label>
                                                            <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "dob" id="dob"  value="{{$record->dob}}" style="font-size: 13px;">
                                                            </div>
                                                    </div>

                                                    <div class="form-group row" >
                                                        <label  class="col-sm-4 col-form-label" for="department"style="font-size: 13px;padding-left:29px;"> Contract Employee Department</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" name = "department" id="department"  value="{{$inj_dep}}" style="font-size: 13px;">
                                                            </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label  class="col-sm-4 col-form-label" for="position"style="font-size: 13px;padding-left:29px;"> Contract Employee Position</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "position" id="position" value="{{$record->position}}"  style="font-size: 13px;">
                                                        </div>
                                                    </div>
                                         </div>
                                       </div>
                                        @else
                                        <div class="form-group row" id="contractordetails" style="display:none;">
                                         <div class="col-lg-12">

                                              <div class="form-group row" >
                                                    <label  class="col-sm-4 col-form-label" for="name"style="font-size: 13px;padding-left:29px;"> Contract Employee Name </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "contractor_name" id="contractor_name"  value="" style="font-size: 13px;">
                                                        </div>
                                                </div>

                                                <div class="form-group row">
                                                <label  class="col-sm-4 col-form-label" for="number"style="font-size: 13px;padding-left:29px;">Contract Employee Number(if Available)</label>
                                                    <div class="col-sm-8">        
                                                        <input type="text" class="form-control" name = "number" id="number" value=""  style="font-size: 13px;">
                                                    </div>
                                                </div>


                                                    <div class="form-group row" >
                                                        <label class="col-sm-4 col-form-label" for="dob"style="font-size: 13px;padding-left:29px;"> Contract Employee Date of Birth</label>
                                                            <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "dob" id="dob"  value="" style="font-size: 13px;">
                                                            </div>
                                                    </div>

                                                    <div class="form-group row" >
                                                        <label  class="col-sm-4 col-form-label" for="department"style="font-size: 13px;padding-left:29px;"> Contract Employee Department</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" name = "department" id="department"  value="" style="font-size: 13px;">
                                                            </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label  class="col-sm-4 col-form-label" for="position"style="font-size: 13px;padding-left:29px;"> Contract Employee Position</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "position" id="position" value=""  style="font-size: 13px;">
                                                        </div>
                                                    </div>
                                         </div>
                                       </div>
                                        @endif
                                       
                                        @if($record->injured_person_type=='other')
                                           <div class="form-group row" id="nocontractordetails">
                                                <label  class="col-sm-4 col-form-label" for="position"style="font-size: 13px;"></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name = "injured_person_other" id="injured_person_other" value="{{$record->e_name}}" placeholder="Please specify" style="font-size: 13px;">
                                                </div>
                                            </div>
                                        @else
                                           <div class="form-group row" id="nocontractordetails" style="display:none;">
                                                <label  class="col-sm-4 col-form-label" for="position"style="font-size: 13px;"></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name = "injured_person_other" id="injured_person_other" value="" placeholder="Please specify" style="font-size: 13px;">
                                                </div>
                                            </div>
                                        @endif

                                            <?php
                                              $injurydate = DateTime::createFromFormat('d-m-Y', $record->dateofinjury);             
                                              $injurydate=$injurydate->format('Y-m-d');                      
                                            ?>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="date_timeinj"style="font-size: 13px;padding-left:29px;">Date and Time of Injury</label>
                                                <div class="col-sm-4">
                                                 <input type="date" class="js-datepicker form-control"  id="injdate" name="injdate" value="{{$injurydate}}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                             </div>
                                                <div class="col-sm-4">
                                                    <input type="time" class="form-control" name="injtime" style="font-size: 13px;" placeholder="Time" value="{{$record->timeofinjury}}">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" style="font-size: 13px;padding-left:29px;">Type of Injury </label>
                                                <div class="col-sm-8">
                                               
                                                   <input style="margin-right:4px;margin-left:10px;" type="radio" value="work_related" id="work_related"  name="injury_type" <?php if($record->injury_type=='work_related'){ ?>checked<?php } ?> onclick="displayworkinjury()" required><label style="font-size: 13px;">Work Related</label>
                                              
                                                   <input style="margin-right:4px;margin-left:10px;" type="radio" value="non_work_related" id="work_related1"  name="injury_type" <?php if($record->injury_type=='non_work_related'){ ?>checked <?php } ?>onclick="displayworkinjury()"><label style="font-size: 13px;">Non-Work Related</label>
                                                
                                                </div>
                                            </div>
                                            
                                            @if($record->injury_type=='work_related')
                                            <div class="form-group row" id="injurywrkcases">
                                              <label  class="col-sm-4 col-form-label" for="number"style="font-size:13px;padding-left:29px;">Work Related Injury Details</label>
                                                <div class="col-sm-8">
                                                    <select class="js-select2 form-control" name = "work_injury[]" id="work_injury" style="font-size: 13px;width:525px;" multiple onchange="displayotherworkinjury()"> 
                                                        <?php
                                                           $workcase = explode(',',$record->workrelated_injury);                          
                                                        ?>          
                                                        @foreach($injurywrkcase as $injw)                       
                                                        <option value="{{$injw->id}}" {{ (collect($workcase)->contains($injw->id)) ? 'selected':'' }}>{{$injw->name}}</option>
                                                       
                                                        @endforeach 
                                                        <option value="other" {{ (collect($workcase)->contains('other')) ? 'selected':'' }}>Other</option>    
                                                    </select>
                                                </div>
                                            </div>
                                            @else
                                            <div class="form-group row" id="injurywrkcases" style="display:none;">
                                              <label  class="col-sm-4 col-form-label" for="number"style="font-size: 13px;padding-left:29px;">Work Related Injury Details<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select class="js-select2 form-control" name = "work_injury[]" id="work_injury" style="font-size: 13px;width:525px;" multiple onchange="displayotherworkinjury()">            
                                                        @foreach($injurywrkcase as $injw)
                                                        <option  value="{{$injw->id}}">{{$injw->name}}</option>
                                                        @endforeach 
                                                        <option value="other">Other</option>    
                                                    </select>
                                                </div>
                                            </div>
                                            @endif

                                            @if($record->workrelated_injury_other!=NULL)
                                            <div class="form-group row" id="injurywrkothrcase">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                                 <div class="col-sm-8">
                                                    <textarea type="text"style="font-size: 13px;" class="form-control" id="workinjury_oth" name="workinjury_oth" placeholder="Please specify Work Related Injury Details">{{$record->workrelated_injury_other}}</textarea>
                                                 </div>
                                            </div>
                                            @else
                                            <div class="form-group row" id="injurywrkothrcase"  style="display:none;">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                                 <div class="col-sm-8">
                                                    <textarea type="text"style="font-size: 13px;" class="form-control" id="workinjury_oth" name="workinjury_oth" placeholder="Please specify Work Related Injury Details">{{$record->workrelated_injury_other}}</textarea>
                                                 </div>
                                            </div>
                                            @endif

                                            @if($record->injury_type=='non_work_related')
                                            <div class="form-group row" id="injurynonwrkcases">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"><!--Non-Work Related Injury Details<span class="text-danger">*</span>--></label>
                                                 <div class="col-sm-8">
                                                    <textarea type="text"style="font-size: 13px;" class="form-control" id="nonwork_injury" name="nonwork_injury" placeholder="Please specify Non-work Related Injury Details">{{$record->nonwork_related_injury}}</textarea>
                                                 </div>
                                            </div>
                                            @else
                                            <div class="form-group row" id="injurynonwrkcases" style="display:none;">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"><!--Non-Work Related Injury Details<span class="text-danger">*</span>--></label>
                                                 <div class="col-sm-8">
                                                    <textarea type="text"style="font-size: 13px;" class="form-control" id="nonwork_injury" name="nonwork_injury" placeholder="Please specify Non-work Related Injury Details"></textarea>
                                                 </div>
                                            </div>
                                            @endif
                                           
                               
                                            @if($record->injury_type=='work_related')
                                            <div class="form-group row" id="worknatureinjury">
                                                <label class="col-sm-4 col-form-label" for="hospital"style="font-size: 13px;padding-left:29px;">Nature Of Injury</label>
                                                    <div class="col-sm-8">
                                                        
                                                      @if($record->natureofinjury=='-')
                                                        <select class="js-select2 form-control" name = "work_nature[]" id="work_nature" style="font-size: 13px;" multiple data-placeholder='Choose Nature of Injury'>
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>
                                                            <?php 
                                                              $injury_nature = DB::table('employee_injury_nature_details')
                                                                                                                    ->where('injury_id',$record->id)
                                                                                                                    ->pluck('natureofinjury')
                                                                                                                    ->toArray();                                                                
                                                              $natureinjury = DB::table('employee_health_injury_natures')->where('injury_type','work_related')->get();
                                                            ?>
                                                            @foreach($natureinjury as $nature)
                                                            <option value="{{$nature->id}}" {{ (collect($injury_nature)->contains($nature->id)) ? 'selected':'' }}>{{$nature->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @else
                                                        <textarea type="text"style="font-size: 13px;"  class="form-control"  name="work_nature" id="work_nature" >{{$record->natureofinjury}}</textarea>
                                                        @endif
                                                    </div>
                                            </div>
                                            @else
                                            <div class="form-group row" id="worknatureinjury" style="display:none;">
                                            <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;padding-left:29px;">Nature Of Injury </label>
                                                <div class="col-sm-8">
                                                       <select class="js-select2 form-control" name = "work_nature[]" id="work_nature"  style="font-size: 13px;width:525px;" multiple data-placeholder="Choose Nature of Injury">
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>                                
                                                        </select>
                                                </div>
                                            </div>
                                            @endif

                                            @if($record->injury_type=='non_work_related')
                                            <div class="form-group row" id="non_worknatureinjury">
                                                <label class="col-sm-4 col-form-label" for="hospital"style="font-size: 13px;padding-left:29px;">Nature Of Injury</label>
                                                    <div class="col-sm-8"> 
                                                      @if($record->natureofinjury=='-') 
                                                        <select class="js-select2 form-control" name = "non_work_nature[]" id="non_work_nature" style="font-size: 13px;" multiple data-placeholder='Choose Nature of Injury'>
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>
                                                            <?php 
                                                              $injury_nature = DB::table('employee_injury_nature_details')
                                                                                                                        ->where('injury_id',$record->id)
                                                                                                                        ->pluck('natureofinjury')
                                                                                                                        ->toArray();  
                                                              $natureinjury = DB::table('employee_health_injury_natures')->where('injury_type','non_work_related')->get();
                                                            ?>
                                                            @foreach($natureinjury as $nature)
                                                            <option value="{{$nature->id}}" {{ (collect($injury_nature)->contains($nature->id)) ? 'selected':'' }}>{{$nature->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @else
                                                        <textarea type="text"style="font-size: 13px;"  class="form-control"  name = "non_work_nature[]" id="non_work_nature" >{{$record->natureofinjury}}</textarea>
                                                        @endif
                                                    </div>
                                            </div>
                                            @else
                                            <div class="form-group row" id="non_worknatureinjury" style="display:none;">
                                            <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;padding-left:29px;">Nature Of Injury </label>
                                                <div class="col-sm-8">
                                                       <select class="js-select2 form-control" name = "non_work_nature[]" id="non_work_nature"  style="font-size: 13px;width:525px;" multiple data-placeholder="Choose Nature of Injury">
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>                                
                                                        </select>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="form-group row" id="bodyselectcheck">
                                                <label class="col-sm-4 col-form-label" for="bodyinjprt" style="font-size: 13px;padding-left:29px;">Part of the Body Injured  </label>
                                                    <div class="col-sm-8">
                                                        <?php 
                                                           $m = 0; 
                                                           $items_per_row = 5; 
                                                           $injpart = explode(',',$record->bodily_location);
                                                        ?>
                                                        @foreach($injuredbodyprt as $bdyp)                       
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$bdyp}}"  id="body_injured"   name="body_injured[]" {{ (collect($injpart)->contains($bdyp)) ? 'checked' : ''}}><label style="font-size: 13px;">{{$bdyp}}</label>
                                                        <?php 
                                                           $m++; 
                                                           if($m % $items_per_row == 0){ ?><br><?php } ?>                                    
                                                        @endforeach
                                                        <br>
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other"   name="body_injured[]" id = "body_injured_other" {{ (collect($injpart)->contains('other')) ? 'checked' : ''}} onclick="injured_display()"><label style="font-size: 13px;">Other</label>
                                                    </div>
                                            </div>

                                            @if($record->bodily_location=='other')
                                             <div class="form-group row" id="injuredbdyoth">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;padding-left:29px;">&nbsp;</label>
                                                   <div class="col-sm-8">
                                                       <input type="text" class="form-control" name = "body_injured_oth" id="body_injured_oth" value="{{$record->body_injured_other}}" placeholder="Please specify injured body part" style="font-size: 13px;">
                                                   </div>
                                            </div>
                                            @else
                                            <div class="form-group row" style="display: none;" id="injuredbdyoth">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                                   <div class="col-sm-8">
                                                       <input type="text" class="form-control" name = "body_injured_oth" id="body_injured_oth" value="" placeholder="Please specify injured body part" style="font-size: 13px;">
                                                   </div>
                                            </div>
                                            @endif

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="locaat" style="font-size: 13px;padding-left:29px;">Location At Time of Injury </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"style="font-size: 13px;"  class="form-control" id="location" name="location" placeholder="">{{$record->location}}</textarea>
                                                    </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="injury_cause" style="font-size: 13px;padding-left:29px;">How was the Injury (Cause of Injury) </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"style="font-size: 13px;" required class="form-control" id="cause" name="cause" placeholder="">{{$record->causeofinjury}}</textarea>
                                                    </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="local"style="font-size: 13px;padding-left:29px;">Was any Plant, Equipment,Substance or Thing Involved in Injury?</label>
                                                <div class="col-md-3">
                                                    <input   style="margin-right:4px;" type="radio" value="yes" id="yes" onclick="display()" name="thing"  @if($record->things_involved=='yes') checked @endif><label style="font-size: 13px;">Yes</label>
                                                </div>
                                               <div class="col-md-3">
                                                   <input  style="margin-right:4px;" type="radio"   value="no" id="no" onclick="display()"  name="thing" @if($record->things_involved=='no') checked @endif><label style="font-size: 13px;">No</label>
                                                </div>
                                            </div>

                                            @if($record->things_involved=='yes')
                                            <div class="form-group row" id="regu">
                                                <label class="col-sm-4 col-form-label" for="things"style="font-size: 13px;padding-left:29px;">Details</label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"style="font-size: 13px;" id="things" name="things" class="form-control">{{$record->things_involved_details}}</textarea>
                                                    </div>
                                            </div>
                                            @else
                                            <div class="form-group row" style="display:none;" id="regu">
                                                <label class="col-sm-4 col-form-label" for="regulation"style="font-size: 13px;padding-left:29px;"> </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text" style="font-size: 13px;"class="form-control" id="things" name="things" placeholder="Enter the Details" style="display:none;"></textarea>
                                                    </div>
                                            </div>
                                            @endif

                                            @if(count($witnesses)>0)
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="hospital"style="font-size: 13px;padding-left:29px;">Witness</label>
                                        <div class="col-sm-8">
                                            <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable"  >
                                                    <thead style="background-color:#FFFFFF">
                                                        <tr>
                                                            <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">SL No.</th>
                                                            <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Witness Name</th>
                                                            <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Witness Mobile No.</th>
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
                                                                {{$wit->number}}
                                                                </td>
                                                            </tr> 
                                                    @endforeach
                                                    </tbody>
                                            </table>
                                        </div>  
                                    </div>  
                              @endif

                                     <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" for="injury_reported" style="font-size: 13px;padding-left:29px;"> Has The Injury Been Reported To The Workers Supervisor? <span class="text-danger">*</span></label>
                                           <div class="col-md-3">
                                                <input   style="margin-right:4px;" type="radio" value="yes" <?php if($record->reported_to_supervisor=='yes'){ ?> checked <?php } ?>  name="supervisor" required><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input  style="margin-right:4px;" type="radio"   value="no"  <?php if($record->reported_to_supervisor=='no'){ ?> checked <?php } ?> name="supervisor"><label style="font-size: 13px;">No</label>
                                            </div>
                                     </div>

                                     <div class="form-group row">
                                              <label class="col-sm-4 col-form-label" for="treatment_provided"style="font-size: 13px;padding-left:29px;">Was any Treatment Provided? <span class="text-danger">*</span></label>
                                                <div class="col-md-3">
                                                    <input   style="margin-right:4px;" type="radio" value="yes" id="yes1" <?php if($record->treatment_provided=='yes'){ ?> checked <?php } ?> onclick="display1()" name="treatment" required><label style="font-size: 13px;">Yes</label>
                                                </div>
                                               <div class="col-md-3">
                                                 <input  style="margin-right:4px;" type="radio"   value="no" id="no1"  <?php if($record->treatment_provided=='no'){ ?> checked <?php } ?> onclick="display1()"  name="treatment"><label style="font-size: 13px;">No</label>
                                               </div>
                                            </div>

                                            @if($record->treatment_provided=='yes')
                                            <div class="form-group row"  id="treat">
                                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        <?php
                                                          $treatmentdata = explode(',',$record->treatment_details);
                                                        ?>
                                                        @foreach($treatment as $treat)         
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$treat->id}}" {{(collect($treatmentdata)->contains($treat->id)) ? 'checked':''}}  name="treatments[]" id="treatments"><label style="font-size: 13px;">{{$treat->name}}</label>
                                                        <br>
                                                        @endforeach     
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other" {{(collect($treatmentdata)->contains('other')) ? 'checked':''}}  name="treatments[]" id = "treatments_other" onclick="treatmentsotherdisplay()"><label style="font-size: 13px;">Other</label>                    
                                                    </div>
                                            </div>
                                            @else
                                            <div class="form-group row" style="display:none;" id="treat">
                                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        @foreach($treatment as $treat)
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$treat->id}}"   name="treatments[]" id="treatments"><label style="font-size: 13px;">{{$treat->name}}</label>
                                                        <br>
                                                        @endforeach     
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other"   name="treatments[]" id = "treatments_other" onclick="treatmentsotherdisplay()"><label style="font-size: 13px;">Other</label>                    
                                                    </div>
                                            </div>
                                            @endif
                                       
                                            @if($record->treatmentdetails_other!=NULL)
                                            <div class="form-group row"  id="treatmentsother">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                                   <div class="col-sm-8">
                                                       <textarea type="text" style="font-size:13px;width:490px;" class="form-control" name = "treatment_oth" id="treatment_oth" placeholder="Please specify treatment provided">{{$record->treatmentdetails_other}}</textarea>
                                                   </div>
                                            </div>
                                            @else
                                            <div class="form-group row" style="display: none;" id="treatmentsother">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                                   <div class="col-sm-8">
                                                       <textarea type="text" style="font-size:13px;width:490px;" class="form-control" name = "treatment_oth" id="treatment_oth" placeholder="Please specify treatment provided"></textarea>
                                                   </div>
                                            </div>
                                            @endif

                                            @if($record->status=='Cured') 
                                         <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="local"style="font-size: 13px;">Did the Injured Worker Return To Work Following the Injury?</label>
                                                <div class="col-md-3">
                                                <input   style="margin-right:4px;" type="radio" value="yes" id="yes2" onclick="display2()"  name="returnwork" @if($record->injured_returnto_work=='yes') checked @endif><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input  style="margin-right:4px;" type="radio"   value="no" id="no2" onclick="display2()"  name="returnwork"@if($record->injured_returnto_work=='no') checked @endif><label style="font-size: 13px;">No</label>
                                            </div>
                                            </div>

                                            @if($record->injured_returnto_work=='yes')
                                            <div class="form-group row"  id="return">
                                                <label class="col-sm-4 col-form-label" for="regulation"style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"  style="font-size: 13px;"class="form-control" id="returnworks" name="returnworks" placeholder="Enter The Details" style="display:none;">{{$record->injured_returnto_work_details }}</textarea>
                                                    </div>
                                            </div>
                                            @else
                                            <div class="form-group row" style="display:none;" id="return">
                                                <label class="col-sm-4 col-form-label" for="regulation"style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"  style="font-size: 13px;"class="form-control" id="returnworks" name="returnworks" placeholder="Enter The Details" style="display:none;"></textarea>
                                                    </div>
                                            </div>
                                            @endif
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="local"style="font-size: 13px;">#of Medical Leaves(in Hours)</label>
                                                <div class="col-md-3">
                                                <input type="text"style="font-size: 13px;" required class="form-control" id="leaves" name="leaves" value="{{$record->medical_leaves}}"  required placeholder="Leaves in Hours">
                                            </div>
                                            </div>
                                          @endif
                               
                                            
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-md-6 col-lg-12 col-sm-2 col-form-label" for="diastolic" style="font-size: 13px;">Blood Pressure Diastolic</label>
                                                    <div class="col-md-6 col-lg-12 col-sm-2">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="bloodpd" name="bloodpd" placeholder="Enter Blood Pressure Diastolic" value="{{$record->injured_bp_diastolic}}">
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-md-6 col-lg-12 col-sm-2 col-form-label" for="systolic"style="font-size: 13px;">Blood Pressure Systolic</label>
                                                    <div class="col-md-6 col-lg-12 col-sm-2">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="bps" name="bps" placeholder="Enter Blood Pressure Systolic" value="{{$record->injured_bp_systolic}}" >
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="blood_sugar" style="font-size: 13px;">Blood Sugar Level</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="bsl" name="bsl" placeholder="Enter Blood Sugar Level" value="{{$record->injured_bs_level}}">
                                                    </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="pulse" style="font-size:13px;">Pulse</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="pulse" name="pulse" value="{{$record->pulse}}" placeholder="Enter Pulse">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="temp" style="font-size:13px;">Temperature</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="temp" name="temp" value="{{$record->temperature}}" placeholder="Enter Temperature">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="respiratory_rate" style="font-size:13px;">Respiratory Rate</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="respiratory_rate" name="respiratory_rate" value="{{$record->respiratory_rate}}" placeholder="Enter Respiratory Rate">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="weight" style="font-size:13px;">Weight</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="weight" name="weight" value="{{$record->weight}}" placeholder="Enter Weight">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="height" style="font-size:13px;">Height</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="height" name="height" value="{{$record->height}}" placeholder="Enter Height">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="remarks" style="font-size: 13px;">Remarks (if any)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="remark" name="remark" value="{{$record->remarks}}" placeholder="Enter Remarks (if any)">
                                                    </div>
                                            </div>

                                             <div class="form-group" style="display:inline-block;width:49%;">
                                               <label  class="col-sm-12 col-form-label" for="number"style="font-size: 13px;">Status</label>
                                                 <div class="col-sm-12">
                                                    <select class="form-control"  name ="status"  style="font-size: 13px;width:350px;">
                                                        <option  value="">Please Select Status</option>
                                                        @foreach($status as $stu)
                                                        @if($record->status==$stu) 
                                                        <option  value="{{$stu}}" selected>{{$stu}}</option>
                                                        @else
                                                        <option  value="{{$stu}}">{{$stu}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="form-group row" style="margin-top:15px;margin-bottom:15px;">
                                            <div class="col-sm-12 text-center">
                                               <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;"  onclick="return confirm('Do you want to proceed ? ')">Update </button>
                                            </div> 
                                            </div>
                                        </div>
                       
                                    </form>
                            <!-- update form ends here -->
                        </div>
                   </div>
        </div>
     </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        let CSRF_TOKEN = "";
        window.onload = function() {
            CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        }

        function displaypersontype()
        {
            if(document.getElementById('contractor').checked)
            {
                document.getElementById('contractordetails').style.display="";
                document.getElementById('contractor_name').required= true;
                document.getElementById('nocontractordetails').style.display="none";
                document.getElementById('injured_person_other').required= false;
            }
            else
            {
                document.getElementById('nocontractordetails').style.display="";
                document.getElementById('injured_person_other').required= true;
                document.getElementById('contractordetails').style.display="none";
                document.getElementById('contractor_name').required= false;
            }
        }

        function display(){
        if(document.getElementById('yes').checked){
        document.getElementById('regu').style.display="";
        document.getElementById('things').required= true;
        }
        else if(document.getElementById('no').checked){
        document.getElementById('regu').style.display="none";
        document.getElementById('things').required= false;
        }
        }

  function display1()
    {
        if(document.getElementById('yes1').checked)
        {
            document.getElementById('treat').style.display="";
        }
        else if(document.getElementById('no1').checked)
        {
            document.getElementById('treat').style.display="none";
        }
    }

  function validateform()
  {
      //checking validation for body injured part
        var checked = 0;
        var bodysel = document.getElementById("bodyselectcheck");
        var chks = bodysel.getElementsByTagName("INPUT");
 
        //Loop and count the number of checked CheckBoxes.
        for (var i = 0; i < chks.length; i++) {
            if (chks[i].checked) {
                checked++;
            }
        }
 
        if (checked > 0) {
            
        } else {
            alert("Please Select Part of the Body Injured");
            return false;
        }

    //checking validation for treatment provided
    if(document.getElementById('yes1').checked)
        {
            var checked = 0;
            var tretsel = document.getElementById("treat");
            var chks = tretsel.getElementsByTagName("INPUT");
 
            //Loop and count the number of checked CheckBoxes.
            for (var i = 0; i < chks.length; i++) {
                if (chks[i].checked) {
                    checked++;
                }
            }
    
            if (checked > 0) {
            } else {
                alert("Please Select treatment Provided");
                document.getElementById("bodyselectcheck").focus();
                return false;
            }
        }
  }



function display2()
  {
   if(document.getElementById('yes2').checked)
      {
        document.getElementById('return').style.display="";
        document.getElementById('returnworks').required= true;
        }
    else if(document.getElementById('no2').checked)
      {
        document.getElementById('return').style.display="none";
        document.getElementById('returnworks').required= false;
        }
    }

    function displayworkinjury()
        {
          if(document.getElementById('work_related').checked)
            {
                document.getElementById('injurywrkcases').style.display="";
                document.getElementById('work_injury').required= true;
                document.getElementById('injurynonwrkcases').style.display="none";
                document.getElementById('nonwork_injury').required= false;            
                document.getElementById('worknatureinjury').style.display="";
                document.getElementById('non_worknatureinjury').style.display="none";

                var options = $('#work_nature');
                var inj_type = "#work_related";
                
                document.getElementById('work_nature').required= true;
                document.getElementById('non_work_nature').required= false;
            }
          else if(document.getElementById('work_related1').checked) 
           {
              document.getElementById('injurynonwrkcases').style.display="";
              document.getElementById('nonwork_injury').required= true;
              document.getElementById('injurywrkcases').style.display="none";
              document.getElementById('work_injury').required= false;
              document.getElementById('non_worknatureinjury').style.display="";
              document.getElementById('worknatureinjury').style.display="none";

              var options = $('#non_work_nature');
              var inj_type = "#work_related1";
                
              document.getElementById('work_nature').required= false;
              document.getElementById('non_work_nature').required= true;
           }
           $.ajax({
                        data: {
                                "inj_type": $(inj_type).val()
                                , _token: CSRF_TOKEN
                            }          
                        , url: "{{ route('selectinjurytype')}}"
                        , type: "POST"
                        , success:function(response) {
                           if(response.status == 200)
                                 {
                                    options.empty(); // clear dropdown values
                                        $.each(response.data, function (val, text) {
                                        options.append(
                                            $('<option></option>').val(text.id).html(text.name)
                                        );
                                      });                   
                                    
                                 }
                         }
                           
                });
        }

       function displayotherworkinjury()
       {
           if(document.getElementById('work_injury').value =='other')
            {
                document.getElementById('injurywrkothrcase').style.display="";
                document.getElementById('workinjury_oth').required= true;
            }
           else
           {
                document.getElementById('injurywrkothrcase').style.display="none";
                document.getElementById('workinjury_oth').required= false;
           }
       }
     
       function injured_display()
        {
          if(document.getElementById('body_injured_other').checked)
            {
                document.getElementById('injuredbdyoth').style.display="";
                document.getElementById('body_injured_oth').required= true;
            }
          else 
           {
            document.getElementById('injuredbdyoth').style.display="none";
            document.getElementById('body_injured_oth').required= false;
           }
        }
        
        function treatmentsotherdisplay()
       {
          if(document.getElementById('treatments_other').checked)
            {
                document.getElementById('treatmentsother').style.display="";
                document.getElementById('treatment_oth').required= true;
            }
          else 
           {
            document.getElementById('treatmentsother').style.display="none";
            document.getElementById('treatment_oth').required= false;
           }
       }

       var i = 0;
    $("#add").click(function(){
        ++i;
            $("#dynamicTable").append('<tr><td> <input type="text" name="doc['+i+'][name]" style="font-size: 13px;"class="form-control"  ><td> <input type="text" name="doc['+i+'][number]" style="font-size: 13px;"class="form-control"  ></td><td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove witness"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
    });
        $(document).on('click', '.remove-tr', function(){
    $(this).parents('tr').remove();
    });
    </script>


 <!-- Apps Modal -->
           
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
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/register">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Employee Health Record
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/injuryrecords">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Employee Injury Record
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/occupationhealth/illnessrecords">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                  Employee Illness Record
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="{{ url('/user/occupationhealth/analysis') }}">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 " style="margin-bottom:60px">
                                                  Analysis
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
   @endsection