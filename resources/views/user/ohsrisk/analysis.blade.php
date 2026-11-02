@extends('pages.datatables')


@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Health And Safety Risk</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="padding-top:30px;width:98%;margin-left:25px;">
    <!-- Horizontal -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="scrollable" style="height:500px;">
                <div class="block-content block-content-full" style="width:100%; backgroundcolor:fff">
                    <div class="container">
                        <div class="row" style="height:200px">

                            {!! $chart->container() !!}

                        </div>
                    </div>
                    <hr>
                    <br>
                    <div class="container">
                        <div class="row" style="height:200px">


                            {!! $chart2->container() !!}

                            <br>&emsp;


                        </div>
                    </div>
                    <hr>
                    <br>
                    <div class="container">
                        <div class="row" style="height:200px">

                            {!! $chart3->container() !!}

                        </div>
                    </div>
                    <br>
                    <div class="container">
                        <div class="row" style="height:200px">

                            {!! $chart4->container() !!}

                        </div>
                    </div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
                    {!! $chart->script() !!}
                    {!! $chart2->script() !!}
                    {!! $chart3->script() !!}
                    {!! $chart4->script() !!}
                </div>
            </div>
        </div>
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

<!-- END Page Container -->

@endsection
