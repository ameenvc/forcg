@extends('pages.datatables')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <style>
        .move {
            margin-left: 5%;
            position: absolute;
            /* top: 17% */
        }

    </style>
</head>
<body>
    <div class="content" style="width:100%">
        <div class="block block-rounded" style="margin-left:-1%;">
            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;overflow-y:auto;">
                    <table class="move" width="70%">
                        <tr>
                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-left:1%;">
                                        <li class="breadcrumb-item">Document Management</li>
                                        <li class="breadcrumb-item" aria-current="page">
                                            <a class="link-fx" href="{{ route('doc.register')}}">Register</a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">
                                            <a class="link-fx" href="">Open</a>
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
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Document&nbspID</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:12%;position:sticky;top:-1">Department</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">New&nbspDocument Number</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:15%;position:sticky;top:-1">New&nbspDocument Name</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:10%;position:sticky;top:-1">Process Stage</th>
                                <th class="text-center" style="font-size:10px;text-transform:capitalize;width:5%;position:sticky;top:-1">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=0;@endphp
                            @foreach($data as $value)
                            @php
                              $rr=$value->id;
                              $rid=$value->rid;
                            @endphp
                            <tr>
                                <td class="text-center " style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center " style="font-size:10px;">{{$value->doc_id }}</td>
                                <td class="font-w600 " style="font-size:10px;">
                                    <a href="#">
                                        @if(is_numeric($value->department))
                                            @foreach($hods as $hod)
                                            @if($value->department==$hod->id)
                                                {{$hod->departmentname}}
                                                @break
                                            @endif
                                            @endforeach
                                        @else
                                            {{$value->department}}
                                        @endif
                                    </a>
                                </td>

                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted ">{{$value->ndocnumber}}</em>
                                </td>

                                <td class="d-none d-sm-table-cell " style="font-size:10px;">
                                    <em class="text-muted ">{{$value->ndocname}}</em>
                                </td>

                                <td class="d-none d-sm-table-cell " style="font-size:10px;">

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
                                                        <th style="width:70px">Document ID</th>
                                                        <td>{{ $value->doc_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <td>
                                                            @if(is_numeric($value->department))
                                                                @foreach($hods as $hod)
                                                                @if($value->department==$hod->id)
                                                                    {{$hod->departmentname}}
                                                                    @break
                                                                @endif
                                                                @endforeach
                                                            @else
                                                              {{$value->department}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Requester ID</th>
                                                        <td>{{ $value->rid }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Requester Name</th>
                                                        <td>{{ $value->name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Document Type</th>
                                                        <td>{{ $value->doctype }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Type of Action Required</th>
                                                        <td>{{ $value->type }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>New Document Number</th>
                                                        <td>{{ $value->ndocnumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>New Document Name</th>
                                                        <td>{{ $value->ndocname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Revision Number</th>
                                                        <td>{{$value->erevnumber}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Revision Date</th>
                                                        <td>{{$value->erevdate}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Added date</th>
                                                        <td>{{ $value->date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reviewed Date</th>
                                                        <td>{{ $value->reviewed_at }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Status</th>
                                                        <td>{{$value->status}}</td>
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
                        @endforeach
                    </table>
                    <div class="content py-3">
                        <div class="mb-3" style="text-align:center">
                            <div class="btn-group" role="group" aria-label="Horizontal Alternate Dark">
                                @foreach($hods as $hod)
                                @if(Auth::user()->email==$hod->hod && $department==$hod->id)
                                @if( $status=='Pending')
                                <a href="{{ route('doc.ok', ["id" => $id])}}" class="btn btn-alt-dark" style="font-size:13px;">Reviewed OK</a>
                                <a href="{{ route('doc.notok', ["id" => $id])}}" class="btn btn-alt-dark" style="font-size:13px;">Reviewed Not OK</a>
                                @endif
                                @endif
                                @endforeach
                                @foreach($mrs as $mr)
                                @if(Auth::user()->email==$mr && $status=='Reviewed OK')
                                <a href="{{ route('doc.addss', ["id" => $id])}}" class="btn btn-alt-dark" style="font-size:13px;">Approve</a>
                                <a href="{{ route('doc.disapprove', ["id" => $id])}}" class="btn btn-alt-dark" style="font-size:13px;">Disapprove</a>
                                @endif
                                @endforeach

                                <button type="button" class="btn btn-alt-dark" style="font-size:13px;" data-toggle="modal" data-id="{{ $id }}" data-target="#viewDocs{{$id}}"><strong>Attachments</strong> </button>
                                <div id="viewDocs{{$id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                                                    <?php $no=0;?>
                                                    @if( count($docs) > 0)
                                                    @foreach($docs as $doc)
                                                    @if( $doc->id==$id)
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size:13px;width: ;">{{ $no=$no+1}}</td>
                                                            <td style="font-size:13px;width: 100%;">{{ $doc->documents }}</td>
                                                            <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/documents/".$doc->documents)}}" download="{{$doc->documents}}">Download</a></td>
                                                        </tr>
                                                    </tbody>
                                                    @endif
                                                    @endforeach

                                                    @else
                                                    <div class="row justify-content-center">
                                                        <h2>No Files</h2>
                                                    </div>
                                                    @endif
                                            </table>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
</body>
</html>
@endsection
