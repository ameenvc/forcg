@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

<body>

    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Audit Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Suggest Changes</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/auditmgmt">Back</a>
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
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <!-- <div class="row">
                                <div class="col-lg-8"> -->
                <!-- Form Horizontal - Default Style -->
                <form action="{{route('actionsystem.suggestchanges')}}" method="post">
                    {{csrf_field()}}
                    <div class="jumbotron jumbotron-fluid">
                        <div class="container">

                            <textarea rows="6" class="form-control" name="remarks" style="font-size:13px;" required></textarea>
                            <br>

                            <div class="form-group col-sm-12" style="display:inline-block;width:98.5%;">
                                <label class="col-form-label" style="font-size: 13px;">Actions required to be modified again?</label>
                                <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="yes2" name="action_modified" required><label style="font-size: 13px;">Yes</label>
                                <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="no2" name="action_modified"><label style="font-size: 13px;">No</label>
                            </div>

                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="submit" value="Submit" class="btn btn-primary" style="font-size:13px;margin-left:83% " onclick="return confirm('Are You Sure To Submit')">
                            <input type="reset" value="Reset" class="btn btn-primary" style="font-size:13px; ">
                        </div>
                    </div>
                </form>
                <!-- END Form Horizontal - Default Style -->

                <!-- </div>
                            </div> -->
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
                                    <a class="block block-rounded block-link-shadow bg-body" href="/user/auditmgmt">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="far fa-list-alt fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Register
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Products -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/folders">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-check-square fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Closed Issues
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/actionsystem/openissue">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                My Open NCRs
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>
                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/actionsystem/analysis">
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-clone fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Analysis
                                            </p>
                                        </div>
                                    </a>
                                    <!-- END Sales -->
                                </div>


                                <div class="col-6">
                                    <!-- Sales -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                        <div class="block-content text-center" style="height:130px">
                                            <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Audit Schedule Analysis
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
            <!-- </div>-->



        </div>
        <!-- END Apps Modal -->

</body>

</html>
@endsection
