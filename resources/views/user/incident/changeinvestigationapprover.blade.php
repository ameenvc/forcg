@extends('layouts.backend')
@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Incident Report</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Change Investigation Approver</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{url('/user/incidents/register')}}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    @if(Session::has('msg'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('msg')!!}
    </div>
    @endif
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-10">
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" enctype="multipart/form-data" action="{{route('incident.changeinvestigationapprover')}}" method="post" files="true">
                        {{csrf_field()}}
                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="report_no" style="font-size:13px;">Report No</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="report_no" name="report_no" style="font-size:13px;" value="{{$data->report_no}}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="type" style="font-size:13px;">Type</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="type" style="font-size:13px;" name="type" value="{{$data->incident_type}}" readonly>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="status" style="font-size:13px;">Status</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="status" style="font-size:13px;" name="status" value="{{$status}}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="col-sm-12 col-form-label" for="approver" style="font-size:13px;">Change Investigation Approver <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="form-control" name="approver" required="true" style="font-size:13px;width:100%;">
                                    @foreach($approvers as $approver)
                                    <option value="{{$approver->email}}" <?php if($data->approver_email==$approver->email) {?>selected<?php } ?>>{{$approver->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        </div>
                        <input type="hidden" name="incident_id" value="{{$data->id}}">
                        <div class="row">
                            <div class="form-group col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;" onclick="return confirm('Do you want to proceed ? ')">Submit</button>
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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/register">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Incident Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/incidents/closed/incidents">
                                <div class="block-content text-center">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Incident
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/incident/correactions">
                                <div class="block-content text-center">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Corrective Actions
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

@endsection
