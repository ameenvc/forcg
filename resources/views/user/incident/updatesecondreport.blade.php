@extends('layouts.multiselect')
@section('content')
<!-- Main Container -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Update Investigation Result</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/incidents/register">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

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
                    <form class="mb-5" action="{{route('updatedSecondReport')}}" method="POST" enctype="multipart/form-data">
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
                        <?php
                        $today=Carbon\Carbon::now()->toDateTimeString();
                        $today=Carbon\Carbon::parse($today);
                        ?>
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
                                <label class="col-sm-12 col-form-label" style="font-size:13px;">Subdivision</label>
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
                                <label class="col-sm-12 col-form-label" for="area" style="font-size: 13px;">Area</label>
                                <div class="col-sm-12">
                                    <!-- <input type="text"style="font-size: 13px;" value="{{$incident->area}}" required class="form-control" id="area" name="area" readonly> -->
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="area" name="area" readonly>{{$incident->area}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="priority" style="font-size: 13px;">Incident Type</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->incident_type}}" required class="form-control" id="type" name="type" readonly>

                                </div>
                                <!-- <div style="margin-left:850px;margin-top:-40px;FONT-SIZE:8px">MS.10.01</div> -->
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#sel_priority').select2({});
                                });

                            </script>
                        
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
                                <label class="col-sm-12 col-form-label" for="hospital" style="font-size: 13px;">Name of Hospital Injured / Suffered Person attended/ Admitted</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" value="{{$incident->hospital}}" required class="form-control" id="hospital" name="hospital" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskdec" style="font-size: 13px;">Date and Time of Incident</label>
                                <div class="col-sm-12">
                                    <input type="text" class="js-datepicker form-control" id="date" name="incidentdate" data-week-start="1" data-autoclose="true" value="{{$incident->incidentdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" readonly>
                                </div>
                                <div class="col-sm-12">
                                    <input type="time" class="form-control" name="time" style="font-size: 13px;" placeholder="Time" value="{{$incident->time}}" readonly>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="place" style="font-size: 13px;">Place of the Incident</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="place" name="place" readonly value="{{$incident->incidentplace}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="employer" style="font-size: 13px;">Employer of the injured / suffered person (state if labour supply)</label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="employer" name="employer" readonly value="{{$incident->emplyoer}}"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="trade" style="font-size: 13px;"> Trade or occupation</label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->trade_or_occupation}}"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="desc" style="font-size: 13px;"> Description</label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="desc" name="desc" readonly>{{$incident->incidentdesc}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Action Taken</label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="action" name="action" readonly>{{$incident->actions}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Reported under local regulation</label>
                                <div class="col-md-12">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->local_regulation}}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Evaluation of control measures</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->eval}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Public reaction</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->pubrec}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of occurrence</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->probablity}}">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of financial loss</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->finloss}}">

                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Effect on community</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->efcom}}">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity in terms of human trauma</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->humtur}}">

                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Potential For Loss</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->potloss}}">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Severity of potential loss</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->sevpotloss}}">

                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">Probability of reoccurrence</label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$incident->porbocc}}">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6" id="tleader">
                                <label class="col-sm-12 col-form-label" for="person" style="font-size: 13px;">Team Leader</label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$investigator->tleadername}}">

                                </div>
                            </div>
                            <div class="form-group col-sm-6" id="tmember">
                                <label class="col-sm-12 col-form-label" for="members" style="font-size: 13px;">Team Members</label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="trade" name="trade" readonly value="{{$investigator->tmembersnames}}">
                                </div>
                            </div>
                        </div>
                            <?php
                            if($investigator->targetdate!=null){
                                $intardate = DateTime::createFromFormat('d/m/Y', $investigator->targetdate);
                            $intardate=$intardate->format('Y-m-d');
                            }
                            ?>
                        <div class="row">
                            <div class="form-group col-sm-6" id="tdate">
                                <label class="col-sm-12 col-form-label" for="targetdate" style="font-size: 13px;">Target date to complete investigation</label>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="targetdate" name="targetdate" data-week-start="1" data-autoclose="true" value="{{$intardate}}" data-date-format="dd/mm/yy" style="font-size: 13px;" readonly>
                                </div>
                            </div>

                            <!--Fileds filled by team leader-->
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was there a Risk Assessment?</label>
                                @if($incident->riskassessment=='yes')
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes" onclick="display()" name="riskassessment" checked><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no" onclick="display()" name="riskassessment"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" value="{{$incident->riskassess}}" class="form-control" id="riskassess" name="riskassess" required placeholder="Enter Risk Assessment#">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes" onclick="display()" name="riskassessment"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no" onclick="display()" name="riskassessment" checked><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;display:none" class="form-control" id="riskassess" name="riskassess" value="" placeholder="Enter Risk Assessment#">
                                </div>
                                @endif
                            </div>
                        </div>
                        <?php
                        // $trainedate=Carbon\Carbon::createFromTimestampUTC($this->invoice->date);
                        if($incident->traindate!=null){
                            $trainedate = DateTime::createFromFormat('d/m/Y', $incident->traindate);
                        $trainedate=$trainedate->format('Y-m-d');
                        }
                        if($incident->tooldate!=null){
                            $tooldate=DateTime::createFromFormat('d/m/Y', $incident->tooldate);
                        $tooldate=$tooldate->format('Y-m-d');
                        }
                        if($incident->currective_targdate!=null){
                            $targetdate=DateTime::createFromFormat('d/m/Y', $incident->currective_targdate);
                        $targetdate=$targetdate->format('Y-m-d');}
                        // $trainedate=Carbon\Carbon::parse($incident->traindate)->toFormattedDateString()
                        //  $Carbon::parse($request->currective_targdate)->format('d/m/Y');
                        ?>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was there a Pre-Task/Tool Box Briefing?</label>
                                @if($incident->toolbox=='yes')
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes1" onclick="display()" name="toolbox" checked><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no1" onclick="display()" name="toolbox"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="tooldate" name="tooldate" value="{{$tooldate}}" style="" data-week-start="1" data-autoclose="true" data-date-format="dd/mm/yy" style="font-size: 13px;" placeholder="Date of ToolBox">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes1" onclick="display()" name="toolbox"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no1" onclick="display()" name="toolbox" checked><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" id="tooldate" name="tooldate" style="display:none;" value="" data-week-start="1" data-autoclose="true" data-date-format="dd/mm/yy" style="font-size: 13px;" placeholder="Date of ToolBox">
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-sm-6" id="trd">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was the Person Trained to do the Job?</label>
                                @if($incident->training=='yes')
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" checked type="radio" value="yes" id="yes2" onclick="display()" name="training"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no2" onclick="display()" name="training"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">

                                    <input type="date" value="{{$trainedate}}" class="js-datepicker form-control" id="traindate" name="traindate" data-week-start="1" data-autoclose="true" style="" data-date-format="dd/mm/yy" style="font-size: 13px;">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes2" onclick="display()" name="training"><label style="font-size: 13px;">Yes</label>
                                    <input style="margin-right:4px;margin-left:10px;" checked type="radio" value="no" id="no2" onclick="display()" name="training"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="date" class="js-datepicker form-control" value="" id="traindate" name="traindate" data-week-start="1" data-autoclose="true" style="display:none;" data-date-format="dd/mm/yy" style="font-size: 13px;" placeholder="Date of Training">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;"> Was the Person Authorised to do the Works?</label>
                                @if($incident->autherised=='yes')
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" checked type="radio" value="yes" id="yes3" onclick="display()" name="autherised"><label style="font-size: 13px;">Yes</label>
                    
                                    <input style="margin-right:4px;margin-left;10px;" type="radio" value="no" id="no3" onclick="display()" name="autherised"><label style="font-size: 13px;">No</label>
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes3" onclick="display()" name="autherised"><label style="font-size: 13px;">Yes</label>
                                
                                    <input style="margin-right:4px;margin-left:10px;" checked type="radio" value="no" id="no3" onclick="display()" name="autherised"><label style="font-size: 13px;">No</label>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="activity" style="font-size: 13px;">Activity carried out at time of Incident </label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="activity" name="activity" placeholder="(For Pandemic, details of all activities including the close contact traces)" required>{{$incident->activity}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" for="investigatordisc" style="font-size: 13px;">Description of Incident: </label>
                                <div class="col-sm-12">
                                    <textarea type="text" required style="font-size: 13px;" class="form-control" id="investigatordisc" name="investigatordisc" placeholder="(for pandemic, this should list the probable places from where he affected)" required>{{$incident->investigatordisc}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="bodypart" style="font-size: 13px;">Parts of Body affected, if any </label>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" value="{{$incident->bodypart}}" id="bodypart" name="bodypart">
                                </div>
                            </div>


                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;">Was the incident caused by an unsafe work condition? (i.e. faulty equipment, unsafe ventilation, insufficient training) </label>
                                @if($incident->unsafework=='yes')
                                <div class="col-sm-12">
                                    <input checked style="margin-right:4px;" type="radio" value="yes" onclick="display()" id="yes5" name="unsafework"><label style="font-size: 13px;">Yes</label>
                               
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no5" onclick="display()" name="unsafework"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" value="{{$incident->workunsafe}}" id="workunsafe" name="workunsafe">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes5" onclick="display()" name="unsafework"><label style="font-size: 13px;">Yes</label>
                                
                                    <input checked style="margin-right:4px;margin-left:10px;" type="radio" id="no5" value="no" onclick="display()" name="unsafework"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <input type="text" style="font-size: 13px;display:none;" class="form-control" id="workunsafe" name="workunsafe">
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="local" style="font-size: 13px;">Was the incident caused by an unsafe act? (I.e. improper lifting, failure to wear appropriate PPE, failure to follow identified safety protocols, etc.)</label>
                                @if($incident->unsafeact=='yes')
                                <div class="col-md-2">
                                    <input style="margin-right:4px;" checked type="radio" value="yes" onclick="display()" id="yes6" name="unsafeact"><label style="font-size: 13px;">Yes</label>
                            
                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" onclick="display()" id="no6" name="unsafeact"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" required style="font-size: 13px;" class="form-control" id="actunsafe" value="{{$incident->actunsafe}}" name="actunsafe">
                                </div>
                                @else
                                <div class="col-sm-12">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes6" onclick="display()" name="unsafeact"><label style="font-size: 13px;">Yes</label>
                                
                                    <input style="margin-right:4px;margin-left:10px;" checked type="radio" id="no6" onclick="display()" value="no" name="unsafeact"><label style="font-size: 13px;">No</label>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;display:none;" class="form-control" id="actunsafe" name="actunsafe">
                                </div>
                                @endif
                            </div>
                            <?php
                                       $x=0;
                                       $y=0;
                                        ?>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="ppkit" style="font-size: 13px;">What personal protective equipment was being used (if any)? </label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="ppkit[]" id="ppkit" required="true" style="font-size: 13px;width:100%;" multiple>
                                        @while(count($usedppkits)>$x)
                                        @foreach($protection as $pro)
                                        @if($pro->kit==$usedppkits[$x] )
                                        <option value="{{$pro->kit}}" selected>{{$pro->kit}}</option>
                                        @endif
                                        @endforeach
                                        <?php  $x+=1?>
                                        @endwhile
                                        @while(count($ppkits)>$y)
                                        <option value="{{$ppkits[$y]['kit']}}">{{$ppkits[$y]['kit']}}</option>
                                        <?php $y+=1; ?>
                                        @endwhile
                                    </select>
                                    
                                </div>
                            </div>
                        </div>
                        <?php

                        $docs=[];
                        $docs=explode(',',$incident->attachmentby_investigator);
                        $dir=str_replace('/', '_',$incident->report_no);
                        // dd($dir);?>
                        <div class="row">
                            @if($incident->attachmentby_investigator!="")
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="addeddate" style="font-size: 13px;">Incident Related Files(attached by investigator)</label>
                                <div class="col-sm-12">
                                    {{-- <textarea input type="text"style="font-size: 13px;" class="form-control" id="files" name="files"  readonly>{{$incident->attachmentby_investigator}}</textarea> --}}
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
                                                        <?php $no=0; ?>

                                                        @if( count($docs) > 0 )
                                                        @foreach($docs as $value1)
                                                        @if( $value1!="" )
                                                        <tbody>
                                                            <tr>
                                                                <td style="font-size:10px; ;">{{ $no=$no+1}}</td>
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
                            @endif

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="action" style="font-size: 13px;"> Attach more incident related files if any(avoid '#' and '&' in file names)</label>
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
                        <?php
                        $a=0;
                        $b=0;
                        ?>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="riskgroup" style="font-size: 13px;">How can future incident / spread be prevented</label>
                                <div class="col-sm-12">
                                    {{-- <div class="form-group"style="color:#666666"> --}}
                                    {{-- <select  class="js-select2 form-control" name = "riskgroup"> --}}
                                    <select class="form-control" name="prevent[]" id="prevent" required="true" style="font-size: 13px;width:100%;" multiple>
                                        @while(count($usedpremethods)>$a)
                                        @foreach($prevent as $pre)
                                        @if($pre->incident_prevent===$usedpremethods[$a])
                                        <option value="{{$pre->incident_prevent}}" selected>{{$pre->incident_prevent}}</option>
                                        @endif
                                        @endforeach
                                        <?php  $a+=1?>
                                        @endwhile
                                        @while(count($prevmethods)>$b)
                                        <option value="{{$prevmethods[$b]['incident_prevent']}}">{{$prevmethods[$b]['incident_prevent']}}</option>
                                        <?php $b+=1; ?>
                                        @endwhile
                                        @if(in_array('Other', $usedpremethods))
                                            <option value="Other" selected>Other</option>
                                        @else
                                            <option value="Other">Other</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="otherReason" style="font-size: 13px; display: none;" id="otherReasonLabel">Specify Other Reason <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="otherReason" id="otherReason" style="display: none; font-size: 13px;" value="{{$incident->prevent_other}}">     
                                </div>
                            </div>
                            <script>
                                
                                $(document).ready(function(){
                                    var otherInArray = <?= json_encode(in_array('Other', $usedpremethods)) ?>;
                                    if(otherInArray){
                                        $('#otherReasonLabel').show();
                                        $('#otherReason').show().prop('required', true); 
                                    }else{
                                        $('#otherReasonLabel').hide();
                                        $('#otherReason').hide().prop('required', false);
                                    }
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
                            <!--
                                             <table class="table table-responsive table-bordered table-striped table-vcenter" style="width:148%;" id=""  >
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                        <th class="text-center" style="font-size:13px;width: 280px;text-transform:capitalize">Root cause</th>
                                        <th class="text-center" style="font-size:13px;width: 280px;text-transform:capitalize">Corrective action</th>
                                        <th class="text-center" style="font-size:13px;width: 150px;text-transform:capitalize">Target Date</th>
                                        <th class="text-center" style="font-size:13px;width: 180px;text-transform:capitalize">Responsibility</th>

                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                        <td>
                                        <textarea type="text" style="font-size: 13px;width:230px;"class="form-control"  name="rootcause" >{{$incident->rootcause}}</textarea>
                                             </td>
                                             <td>
                                        <textarea type="text" style="font-size: 13px;width:230px;"class="form-control"  name="corrective_act" >{{$incident->corrective_act}}</textarea>
                                             </td>
                                             <td>
                                        <input input type="date" value="{{$targetdate}}" class="js-datepicker form-control" style="font-size: 13px;width: 160px;"class="form-control"  name="currective_targdate"  data-week-start="1" data-autoclose="true"  data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;" >
                                             </td>
                                             <td>
                                             <select class="form-control" name = "responsiblity" id="responsiblity"  style="font-size: 13px;width: 150px;">
                                                        @foreach($users as $user)
                                                        @if($user->email==$incident->responsiblity)
                                                         <option selected value="{{$user->email}}" placeholder=>{{$user->name}}</option>
                                                        @else
                                                        <option  value="{{$user->email}}" placeholder=>{{$user->name}}</option>
                                                       @endif
                                                        @endforeach
                                                        </select>
                                             </td>

                                        </tr>
                                </tbody>
                            </table>
                        <br/>
                            <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable" style="width:148%" >
                                <thead style="background-color:#FFFFFF">
                                    <tr>
                                    <th></th>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Witness Name</th>
                                        <th class="text-center" style="font-size:13px;width: 230px;text-transform:capitalize">Witness Address/Mobile No.</th>
                                         <th class="text-center" style="font-size:13px;width: 240px;text-transform:capitalize">Statement Provided?</th>
                                         <th class="text-center"  style="font-size:13px;width: 140px;text-transform:capitalize">Add witness</th>

                                    </tr>
                                </thead>
                                <?php
                                 $rand=rand(999,1);
                                $c=0; ?>
                                <tbody>
                                @if(count($witnesses)>0)
                                <input type="hidden" value="{{count($witnesses)}}" id="i" name="existinig_witness" style="display:none;">
                                @foreach($witnesses as $witness)
                                        <tr>
                                        <td><input class="checkbox" type="checkbox" id="{{$witness->id}}" name="id[]" ></td>
                                        <td>
                                        <input type="text" name="doc[{{$c}}][name]" style="font-size: 13px;"class="form-control" value="{{$witness->name}}" >
                                             </td>
                                             <td>
                                        <textarea type="text" name="doc[{{$c}}][address]" style="font-size: 13px;"class="form-control">{{$witness->phone_or_address}}</textarea>
                                             </td>
                                             <td>
                                             @if($witness->statement=='yes')
                                             <div class="col-md-6" style="display:inline">
                                                <input   style="margin-right:4px;" checked type="radio" value="yes"   name="doc[{{$c}}][statement]"><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-6" style="display:inline;margin-left:-25px">
                                                <input  style="margin-right:4px;" type="radio"   value="no"   name="doc[{{$c}}][statement]"><label style="font-size: 13px;margin-left:-3px">No</label>
                                            </div>
                                            @else
                                            <div class="col-md-6" style="display:inline;">
                                                <input   style="margin-right:4px;" type="radio" value="yes"   name="doc[{{$c}}][statement]"><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-6" style="display:inline;margin-left:-25px">
                                                <input  style="margin-right:4px;" checked type="radio"   value="no"   name="doc[{{$c}}][statement]"><label style="font-size: 13px;margin-left:-3px">No</label>
                                            </div>
                                            @endif
                                             </td>
                                            <td></td>
                                        </td>
                                        </tr>
                                        <?php $c+=1 ?>
                                        @endforeach
                                        <tr>
                                        <td></td>
                                        <td>
                                        <input type="text" name="doc[{{count($witnesses)}}][name]" style="font-size: 13px;"class="form-control"  >
                                             </td>
                                             <td>
                                        <textarea type="text" name="doc[{{count($witnesses)}}][address]" style="font-size: 13px;"class="form-control"></textarea>
                                             </td>
                                             <td>
                                             <div class="col-md-6" style="display:inline">
                                                <input   style="margin-right:4px;" type="radio" value="yes"   name="doc[{{count($witnesses)}}][statement]"><label style="font-size: 13px;">Yes</label>
                                            </div>
                                            <div class="col-md-6" style="display:inline;margin-left:-25px">
                                                <input  style="margin-right:4px;" type="radio"   value="no"   name="doc[{{count($witnesses)}}][statement]"><label style="font-size: 13px;margin-left:-3px">No</label>
                                            </div>
                                             </td>
                                            <td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light " data-toggle="tooltip" title="Add witness">
                                            <i class="fa fa-fw fa-plus"></i>
                                            </button>
                                        </td>
                                        </tr>

                                        @endif
                                </tbody>
                            </table>
                                    -->
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <!--<button type="button"  class="btn btn-danger"  id="delparty" value=id[] style="font-size:13px;margin-left:10%;" >Remove</button> -->
                                <button for="reset" type="reset" class="btn btn-secondary" style="font-size:13px;">Reset</button>
                                <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" data-toggle="click-ripple" onclick="return confirm('Do you want to proceed ? ')">Update </button>
                            </div>
                        </div>
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                        {{----------delete request button ----------}}
                        <script>
                            $("#delparty").click(function() {
                                var dataArr = new Array();
                                if ($('input:checkbox:checked').length > 0) {
                                    $('input:checkbox:checked').each(function() {
                                        dataArr.push($(this).attr('id'));
                                        // console.log(dataArr);
                                        $.ajax({
                                            type: 'get'
                                            , url: "{{route('deleteVitnesses')}}" + "?val=" + $(this).attr('id')
                                            , success: function(response) {
                                                // alert(response);
                                                window.location.href = "{{url('/user/updatesecond/incident/report/'.$incident->id.'/'.$rand)}}";
                                                //  redirect('/user/doc/masterlist')->withData($data);
                                            }
                                            , error: function(response) {
                                                alert(errResponse);
                                            }
                                        });
                                    });

                                } else {
                                    alert('No Record Selected');
                                }
                            });

                        </script>


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
    <script>
        $(document).ready(function() {
            $('#ppkit').select2({});
        });

        $(document).ready(function() {
            $('#prevent').select2({});
        });

        var i = document.getElementById('i').value;
        $("#add").click(function() {
            ++i;
            $("#dynamicTable").append('<tr><td></td><td> <input type="text" name="doc[' + i + '][name]" style="font-size: 13px;"class="form-control"  ><td> <textarea type="text" name="doc[' + i + '][address]" style="font-size: 13px;"class="form-control"  ></textarea></td><td> <div class="col-md-6" style="display:inline"><input   style="margin-right:4px;" type="radio" value="yes"   name="doc[' + i + '][statement]"><label style="font-size: 13px;">Yes</label></div><div class="col-md-6" style="display:inline;margin-left:-25px;"><input  style="margin-right:4px;" type="radio"   value="no"   name="doc[' + i + '][statement]"><label style="font-size: 13px;margin-left:-3px">No</label></div></td><td class="text-center"><button type="button" name="add" id="add" class="btn btn-sm btn-light" data-toggle="tooltip" title="Remove witness"><i class="fa fa-fw remove-tr fa-times"></i></button></td></tr>');
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });

        function display() {
            if (document.getElementById('yes').checked) {
                document.getElementById('riskassess').style.display = "";
                document.getElementById('riskassess').required = true;
            } else if (document.getElementById('no').checked) {
                document.getElementById('riskassess').style.display = "none";
                document.getElementById('riskassess').required = false;
            }
            if (document.getElementById('yes1').checked) {
                document.getElementById('tooldate').style.display = "";
                document.getElementById('tooldate').required = true;
            } else if (document.getElementById('no1').checked) {
                document.getElementById('tooldate').style.display = "none";
                document.getElementById('tooldate').required = false;
            }
            if (document.getElementById('yes2').checked) {
                document.getElementById('traindate').style.display = "";
                document.getElementById('traindate').required = true;
            } else if (document.getElementById('no2').checked) {
                document.getElementById('traindate').style.display = "none";
                document.getElementById('traindate').required = false;
            }
            if (document.getElementById('yes5').checked) {

                document.getElementById('workunsafe').style.display = "";
                document.getElementById('workunsafe').required = true;
            } else if (document.getElementById('no5').checked) {

                document.getElementById('workunsafe').style.display = "none";
                document.getElementById('workunsafe').required = false;
            }
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