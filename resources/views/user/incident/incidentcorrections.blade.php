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
    <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
</ul>
</div>


@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
{{--<style>
.header{
    margin-left:7%;
    margin-bottom:-3.5%;
}
</style>--}}
<style>
@media screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1366px)  {
    body {
        margin:0;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        width:100%;
    }
    .pb-1, .py-1 {
        /*padding-bottom: .25rem!important;*/
        bottom:0;
        position:relative;
        display:inline-block;
        clear: both;
    }
}
  </style>
</head>
    <body>
    
      <input type="hidden" value="a1" id="tabselected">

                <!-- Page Content -->
                 <div class="content"style="width:100%;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        <div class="block-content block-content-full" >

                                       <!--shift header to white background-->
                                       <div style="overflow-x:hidden;oveflow-y:auto;" >
                         <table class="move" width="65%">
                         <table class="move" width="15%" style="margin-left:670px">
                                    <tr>

                                    <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px;">
                                                <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Incident Report</li>
                                                <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="{{url('/user/corrective/corrissues')}}">Corrective Action</a>
                                                </li>
												<li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                    <a class="link-fx" href="{{url('/user/incidents/register')}}">Back to Incident Reports</a>
                                                </li>
                                            </ol>
                                        </nav>
                                    </th>
                                    </tr>
                                    </table>
                      <!--end of shift header to white background-->
                        <div style="overflow-y:auto;overflow-x:auto;" >
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->   <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                    <tr>
                                        
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1" >Id</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Category</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Type</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Due Date</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Process Stage</th>
                                        <th class="text-center" style="font-size:10px;width: 50px;text-transform:capitalize;position:sticky;top:-1">Action Status</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                        <th class="text-center" style="font-size:10px;width: 30px;text-transform:capitalize;position:sticky;top:-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=0;?>
                                   
                                    <?php   $rr=$issue->id;
                                            $date = $issue->duedate;
                                            $today = Date("Y-m-d");
                                            $issuedocs=explode(',',$issue->file);
                                            $actiondocs=explode(',',$issue->action_file);
                                    ?>
                                    <tr>
                                        
                                        <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                            @if (($issue->status == 'open' || $issue->status == 'NULL')&& $today > $date)
                                                <span class="badge badge-danger"data-toggle="popover" data-placement="right"  data-content="Due">{{$issue->issueid}}</span>
                                            @else
                                            <span class="badge badge bg-success-light">{{$issue->issueid}}</span>
                                            @endif
                                        </td>
                                        
                                        <td class="text-center "style="font-size:10px;width: 5%;color:black">{{$issue->category}}</td>
                                        
                                        <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                            <em class="text-muted">{{$issue->department}}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell"style="font-size:12px;">
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
										
                                        <td class=" font-w600 "style="font-size:10px;"> <a href="#">Incident # {{$issue->title}}</a></td>
										           
                                        <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                            <em class="text-muted">{{date('M/d/Y',strtotime($issue->duedate))}}</em>
                                        </td>
                                        <td class="d-none d-sm-table-cell"style="font-size:12px;">
                                            @if($issue->processstage=='Under Review'||$issue->processstage=='Awaiting actions')
                                                <span class="badge badge-secondary">Under Review</span>
                                            @elseif($issue->processstage=='Actions Proposed')
                                                <span class="badge badge bg-flat"style="color:white">{{$issue->processstage}}</span>
                                            @elseif($issue->processstage=='Action Reviewed, OK')
                                                <span class="badge badge-success">Action Approved</span>
                                            @elseif($issue->processstage=='Action Reviewed, Not OK')
                                                <span class="badge badge-danger">Action Rejected</span>
                                            @elseif($issue->processstage=='Need More Action')
                                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                                            @elseif($issue->processstage=='Actions Completed')
                                                <span class="badge badge-info">{{$issue->processstage}}</span>
                                            @elseif($issue->processstage=='Approved')
                                                <span class="badge badge bg-success-light">{{$issue->processstage}}</span>
                                            @elseif($issue->processstage=='rejected')
                                                <span class="badge badge bg-city-light"style="color:white;text-transform:capitalize;">{{$issue->processstage}}</span>
                                            @endif

                                        </td>
                                        <td class="d-none d-sm-table-cell "style="font-size:10px;">
                                            <em class="text-muted">{{$issue->impl_status}}</em>
                                        </td>

                                        <div class="dropdown font-size-sm">
                                            <td class="font-w600 font-size-sm">
                                            <button type="button"class="btn btn-primary dropdown-toggle" id="dropdown-default-primary"  style="font-size:10px;" data-toggle="dropdown">Actions</button>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">

                                                    @foreach($issuereviewers as $rep)
                                                        @if(Auth::user()->email==$rep)
                                                            <a  href="{{url('/user/corrective/editresp?val='.$rr)}}" class="dropdown-item"  style="font-size:10px;">Responsibility change</a>

                                                            @if( ($issue->status=='NULL' && $issue->processstage=='Under Review')||($issue->processstage=='Awaiting actions'))
                                                                    <a  href="{{url('/user/corrective/approve?val='.$rr)}}" class="dropdown-item" style="font-size:10px;" >Review</a>

                                                            @elseif( $issue->processstage=='Actions Proposed' )
                                                                    <a  href="{{url('/user/corrective/reviewstatus?val='.$rr)}}" class="dropdown-item"  style="font-size:10px;">Review Action</a>

                                                            @elseif(  $issue->processstage=='Actions Completed' )
                                                                <a  href="{{url('/user/corrective/closure?val='.$rr)}}" class="dropdown-item"  style="font-size:10px;">Closure Review</a>
                                                            @elseif($issue->processstage=='rejected')
                                                                <a onclick="cll('Are u sure to resubmit?')" href="{{url('/user/corrective/resubmit?val='.$rr)}}" class="dropdown-item" style="font-size:10px;" >Resubmit Issue</a>

                                                            {{-- @elseif($issue->processstage=='Action Reviewed, Ok' && $issue->impl_status=='Completed-100%')
                                                                <a  onclick="cll()" href="{{url('/user/corrective/closed?val='.$rr)}}" class="dropdown-item" style="font-size:10px;">Close Issue</a>  --}}
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                        </div>

                                    <td>
                                        <button type="button" class="btn btn-primary viewDetails" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $issue->id  }}" data-target="#viewModal{{ $issue->id }}"><strong>View</strong> </button>

                                        <div id="viewModal{{ $issue->id }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title" style="padding:1rem;">Detailed View</h2>
                                                            <div class="p-1 text-center rounded-top" style="padding:1rem">
                                                            <img class="img-avatar img-avatar48 img-avatar-thumb" data-toggle="modal" data-target="#modal-block-fadein" alt="" id="responsible{{$issue->id}}">
                                                            <img class="img-avatar img-avatar48 img-avatar-thumb"  data-toggle="modal" data-target="#modal-block-fadein" alt="" id="postedbyUser{{$issue->id}}">

                                                            </div>
                                                    {{-- <h2 class="modal-title">Detailed View</h2> --}}
                                                </div>
                                                <table class="table table-striped">
                                                <tr>
                                                            <th style="width:70px">Issue id</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @if (($issue->status == 'open' || $issue->status == 'NULL')&& $today > $date)
                                                                    <span class="badge badge-danger">{{$issue->issueid}}</span>

                                                                @else
                                                                <span class="badge badge bg-success-light">{{$issue->issueid}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Category</th>
                                                            <td>{{$issue->category}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Department</th>
                                                            <td>{{$issue->department}}</td>
                                                        </tr>

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

                                                        <tr>
                                                            <th>Process stage</th>
                                                            <td class="d-none d-sm-table-cell">
                                                                @if($issue->processstage=='Under Review'||$issue->processstage=='Awaiting actions')
                                                                <span class="badge badge-secondary">Under Review</span>
                                                            @elseif($issue->processstage=='Actions Proposed')
                                                                <span class="badge badge bg-flat"style="color:white">{{$issue->processstage}}</span>
                                                            @elseif($issue->processstage=='Action Reviewed, OK')
                                                                <span class="badge badge-success">Action Approved</span>
                                                            @elseif($issue->processstage=='Action Reviewed, Not OK')
                                                                <span class="badge badge-danger">Action Rejected</span>
                                                            @elseif($issue->processstage=='Need More Action')
                                                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                                                            @elseif($issue->processstage=='Actions Completed')
                                                                <span class="badge badge-info">{{$issue->processstage}}</span>
                                                            @elseif($issue->processstage=='Approved')
                                                                <span class="badge badge bg-success-light">{{$issue->processstage}}</span>
                                                            @elseif($issue->processstage=='rejected')
                                                                <span class="badge badge bg-city-light"style="color:white;text-transform:capitalize;">{{$issue->processstage}}</span>
                                                            @endif
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Title</th>
															@if($issue->category=="Incident Investigation")
                                                            <td>Incident # {{$issue->title}}</td>
														    @else
															<td>{{$issue->title}}</td>
                                                            @endif														
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
                                                            <td ><?php echo nl2br($issue->correction )?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Root cause</th>
                                                            <td ><?php echo nl2br($issue->rootcause )?></td>
                                                        </tr>

                                                        <tr>
                                                            <th>Corrective action</th>
                                                            <td ><?php echo nl2br($issue->correctiveaction )?></td>
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

                                                                                                @if( $value1!="NULL" )
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                                                            <td style="font-size:15px;width: 100%;">{{ $value1}}</td>
                                                                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value1)}}" download="{{$value1}}" >Download</a></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                    @else
                                                                                            <div class="row justify-content-center">
                                                                                                <h2>No Files</h2>
                                                                                            </div>
                                                                                                @endif
                                                                                                {{-- @endif  --}}
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
                                                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal5{{$issue->id}}"><strong>View Attachments</strong> </button>
                                                                    <div id="viewModal5{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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

                                                                                                @if( $value2!="NULL" && $value2!="")
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                                                                            <td style="font-size:15px;width: 100%;">{{ $value2}}</td>
                                                                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success"  href="{{asset("storage/issue_files/".$value2)}}" download="{{$value2}}" >Download</a></td>
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
                                                       <tr>
                                                            <th>Remarks</th>
                                                            <td ><?php echo nl2br($issue->remarks)?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Action review remarks</th>
                                                            <td ><?php echo nl2br($issue->action_remarks)?></td>
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
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



                    {{----------delete request button for doc controllers --}}
             <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

             <script>
                $("#del").click(function () {
                    var dataArr = new Array();
                    if($('input:checkbox:checked').length > 0){
                        $('input:checkbox:checked').each(function(){
                            dataArr.push($(this).attr('id'));
                            // console.log(dataArr);
                            $.ajax({
                                type    :'get',
                                url     :"{{route('deletecorr')}}"+"?val="+$(this).attr('id'),
                                success : function(response){
                                        // alert(response);
                                        window.location.href ="{{url('/user/corrective/corrissues')}}";
                                        //  redirect('/user/doc/masterlist')->withData($data);
                                },
                                error   :function(response){
                                        alert(errResponse);
                                }
                    });
                        });

                    }
                    else{
                            alert('No Record Selected');
                         }
                });
            </script>
            <script>
            $(document).ready(function(){
            $( ".viewDetails" ).on('click',function() {
                var id = $(this).data('id');
                $.ajax({
                url: "/show/" + id + "/" + "corrective",
                type: 'get',
                data: {id: id},
                dataType: "json",
                success: function(response){
                    response.data.responsibleUser ? $('#responsible'+ id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                    response.data.postedByUser ? $('#postedbyUser'+id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                }
            });
            });
            });
            </script>
               {{-----------edit  button ------------}}
           <script>
               $("#edit").click(function () {
                   var dataArr = new Array();
                   var group = new Array();
                   var d;
                   var all;
                   if($('input:checkbox:checked').length > 0){
                       $('input:checkbox:checked').each(function(){
                           dataArr.push($(this).attr('id'));
                       //  console.log(dataArr);

                           all = ""+dataArr;
                           group.push(all); // push all checkbox values into an array
                       //  console.log(group);


                   });
                   $.ajax({
                               type    :'get',
                               url     :"{{route('editcorraction')}}"+"?val="+(all),
                               success : function(response){
                                       // alert(response);

                                       window.location.href ="{{route('editcorraction')}}"+"?val="+(all);
                                                           // console.log(response);

                               },
                               error   :function(response){
                                       alert(errResponse);
                               }
                       });

                   }
                   else{
                           alert('No Record Selected');
                       }
               });
               </script>

            </div>
                <!-- END Page Content -->



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

                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/addcorrective">
                                            <div class="block-content text-center" style="height:130px" >
                                            <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Issue
                                                </p>
                                            </div>
                                        </a>

                                    </div> -->
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/corrective/corrissues">
                                            <div class="block-content text-center" style="height:130px" >
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/closedissue">
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/corrective/openissue">
                                            <div class="block-content text-center" style="height:130px" >
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    My Open Issues
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">

                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/corrective/analysis' >
                                           <div class="block-content text-center" style="height:130px" >
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Analysis
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

@section('sideitem')
@foreach($mr as $mrs)
@if(Auth::user()->email==$mrs)
<div class="input-group input-group-sm ml-2">
<button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual"  id="edit" value=id[] >
<i class="fa fa-1x fa-edit" style="color:grey;"></i>
</button>
</div>

<div class="input-group input-group-sm ml-2">
 <button type="button" class="btn btn-sm btn-dual"  id="del"value=id[]  >
 <i class="fas fa-1x fa-trash" style="color:red;"></i>
 </button>
</div>
@endif
@endforeach
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

            
            <?php $today = date('d-m-Y');
                $postdate =$issue->postdate;
                $date=date('d-m-Y',strtotime($postdate));
                $t = Date("Y-m-d");?>
                @if($issue->department==Auth::user()->deptid)
            @if($issue->status=="open" && $today==$date)
            <li>
                <a class="text-dark media py-2" >
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
            @if($issue->status == 'open' && $t > $issue->duedate)
            <li>
                <a class="text-dark media py-2" >
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
                <a class="text-dark media py-2" >
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

            
        </ul>

    </div>
</div>
@endsection

      </body>
</html>
@endsection
