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
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>
<!--
<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/waste/addwastemaster')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>-->
@endsection
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        body {
            overflow-y: hidden;

        }

    </style>
    {{--<style>
.header{
    margin-left:6%;
    margin-bottom:-2.9%;
}
</style>--}}
</head>
<body>

    <input type="hidden" value="a1" id="tabselected">
    <!-- Page Content -->

    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded" style="margin-left:-1%;">
            {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Waste Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="/user/wastemgmt">Transfer Request</a>
                                        </li>

                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        @if(Session::has('msg'))
                        <div class="alert alert-danger">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif

                        @if(Session::has('updtmsg'))
                        <div class="alert alert-success">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('updtmsg')!!}
                        </div>
                        @endif

                        @if(Session::has('revmsg'))
                        <div class="alert alert-warning">
                            <a class="close" data-dismiss="alert">×</a>
                            {!!Session::get('revmsg')!!}
                        </div>
                        @endif
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th style="width: 5%;position:sticky;top:-1"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Transfer Request</th>
                                    <th class="text-center" style="font-size:10px;width:10%;text-transform:capitalize;position:sticky;top:-1">Request date</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Department</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1;width:20%;">Created By</th>
                                    <th class="text-center" style="font-size:10px;width:20%;text-transform:capitalize;position:sticky;top:-1">Item name</th>
                                    <th class="text-center" style="font-size:10px;width:20%;text-transform:capitalize;position:sticky;top:-1">Item code</th>
                                    <th class="text-center" style="font-size:10px;width:10%;text-transform:capitalize;position:sticky;top:-1">Type</th>
                                    <th class="text-center" style="font-size:10px;width:5%;text-transform:capitalize;position:sticky;top:-1">Quantity</th>
                                    <!--<th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">HOD Approval Status</th>-->
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Process stage</th>
                                    <!--<th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Disposal / sales Request</th>-->
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">Actions</th>
                                    <th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=0;?>

                                @foreach($data as $value)
                                <?php
                                    $reqnum=$value->request_no;

                                    $revir = $value->reviewer;
                                    $rvd = explode(",",$revir);

                                    $rvdby = $value->reviewed_by;
                                    $revby = explode(",",$rvdby);

                                    $year = $value->year;
                                    $dt = $value->date;
                                    $cdate = date('Y-m-d',strtotime($dt));
                                    $rdat = explode('-',$cdate);
                                    $reqdateval = $dt == null ? null : $rdat[2]."-".$rdat[1]."-".$rdat[0];
                                ?>
                                <tr>
                                    <input type="hidden" value="{{ $year}}" name="year" id="year"/>
                                    <td><input class="text-center font-size-sm" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                    <td class="text-center " style="font-size:10px;height:30px;">{{$no=$no+1}}</td>
                                    <td class="text-center " style="font-size:14px;"><span class="badge badge-success">{{ $value->request_no }}</span></td>
                                    <td class="text-center " style="font-size:10px;">{{ $reqdateval }}</td>
                                    <td class="text-center " style="font-size:10px;">
                                        @if(is_numeric($value->department))
                                        @foreach($dept as $dep)
                                        @if($value->department==$dep->id)
                                        {{$dep->departmentname}}
                                        @break
                                        @endif
                                        @endforeach
                                        @else
                                        {{ $value->department }}
                                        @endif
                                    </td>
                                    <td class="text-center " style="font-size:10px;">
                                        @if($value->responsibility==NULL)
                                        &nbsp;&nbsp;
                                        @else
                                        {{ $value->responsibility }}
                                        @endif
                                    </td>
                                    <td class="text-center " style="font-size:10px;">{{ $value->itemname }}</td>
                                    <td class="text-center " style="font-size:10px;">{{ $value->itemcode }}</td>
                                    <td class="text-center " style="font-size:10px;">{{ $value->type }}</td>
                                    <td class="text-center " style="font-size:10px;">{{ $value->quantity }}</td>

                                    <td class="text-center " style="font-size:13px;">
                                        @if($value->process_stage=="Created (Awaiting HOD Approval)")
                                        <span class="badge badge-info">{{$value->process_stage}}</span>
                                        @elseif($value->process_stage=="HOD Approved")
                                        <span class="badge badge-success">{{$value->process_stage}}</span>
                                        @elseif($value->process_stage=="HOD Rejected")
                                        <span class="badge badge-danger">{{$value->process_stage}}</span>
                                        @elseif($value->process_stage=="Disposal Requested")
                                        <span class="badge badge-primary">{{$value->process_stage}}</span>
                                        @elseif($value->process_stage=="Sales Requested")
                                        <span class="badge badge-warning">{{$value->process_stage}}</span>
                                        @elseif($value->process_stage=="Reviewed")
                                        <span class="badge badge-warning">
                                            @if($value->transfer_type=="disposal")
                                            Disposal Request Reviewed({{$value->reviewed_count}}/{{$value->count}})
                                            @else
                                            Sales Request Reviewed({{$value->reviewed_count}}/{{$value->count}})
                                            @endif
                                        </span>
                                        @elseif($value->process_stage=="Approved")
                                        <span class="badge badge-success">
                                            @if($value->transfer_type=="disposal")
                                            Disposal Request {{$value->process_stage}}
                                            @elseif($value->transfer_type=="sales")
                                            Sales Request {{$value->process_stage}}
                                            @endif
                                        </span>
                                        @elseif($value->process_stage=="Rejected")
                                        <span class="badge badge-danger">
                                            @if($value->transfer_type=="disposal")
                                            Disposal Request {{$value->process_stage}}
                                            @elseif($value->transfer_type=="sales")
                                            Sales Request {{$value->process_stage}}
                                            @endif
                                        </span>
                                        @elseif($value->process_stage=="Physically Disposed")
                                        <span class="badge badge-info">{{$value->process_stage}}</span>
                                        @endif
                                    </td>

                                    <div class="dropdown font-size-sm">
                                        <td class="font-w600 font-size-sm">

                                            <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width:80px;height:35px;" data-toggle="dropdown">Actions</button>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                                @foreach($dept as $dep)
                                                @if(($value->department==$dep->id) && ($dep->hod==Auth::user()->email) && ($value->hod_approval==NULL))
                                                <a href="{{ url('/user/waste/hodapprove?val='.$reqnum.'&dept='.$value->department.'&yr='.$year)}}" class="dropdown-item" style="font-size:10px;">HOD Approval</a>
                                                @break;
                                                @endif
                                                @endforeach

                                                @foreach($trfauth as $trf)
                                                @if((Auth::user()->email==$trf->email) && ($value->transfer_type==NULL) && ($value->hod_approval=="Approved"))
                                                <a href="{{ url('/user/waste/disposalsalesrequest?val='.$reqnum.'&yr='.$value->year)}}" class="dropdown-item" style="font-size:10px;">Disposal/Sales Request</a>
                                                @endif
                                                @endforeach

                                                @if(($value->hod_approval=="Rejected") && ($value->posted_by==Auth::user()->email))
                                                <a href="{{ url('/user/waste/hodeditrequest?val='.$reqnum.'&yr='.$value->year)}}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                                @endif

                                                @if(($value->hod_approval=="Rejected") && ($value->posted_by==Auth::user()->email))
                                                <a href="{{ url('/user/waste/hoddeleterequest?val='.$reqnum.'&yr='.$value->year)}}" class="dropdown-item" style="font-size:10px;" onclick="return delconfirm()">Delete</a>
                                                @endif

                                                @foreach($rvd as $rr)
                                                @if((Auth::user()->email==$rr) && ($value->process_stage!="Approved") && ($value->process_stage!="Rejected") && ($value->transfer_type!=NULL) && ($value->hod_approval=="Approved"))
                                                @if(in_array(Auth::user()->email, $revby))
                                                @break;
                                                @endif
                                                <a href="{{ url('/user/waste/review?val='.$reqnum.'&rev='.$rr.'&yr='.$year)}}" class="dropdown-item" style="font-size:10px;">Review</a>
                                                @break;
                                                @endif
                                                @endforeach

                                                @if(($value->approver==Auth::user()->email) &&($value->process_stage=="Reviewed") && ($value->reviewed_count == $value->count) && ($value->transfer_type!=NULL))
                                                <a href="{{ url('/user/waste/approve?val='.$reqnum.'&appr='.$value->approver.'&yr='.$year)}}" class="dropdown-item" style="font-size:10px;">Approve</a>
                                                @endif

                                                @if(($value->process_stage=="Rejected") && ($value->posted_by==Auth::user()->email) && ($value->transfer_type!=NULL))
                                                <a href="{{ url('/user/waste/editrequest?val='.$reqnum.'&yr='.$value->year)}}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                                @endif

                                                @if(($value->process_stage=="Rejected") && ($value->posted_by==Auth::user()->email) && ($value->transfer_type!=NULL))
                                                <a href="{{ url('/user/waste/deleterequest?val='.$reqnum.'&yr='.$value->year)}}" class="dropdown-item" style="font-size:10px;" onclick="return delconfirm()">Delete</a>
                                                @endif

                                                @foreach($trfauth as $trf)
                                                @if((Auth::user()->email==$trf->email) && ($value->transfer_type!=NULL) && ($value->process_stage=="Approved"))
                                                <a href="#" class="button" data-toggle="modal" data-id="{{  $value->id}}" data-target="#exampleModal{{$value->id}}" style="font-size:10px;color:#6c757d;margin-left:7px;">Update Record</a>
                                                @break;
                                                @endif
                                                @endforeach


                                            </div>

                                        </td>
                                    </div>

                                    <div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Disposal / Sales Request Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($value->transfer_type=="disposal")
                                                    <div class="form-group">
                                                        <label for="dispagency" class="col-form-label" style="font-size:12px;">Disposal Agency:</label>
                                                        <input type="text" class="form-control" style="font-size:12px;" name="agency" id="agency" value="{{$value->disposal_agency}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dispdate" class="col-form-label" style="font-size:12px;">Disposal Date:</label>
                                                        <input type="text" class="form-control" style="font-size:12px;" name="ddate" id="ddate" value="{{date('d-m-Y',strtotime($value->disposal_date))}}" readonly>
                                                    </div>
                                                    @elseif($value->transfer_type=="sales")
                                                    <div class="form-group">
                                                        <label for="soldto" class="col-form-label" style="font-size:12px;">Sold to:</label>
                                                        <input type="text" class="form-control" style="font-size:12px;" name="sold_to" id="sold_to" value="{{$value->sold_to}}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="datecollect" class="col-form-label" style="font-size:12px;">Date of Collection:</label>
                                                        <input type="text" class="form-control" style="font-size:12px;" name="sold_date" id="sold_date" value="{{date('d-m-Y',strtotime($value->sold_date))}}" readonly>
                                                    </div>
                                                    @endif
                                                </div>
                                                <!--<input type="text" name="wasteid" id="wasteid" value="{{$value->id}}">-->

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                                                    <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveChanges('{{$value->id}}')">Dispose</button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>




                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $value->id}}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>

                                        <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Request Number</th>
                                                            <td><span class="badge badge-success" style="font-size:14px;">{{ $value->request_no }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Request Date</th>
                                                            <td style="text-transform:capitalize">{{ $reqdateval}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Department</th>
                                                            <td style="text-transform:capitalize">
                                                                @if(is_numeric($value->department))
                                                                @foreach($dept as $dep)
                                                                @if($value->department==$dep->id)
                                                                {{$dep->departmentname}}
                                                                @break
                                                                @endif
                                                                @endforeach
                                                                @else
                                                                {{ $value->department }}
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Itemname</th>
                                                            <td style="text-transform:capitalize">{{ $value->itemname}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Itemcode</th>
                                                            <td style="text-transform:capitalize">{{ $value->itemcode}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Type of Scrap</th>
                                                            <td style="text-transform:capitalize">{{$value->type}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Quantity</th>
                                                            <td style="text-transform:capitalize">{{$value->quantity}} {{$value->unit}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Useable Parts</th>
                                                            <td style="text-transform:capitalize">{{$value->useableparts}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Process Stage</th>
                                                            <td style="text-transform:capitalize">
                                                                @if($value->process_stage=="Created")
                                                                <span class="badge badge-info">{{$value->process_stage}}</span>
                                                                @elseif($value->process_stage=="HOD Approved")
                                                                <span class="badge badge-success">{{$value->process_stage}}</span>
                                                                @elseif($value->process_stage=="HOD Rejected")
                                                                <span class="badge badge-danger">{{$value->process_stage}}</span>
                                                                @elseif($value->process_stage=="Disposal Requested")
                                                                <span class="badge badge-primary">{{$value->process_stage}}</span>
                                                                @elseif($value->process_stage=="Sales Requested")
                                                                <span class="badge badge-warning">{{$value->process_stage}}</span>
                                                                @elseif($value->process_stage=="Reviewed")
                                                                <span class="badge badge-warning">
                                                                    @if($value->transfer_type=="disposal")
                                                                    Disposal Request Reviewed({{$value->reviewed_count}}/{{$value->count}})
                                                                    @else
                                                                    Sales Request Reviewed({{$value->reviewed_count}}/{{$value->count}})
                                                                    @endif
                                                                </span>
                                                                @elseif($value->process_stage=="Approved")
                                                                <span class="badge badge-success">
                                                                    @if($value->transfer_type=="disposal")
                                                                    Disposal Request {{$value->process_stage}}
                                                                    @elseif($value->transfer_type=="sales")
                                                                    Sales Request {{$value->process_stage}}
                                                                    @endif
                                                                </span>
                                                                @elseif($value->process_stage=="Rejected")
                                                                <span class="badge badge-danger">
                                                                    @if($value->transfer_type=="disposal")
                                                                    Disposal Request {{$value->process_stage}}
                                                                    @elseif($value->transfer_type=="sales")
                                                                    Sales Request {{$value->process_stage}}
                                                                    @endif
                                                                </span>
                                                                @elseif($value->process_stage=="Physically Disposed")
                                                                <span class="badge badge-info">{{$value->process_stage}}</span>
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        @if($value->transfer_type=="disposal")
                                                        <tr>
                                                            <th>Disposal Agency</th>
                                                            <td style="text-transform:capitalize">{{ $value->disposal_agency }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Disposal date</th>
                                                            <td style="text-transform:capitalize">{{date('d-m-Y',strtotime($value->disposal_date))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Disposal Requested by</th>
                                                            <td>
                                                                @foreach($users as $u)
                                                                @if($value->disp_sales_requested_by==$u->email)
                                                                {{ $u->name }}
                                                                @break;
                                                                @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                        @elseif($value->transfer_type=="sales")
                                                        <tr>
                                                            <th>Sold to</th>
                                                            <td style="text-transform:capitalize">{{ $value->sold_to }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Date of Collection</th>
                                                            <td style="text-transform:capitalize">{{date('d-m-Y',strtotime($value->sold_date))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Sales Requested by</th>
                                                            <td>
                                                                @foreach($users as $u)
                                                                @if($value->disp_sales_requested_by==$u->email)
                                                                {{ $u->name }}
                                                                @break;
                                                                @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Posted by</th>
                                                            <td style="text-transform:capitalize">{{ $value->posted_name }}</td>
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            let del_url = "{{route('deletetransferrequest', ':id')}}";

            $("#del").click(function() {
                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Waste Details will be deleted permanently,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {} else {
                        return false;
                    }
                } else {
                    return false;
                }
                var dataArr = new Array();
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        del_url = del_url.replace(':id', $(this).attr('id'));
                        $.ajax({
                            type: 'get'
                            , url: del_url
                            , success: function(response) {
                                window.location.reload();
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
         <script>
            $("#edit").click(function() {
                var dataArr = new Array();
                var group = new Array();
                var d;
                var all;
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        all = "" + dataArr;
                        group.push(all); // push all checkbox values into an array
                    });
                    let edit_url = "{{route('edittransferrequest',':id')}}";
                    edit_url = edit_url.replace(":id", all);
                    $.ajax({
                        type: 'get',
                        data:{
                            'year' : $('#year').val()
                        }
                        , url: edit_url
                        , success: function(response) {
                            window.location.href = edit_url;
                        }
                        , error: function(response) {
                            alert(errResponse);
                        }
                    });

                } else {
                    alert('No Record Selected');
                }
            });

        </script>
        <script>
            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 240);

        </script>

        {{-----------dispose button for disposal/sales request------------}}

        <script>
            let url = "{{ route('wastedispose')}}";
            let CSRF_TOKEN = "";
            window.onload = function() {
                CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            }
            let sw = 0;

            function saveChanges(id) {
                //console.log(id);
                $.ajax({
                    data: {
                        "wasteid": id
                        , _token: CSRF_TOKEN
                    }
                    , url: url
                    , type: "POST"
                    , success: function(result) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }

                });
            }

        </script>

        <script>
            function delconfirm() {

                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Transfer Request will be deleted permanently,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {} else {
                        return false;
                    }
                } else {
                    return false;
                }
            }

        </script>




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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste Management
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/waste/register">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Waste / Scrap List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/transferrequest">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Transfer Request
                                        </p>
                                    </div>
                                </a>

                            </div>

                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/waste/analysis">
                                    <div class="block-content text-center">
                                        <i class="si si-wallet fa-2x text-primary"></i>
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
    {{-- @foreach($mr as $mrs) --}}
    {{-- @if(Auth::user()->email==$mrs) --}}
    @if(Gate::allows('edit-waste-transferrequest') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if(Gate::allows('delete-waste-transferrequest') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection

</body>
</html>
@endsection