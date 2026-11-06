@extends('layouts.backend')
@section('content')
@include('shared.forms.risk-assets')






                <!-- Page Content -->
                <div class="content risk-form-page risk-form-shell">
    @include('shared.forms.risk-page-header', ['formTitle' => 'Request changes', 'formDescription' => 'Explain the changes required so the risk owner can respond.'])


                   <!-- Horizontal -->
                    <div class="block block-rounded">

                        <div class="block-content block-content-full">
                            <div class="row">

                                <div class="col-lg-8">
<form class="risk-module-form" action="{{route('risk.disapprovingg')}}" method="post" >
    {{ csrf_field() }}
    {{-- <div class="jumbotron jumbotron-fluid"> --}}
            <div class="container">
              <label for="risk-change-reason">Changes required</label>
              <textarea rows="10"  class="form-control" id="risk-change-reason" name="reason" required style=""></textarea>
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
    <!-- END Form Horizontal - Default Style -->

    </div>
    </div>
    </div>
    </div>
    <!-- END Horizontal -->



    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="risk-module-form modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
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




    @endsection
