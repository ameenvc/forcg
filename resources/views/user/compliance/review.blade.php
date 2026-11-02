@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Compliance Management</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Review</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/compliancemgmt">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">

    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0px 0px 15px;background-color: #ffffff;">

        <div class="block-content block-content-full">
            <div class="row">

                <div class="col-lg-10">
                    @if(count($errors)>0)
                    @foreach($errors->all() as $error)
                    <div class="alert alert-warning">
                        {{$error}}
                    </div>
                    @endforeach
                    @endif
                    <!-- Form Horizontal - Default Style -->
                    <form class="mb-5" action="{{ route('user.compliance.addreview') }}" enctype="multipart/form-data" method="post">
                        {{csrf_field()}}

                        <input type="hidden" name="auth_id" value="{{ $auth_id }}" />
                        <input type="hidden" name="id" value="{{ $id }}" />

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Review frequency </label>
                            <div class="col-sm-12">
                                <select class="form-control" name="frequency" required="true" style="font-size:13px;">
                                    <option value="Quarterly" style="font-size:13px;" {{$data->frequency == 'Quarterly' ? 'selected' : '' }}>Quarterly</option><br>
                                    <option value="Half yearly" style="font-size:13px;" {{$data->frequency == 'Half yearly' ? 'selected' : '' }}>Half yearly</option><br>
                                    <option value="Yearly" style="font-size:13px;" {{$data->frequency == 'Yearly' ? 'selected' : '' }}>Yearly</option><br>
                                </select>

                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="authority" style="font-size:13px;">Regulatory authority</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="authority" style="font-size:13px;" value="{{$data->authority}}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="issue" style="font-size:13px;">Legislative issue</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="issue" style="font-size:13px;" value="{{$data->issue}}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="version" style="font-size:13px;">Version / year / dated</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" name="version" value="{{$data->version}}">
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="regulation" style="font-size:13px;">Act or regulation</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" name="regulation" value="{{$data->regulation}}">
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="owner" style="font-size:13px;">Department</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="owner" required="true" style="font-size:13px;" placeholder="Choose a Department">
                                    @if(count($departments)> 0 )
                                    @foreach($departments as $value)
                                    <option value="{{$value->id}}" style="font-size:13px;" {{ $data->owner == $value->id ? 'selected' : ''}}> {{ $value->departmentname }}</option><br>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="aspect" style="font-size:13px;">Related aspect/risk number</label>
                            <div class="col-sm-12">
                                <textarea cols="10" rows="5" class="form-control" type="text" style="font-size:13px;" name="aspect">{{$data->aspect}} </textarea>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="requirement" style="font-size:13px;">Requirement</label>
                            <div class="col-sm-12">
                                <textarea cols="10" rows="5" class="form-control" id="requirement" style="font-size:13px;" required="true" name="requirement" placeholder="Enter requirement">{{ $data->requirement }}</textarea>
                            </div>
                        </div>

                        <div class="form-group up" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="responsibility" style="font-size:13px;">Responsible person <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-select w-100" width="100" List-style-type="none" name="responsibility" id="responsibility" required="true" style="font-size:13px;" placeholder="Choose a Department">
                                    @if(count($users)> 0 )
                                    @foreach($users as $value)
                                    <option value="{{$value->email}}" style="font-size:13px;" {{ $data->responsibility == $value->email ? 'selected' : ''}}> {{ $value->name }}</option><br>
                                    @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>

                        <div class="form-group d-inline-block" style="width:49%;position:relative;bottom:20px;">
                            <label class="col-sm-12 col-form-label" style="font-size:13px;">Status</label>
                            <div class="col-sm-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="example-radios-inline1" name="status" value="complied_under_review">
                                    <label class="form-check-label" for="example-radios-inline1" style="font-size:13px;">Complied</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="example-radios-inline2" name="status" value="not_complied_under_review">
                                    <label class="form-check-label" for="example-radios-inline2" style="font-size:13px;">Not Complied</label>
                                </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input" type="radio" id="example-radios-inline3" name="status" value="partially_complied_under_review">
                                     <label class="form-check-label" for="example-radios-inline3" style="font-size:13px;">Partially Complied</label>
                                 </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary" style="font-size:13px;margin-left:85% ">Update</button>
                            </div>
                        </div>
                    </form>
                    <!-- END Form Horizontal - Default Style -->

                </div>
            </div>
        </div>
    </div>
    <!-- END Horizontal -->



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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </body>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sel_control').select2({});
            $('#sel_monitor').select2({});
        });

    </script>

</html>
@endsection
