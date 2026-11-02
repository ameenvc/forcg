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
@php
$data=$data1=[];
@endphp
<div class="main-container">
    <!-- Page Content -->
    <div class="content" style="width:100%;">
        @if(count($errors) >0 )
        @foreach($errors->all() as $error)
        <div class='alert alert-warning'>
            {{ $error }}
        </div>
        @endforeach
        @endif
        @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-warning">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('error')!!}
        </div>
        @endif
        <!-- Topics -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-options" style="margin-left:-25px;">
                    <a class="btn btn-sm btn-info" href="{{ route('doc.governancehub')}}">
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
                                <th class=" d-md-table-cell " style="width:15%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Document #</th>
                                <th class=" d-md-table-cell text-center" style="width:20%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Document Name</th>
                                <th class=" d-md-table-cell text-center" style="width: 10%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Type</th>
                                <th class=" d-md-table-cell text-center" style="width: 10%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Views</th>
                                <th class=" d-md-table-cell text-center" style="width: 15%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Revision #</th>
                                <th class=" d-md-table-cell text-center" style="width: 15%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Dated</th>
                                <th class=" d-md-table-cell text-center" style="width: 15%;text-transform: capitalize;font-size: 15px;position:sticky;top:-1px;">Owner</th>
                                <th class="text-center d-md-table-cell" style="width: 10%;font-size:15px;text-transform:capitalize;position:sticky;top:-1px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            <tr>
                                <td style="font-size: 13px;">
                                    <span>{{ $data->document_number }}</span>
                                </td>
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <a href="{{ url('/user/doc/procedures/?id='. $data->id) }}">{{ $data->document_name }}</a>
                                </td>
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <span class="text-center"><?= ucwords(str_replace("_", " ", $data->type)) ?></span>
                                </td>
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <span class="text-center">{{ $data->views }}</span>
                                </td>
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <span class="font-size-sm">{{ $data->revision }}</span>
                                </td>
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <span class="text-center">{{ $data->date }}</span>
                                </td>
                                @php
                                $owner = \App\Departments::where('id',$data->owner)->first();
                                @endphp
                                @if(isset($owner))
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <span class="text-center">{{ $owner->departmentname }}</span>
                                </td>
                                @else
                                <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                    <span class="text-center">-</span>
                                </td>
                                @endif
                                <div class="dropdown font-size-sm">
                                    <td class="d-md-table-cell ">
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" {{--id="drop{{$value->Oid}}"--}}>
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" {{--id="dropdownMenu{{ $value->Oid }}"--}}>
                                                @php
                                                $email = Auth::user()->email;
                                                $docAccess = \App\Doccontroller::where('email', $email)->first();
                                                $is_privileged = $docAccess?true:false;
                                                @endphp
                                                @if($is_privileged)
                                                <a href="#" class="dropdown-item" style="font-size:10px;" onclick="restore('{{$data->id}}')"><i class="fa fa-1x fa-redo" style="color:grey;"></i>&nbsp;&nbsp;Restore</a>
                                                <a href="#" style="font-size:10px" class="dropdown-item" onclick="deletedoc('{{$data->id}}')"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                @else
                                                <a href="#" class="dropdown-item" style="font-size:10px;pointer-events:none;color:#999;text-decoration:none;"><i class="fa fa-1x fa-redo" style="color:grey;"></i>&nbsp;&nbsp;Restore</a>
                                                <a href="#" style="font-size:10px;pointer-events:none;color:#999;text-decoration:none;" class="dropdown-item"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                @endif
                                            </div>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $data->id }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn('{{$data->id}}')">
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
                                                            <th style="width:70px">Document Number</th>
                                                            <td>{{$data->document_number}}</td>
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
                                                            <th>Views</th>
                                                            <td>{{$data->views}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Revision #</th>
                                                            <td>{{ $data->revision }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Date</th>
                                                            <td>{{ $data->date}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Owner</th>
                                                            @if(isset($owner))
                                                            <td>{{ $owner->departmentname }}</td>
                                                            @else
                                                            <td>-</td>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th>Prepared By</th>
                                                            <td>{{ $data->prepared_by }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reviewed By</th>
                                                            <td>{{ $data->reviewed_by }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Approved By</th>
                                                            <td>{{ $data->approved_by }}</td>
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
        if (confirm('Are you sure you want to restore this document?')) {
            console.log(id);
            $.ajax({
                type: 'POST',
                url: "{{ route('doc.restoredocument') }}",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    window.location.reload();
                },
                error: function(error) {
                    // Handle error, e.g., show an error message
                    alert('Document has not been restored successfully')
                    console.error('Error deleting record: ' + error.statusText);
                }
            });
        }
    }
</script>
<script>
    function deletedoc(id) {
        if (confirm('Are you sure you want to delete this document?')) {
            if (confirm('Are you absolutely sure? This action cannot be undone.')) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('doc.deletedocument') }}",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(error) {
                        alert('Document has not been successfully deleted.');
                        console.error('Error deleting record: ' + error.statusText);
                    }
                });
            }
        }
    }
</script>
@endsection
