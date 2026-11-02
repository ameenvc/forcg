@extends('layouts.multiselect')

@section('content')

      <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Occupational Health</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Add Injury Record</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/occupationhealth/register">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>


                <!-- END Hero -->

                 <!-- Page Content -->
                 <div class="content" style="">
                   <!-- Horizontal -->
                    <div class="block block-rounded">
                        <div class="block-content block-content-full">
                            <div class="row">
                                 <div class="col-md-12">
                                     <label class="col-sm-12 col-form-label text-right" style="font-size:13px;">All fields marked with <span class="text-danger">*</span> are mandatory</label>
                                 </div>
                                <div class="col-lg-10">
                                    <!-- Form Horizontal - Default Style -->
                                    <?php $f=0; ?>
                        <form class="mb-5" action="{{route('addinjuryrecord')}}" method="POST" enctype="multipart/form-data">
                                                 <?php
                                                $today=Carbon\Carbon::now()->toDateTimeString();
                                                $today=Carbon\Carbon::parse($today);
                                                ?>
                            @csrf
                            @if(Session::has('msg'))
                                <div class="alert alert-primary">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {!!Session::get('msg')!!}
                                </div>
                            @endif
                               
                               

                                        <div class="col-lg-10">
                                        
                                        <div class="form-group row">
                                            <label  class="col-sm-4 col-form-label" for="riskgroup"style="font-size: 13px;">Category <span class="text-danger">*</span></label>
                                              <div class="col-sm-8">
                                                  <select class="form-control" name = "category" required="true" style="font-size: 13px;">
                                                    <option value="" style="font-size: 13px;">Choose Category</option>
                                                    @foreach($healthcategory as $cat)
                                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                                    @endforeach
                                                  </select>
                                               </div>
                                        </div>
                                        
                                        <!-- <div class="form-group row">
                                            <label  class="col-sm-4 col-form-label" for="name"style="font-size: 13px;">Created By</label>
                                                <div class="col-sm-8">
                                                    <input type="text" value="{{Auth::user()->name}}" class="form-control" name = "createdby" id="createdby" required="true" style="font-size: 13px;" readonly>
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="name"style="font-size: 13px;">Created Date</label>
                                            <div class="col-sm-8">
                                                <input type="text" value="{{$today->format('d-m-Y')}}" class="form-control" name = "createddate" id="createddate" required="true" style="font-size: 13px;" readonly>
                                            </div>
                                        </div> -->
                                     <!--
                                        <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="riskgroup"style="font-size: 13px;">Creator Department</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" name = "department" required="true" style="font-size: 13px;">
                                                            @if(count($depts)>0)
                                                            @foreach($depts as $dept)
                                                            <option value="{{$dept->departmentname}}">{{$dept->departmentname}}</option>
                                                            @endforeach
                                                            @endif
                                                            </select>
                                                            </div>
                                                        </div>
                                        -->
                                        <!-- <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="createrposition"style="font-size: 13px;">Creator Position</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name = "createrposition" id="createrposition" value="{{$emplydetails ? $emplydetails->emp_position : null}}"  style="font-size: 13px;" readonly >
                                            </div>
                                        </div>
                                     
                                        <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="createrdepartment"style="font-size: 13px;">Creator Department</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name = "createrdepartment" id="createrdepartment" value="{{$empdeptname}}"  style="font-size: 13px;" readonly >
                                            </div>
                                        </div> -->

     <input type="hidden" value="{{Auth::user()->name}}" class="form-control" name = "createdby" id="createdby" >	
	 <input type="hidden" value="{{$today->format('d-m-Y')}}" class="form-control" name = "createddate" id="createddate">	
	 <input type="hidden" class="form-control" name = "createrposition" id="createrposition" value="{{$emplydetails ? $emplydetails->emp_position : null}}">	 
	 <input type="hidden" class="form-control" name = "createrdepartment" id="createrdepartment" value="{{$empdeptname}}">



                                            <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="number"style="font-size: 13px;">Injured Employee Number</label>
                                                        <div class="col-sm-8">
                                                            <!--<select class="form-control" onchange="sel(this.value,'{{$empdetails}}')"  name = "number" required="true" style="font-size: 13px;">
                                                            <option>Select Employee Number</option>
                                                            @if(count($empdetails)>0)
                                                            @foreach($empdetails as $emp)
                                                            <option  value="{{$emp->emp_number}}">{{$emp->emp_number}} - {{$emp->emp_name}}</option>
                                                            @endforeach
                                                            @endif
                                                            </select>-->
                                                            <input type="text" class="form-control" name = "number" id="number" value="{{$injuredemp->emp_number}}" required="true" style="font-size: 13px;" readonly>
                                                          </div>
                                         </div>


                                            <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="name"style="font-size: 13px;">Injured Employee Name</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "name" id="name" required="true" value="{{$injuredemp->emp_name}}" style="font-size: 13px;" readonly>
                                                            </div>
                                                        </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="dob"style="font-size: 13px;">Injured Employee Date of Birth</label>
                                                    <div class="col-sm-8">
                                                    <input type="text" class="form-control" name = "dob" id="dob" required="true" value="{{$injuredemp->emp_dob == null ? null : $injuredemp->emp_dob}}" style="font-size: 13px;" readonly>
                                                    </div>
                                            </div>
                                            <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="department"style="font-size: 13px;">Injured Employee Department</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "department" id="department" required="true" value="{{$injempdeptname}}" style="font-size: 13px;" readonly>
                                                            </div>
                                                        </div>
                                            <div class="form-group row">
                                                    <label  class="col-sm-4 col-form-label" for="position"style="font-size: 13px;">Injured Employee Position</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name = "position" id="position" value="{{$injuredemp->emp_position}}" required="true" style="font-size: 13px;" readonly>
                                                            </div>
                                                        </div>



                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="date_timeinj"style="font-size: 13px;">Date and Time of Injury <span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                 <input type="date" class="js-datepicker form-control"  id="injdate" name="injdate" required data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                             </div>
                                                <div class="col-sm-4">
                                                    <input type="time" class="form-control" name="time" style="font-size: 13px;" placeholder="Time" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" style="font-size: 13px;">Type of Injury <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                   <input style="margin-right:4px;margin-left:10px;" type="radio" value="work_related" id="work_related"  name="injury_type" onclick="displayworkinjury()" required><label style="font-size: 13px;">Work Related</label>
                                                   <input style="margin-right:4px;margin-left:10px;" type="radio" value="non_work_related" id="work_related1"  name="injury_type" onclick="displayworkinjury()"><label style="font-size: 13px;">Non-Work Related</label>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row" id="injurywrkcases" style="display:none;">
                                              <label  class="col-sm-4 col-form-label" for="number"style="font-size: 13px;">Work Related Injury Details<span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                    <select class="js-select2 form-control" name = "work_injury[]" id="work_injury" style="font-size: 13px;width:525px;" multiple onchange="displayotherworkinjury()">            
                                                        @foreach($injurywrkcase as $injw)
                                                        <option  value="{{$injw->id}}">{{$injw->name}}</option>
                                                        @endforeach 
                                                        <option value="other">Other</option>    
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row" id="injurywrkothrcase" style="display:none;">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                                 <div class="col-sm-8">
                                                    <textarea type="text"style="font-size: 13px;" class="form-control" id="workinjury_oth" name="workinjury_oth" placeholder="Please specify Work Related Injury Details"></textarea>
                                                 </div>
                                            </div>


                                            <div class="form-group row" id="injurynonwrkcases" style="display:none;">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;"><!--Non-Work Related Injury Details<span class="text-danger">*</span>--></label>
                                                 <div class="col-sm-8">
                                                    <textarea type="text"style="font-size: 13px;" class="form-control" id="nonwork_injury" name="nonwork_injury" placeholder="Please specify Non-work Related Injury Details"></textarea>
                                                 </div>
                                            </div>
                                           

                                            <!-- <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;">Nature Of Injury <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                       
                                                        <select class="form-control" name = "nature" required="true" style="font-size: 13px;">
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>
                                                            @foreach($natureinjury as $nature)
                                                            <option value="{{$nature->id}}">{{$nature->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div> -->
                                            <div class="form-group row" id="worknatureinjury" style="display:none;">
                                            <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;">Nature Of Injury <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                       <select class="js-select2 form-control" name = "work_nature[]" id="work_nature"  style="font-size: 13px;width:525px;" multiple data-placeholder="Choose Nature of Injury">
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>                                
                                                        </select>
                                                </div>
                                            </div>

                                            <div class="form-group row" id="non_worknatureinjury" style="display:none;">
                                            <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;">Nature Of Injury <span class="text-danger">*</span></label>
                                                <div class="col-sm-8">
                                                       <select class="js-select2 form-control" name = "non_work_nature[]" id="non_work_nature"  style="font-size: 13px;width:525px;" multiple data-placeholder="Choose Nature of Injury">
                                                            <option value="" style="font-size: 13px;">Choose Nature of Injury</option>                                
                                                        </select>
                                                </div>
                                            </div>

                                            <!--<div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="hospital"style="font-size: 13px;">Bodily Location of Injury</label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"style="font-size: 13px;" required class="form-control" id="blocation" name="blocation" placeholder=""></textarea>
                                                    </div>
                                            </div>-->

                                            <div class="form-group row" id="bodyselectcheck">
                                                <label class="col-sm-4 col-form-label" for="bodyinjprt" style="font-size: 13px;">Part of the Body Injured <span class="text-danger">*</span> </label>
                                                    <div class="col-sm-8">
                                                        <?php 
                                                           $m = 0; 
                                                           $items_per_row = 5; 
                                                        ?>
                                                        @foreach($injuredbodyprt as $bdyp)                       
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$bdyp}}" id="body_injured"   name="body_injured[]" ><label style="font-size: 13px;">{{$bdyp}}</label>
                                                        <?php 
                                                           $m++; 
                                                           if($m % $items_per_row == 0){ ?><br><?php } ?>                                    
                                                        @endforeach
                                                        <br>
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other"   name="body_injured[]" id = "body_injured_other" onclick="injured_display()"><label style="font-size: 13px;">Other</label>
                                                    </div>
                                            </div>

                                            <div class="form-group row" style="display: none;" id="injuredbdyoth">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                                   <div class="col-sm-8">
                                                       <input type="text" class="form-control" name = "body_injured_oth" id="body_injured_oth" value="" placeholder="Please specify injured body part" style="font-size: 13px;">
                                                   </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="locaat" style="font-size: 13px;">Location At Time of Injury <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"style="font-size: 13px;" required class="form-control" id="location" name="location" placeholder=""></textarea>
                                                    </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="injury_cause" style="font-size: 13px;">How was the Injury (Cause of Injury) <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text"style="font-size: 13px;" required class="form-control" id="cause" name="cause" placeholder=""></textarea>
                                                    </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="local"style="font-size: 13px;">Was any Plant, Equipment,Substance or Thing Involved in Injury? <span class="text-danger">*</span></label>
                                                <div class="col-md-3">
                                                <input   style="margin-right:4px;" type="radio" value="yes" id="yes" onclick="display()" name="thing" required><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input  style="margin-right:4px;" type="radio"   value="no" id="no" onclick="display()"  name="thing"><label style="font-size: 13px;">No</label>
                                            </div>
                                            </div>

                                            <div class="form-group row" style="display:none;" id="regu">
                                                <label class="col-sm-4 col-form-label" for="regulation"style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text" style="font-size: 13px;"class="form-control" id="things" name="things" placeholder="Enter the Details" style="display:none;"></textarea>
                                                    </div>
                                            </div>

                                    </div>

                                    <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="witness"style="font-size:13px;padding-left:29px;">Enter the Witness (If any)</label>
                                        <div class="col-md-9" style="padding-left:50px;">
                                     <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable" style="width:525px;">
                                        <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:13px;;text-transform:capitalize">Witness Name</th>
                                        <th class="text-center" style="font-size:13px;;text-transform:capitalize">Mobile No.</th>
                                        <th class="text-center" style="font-size:13px;;text-transform:capitalize"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                        <td>
                                        <input type="text" name="doc[0][name]" style="font-size: 13px;"class="form-control"  >
                                             </td>
                                             <td>
                                        <input type="text" name="doc[0][number]" style="font-size: 13px;"class="form-control">
                                             </td>

                                    <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add witness">
                                            <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>
                                        </tr>
                                </tbody>
                            </table>
                              </div>
                              </div>


                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="injury_reported" style="font-size: 13px;padding-left:29px;"> Has The Injury Been Reported To The Workers Supervisor? <span class="text-danger">*</span></label>
                                                <div class="col-md-3">
                                                <input   style="margin-right:4px;" type="radio" value="yes"   name="supervisor" required><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input  style="margin-right:4px;" type="radio"   value="no"   name="supervisor"><label style="font-size: 13px;">No</label>
                                            </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="treatment_provided"style="font-size: 13px;padding-left:29px;">Was any Treatment Provided? <span class="text-danger">*</span></label>
                                                <div class="col-md-3">
                                                <input   style="margin-right:4px;" type="radio" value="yes" id="yes1" onclick="display1()" name="treatment" required><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input  style="margin-right:4px;" type="radio"   value="no" id="no1" onclick="display1()"  name="treatment"><label style="font-size: 13px;">No</label>
                                            </div>
                                            </div>

                                            <div class="form-group row" style="display:none;" id="treat">
                                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        <!--<textarea type="text" required style="font-size: 13px;"class="form-control" id="treatments" name="treatments" placeholder="Enter The Details" style="display:none;"></textarea>-->
                                                        @foreach($treatment as $treat)
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$treat->id}}"   name="treatments[]" id="treatments"><label style="font-size: 13px;">{{$treat->name}}</label>
                                                        <br>
                                                        @endforeach     
                                                        <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other"   name="treatments[]" id = "treatments_other" onclick="treatmentsotherdisplay()"><label style="font-size: 13px;">Other</label>                    
                                                    </div>
                                            </div>

                                            <div class="form-group row" style="display: none;" id="treatmentsother">
                                              <label  class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                                   <div class="col-sm-8">
                                                       <textarea type="text" style="font-size:13px;width:490px;" class="form-control" name = "treatment_oth" id="treatment_oth" placeholder="Please specify treatment provided"></textarea>
                                                   </div>
                                            </div>
                            <!--
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="local"style="font-size: 13px;">Did the Injured Worker Return To Work Following the Injury?</label>
                                                <div class="col-md-3">
                                                <input   style="margin-right:4px;" type="radio" value="yes" id="yes2" onclick="display2()" name="returnwork"><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input  style="margin-right:4px;" type="radio"   value="no" id="no2" onclick="display2()"  name="returnwork"><label style="font-size: 13px;">No</label>
                                            </div>
                                            </div>

                                            <div class="form-group row" style="display:none;" id="return">
                                                <label class="col-sm-4 col-form-label" for="regulation"style="font-size: 13px;"> </label>
                                                    <div class="col-sm-8">
                                                        <textarea type="text" required style="font-size: 13px;"class="form-control" id="returnworks" name="returnworks" placeholder="Enter The Details" style="display:none;"></textarea>
                                                    </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label" for="local"style="font-size: 13px;">#of Medical Leaves</label>
                                                <div class="col-md-3">
                                                <input type="text"style="font-size: 13px;" required class="form-control" id="leaves" name="leaves"  required placeholder="No.of leaves">
                                            </div>
                                            </div>
-->

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="diastolic" style="font-size: 13px;">Blood Pressure Diastolic</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="bloodpd" name="bloodpd" placeholder="Enter Blood Pressure Diastolic">
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="systolic"style="font-size: 13px;">Blood Pressure Systolic</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="bps" name="bps" placeholder="Enter Blood Pressure Systolic">
                                                    </div>
                                            </div>
                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="blood_sugar" style="font-size: 13px;">Blood Sugar Level</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="bsl" name="bsl" placeholder="Enter Blood Sugar Level">
                                                    </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="pulse" style="font-size:13px;">Pulse</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="pulse" name="pulse" value="" placeholder="Enter Pulse">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="temp" style="font-size:13px;">Temperature</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="temp" name="temp" value="" placeholder="Enter Temperature">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="respiratory_rate" style="font-size:13px;">Respiratory Rate</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="respiratory_rate" name="respiratory_rate" value="" placeholder="Enter Respiratory Rate">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="weight" style="font-size:13px;">Weight</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="weight" name="weight" value="" placeholder="Enter Weight">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="height" style="font-size:13px;">Height</label>
                                                <div class="col-sm-12">
                                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="height" name="height" value="" placeholder="Enter Height">
                                                </div>
                                            </div>

                                            <div class="form-group" style="display:inline-block;width:49%;">
                                                <label class="col-sm-12 col-form-label" for="remarks" style="font-size: 13px;">Remarks (if any)</label>
                                                    <div class="col-sm-12">
                                                        <input type="text"style="font-size: 13px;width:350px;"  class="form-control" id="remark" name="remark" placeholder="Enter Remarks (if any)">
                                                    </div>
                                            </div>

                                             <div class="form-group" style="display:inline-block;width:49%;">
                                               <label  class="col-sm-12 col-form-label" for="number"style="font-size: 13px;">Status</label>
                                                 <div class="col-sm-12">
                                                    <select class="form-control"  name ="status"  style="font-size: 13px;width:350px;">
                                                        <option  value="">Please Select Status</option>
                                                        @foreach($status as $stu)
                                                        <option  value="{{$stu}}">{{$stu}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                        <br/>
                                    <div style="text-align:center;">
                                        <!--<button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Add </button>-->
                                        <!--<button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return validatebodyinjured(); return chktreatmentprd();">Add </button>-->
                                        <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px; float:right;margin-right:18%;" data-toggle="click-ripple" onclick="return validateform();">Add </button>
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
                                                <p class="font-w600 font-size-sm mt-2" style="margin-bottom:60px">
                                                  Analysis
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

let CSRF_TOKEN = "";
    window.onload = function() {
        CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    }


function sel(value,details){
    datas=JSON.parse(details);
    for(var key in datas){
        if(datas[key].emp_number===value)
        {
            document.getElementById('name').value=datas[key].emp_name;
            document.getElementById('department').value=datas[key].emp_department;
            document.getElementById('position').value=datas[key].emp_position;
            document.getElementById('dob').value=datas[key].emp_dob;
        }
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
        //Reference the div
        var bodysel = document.getElementById("bodyselectcheck");
 
        //Reference all the CheckBoxes in Table.
        var chks = bodysel.getElementsByTagName("INPUT");
 
        //Loop and count the number of checked CheckBoxes.
        for (var i = 0; i < chks.length; i++) {
            if (chks[i].checked) {
                checked++;
            }
        }
 
        if (checked > 0) {
            //alert(checked + " CheckBoxe(s) are checked.");
           // return true;
        } else {
            alert("Please Select Part of the Body Injured");
            //document.getElementById("bodyselectcheck").focus();
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
                //alert(checked + " CheckBoxe(s) are checked.");
            // return true;
            } else {
                alert("Please Select treatment Provided");
                document.getElementById("bodyselectcheck").focus();
                return false;
            }
        }
  }



function display2(){
        if(document.getElementById('yes2').checked){
        document.getElementById('return').style.display="";
        document.getElementById('returnworks').required= true;
        }
        else if(document.getElementById('no2').checked){
        document.getElementById('return').style.display="none";
        document.getElementById('returnworks').required= false;
        }
        }


        // function displayworkinjury()
        // {
        //   if(document.getElementById('work_related').checked)
        //     {
        //         document.getElementById('injurywrkcases').style.display="";
        //         document.getElementById('work_injury').required= true;
        //         document.getElementById('injurynonwrkcases').style.display="none";
        //         document.getElementById('nonwork_injury').required= false;            
        //         document.getElementById('worknatureinjury').style.display="";
        //         document.getElementById('non_worknatureinjury').style.display="none";

        //         console.log($('#work_related').val());
                
        //          $.ajax({
        //                 data: {
        //                         "inj_type": $('#work_related').val()
        //                         , _token: CSRF_TOKEN
        //                     }          
        //                 , url: "{{ route('selectinjurytype')}}"
        //                 , type: "POST"
        //                 , success:function(response) {
        //                        //console.log(response);
        //                    if(response.status == 200)
        //                          {
        //                             //console.log(response.data);
        //                             //console.log(response.data.length);
        //                             var selectOption = $('#work_nature');                
        //                                 $.each(response.data, function (val, text) {
        //                                 selectOption.append(
        //                                     $('<option></option>').val(text.id).html(text.name)
        //                                 );
        //                               });                   
                                    
        //                          }
        //                  }
                           
        //         });
        //         document.getElementById('work_nature').required= true;
        //         document.getElementById('non_work_nature').required= false;
        //     }
        //   else if(document.getElementById('work_related1').checked) 
        //    {
        //       document.getElementById('injurynonwrkcases').style.display="";
        //       document.getElementById('nonwork_injury').required= true;
        //       document.getElementById('injurywrkcases').style.display="none";
        //       document.getElementById('work_injury').required= false;
        //       document.getElementById('non_worknatureinjury').style.display="";
        //       document.getElementById('worknatureinjury').style.display="none";

        //         console.log($('#work_related1').val());
                
        //          $.ajax({
        //                 data: {
        //                         "inj_type": $('#work_related1').val()
        //                         , _token: CSRF_TOKEN
        //                     }          
        //                 , url: "{{ route('selectinjurytype')}}"
        //                 , type: "POST"
        //                 , success:function(response) {
        //                    if(response.status == 200)
        //                          {
        //                             var selectOption = $('#non_work_nature');
        //                             $.each(response.data, function (val, text) {
        //                                 selectOption.append(
        //                                     $('<option></option>').val(text.id).html(text.name)
        //                                 );
        //                             });
                                    
        //                          }
        //                  }
                           
        //         });
        //         document.getElementById('work_nature').required= false;
        //         document.getElementById('non_work_nature').required= true;
        //    }
        // }

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

   @endsection
