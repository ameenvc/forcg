@extends('layouts.backend')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2 text-center text-sm-left">
                <div class="flex-sm-fill">
                    <h1 class="h3 font-w700 mb-2">
                        Environmental Aspect Dashboard
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
    <!-- Overview -->
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Pending Orders -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                    @if($highcount)
                    <a href="{{ route('enviaspects.all', ['type' => 'high'])}}">
                        <dt class="font-size-h2 font-w700">{{$highcount}}</dt>
                    </a>
                    @else
                    <dt class="font-size-h2 font-w700">{{$highcount}}</dt>
                    @endif
                        <dd class="text-muted mb-0">High Significant</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-shopping-cart font-size-h3 text-primary"></i>
                    </div>
                </div>
                {{-- <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                    <a class="font-w500 d-flex align-items-center" href="javascript:void(0)">
                        View all orders
                        <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                    </a>
                </div> --}}
            </div>
            <!-- END Pending Orders -->
        </div>
        <div class="col-sm-6 col-xl-3">
            <!-- New Customers -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($mediumcount)
                        <a href="{{ route('enviaspects.all', ['type' => 'medium'])}}">
                           <dt class="font-size-h2 font-w700">{{$mediumcount}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$mediumcount}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Moderate significant</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-users font-size-h3 text-primary"></i>
                    </div>
                </div>
                {{-- <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                    <a class="font-w500 d-flex align-items-center" href="javascript:void(0)">
                        View all customers
                        <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                    </a>
                </div> --}}
            </div>
            <!-- END New Customers -->
        </div>
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($lowcount)
                        <a href="{{ route('enviaspects.all', ['type' => 'low'])}}">
                           <dt class="font-size-h2 font-w700">{{$lowcount}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$lowcount}}</dt>
                        @endif
                        <dd class="text-muted mb-0">No significant</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-inbox font-size-h3 text-primary"></i>
                    </div>
                </div>
                {{-- <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                    <a class="font-w500 d-flex align-items-center" href="javascript:void(0)">
                        View all messages
                        <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                    </a>
                </div> --}}
            </div>
            <!-- END Messages -->
        </div>
        <div class="col-sm-6 col-xl-3">
            <!-- Conversion Rate -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        @if($Ccount)
                        <a href="{{ route('enviaspects.all', ['type' => 'c'])}}">
                           <dt class="font-size-h2 font-w700">{{$Ccount}}</dt>
                        </a>
                        @else
                        <dt class="font-size-h2 font-w700">{{$Ccount}}</dt>
                        @endif
                        <dd class="text-muted mb-0">Legal/regulatory requirement</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-primary"></i>
                    </div>
                </div>
                {{-- <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                    <a class="font-w500 d-flex align-items-center" href="javascript:void(0)">
                        View statistics
                        <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                    </a>
                </div> --}}
            </div>
            <!-- END Conversion Rate-->
        </div>
    </div>
    <!-- END Overview -->
    <div class="row">
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Environmental Aspects-Department Wise</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-earnings"  id="canvas"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Total number Vs Significance</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-sales" id="canvas1"></canvas></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Environmental Aspects-High Significance</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-earnings"  id="canvas2"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Environmental Aspects-Medium Significance</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-sales" id="canvas3"></canvas></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Environmental Aspects-No Significance</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-earnings"  id="canvas4"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="block block-rounded block-mode-loading-oneui">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Potential Environmental Impact</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                        <button type="button" class="btn-block-option">
                            <i class="si si-settings"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content p-0 text-center">
                    <!-- Chart.js is initialized in js/pages/be_pages_dashboard_v1.min.js which was auto compiled from _js/pages/be_pages_dashboard_v1.js) -->
                    <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                    <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-sales" id="canvas5"></canvas></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
    var process = <?php echo $process; ?>;
    var highp = <?php echo $highp; ?>;
    var mediump = <?php echo $mediump; ?>;
    var lowp = <?php echo $lowp; ?>;
    var highk = <?php echo $highk; ?>;
    var mediumk = <?php echo $mediumk; ?>;
    var lowk = <?php echo $lowk; ?>;
    var RDK = <?php echo $RDK; ?>;
    var APK = <?php echo $APK; ?>;
    var WPK = <?php echo $WPK; ?>;
    var NVK = <?php echo $NVK; ?>;
    var LCK = <?php echo $LCK; ?>;
    var ODK = <?php echo $ODK; ?>;


    // var process = "{{ $process }}";
    // var highp = "{{ $highp }}";
    // var mediump = "{{ $mediump }}";
    // var lowp = "{{$lowp}}";
    // var highk = "{{$highk}}";
    // var mediumk = "{{$mediumk}}";
    // var lowk = "{{$lowk}}";
    // var RDK = "{{$RDK}}";
    // var APK = "{{$APK}}";
    // var WPK = "{{$WPK}}";
    // var NVK = "{{$NVK}}";
    // var LCK = "{{$LCK}};
    // var ODK = "{{$ODK}}";
        var barChartData = {
            labels: process,
            datasets: [{
                label: 'High',
                backgroundColor: "red",
                data: highp
            },

            {
                label: 'Medium',
                backgroundColor: "orange",
                data: mediump
            },

            {
                label: 'No significant',
                backgroundColor: "#a6ff00",
                data: lowp
            }]
        };
        var barChartData1 = {
            labels: [
                'High',
                'Medium',
                'No significant'
                ],
            datasets: [{
                data: [highk,mediumk,lowk],
                backgroundColor: [
                'red',
                'orange',
                '#a6ff00'
                ],
                hoverOffset: 4

            }]
        };
        var barChartData2 = {
            labels: process,
            datasets: [{
                label: 'High',
                backgroundColor: "red",
                data: highp
            }]
        };
        var barChartData3 = {
            labels: process,
            datasets: [{
                label: 'Medium',
                backgroundColor: "orange",
                data: mediump
            }]
        };
        var barChartData4 = {
            labels: process,
            datasets: [{
                label: 'No significant',
                backgroundColor: "#a6ff00",
                data: lowp,
                fill: false,
                tension: 0.1
            }]
        };
        var barChartData5 = {
            labels: ['Resource Depletion','Air Pollution','Water Pollution','Noise and Vibrational Effect','Land Contamination','Ozone Depletion'],
            datasets: [{
                backgroundColor: ['violet','indigo','blue','green','yellow','orange'],
                data: [RDK,APK,WPK,NVK,LCK,ODK]
            }]
        };
        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'horizontalBar',
                data: barChartData,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',

                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Environmental Aspects-Department Wise'
                    }
                }
            });

            var ctx= document.getElementById("canvas1").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'doughnut',
                data: barChartData1,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Environmental Aspects-Total number Vs Significance'
                    }
                }
            });
            var ctx= document.getElementById("canvas2").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData2,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Environmental Aspects-High Significance'
                    }
                }
            });
            var ctx= document.getElementById("canvas3").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData3,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Environmental Aspects-Medium Significance'
                    }
                }
            });
            var ctx= document.getElementById("canvas4").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'line',
                data: barChartData4,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Environmental Aspects-No Significance'
                    }
                }
            });
            var ctx= document.getElementById("canvas5").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: 'doughnut',
                data: barChartData5,
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 1,
                            borderColor: '#c1c1c1',
                            borderSkipped: 'bottom',
                        }
                    },
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Potential Environmental Impact'
                    }
                }
            });
        };
    </script>


@endsection



