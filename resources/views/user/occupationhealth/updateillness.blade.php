@extends('layouts.multiselect')

@section('content')

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Occupational Health</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Update Illness Record</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/occupationhealth/illnessrecords">Back</a>
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
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <?php $f=0; ?>
                    <form class="mb-5" action="{{route('updateillnessrecord')}}" method="POST" enctype="multipart/form-data">
                        @php
                        $today=Carbon\Carbon::now()->toDateTimeString();
                        $today=Carbon\Carbon::parse($today);
                        @endphp
                        @csrf
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif


                        <div class="col-lg-10">
                            <input type="hidden" value="{{$record->id}}" name="recid">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;padding-left:29px;">Report Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="report_num" id="report_num" value="{{$record->report_num}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="riskgroup" style="font-size:13px;padding-left:29px;">Category <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="category" required="true" style="font-size: 13px;">
                                        <option value="" style="font-size: 13px;">Choose Category</option>
                                        @foreach($healthcategory as $cat)
                                        <option value="{{$cat->id}}" <?php if($record->category_id==$cat->id){ ?> selected <?php }?>>{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" name="createdby" id="createdby" value="{{$record->creator_name}}">
                            <input type="hidden" value="{{$today->format('d-m-Y')}}" class="form-control" name="createddate" id="createddate" value="{{$record->created_date}}">
                            <input type="hidden" class="form-control" name="createrdept" id="createrdept" value="{{$creat_dep}}">
                            <input type="hidden" class="form-control" name="createrposition" id="createrposition" value="{{$record->creator_position}}">


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="number" style="font-size: 13px;padding-left:29px;">Illed Employee Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="number" id="number" value="{{$record->e_number}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="name" style="font-size: 13px;padding-left:29px;">Illed Employee Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name" value="{{$record->e_name}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="dob" style="font-size: 13px;padding-left:29px;">Illed Employee Date of Birth</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="dob" id="dob" value="{{$record->dob}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="department" style="font-size: 13px;padding-left:29px;">Illed Employee Department</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="department" id="department" value="{{$ill_dep}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="position" style="font-size: 13px;padding-left:29px;">Illed Employee Position</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="position" id="position" value="{{$record->position}}" style="font-size: 13px;" readonly>
                                </div>
                            </div>

                            @php
                            $illdate = DateTime::createFromFormat('d-m-Y', $record->dateofillness);
                            $illdate=$illdate->format('Y-m-d');
                            @endphp

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="injurydate" style="font-size: 13px;padding-left:29px;">Date and Time of Injury</label>
                                <div class="col-sm-4">
                                    <input type="date" class="js-datepicker form-control" name="illdate" id="date" data-week-start="1" data-autoclose="true" value="{{$illdate}}" data-date-format="dd/mm/yy" placeholder="dd/mm/yy" style="font-size: 13px;">
                                </div>
                                <div class="col-sm-4">
                                    <input type="time" class="form-control" name="illtime" id="illtime" value="{{$record->timeofillness}}" style="font-size: 13px;">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;padding-left:29px;">Type of Illness <span class="text-danger">*</span> </label>
                                <div class="col-sm-8">

                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="work_related" id="work_related" name="ill_type" <?php if($record->ill_type=='work_related'){ ?>checked<?php } ?> onclick="displaynonworkillness()" required><label style="font-size: 13px;">Work Related</label>

                                    <input style="margin-right:4px;margin-left:10px;" type="radio" value="non_work_related" id="nonwork_related" name="ill_type" <?php if($record->ill_type=='non_work_related'){ ?>checked <?php } ?>onclick="displaynonworkillness()"><label style="font-size: 13px;">Non-Work Related</label>

                                </div>
                            </div>

                            @if($record->ill_type=='non_work_related')
                            <div class="form-group row" id="illnonwrkcases">
                                <label class="col-sm-4 col-form-label" for="number" style="font-size:13px;padding-left:29px;">Non-Work Related Illness Details</label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name="nonwork_ill[]" id="nonwork_ill" style="font-size: 13px;width:525px;" multiple onchange="displayothernonworkillness()">
                                        @php
                                        $nonworkcase = explode(',',$record->nonworkrelated_illness);
                                        @endphp
                                        @foreach($illnessnonwork as $injw)
                                        <option value="{{$injw->id}}" {{ (collect($nonworkcase)->contains($injw->id)) ? 'selected':'' }}>{{$injw->name}}</option>

                                        @endforeach
                                        <option value="other" {{ (collect($nonworkcase)->contains('other')) ? 'selected':'' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" id="illnonwrkcases" style="display:none;">
                                <label class="col-sm-4 col-form-label" for="number" style="font-size: 13px;padding-left:29px;">Non-Work Related Illness Details<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name="nonwork_ill[]" id="nonwork_ill" style="font-size: 13px;width:525px;" multiple onchange="displayothernonworkillness()">
                                        @foreach($illnessnonwork as $injw)
                                        <option value="{{$injw->id}}">{{$injw->name}}</option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            @endif

                            @if($record->nonworkrelated_illness_other!=NULL)
                            <div class="form-group row" id="illnonwrkothrcase">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="nonwork_oth" name="nonwork_oth" placeholder="Please specify Non-Work Related illness Details">{{$record->nonworkrelated_illness_other}}</textarea>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" id="illnonwrkothrcase" style="display:none;">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="nonwork_oth" name="nonwork_oth" placeholder="Please specify Non-Work Related illness Details">{{$record->nonworkrelated_illness_other}}</textarea>
                                </div>
                            </div>
                            @endif

                            @if($record->ill_type=='work_related')
                            <div class="form-group row" id="illnesswrkcases">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="work_ill" name="work_ill" placeholder="Please specify Work Related Illness Details">{{$record->workrelated_illness}}</textarea>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" id="illnesswrkcases" style="display:none;">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="work_ill" name="work_ill" placeholder="Please specify Work Related Illness Details"></textarea>
                                </div>
                            </div>
                            @endif


                            @if($record->ill_type=='work_related')
                            <div class="form-group row" id="worknatureillness">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Nature Of Illness</label>
                                <div class="col-sm-8">
                                    @if($record->natureofillness=='-')
                                    <select class="js-select2 form-control" name="work_nature[]" id="work_nature" style="font-size: 13px;" multiple data-placeholder="Choose Nature of Illness">
                                        <option value="" style="font-size: 13px;">Choose Nature of Illness</option>
                                        @php
                                        $illness_nature = DB::table('employee_illness_nature_details')
                                        ->where('illness_id',$record->id)
                                        ->pluck('natureofillness')
                                        ->toArray();
                                        $natureillness = DB::table('employee_health_illness_natures')->where('illness_type','work_related')->get();
                                        @endphp
                                        @foreach($natureillness as $nature)

                                        <option value="{{$nature->id}}" {{ (collect($illness_nature)->contains($nature->id)) ? 'selected':'' }}>{{$nature->name}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="work_nature" name="work_nature">{{$record->natureofillness}}</textarea>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="form-group row" id="worknatureillness" style="display:none;">
                                <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;padding-left:29px;">Nature Of Illness </label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name="work_nature[]" id="work_nature" style="font-size: 13px;width:525px;" multiple data-placeholder="Choose Nature of Illness">
                                        <option value="" style="font-size: 13px;">Choose Nature of Illness</option>
                                    </select>
                                </div>
                            </div>
                            @endif

                            @if($record->ill_type=='non_work_related')
                            <div class="form-group row" id="non_worknatureillness">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Nature Of Illness</label>
                                <div class="col-sm-8">
                                    @if($record->natureofillness=='-')
                                    <select class="js-select2 form-control" name="non_work_nature[]" id="non_work_nature" style="font-size: 13px;" multiple>
                                        <option value="" style="font-size: 13px;">Choose Nature of Illness</option>
                                        @php
                                        $illness_nature = DB::table('employee_illness_nature_details')
                                        ->where('illness_id',$record->id)
                                        ->pluck('natureofillness')
                                        ->toArray();
                                        $natureillness = DB::table('employee_health_illness_natures')->where('illness_type','non_work_related')->get();
                                        @endphp
                                        @foreach($natureillness as $nature)
                                        <option value="{{$nature->id}}" {{ (collect($illness_nature)->contains($nature->id)) ? 'selected':'' }}>{{$nature->name}}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="non_work_nature" name="non_work_nature">{{$record->natureofillness}}</textarea>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="form-group row" id="non_worknatureillness" style="display:none;">
                                <label class="col-sm-4 col-form-label" for="injurynt" style="font-size: 13px;padding-left:29px;">Nature Of Illness</label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" name="non_work_nature[]" id="non_work_nature" style="font-size: 13px;width:525px;" multiple data-placeholder="Choose Nature of Illness">
                                        <option value="" style="font-size: 13px;">Choose Nature of Illness</option>
                                    </select>
                                </div>
                            </div>

                            @endif


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Location At Time of Illness <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" required class="form-control" id="location" name="location">{{$record->location}}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Cause of Illness <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" required class="form-control" id="cause" name="cause">{{$record->causeofillness}}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Symptoms <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" required class="form-control" id="symptom" name="symptom">{{$record->symptoms}}</textarea>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="local" style="font-size: 13px;padding-left:29px;">Was any Company Matters/Materials/Equipment Caused Illness? </label>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes" onclick="display()" name="thing" @if($record->things_involved=='yes') checked @endif><label style="font-size: 13px;">Yes</label>
                                </div>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="no" id="no" onclick="display()" name="thing" @if($record->things_involved=='no') checked @endif><label style="font-size: 13px;">No</label>
                                </div>
                            </div>

                            @if($record->things_involved=='yes')
                            <div class="form-group row" id="regu">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Details</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" id="things" name="things" class="form-control">{{$record->things_involved_details}}</textarea>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" style="display:none;" id="regu">
                                <label class="col-sm-3 col-form-label" style="font-size: 13px;padding-left:29px;"></label>
                                <div class="col-sm-9" style="padding-left:50px;">
                                    <textarea style="font-size:13px;width:520px;" class="form-control" id="things" name="things" placeholder="Enter the Details"></textarea>
                                </div>
                            </div>
                            @endif

                            @if(count($witnesses)>0)
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="hospital" style="font-size: 13px;padding-left:29px;">Witness</label>
                                <div class="col-sm-8">
                                    <table class="table table-responsive table-bordered table-striped table-vcenter" id="dynamicTable">
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
                                <label class="col-sm-4 col-form-label" for="supervisor" style="font-size: 13px;padding-left:29px;">Is Illness Is Any Type of Pandemic? <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input type="text" style="font-size: 13px;" required class="form-control" id="cause" readonly value="{{$record->pandamic}}">
                                </div>
                            </div>

                            @if($record->pandamic=='yes')
                            <div class="form-group row" id="pand">
                                <label class="col-sm-4 col-form-label" for="regulation" style="font-size: 13px;padding-left:29px;">Details</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="pandemics" name="pandemics" readonly>{{$record->pandamic_details}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row" id="cont">
                                <label class="col-sm-4 col-form-label" for="regulation" style="font-size: 13px;padding-left:29px;">Contacts</label>
                                <div class="col-sm-8">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="contacts" name="contacts" value="{{$record->pandemic_contacts}}" readonly>

                                </div>
                            </div>

                            @endif

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="injury_reported" style="font-size: 13px;padding-left:29px;"> Has The Illness Been Reported To The Workers Supervisor? <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="yes" <?php if($record->reported_to_supervisor=='yes'){ ?> checked <?php } ?> name="supervisor" required><label style="font-size: 13px;">Yes</label>
                                </div>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="no" <?php if($record->reported_to_supervisor=='no'){ ?> checked <?php } ?> name="supervisor"><label style="font-size: 13px;">No</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="treatment_provided" style="font-size: 13px;padding-left:29px;">Was any Treatment Provided? <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes1" <?php if($record->treatment_provided=='yes'){ ?> checked <?php } ?> onclick="display1()" name="treatment" required><label style="font-size: 13px;">Yes</label>
                                </div>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="no" id="no1" <?php if($record->treatment_provided=='no'){ ?> checked <?php } ?> onclick="display1()" name="treatment"><label style="font-size: 13px;">No</label>
                                </div>
                            </div>

                            @if($record->treatment_provided=='yes')
                            <div class="form-group row" id="treat">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"> </label>
                                <div class="col-sm-8">
                                    @php
                                    $treatmentdata = explode(',',$record->treatment_details);
                                    @endphp
                                    @foreach($treatment as $treat)
                                    <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$treat->id}}" {{(collect($treatmentdata)->contains($treat->id)) ? 'checked':''}} name="treatments[]" id="treatments"><label style="font-size: 13px;">{{$treat->name}}</label>
                                    <br>
                                    @endforeach
                                    <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other" {{(collect($treatmentdata)->contains('other')) ? 'checked':''}} name="treatments[]" id="treatments_other" onclick="treatmentsotherdisplay()"><label style="font-size: 13px;">Other</label>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" style="display:none;" id="treat">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;"> </label>
                                <div class="col-sm-8">
                                    @foreach($treatment as $treat)
                                    <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="{{$treat->id}}" name="treatments[]" id="treatments"><label style="font-size: 13px;">{{$treat->name}}</label>
                                    <br>
                                    @endforeach
                                    <input style="margin-right:4px;margin-left:10px;" type="checkbox" value="other" name="treatments[]" id="treatments_other" onclick="treatmentsotherdisplay()"><label style="font-size: 13px;">Other</label>
                                </div>
                            </div>
                            @endif

                            @if($record->treatmentdetails_other!=NULL)
                            <div class="form-group row" id="treatmentsother">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size:13px;width:490px;" class="form-control" name="treatment_oth" id="treatment_oth" placeholder="Please specify treatment provided">{{$record->treatmentdetails_other}}</textarea>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" style="display: none;" id="treatmentsother">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;">&nbsp;</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size:13px;width:490px;" class="form-control" name="treatment_oth" id="treatment_oth" placeholder="Please specify treatment provided"></textarea>
                                </div>
                            </div>
                            @endif


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="local" style="font-size: 13px;padding-left:29px;">Did the Illed Worker Return To Work Following the illness? <span class="text-danger">*</span></label>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="yes" id="yes3" <?php if($record->illed_returnto_work=='yes'){ ?> checked <?php } ?> onclick="display3()" name="returnwork" required><label style="font-size: 13px;">Yes</label>
                                </div>
                                <div class="col-md-3">
                                    <input style="margin-right:4px;" type="radio" value="no" id="no3" <?php if($record->illed_returnto_work=='no'){ ?> checked <?php } ?> onclick="display3()" name="returnwork"><label style="font-size: 13px;">No</label>
                                </div>
                            </div>

                            @if($record->illed_returnto_work=='yes')
                            <div class="form-group row" id="return">
                                <label class="col-sm-4 col-form-label" style="font-size: 13px;padding-left:29px;">Details</label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="returnworks" name="returnworks">{{$record->illed_returnto_work_details}}</textarea>
                                </div>
                            </div>
                            @else
                            <div class="form-group row" style="display:none;" id="return">
                                <label class="col-sm-4 col-form-label" for="regulation" style="font-size: 13px;"> </label>
                                <div class="col-sm-8">
                                    <textarea type="text" style="font-size: 13px;" class="form-control" id="returnworks" name="returnworks" placeholder="Enter The Details" style="display:none;"></textarea>
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="local" style="font-size: 13px;padding-left:29px;">#of Medical Leaves</label>
                                <div class="col-md-3">
                                    <input type="text" style="font-size: 13px;" class="form-control" id="leaves" name="leaves" value="{{$record->medical_leaves}}">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="diastolic" style="font-size: 13px;">Blood Pressure Diastolic</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="bloodpd" name="bloodpd" value="{{$record->illed_bp_diastolic}}" placeholder="Enter Blood Pressure Diastolic">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="systolic" style="font-size: 13px;">Blood Pressure Systolic</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="bps" name="bps" value="{{$record->illed_bp_systolic}}" placeholder="Enter Blood Pressure Systolic">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="blood_sugar" style="font-size: 13px;">Blood Sugar Level</label>
                                <div class="col-sm-12">
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="bsl" name="bsl" value="{{$record->illed_bs_level}}" placeholder="Enter Blood Sugar Level">
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
                                    <input type="text" style="font-size: 13px;width:350px;" class="form-control" id="remark" name="remark" value="{{$record->remarks}}" placeholder="Enter Remarks (if any)">
                                </div>
                            </div>

                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" for="number" style="font-size: 13px;">Status</label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="status" style="font-size: 13px;width:350px;">
                                        <option value="">Please Select Status</option>
                                        @foreach($status as $stu)
                                        @if($record->status==$stu)
                                        <option value="{{$stu}}" selected>{{$stu}}</option>
                                        @else
                                        <option value="{{$stu}}">{{$stu}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <br />
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

        function display() {
            if (document.getElementById('yes').checked) {
                document.getElementById('regu').style.display = "";
                document.getElementById('things').required = true;
            } else if (document.getElementById('no').checked) {
                document.getElementById('regu').style.display = "none";
                document.getElementById('things').required = false;
            }
        }

        function displaynonworkillness() {
            if (document.getElementById('work_related').checked) {
                document.getElementById('illnesswrkcases').style.display = "";
                document.getElementById('work_ill').required = true;
                document.getElementById('illnonwrkcases').style.display = "none";
                document.getElementById('nonwork_ill').required = false;
                document.getElementById('worknatureillness').style.display = "";
                document.getElementById('non_worknatureillness').style.display = "none";

                var options = $('#work_nature');
                var ill_type = "#work_related";

                document.getElementById('work_nature').required = true;
                document.getElementById('non_work_nature').required = false;


            } else if (document.getElementById('nonwork_related').checked) {
                document.getElementById('illnonwrkcases').style.display = "";
                document.getElementById('nonwork_ill').required = true;
                document.getElementById('illnesswrkcases').style.display = "none";
                document.getElementById('work_ill').required = false;
                document.getElementById('non_worknatureillness').style.display = "";
                document.getElementById('worknatureillness').style.display = "none";

                var options = $('#non_work_nature');
                var ill_type = "#nonwork_related";

                document.getElementById('work_nature').required = false;
                document.getElementById('non_work_nature').required = true;
            }

            console.log($(ill_type).val());
            $.ajax({
                data: {
                    "ill_type": $(ill_type).val()
                    , _token: CSRF_TOKEN
                }
                , url: "{{ route('selectillnesstype')}}"
                , type: "POST"
                , success: function(response) {
                    if (response.status == 200) {
                        options.empty(); // clear dropdown values
                        $.each(response.data, function(val, text) {
                            options.append(
                                $('<option></option>').val(text.id).html(text.name)
                            );
                        });

                    }
                }

            });
        }

        function displayothernonworkillness() {
            if (document.getElementById('nonwork_ill').value == 'other') {
                document.getElementById('illnonwrkothrcase').style.display = "";
                document.getElementById('nonwork_oth').required = true;
            } else {
                document.getElementById('illnonwrkothrcase').style.display = "none";
                document.getElementById('nonwork_oth').required = false;
            }
        }


        /**--pandemic details function-- */
        function pandemicdisplay() {
            if (document.getElementById('yespandemic').checked) {
                document.getElementById('pand').style.display = "";
                document.getElementById('cont').style.display = "";
                document.getElementById('pandemics').required = true;
            } else if (document.getElementById('nopandemic').checked) {
                document.getElementById('pand').style.display = "none";
                document.getElementById('cont').style.display = "none";
                document.getElementById('pandemics').required = false;
            }
        }


        /**--treatment details function-- */
        function display1() {
            if (document.getElementById('yes1').checked) {
                document.getElementById('treat').style.display = "";
            } else if (document.getElementById('no1').checked) {
                document.getElementById('treat').style.display = "none";
                document.getElementById('treatmentsother').style.display = "none";
                document.getElementById('treatment_oth').required = false;
            }
        }


        function treatmentsotherdisplay() {
            if (document.getElementById('treatments_other').checked) {
                document.getElementById('treatmentsother').style.display = "";
                document.getElementById('treatment_oth').required = true;
            } else {
                document.getElementById('treatmentsother').style.display = "none";
                document.getElementById('treatment_oth').required = false;
            }
        }

        /**--return to work details function-- */
        function display3() {
            if (document.getElementById('yes3').checked) {
                document.getElementById('return').style.display = "";
                document.getElementById('returnworks').required = true;
            } else if (document.getElementById('no3').checked) {
                document.getElementById('return').style.display = "none";
                document.getElementById('returnworks').required = false;
            }
        }

    </script>


    @endsection
