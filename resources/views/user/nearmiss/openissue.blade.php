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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{ route('nearmiss.addissue') }}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection

@section('content')
<!doctype html>
<html lang="en">
<style>
    body {
        overflow-y: hidden;
    }

</style>
<style>
    .header {
        margin-left: 7%;
        margin-bottom: -3.6%;
    }

</style>
<body>
    <?php
    $issues1=$issues=$issues3=[];
    ?>
    <input type="hidden" value="a3" id="tabselected">

    <!--Start of Change Log  -->
    <!-- Page Content -->
    <div class="tab-pane active" id="My_open" role="tabpanel" style="">
        <div class="content" style="width:100%">

            <!-- Dynamic Table Full -->
            <div class="block block-rounded" style="margin-left:-1%;">

                <div class="block-content block-content-full">

                    <!--shift header to white background-->

                    <div class="scrollable" id="content">

                        <table class="move" width="15%" style="margin-left:670px">
                            <tr>

                                <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                        <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                            <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Near Miss</li>
                                            <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                <a class="link-fx" href="{{ route('nearmiss.openissue')}}">Open Issues</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </th>
                            </tr>
                        </table>

                        <!--end of shift header to white background-->


                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize">Slno</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Id</th>
                                    {{-- <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Category</th> --}}
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Department</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Type</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Target Date</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Process Stage</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize">Action Status</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize">Implementation Status</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Actions</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 20px;text-transform:capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach ($issues2 as $issue)
                                <?php $rr=$issue->id;
                                    $date = $issue->targetdate;
                                    $today = Date("Y-m-d");
                                    $issuedocs=explode(',',$issue->file);
                                                $actiondocs=explode(',',$issue->action_file);
                                    ?>
                                <tr>
                                    <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if ($issue->status == 'open' && $today > $date)
                                        <span class="badge badge-danger" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                                        @else
                                        <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted" style="font-size:10px;">{{$issue->department}}</em>
                                    </td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
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

                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted">{{date('M/d/Y',strtotime($issue->targetdate))}}</em>
                                    </td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        @if($issue->processstage=='Awaiting actions')
                                        <span class="badge badge-secondary">{{$issue->processstage}}</span>
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
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        @if($issue->processstage=="Awaiting actions")
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">0%</p>
                                        @endif
                                        @if($issue->impl_status=="Progress-25%" || $issue->processstage=="Action Reviewed, OK" && $issue->impl_status!="Partially Completed-50%"  )
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
                                    <div class="dropdown ">
                                        <td class="font-w600 " class="text-center " style="font-size:10px;">
                                            @if( $issue->processstage=='Action Reviewed, OK' )
                                            <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width: 70px;height:30px;" data-toggle="dropdown">Update</button>
                                            <div class="dropdown-menu " aria-labelledby="dropdown-dropleft-outline-dark">
                                                <a href="{{ route('nearmiss.hundred', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Completed-100%</a>
                                                <a href="{{ route('nearmiss.five', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Partially Completed-50%</a>
                                                <a href="{{ route('nearmiss.two', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Progress-25%</a>
                                                <a href="{{ route('nearmiss.zero', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Not Started-0%</a>
                                            </div>
                                            {{-- @elseif($issue->processstage=='Actions Completed' && $issue->impl_status=="Completed-100%")
                                            <div class="progress mb-1" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p class="fs-xs fw-semibold mb-0">100%</p>--}}
                                            @endif
                                        </td>
                                    </div>
                                    <td class="font-w600 " class="text-center " style="font-size:10px;">
                                        @if ($issue->processstage =='Awaiting actions' )
                                        <a class="btn btn-primary" style="font-size:10px;width: 120px;" href="{{ route('nearmiss.editopen', ["id" => $rr])}}">Propose Action</a>
                                        @elseif ($issue->processstage =='Action Reviewed, Not OK' || $issue->processstage =='Action Reviewed, OK' || $issue->processstage == 'Need More Action' || $issue->processstage == 'Actions Proposed')
                                        <a class="btn btn-primary" style="font-size:10px;width: 68px;" href="{{ route('nearmiss.editrespondedissue', ["id" => $rr])}}">Edit</a>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary viewDetails" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $issue->id  }}" data-target="#viewModal{{ $issue->id }}"><strong>View</strong> </button>
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
            <!-- END Dynamic Table Full -->
        </div>
    <!-- END Page Content -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".viewDetails").on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "/show/" + id + "/" + "nearmiss"
                    , type: 'get'
                    , data: {
                        id: id
                    }
                    , dataType: "json"
                    , success: function(response) {
                        response.data.responsibleUser ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                        response.data.postedByUser ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                    }
                });
            });
        });
        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 240);
    </script>

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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('nearmiss.openissue')}}">
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



    </div> <!-- END Apps Modal -->
    @include('exporting.fornearmiss')
</body>
</html>
@endsection
