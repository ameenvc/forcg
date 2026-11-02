@extends('layouts.backend')


@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Environmental Aspect</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="padding-top:30px;width:100%;margin-left:25px;">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="scrollable" style="height:450px;">
                <div class="block-content block-content-full" style="width:100%; backgroundcolor:fff">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12" style="height:400px">
                                {!! $chart->container() !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6" style="height:400px">
                                {!! $chart1->container() !!}
                            </div>
                            <div class="col-sm-6" style="height:400px">
                                {!! $chart3->container() !!}
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-6" style="height:400px">
                                {!! $chart4->container() !!}
                            </div>
                            <div class="col-sm-6" style="height:400px">
                                {!! $chart5->container() !!}
                            </div>
                        </div>
                    </div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                    {!! $chart->script() !!}
                    {!! $chart1->script() !!}
                    {!! $chart3->script() !!}
                    {!! $chart4->script() !!}
                    {!! $chart5->script() !!}

                </div>
            </div>
        </div>
    </div>
</div>
</div>


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
@endsection
