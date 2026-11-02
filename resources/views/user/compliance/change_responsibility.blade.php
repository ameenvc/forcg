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
                        <a class="link-fx" href="">Change Responsibility</a>
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
                    <form class="mb-5" action="{{ route("user.compliance.post_change_responsibility") }}" enctype="multipart/form-data" method="POST" files="true">
                        {{csrf_field()}}

                        <input type="hidden" name="id" value="{{ $id }}" />

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="frequency" style="font-size:13px;">Review frequency </label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" value="{{$data->frequency}}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="authority" style="font-size:13px;">Regulatory authority</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" value="{{$data->regulatory->name}}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="issue" style="font-size:13px;">Legislative issue</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" value="{{$data->legalDocuments->name}}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="version" style="font-size:13px;">Version / year / dated</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" value="{{$data->version}}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="regulation" style="font-size:13px;">Act or regulation</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" value="{{$data->regulation}}" disabled>
                            </div>
                        </div>


                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="owner" style="font-size:13px;">Department</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" style="font-size:13px;" value="{{$data->dep ? $data->dep->departmentname : '' }}" disabled>
                            </div>
                        </div>

                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="aspect" style="font-size:13px;">Related aspect/risk number</label>
                            <div class="col-sm-12">
                                <textarea cols="10" rows="5" class="form-control" type="text" style="font-size:13px;" readonly>{{$data->aspect}} </textarea>
                            </div>
                        </div>
                        <div class="form-group" style="display:inline-block;width:49%;">
                            <label class="col-sm-12 col-form-label" for="requirement" style="font-size:13px;">Requirement</label>
                            <div class="col-sm-12">
                                <textarea cols="10" rows="5" class="form-control" id="requirement" style="font-size:13px;"  placeholder="Enter requirement" readonly>{{ $data->requirement }}</textarea>
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
@section('js')
<script>
    function reject(id) {
        $.ajax({
            url: '{{ route("user.compliance.reject")}}'
            , type: 'POST'
            , headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
            , data: {
                'compliance_id': id
            }
            , success: function() {
                window.location.href = "/user/compliancemgmt";
            }
            , error: function(err) {
                console.log(err);
            }
        , });

    }

</script>

@endsection
