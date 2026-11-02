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
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>
@foreach($mrdoc as $md)
@if(Auth::user()->email==$md)
<div class="input-group input-group-sm">
    <a type="button" href="{{route('doc.addmaster')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
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
            overflow-y: scroll;
        }

    </style>
</head>
<body>
    @php
      $data=$data1=[];
    @endphp
    <input type="hidden" value="a3" id="tabselected">
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                <div class="scrollable" style="overflow-y:scroll;" id="content">
                    <table class="move" width="65%">
                        <table class="move" width="15%" style="margin-left:670px">
                            <tr>
                                <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                        <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                            <li class="breadcrumb-item" style="margin-left:-600px;margin-top:-8px">Document Management</li>
                                            <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                <a class="link-fx" href="">History</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </th>
                            </tr>
                        </table>
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:1%;position:sticky;top:-1">S.No</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Type</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:3%;position:sticky;top:-1">ID</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">Document&nbspName</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Document Number</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Revision Number</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Department</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Requested Date</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Approved Date</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;width:1%;position:sticky;top:-1">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no=0;
                                    $a="Add Request";
                                    $b="Change Request";
                                    $c="Removal Request";
                                @endphp
                                @foreach($data2 as $value)
                                <tr>
                                    <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                    <td class="text-center " style="font-size:10px;">
                                        @if($value->request=='AR')
                                        <a href="#">{{$a}}</a>
                                        @elseif($value->request=='CR')
                                        <a href="#">{{ $b}}</a>
                                        @else
                                        <a href="#">{{ $c}}</a>
                                        @endif
                                    </td>
                                    <td style="font-size:10px;">
                                        <em class="text-center " style="font-size:10px;">{{$value->document_id}}</em>
                                    </td>
                                    <td style="font-size:10px;">
                                        <em class="text-center " style="font-size:10px;">{{$value->docname}}</em>
                                    </td>
                                    <td style="font-size:10px;">
                                        <em class="text-center " style="font-size:10px;">{{$value->docno}}</em>
                                    </td>
                                    <td style="font-size:10px;">
                                        @if($value->ch_approved=='YES')
                                        <em class="text-center " style="font-size:10px;">{{$value->nrevnumber}}</em>
                                        @endif
                                        @if($value->ch_approved!='YES')
                                        <em class="text-center " style="font-size:10px;">{{ $value->erevnumber}}</em>
                                        @endif
                                    </td>
                                    <td style="font-size:10px;">
                                        <em class="text-center " style="font-size:10px;">                    
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
                                        </em>
                                    </td>
                                    <td style="font-size:10px;">
                                        <em class="text-center " style="font-size:10px;">{{date('d-m-Y',strtotime($value->reqdate))}}</em>
                                    </td>
                                    <td style="font-size:10px;">
                                        <em class="text-center " style="font-size:10px;">{{ date('d-m-Y',strtotime($value->approvedate))}}</em>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal{{$value->id}}"><strong>View</strong> </button>
                                        <div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Type</th>
                                                            @if($value->request=='AR')
                                                            <td>{{ $a}}</td>
                                                            @elseif($value->request=='CR')
                                                            <td>{{ $b}}</td>
                                                            @else
                                                            <td>{{ $c}}</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th>Document id</th>
                                                            <td>{{ $value->document_id }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Document name</th>
                                                            <td>{{ $value->docname }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Document number</th>
                                                            <td>{{ $value->docno}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Revision number</th>
                                                            <td>{{ $value->ch_approved=='YES' ? $value->nrevnumber : $value->erevnumber}}</td>
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
                                                            <th>Reason</th>
                                                            <td>{{ nl2br($value->reason) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Requested date</th>
                                                            <td>{{$value->reqdate }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Approved date</th>
                                                            <td>{{$value->approvedate }}</td>
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
            <script>
                  function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);
            </script>
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href='{{ route('doc.analysis')}}'>
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
</html>
@endsection
