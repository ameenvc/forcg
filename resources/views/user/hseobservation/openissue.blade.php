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
    <a type="button" href="{{url('/user/hseobservation/addissue')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection

@section('content')
<style>
    body {
        overflow-y: auto;
    }
    .headingselect {
        white-space: break-spaces;
        line-height: 1rem;
    }
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    } 
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
        min-width: 120px !important;
    }
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    @media (max-width: 1800px) {
        #content {
            height: calc(100vh - 220px) !important;
            max-height: 900px !important;
        }
    }
    @media (max-width: 1200px) {
        #content {
            max-height: 600px !important;
        }
    }
    #content{
        height:calc(100vh - 220px); max-height:900px;
    }
</style>
<?php $issues1=$issues=$issues3=[]; ?>
<input type="hidden" value="a3" id="tabselected">
<div class="content" style="width:100%">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">               
                <li class="breadcrumb-item font-w700 text-uppercase" aria-current="page">
                    HSE Observations
                </li>
                <li class="breadcrumb-item">
                    My Open Issues
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="table-responsive scrollable" id="content">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                    <thead>
                        <tr style="position: sticky; top: -2px; z-index: 5;">
                            <td class="text-center bg-white titleheading" style="min-width:50px !important;" data-orderable="false"></td>
                            <td class="text-center bg-white titleheading" style="font-size:10px;font-weight:bold !important;min-width:50px !important;" data-orderable="false">Slno</td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Id</td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Title</td>
                            <td id="categories101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="departments101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="types101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="duedates101" class="titleheading text-center bg-white" data-orderable="false"></td>
                            <td id="processstageshead101" data-orderable="false" class="text-center titleheading bg-white"></td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Action Status</td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Implementation Status</td>
                            <td class="text-center bg-white titleheading" style="font-size:10px;font-weight:bold !important;min-width:fit-content !important;" data-orderable="false">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0;?>
                        @foreach ($issues2 as $issue)
                        <?php $rr=$issue->id;
                            $date = $issue->duedate;
                            $today = Date("Y-m-d");
                            ?>
                        <tr>
                            <td class="text-center"><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td>
                            <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                            <td class="text-center" style="font-size:12px;">
                                @if ($issue->status == 'open' && $today > $date)
                                <span class="badge badge-danger" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                                @else
                                <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                @endif
                            </td>
                            <td class="text-center font-w600 " style="font-size:10px;">
                                <a href="#">{{$issue->title}}</a>
                            </td>
                            <td class="text-center" style="font-size:10px;color:black">
                                {{$issue->categoryName}}
                                <div style="display: none;">{{'e_'.$issue->categoryName.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                {{$issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department}}
                                <div style="display: none;">{{'e_'.$issue->categoryName.'_f'}}{{$issue->departmentHse ? 'e_'.$issue->departmentHse->departmentname.'_f' : 'e_'.$issue->department.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:12px;">
                                @if(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Low")
                                <span class="badge badge-success">{{$hsetypes[$issue->type]}}</span>
                                @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Moderate")
                                <span class="badge badge-warning">{{$hsetypes[$issue->type]}}</span>
                                @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="High")
                                <span class="badge badge-danger">{{$hsetypes[$issue->type]}}</span>
                                @else
                                <span class="badge badge-primary">{{$hsetypes[$issue->type]??'--'}}</span>
                                @endif
                                <div style="display: none;">{{isset($hsetypes[$issue->type]) ? 'e_'.$hsetypes[$issue->type].'_f' :''}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                <em class="text-muted">{{date('d-m-Y',strtotime($issue->duedate))}}</em>
                                <div style="display: none;">{{'e_'.date('Y',strtotime($issue->duedate)).'_f'}}</div>
                            </td>

                            <td class="text-center" style="font-size:12px;">
                                @if($issue->processstage=='Awaiting actions')
                                <span class="badge badge-secondary">{{$issue->processstage}}</span>
                                @elseif($issue->processstage=='Actions Proposed')
                                <span class="badge badge bg-flat" style="color:white">{{$issue->processstage}}</span>
                                @elseif($issue->processstage=='Action Reviewed, OK')
                                <span class="badge badge-success">Action Approved</span>
                                <div style="display: none;">{{'e_Action Approved_f'}}</div>
                                @elseif($issue->processstage=='Action Reviewed, Not OK')
                                <span class="badge badge-danger">Action Rejected</span>
                                <div style="display: none;">{{'e_Action Rejected_f'}}</div>
                                @elseif($issue->processstage=='Need More Action')
                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                                @elseif($issue->processstage=='Actions Completed')
                                <span class="badge badge-info">{{$issue->processstage}}</span>
                                @endif
                                <div style="display: none;">{{'e_'.$issue->processstage.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                @php
                                    $impl_per = 0;
                                    switch ($issue->impl_status) {
                                        case 'Progress-10%':
                                            $impl_per = 10;
                                            break;
                                        case 'Progress-25%':
                                            $impl_per = 25;
                                            break;
                                        case 'Partially Completed-50%': 
                                            $impl_per = 50;
                                            break;
                                        case 'Completed-100%':
                                            $impl_per = 100;
                                            break;
                                    }
                                @endphp
                                <div class="progress mb-1" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$impl_per}}%;" aria-valuenow="{{$impl_per}}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="fs-xs fw-semibold mb-0">{{$impl_per}}%</p>
                            </td>
                            <td class="font-w600 text-center " style="font-size:10px;">
                                    <div class="dropdown ">
                                        @if( $issue->processstage=='Action Reviewed, OK' )
                                        <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width: 70px;height:30px;" data-toggle="dropdown">Update</button>
                                        <div class="dropdown-menu " aria-labelledby="dropdown-dropleft-outline-dark">
                                            <a href="{{ url('/user/hseobservation/hundred?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Completed-100%</a>
                                            <a href="{{ url('/user/hseobservation/five?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Partially Completed-50%</a>
                                            <a href="{{ url('/user/hseobservation/two?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Progress-25%</a>
                                            <a href="{{ url('/user/hseobservation/zero?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Not Started-0%</a>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="font-w600 font-size-sm text-center">
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                        @if($issue->processstage =='Awaiting actions')
                                            <a href="{{url('/user/hseobservation/editopen?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Propose Action</a>
                                        @elseif ($issue->processstage =='Action Reviewed, Not OK' || $issue->processstage =='Action Reviewed, OK' || $issue->processstage == 'Need More Action' || $issue->processstage == 'Actions Proposed')
                                            <a class="dropdown-item" style="font-size:10px;" href="{{url('/user/hseobservation/editrespondedissue?val='.$rr)}}">Edit</a>
                                        @endif
                                    </div>
                                    <a href="#" class="btn btn-sm btn-alt-primary viewDetails" data-id="{{ $issue->id }}"  data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{ $issue->id}}">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                    </a>
                                </div>
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
                                            <table class="table table-striped text-left">
                                                <tr>
                                                    <th style="width:70px">Issue id</th>
                                                    <td >
                                                        @if ($issue->status == 'open' && $today > $date)
                                                        <span class="badge badge-danger">{{$issue->issueid}}</span>

                                                        @else
                                                        <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Category</th>
                                                    <td>{{$issue->category}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Department</th>
                                                    <td>{{$issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department}}</td>
                                                </tr>

                                                <tr>
                                                    <th>Type</th>
                                                    <td>
                                                        @if(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Low")
                                                        <span class="badge badge-success">{{$hsetypes[$issue->type]}}</span>
                                                        @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Moderate")
                                                        <span class="badge badge-warning">{{$hsetypes[$issue->type]}}</span>
                                                        @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="High")
                                                        <span class="badge badge-danger">{{$hsetypes[$issue->type]}}</span>
                                                        @else
                                                        <span class="badge badge-primary">{{$hsetypes[$issue->type]??'--'}}</span>
                                                        @endif
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <th>Process stage</th>
                                                    <td>
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

                                                                                @if( count($issuedocs) > 0 )
                                                                                @foreach($issuedocs as $value1)
                                                                                @if( $value1->id == $issue->id )
                                                                                @if( $value1->issue_file!="NULL" )
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                                                        <td style="font-size:15px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                                @endif
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

                                                                                @if( count($actiondocs) > 0 )
                                                                                @foreach($actiondocs as $value2)
                                                                                @if( $value2->id == $issue->id )
                                                                                @if( $value2->action_file!="NULL" )
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                                                        <td style="font-size:15px;width: 100%;">{{ $value2->action_file}}</td>
                                                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value2->action_file)}}" download="{{$value2->action_file}}">Download</a></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                                @endif
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
                                                    <th>Remarks</th>
                                                    <td><?php echo nl2br($issue->remarks)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Action review remarks</th>
                                                    <td><?php echo nl2br($issue->action_remarks)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Posted by</th>
                                                    <td>{{$issue->postedby}}&nbsp; {{ $issue->postedBy ? $issue->postedBy->name : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Added date</th>
                                                    <td>{{$issue->postdate}}</td>
                                                    <!-- <td>{{date('M/d/Y',strtotime($issue->postdate))}}</td>                                                            -->
                                                </tr>
                                                <tr>
                                                    <th>Last reviewed</th>
                                                    <td>{{$issue->last_reviewed_at}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Approved by</th>
                                                    <td>{{$issue->approvedby}}&nbsp; {{ $issue->approvedByHse ? $issue->approvedByHse->name : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Issue closed by</th>
                                                    <td>{{$issue->closedby}}&nbsp; {{ $issue->closedByHse ? $issue->closedByHse->name : ''}}</td>
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
                        <!-- <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body" href="/user/actionsystem/addissue">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Add Issue
                                        </p>
                                    </div>
                                </a>

                            </div> -->
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/hseobservation/issues">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/closedissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/openissue">
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
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/analysis">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                        </div>
                        <!-- <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/analysis" style="margin-top:6px">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>

                            </div>   -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- </div>-->



</div> 
<!-- END Apps Modal -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/datatable_extension_for_complaint.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".viewDetails").on('click', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "/show/" + id + "/" + "hseissues"
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
    autoResizeDiv(window.innerHeight - 100);

</script>
<script>
    $(document).ready(function () {
        function initializeDropdowns(tableId, dropdownConfigs) {
            $(tableId).one("preInit.dt", function () {
                let table = $(tableId).DataTable();
                let previousSelectedValue = null;
                dropdownConfigs.forEach(config => {
                    let { dropdownId, columnHeaderId, optionsArray, placeholder,isDateFilter} = config;
                    let columnIndex = table.column(columnHeaderId).index();
                    let optionsHTML = optionsArray.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                    let dropdownHTML = '';
                    if (isDateFilter) {
                        const tree = createTree(optionsArray);
                        dropdownHTML = buildTreeHtml(tree, placeholder.replace(/ /g, '-'), placeholder);
                    } else {
                        const minWidth = placeholder === 'Complaint Categories' ? '234px' : 'fit-content';
                        dropdownHTML = `
                            <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:${minWidth};background-color:#fff;font-weight:600 !important;" id="${dropdownId}">
                                <option value="">${placeholder}&nbsp;&nbsp;&#9207;</option>
                                ${optionsHTML}
                            </select>
                        `;
                    }
                    $(columnHeaderId).html(dropdownHTML);
                    if (isDateFilter) {
                        const dateFilterElement = $('.' + placeholder.replace(/ /g, '-'));
                        dateFilterElement.find('.filter-header').click(function() {
                            $(this).next('.filter-content').slideToggle();
                        });
                        dateFilterElement.find('.expand').click(function(e) {
                            e.stopPropagation();
                            const dateFilter = $(this).closest('.date-filter')
                            if (dateFilter.next('.nested').length) {
                                dateFilter.next('.nested').slideToggle();
                                $(this).text($(this).text() === '-' ? '+' : '-');
                            }
                        });
                        dateFilterElement.find(".cke-filter").change(function() {
                            const eleName = $(this).attr('data-name')
                            const value = $(this).val();
                            let searchDataList = []
                            if(this.checked) {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', true);
                                }
                                else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', true);
                                }
                            } else {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', false);
                                } else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', false);
                                }
                            }

                            const yearHasBeenSelected = $(this).attr('data-year');
                            const monthHasBeenSelected = $(this).attr('data-month');

                            let isSelectAllMonth = true;
                            dateFilterElement.find(".cke-filter[data-month=" + monthHasBeenSelected + "]").each(function (index, item) {
                                if(!$(item).is(":checked")) {
                                    isSelectAllMonth = false;
                                    return false;
                                }
                            })
                            if (isSelectAllMonth) {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', false);
                            }
                            let isSelectAllYear = false;
                            dateFilterElement.find(".cke-filter[data-year=" + yearHasBeenSelected + "]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    isSelectAllYear = true;
                                    return false;
                                }
                            })
                            if (isSelectAllYear) {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', false);
                            }

                            dateFilterElement.find(".cke-filter[data-name=month]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    searchDataList.push($(item).val())
                                }
                            })
                            const searchKeyWord = searchDataList.join('|');
                            console.log(searchKeyWord);
                            table.column(columnIndex).search(searchKeyWord,true,false).draw()
                            $(this).closest('.filter-content').slideToggle();
                        });
                    }else{
                        $(`#${dropdownId}`).on('change', function () {
                            let selectedValue = $(this).val();
                            let $select = $(this);

                            let firstOption = $select.find("option:first");

                            if (selectedValue) {
                                previousSelectedValue = selectedValue;
                                table.column(columnIndex).search('e_' + selectedValue + '_f').draw();
                                $(this).addClass('text-primary');
                                $("#" + dropdownId + " option").css('color', 'black')
                            } else {
                                table.column(columnIndex).search('').draw();
                                $(this).removeClass('text-primary').val('');
                                if (previousSelectedValue) {
                                    const $prevOption = $select.find(`option[value="${previousSelectedValue}"]`);
                                    if ($prevOption.length) {
                                        $prevOption.text(previousSelectedValue);
                                    }
                                }
                                firstOption.html(`${placeholder}&nbsp;&nbsp;&#9207;`);
                            }
                        });
                    }
                });
            });
        }

        // Prepare dropdown data from Blade
        let processStages = "{{ $allpstages }}".split(',');
        let types101 = "{{ $alltypenames }}".split(',');
        let departments101 = "{{ $alldeptnames }}".split(',');
        let dueDates101 = "{{ $years }}".split(',');
        let categories101 = "{{ $allcategoriesname }}".split(',');
        // Initialize dropdowns for table #tabledata_1
        initializeDropdowns('#tabledata', [
            {
                dropdownId: 'processstages101_dropdown',
                columnHeaderId: '#processstageshead101',
                optionsArray: processStages,
                placeholder: 'Process Stage',
                isDateFilter:false
            },
            {
                dropdownId: 'types101_dropdown',
                columnHeaderId: '#types101',
                optionsArray: types101,
                placeholder: 'Type',
                isDateFilter:false
            },
            {
                dropdownId: 'departments101_dropdown',
                columnHeaderId: '#departments101',
                optionsArray: departments101,
                placeholder: 'Department',
                isDateFilter:false
            },
            {
                dropdownId: 'categories101_dropdown',
                columnHeaderId: '#categories101',
                optionsArray: categories101,
                placeholder: 'Category',
                isDateFilter:false
            },
            {
                dropdownId: 'duedates101_dropdown',
                columnHeaderId: '#duedates101',
                optionsArray: dueDates101,
                placeholder: 'Due Date',
                isDateFilter:false
            }
        ]);
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reset all dropdowns
                $(".headingselect").each(function () {
                    $(this).val(""); // reset dropdown
                    $(this).removeClass('text-primary'); // remove styling
                    const placeholder = $(this).find("option:first").text().split(' ')[0]; // e.g., 'Department'
                    $(this).find("option:first").text(`${placeholder}`);
                });
            }
        });
    });
</script>
@include('exporting.foractionsystem')
@endsection
