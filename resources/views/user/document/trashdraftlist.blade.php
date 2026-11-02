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
@endsection
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .select2-results__options,
        .select2 {
            font-size: 13px;
        }
    </style>
    <style>
        .disabled-link {
            pointer-events: none;
            color: #999;
            text-decoration: none;
        }
    </style>
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{asset('js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" id="css-main" href="{{asset('css/oneui.min.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="main-container">
        <!-- Page Content -->
        <div class="content" style="width:100%;">
            @if(Session::has('msg'))
            <div class="alert alert-primary">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('msg')!!}
            </div>
            @endif
            <!-- Topics -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <div class="block-options" style="margin-left:-25px;">
                        <a class="btn btn-sm btn-info" href="{{ route('doc.document_draft')}}">
                            <i class="fa fa-x fa-angle-left mr-1"></i>&nbsp;Back
                        </a>

                    </div>
                </div>
                <div class="block-content">
                    <!-- Topics -->
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless table-vcenter js-dataTable-full">
                            <thead class="border-bottom">
                                <tr>
                                    <th class=" d-md-table-cell text-center" style="width:15%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Drafted By</th>
                                    <th class=" d-md-table-cell text-center" style="width:20%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Document Name</th>
                                    <th class=" d-md-table-cell text-center" style="width: 10%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Type</th>
                                    <th class=" d-md-table-cell text-center" style="width: 10%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Dated</th>
                                    <th class=" d-md-table-cell text-center" style="width: 10%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Owner</th>
                                    <th class=" d-md-table-cell text-center" style="width: 20%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Status</th>
                                    <th class="text-center d-md-table-cell" style="width: 10%;font-size:15px;text-transform:capitalize;position:sticky;top:-1px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($datas as $data)
                                <tr>
                                    @php
                                    $drafted_name = \App\User::where('email', $data->drafted_by)->first();

                                    @endphp
                                    <td class="text-center" style="font-size: 13px;">
                                        @if($drafted_name)
                                        <span>{{ $drafted_name->name  }}</span>
                                        @endif
                                    </td>
                                    <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                        <a href="{{ route('doc.procedures_draft',['id' => $data->id]) }}">{{ $data->document_name }}</a>
                                    </td>
                                    <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                        <span><?= ucwords(str_replace("_", " ", $data->type)) ?></span>
                                    </td>
                                    <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                        <span>{{ $data->date }}</span>
                                    </td>
                                    <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                        @if(is_numeric($data->owner))
                                            {{ $data->department ? $data->department->departmentname : '--'}}
                                        @else
                                            {{$data->owner}}
                                        @endif
                                    </td>
                                    <td class="text-center d-sm-table-cell" style="font-size: 13px;">
                                        @if($data->status=='Draft Reviewed')
                                        <span style="color:#1fa98a;font-weight:500;">{{$data->status}}</span>
                                        @elseif($data->status=='Review Requested')
                                        <span style="color:#800088;font-weight:500;">{{$data->status}}</span>
                                        @elseif($data->status=='Under Draft')
                                        <span> {{$data->status}}</span>
                                        @else
                                        <span style="color:red;">{{$data->status}}</span>
                                        @endif
                                    </td>
                                    <div class="dropdown font-size-sm">
                                        <td class="d-md-table-cell ">
                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                                    <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                </a>
                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}">

                                                    @php
                                                    $email = Auth::user()->email;
                                                    $docAccess = \App\Doccontroller::where('email', $email)->first();
                                                    $is_privileged = $docAccess?true:false;
                                                    @endphp
                                                    @if($is_privileged)
                                                    <a href="#" class="dropdown-item" style="font-size:10px;color:black;" onclick="restore('{{$data->id}}')"><i class="fa fa-1x fa-redo" style="color:grey;"></i>&nbsp;&nbsp;Restore</a>
                                                    <a href="#" style="font-size:10px;color:black;" class="dropdown-item" onclick="deletedoc('{{$data->id}}')"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                    @else
                                                    <a href="#" class="dropdown-item" style="font-size:10px;pointer-events:none;color:#999;text-decoration:none;"><i class="fa fa-1x fa-redo" style="color:grey;"></i>&nbsp;&nbsp;Restore</a>
                                                    <a href="#" style="font-size:10px;pointer-events:none;color:#999;text-decoration:none;" class="dropdown-item"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                    @endif
                                                </div>
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{$data->id}}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn('{{$data->id}}')">
                                                    <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                </a>
                                            </div>


                                            <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-title">Detailed View</h2>
                                                        </div>
                                                        <table class="table table-striped">
                                                            <tr>
                                                                <th style="width:70px">Drafted By</th>
                                                                @if($drafted_name)
                                                                <td>{{ $drafted_name->name}}</td>
                                                                @endif
                                                            </tr>

                                                            <tr>
                                                                <th>Document Name</th>
                                                                <td>{{ $data->document_name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Type</th>
                                                                <td>{{ ucwords(str_replace("_", " ", $data->type)) }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Date</th>
                                                                <td>{{ $data->date }}</td>
                                                            </tr>

                                                            <tr>
                                                                <th>Owner</th>
                                                                <td>
                                                                    @if(is_numeric($data->owner))
                                                                        {{ $data->department ? $data->department->departmentname : '--'}}
                                                                    @else
                                                                        {{$data->owner}}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status</th>
                                                                <td class="d-sm-table-cell">
                                                                    @if($data->status=='Draft Reviewed')
                                                                    <span style="color:#1fa98a;font-weight:500;">{{$data->status}}</span>
                                                                    @elseif($data->status=='Review Requested')
                                                                    <span style="color:#800088;font-weight:500;">{{$data->status}}</span>
                                                                    @elseif($data->status=='Under Draft')
                                                                    <span>{{$data->status}}</span>
                                                                    @else
                                                                    <span style="color:red;">{{$data->status}}</span>
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
                                    </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- END Topics -->
                </div>
            </div>
            <!-- END Topics -->
        </div>
        <!-- END Page Content -->
    </div>
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
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{ route('doc.governancehub')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-folder-open fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Governance Hub
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
    <script>
        function ViewBtn(id) {
            $('#viewModal' + id).modal('show');
        }
    </script>
    <script>
        function restore(id) {
            if (confirm('Are you sure you want to restore this draft?')) {
                console.log(id);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('doc.restoredraft') }}",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(error) {
                        // Handle error, e.g., show an error message
                        alert('Draft has not been restored successfully')
                        console.error('Error deleting record: ' + error.statusText);
                    }
                });
            }
        }
    </script>
    <script>
        function deletedoc(id) {
            if (confirm('Are you sure you want to delete this draft?')) {
                if (confirm('Are you absolutely sure? This action cannot be undone.')) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('doc.deletedraft') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function(error) {
                            alert('Draft has not been successfully deleted.');
                            console.error('Error deleting record: ' + error.statusText);
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>
@endsection
