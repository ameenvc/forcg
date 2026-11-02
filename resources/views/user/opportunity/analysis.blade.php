@extends('layouts.backend')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Opportunity</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($totalk)
                        <a href="{{ route('opportunity.oppindex', ['type' => 'all'])}}">
                            <dt class="font-size-h2 font-w700">{{ $totalk}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{ $totalk}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Total</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-inbox font-size-h3 text-primary"></i>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($Hkey)
                        <a href="{{ route('opportunity.oppindex', ['type' => 'High'])}}">
                            <dt class="font-size-h2 font-w700">{{ $Hkey }}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{ $Hkey }}</dt>
                        @endif
                        <dd class="text-muted mb-0">High</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($Mkey)
                        <a href="{{ route('opportunity.oppindex', ['type' => 'Moderate'])}}">
                            <dt class="font-size-h2 font-w700">{{ $Mkey}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{ $Mkey}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Moderate</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($Lkey)
                        <a href="{{ route('opportunity.oppindex', ['type' => 'Low'])}}">
                            <dt class="font-size-h2 font-w700">{{ $Lkey}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{ $Lkey}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Low</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 d-flex flex-column">
            <div class="" style="height:250px;background:white;padding-bottom:10px;">
                {!! $chart->container() !!}
            </div>
        </div>
        <div class="col-xl-6 d-flex flex-column">

            <div class="" style="height:250px;">
                {{-- {!! $chart1->container() !!} --}}
                <div class="block block-rounded flex-grow-1 d-flex flex-column" style="height: 250px">
                    <div class="block-header block-header-default">
                        <h8>Opportunity Review</h8>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                        <div class="col-6 col-xl-10 ">
                            <div class="js-pie-chart pie-chart" data-percent="{{ $percent }}" data-line-width="3" data-size="100" data-bar-color="rgb(46,134,193)" data-track-color="#eeeeee">
                                <span>{{ $percent}}%</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="p-1" style="height:300px;background:white;">
                {!! $chart1->container() !!}
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="p-1" style="height:300px;background:white;">
                {!! $chart5->container() !!}
            </div>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="p-1" style="height:300px;background:white;">
                {!! $chart6->container() !!}
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $chart->script() !!}
{!! $chart1->script() !!}
{!! $chart5->script() !!}
{!! $chart6->script() !!}
<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>
@endsection