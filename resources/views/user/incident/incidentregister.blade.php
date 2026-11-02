@extends('pages.datatables')
@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/report/add')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">
<style>
    body {
        overflow-y: auto;
    }

    .status{
    display: inline-block;
    padding: 7px 5px 5px 7px;
    font-size: 75%;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  .status-blue{
      color: #25499c;
      background-color: #d9e2f6;
      border-color: #d9e2f6;
  }

  .status-red{
      color: #af1e1e;
      background-color: #f8d4d4;
      border-color: #fbeaea;
  }
  .status-warning{
      color: #af6f1e;
      background-color: #fbdece;
      border-color: #fbf4ea;
  }
  .status-secondary{
      color: #343a40;
      background-color: #E5E4E2;
      border-color: #edf0f2;
  }
  .status-success{
      color: #176044;
      background-color: #e0edcf;
      border-color: #bff0dd;
  }

</style>
<body>



    <!-- Page Content -->
    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                <div class="scrollable" style="overflow-y:scroll;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Incident Report</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Incident Register</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:1%; position:sticky;top:-1;z-index: 1"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:1%; position:sticky;top:-1;">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize; width:7%;position:sticky;top:-1;">Report No.</th>
                                    <th style="font-size:10px;text-transform:capitalize;width:10%; position:sticky;top:-1;">Type</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; width:10%;position:sticky;top:-1;">Department</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;width:10%; position:sticky;top:-1;">Investigation lead</th>
                                    <th style="font-size:10px;text-transform:capitalize; width:10%;position:sticky;top:-1;">Incident Date</th>
                                    <th class="d-none d-sm-table-cell text-center" style="font-size:10px;text-transform:capitalize;width:10%; position:sticky;top:-1;">Status</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;width:10%; position:sticky;top:-1;">Corrective Action Status</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;width:4%; position:sticky;top:-1;z-index: 1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach($incidents as $incident)
                                @php
                                $injuredpersons=0;
                                $today = Date("Y-m-d");
                                $color = null ;
                                $docs=[];
                                $docs=explode(',',$incident->attachmentby_reporter);
                                $rdocs=explode(',',$incident->attachmentby_reviewer);
                                $dir=str_replace('/', '_',$incident->report_no);
                                @endphp
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{$incident->report_no}}" name="id[]"></td>
                                    <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center" style="font-size:10px;">{{$incident->report_no}}</td>
                                    <td>
                                        <em class="text-mute" style="font-size:10px;">{{$incident->incident_type}}</em>
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">
                                            {{ $incident->departmentOfIncident ? $incident->departmentOfIncident->departmentname : '' }}
                                        </em>
                                    </td>
                                    <td style="font-size:10px;" align="center">
                                        @if($incident->investigator)
                                            @if($incident->investigator->user
                                                && $incident->investigator->user->profile_picture
                                                && File::exists(public_path('/storage/profile/'. $incident->investigator->user->profile_picture))
                                            )
                                                <img src="{{ asset("storage/profile/". $incident->investigator->user->profile_picture) }}"
                                                     class="avatar"
                                                     title="{{ $incident->investigator->user->name}}"/>
                                            @else
                                                <img src="{{asset('/images/avatar10.jpg')}}" class="avatar" title="User Not found"/>
                                            @endif
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="" style="font-size:10px;">
                                        <em class="text-muted">{{$incident->incidentdate}}</em>
                                    </td>

                                    <td style="font-size:13px;" align="center">
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

                                    <td style="font-size:11px" class="text-center">
                                      {{ $incident->correctives_count ? 'Open' : 'Not Added' }}
                                    </td>
                                    <?php
                                        $rand = rand(999,1);
                                        $cnt = 0;
                                    ?>
                                <td style="font-size:10px" class="text-left">
                                <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$incident->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $incident->id }}">

                                            @if($incident->adder_email==Auth::user()->email && $incident->reviewed=='Under review')
                                                <a class='dropdown-item' onclick="return confirm('Do you really want to delete this Incident Report')"
                                                   href="{{url('/user/incident/report/deleteincident/'.$incident->id.'/'.$rand)}}" style="font-size:10px;">
                                                   <i class="far fa-window-close text-danger" style="font-size:10px;"></i>&nbsp;&nbsp; Delete
                                                </a>
                                            @endif
                                            @if($incident->adder_email == Auth::user()->email && $incident->reviewed == 'Need Improvement')
                                                <a class='dropdown-item' href="{{url('/user/incident/report/update/'.$incident->id.'/'.$rand)}}"
                                                   style="font-size:10px;">
                                                   <i class="far fa-edit" style="font-size:10px;"></i> &nbsp;&nbsp;Update Incident Report
                                                </a>
                                            @endif

                                            @if(Auth::user()->email == $incident->reviewer_email
                                                && ($incident->reviewed == 'Under review' || $incident->reviewed == 'Rejected')
                                            )
                                                <a class='dropdown-item' href="{{url('/user/incident/review/'.$incident->id.'/'.$rand)}}"
                                                   style="font-size:10px;">
                                                    <i class="far fa-calendar-check" style="font-size:10px;"></i>&nbsp;&nbsp; Review
                                                </a>
                                            @endif

                                            @if(($incident->reviewed=='Under review' || $incident->reviewed=='Rejected') && Gate::allows('change-incident-reviewer-approver'))
                                                <a href="{{url('/user/incident/changereviewer/'.$incident->id)}}" class="dropdown-item"
                                                   style="font-size:10px;">
                                                    <i class="fa fa-exchange-alt" style="font-size:10px;"></i>&nbsp;&nbsp;Change Reviewer
                                                </a>
                                            @endif

                                            @if(count($investigators) > 0)
                                                @foreach($investigators as $investigator)
                                                    @if($investigator->report_no === $incident->report_no)
                                                        @php $cnt+=1; @endphp
                                                    @endif
                                                @endforeach
                                            @endif

                                            @php
                                                $teammembers = explode(',', $incident->investigator ? $incident->investigator->tmembers : '');
                                            @endphp

                                            @if(
                                                $incident->investigator
                                                && (Auth::user()->email== $incident->reviewer_email
                                                    || Auth::user()->email== $incident->approver_email
                                                    || Auth::user()->email== $incident->investigator->tleader
                                                    || in_array(Auth::user()->email, $teammembers)
                                                )
                                                && $incident->correctives_count==0
                                            )
                                                @if(($incident->investigation_status != NULL) || ($incident->reviewed == 'Actions Open'))
                                                    <a class='dropdown-item' href="{{url('/user/incident/addcorrective/'.$incident->id.'/'.$rand)}}"
                                                       style="font-size:10px;">
                                                      <i class="far fa-calendar-plus" style="font-size:10px;"></i>&nbsp;&nbsp;Add Corrective Actions
                                                    </a>
                                                @endif
                                            @endif
                                                <!-- below button is to allow reviewer to  change investigation team -->
                                            @if( $cnt > 0)
                                                @if(
                                                    (Auth::user()->email==$incident->reviewer_email)
                                                    && ($incident->investigation_status!='Approved')
                                                    && ($incident->reviewed!='Closed')
                                                )
                                                    <a class='dropdown-item' href="{{url('/user/incident/change/investigators/'.$incident->id.'/'.$rand)}}"
                                                       style="font-size:10px;">
                                                      <i class="far fa-share-square" style="font-size:10px;"></i>&nbsp;&nbsp;Change Investigators
                                                    </a>
                                                @endif
                                            @endif
                                            <!-- below is to display updated investigation result once the innitial review is done by reviewer -->
                                            @if($incident->investigator && Auth::user()->email== $incident->investigator->tleader)
                                                @if($incident->investigation_status == 'Under Investigation')
                                                    <a class='dropdown-item' href="{{url('/user/update/incident/report/'.$incident->id.'/'.$rand)}}"
                                                       style="font-size:10px;">
                                                        <i class="far fa-list-alt" style="font-size:10px;"></i>&nbsp;&nbsp;Add Investigation Results
                                                    </a>
                                                @endif
                                                <!-- if investigation completed then allowing investigation leader to update the report again -->
                                                @if(
                                                    ($incident->investigation_status=='Need Improvement' || $incident->investigation_status=='Rejected')
                                                     && ($incident->reviewed!='Closed')
                                                )
                                                    <a class='dropdown-item' href="{{url('/user/updatesecond/incident/report/'.$incident->id.'/'.$rand)}}"
                                                       style="font-size:10px;">
                                                        <i class="fa fa-edit" style="font-size:10px;"></i>&nbsp;&nbsp;Update Investigation Results
                                                    </a>
                                                @endif
                                            @endif
                                            <!-- if investigation is completed then provide reviewer to review and approve -->
                                            @if(($incident->investigation_status=='Investigation Completed') && ($incident->reviewed!='Closed'))
                                                @if(Auth::user()->email==$incident->reviewer_email)
                                                    <a class='dropdown-item' href="{{url('/user/review/approve/investigation/'.$incident->id.'/'.$rand)}}"
                                                       style="font-size:10px;">
                                                      <i class="far fa-sticky-note" style="font-size:10px;"></i>&nbsp;&nbsp;Review Investigation
                                                    </a>
                                                @endif
                                            @endif

                                            @if(($incident->investigation_status=='Reviewed') && ($incident->reviewed!='Closed'))
                                                @if(Auth::user()->email==$incident->approver_email)
                                                    <a class='dropdown-item' href="{{url('/user/review/approve/investigation/report/'.$incident->id.'/'.$rand)}}"
                                                       style="font-size:10px;">
                                                       <i class="far fa-check-square" style="font-size:10px;"></i>&nbsp;&nbsp;Approve Investigation
                                                    </a>
                                                @endif
                                            @endif

                                            @if(
                                                ($incident->investigation_status=='Reviewed' || $incident->investigation_status=='Not Needed')
                                                 && ($incident->reviewed!='Closed')
                                            )
                                                @if(Gate::allows('change-incident-reviewer-approver'))
                                                    <a href="{{url('/user/incident/changeapprover/'.$incident->id)}}" class="dropdown-item"
                                                       style="font-size:10px;">
                                                      <i class="fa fa-exchange-alt" style="font-size:10px;"></i>&nbsp;&nbsp;Change Approver
                                                    </a>
                                                @endif
                                            @endif

                                            @if(
                                                ($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved')
                                                && $incident->investigation_status=='Not Needed'
                                            )
                                                @if(Auth::user()->email==$incident->approver_email)
                                                    <a href="{{url('/user/incident/closurereview/'.$incident->id)}}"
                                                       class="dropdown-item" style="font-size:10px;">
                                                      <i class="far fa-calendar-check" style="font-size:10px;"></i>&nbsp;&nbsp;Closure Review
                                                    </a>
                                                @endif
                                            @endif

                                            @if($incident->reviewed=='Under Secondary Review')
                                                @if(Gate::allows('change-incident-reviewer-approver'))
                                                    <a href="{{url('/user/incident/changeinvestigationreviewer/'.$incident->id)}}" class="dropdown-item" style="font-size:10px;">
                                                      <i class="fa fa-user-edit" style="font-size:10px;"></i>&nbsp;&nbsp;Change Investigation Reviewer
                                                    </a>
                                                @endif
                                            @endif

                                            @if(($incident->reviewed=='Reviewed' || $incident->reviewed=='Approved') && $incident->investigation_status=='Reviewed')
                                                @if(Gate::allows('change-incident-reviewer-approver'))
                                                    <a href="{{url('/user/incident/changeinvestigationapprover/'.$incident->id)}}" class="dropdown-item" style="font-size:10px;">
                                                       <i class="fa fa-user-edit" style="font-size:10px;"></i>&nbsp;&nbsp;Change Investigation Approver
                                                    </a>
                                                @endif
                                            @endif

                                            </div>

                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $incident->id }}"
                                           data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{$incident->id}}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                        </a>
                                    </div>

                                        <!-- <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $incident->id }}" data-target="#viewModal{{$incident->id}}"><strong>View</strong> </button> -->
                                        <div id="viewModal{{$incident->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
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
                                                                <div class="col-sm-8">
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
                                                                                        <?php $o=0; ?>

                                                                                        @if(count($docs) > 0)
                                                                                            @foreach($docs as $value1)
                                                                                                @if($value1!="")
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                                                            <td style="font-size:15px;width: 100%;">{{ $value1}}</td>
                                                                                                            <td>
                                                                                                                <a style="margin-left:70px;font-size:11px;width: 80px;"
                                                                                                                   class="btn btn-success"
                                                                                                                   href="{{asset("storage/IncidentFiles/".$dir."/".$value1)}}"
                                                                                                                   download="{{$value1}}">Download</a>
                                                                                                            </td>
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
                                                                <div class="col-sm-8">
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
                                                                                        <?php $n=0; ?>

                                                                                        @if(count($rdocs) > 0)
                                                                                            @foreach($rdocs as $value2)
                                                                                                @if($value2!="")
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                                                                            <td style="font-size:15px;width: 100%;">{{ $value2}}</td>
                                                                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;"
                                                                                                                   class="btn btn-success"
                                                                                                                   href="{{asset("storage/IncidentFiles/".$dir."/".$value2)}}"
                                                                                                                   download="{{$value2}}">Download</a>
                                                                                                            </td>
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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
            {{----------delete request button ----------}}
            <script>
                $("#del").click(function() {
                    var dataArr = new Array();
                    if ($('input:checkbox:checked').length > 0) {
                        $('input:checkbox:checked').each(function() {
                            dataArr.push($(this).attr('id'));
                            // console.log(dataArr);
                            $.ajax({
                                type: 'get'
                                , url: "{{route('deleteIncidentReport')}}" + "?val=" + $(this).attr('id')
                                , success: function(response) {
                                    window.location.href = "{{url('/user/incidents/register')}}";
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
            {{-- --------edit  button ------------}}
            <script>
                $("#edit").click(function() {
                    var dataArr = new Array();
                    var group = new Array();
                    var d;
                    var all;
                    if ($('input:checkbox:checked').length > 0) {
                        $('input:checkbox:checked').each(function() {
                            dataArr.push($(this).attr('id'));
                            //  console.log(dataArr);

                            all = "" + dataArr;
                            group.push(all); // push all checkbox values into an array
                            //  console.log(group);


                        });
                        $.ajax({
                            type: 'get'
                            , url: "{{route('editincident')}}" + "?val=" + (all)
                            , success: function(response) {
                                // alert(response);

                                window.location.href = "{{route('editincident')}}" + "?val=" + (all);
                                // console.log(response);

                            }
                            , error: function(response) {
                                alert(errResponse);
                            }
                        });

                    } else {
                        alert('No Record Selected');
                    }
                });

                function autoResizeDiv(height) {
                    document.getElementById('content').style.height = (height) + 'px';
                }
                window.onresize = autoResizeDiv;
                autoResizeDiv(window.innerHeight - 100);

            </script>

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
                            <!--
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


</body>
</html>
@endsection


@section('sideitem')
{{-- @foreach($mr as $mrs) --}}
{{-- @if(Auth::user()->email==$mrs) --}}
@if(Gate::allows('edit-incident') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" style="margin-left:398px;" id="edit" value="id[]">
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-incident') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" id="del" value="id[]" class="btn btn-sm btn-dual">
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
{{-- @endif --}}
{{-- @endforeach --}}

@section('notification')
<div class="dropdown d-inline-block ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-bell"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
        <div class="p-2 bg-primary-dark text-center rounded-top">
            <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
        </div>
        <ul class="nav-items mb-0">

            @foreach($incidents as $incident)
            <?php
                $today = Date("d/m/Y");
               ?>
            @if($incident->department==Auth::user()->deptid)
            @if($incident->reviewed=="Under review" && $incident->addeddate==$today)
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-check-circle text-success"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">A new Incident Report Added</div>
                        <span class="font-w500 text-muted">An new Incident Report added in {{$incident->department}} department with Report ID {{$incident->report_no}}</span>
                    </div>
                </a>
            </li>
            @endif

            @if($incident->reviewed == 'Under review' && $incident->addeddate<$today) <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-times-circle text-danger"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">Pending Actions</div>
                        <span class="font-w500 text-muted">Primary Review is Pending for Incident Report {{$incident->report_no}}</span>
                    </div>
                </a>
                </li>
                @endif

                @if($incident->reviewed == 'Under Secondary Review' || $incident->investigation_status=="Under Investigation" || $incident->investigation_status=="Need Improvement" || $incident->investigation_status=="Reviewed" )
                @foreach($investigators as $inv)
                @if($inv->report_no == $incident->report_no)
                @if($inv->targetdate < $today) <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-times-circle text-danger"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Due</div>
                            <span class="font-w500 text-muted">Due on {{$incident->report_no}} </span>
                        </div>
                    </a>
                    </li>
                    @endif
                    @endif
                    @endforeach
                    @endif
                    @if($incident->investigation_status=="Under Investigation" || $incident->investigation_status=="Need Improvement")
                    <li>
                        <a class="text-dark media py-2">
                            <div class="mr-2 ml-3">
                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                            </div>
                            <div class="media-body pr-2">
                                <div class="font-w600">Pending Actions</div>
                                <span class="font-w500 text-muted">Investigation is Pending for Incident Report {{$incident->report_no}}</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if($incident->investigation_status=="Reviewed")
                    <li>
                        <a class="text-dark media py-2">
                            <div class="mr-2 ml-3">
                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                            </div>
                            <div class="media-body pr-2">
                                <div class="font-w600">Pending Actions</div>
                                <span class="font-w500 text-muted">Approval is Pending for Incident Report {{$incident->report_no}}</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if($incident->investigation_status=="Investigation Completed")
                    <li>
                        <a class="text-dark media py-2">
                            <div class="mr-2 ml-3">
                                <i class="fa fa-fw fa-plus-circle text-primary"></i>
                            </div>
                            <div class="media-body pr-2">
                                <div class="font-w600">Pending Actions</div>
                                <span class="font-w500 text-muted">Secondary Review is Pending for Incident Report {{$incident->report_no}}</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @endif

                    @endforeach
        </ul>

    </div>
</div>
@endsection

@endsection
