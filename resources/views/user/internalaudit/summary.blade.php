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
            <input type="hidden"  id="tvalue1" name="tvalue1"/>
        </form>
    </li>
    <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
    <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
</ul>
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
    
    <body>
    <?php 
    $issues2=$issues1=$issues3=[];
    ?>
      <input type="hidden" value="a1" id="tabselected">  
   
      <div class="content"style="width:100%">
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                 <div class="scrollable" style="height:450px;" >

                 <table class="move" width="15%" style="margin-left:670px">
                                    <tr>
                                   
                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px;">
                                                <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Audit Management</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="/user/action/issues" >Internal Audit Summary[{{$department}}-{{$year}}]</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                                    </table>
               
                    <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                {{-- <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1"></th> --}}
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1" >Slno</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1" >Id</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Category</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Type</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Due Date</th>
                                <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Action Status</th>
                                {{-- <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Actions</th> --}}
                                <th class="text-center" style="font-size:10px;width: 20px;text-transform:capitalize;position:sticky;top:-1;">View</th>
                            </tr> 
                        </thead>
                        <tbody>
                            <?php $no=0;?>
                            @foreach($issues as $issue)
                            <?php   $rr=$issue->id;
                                    $date = $issue->duedate;
                                    $today = Date("Y-m-d");
                            ?>                             
                            <tr>
                            {{-- <td><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]" ></td> --}}
                            <td class="text-center "style="font-size:10px;">{{ $no=$no+1}}</td>                                     
                            @if($issue->category=="Internal Audit")
                                <td class="text-center "style="font-size:10px;width: 5%;color:#0033cc">{{$issue->issueid}}</td>
                            @elseif($issue->category=="External Audit")
                                <td class="text-center "style="font-size:10px;width: 5%;color:orange">{{$issue->issueid}}</td>
                            @elseif($issue->category=="Management Review")
                                <td class="text-center "style="font-size:10px;width: 5%;color:#33cc33">{{$issue->issueid}}</td>
                            @elseif($issue->category=="Corrective Action")
                                <td class="text-center "style="font-size:10px;width: 5%;color:#ff0066">{{$issue->issueid}}</td>
                            @else
                                <td class="text-center "style="font-size:10px;width: 5%;color:black">{{$issue->issueid}}</td>
                            @endif
                            <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                <em class="text-muted">{{$issue->category}}</em>
                            </td>
                            <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                <em class="text-muted">{{$issue->department}}</em>
                            </td>
                            <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                @if($issue->type=="Low")
                                    <span class="badge badge-success">{{$issue->type}}</span>
                                @elseif($issue->type=="Moderate")
                                    <span class="badge badge-warning">{{$issue->type}}</span>
                                @elseif($issue->type=="High")
                                    <span class="badge badge-danger">{{$issue->type}}</span>
                                @else
                                    <span class="badge badge-primary">{{$issue->type}}</span>
                                @endif
                            </td>                                       
                            <td class=" font-w600 "style="font-size:10px;">
                                <a href="#">{{$issue->title}}</a>
                            </td>    
                            
                            <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                <em class="text-muted">{{date('M/d/Y',strtotime($issue->duedate))}}</em>
                            </td>
                            <td class="d-none d-sm-table-cell"style="font-size:12px;">
                            @if ($issue->status =='open'  && $today <= $date)
                                <span class="badge badge-primary">{{$issue->processstage}}</span>
                            @elseif ($issue->status == 'open' && $today > $date)
                                <span class="badge badge-danger">{{$issue->processstage}}</span>
                            @elseif($issue->status == 'closed' && $issue->closed <= $date)
                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                            @elseif($issue->status == 'closed' && $issue->closed > $date)
                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                            @endif
                            </td>
                            <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                <em class="text-muted">{{$issue->impl_status}}</em>
                            </td>                            
                            {{-- <div class="dropdown font-size-sm">
                                <td class="font-w600 font-size-sm">
                                    <button type="button"class="btn btn-primary dropdown-toggle" id="dropdown-default-primary"  style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                        @foreach($auditors as $rep)
                                            @if(Auth::user()->email==$rep)
                                               <a  href="{{url('/user/actionsystem/editresp?val='.$rr)}}" class="dropdown-item"  style="font-size:10px;">Responsibility change</a>
                                                    
                                                @if( $issue->processstage=="Actions Proposed")
                                                    <a href="{{url('/user/actionsystem/reviewstatus?val='.$rr)}}" class="dropdown-item"style="font-size:10px;">Review Action</a>		
                                                @elseif($issue->processstage=="Actions Completed" OR $issue->impl_status=="Completed-100%")
                                                    <a  href="{{url('/user/actionsystem/closure?val='.$rr)}}" class="dropdown-item" >Closure Review</a>
                                                @endif
                                            @endif
                                        @endforeach
                                        @foreach($mr as $mrep)
                                            @if(Auth::user()->email==$mrep && $issue->processstage=="Accomplished")
                                                <a  href="{{url('/user/actionsystem/reopen?val='.$rr)}}" class="dropdown-item" >Reopen</a>
                                            @endif
                                        @endforeach                                    
                                    </div>
                                </td>
                            </div> --}}
                            
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
                                                    <td>{{$issue->issueid}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Category</th>
                                                    <td style="text-transform:capitalize">{{$issue->category}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Department</th>
                                                    <td style="text-transform:capitalize">{{$issue->department}}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Type</th>
                                                    <td class="d-none d-sm-table-cell">
                                                        @if($issue->type=="Low")
                                                            <span class="badge badge-success">{{$issue->type}}</span>
                                                        @elseif($issue->type=="Moderate")
                                                            <span class="badge badge-warning">{{$issue->type}}</span>
                                                        @elseif($issue->type=="High")
                                                            <span class="badge badge-danger">{{$issue->type}}</span>
                                                        @else
                                                            <span class="badge badge-primary">{{$issue->type}}</span>
                                                        @endif
                                                    </td>     
                                                </tr>
                                                
                                                <tr>
                                                    <th>Status</th>
                                                    <td class="d-none d-sm-table-cell">
                                                        @if ($issue->status =='open'  && $today <= $date)
                                                            <span class="badge badge-primary">{{$issue->processstage}}</span>
                                                        @elseif ($issue->status == 'open' && $today > $date)
                                                            <span class="badge badge-danger">{{$issue->processstage}}</span>
                                                        @elseif($issue->status == 'closed' && $issue->closed <= $date)
                                                            <span class="badge badge-warning">{{$issue->processstage}}</span>
                                                        @elseif($issue->status == 'closed' && $issue->closed > $date)
                                                            <span class="badge badge-warning">{{$issue->processstage}}</span>
                                                        @endif
                                                    </td>                                                        
                                                    
                                                </tr>
                                                <tr>
                                                    <th>Title</th>
                                                    <td style="text-transform:capitalize">{{$issue->title}}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Observation</th>
                                                        <td style="text-transform:capitalize"><?php echo nl2br($issue->observation )?></td>
                                                </tr>
                                                <tr>
                                                    <th>Responsibility</th>
                                                    <td style="text-transform:capitalize">{{$issue->responsibility}}</td>
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
                                                    <td style="text-transform:capitalize"><?php echo nl2br($issue->correction )?></td>
                                                </tr>
                                                <tr>
                                                    <th>Root cause</th>
                                                    <td style="text-transform:capitalize"><?php echo nl2br($issue->rootcause )?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Corrective action</th>
                                                    <td style="text-transform:capitalize"><?php echo nl2br($issue->correctiveaction )?></td>
                                                </tr>
                                                <tr>
                                                    <th>Attachments (issues)</th>
                                                    <td >
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
                                                                                <?php $sno=0; ?>

                                                                                @if( count($issuedocs) > 0 )
                                                                                    @foreach($issuedocs as $value1)
                                                                                        @if( $value1->id == $issue->id )  
                                                                                        @if( $value1->issue_file!="NULL" )                                                                                                               
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                                                    <td style="font-size:15px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value1->issue_file)}}" download="{{$value1->issue_file}}" >Download</a></td>
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
                                                    <td >
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
                                                                                <?php $n=0; ?>

                                                                                @if( count($actiondocs) > 0 )
                                                                                    @foreach($actiondocs as $value2)
                                                                                        @if( $value2->id == $issue->id ) 
                                                                                        @if( $value2->action_file!="NULL" )                                                                                                               
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                                                                    <td style="font-size:15px;width: 100%;">{{ $value2->action_file}}</td>
                                                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value2->action_file)}}" download="{{$value2->action_file}}" >Download</a></td>
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
                                                    <td style="text-transform:capitalize"><?php echo nl2br($issue->remarks)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Action review remarks</th>
                                                    <td style="text-transform:capitalize"><?php echo nl2br($issue->action_remarks)?></td>
                                                </tr>
                                                <tr>
                                                    <th>Posted by</th>
                                                    <td>{{$issue->postedby}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Added date</th>
                                                    <td>{{$issue->postdate}}</td>
                                                    <!-- <td>{{date('M/d/Y',strtotime($issue->postdate))}}</td>                                                            -->
                                                </tr>
                                                <tr>
                                                    <th>Last reviewed</th>
                                                    <td >{{$issue->updated_at}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Approved by</th>
                                                    <td >{{$issue->approvedby}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Issue closed by</th>
                                                    <td >{{$issue->closedby}}</td>
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
                                                    My Open Issues
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
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Audit Schedule
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Sales -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-address-card fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                   <br> Auditors List
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                            <div class="block-content text-center" style="height:130px">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Audits
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
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


    
    </div>
    <!-- END Apps Modal -->     
      
   


@include('exporting.foractionsystem')
    </body>
</html>
@endsection