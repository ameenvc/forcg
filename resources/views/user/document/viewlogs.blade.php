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

                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
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
    <style>
        body {
            overflow-y: auto;
        }

    </style>
</head>
<body>
    @php
    $data=$data2=[];
    @endphp
    <input type="hidden" value="a2" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Document Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="">Master List</a>
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
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Document Number</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:20%;position:sticky;top:-1">Document</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Revision Number</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Revision Date</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:9%;position:sticky;top:-1">Type</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:1%;position:sticky;top:-1">Level</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Department</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:19%;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1;z-index: 1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=0;@endphp
                            @foreach($data1 as $value)
                            @php $rr=$value->id; @endphp
                            <tr>
                                <td><input class="text-center " style="font-size:10px;" type="checkbox" id="{{ $value->id}}" name="id[]"></td>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->docnumber }}</td>

                                <td class="font-w600 " style="font-size:10px;">
                                    <a href="#">{{$value->document}}</a>
                                </td>
                                @if($value->ch_approved!='YES')
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted" style="font-size:10px;">{{$value->revnumber}}</em>
                                </td>
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted" style="font-size:10px;">{{$value->erevdate}}</em>
                                </td>
                                @endif
                                @if($value->ch_approved=='YES')
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted" style="font-size:10px;">{{$value->nrevnumber}}</em>
                                </td>
                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted" style="font-size:10px;">{{$value->nrevdate}}</em>
                                </td>
                                @endif
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{$value->doctype ? $value->doctype->typename : $value->type}}</em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">{{$value->level}}</em>
                                </td>
                                <td style="font-size:10px;">
                                    <em class="text-muted " style="font-size:10px;">
                                       @if(is_numeric($value->department))
                                        {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                                       @else
                                        {{$value->department}}
                                       @endif  
                                    </em>
                                </td>
                               
                                <td class="d-none d-sm-table-cell " style="font-size:12px;">
                                   <span class="{{ $value->status_class}}">{{ $value->status}}</span>
                                </td>
                                <td style="font-size:10px" class="text-left">
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        @if($value->sw)
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->id}}">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        @else
                                        <a href="#" class="btn btn-sm btn-alt-primary"  data-placement="top" title="Actions">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        @endif
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->id }}">
                                            @php
                                              $current_user = $value->departmentdetails? $value->departmentdetails->hod : '';
                                            @endphp

                                            @if($current_user == Auth::user()->email)
                                            @if( $value->status=='Change Request Raised')
                                            <a href="{{ route('doc.creview', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">HoD Review</a>
                                            @elseif($value->status=='Removal Request Raised')
                                            <a href="{{ route('doc.rreview', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">HoD Review</a>
                                            @endif
                                            @endif                                       
                                           
                                            @if(in_array(Auth::user()->email,$doccontrollers) && $value->status=='Change Request Reviewed OK')
                                            <a href="{{ route('doc.masterreview', ["id" => $rr]) }}" class="dropdown-item" style="font-size:10px;">Review</a>
                                            @elseif(in_array(Auth::user()->email,$doccontrollers) && $value->status=='Removal Request Reviewed OK')
                                            <a href="{{ route('doc.removereview', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Review</a>
                                            @endif
                                           
                                            
                                            @if($value->postedby==Auth::user()->employeeid && ($value->status=='Change Request Reviewed Not OK' || $value->status == "Change Request Disapproved"))
                                            <a href="{{ route('doc.editchangerequest', ["id" => $rr]) }}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                            <a href="{{ route('doc.deletechangerequest', ["id" => $rr]) }}" class="dropdown-item" style="font-size:10px;" onclick="return delconfirm()">Delete</a>
                                            @endif

                                            @if($value->postedby==Auth::user()->employeeid && ($value->status=='Removal Request Reviewed Not OK' || $value->status == "Removal Request Disapproved"))
                                            <a href="{{ route('doc.editremoverequest', ["id" => $rr]) }}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                            <a href="{{ route('doc.deleteremoverequest', ["id" => $rr]) }}" class="dropdown-item" style="font-size:10px;" onclick="return delremconfirm()">Delete</a>
                                            @endif
                                        </div>

                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $value->id }}"
                                           data-toggle="modal" data-placement="top" title="View" data-target="#viewModal{{$value->id}}">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                        </a>
                                    </div>
                               
                                    <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped" style="font-size:15px;">
                                                    <tr>
                                                        <th style="width:90px">Department</th>
                                                        <td>
                                                            @if(is_numeric($value->department))
                                                                {{$value->departmentdetails? $value->departmentdetails->departmentname : ''}}
                                                            @else
                                                                {{$value->department}}
                                                            @endif  
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Document number</th>
                                                        <td>{{ $value->docnumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Document</th>
                                                        <td>{{ $value->document }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Type</th>
                                                        <td>{{ $value->doctype ? $value->doctype->typename : $value->type }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Level</th>
                                                        <td>{{ $value->level}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Revision number</th>
                                                        <td>{{ $value->ch_approved != 'YES' ? $value->revnumber : $value->nrevnumber}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Revision date</th>
                                                        <td>{{ $value->ch_approved != 'YES' ? $value->erevdate : $value->nrevdate}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Added date</th>
                                                        <td>{{ $value->created_at }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reviewed date</th>
                                                        <td>{{ $value->reviewedat }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Posted by(employee id)</th>
                                                        <td>{{ $value->postedby }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td> <span class="{{ $value->status_class}}">{{ $value->status}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reason For {{ $value->status == 'Change Request Raised' ? "change" : "removal"}} request</th>
                                                        <td>{{ nl2br($value->reason) }}</td>
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
    <!-- END Dynamic Table Full -->
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
                                <a class="block block-rounded block-link-shadow bg-body" href="{{ route('doc.home')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-1" href="{{ route('doc.history') }}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{ route('doc.explorer') }}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('doc.analysis')}}">
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

    @include('exporting.fordocumentmanagement')
    <!-- END Apps Modal -->
</body>

@section('sideitem')
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:390px; " data-toggle="dropdown">
        <i class="fa fa-file-alt fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="width:20px;text-align:center;">
        <li> <button id="change" value=id[] style="width:150px;  font-size:10px;border:none;background-color:white;">Change Request(DCR)</button></li>

        <a href="{{ route('doc.add') }}" style="width:150px;  font-size:10px;color:black;">Add Request(DAR)</a>
        <li> <button id="delete" value=id[] style="width:150px;  font-size:10px;border:none;background-color:white;">Removal Request(DRR)</button> </li>
    </ul>
</div>

@if(Gate::allows('edit-doc') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="edit" value=id[]>
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-doc') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    let home_route = "{{ route('doc.home')}}";
    let remove_route = "{{ route('doc.remove',':id') }}";
    let change_route = "{{ route('doc.change',':id') }}";
    let delete_route = "{{ route('doc.delete',':id') }}";
    let edit_route = "{{ route('doc.editdoc', ':id')}}";
    function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);
</script>
<script src="{{ asset('js/document/viewlogs_script.js')}}"></script>
<script>
            function delconfirm() {

                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Change Request datas will be deleted permanently,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {} else {
                        return false;
                    }
                } else {
                    return false;
                }
            }

            function delremconfirm() 
            {
                mess1 = "Are you sure?";
                x = confirm(mess1);
                if (x == true) {
                    mess2 = 'Removal Request  will be deleted permanently,Press OK to continue.';
                    y = confirm(mess2);
                    if (y == true) {} else {
                        return false;
                    }
                } else {
                    return false;
                }
                }



        </script>
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

            @foreach($data1 as $value)

            @if($value->department==Auth::user()->deptid)
            @if($value->status=='Change Request Raised')
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-check-circle"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">Change request raised</div>
                        <span class="font-w500 text-muted">Change request raised for document number {{$value->docnumber}}</span>
                    </div>
                </a>
            </li>
            @endif
            @if($value->status=='Removal Request Raised')
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-check-circle text-danger"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">Removal request raised</div>
                        <span class="font-w500 text-muted">Removal request raised for document number {{$value->docnumber}}</span>
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
