@extends('layouts.multiselect')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Add Investigation Result</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/incidents/register">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>


<!-- END Hero -->
<!-- Page Content -->
<div class="content">
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{route('updatedReport')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="rid" style="font-size: 13px;">Report ID</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="rid" name="rid" value="{{$incident->report_no}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="addedby" style="font-size: 13px;">Reported By</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="addedby" name="addedby" value="{{$incident->addedby}}" readonly>
                                </div>
                            </div>
                        </div>
                        @php
                        $today=Carbon\Carbon::now()->toDateTimeString();
                        $today=Carbon\Carbon::parse($today);
                        @endphp
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Reporting Date</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="addeddate" name="addeddate" value="{{$incident->addeddate}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Department</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" name="department" value="{{$reportedby_depname}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             @if($incident->division)
                             <div class="form-group col-sm-6">
                                 <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                 <div class="col-sm-12">
                                     <div class="form-group" style="color:#666666">
                                         <input type="text" class="form-control" style="font-size: 13px;" value="{{$incident->division->name}}" readonly>
                                     </div>
                                 </div>
                             </div>
                             @endif
                             @if($incident->subdiv)
                             <div class="form-group col-sm-6">
                                 <label class="col-sm-12 col-form-label" style="font-size:13px;" for="department">Subdivision</label>
                                 <div class="col-sm-12">
                                     <div class="form-group" style="color:#666666">
                                         <input type="text" class="form-control" style="font-size: 13px;" value="{{$incident->subdiv->name}}" readonly>
                                     </div>
                                 </div>
                             </div>
                             @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="area" style="font-size: 13px;">Area <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="area" name="area" readonly>{{$incident->area}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Type <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->incident_type}}" required class="form-control" id="type" name="type" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;">Any Person Injured / Suffered</label>
                                <div class="col-sm-12">
                                    @if($incident->any_person_injured=='yes')
                                    <input style="font-size: 13px;" type="text" value="yes" id="person_injured" name="person_injured" value="Yes" class="form-control" readonly>
                                    @elseif($incident->any_person_injured=='no')
                                    <input style="font-size: 13px;" type="text" value="no" id="person_injured1" name="person_injured" value="No" class="form-control" readonly />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($incident->any_person_injured=='yes')
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Name of the injured / suffered person</label>
                                @if($incident->injuredperson=='other')
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injempname_oth" name="injempname_oth" value="{{$incident->injured_person_oth_name}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injempid_oth" name="injempid_oth" value="{{$incident->injured_person_oth_empid}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injposition_oth" name="injposition_oth" value="{{$incident->injured_person_oth_position}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injdept_oth" name="injdept_oth" value="{{$injured_deptname}}">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injper" name="injper" value="{{$incident->injuredperson}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injempid" name="injempid" value="{{$incident->empid}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injposition" name="injposition" value="{{$incident->injured_person_position}}">
                                </div>
                                <br />
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" readonly class="form-control" id="injdept" name="injdept" value="{{$injured_deptname}}">
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="hospital" style="font-size: 13px;">Name of Hospital Injured / Suffered Person attended/ Admitted <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->hospital}}" required class="form-control" id="hospital" name="hospital" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="place" style="font-size: 13px;">Place of the Incident</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="place" name="place" readonly value="{{$incident->incidentplace}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date and Time of Incident</label>
                                <div class="col-sm-12">
                                    <input type="text" class="js-datepicker form-control" id="date" name="incidentdate" data-week-start="1" data-autoclose="true" value="{{$incident->incidentdate}}" style="font-size: 13px;" readonly>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="time" class="form-control" name="time" style="font-size: 13px;" placeholder="Time" value="{{$incident->time}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="employer" style="font-size: 13px;">Employer of the injured / suffered person (state if labour supply) <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="employer" name="employer" readonly value="{{$incident->emplyoer}}"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="trade" style="font-size: 13px;">Trade or occupation <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->trade_or_occupation}}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="desc" style="font-size: 13px;">Description <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="desc" name="desc" readonly>{{$incident->incidentdesc}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;">Action Taken <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="action" name="action" readonly>{{$incident->actions}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;">Reported under local regulation</label>
                                <div class="col-md-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->local_regulation}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Evaluation of control measures <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->eval}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Public reaction <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->pubrec}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of occurrence <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->probablity}}">
                                    </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of financial loss <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->finloss}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Effect on community <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->efcom}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of human trauma <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->humtur}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Potential For Loss <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->potloss}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity of potential loss <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->sevpotloss}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of reoccurrence <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->porbocc}}">
                                </div>
                            </div>


                            <div class="form-group col-sm-6" id="tleader">
                                <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Team Leader <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$investigator->tleadername}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6" id="tmember">
                                <label class="col-sm-12 col-form-label" for="members" style="font-size: 13px;">Team Members <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$investigator->tmembersnames}}">
                                </div>
                            </div>
                            @php
                            if($investigator->targetdate!=null){
                            $intardate = DateTime::createFromFormat('d/m/Y', $investigator->targetdate);
                            $intardate=$intardate->format('Y-m-d');
                            }
                            @endphp

                            <div class="form-group col-sm-6" id="tdate">
                                <label class="col-sm-12 col-form-label" for="targetdate" style="font-size: 13px;">Target date to complete investigation</label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="targetdate" name="targetdate" data-week-start="1" data-autoclose="true" value="{{$intardate}}" data-date-format="dd/mm/yy" style="font-size: 13px;" readonly>
                                </div>
                            </div>
                        </div>
                            <!--Fileds filled by team leader-->
                            <br/>
                        <div class="row">
                            <div class="form-group col-sm-12" align="center">
                                <label class="col-sm-12" for="riskgroup" style="font-size:16px;margin-top:20px;margin-bottom:20px;height:40px;"><span class="bg-info text-white">To be filled by Investigation Team Leader</span></label>
                            </div>
                         </div>
                         <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was there a Risk Assessment?</label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;font-size: 13px;" type="radio" value="yes" id="yes" onclick="display1()" name="riskassessment"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;font-size: 13px;margin-left:10px;" type="radio" value="no" id="no" onclick="display1()" name="riskassessment"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;display:none" class="form-control" id="riskassess" name="riskassess" placeholder="Enter Risk Assessment#">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was there a Pre-Task/Tool Box Briefing?</label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px; font-size: 13px;" type="radio" value="yes" id="yes1" onclick="display2()" name="toolbox"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;font-size: 13px;margin-left:10px;" type="radio" value="no" id="no1" onclick="display2()" name="toolbox"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="tooldate" name="tooldate" style="display:none;font-size:13px" data-week-start="1" data-autoclose="true" data-date-format="dd/mm/yy" style="font-size: 13px;" placeholder="Date of ToolBox">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6" id="trd">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was the Person Trained to do the Job?</label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;font-size: 13px;" type="radio" value="yes" id="yes2" onclick="display3()" name="training"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;font-size: 13px;margin-left:10px;" type="radio" value="no" id="no2" onclick="display3()" name="training"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="traindate" name="traindate" data-week-start="1" data-autoclose="true" style="display:none;font-size:13px" data-date-format="dd/mm/yy" style="font-size: 13px;" placeholder="Date of Training">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was the Person Authorised to do the Works?</label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;font-size: 13px;" type="radio" value="yes" id="yes3" name="autherised"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;font-size: 13px;margin-left:10px;" type="radio" value="no" id="no3" name="autherised"><label style="font-size: 13px;">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="activity" style="font-size: 13px;">Activity carried out at time of Incident:<span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="activity" name="activity" placeholder="(For Pandemic, details of all activities including the close contact traces)" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="investigatordisc" style="font-size: 13px;">Description of Incident: <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="investigatordisc" name="investigatordisc" placeholder="(for pandemic, this should list the probable places from where he affected)" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="bodypart" style="font-size: 13px;">Parts of Body affected, if any <span class="text-danger">*</span> </label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="bodypart" name="bodypart">
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;">Was the incident caused by an unsafe work condition? (i.e. faulty equipment, unsafe ventilation, insufficient training) </label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;font-size: 13px;" type="radio" value="yes" onclick="display5()" id="yes5" name="unsafework"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;font-size: 13px;margin-left:10px;" type="radio" value="no" onclick="display5()" id="no5" name="unsafework"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;display:none;" class="form-control" id="workunsafe" name="workunsafe">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;">Was the incident caused by an unsafe act? (I.e. improper lifting, failure to wear appropriate PPE, failure to follow identified safety protocols, etc.)</label>
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;font-size: 13px;" type="radio" onclick="display6()" value="yes" id="yes6" name="unsafeact"><label style="font-size: 13px;">Yes</label>
                                
                                    <input style="margin-right:4px;font-size: 13px;margin-left:10px;" type="radio" onclick="display6()" value="no" id="no6" name="unsafeact"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;display:none;" class="form-control" id="actunsafe" name="actunsafe">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="ppkit" style="font-size: 13px;">What personal protective equipment was being used (if any)? <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="ppkit[]" id="ppkit" required="true" style="font-size: 13px;width:100%;" multiple data-placeholder="Select Protective Equipment">
                                        @foreach($protection as $pro)
                                        <option value="{{$pro->kit}}">{{$pro->kit}}</option>
                                        @endforeach
                                    </select>                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Attach Incident Related Files(avoid '#' and '&' in file names)</label>
                                <div class="col-sm-12">
                                    <div class="input-group realprocode control-group lst increment">
                                        <input type="file" name="filenames[]" class="myfrm form-control">
                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button"> <i class="fa fa-plus fa-1x"></i></button>
                                        </div>
                                    </div>

                                    <div class="clone hide">
                                        <div class="realprocode control-group lst input-group" style="margin-top:10px">
                                            <input type="file" name="filenames[]" class="myfrm form-control">
                                            <div class="input-group-btn">
                                                <button class="btn btn-danger" type="button"><i class="fa fa-minus fa-1x"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $(".btn-success").click(function() {
                                        var lsthmtl = $(".clone").html();
                                        $(".increment").after(lsthmtl);
                                    });
                                    $("body").on("click", ".btn-danger", function() {
                                        $(this).parents(".realprocode").remove();
                                    });
                                });

                            </script>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;width:100%;">How can future incident / spread be prevented <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="prevent[]" id="prevent" required="true" style="font-size: 13px;width:100%;" multiple data-placeholder="Please Choose One">
                                        @foreach($prevent as $pre)
                                        <option value="{{$pre->incident_prevent}}">{{$pre->incident_prevent}}</option>
                                        @endforeach
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6"></div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="otherReason" style="font-size: 13px; display: none;" id="otherReasonLabel">Specify Other Reason <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="otherReason" id="otherReason" style="display: none; font-size: 13px;">     
                                </div>
                            </div>
                            <script>
                                $(document).ready(function(){
                                    $('#prevent').change(function(){
                                        var selectedValues = $(this).val();
                                        if (selectedValues && selectedValues.includes('Other')) {
                                            $('#otherReasonLabel').show();
                                            $('#otherReason').show().prop('required', true);
                                        } else {
                                            $('#otherReasonLabel').hide();
                                            $('#otherReason').hide().prop('required', false);
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <div style="text-align:left;font-size:13px;color:red;margin-top:50px;">
                            Note: Add Corrective Action by clicking Action Button in Register page
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12 text-right form-group">
                                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
    <!-- END Page Container -->
    <script>
        $(document).ready(function() {
            $('#sel_priority').select2({});
        });
        $(document).ready(function() {
            $('#ppkit').select2({});
        });
        $(document).ready(function() {
            $('#prevent').select2({});
        });

        var i = 0;
        $("#add").click(function() {
            ++i;
            $("#dynamicTable").append('<tr><td> <input type="text" name="doc[' + i + '][name]" style="font-size: 13px;"class="form-control"  ><td> <textarea type="text" name="doc[' + i + '][address]" style="font-size: 13px;"class="form-control"  ></textarea></td><td> <div class="col-md-6" style="display:inline"><input   style="margin-right:4px;" type="radio" value="yes"   name="doc[' + i + '][statement]"><label style="font-size: 13px;">Yes</label></div><div class="col-md-6" style="display:inline;margin-left:-10px;"><input  style="margin-right:4px;" type="radio"   value="no"   name="doc[' + i + '][statement]"><label style="font-size: 13px;">No</label></div></td><td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove witness"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

        function display1() {
            if (document.getElementById('yes').checked) {
                document.getElementById('riskassess').style.display = "";
                document.getElementById('riskassess').required = true;
            } else if (document.getElementById('no').checked) {
                document.getElementById('riskassess').style.display = "none";
                document.getElementById('riskassess').required = false;
            }
        }

        function display2() {
            if (document.getElementById('yes1').checked) {
                document.getElementById('tooldate').style.display = "";
                document.getElementById('tooldate').required = true;
            } else if (document.getElementById('no1').checked) {
                document.getElementById('tooldate').style.display = "none";
                document.getElementById('tooldate').required = false;
            }
        }

        function display3() {
            if (document.getElementById('yes2').checked) {
                document.getElementById('traindate').style.display = "";
                document.getElementById('traindate').required = true;
            } else if (document.getElementById('no2').checked) {
                document.getElementById('traindate').style.display = "none";
                document.getElementById('traindate').required = false;
            }
        }

        function display5() {
            if (document.getElementById('yes5').checked) {

                document.getElementById('workunsafe').style.display = "";
                document.getElementById('workunsafe').required = true;
            } else if (document.getElementById('no5').checked) {

                document.getElementById('workunsafe').style.display = "none";
                document.getElementById('workunsafe').required = false;
            }
        }

        function display6() {

            if (document.getElementById('yes6').checked) {

                document.getElementById('actunsafe').style.display = "";
                document.getElementById('actunsafe').required = true;
            } else if (document.getElementById('no6').checked) {

                document.getElementById('actunsafe').style.display = "none";
                document.getElementById('actunsafe').required = false;
            }

        }

    </script>
@endsection