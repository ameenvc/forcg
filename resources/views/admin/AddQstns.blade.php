@extends('admin.datatable')

@section('content')

    <!-- Page Content -->
    <div class="content">
        <!-- Horizontal -->
        <div class="block block-rounded">


            <div class="block-header">
                <h3 class="block-title">Add questions</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/vendorEvaluationDetails">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:85%;">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/insertqstns')}}" onsubmit="return validation()" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            <div class="row mb-4">
                                <div class="col-sm-3 ">
                                    <label class="col-form-label" style="font-size: 13px;" for="type">Enter Question <span style="color:red">*</span></label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" style="font-size: 13px;" id="qstn" name="qstn" placeholder="Enter Question" required></textarea>

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label style="font-size: 13px;" class="col-form-label" for="title">Score Out Of</label>
                                    
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control" style="font-size: 13px;width:100%;" name="score" id="score">
                                        <option selected="" disabled>Choose</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">Select Type</label>

                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control" style="font-size: 13px;width:100%;" name="type" id="type">
                                        <option selected="" disabled>Choose</option>
                                        <option value="Product">Product</option>
                                        <option value="Service Provider">Service Provider</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-form-label" for="issue" style="font-size: 13px;">Select To Be Filled By</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <select class="form-control" style="font-size: 13px;" name="fill" id="fill">
                                            <option selected="" disabled>Choose</option>
                                            <option value="End User">End User</option>
                                            <option value="HSE">System & Compliance</option>
                                            <option value="Procurement">Supply chain Representative</option>
                                        </select>
                                        <div class="input-group-append">
                                            {{-- <button type="button" class="btn btn-dark">Submit</button> --}}
                                            <input type="submit" name="update" value="Add" style="font-size: 10px;" class="btn btn-primary" onclick="formSubmit()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </div>
@endsection
