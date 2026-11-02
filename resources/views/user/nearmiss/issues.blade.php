@extends('pages.datatables')

@section('exportadd')

<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{ route('nearmiss.addissue')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>

@endsection

@section('content')
<!doctype html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    <style>
        .headingselect {
            white-space: break-spaces;
            line-height: 1rem;
        }

    </style>
</head>

<body>
    @php
    $issues2=$issues1=$issues3=[];
    @endphp
    <input type="hidden" value="a1" id="tabselected">

    <div class="content" style="width:100%; height:100vh;">
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">

                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Near Miss
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="{{route('nearmiss.index')}}">Register</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>

                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <thead>
                            <tr>
                                <td class="text-center titleheading" style="width:1%;"></td>
                                <td class="text-center titleheading" style="width:5%;">Slno</td>
                                <td class="text-center titleheading" style="width:5%;">Id</td>
                                <td id="deptselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;"></td>
                                <td class="text-center titleheading" style="width:30px;">Area</td>
                                <td id="typeselectdata" data-orderable="false" style="width:7%;text-transform:capitalize;"></td>
                                <td class="text-center titleheading" style="width:22%;">Title</td>
                                <td id ="responsibleselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;"></td>
                                <td id="duedateselectdata" data-orderable="false" style="width: 30px;text-transform:capitalize;position:sticky;"></td>
                                <td id ="stageselectdata" data-orderable="false" class="text-center" style="width:5%;text-transform:capitalize;position:sticky;"></td>
                                <td class="text-center titleheading" style="width:7%;">Action Status</td>
                                <td class="text-center titleheading" style="width:10%;">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=0; @endphp
                            @foreach($issues as $issue)
                                @php
                                    $rr=$issue->id;
                                    $date = $issue->targetdate;
                                    $today = Date("Y-m-d");
                                    $issuedocs=explode(',',$issue->file);
                                    $actiondocs=explode(',',$issue->action_file);
                                    $reviewauthority=explode(',',$issue->reviewauthority);
                                    $closureauthority=explode(',',$issue->closureauthority);
                                @endphp
                                <tr>
                                    <td><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td>
                                    <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if ($issue->status == 'open' && $today > $date)
                                        <span class="badge badge-danger" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                                        @else
                                        <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="font-size:10px;">
                                        {{$issue->department}}
                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted">{{$issue->area}}</em>
                                    </td>
                                    <td class="text-center" style="font-size:12px;">
                                        @if($issue->type=="Low")
                                        <span class="badge badge-success">{{$issue->type}}</span>
                                        @elseif($issue->type=="Moderate"|| $issue->type=="Medium")
                                        <span class="badge badge-warning">{{$issue->type}}</span>
                                        @elseif($issue->type=="High")
                                        <span class="badge badge-danger">{{$issue->type}}</span>
                                        @else
                                        <span class="badge badge-primary">{{$issue->type}}</span>
                                        @endif
                                    </td>
                                    <td class=" font-w600 " style="font-size:10px;">
                                        <a href="#">{{$issue->title}}</a>
                                    </td>
                                    <td class="text-center" style="font-size:10px;">
                                        {{ $issue->responsible ? $issue->responsible->name : '-' }}
                                    </td>

                                    <td class="text-center" style="font-size:10px;">
                                        {{$issue->targetdate ? date('d-m-Y',strtotime($issue->targetdate)) : '-'}}
                                    </td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if($issue->processstage=='Awaiting actions')
                                        <span class="badge badge-secondary">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Guest Submitted')
                                        <span class="badge" style="background-color:#6f42c1;color:white;">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Actions Proposed')
                                        <span class="badge badge bg-flat" style="color:white">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Action Reviewed, OK')
                                        <span class="badge badge-success">Action Approved</span>
                                        @elseif($issue->processstage=='Action Reviewed, Not OK')
                                        <span class="badge badge-danger">Action Rejected</span>
                                        @elseif($issue->processstage=='Need More Action')
                                        <span class="badge badge-warning">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Actions Completed')
                                        <span class="badge badge-info">{{$issue->processstage}}</span>
                                        @elseif($issue->processstage=='Entry Rejected')
                                        <span class="badge badge-danger">{{$issue->processstage}}</span>
                                        @endif

                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        @if($issue->processstage=="Awaiting actions")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">0%</p>
                                        @endif
                                        @if($issue->impl_status=="Progress-25%" || $issue->processstage=="Action Reviewed, OK" && $issue->impl_status!="Partially Completed-50%" )
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">25%</p>
                                        @endif
                                        @if($issue->impl_status=="Partially Completed-50%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">50%</p>
                                        @endif
                                        @if($issue->impl_status=="Completed-100%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">100%</p>
                                        @endif
                                        @if($issue->processstage=="Action Reviewed, Not OK")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">10%</p>
                                        @endif
                                        @if($issue->processstage=="Actions Proposed" && $issue->impl_status=="Not Started-0%")
                                        <div class="progress mb-1" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0">0%</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                                @if($issue->processstage=="Guest Submitted" && in_array(Auth::user()->email,$reviewers))
                                                <a href="{{ route('nearmiss.intialreview', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Initial Review</a>
                                                @endif
                                                @if($issue->processstage!="Guest Submitted" && $issue->processstage!="Entry Rejected")  
                                                    @if($issue->processstage=="Actions Proposed" && in_array(Auth::user()->email,$reviewauthority))
                                                    <a href="{{ route('nearmiss.reviewstatus', ['id' => $rr])}}" class="dropdown-item" style="font-size:10px;">Review Action</a>
                                                    @endif
                                                    @if(($issue->processstage=="Actions Completed" || $issue->impl_status=="Completed-100%") && in_array(Auth::user()->email,$closureauthority))
                                                    <a href="{{ route('nearmiss.closure', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                                    @endif
                                                    <a href="#" class="dropdown-item" style="font-size:10px;" onclick="theAlert({{ $issue->id }})">Add attachment</a>
                                                    @if(Gate::allows('nearmiss-responsibility-change'))
                                                    <a href="{{ route('nearmiss.editresp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Responsibility change</a>
                                                    @endif
                                                @endif
                                            </div>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $issue->id }}" data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{ $issue->id}}">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                            </a>
                                        </div>
                                        <!-- <button type="button" class="btn btn-primary viewDetails" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $issue->id  }}" data-target="#viewModal{{ $issue->id }}"><strong>View</strong> </button> -->
                                        <div id="viewModal{{ $issue->id }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" style="padding:1rem;">Detailed View</h2>
                                                        <div class="p-1 text-center rounded-top" style="padding:1rem">
                                                            <img class="img-avatar img-avatar48 img-avatar-thumb" data-toggle="modal" data-target="#modal-block-fadein" alt="" id="responsible{{$issue->id}}">
                                                            <img class="img-avatar img-avatar48 img-avatar-thumb" data-toggle="modal" data-target="#modal-block-fadein" alt="" id="postedbyUser{{$issue->id}}">
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:230px">Issue id</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @if ($issue->status == 'open' && $today > $date)
                                                                <span class="badge badge-danger">{{$issue->issueid}}</span>

                                                                @else
                                                                <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if($issue->department)
                                                        <tr>
                                                            <th>Department</th>
                                                            <td style="text-transform:capitalize" class="text-primary">{{$issue->department}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->division)
                                                        <tr>
                                                            <th>Division</th>
                                                            <td class="division-gray">{{ $issue->division ? $issue->division->name : '' }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->subdiv)
                                                        <tr>
                                                            <th>Subdivision</th>
                                                            <td class="subdivision-brown">{{ $issue->subdiv ? $issue->subdiv->name : '' }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->type)
                                                        <tr>
                                                            <th>Type</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @if($issue->type=="Low")
                                                                <span class="badge badge-success">{{$issue->type}}</span>
                                                                @elseif($issue->type=="Moderate"|| $issue->type=="Medium")
                                                                <span class="badge badge-warning">{{$issue->type}}</span>
                                                                @elseif($issue->type=="High")
                                                                <span class="badge badge-danger">{{$issue->type}}</span>
                                                                @else
                                                                <span class="badge badge-primary">{{$issue->type}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Process stage</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @if($issue->processstage=='Awaiting actions')
                                                                <span class="badge badge-secondary">{{$issue->processstage}}</span>
                                                                @elseif($issue->processstage=='Guest Submitted')
                                                                <span class="badge" style="background-color:#6f42c1;color:white;">{{$issue->processstage}}</span>
                                                                @elseif($issue->processstage=='Actions Proposed')
                                                                <span class="badge badge bg-flat" style="color:white">{{$issue->processstage}}</span>
                                                                @elseif($issue->processstage=='Action Reviewed, OK')
                                                                <span class="badge badge-success">Action Approved</span>
                                                                @elseif($issue->processstage=='Action Reviewed, Not OK')
                                                                <span class="badge badge-danger">Action Rejected</span>
                                                                @elseif($issue->processstage=='Entry Rejected')
                                                                <span class="badge badge-danger">{{$issue->processstage}}</span>
                                                                @elseif($issue->processstage=='Need More Action')
                                                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                                                                @elseif($issue->processstage=='Actions Completed')
                                                                <span class="badge badge-info">{{$issue->processstage}}</span>
                                                                @endif
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Title</th>
                                                            <td style="text-transform:capitalize">{{$issue->title}}</td>
                                                        </tr>
                                                        @if($issue->reportedId)
                                                        <tr>
                                                            <th>Reported by Id</th>
                                                            <td>{{$issue->reportedId}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->reporter_type)
                                                        <tr>
                                                            <th>Reporter Type</th>
                                                            <td>{{$issue->reporter_type}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->employeeid)
                                                        <tr>
                                                            <th>Employee Id</th>
                                                            <td>{{$issue->employeeid}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->employeeid && $issue->employeeDetails)
                                                        <tr>
                                                            <th>Employee Name</th>
                                                            <td>{{$issue->employeeDetails->emp_name}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->employeeid && $issue->employeeDetails)
                                                        <tr>
                                                            <th>Employee Department</th>
                                                            <td>{{optional($issue->employeeDetails->department)->departmentname}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->reporter_name)
                                                        <tr>
                                                            <th>Reporter Name</th>
                                                            <td>{{$issue->reporter_name}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->mobile_number)
                                                        <tr>
                                                            <th>Mobile Number</th>
                                                            <td>{{$issue->mobile_number}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->company_name)
                                                        <tr>
                                                            <th>Company Name</th>
                                                            <td>{{$issue->company_name}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->email)
                                                        <tr>
                                                            <th>Email Address</th>
                                                            <td>{{$issue->email}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->reportedName)
                                                        <tr>
                                                            <th>Reported by name</th>
                                                            <td>{{$issue->reportedName}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->closureauthority)
                                                        <tr>
                                                            <th>Closure Authority</th>
                                                            <td>{{$issue->closureauthority}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->reviewauthority)
                                                        <tr>
                                                            <th>Review Authority</th>
                                                            <td>{{$issue->reviewauthority}}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Observation</th>
                                                            <td style="text-transform:capitalize">
                                                                <?php echo nl2br($issue->observation ) ?></td>
                                                        </tr>
                                                        @if($issue->responsibility)
                                                        <tr>
                                                            <th>Responsibility</th>
                                                            <td style="text-transform:capitalize">{{$issue->responsibility}}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->agreedaction && $issue->agreedaction!='NULL')
                                                        <tr>
                                                            <th>Recommended action</th>
                                                            @if($issue->agreedaction!='NULL')
                                                            <td><?php echo nl2br ($issue->agreedaction) ?></td>
                                                            @else
                                                            <td></td>
                                                            @endif
                                                        </tr>
                                                        @endif
                                                        @if($issue->targetdate)
                                                        <tr>
                                                            <th>Target date</th>
                                                            <td>{{$issue->targetdate ? date('M/d/Y',strtotime($issue->targetdate)) : ''}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->correction)
                                                        <tr>
                                                            <th>Correction</th>
                                                            <td style="text-transform:capitalize">
                                                                <?php echo nl2br($issue->correction)?></td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->rootcause)
                                                        <tr>
                                                            <th>Root cause</th>
                                                            <td style="text-transform:capitalize">
                                                                <?php echo nl2br($issue->rootcause) ?></td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->correctiveaction)
                                                        <tr>
                                                            <th>Corrective action</th>
                                                            <td style="text-transform:capitalize">
                                                                <?php echo nl2br($issue->correctiveaction) ?></td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->file && $issue->file!='NULL')
                                                        <tr>
                                                            <th>Attachments (issues)</th>
                                                            <td>
                                                                <div class="col-sm-8">
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View
                                                                            Attachments</strong> </button>
                                                                    <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                                                                                        @php $n=0; @endphp

                                                                                        @if( count($issuedocs) > 0 )
                                                                                        @foreach($issuedocs as $value1)

                                                                                        @if( $value1!="NULL" )
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:10px; ;">
                                                                                                    {{ $n=$n+1}}</td>
                                                                                                <td style="font-size:15px;width: 100%;">
                                                                                                    {{ $value1}}</td>


                                                                                                @if(File::exists(public_path('/storage/nearmiss/'.$value1)))
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/nearmiss/".$value1)}}" download="{{$value1}}">Download</a>
                                                                                                </td>

                                                                                                @else
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}">Download</a>
                                                                                                </td>

                                                                                                @endif

                                                                                            </tr>
                                                                                        </tbody>
                                                                                        @else
                                                                                        <div class="row justify-content-center">
                                                                                            <h2>No Files</h2>
                                                                                        </div>
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
                                                        @endif
                                                        @if($issue->action_file && $issue->action_file!='NULL')
                                                        <tr>
                                                            <th>Attachments (action)</th>
                                                            <td>
                                                                <div class="col-sm-8">
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal6{{$issue->id}}"><strong>View
                                                                            Attachments</strong> </button>
                                                                    <div id="viewModal6{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                                                                                        @php $o=0; @endphp

                                                                                        @if( count($actiondocs) > 0 )
                                                                                        @foreach($actiondocs as $value2)

                                                                                        @if( $value2!="NULL" && $value2!="")
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:10px; ;">
                                                                                                    {{ $o=$o+1}}</td>
                                                                                                <td style="font-size:15px;width: 100%;">
                                                                                                    {{ $value2}}</td>

                                                                                                @if(File::exists(public_path('/storage/nearmiss/'.$value2)))
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/nearmiss/".$value2)}}" download="{{$value2}}">Download</a></td>

                                                                                                @else
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}">Download</a></td>

                                                                                                @endif
                                                                                            </tr>
                                                                                        </tbody>
                                                                                        @else
                                                                                        <div class="row justify-content-center">
                                                                                            <h2>No Files</h2>
                                                                                        </div>
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
                                                        @endif
                                                        @if($issue->initial_reviewed_rejection)
                                                        <tr>
                                                            <th>Initial Review Remarks</th>
                                                            <td>{{ nl2br($issue->initial_reviewed_rejection) }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->remarks)
                                                        <tr>
                                                            <th>Remarks</th>
                                                            <td style="text-transform:capitalize;color:red">{{ nl2br($issue->remarks) }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->action_remarks)
                                                        <tr>
                                                            <th>Action review remarks</th>
                                                            <td style="text-transform:capitalize;color:red">{{ nl2br($issue->action_remarks) }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->postedby)
                                                        <tr>
                                                            <th>Posted by</th>
                                                            <td>{{$issue->postedby}}&nbsp; {{ $issue->postedByUser ? $issue->postedByUser->name : ''}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->postedByUser)
                                                        <tr>
                                                            <th>Posted by department</th>
                                                            <td>{{($issue->postedByUser) ? $issue->postedByUser->deptid : null}}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Added date</th>
                                                            <td>{{$issue->created_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Last reviewed</th>
                                                            <td>{{$issue->updated_at}}</td>
                                                        </tr>
                                                        
                                                        @if($issue->initial_reviewed_at)
                                                        <tr>
                                                            <th>Initial Review At</th>
                                                            <td>{{ date('d-m-Y h:i:s A', strtotime($issue->initial_reviewed_at)) }}</td>
                                                            
                                                        </tr>
                                                        @endif
                                                        @if($issue->initialReviewByUser)
                                                        <tr>
                                                            <th>Initial Reviewed By</th>
                                                            <td>{{ $issue->initialReviewByUser ? $issue->initialReviewByUser->name : ''}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->approvedby)
                                                        <tr>
                                                            <th>Approved by</th>
                                                            <td>{{$issue->approvedby}}&nbsp;{{ $issue->approvedByUser ? $issue->approvedByUser->name : ''}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($issue->closedby)
                                                        <tr>
                                                            <th>Issue closed by</th>
                                                            <td>{{$issue->closedby}}</td>
                                                        </tr>
                                                        @endif
                                                    </table>
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
        </div>
    </div>
    {{----------delete request button for doc controllers --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();

            let alltypenames = "{{ $alltypenames }}".split(',');
            let type_text = "";
            for (var i = 0; i < alltypenames.length; i++) {
                type_text += `<option value="${alltypenames[i]}">${alltypenames[i]}</option>`;
            }
                $typeheading = $(`<select class="headingselect" style="width:100px;border:none;" id="type_dropdown">
                <option value="">Type&nbsp;&nbsp;&#9207;</option>` +
                    type_text +
                    `</select>`);
             $('#typeselectdata').html($typeheading);
             $("#type_dropdown").on('change', function() {
                    console.log($(this).val());
                        if ($(this).val()) {
                            table.columns(5).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#type_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });
                });

            let allpstages = "{{ $allpstages }}".split(',');
            let stage_text = "";
            for (var i = 0; i < allpstages.length; i++) {
                stage_text += `<option value="${allpstages[i]}">${allpstages[i]}</option>`;
            }
            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                $stageheading = $(`<select class="headingselect" style="width:120px;" id="stage_dropdown">
                <option value="">Process Stage&nbsp;&nbsp;&#9207;</option>` +stage_text +
                    `</select>`);

           $('#stageselectdata').html($stageheading);
           $("#stage_dropdown").on('change', function() {
                console.log($(this).val());
                    if ($(this).val()) {
                        table.columns(9).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#stage_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });
            });

            let years = "{{ $years }}".split(',');
            let duedate_text = "";
            for (var i = 0; i < years.length; i++) {
                duedate_text += `<option value="${years[i]}">${years[i]}</option>`;
            }

            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                $duedateheading = $(`<select class="headingselect" style="width:100px;" id="year_dropdown">
                <option value="">Duedate&nbsp;&nbsp;&#9207;</option>` +
                   duedate_text +
                    `</select>`);
            $('#duedateselectdata').html($duedateheading);
            $("#year_dropdown").on('change', function() {
                console.log($(this).val());
                    if ($(this).val()) {
                        table.columns(8).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#year_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });

            });

            let alldeptnames = "{{ $alldeptnames }}".split(',');
            let dept_text = "";
            for (var i = 0; i < alldeptnames.length; i++) {
                if(alldeptnames[i]) {
                    dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
                }
            }
            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                $deptheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="dept_dropdown">
                <option value="">Department&nbsp;&nbsp;&#9207;</option>` +dept_text +
                    `</select>`);

           $('#deptselectdata').html($deptheading);
           $("#dept_dropdown").on('change', function() {
                console.log($(this).val());
                    if ($(this).val()) {
                        table.columns(3).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#dept_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });
            });

            let allusernames = "{{ $allusernames }}".split(',');
            let all_user_text = "";
            for (var i = 0; i < allusernames.length; i++) {
                all_user_text += `<option value="${allusernames[i]}">${allusernames[i]}</option>`;
            }
            $("#tabledata").one("preInit.dt", function() {
                let table = $('#tabledata').DataTable();
                $alluserheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="all_user_dropdown">
                <option value="">Responsibility&nbsp;&nbsp;&#9207;</option>` +all_user_text +
                    `</select>`);

           $('#responsibleselectdata').html($alluserheading);
           $("#all_user_dropdown").on('change', function() {
                console.log($(this).val());
                    if ($(this).val()) {
                        table.columns(7).search($(this).val()).draw();
                        $(this).addClass('text-primary');
                        $("#all_user_dropdown option").css('color', 'black')
                    } else {
                        location.reload();
                    }
                });
            });

        });
    </script>
    <script>
        var route_nearmiss_index = "{{ route('nearmiss.index') }}";

        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>
    <script>
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        function theAlert(id) {
            let url = '{{ route("nearmiss.saveActionAttachment", ":id") }}';
            url = url.replace(':id', id);
            Swal.fire({
                title: 'Select files'
                , html: '<label>Action Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>' +
                    '<label>Issue Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>'
                , showCancelButton: true
                , confirmButtonText: 'Upload'
                , onBeforeOpen: () => {
                    $(".swal2-file").change(function() {
                        var reader = new FileReader();
                        reader.readAsDataURL(this.files);
                    });
                }
            }).then((file) => {
                if (file.value) {
                    var formData = new FormData();
                    var files = $('.swal2-file')[0].files;
                    var issueFiles = $('.swal2-file')[1].files;
                    $.each(files, function(i, x) {
                        formData.append('fileToUpload[]', x);
                    });
                    $.each(issueFiles, function(i, x) {
                        formData.append('fileToUpload1[]', x);
                    });
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                        , method: 'post'
                        , url: url
                        , data: formData
                        , processData: false
                        , contentType: false
                        , success: function(resp) {
                            Swal.fire({
                                title: 'Uploaded'
                                , text: 'Your files have been uploaded'
                                , icon: 'success'
                            })
                        }
                        , error: function() {
                            Swal.fire({
                                title: 'Oops'
                                , text: 'Something went wrong!'
                                , icon: 'error'
                            })
                        }
                    })
                }
            })


        }

    </script>
    <script src="{{ asset('js/nearmiss/issues_script.js') }}"></script>
    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Options</h3>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="{{ route('nearmiss.index')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.closedissue')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-check-square fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.openissue') }}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            My Open Issues
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.analysis')}}">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div>-->
    </div>
    <!-- END Apps Modal -->

    @section('sideitem')
    {{-- @foreach($mr as $mrs) --}}
    {{-- @if(Auth::user()->email==$mrs) --}}
    @if(Gate::allows('edit-nearmiss') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="margin-left:310px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if(Gate::allows('delete-nearmiss') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection
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

                @foreach($issues as $issue)
                @php $today = date('d-m-Y');
                $postdate =$issue->created_at;
                $date=date('d-m-Y',strtotime($postdate));
                $t = Date("Y-m-d");
                @endphp
                @if($issue->department==Auth::user()->deptid)
                @if($issue->processstage=="Awaiting actions" && $today==$date)
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-check-circle text-success"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">A new issue raised</div>
                            <span class="font-w500 text-muted">An issue raised in {{$issue->department}} department with id {{$issue->issueid}}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if($issue->status == 'open' && $t > $issue->targetdate)
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-times-circle text-danger"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Due</div>
                            <span class="font-w500 text-muted">Due on {{$issue->issueid}} </span>
                        </div>
                    </a>
                </li>
                @endif
                @if($issue->impl_status!="Completed-100%")
                <li>
                    <a class="text-dark media py-2">
                        <div class="mr-2 ml-3">
                            <i class="fa fa-fw fa-plus-circle text-primary"></i>
                        </div>
                        <div class="media-body pr-2">
                            <div class="font-w600">Pending Actions</div>
                            <span class="font-w500 text-muted">Actions pending in {{$issue->issueid}}</span>
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

    @include('exporting.fornearmiss')

</body>
</html>
@endsection
