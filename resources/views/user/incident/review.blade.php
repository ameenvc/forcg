@extends('layouts.multiselect')
@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Review</a>
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
                    <form class="mb-5" action="{{route('incidentReviewed')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-10">
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
                            <div class="row">
                                @php
                                $today=Carbon\Carbon::now()->toDateTimeString();
                                $today=Carbon\Carbon::parse($today);
                                $incdate = DateTime::createFromFormat('d/m/Y', $incident->incidentdate);
                                $incdate=$incdate->format('Y-m-d');
                                @endphp
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Reporting Date</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="addeddate" name="addeddate" value="{{$incident->addeddate}}" readonly>
                                    </div>
                                </div>
                                @php
                                $docs=[];
                                $docs=explode(',',$incident->attachmentby_reporter);
                                $rdocs=explode(',',$incident->attachmentby_reviewer);
                                $dir=str_replace('/', '_',$incident->report_no);
                                @endphp
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Incident Related Files(attached by reporter)</label>
                                    <div class="col-sm-12">

                                        <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $incident->id }}" data-target="#viewModal4{{$incident->id}}"><strong>View Attachments</strong> </button>
                                        <div id="viewModal4{{$incident->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>
                                                    <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <div class="row justify-content-center">
                                                            <thead>
                                                                <tr class="table-secondary">
                                                                    <th>S.No</th>
                                                                    <th>Attachments</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <?php $sn=0; ?>

                                                            @if( count($docs) > 0 )
                                                            @foreach($docs as $value1)
                                                            @if( $value1!="" )
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size:10px; ;">{{ $sn=$sn+1}}</td>
                                                                    <td style="font-size:15px;width: 100%;">{{ $value1}}</td>
                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/IncidentFiles/".$dir."/".$value1)}}" download="{{$value1}}">Download</a></td>
                                                                </tr>
                                                            </tbody>
                                                            @endif
                                                            @endforeach
                                                            @else
                                                            <div class="row justify-content-center">
                                                                <h2>No Files</h2>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Department</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="department" style="font-size: 13px;width:100%;" id="department" onchange="chooseDivision($(this).val(), $('#division_val').val())">
                                            @foreach($depts as $dept)
                                            <option value="{{$dept->id}}" <?php if($incident->department==$dept->id){?> selected<?php } ?>>{{$dept->departmentname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="area" style="font-size: 13px;">Area<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" value="{{$incident->area}}" required class="form-control" id="area" name="area">
                                    </div>
                                </div>
                            </div>
                             <input type="hidden" id="division_val" value="{{ $incident->division_id }}" />
                             <input type="hidden" id="area_val" value="{{ $incident->area_id }}" />
                            <div class="row">
                                <div class="form-group col-sm-6" id="division">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="division">Division</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="division_select" name="division" style="font-size: 13px;width:100%;" placeholder="Division" onchange="chooseArea($(this).val(), $('#area_val').val())">
                                            <option value="0" disabled>Choose Division</option></br>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6" id="subdiv">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;" for="subdiv">Sub Division</label>
                                    <div class="col-sm-12" id="action_number" name="action">
                                        <select class="form-control" id="area_select" name="subdiv" style="font-size: 13px;width:100%;" placeholder="Sub Division">
                                            <option value="0" disabled>Choose Sub Division</option></br>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @php

                            $incidents= explode(',',$incident->incident_type);
                            $x=0;
                            $y=0;
                            @endphp
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Type</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="type[]" multiple style="font-size: 13px;width:100%;" id="type">
                                            @while(count($incidents)>$x)
                                            @foreach($inctype as $inc)
                                            @if($inc->incident_type==$incidents[$x] )
                                            <option value="{{$inc->incident_type}}" selected>{{$inc->incident_type}}</option>
                                            @endif
                                            @endforeach
                                            <?php  $x+=1?>
                                            @endwhile
                                            @while(count($incidenttype)>$y)
                                            <option value="{{$incidenttype[$y]['incident_type']}}">{{$incidenttype[$y]['incident_type']}}</option>
                                            <?php $y+=1; ?>
                                            @endwhile
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size: 13px;">Any Person Injured / Suffered</label>
                                    <div class="col-sm-12">
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="person_injured" name="person_injured" onclick="display3()" <?php if($incident->any_person_injured=='yes') {?>checked<?php } ?>><label style="font-size: 13px;">Yes</label>
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="person_injured1" name="person_injured" onclick="display3()" <?php if($incident->any_person_injured=='no') {?>checked<?php } ?>><label style="font-size: 13px;">No</label>
                                    </div>
                                </div>
                            </div>
                            
                            @if($incident->injuredperson=='other')
                            <div class="row">
                                <div class="form-group col-sm-6" id="checkid">
                                    <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Name and Employee ID of the injured / suffered person <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" value="{{$incident->injured_person_oth_name}}" required class="form-control" id="injempname_oth" name="injempname_oth" placeholder="Injured Employee Name">
                                    </div>
                                    <br>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" value="{{$incident->injured_person_oth_empid}}" required class="form-control" id="injempid_oth" name="injempid_oth" placeholder="Injured Employee ID">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" value="{{$incident->injured_person_oth_position}}" class="form-control" id="injposition_oth" name="injposition_oth" placeholder="Injured Employee Position">
                                    </div>
                                    <br>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="injdept_oth" id="injdept_oth" style="font-size: 13px;width:100%;">
                                            <option value="">Select Injured Employee Department</option>
                                            @if(count($depts)>0)
                                            @foreach($depts as $dept)
                                            <option value="{{$dept->id}}" <?php if($incident->injured_person_oth_dept==$dept->id) {?> selected <?php } ?>>{{$dept->departmentname}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                            <div class="form-group col-sm-6" id="checkid">
                                <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Name of the injured / suffered person</label>
                                <div class="col-sm-12">
                                    <select class="form-control" onchange="sel(this.value,'{{$empdetails}}','{{$depnam}}')" onclick="display2()" id="person" name="person" style="font-size: 13px;width:100%;">
                                        <option value="">Select Injured Employee</option>
                                        @if(count($empdetails)>0)
                                        @foreach($empdetails as $emp)
                                        <option value="{{$emp->emp_number}}" <?php if($incident->injuredperson==$emp->emp_name) {?> selected <?php } ?>>{{$emp->emp_name}}</option>
                                        @endforeach
                                        @endif
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->empid}}" class="form-control" id="empid" name="empid" placeholder="Injured Employee ID" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;margin-top:38px;" value="{{$incident->injured_person_position}}" class="form-control" id="injposition" name="injposition" placeholder="Injured Employee Position" readonly>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$injured_person_deptname}}" class="form-control" id="injdept" name="injdept" placeholder=" Injured Employee Department" readonly>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-6">
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="injempname_oth2" name="injempname_oth2" placeholder="Injured Employee Name">
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="injempid_oth2" name="injempid_oth2" placeholder="Injured Employee ID">
                                </div>

                            </div>

                            <div class="form-group col-sm-6">
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="injposition_oth2" name="injposition_oth2" placeholder="Injured Employee Position">
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <select class="form-control" name="injdept_oth2" id="injdept_oth2" style="font-size: 13px;width:100%;">
                                        <option value="">Select Injured Employee Department</option>
                                        @if(count($depts)>0)
                                        @foreach($depts as $dept)
                                        <option value="{{$dept->id}}">{{$dept->departmentname}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            </div>
                            @endif
                            <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date and Time of Incident</label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="date" name="incidentdate" data-week-start="1" data-autoclose="true" value="{{$incdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                </div>
                                <div class="col-sm-12">
                                    <input type="time" class="form-control" name="time" style="font-size: 13px;" placeholder="Time" value="{{$incident->time}}">
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="hospital" style="font-size: 13px;">Name of Hospital Injured / Suffered Person attended/ Admitted<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" value="{{$incident->hospital}}" required class="form-control" id="hospital" name="hospital">
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="place" style="font-size: 13px;">Place of the Incident</label>
                                    <div class="col-sm-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="place" name="place" value="{{$incident->incidentplace}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="employer" style="font-size: 13px;">Employer of the injured / suffered person (state if labour supply)<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" required style="font-size: 13px;" class="form-control" id="employer" name="employer" value="{{$incident->emplyoer}}"></textarea>
                                    </div>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="trade" style="font-size: 13px;"> Trade or occupation<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" value="{{$incident->trade_or_occupation}}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="desc" style="font-size: 13px;"> Description<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="desc" name="desc">{{$incident->incidentdesc}}</textarea>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Action Taken<span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="action" name="action">{{$incident->actions}}</textarea>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Reviewer<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="sel_priority" name="reviewer" style="font-size: 13px;width:100%;">

                                            @if(count($reviewers))
                                            @foreach($reviewers as $reviewer)
                                            @if($reviewer->email==$incident->reviewer_email)
                                            <option value="{{$reviewer->email}}" selected>{{$reviewer->name}}</option>
                                            @else
                                            <option value="{{$reviewer->email}}">{{$reviewer->name}}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Approver</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" id="sel_priority" name="approver" style="font-size: 13px;width:100%;">

                                            @if(count($approvers))
                                            @foreach($approvers as $approver)
                                            @if($approver->email==$incident->approver_email)
                                            <option value="{{$approver->email}}" selected>{{$approver->name}}</option>
                                            @else
                                            <option value="{{$approver->email}}">{{$approver->name}}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Reported under local regulation</label>
                                    <div class="col-md-12">
                                        @php
                                        if($incident->local_regulation==NULL)
                                        {
                                        $local_regulation = '-';
                                        }
                                        else
                                        {
                                        $local_regulation = $incident->local_regulation;
                                        }
                                        @endphp

                                        <input type="text" style="font-size: 13px;" class="form-control" id="localreg" name="localreg" value="{{$local_regulation}}">
                                    </div>
                                </div>
                            </div>
                            @php
                            $eval=['No loss will occure','Effective even under abnormal operating conditions',
                            'Only effective under normal operating condtions','Only partially effective','Not Effective'
                            ];
                            $pubrec=['No public reaction(internal effect only)','Little/Minor reactions from community','Local/Press/Community/Action group reaction',
                            'Severe local press/community/action group reaction',
                            'Severe national press/government/community and action groups reaction'

                            ];
                            $probality=['1 in 25 years','1 in 10 years','1 in 5 years', '1 in 3 years', '1 in 2 Years'
                            ];
                            $finloss=['10,000',
                            '≥50,000',
                            '≥100,000',
                            '≥250,000',
                            '≥500,000'
                            ];
                            $effcommunity=['Zero injuries / Disease, exposure or damage',
                            'Minor/ injuries / Disease and/or minimal exposure/damage'
                            ,'Disabling/severe injuries / Disease / and/or severe exposure/contamination/damage'
                            ,'Fatalities /Extensive damage / exposure / contamination / Pandemic',
                            'Catastrophic and widespread damage/exposure/Fatality (More than 1) / contamination / Pandemic'
                            ];
                            $humanturma=[
                            'No injury/illness/exposure',
                            'First Aid only',
                            'Minor injury/discomfort/exposure – visits hospital/clinic',
                            'Disabling injury/disease or reportable injury / disease / contamination / Pandemic / toxicities',
                            'Fatal injury / disease / extensive contamination / Pandemic. Catastrophic fatal injury/ disease (Greater than 5 persons)'

                            ];

                            @endphp
                            <br />
                            <div class="form-group">
                                <label class="col-form-label" for="riskgroup" style="font-size: 16px;color:white;margin-left:50%;background-color:#00B0FF;">To be filled by reviewer</label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Evaluation of control measures<span class="text-danger">*</span></label>
                                    @if($em!=null)
                                    <div class="col-sm-12">
                                        <select class="form-control" name="eval" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($eval as $ev)
                                            @if($ev==$em)
                                            <option value="{{$ev.'/'.$a}}" selected>{{$ev}}</option>
                                            @else
                                            <option value="{{$ev.'/'.$a}}">{{$ev}}</option>
                                            @endif
                                            <?php $a+=1 ?>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-sm-12">
                                        <select class="form-control" name="eval" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($eval as $ev)
                                            <option value="{{$ev.'/'.$a}}">{{$ev}}</option>
                                            <?php $a+=1 ?>
                                            @endforeach

                                        </select>
                                    </div>
                                    @endif
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Public reaction<span class="text-danger">*</span></label>
                                    @if($pre!=null)
                                    <div class="col-sm-12">
                                        <select class="form-control" name="pubrec" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($pubrec as $pr)
                                            @if($pr==$pre)
                                            <option value="{{$pr.'/'.$a}}" selected>{{$pr}}</option>
                                            @else
                                            <option value="{{$pr.'/'.$a}}">{{$pr}}</option>
                                            @endif
                                            <?php $a+=1 ?>
                                            @endforeach

                                        </select>
                                    </div>
                                    @else
                                    <div class="col-sm-12">
                                        <select class="form-control" name="pubrec" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($pubrec as $pr)
                                            <option value="{{$pr.'/'.$a}}">{{$pr}}</option>
                                            <?php $a+=1 ?>
                                            @endforeach

                                        </select>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of occurrence<span class="text-danger">*</span></label>
                                @if($po!=null)
                                <div class="col-sm-12">
                                    <select class="form-control" name="prob" required="true" style="font-size: 13px;width:100%;">
                                        <?php $a=1 ?>
                                        @foreach($probality as $prob)
                                        @if($prob==$po)
                                        <option value="{{$prob.'/'.$a}}" selected>{{$prob}}</option>
                                        @else
                                        <option value="{{$prob.'/'.$a}}">{{$prob}}</option>
                                        @endif
                                        <?php $a+=1 ?>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <select class="form-control" name="prob" required="true" style="font-size: 13px;width:100%;">
                                        <?php $a=1 ?>
                                        @foreach($probality as $prob)
                                        <option value="{{$prob.'/'.$a}}">{{$prob}}</option>
                                        <?php $a+=1 ?>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of financial loss <span class="text-danger">*</span></label>
                                @if($sf!=null)
                                <div class="col-sm-12">
                                    <select class="form-control" name="floss" required="true" style="font-size: 13px;width:100%;">
                                        <?php $a=1 ?>
                                        @foreach($finloss as $floss)
                                        @if($floss==$sf)
                                        <option value="{{$floss.'/'.$a}}" selected>{{$floss}}</option>
                                        @else
                                        <option value="{{$floss.'/'.$a}}">{{$floss}}</option>
                                        @endif
                                        <?php $a+=1 ?>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <select class="form-control" name="floss" required="true" style="font-size: 13px;width:100%;">
                                        <?php $a=1 ?>
                                        @foreach($finloss as $floss)
                                        <option value="{{$floss.'/'.$a}}">{{$floss}}</option>
                                        <?php $a+=1 ?>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Effect on community<span class="text-danger">*</span></label>
                                    @if($ec!=null)
                                    <div class="col-sm-12">
                                        <select class="form-control" name="efcom" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($effcommunity as $com)
                                            @if($com==$ec)
                                            <option value="{{$com.'/'.$a}}" selected>{{$com}}</option>
                                            @else
                                            <option value="{{$com.'/'.$a}}">{{$com}}</option>
                                            @endif
                                            <?php $a+=1 ?>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-sm-12">
                                        <select class="form-control" name="efcom" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($effcommunity as $com)
                                            <option value="{{$com.'/'.$a}}">{{$com}}</option>
                                            <?php $a+=1 ?>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of human trauma <span class="text-danger">*</span></label>
                                    @if($sh!=null)
                                    <div class="col-sm-12">
                                        <select class="form-control" name="humtu" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($humanturma as $ht)
                                            @if($ht==$sh)
                                            <option value="{{$ht.'/'.$a}}" selected>{{$ht}}</option>
                                            @else
                                            <option value="{{$ht.'/'.$a}}">{{$ht}}</option>
                                            @endif
                                            <?php $a+=1 ?>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-sm-12">
                                        <select class="form-control" name="humtu" required="true" style="font-size: 13px;width:100%;">
                                            <?php $a=1 ?>
                                            @foreach($humanturma as $ht)
                                            <option value="{{$ht.'/'.$a}}">{{$ht}}</option>
                                            <?php $a+=1 ?>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Potential For Loss<span class="text-danger">*</span></label>
                                    @if(count($potforloss)>0)
                                    <?php
                                                        $m=0;
                                                        $n=0;
                                                        ?>
                                    <div class="col-sm-12">
                                        {{-- <div class="form-group"style="color:#666666"> --}}
                                        {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                        <select class="form-control" name="potloss[]" id="potloss" required="true" style="font-size: 13px;width:100%;" multiple data-placeholder="Select Potential for Loss">
                                            @while(count($potforloss)>$m)
                                            @foreach($potlss as $pl)
                                            @if($pl->losses==$potforloss[$m])
                                            <option value="{{$pl->losses}}" selected>{{$pl->losses}}</option>
                                            @endif
                                            @endforeach
                                            <?php $m+=1; ?>
                                            @endwhile
                                            @while(count($pfl)>$n)
                                            <option value="{{$pfl[$n]['losses']}}">{{$pfl[$n]['losses']}}</option>
                                            <?php $n+=1 ?>
                                            @endwhile
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-sm-12">
                                        {{-- <div class="form-group"style="color:#666666"> --}}
                                        {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                        <select class="form-control" name="potloss[]" id="potloss" required="true" style="font-size: 13px;width:100%;" multiple data-placeholder="Select Potential for Loss">
                                            @foreach($potlss as $pl)
                                            <option value="{{$pl->losses}}">{{$pl->losses}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity of potential loss<span class="text-danger">*</span></label>

                                    @if($spl!=null)
                                    <div class="col-sm-12">
                                        <select class="form-control" name="spl" required="true" style="font-size: 13px;width:100%;">
                                            @if($spl=="major")
                                            <option value="major" selected>Major</option>
                                            <option value="medium">Medium</option>
                                            <option value="minor">Minor</option>
                                            @elseif($spl=="medium")
                                            <option value="major">Major</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="minor">Minor</option>
                                            @else
                                            <option value="major">Major</option>
                                            <option value="medium">Medium</option>
                                            <option value="minor" selected>Minor</option>
                                            @endif
                                        </select>
                                    </div>

                                    @else

                                    <div class="col-sm-12">
                                        {{-- <div class="form-group"style="color:#666666"> --}}
                                        {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                        <select class="form-control" name="spl" required="true" style="font-size: 13px;width:100%;">
                                            <option value="major">Major</option>
                                            <option value="medium">Medium</option>
                                            <option value="minor">Minor</option>
                                        </select>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of reoccurrence <span class="text-danger">*</span></label>
                                    @if($probre!=null)
                                    <div class="col-sm-12">
                                        {{-- <div class="form-group"style="color:#666666"> --}}
                                        {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                        <select class="form-control" name="porbocc" required="true" style="font-size: 13px;width:100%;">
                                            @if($probre=="high")
                                            <option value="high" selected>High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                            @elseif($probre=="medium")
                                            <option value="high">High</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="low">Low</option>
                                            @else
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low" selected>Low</option>

                                            @endif
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-sm-12">
                                        <select class="form-control" name="porbocc" required="true" style="font-size: 13px;width:100%;">
                                            <option value="high">High</option>
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                        </select>
                                    </div>

                                    @endif
                                </div>
                                @if($incident->attachmentby_reviewer!="")
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Incident Related Files(attached by reviewer)</label>
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $incident->id }}" data-target="#viewModal5{{$incident->id}}"><strong>View Attachments</strong> </button>
                                        <div id="viewModal5{{$incident->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Attachments</h3>
                                                    </div>
                                                    <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <div class="row justify-content-center">
                                                            <thead>
                                                                <tr class="table-secondary">
                                                                    <th>S.No</th>
                                                                    <th>Attachments</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <?php $sno=0; ?>

                                                            @if( count($rdocs) > 0 )
                                                            @foreach($rdocs as $value2)
                                                            @if( $value2!="" )
                                                            <tbody>
                                                                <tr>
                                                                    <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                    <td style="font-size:15px;width: 100%;">{{ $value2}}</td>
                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/IncidentFiles/".$dir."/".$value2)}}" download="{{$value2}}">Download</a></td>
                                                                </tr>
                                                            </tbody>
                                                            @endif
                                                            @endforeach
                                                            @else
                                                            <div class="row justify-content-center">
                                                                <h2>No Files</h2>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Attach incident related files(avoid '#' and '&' in file names)</label>
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
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"></label>
                                <div class="col-md-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="approve" onclick="display()" name="apprej" required><label style="font-size: 13px;">Update as Reviewed</label>
                                </div>
                                <div class="col-md-12">
                                    <input style="margin-right:4px;" type="radio" value="no" id="improve" onclick="display()" name="apprej"><label style="font-size: 13px;">Need Improvement?</label>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-sm-12" id="imp">
                                <label class="col-sm-12 col-form-label" for="justification" style="font-size: 13px;">Justify</label>
                                <div class="col-md-12">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="improvement" name="improvement" placeholder="Enter Justification"></textarea>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6" id="furinvest">
                                    <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Is this incident needs further investigation?</label>
                                    <div class="col-sm-12">
                                        <input style="margin-right:4px;" type="radio" value="yes" id="yes" onclick="display4()" name="investigation"><label style="font-size: 13px;">Yes</label>
                                        <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no" onclick="display4()" name="investigation"><label style="font-size: 13px;">No</label>

                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6" id="tleader">
                                    <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Team Leader</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="leader" id="leader" style="font-size: 13px;">
                                            <option value="">Select Team Leader</option>
                                            @foreach($users as $user)
                                            <option value="{{$user->email}}">{{$user->name}}</option>s
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6" id="tmember">
                                    <label class="col-sm-12 col-form-label" for="members" style="font-size: 13px;">Team Members</label>
                                    <div class="col-sm-12">
                                        <select class="form-control" name="members[]" style="font-size: 13px;width:100%;" id="priority" multiple data-placeholder="Select Team members">
                                            @foreach($users as $user)
                                            <option value="{{$user->email}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6" id="tdate">
                                    <label class="col-sm-12 col-form-label" for="targetdate" style="font-size: 13px;">Target date to complete investigation</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="js-datepicker form-control" id="targetdate" name="targetdate" data-week-start="1" data-autoclose="true" value="{{$incident->incidentdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12" id="just">
                                    <label class="col-sm-12 col-form-label" for="justification" style="font-size: 13px;">Justification for not making full investigation</label>
                                    <div class="col-md-12">
                                        <input type="text" style="font-size: 13px;" class="form-control" id="justification" name="justification" placeholder="Please enter Reason for not making full investigation">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right form-group">
                                    <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                    <button type="submit" for="submit" id="sub" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return checkall()">Update </button>
                                </div>
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
        $('#potloss').select2({});
        $('#priority').select2({});
        $('#type').select2({});
    });

    function checkall() {
        if (document.getElementById('approve').checked) {
            if (document.getElementById('yes').checked || document.getElementById('no').checked) {
                message = 'Do you want to proceed ? ';
                return confirm(message);
            } else {
                alert('Select if this incident needs further investigation?');
                document.getElementById('yes').focus();
                return false;
            }

        }

    }


    function display() {
        if (document.getElementById('approve').checked) {
            document.getElementById('furinvest').style.display = "";
            document.getElementById('imp').style.display = "none";
            document.getElementById('improvement').required = false;

        } else if (document.getElementById('improve').checked) {
            document.getElementById('imp').style.display = "";
            document.getElementById('improvement').required = true;
            document.getElementById('furinvest').style.display = "none";
            document.getElementById('tleader').style.display = "none";
            document.getElementById('tmember').style.display = "none";
            document.getElementById('tdate').style.display = "none";
            document.getElementById('just').style.display = "none";
            document.getElementById('justification').required = false;

        }
    }

    function display4() {
        if (document.getElementById('yes').checked) {
            document.getElementById('tleader').style.display = "";
            document.getElementById('tmember').style.display = "";
            document.getElementById('tdate').style.display = "";
            document.getElementById('just').style.display = "none";
            document.getElementById('priority').required = true;
            document.getElementById('leader').required = true;
            document.getElementById('targetdate').required = true;
            document.getElementById('justification').required = false;
        } else if (document.getElementById('no').checked) {
            document.getElementById('tleader').style.display = "none";
            document.getElementById('tmember').style.display = "none";
            document.getElementById('tdate').style.display = "none";
            document.getElementById('just').style.display = "";
            document.getElementById('priority').required = false;
            document.getElementById('leader').required = false;
            document.getElementById('targetdate').required = false;
            document.getElementById('justification').required = true;
        }
    }

</script>
<script>
    function sel(value, details, deprts) {
        datas = JSON.parse(details);
        depmts = JSON.parse(deprts);
        for (var key in datas) {
            if (datas[key].emp_number === value) {
                document.getElementById('empid').value = datas[key].emp_number;
                var a = datas[key].emp_department;
                deprtname = '-';
                for (let i = 0; i < depmts.length; i++) {
                    if (depmts[i].id == a) {
                        deprtname = depmts[i].departmentname;
                        break;
                    }
                }
                document.getElementById('injdept').value = deprtname;
                if (datas[key].emp_position == '') {
                    document.getElementById('injposition').value = '-';
                } else {
                    document.getElementById('injposition').value = datas[key].emp_position;
                }

            }
        }
    }

</script>
<script type="text/javascript">
    window.onload = function() {
        $(document).ready(function() {
            $('#injempname_oth2').hide();
            $('#injempid_oth2').hide();
            $('#injposition_oth2').hide();
            $('#injdept_oth2').hide();

            if ($("#person_injured1:checked").val() == 'no') {
                $('#checkid').hide();
                $('#person').hide();
                $('#empid').hide();
                $('#injposition').hide();
                $('#injdept').hide();
                $('#injempname_oth2').hide();
                $('#injempid_oth2').hide();
                $('#injposition_oth2').hide();
                $('#injdept_oth2').hide();
            }

            $('#furinvest').hide();
            $('#imp').hide();
            $('#tleader').hide();
            $('#tmember').hide();
            $('#tdate').hide();
            $('#just').hide();

        });

        $("#person_injured").change(function() {
            if ($("#person_injured:checked").val() == 'yes') {
                $('#checkid').show();
                $('#person').show();
                $('#empid').show();
                $('#injposition').show();
                $('#injdept').show();
                $('#injposition_oth').show();
                $('#injdept_oth').show();


            }
        });
        $("#person_injured1").change(function() {
            if ($("#person_injured1:checked").val() == 'no') {
                $('#checkid').hide();
                $('#person').hide();
                $('#empid').hide();
                $('#injposition').hide();
                $('#injdept').hide();
                $('#injempname_oth2').hide();
                $('#injempid_oth2').hide();
                $('#injposition_oth2').hide();
                $('#injdept_oth2').hide();
                $('#injposition_oth').hide();
                $('#injdept_oth').hide();
            }
        });

        $("#person").change(function() {
            if ($("#person option:selected").val() == "other") {
                $('#injempname_oth2').show();
                $('#injempid_oth2').show();
                $('#injposition_oth2').show();
                $('#injdept_oth2').show();
                $('#empid').hide();
                $('#injposition').hide();
                $('#injdept').hide();

            } else if ($("#person option:selected").val() != "other") {
                $('#checkid').show();
                $('#empid').show();
                $('#injposition').show();
                $('#injdept').show();
                $('#injempname_oth2').hide();
                $('#injempid_oth2').hide();
                $('#injposition_oth2').hide();
                $('#injdept_oth2').hide();

            }

        });
    }

</script>

<script>
    function display2() {
        if (document.getElementById('person').value == "other") {

            document.getElementById('injempname_oth2').required = true;
            document.getElementById('injempid_oth2').required = true;
            document.getElementById('injposition_oth2').required = true;
            document.getElementById('injdept_oth2').required = true;

        } else {
            document.getElementById('injempname_oth2').required = false;
            document.getElementById('injempid_oth2').required = false;
            document.getElementById('injposition_oth2').required = false;
            document.getElementById('injdept_oth2').required = false;
        }
    }

    function display3() {
        if (document.getElementById('person_injured').checked) {
            document.getElementById('person').required = true;
        } else if (document.getElementById('person_injured1').checked) {
            document.getElementById('person').required = false;
        }
    }

</script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let department = '{{ $incident->department }}';
    let area_id = '{{$incident->area_id}}';
    let show_area = 0;
    let area_data = [];
    let area_selector = $('#subdiv');
    let area_select_selector = $('#area_select');
    let division_selector = $('#division');
    let division_select_selector = $('#division_select');
    let division_select = '';
    area_select_selector.val(area_id);
    area_selector.removeClass('d-none').addClass('d-inline-block');
    chooseDivision(department, $('#division_val').val());

    function chooseDivision(id, division_id) {
        division_select_selector.empty();
        let get_areas_url = '{{ route("admin.departmentAreas.getAreas", ":id") }}';
        get_areas_url = get_areas_url.replace(':id', id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: get_areas_url
            , async: false
            , success: function(resp) {
                show_area = resp.data.length;
                area_data = resp.data;
            }
            , error: function(err) {
                console.log(err);
            }
        });

        let the_url = '{{ route("admin.departmentDivisions.getDivisions", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        let option_text1 = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , async: false
            , success: function(resp) {
                if (!resp.data.length) {
                    division_selector.removeClass('d-inline-block').addClass('d-none');
                    area_selector.removeClass('d-inline-block').addClass('d-none');
                    area_select_selector.val(0);
                    division_select_selector.val(0);
                } else {
                    if (show_area) {
                        area_selector.removeClass('d-none');
                        area_selector.addClass('d-inline-block');
                        for (var i = 0; i < area_data.length; i++) {
                            let if_selected = area_data[i].id === Number(area_id) ? 'selected' : '';
                            option_text1 += `<option value="${area_data[i].id}" ${if_selected}>${area_data[i].name}</option>`
                        }
                        area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text1);

                    } else {
                        area_selector.removeClass('d-inline-block');
                        area_selector.addClass('d-none');
                        area_select_selector.val(0);
                    }

                    division_selector.removeClass('d-none').addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id == Number(division_id) ? 'selected' : '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`;
                    }
                    division_select_selector.empty().append(`<option value="0">Choose Division</option></br>` + option_text);
                    division_select = division_select_selector.val();
                }

            }
            , error: function(err) {
                console.log(err);
            }
        });

    }


    chooseArea(division_select, $('#area_val').val());

    function chooseArea(id, area_id) {
        console.log(id, area_id);
        let the_url = '{{ route("admin.departmentDivisions.getAreasOfADivision", ":id") }}';
        the_url = the_url.replace(':id', id);
        let option_text = "";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
            , method: 'get'
            , url: the_url
            , success: function(resp) {
                if (!resp.data.length) {
                    area_selector.removeClass('d-inline-block');
                    area_selector.addClass('d-none');
                    area_select_selector.val(0);
                } else {
                    area_selector.removeClass('d-none');
                    area_selector.addClass('d-inline-block');
                    for (var i = 0; i < resp.data.length; i++) {
                        let if_selected = resp.data[i].id === Number(area_id) ? 'selected' : '';
                        option_text += `<option value="${resp.data[i].id}" ${if_selected}>${resp.data[i].name}</option>`
                    }
                    area_select_selector.empty().append('<option value="0">Choose Sub Division</option></br>' + option_text);
                    let area_select = area_select_selector.val();
                }
            }
            , error: function(err) {
                console.log(err);
            }
        });
    }

</script>

@endsection
