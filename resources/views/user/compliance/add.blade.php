@extends('layouts.multiselect')
@section('content')
<style>
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
<!-- Page Content -->
<div class="content">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-w700">
                    <a href="/user/compliancemgmt" class="text-black">
                        Compliance Management
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    Add Compliance
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-12">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{url('/user/compliance/submit')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
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
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="authority" style="font-size:13px;">Regulatory authority / Regulation issued by <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="authority" style="font-size:13px;width:100%;" required="true" name="authority"></select>
                                </div>
                                <div class="col-sm-12 mt-2 d-none" id="otherRegulatory">
                                    <input type="text" class="form-control" id="other_reg" style="font-size:13px;" name="other_reg" placeholder="Enter Regulatory authority name" value="{{ old('other_reg')}}">
                                </div>
                                <div id="error_authority" class="text-danger col-sm-12 mt-1 d-none" style="font-size:13px;">
                                    Same Regulatory authority already exists.
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="issue" style="font-size:13px;">Name of legal Document <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" id="issue" style="font-size:13px;width:100%;" required="true" name="issue" data-placeholder="Select Legal Document">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 d-none" id="otherDocument">
                                    <input type="text" class="form-control" id="other_doc" style="font-size:13px;" name="other_doc" placeholder="Enter Legal document name" value="{{ old('other_doc')}}">
                                </div>
                                <div id="error_document" class="text-danger col-sm-12 mt-1 d-none" style="font-size:13px;">
                                    Same Legal document already exists for this Regulatory authority.
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="version" style="font-size:13px;">Version / year / dated <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="version" style="font-size:13px;" required="true" name="version" placeholder="Enter version" value="{{ old('version')}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="regulation" style="font-size:13px;">Legal Requirement Title <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="regulation" style="font-size:13px;" required="true" name="regulation" placeholder="Enter specific clause/section/article" value="{{old('regulation')}}">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="requirement" style="font-size:13px;">Requirement <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <textarea cols="10" rows="5" class="form-control" id="requirement" style="font-size:13px;" required="true" name="requirement" placeholder="Enter requirement">{{ old('requirement')}}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsible person <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-select w-100" name="responsibility" id="responsibility" required="true" style="font-size:13px;width:100%;" data-placeholder="Choose Responsibility">
                                        <option></option>
                                        @if(count($users)> 0 )
                                        @foreach($users as $value)
                                        <option value="{{$value->email}}" style="font-size:13px;" {{ old('responsibility') == $value->email ? 'selected' : ''}}> {{ $value->name }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>
                                <label class="col-sm-12 col-form-label mt-2" for="aspect" style="font-size:13px;">Related aspect/ risk number <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="aspect" style="font-size:13px;" required="true" name="aspect" placeholder="Enter related aspect/ risk number" value="{{ old('aspect') }}">
                                </div>
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="owner" style="font-size:13px;">Department <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="owner" required="true" style="font-size:13px;width:100%;" data-placeholder="Choose a Department">
                                        <option></option>
                                        @if(count($mn)> 0 )
                                        @foreach($mn as $value)
                                        <option value="{{$value->id}}" style="font-size:13px;" {{old('owner') == $value->id ? 'selected' : ''}}> {{ $value->departmentname }}</option><br>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Review frequency <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="frequency" required style="font-size:13px;width:100%;" data-placeholder="Choose Review Frequency">
                                        <option></option>
                                        <option value="Quarterly" style="font-size:13px;" {{old('frequency') == 'Quarterly' ? 'selected' : ''}}>Quarterly</option><br>
                                        <option value="Half yearly" style="font-size:13px;" {{old('frequency') == 'Half yearly' ? 'selected' : ''}}>Half yearly</option><br>
                                        <option value="Yearly" style="font-size:13px;" {{old('frequency') == 'Yearly' ? 'selected' : ''}}>Yearly</option><br>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Legal Compliance Reviewer <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control js-select2" name="legalCoordinator" required style="font-size:13px;width:100%;" data-placeholder="Select Legal Coordinator">
                                        <option></option>
                                        @if(count($legalCoordinators)> 0 )
                                            @foreach($legalCoordinators as $value)
                                                <option value="{{$value->id}}" style="font-size:13px;" {{ old('legalCoordinator') == $value->id ? 'selected' : ''}}> {{ $value->name }}</option><br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="status" style="font-size:13px;">Status <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="status" id="status" required="true" style="font-size:13px;" placeholder="Choose Status" onchange="changeStatus()">
                                        <option value="Complied" style="font-size:13px;" {{ old('status') == "Complied" ? 'selected' : ''}}>Complied</option><br>
                                        <option value="Partially Complied" style="font-size:13px;" {{ old('status') == "Partially Complied" ? 'selected' : ''}}>Partially Complied</option><br>
                                        <option value="Not Complied" style="font-size:13px;" {{ old('status') == "Not Complied" ? 'selected' : ''}}>Not Complied</option><br>
                                        <option value="Under Review" style="font-size:13px;" {{ old('status') == "Under Review" ? 'selected' : ''}}>Under Review</option><br>
                                    </select>

                                </div>
                            </div> --}}
                            {{-- <div class="form-group /*display_none*/" style="width:49%;" id="div_target_date">
                                <label class="col-sm-12 col-form-label" for="date" style="font-size:13px;">Target date</label>
                                <div class="col-sm-12">
                                    <input type="Date" class="form-control" id="title" style="font-size:13px;" name="duedate" value="{{ old('duedate') }}">
                                </div>
                            </div> --}}
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="file" style="font-size:13px;">File</label>
                                <div class="col-sm-12">
                                    <input type="file" class="form-control" id="file" style="font-size:13px;" name="file" value="{{ old('file') }}">
                                </div>
                            </div>

                            <input type="hidden" class="form-control" id="addeddate" name="addeddate" style="font-size:13px;" value="{{ date('Y-m-d') }}">
                            <br>
                            <input type="hidden" name="postedby" value="{{Auth::user()->employeeid}}">

                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary mr-2" style="font-size:13px;">Add</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Apps</h3>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->
<script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#authority').select2({
            ajax: {
                url: window.location.origin +"/user/compliance/get-regulatory",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term 
                    };
                },
                processResults: function (data) {
                    // authoritiesNameArray = []; 
                    var results = []
                    $.each(data.items, function (index,item) {
                        results.push({
                            id: item.id,
                            text: item.name
                        });
                    });
                    return {
                        results: results,
                        pagination: {
                            more: data.hasMore
                        }
                    };
                    cache: true
                }
            },
            placeholder: 'Select Regulatory Authority'
        });
        $('#authority').on('change', function() {
            var authorityID = $(this).val();
            $('#error_authority').addClass('d-none');
            $('#error_document').addClass('d-none');
            // Clear previous options and disable the document dropdown
            $('#issue').empty().append('<option></option>').prop('disabled', true);
            // Skip AJAX if "Other" is selected or no authority is selected
            if (authorityID === '' || authorityID === '0') {
                $('#otherRegulatory').removeClass('d-none');
                $('#otherDocument').removeClass('d-none');
                $('#issue').empty().append('<option></option><option value="other" selected>Other</option>').prop('disabled', true);
                $('#other_reg').attr('required',true).val('');
                $('#other_doc').attr('required',true).val('');
                return; 
            }else {
                $('#otherRegulatory').addClass('d-none');
                $('#otherDocument').addClass('d-none');
                $('#other_reg').attr('required',false).val('');
                $('#other_doc').attr('required',false).val('');
            }
            // Perform AJAX request to fetch associated legal documents
            $.ajax({
                url: `/user/compliance/get-legal-documents/${authorityID}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Populate the dropdown with fetched data
                    $.each(data, function(key, document) {
                        $('#issue').append('<option value="' + document.id + '" style="font-size:13px;">' + document.name + '</option>');
                    });
                    $('#issue').append('<option value="other">Other</option>').prop('disabled', false).trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching legal documents:", error);
                    alert("Could not load legal documents. Please try again.");
                    $('#issue').prop('disabled', false);
                }
            });
        });
        $('#issue').on('change', function() {
            $('#error_document').addClass('d-none');
            if ($(this).val() === 'other') {
                $('#otherDocument').removeClass('d-none');
                $('#other_doc').attr('required',true).val('');
            } else {
                $('#otherDocument').addClass('d-none');
                $('#other_doc').attr('required',false).val('');
            }
        });
        $('#other_doc').on('input',function(){
            var authorityID = $('#authority').val();
            var inputVal = $(this).val().toLowerCase();
            if (authorityID === '' || authorityID === '0' || $('#otherRegulatory').hasClass('d-none')==false) {
                $('#error_document').addClass('d-none');
                return;
            }
            $.ajax({
                url: `/user/compliance/check-legal-document`,
                type: 'GET',
                data: { authority_id: authorityID, document_name: inputVal },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#error_document').removeClass('d-none');
                    } else {
                        $('#error_document').addClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error checking legal document:", error);
                }
            });
        });
        $('#other_reg').on('input',function(){
            var reg = $(this).val();    
            $.ajax({
                url: `/user/compliance/check-regulatory`,
                type: 'GET',
                data: { regulatory_name: reg },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#error_authority').removeClass('d-none');
                    } else {
                        $('#error_authority').addClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error checking regulatory:", error);
                }
            });
        });
        $('form').on('submit',function(e){
            e.preventDefault();
            const $regError = !$('#error_authority').hasClass('d-none');
            const $docError = !$('#error_document').hasClass('d-none');
            if($regError || $docError){
                Swal.fire({
                    icon: 'error',
                    title: '<span style="font-size:22px;color:red;">Duplicate Regularoty authority / Legal document name</span>',
                    text: 'Please change the Regularoty authority / Legal document name.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            this.submit();
        });
    });
</script>
@if (session('success_email'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("{{ route('user.compliance.send_add_compliance_emails') }}?id={{ session('Cid') }}&type=add")
            .then(response => console.log("Email sending triggered in Add"));
    });
</script>
@endif
@endsection
