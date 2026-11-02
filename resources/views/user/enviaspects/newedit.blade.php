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
                        <li class="breadcrumb-item">Environmental Aspect</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Edit Activity</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/enviaspects/listofaspects/{{Session::get('envidept')}}">Back</a>
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
                <div class="row">

                    <div class="col-lg-10">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{url('/user/enviaspects/editnew')}}" method="POST">


                            {{csrf_field()}}
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


                            @foreach($data as $dat)


                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="objid">Activity</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="objid" name="activity" value="{{$dat->activity}}">
                                </div>
                            </div>
                            <div class="form-group" style="display:inline-block;width:49%;">
                                <label class="col-sm-12 col-form-label" style="font-size: 13px;" for="riskdec">Location</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="location" style="font-size: 13px;" name="location" value="{{$dat->location}}">
                                </div>
                            </div>
                    </div>






                    <input class="form-control" type="hidden" style="font-size: 13px;" name="id" id="id" readonly value="{{$dat->Eid}}">

                </div>

                @endforeach

                <br>

                <div class="form-group row">
                    <div class="col-sm-8 ml-auto">
                        <button type="submit" class="btn btn-primary" style="font-size: 13px;margin-left:59.5%;margin-top:-70px;" onclick="return confirm('Do you want to proceed?')">Submit</button>
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
                        <h3 class="block-title">OPTIONS</h3>
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/enviaspects/folder">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>




                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/enviaspects/analysis">
                                    <div class="block-content text-center" style="height:130px">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Analysis
                                        </p>
                                    </div>
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->

</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>




</html>
@endsection
