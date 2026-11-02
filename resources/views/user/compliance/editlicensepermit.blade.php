@extends('pages.datatables')
@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Compliance Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Edit License/Permit</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/compliance/licensepermits">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->
<style>
    .note-editable{
        height:250px !important;
    }
    .select2-results__options,
    .select2 {
        font-size: 12px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- Page Content -->
<div class="content">
    @if(count($errors) >0 )
        @foreach($errors->all() as $error)
        <div class='alert alert-warning'>
        <a class="close" data-dismiss="alert">×</a>
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
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form id="updatelicense" action="{{route('user.compliance.updatelicensepermit')}}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-sm-12 col-form-label" style="font-size:13px;">ID <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="name" id="name" required style="font-size:13px;" value="{{$data->compliance_auth_id}}" readonly>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-sm-12 col-form-label" style="font-size:13px;">License/Permit Name <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="name" id="name" required style="font-size:13px;" value="{{$data->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-sm-12 col-form-label" style="font-size:13px;">License Number <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="licensenumber" id="licensenumber" required  style="font-size:13px;" value="{{$data->licensenumber}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="expirydate" class="col-sm-12 col-form-label" style="font-size:13px;">Expiry Date <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="date" name="expirydate" id="expirydate" required style="font-size:13px;" value="{{$data->expirydate}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="form-group col-sm-6">
                                <label for="effectivedate" class="col-sm-12 col-form-label" style="font-size:13px;">Effective Date</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="date" name="effdate" id="effdate" style="font-size:13px;" value="{{$data->effectivedate}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="responsible" class="col-sm-12 col-form-label" style="font-size:13px;">Responsible Person <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control selectm select_responsible" name="responsible[]" id="responsible" style="font-size: 13px;width:100%;" multiple required>
                                    @if(count($users) > 0)
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" style="font-size:13px;" {{ in_array($user->id, explode(',',$data->responsible)) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach 
                                    @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Email reminder for Renewal <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control selectm select_frequency" style="font-size:12px;width: 100%;" id="frequency" name="frequency[]" data-placeholder="Choose frequencies" multiple>
                                        <option value="Weekly" style="font-size:12px;" {{ in_array('Weekly', explode(',',$data->frequency)) ? 'selected' : '' }}>Weekly</option><br>
                                        <option value="Every 5 Days" style="font-size:12px;" {{ in_array('Every 5 Days', explode(',',$data->frequency)) ? 'selected' : '' }}>Every 5 days</option><br>
                                        <option value="Every 2 Days" style="font-size:12px;" {{ in_array('Every 2 Days', explode(',',$data->frequency)) ? 'selected' : '' }}>Every 2 days</option><br>
                                        <option value="Daily" style="font-size:12px;" {{ in_array('Daily', explode(',',$data->frequency)) ? 'selected' : '' }}>Daily</option><br>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="form-group col-sm-12">
                                <label for="renewal" class="col-sm-12 col-form-label" style="font-size:13px;">Renewal Requirements</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control js-summernote" style="font-size:13px;" name="renewal" id="renewal" >{{$data->renewal_requirements}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="associated" class="col-sm-12 col-form-label" style="font-size:13px;">Associated Regulations</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control js-summernote" style="font-size:12px;" name="associated" id="associated">{{$data->associated_regulations}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="file" class="col-sm-12 col-form-label" style="font-size:13px;">File</label>
                                <div class="col-sm-12">
                                    <input class="form-control" style="font-size:13px;" type="file" name="document[]" id="document" multiple>
                                </div>
                            </div>
                            <div class="form-group col-sm-6" style="margin:auto 0;">
                                <a data-toggle="modal"  data-target="#viewModal"  data-id="{{ $data->id }}" class="btn btn-secondary btn-small col-sm-12" style="font-size:13px;width: 150px;"><strong>View Attachments</strong></a> 
                            </div>
                            <input type="hidden" name="license_id" id="license_id" value="{{$data->id}}">
                        </div>        
                        <div class="row">
                            <div class="col-sm-12 text-right form-group">
                                <button type="button" class="btn btn-secondary" style="font-size:13px">Reset</button>
                                <button type="submit" class="btn btn-primary" style="font-size:13px">Update</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->
</div>
<div id="viewModal" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Attachments</h3>
                <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
            </div>
            <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                <div class="row justify-content-center">
                    <thead>
                        <tr class="table-secondary">
                            <th>S.No</th>
                            <th>Attachment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php $o=0; ?>
                    @if($data->file!='')
                        <tbody>
                            @php $files = explode(',',$data->file) @endphp
                            @foreach($files as $f)
                            <tr>
                                <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                <td style="font-size:15px;width:70%;">{{ $f}}</td>
                                <td style="width:100%;">
                                    <a style="font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/compliance_files/'.$f)}}" download="{{$f}}">Download</a>
                                    <button class="btn btn-secondary remove-attachment" data-filename="{{ $f }}" style="font-size:11px;" title="Delete">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/compliancemgmt">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br />
                                        Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/compliance/myactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        <br>My Actions
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Payments -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/compliance/companalysis'>
                                <div class="block-content text-center">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
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
<!-- Ends App Model -->
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var removedFilenames = [];
        $('.remove-attachment').on('click', function () {
            var filename = $(this).data('filename');
            $(this).closest('tr').remove();
            removedFilenames.push(filename);
        });
        $('#updatelicense').on('submit', function () {
            $('<input />').attr('type', 'hidden')
                .attr('name', 'removedFilenames')
                .attr('value', JSON.stringify(removedFilenames))
                .appendTo($(this));
            return true;
        });
    });
</script>
@section('javascript')
<script>
    $('.select_responsible').select2();
    $('.select_frequency').select2();
</script>
@endsection