@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

<body>

    <!-- Hero -->
    <!-- <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Health And Safety Risk</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Action to be taken</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div> -->
    <!-- END Hero -->

    <!-- Page Content -->
    <!-- <div class="tab-pane active" id="Register" role="tabpanel"> -->


    <!-- Horizontal -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Health And Safety Risk</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Initial Review</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/ohsrisk/folder/{{$type}}">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content" style="padding-top:30px;width:95%;margin-left:45px;">
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <?php $f=0; ?>
                        <form action="{{route('control_pro')}}" method="post">
                            {{csrf_field()}}

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="proc_no" style="font-size:13px;">Control Procedure Number</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="proc_no" name="proc_no" style="font-size:13px;" placeholder="Enter Here. " value="{{ old('proc_no') }}">
                                </div>
                            </div>
                            <br>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="procedure" style="font-size:13px;">Other Controls</label>
                                <div class="col-sm-8">
                                    <textarea type="text" rows="4" class="form-control" id="procedure" style="font-size:13px;" name="procedure" placeholder="Enter Here. " value="{{ old('procedure') }}"></textarea>
                                </div>
                            </div>

                            <br>
                            <input type="hidden" name="id" value="{{$id}}">
                            <input type="submit" value="Submit" style="font-size:13px; margin-left:81%;" class="btn btn-primary" onclick="return confirm('Are You Sure To Submit')">
                            <input type="reset" value="Reset" style="font-size:13px; " class="btn btn-primary">

                        </form>
                        <!-- END Form Horizontal - Default Style -->

                        <!-- </div>
                            </div> -->
                    </div>
                </div>
                <!-- END Horizontal -->
            </div>
        </div>
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

                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/folder">
                                        <div class="block-content text-center">
                                            <i class="fa fa-2x fa-clipboard-list text-primary"></i>
                                            <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Register
                                            </p>
                                        </div>
                                    </a>

                                </div>
                                <div class="col-6">
                                    <!-- Payments -->
                                    <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/ohsrisk/analysis">
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
        <!-- END Apps Modal -->
    </div>
    <!-- END Page Container -->



</body>

</html>
@endsection
