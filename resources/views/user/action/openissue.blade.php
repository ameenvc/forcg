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



@endsection

@section('content')
<!doctype html>
<html lang="en">
<head>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
        <link rel="stylesheet" href="{{ asset('css/workflow.css') }}">
        <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">

        <style>
            body {
                overflow-y: hidden;
            }

            .status {
                display: inline-block;
                padding: 7px 5px 5px 7px;
                font-weight: 600;
                line-height: 1;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: 0.25rem;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .headingselect {
                white-space: break-spaces;
                line-height: 1rem;
            }

            .header {
                margin-left: 7%;
                margin-bottom: -3.6%;
            }
        </style>
    </head>


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
                                            <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Audit Management</li>
                                            <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                <a class="link-fx" href="/user/action/openissues">My Open NCRs</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </th>
                            </tr>
                        </table>

                        <!--end of shift header to white background-->


                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                        @endif
                        @if(Session::has('err'))
                            <div class="alert alert-danger">
                                <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                                {!!Session::get('err')!!}
                            </div>
                        @endif
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize"></th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize">Slno</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Id</th>
                                    {{-- <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Category</th> --}}
                                    <th id="deptselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                    <th id="typeselectdata" data-orderable="false" style="width:7%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30%;text-transform:capitalize">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th id="duedateselectdata" data-orderable="false" class="text-center" style="width:30px;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                    <th id="stageselectdata" data-orderable="false" class="text-center" style="width:5%;text-transform:capitalize;position:sticky;top:-1px;"></th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize">Action Status</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 50px;text-transform:capitalize">Implementation Status</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 30px;text-transform:capitalize">Actions</th>
                                    <th class="text-center" style="font-size:10px;position:sticky;top:-1;width: 5%;text-transform:capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>
                                @foreach ($issues2 as $issue)
                                <?php $rr=$issue->id;
                                    $date = $issue->duedate;
                                    $today = Date("Y-m-d");
                                    $dept = DB::table('departments')->where('id',$issue->department)->value('departmentname'); ?>
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
                                    {{-- <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted">{{$issue->category}}</em>
                                    </td> --}}
                                    <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                        <em class="text-muted" style="font-size:10px;">{{$dept}}</em>
                                    </td>
                                    <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                        {{-- @if($issue->type=="Low")
                                        <span class="badge badge-success">{{$issue->type}}</span>
                                        @elseif($issue->type=="Moderate"|| $issue->type=="Medium")
                                        <span class="badge badge-warning">{{$issue->type}}</span>
                                        @elseif($issue->type=="High")
                                        <span class="badge badge-danger">{{$issue->type}}</span>
                                        @else
                                        <span class="badge badge-primary">{{$issue->type}}</span>
                                        @endif --}}
                                        @php
                                        $switch = 0;
                                        $type = App\Type::where('order', $issue->type)->first();
                                        $switch = $type ? 1 : 0;
                                        @endphp
                                        <span class="status" style="font-size:10px;color:{{ $switch ? $type->text_color : '#25499c'}};background-color:{{ $switch ? $type->background_color : '#d9e2f6'}}">{{ $switch ? $type->name : '' }}</span>
                                    </td>
                                    <td class=" font-w600 " style="font-size:10px;">
                                        <a href="{{ route('user.actionsystem.detailopen', $issue->id) }}">{{$issue->title}}</a>
                                    </td>

                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        <em class="text-muted">{{date('M/d/Y',strtotime($issue->duedate))}}</em>
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
                                        @elseif($issue->processstage=='HOD Rejected')
                                        <span class="badge badge-danger">{{$issue->processstage}}</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                        @if($issue->processstage=="Awaiting actions" || $issue->processstage=='HOD Rejected')
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
                                    <div class="dropdown ">
                                        <td class="font-w600 " class="text-center " style="font-size:10px;">
                                            @if( $issue->processstage=='Action Reviewed, OK' )
                                            <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width: 70px;height:30px;" data-toggle="dropdown">Update</button>
                                            <div class="dropdown-menu " aria-labelledby="dropdown-dropleft-outline-dark">
                                                <a href="{{ url('/user/actionsystem/hundred?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Completed-100%</a>
                                                <a href="{{ url('/user/actionsystem/five?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Partially Completed-50%</a>
                                                <a href="{{ url('/user/actionsystem/two?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Progress-25%</a>
                                                <a href="{{ url('/user/actionsystem/zero?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Not Started-0%</a>
                                            </div>
                                            @endif
                                        </td>
                                    </div>
                                    <td class="font-w600 " class="text-center " style="font-size:10px;">
                                        @if ($issue->processstage =='Awaiting actions' )
                                            <a class="btn btn-primary" style="font-size:10px;width: 120px;"
                                               href="{{url('/user/actionsystem/editopen?val='.$rr)}}">Propose Action</a>
                                        @elseif ($issue->processstage =='HOD Rejected' || $issue->processstage == 'Need More Action' )
                                            <a class="btn btn-primary" style="font-size:10px;width: 68px;"
                                               href="{{url('/user/actionsystem/editopen?val='.$rr)}}">Edit</a>
                                        @elseif (
                                            $issue->processstage =='Action Reviewed, Not OK'
                                            || $issue->processstage =='Action Reviewed, OK'
                                            || $issue->processstage == 'Actions Proposed'
                                        )
                                        <a class="btn btn-primary" style="font-size:10px;width: 68px;" href="{{url('/user/actionsystem/editrespondedissue?val='.$rr)}}">Edit</a>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $issue->id  }}" data-target="#viewModal{{ $issue->id }}"><strong>View</strong> </button>
                                        <div id="viewModal{{ $issue->id }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Issue id</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @if ($issue->status == 'open' && $today > $date)
                                                                <span class="badge badge-danger">{{$issue->issueid}}</span>

                                                                @else
                                                                <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th>Category</th>
                                                            <td>{{$issue->category}}</td>
                                                        </tr> -->
                                                        <tr>
                                                            <th>Department</th>
                                                            <td>{{$dept}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Type</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                {{-- @if($issue->type=="Low")
                                                                <span class="badge badge-success">{{$issue->type}}</span>
                                                                @elseif($issue->type=="Moderate" || $issue->type=="Medium")
                                                                <span class="badge badge-warning">{{$issue->type}}</span>
                                                                @elseif($issue->type=="High")
                                                                <span class="badge badge-danger">{{$issue->type}}</span>
                                                                @else
                                                                <span class="badge badge-primary">{{$issue->type}}</span>
                                                                @endif --}}
                                                                @php
                                                                $switch = 0;
                                                                $type = App\Type::where('order', $issue->type)->first();
                                                                $switch = $type ? 1 : 0;
                                                                @endphp
                                                                <span class="status" style="font-size:10px;color:{{ $switch ? $type->text_color : '#25499c'}};background-color:{{ $switch ? $type->background_color : '#d9e2f6'}}">{{ $switch ? $type->name : '' }}</span>
                                                            </td>

                                                        </tr>

                                                        <tr>
                                                            <th>Process stage</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                <!-- @if($issue->processstage=='Awaiting actions')
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
                                                                @endif -->
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
                                                                @elseif($issue->processstage=='HOD Rejected')
                                                                <span class="badge badge-danger">{{$issue->processstage}}</span>
                                                                @endif
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Title</th>
                                                            <td>{{$issue->title}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Observation</th>
                                                            <td><?php echo nl2br($issue->observation )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Potential risks</th>
                                                            <td>
                                                                @if($issue->related_risks)
                                                                @php
                                                                $relatedRisks = json_decode($issue->related_risks, true);
                                                                @endphp
                                                                @if(is_array($relatedRisks) || is_object($relatedRisks))
                                                                @foreach($relatedRisks as $riskId => $riskItem)
                                                                <span class="badge badge-info">{{ $riskItem }}</span>
                                                                @endforeach
                                                                @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Responsibility</th>
                                                            <td>{{$issue->responsibility}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Agreed action</th>
                                                            <td><?php echo nl2br($issue->agreedaction)?></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Due date</th>
                                                            <td>{{date('M/d/Y',strtotime($issue->duedate))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Correction</th>
                                                            <td><?php echo nl2br($issue->correction )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Root cause</th>
                                                            <td><?php echo nl2br($issue->rootcause )?></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Corrective action</th>
                                                            <td><?php echo nl2br($issue->correctiveaction )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Attachments (issues)</th>
                                                            <td>
                                                                <div class="col-sm-8">
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                                    <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments</h3>
                                                                                </div>
                                                                                @php
                                                                                    $n = 0;                                               
                                                                                    $issuedocs = DB::table('multipleissues')
                                                                                                                ->where('id', $issue->id)
                                                                                                                ->whereNotNull('issue_file')
                                                                                                                ->where('issue_file','!=','')                   
                                                                                                                ->get();
                                                                                @endphp
                                                                                @if(count($issuedocs) > 0 )
                                                                                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                                                    <div class="row justify-content-center">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th>S.No</th>
                                                                                                <th>Attachments</th>
                                                                                                <th></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        
                                                                                        @foreach($issuedocs as $value1)
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                                                                <td style="font-size:15px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </table>
                                                                                @else
                                                                                    <div class="row justify-content-center">
                                                                                        <h4 class="text-danger">No Files</h4>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Attachments (action)</th>
                                                            <td>
                                                                <div class="col-sm-8">
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal6{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                                    <div id="viewModal6{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h3 class="modal-title">Attachments</h3>
                                                                                </div>
                                                                                @php
                                                                                    $sno= 0;
                                                                                    $actiondocs = DB::table('multipleactions')
                                                                                                        ->where('id', $issue->id)
                                                                                                        ->whereNotNull('action_file')
                                                                                                        ->where('action_file', '!=', '')
                                                                                                        ->get();
                                                                                @endphp
                                                                                @if( count($actiondocs) > 0 )
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
                                                                                        @foreach($actiondocs as $value2)
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="font-size:10px; ;">{{ $sno=$sno+1}}
                                                                                                </td>
                                                                                                <td style="font-size:15px;width: 100%;">{{ $value2->action_file}}</td>
                                                                                                <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_files/".$value2->action_file)}}" download="{{$value2->action_file}}">Download</a></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </table>
                                                                                @else
                                                                                    <div class="row justify-content-center">
                                                                                        <h4 class="text-danger">No Files</h4>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Remarks</th>
                                                            <td><?php echo nl2br($issue->remarks)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Review action remarks</th>
                                                            <td><?php echo nl2br($issue->action_remarks)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Posted by & Date</th>
                                                            <td>
                                                                <!-- @foreach($users as $u)
                                                @if( $issue->postedby == $u->employeeid )
                                                    {{ $u->name }}-{{ $u->employeeid }}
                                                @endif
                                                @endforeach -->
                                                                <!-- {{$issue->postedby}}&nbsp; {{ $issue->postedBy ? $issue->postedBy->name : ''}} -->
                                                                {{$issue->postedBy ? $issue->postedBy->name : ''}} ({{ $issue->postedby}}) ,{{date('d-m-Y', strtotime($issue->created_at))}}
                                                            </td>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th>Added date</th>
                                                            <td>{{$issue->created_at}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Last reviewed</th>
                                                            <td>{{$issue->update_at}}</td>
                                                        </tr> -->
                                                        <tr>
                                                            <th>Action Reviewed by & Date</th>
                                                            <td style="font-weight:lighter;">
                                                            @if($issue->reviewedby !=null)
                                                            {{ $issue->reviewedBy ? $issue->reviewedBy->name : '--' }} ({{$issue->reviewedby}}) , 
                                                            {{$issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)) : ''}}
                                                            @else
                                                            --
                                                            @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Hod Reviewed by & Date</th>
                                                            <td style="font-weight:lighter;">
                                                                @if($issue->hod_reviewed_by_date != NULL)
                                                                {{ $issue->hodreviewedBy ? $issue->hodreviewedBy->name : '--' }} 
                                                                ({{ $issue->hodreviewedBy?$issue->hodreviewedBy->employeeid:'' }}) , 
                                                                {{$issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)) : ''}}
                                                                @else
                                                                --
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th> Issue Closed by & Date</th>
                                                            <td style="font-weight:lighter;">
                                                            @if($issue->closedby !=null)
                                                                {{ $issue->closedBy ? $issue->closedBy->name : '-' }} 
                                                                ({{ $issue->closedby}}) ,
                                                                {{ $issue->closed ?date('d-m-Y', strtotime($issue->closed)) : '' }}
                                                            @else
                                                                --
                                                            @endif
                                                            </td>
                                                        </tr>
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

        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script>
            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 240);

        </script>

        <script>
            $(document).ready(function() {
                $("#tabledata").one("preInit.dt", function() {
                    let table = $('#tabledata').DataTable();
                    let alldeptnames = "{{ $alldeptnames }}".split(',');
                    let dept_text = "";
                    for (var i = 0; i < alldeptnames.length; i++) {
                        dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
                    }

                    $deptheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="dept_dropdown">
                    <option value="">Department&nbsp;&nbsp;&#9207;</option>` + dept_text +
                        `
                </select>`);

                    $('#deptselectdata').html($deptheading);
                    $("#dept_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(3).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#dept_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });


                    let alltypenames = "{{ $alltypenames }}".split(',');
                    let type_text = "";
                    for (var i = 0; i < alltypenames.length; i++) {
                        type_text += `<option value="${alltypenames[i]}">${alltypenames[i]}</option>`;
                    }
                    $typeheading = $(`<select class="headingselect" style="width:100px;border:none;" id="type_dropdown">
                            <option value="">Type&nbsp;&nbsp;&#9207;</option>` +
                        type_text +
                        `
                        </select>`);
                    $('#typeselectdata').html($typeheading);
                    $("#type_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(4).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#type_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });

                    let years = "{{ $years }}".split(',');
                    let duedate_text = "";
                    for (var i = 0; i < years.length; i++) {
                        duedate_text += `<option value="${years[i]}">${years[i]}</option>`;
                    }

                    $duedateheading = $(`<select class="headingselect" style="width:100px;" id="year_dropdown">
                    <option value="">Due&nbsp;&nbsp;date&nbsp;&nbsp;&#9207;</option>` +
                        duedate_text +
                        `
                </select>`);
                    $('#duedateselectdata').html($duedateheading);
                    $("#year_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(6).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#year_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });

                    let allpstages = "{{ $allpstages }}".split(',');
                    let stage_text = "";
                    for (var i = 0; i < allpstages.length; i++) {
                        stage_text += `<option value="${allpstages[i]}">${allpstages[i]}</option>`;
                    }

                    $stageheading = $(`<select class="headingselect" style="width:120px;" id="stage_dropdown">
                          <option value="">Process Stage&nbsp;&nbsp;&#9207;</option>` + stage_text +
                        `
                      </select>`);

                    $('#stageselectdata').html($stageheading);
                    $("#stage_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(7).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#stage_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });
                });
            });

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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/folders">
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
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open NCRs
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/actionsystem/analysis">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Payments -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Audit Log
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Payments -->
                                </div>

                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Audit Schedule Analysis
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
        @include('exporting.foractionsystem');

</body>




</html>
@endsection
