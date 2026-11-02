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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue2')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue2" name="tvalue2" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>
@foreach($mrdoc as $md)
@if(Auth::user()->email==$md)
<div class="input-group input-group-sm">
    <a type="button" href="{{ route('doc.addmaster') }}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endif
@endforeach
@endsection

@section('content')
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    <style>
        @media screen and (max-width: 760px),
        (min-device-width: 768px) and (max-device-width: 1366px) {
            body {
                margin: 0;
                padding: 0;
                overflow-x: hidden;
                overflow-y: auto;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
            }

            .pb-1,
            .py-1 {
                /*padding-bottom: .25rem!important;*/
                bottom: 0;
                position: relative;
                display: inline-block;
                clear: both;
            }
        }

    </style>
</head>
<body>
    @php
      $data1= $data2=[];
    @endphp
    <input type="hidden" value="a1" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                <div class="scrollable" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Document Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">DAR Register</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                    </table>
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th style="position:sticky;top:-1"></th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:1%;position:sticky;top:-1">S.No</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Document ID</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Department</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Requester Name</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Document Type</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Type of Action</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1">Actions</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=0;@endphp
                            @foreach($data as $value)
                            @php  $rr=$value->id;
                                    $rid=$value->rid;
                            @endphp
                            <tr>
                                <td><input class="text-center " style="font-size:10px;" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->doc_id }}</td>

                                <td class="font-w600 " style="font-size:10px;">
                                    <a href="#">
                                        @if(is_numeric($value->department))
                                            @foreach($departments as $dep)
                                            @if($value->department==$dep->id)
                                                {{$dep->departmentname}}
                                                @break
                                            @endif
                                            @endforeach
                                        @else
                                        {{$value->department}}
                                        @endif
                                    </a>
                                </td>

                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted ">{{$value->name}}</em>
                                </td>
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted ">{{$value->docType ? $value->docType->typename : $value->doctype}}</em>
                                </td>
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted ">{{$value->type}}</em>
                                </td>

                                <td class="d-none d-sm-table-cell" style="font-size:12px;">

                                    @if($value->status=='Approved')
                                    <span class="badge badge-success">{{$value->status}}</span>
                                    @elseif($value->status=='Reviewed Not OK')
                                    <span class="badge badge-danger">{{$value->status}}</span>
                                    @elseif($value->status=='Reviewed OK')
                                    <span class="badge badge-info">{{$value->status}}</span>
                                    @elseif($value->status=='Disapproved')
                                    <span class="badge badge-danger">{{$value->status}}</span>
                                    @elseif($value->status=='Pending')
                                    <span class="badge badge-warning">{{$value->status}}</span>
                                    @endif
                                </td>

                                <td class="text-center " style="font-size:10px">
                                    <div class="dropdown ">
                                        <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width: 70px;height:30px;" data-toggle="dropdown">Actions</button>
                                        <div class="dropdown-menu " aria-labelledby="dropdown-dropleft-outline-dark">
                                            @if((Auth::user()->employeeid == $rid)&&($value->status=='Reviewed Not OK' || $value->status=='Disapproved'))
                                            <a href="{{ route('doc.resubmit', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Resubmit</a>
                                            <a href="{{ route('doc.deleteaddrequest', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;" onclick="return delconfirm()">Delete</a>
                                            @endif
                                            <a href="{{ route('doc.open', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Open</a>
                                        </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;height:30px" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>
                                    <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Document id</th>
                                                        <td>{{ $value->doc_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <td>
                                                            @if(is_numeric($value->department))
                                                                @foreach($departments as $dep)
                                                                @if($value->department==$dep->id)
                                                                    {{$dep->departmentname}}
                                                                    @break
                                                                @endif
                                                                @endforeach
                                                            @else
                                                            {{$value->department}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Requester id</th>
                                                        <td>{{ $value->rid }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Requester name</th>
                                                        <td>{{ $value->name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Document type</th>
                                                        <td>{{ $value->doctype }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Type of action required</th>
                                                        <td>{{ $value->type }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reason</th>
                                                        <td>{{ nl2br($value->reason)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Added date</th>
                                                        <td>{{ $value->date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reviewed date</th>
                                                        <td>{{ $value->reviewed_at }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{$value->status}}</td>
                                                    </tr>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" style="font-size:10px;" data-dismiss="modal">Close</button>
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
    <!-- END Footer -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="{{route('doc.home')}}">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm  mt-2 mb-3">
                                            Master List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('doc.register')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-file-upload fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            DAR&nbspRegister
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                {{-- <br> --}}
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-1" href="{{ route('doc.history')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-history fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm  mt-2 mb-3">
                                            History
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">

                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{ route('doc.explorer')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-folder-open fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Explorer
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">

                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('doc.analysis') }}'>
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-0">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
@include('exporting.fordocumentmanagement')
</body>
@section('sideitem')
{{-- @foreach($mrdoc as $md) --}}
{{-- @if(Auth::user()->email==$md) --}}
@if(Gate::allows('delete-doc') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" style="margin-left:480px; " id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
{{-- @endif --}}
{{-- @endforeach --}}
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
  let route_register = "{{ route('doc.register') }}";
  let route_dardelete = "{{route('doc.dardelete',':id')}}";

  function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);


            function delconfirm() 
            {

                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Add Request datas will be deleted permanently,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {} else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
    
</script>
<script src="{{ asset('js/document/register_script.js')}}"></script>
</body>
</html>
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
            @foreach($data as $value)
            @if($value->department==Auth::user()->deptid)
            @if($value->status=="Pending")
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-check-circle text-warning"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">New document request</div>
                        <span class="font-w500 text-muted">A new document requested with id {{$value->doc_id}}</span>
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
