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
                        <a class="link-fx" href="">Renew License/Permit</a>
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
                    <form id="updatelicense" action="{{route('user.compliance.renewedlicensepermit')}}" enctype="multipart/form-data" method="post">
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
                                    <input class="form-control" type="text" name="name" id="name" required style="font-size:13px;" value="{{$data->name}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-sm-12 col-form-label" style="font-size:13px;">License Number <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="licensenumber" id="licensenumber" required  style="font-size:13px;" value="{{$data->licensenumber}}" readonly>
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
                        </div>
                        <input type="hidden" name="license_id" id="license_id" value="{{$data->id}}">       
                        <div class="row">
                            <div class="col-sm-12 text-right form-group">
                                <button type="button" class="btn btn-secondary" style="font-size:13px">Reset</button>
                                <button type="submit" class="btn btn-primary" style="font-size:13px">Renew</button>
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