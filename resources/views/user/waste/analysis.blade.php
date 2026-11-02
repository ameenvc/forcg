@extends('layouts.backend')
@section('back')

<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="/user/waste/register"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>

@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
            <div class="flex-sm-fill">
                <h1 class="h3 font-w700 mb-2">
                    Waste Dashboard
                </h1>
                <h2 class="h6 font-w500 text-muted mb-0">
                    Welcome <a class="font-w600" href="javascript:void(0)">{{Auth::user()->name}}</a>, everything looks great.
                </h2>
            </div>
            <div class="mt-3 mt-sm-0 ml-sm-3">
                <button type="button" class="btn btn-sm btn-alt-primary">
                    <i class="fa fa-cog"></i>
                </button>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm btn-alt-primary" id="dropdown-analytics-overview" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-calendar-alt"></i>
                        Last 30 days
                        <i class="fa fa-fw fa-angle-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right font-size-sm" aria-labelledby="dropdown-analytics-overview">
                        <a class="dropdown-item font-w500" href="javascript:void(0)">This Week</a>
                        <a class="dropdown-item font-w500" href="javascript:void(0)">Previous Week</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item font-w500" href="javascript:void(0)">This Month</a>
                        <a class="dropdown-item font-w500" href="javascript:void(0)">Previous Month</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        {{-- @if($nmdue)
                        <a href="{{ route('corrective.viewlist', ['type' => 'due'])}}">
                        <dt class="font-size-h2 font-w700">{{$nmdue}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$nmdue}}</dt>
                        @endif --}}
                        <dt class="font-size-h2 font-w700">{{ $total_transfer_request_count}}</dt>
                        <dd class="text-muted mb-0">Total Transfer Requests</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-user-clock" style="color:red;font-size:24px;"></i>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        {{-- @if($actions)
                        <a href="{{ route('corrective.viewlist', ['type' => 'awaiting'])}}">
                        <dt class="font-size-h2 font-w700">{{$actions}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$actions}}</dt>
                        @endif --}}
                        <dt class="font-size-h2 font-w700">{{ $transfer_requests_under_review_count}}</dt>
                        <dd class="text-muted mb-0">Transfer Requests Under review</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-spinner" style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        {{-- @if($proposed)
                        <a href="{{ route('corrective.viewlist', ['type' => 'proposed'])}}">
                        <dt class="font-size-h2 font-w700">{{$proposed}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$proposed}}</dt>
                        @endif --}}
                        <dt class="font-size-h2 font-w700">{{ $awaiting_sales_request_approval_count }}</dt>
                        <dd class="text-muted mb-0">Awaiting Sales Request Approval </dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-file-signature" style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        {{-- @if($awaitingclosure)
                        <a href="{{ route('corrective.viewlist', ['type' => 'closure'])}}">
                        <dt class="font-size-h2 font-w700">{{$awaitingclosure}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$awaitingclosure}}</dt>
                        @endif --}}
                        <dt class="font-size-h2 font-w700">{{$awaiting_disposal_request_approval_count}}</dt>
                        <dd class="text-muted mb-0">Awaiting Disposal Request Approval</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fas fa-circle-notch" style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 d-flex flex-column">
            <!-- Earnings Summary -->
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Type Chart</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas"></canvas>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 d-flex flex-column">
            <!-- Earnings Summary -->
            <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Year Chart</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas1"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
    var wastetypes = {!!$waste_types!!};
    var datatype = {!!$data_type!!};
    var datayear = {!!$data_year!!};
    var two_years_before = {!!$two_years_before!!};
    var barChartData1 = {
        labels: wastetypes
        , datasets: [{
            label: 'Number'
            , backgroundColor: "#003366"
            , data: datatype
        }]
    };
    var barChartData2 = {
        labels: two_years_before
        , datasets: [{
            label: 'Number'
            , backgroundColor: "#003366"
            , data: datayear
        }]
    };
    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar'
            , data: barChartData1
            , options: {
                elements: {
                    rectangle: {
                        borderWidth: 0
                        , borderColor: '#c1c1c1'
                        , borderSkipped: 'bottom'
                    , }
                }
                , scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
                , legend: {
                    position: 'bottom'
                }
                , responsive: true
                , title: {
                    display: true
                    , text: 'Waste Type Wise'
                }
            }
        });
        var ctx1 = document.getElementById("canvas1").getContext("2d");
        window.myBar = new Chart(ctx1, {
            type: 'bar'
            , data: barChartData2
            , options: {
                elements: {
                    rectangle: {
                        borderWidth: 0
                        , borderColor: '#c1c1c1'
                        , borderSkipped: 'bottom'
                    , }
                }
                , scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
                , legend: {
                    position: 'bottom'
                }
                , responsive: true
                , title: {
                    display: true
                    , text: 'Waste Year Wise'
                }
            }
        });
    };

</script>
@endsection
