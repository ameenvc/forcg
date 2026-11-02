@extends('pages.datatables')
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
<!--
<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/report/add')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div> -->
@endsection
@section('content')
<!doctype html>
<html lang="en">

<body>



    <!-- Page Content -->
    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div class="scrollable" style="height:75vh; overflow-y:scroll;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Incident Report</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Closed Incidents</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>

                                <tr>
                                    <th></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Report No.</th>
                                    <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Type</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Department</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspArea&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>

                                    <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Incident Date</th>
                                    {{-- <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">injured Person</th> --}}
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Added By</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Added Date</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Status</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Investigation Status</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">View</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach($incidents as $incident)
                                <?php
                                    $injuredpersons=0;
                                    $today = Date("d/m/Y");
                                    $color = null ;
                                ?>
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$incident->report_no}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;">{{$incident->report_no}}</td>
                                    <td>
                                        <em class="text-mute" style="font-size:10px;">{{$incident->incident_type}}</em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">
                                            @if(is_numeric($incident->department))
                                                @foreach($departments as $dep)
                                                @if($incident->department==$dep->id)
                                                    {{$dep->departmentname}}
                                                    @break
                                                @endif
                                                @endforeach
                                            @else
                                            {{$incident->department}}
                                            @endif
                                        </em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$incident->area}}</em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$incident->incidentdate}}</em>
                                    </td>
                                    {{-- <td class="" style="font-size:10px;">
                                           <em class="text-muted">{{$incident->injuredperson}}</em>
                                    </td> --}}
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$incident->addedby}}</em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$incident->addeddate}}</em>
                                    </td>

                                    <td class="" style="font-size:12px;">
                                      <span class="badge badge-success">{{$incident->reviewed}}</span>
                                    </td>

                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if($incident->investigation_status=='Under Investigation')
                                        <span class="badge badge-danger">{{$incident->investigation_status}}</span>
                                        @elseif($incident->investigation_status=='Not Needed')
                                        <span class="badge badge-success">{{$incident->investigation_status}}</span>
                                        @elseif($incident->investigation_status=='Need Improvement')
                                        <span class="badge badge-danger">{{$incident->investigation_status}}</span>
                                        @elseif($incident->investigation_status=='Rejected')
                                        <span class="badge badge-danger">{{$incident->investigation_status}}</span>
                                        @else
                                        <span class="badge badge-success">{{$incident->investigation_status}}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <button
                                            type="button" class="btn btn-primary" style="font-size:10px;width: 60px;"
                                            data-toggle="modal"
                                            data-id="{{ $incident->id }}"
                                            data-target="#viewModal{{$incident->id}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$incident->id}}" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                        <button class="btn-block-option" data-dismiss="modal"><i class="si si-close"></i></button>
                                                    </div>
                                                    <table class="table table-striped" style="font-size:13px;">
                                                        <tr>
                                                            <th style="width:70px;">Report_No.</th>
                                                            <td>{{$incident->report_no}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Type</th>
                                                            <td>{{$incident->incident_type}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Department</th>
                                                            <td class="text-primary">
                                                                {{ $incident->departmentOfIncident
                                                                    ? $incident->departmentOfIncident->departmentname
                                                                    : $incident->department
                                                                }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Division</th>
                                                            <td class="division-gray">{{ $incident->division ? $incident->division->name : '' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Subdivision</th>
                                                            <td class="subdivision-brown">{{ $incident->subdiv ? $incident->subdiv->name : '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Area</th>
                                                            <td>{{$incident->area}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Incident Date</th>
                                                            <td>{{$incident->incidentdate}}</td>
                                                        <tr>
                                                        <tr>
                                                            <th>Incident Time</th>
                                                            <td>{{$incident->time}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Any person Injured/ Suffured</th>
                                                            <td>{{$incident->any_person_injured}}</td>
                                                        </tr>

                                                        @if($incident->any_person_injured=='yes')
                                                        <tr>
                                                            <th>Injured Person</th>
                                                            <td>
                                                                @if($incident->injuredperson=='other')
                                                                {{$incident->injured_person_oth_name}}
                                                                @else
                                                                {{$incident->injuredperson}}
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Employee ID of Injured Person</th>
                                                            <td>
                                                                @if($incident->injuredperson=='other')
                                                                {{$incident->injured_person_oth_empid}}
                                                                @else
                                                                {{$incident->empid}}
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Position of Injured Person</th>
                                                            <td>
                                                                @if($incident->injuredperson=='other')
                                                                {{$incident->injured_person_oth_position}}
                                                                @else
                                                                {{$incident->injured_person_position}}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Department of Injured Person</th>
                                                            <td>
                                                                @if($incident->injuredperson=='other')
                                                                    {{ $incident->injuredPersonOthDepartment
                                                                        ? $incident->injuredPersonOthDepartment->departmentname
                                                                        : $incident->injured_person_oth_dept
                                                                    }}
                                                                @else
                                                                    {{ $incident->injuredPersonDepartment
                                                                        ? $incident->injuredPersonDepartment->departmentname
                                                                        : $incident->injured_person_dept
                                                                    }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        <tr>
                                                            <th>Added By</th>
                                                            <td>{{$incident->addedby }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Added Date</th>
                                                            <td>{{$incident->addeddate}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Hospital</th>
                                                            <td>{{$incident->hospital}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Incident Place</th>
                                                            <td>{{$incident->incidentplace}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Employer</th>
                                                            <td>{{$incident->emplyoer}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Trade or Occupation</th>
                                                            <td>{{$incident->trade_or_occupation}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Description</th>
                                                            <td style="white-space:pre-wrap;word-wrap:break-word;max-width:55px;">{{$incident->incidentdesc}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Actions Taken</th>
                                                            <td style="white-space:pre-wrap;word-wrap:break-word;max-width:55px;">{{$incident->actions}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Local Regulation</th>
                                                            <td>{{$incident->local_regulation}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Incident Related Attachments(by reporter)</th>
                                                            <td>
                                                                @if ($incident->attachmentby_reporter)
                                                                    <div class="col-sm-8">
                                                                        <button type="button" class="btn btn-secondary btn-small viewAttachments" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $incident->id }}" data-report-no="{{ $incident->report_no }}" data-attachments="{{ $incident->attachmentby_reporter }}" data-target="#viewAttachmentModal"><strong>View Attachments</strong> </button>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td>
                                                                @if($incident->reviewed=='Under review' || $incident->reviewed=='Rejected')
                                                                <span class ="status status-blue">Incident Under Review</span>
                                                                @elseif($incident->reviewed=='Under Secondary Review')
                                                                <span class="status status-blue">Investigation Under Review</span>
                                                                @elseif($incident->reviewed=='Need Improvement')
                                                                <span class="status status-red">{{$incident->reviewed}}</span>
                                                                @elseif($incident->reviewed=='Actions Open')
                                                                <span class="status status-blue">{{$incident->reviewed}}</span>
                                                                @elseif(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Under Investigation')
                                                                <span class="status status-warning">Investigation Assigned</span>
                                                                @elseif(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Not Needed')
                                                                <span class="status status-secondary">No Investigation needed</span>
                                                                @elseif(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Reviewed')
                                                                <span class="status status-warning">Investigation Report Reviewed</span>
                                                                @elseif(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Need Improvement')
                                                                <span class="status status-red">Investigation Report Need Improvement</span>
                                                                @elseif(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Approved')
                                                                <span class="status status-success">Investigation Report Approved</span>
                                                                @elseif(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Rejected')
                                                                <span class="status status-red">Investigation Report Need Improvement</span>
                                                                @else
                                                                <span class="status status-success">{{$incident->reviewed}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Improve investigation</th>
                                                            <td class="text-danger">{{$incident->improve_investigation}}</td>
                                                        </tr>
                                                        @if($incident->reviewed=='Need Improvement')
                                                        <tr>
                                                            <th>Justification</th>
                                                            <td class="text-danger">{{$incident->improve_justification}}</td>
                                                        </tr>
                                                        @endif
                                                        @if(
                                                            $incident->reviewed=="Approved"
                                                            || $incident->reviewed=="Reviewed"
                                                            || $incident->reviewed=="Under Secondary Review"
                                                            || $incident->reviewed=='Need Improvement'
                                                            || $incident->reviewed=='Closed'
                                                          )
                                                        <tr>
                                                            <th>Reviewed Date</th>
                                                            <td>{{$incident->reviewed_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewed By</th>
                                                            <td>{{$incident->reviewed_by}}</td>
                                                        </tr>
                                                        @endif

                                                        <tr>
                                                            <th>Corrective Action Number</th>
                                                            <td>
                                                                @foreach($incident->correctives as $cor)
                                                                    <span class="badge badge-info">{{$cor->issueid}}</span>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!-- Display Review Report-->
                                                    @if(
                                                        $incident->reviewed=="Reviewed"
                                                        || $incident->reviewed=="Approved"
                                                        || $incident->reviewed=="Under Secondary Review"
                                                        || $incident->reviewed=='Need Improvement'
                                                        || $incident->reviewed=='Closed'
                                                    )
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View(Review Report)</h2>
                                                    </div>
                                                    <table class="table table-striped" style="font-size:13px;">
                                                        <tr>
                                                            <th style="width:70px;">Evaluation of control measures</th>
                                                            <td>{{$incident->eval}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Public reaction</th>
                                                            <td>{{$incident->pubrec}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Probability of occurrence</th>
                                                            <td>{{$incident->probablity}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Severity in terms of financial loss</th>
                                                            <td>{{$incident->finloss}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Effect on community</th>
                                                            <td>{{$incident->efcom}}</td>
                                                        <tr>
                                                        <tr>
                                                            <th>Severity in terms of human trauma</th>
                                                            <td>{{$incident->humtur}}</td>
                                                        <tr>
                                                            <th>Potential For Loss</th>
                                                            <td>{{$incident->potloss}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Severity of potential loss</th>
                                                            <td>{{$incident->sevpotloss }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Probability of reoccurrence</th>
                                                            <td>{{$incident->porbocc}}</td>

                                                        </tr>
                                                        <tr>
                                                            <th>Potential Risk Factor</th>
                                                            <td>{{$incident->severity}}</td>

                                                        </tr>
                                                        <tr>
                                                            <th>Incident Related Attachments(by reviewer)</th>
                                                            <td>
                                                                @if ($incident->attachmentby_reviewer)
                                                                    <div class="col-sm-8">
                                                                        <button type="button" class="btn btn-secondary btn-small viewAttachments" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $incident->id }}" data-report-no="{{ $incident->report_no }}" data-attachments="{{ $incident->attachmentby_reviewer }}" data-target="#viewAttachmentModal"><strong>View Attachments</strong> </button>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>further investigation needed?</th>
                                                            <td>{{$incident->further_investigate}}</td>
                                                        </tr>
                                                        @if($incident->further_investigate=="yes")
                                                            @if($incident->investigator)
                                                                <tr>
                                                                    <th>Team Leader</th>
                                                                    <td>{{$incident->investigator->user ? $incident->investigator->user->name : ''}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Team Members</th>
                                                                    <td>{{$incident->investigator->tmembersnames}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Target Date</th>
                                                                    <td>{{$incident->investigator->targetdate}}</td>
                                                                </tr>
                                                            @endif
                                                        @else
                                                            <tr>
                                                                <th>Justification to not making full investigation</th>
                                                                <td>{{$incident->justification}}</td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                    @endif
                                                    <!-- Display Investigation Report-->
                                                    @if(
                                                        $incident->investigation_status=='Investigation Completed'
                                                        || $incident->investigation_status=="Need Improvement"
                                                        || $incident->investigation_status=="Approved"
                                                    )
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View(Investigation Report)</h2>
                                                    </div>
                                                    <table class="table table-striped" style="font-size:13px;">
                                                        <tr>
                                                            <th style="width:70px;">Was there a Risk Assessment?</th>
                                                            <td>{{$incident->riskassessment}}</td>
                                                        </tr>
                                                        @if($incident->riskassessment=='yes')
                                                        <tr>
                                                            <th>Risk Assessment No.</th>
                                                            <td>{{$incident->riskassess}}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Was there a Pre-Task/Tool Box Briefing?</th>
                                                            <td>{{$incident->toolbox}}</td>
                                                        </tr>
                                                        @if($incident->toolbox=='yes')
                                                        <tr>
                                                            <th>Tool Box Date</th>
                                                            <td>{{$incident->tooldate}}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Was the Person Trained to do the Job?</th>
                                                            <td>{{$incident->training}}</td>
                                                        </tr>
                                                        @if($incident->training=='yes')
                                                        <tr>
                                                            <th>Training Date</th>
                                                            <td>{{$incident->traindate}}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Was the Person Authorised to do the Works?</th>
                                                            <td>{{$incident->autherised}}</td>
                                                        <tr>
                                                        <tr>
                                                            <th>Activity carried out at time of Incident</th>
                                                            <td style="white-space:pre-wrap;word-wrap:break-word;max-width:55px;">{{$incident->activity}}</td>
                                                        <tr>
                                                            <th>Description of Incident</th>
                                                            <td style="white-space:pre-wrap;word-wrap:break-word;max-width:55px;">{{$incident->investigatordisc}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Parts of Body affected</th>
                                                            <td>{{$incident->bodypart }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Was the incident caused by an unsafe work condition?</th>
                                                            <td>{{$incident->unsafework}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Was the incident caused by an unsafe act?</th>
                                                            <td>{{$incident->unsafeact}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Personal Protective Equipment being used</th>
                                                            <td>{{$incident->ppkit}}</td>
                                                        </tr>
                                                        @php
                                                            $otherPreventedReason = explode(',',$incident->prevent);
                                                            if(in_array("Other", $otherPreventedReason)){
                                                                $otherPreventedReason[] = $incident->prevent_other;
                                                                $reason = implode(',',array_diff($otherPreventedReason,['Other']));
                                                            }else{
                                                                $reason = $incident->prevent;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <th>Future incident / spread prevention method</th>
                                                            <td>{{$reason}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Target Date</th>
                                                            <td>{{$incident->currective_targdate}}</td>
                                                        </tr>

                                                    </table>
                                                    @endif
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
            <!-- END Dynamic Table Full -->


        </div>
        <!-- END Page Content -->
        {{-- </main> --}}
        <!-- END Main Container -->


        <!-- END Footer -->

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
                                                Open Corrective Actions
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

        {{-- View Attachments Modal --}}
        <div class="modal fade" id="viewAttachmentModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attachments</h5>
                        <button class="btn-block-option" data-dismiss="modal"><i class="si si-close"></i></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                            <div class="justify-content-center">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>S.No</th>
                                        <th style="width: 100%">Attachments</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="attachmentBody">
                                </tbody>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).on("click",".viewAttachments",function(){
            var attachments = $(this).data('attachments');
            var dir = $(this).data('reportNo');

            let tbody = $("#attachmentBody");
            tbody.empty();

            if(!attachments){
                tbody.append("<tr><td colspan='3' class='text-center'>No Files</td></tr>");
                return;
            }

            let files = attachments.split(",");
            let basePath = "{{ asset('storage/IncidentFiles') }}/";

            files.forEach(function(file,index){
                let url = basePath + dir + "/" + file;
                let row = `
                    <tr>
                        <td>${index+1}</td>
                        <td>${file}</td>
                        <td>
                            <a class="btn btn-success btn-sm"
                            href="${url}"
                            download>
                            Download
                            </a>
                        </td>
                    </tr>
                `;

                tbody.append(row);
            });
        });

        $(document).on("click",".viewAttachments",function(){
            var id = $(this).data("id");
            $('#viewAttachmentModal').attr('data-id', id);
            $("#viewModal"+id).modal("hide");
        });

        $('#viewAttachmentModal').on('hidden.bs.modal', function () {
            var id = $(this).data("id");
            // remove leftover backdrop if exists
            $('.modal-backdrop').remove();

            // re-enable body scroll handling
            $('body').removeClass('modal-open');

            // reopen first modal
            $('#viewModal'+id).modal({
                backdrop: 'static',
                keyboard: false
            });
        });

    </script>
</html>
@endsection
