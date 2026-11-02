@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center"  style="height:0px;">

                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Enterprise Risk</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Disapproving</a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="/user/riskmgmt">Back</a>
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

                                <div class="col-lg-8">
<form action="{{route('risk.disapprovingg')}}" method="post" >
    {{ csrf_field() }}
    {{-- <div class="jumbotron jumbotron-fluid"> --}}
            <div class="container">
              <h2 class="display-5" style="font-family:Arial;margin-left:200px">Reason for disapproving</h2>
              <textarea rows="10"  class="form-control" name="reason" required style="margin-left:100px"></textarea>
              <br>
              <input type="hidden" value="{{auth::user()->employeeid}}" name="review">
              <input type="hidden" name="id" value="{{$id}}">
              <div class="form-group row">
                <div class="col-sm-8 ml-auto">
                    <button type="submit" class="btn btn-primary">Submit</button>

                </div>
            </div>
            </div>
            {{-- <input type="submit" value="Disapprove" class="btn btn-danger"> --}}

        </form>

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
         {{-- <div class="col-6">
                                        <!-- CRM -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/risks/addrisk">
                                            <div class="block-content text-center">
                                                <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                Add Risk
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END CRM -->
                                    </div> --}}
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body" href="/user/riskmgmt">
                                            <div class="block-content text-center">
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
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/closedrisks">
                                            <div class="block-content text-center">
                                                <i class="fas fa-check-circle fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Risks
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Sales -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Payments -->
                                        <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/risks/analysis">
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

    </body>

    </html>
    @endsection
