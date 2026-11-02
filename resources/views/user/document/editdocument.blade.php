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
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a class="link-fx text-dark" href="#">Governance Hub</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx text-dark" href="#">Edit Document</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="link-fx" href="{{ route('doc.governancehub')}}">Back</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- update form starts here -->
                            <form action="{{route('doc.edit_document_request')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="col-lg-10">
                                    <input type="hidden" name="id" value="{{$data->id}}">
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="docnumber" style="font-size: 13px;">Document Number</label>
                                            <input type="text" class="form-control" name="docnumber" id="docnumber" value="{{ old('docnumber', $data->document_number) }}" style="font-size: 13px;">
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="docname" style="font-size: 13px;">Document Name</label>
                                            <input type="text" class="form-control" name="docname" id="docname" value="{{ old('docname', $data->document_name) }}" style="font-size: 13px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="doctype" style="font-size: 13px;">Type</label>
                                            <select class="form-control" id="doctype" name="doctype" required style="font-size:13px;">
                                               <option value="">Choose Type</option>
                                                @foreach($types as $type)
                                                <option value="{{$type->id}}" {{$type->id == old('doctype', $data->doctype_id) ? 'selected' : '' }}>
                                                    {{ $type->typename }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="revision" style="font-size: 13px;">Revision</label>
                                            <input type="text" class="form-control" name="revision" id="revision" value="{{ old('revision', $data->revision) }}" style="font-size: 13px;">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="date" style="font-size: 13px;">Date</label>
                                            <input type="date" class="form-control" name="date" id="date" value="{{ old('date', $data->date) }}" style="font-size: 13px;">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="col-form-label" for="owner" style="font-size: 13px;">Owner</label>
                                            <select class="form-control" id="docowner" name="docowner" required style="font-size:13px;">
                                                @foreach($departments as $department)
                                                <option value="{{ $department->id }}" style="font-size:13px;" 
                                                  {{ old('docowner', $data->owner) ==$department->id ? 'selected' : '' }}>{{ $department->departmentname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <button type="submit" for="submit" class="btn btn-primary" style="font-size:13px;" onclick="return confirm('Do you want to proceed ? ')">Update </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <!-- update form ends here -->
                        </div>
                    </div>
                </div>
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
<!-- Modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        const $docNumber = $('#docnumber');
        const $documentType = $('#doctype');
        const retrieveDocumentNumber = (type) => {
            if (!type) {
                if($docNumber.length) {
                    $docNumber.val("");
                }
                return;
            }

            $.ajax({
                url: "{{ route('doc.retrieveDocumentNumber') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                data: {
                    type
                },
                success: function(response) {
                    $docNumber.val(response.document_number);
                },
                error: function(err) {
                    console.error('Error fetching document number:', err);
                    $docNumber.val("");
                }
            });
        }

        // Initialize document number based on selected type
        $documentType.on('change', function (e) {
            let selectedOption = e.target.value;
            retrieveDocumentNumber(selectedOption);
        });

        // Check document number uniqueness on input
        // Debounce the input event to avoid too many requests
        let debounceTimer;
        $docNumber.on('input', function() {
            const docNumber = $(this).val();
            const $this = $(this);
            const documentId = $('input[name="id"]').val();

            // Clear any existing timer
            clearTimeout(debounceTimer);

            // Remove validation styles immediately for better UX
            $this.removeClass('is-invalid');
            $this.next('.invalid-feedback').remove();

            if (docNumber) {
                // Set a new timer with 500ms delay
                debounceTimer = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('doc.checkDocumentNumber') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                            'accept': 'application/json'
                        },
                        data: {
                            document_number: docNumber,
                            document_id: documentId
                        },
                        success: function(response) {
                            if (response.exists) {
                                $this.addClass('is-invalid');
                                $this.next('.invalid-feedback').remove();
                                $this.after('<div class="invalid-feedback">The document number has already been taken.</div>');
                            } else {
                                $this.removeClass('is-invalid');
                                $this.next('.invalid-feedback').remove();
                            }
                        },
                        error: function(err) {
                            console.error('Error checking document number:', err);
                        }
                    });
                }, 500); // 500ms debounce delay
            }
        });
    })
</script>
@endsection