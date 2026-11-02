@extends('layouts.backend')
@section('back')
<button type="button" style="width:34px;" class="btn btn-sm btn-dual mr-2">
    <a href="/user/calibrationmgmt"><i class="fa fa-arrow-left" style="color:black;"></i></a>
</button>
@endsection

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Calibration</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Analysis</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content" style="padding-top:30px;width:96%;">
    <div class="row row-deck">
        <div class="col-sm-6 col-xl-3">
            <!-- Messages -->
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <a href="/user/calibrationmgmt">
                          <dt class="font-size-h2 font-w700">{{$total}}</dt>
                        </a>
                        <dd class="text-muted mb-0">Total Instruments</dd>
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
                       <!--<a href="/user/calibrationmgmt?search=CalibratedandNeartoExpiry">-->
                       <a href="{{ route('calibration.viewlist', ['status' => 'calibrated'])}}">
                          <dt class="font-size-h2 font-w700">{{$calibrated}}</dt>
                       </a>
                        <dd class="text-muted mb-0">Calibrated</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <!--<a href="/user/calibrationmgmt?search=near to expiry">-->
                        <a href="{{ route('calibration.viewlist', ['status' => 'neartoexpiry'])}}">
                          <dt class="font-size-h2 font-w700">{{$neartoexpiry}}</dt>
                        </a> 
                        <dd class="text-muted mb-0">Near to Expiry</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                       <!--<a href="/user/calibrationmgmt?search=expired">-->
                       <a href="{{ route('calibration.viewlist', ['status' => 'expired'])}}">
                          <dt class="font-size-h2 font-w700">{{$expired}}</dt>
                        </a>
                        <dd class="text-muted mb-0">Expired</dd>
                    </dl>
                    <div class="item item-rounded bg-body">
                        <i class="fa fa-chart-line font-size-h3 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">

      <div class="col-xl-8 d-flex flex-column">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Internal Vs External Calibration-Department wise</h8>
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

      <div class="col-xl-4 d-flex flex-column">
          <div style="height:500px;">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Calibration Summary</h8>
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
                    <canvas class="js-chartjs-donut" id="canvas3"></canvas>
                </div>
            </div>
      </div>
      </div>
    </div>

   
    <div class="row">
      <div class="col-xl-8 d-flex flex-column">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Calibration Status-Department wise</h8>
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

      <div class="col-xl-4 d-flex flex-column">
         <div style="height:250px;">    
                <div class="block block-rounded flex-grow-1 d-flex flex-column" style="height: 250px">
                    <div class="block-header block-header-default">
                        <h8>Percentage of Calibrated</h8>
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
                            <div class="js-pie-chart pie-chart" data-percent="{{$percent}}%" data-line-width="3" data-size="100" data-bar-color="rgb(46,134,193)" data-track-color="#eeeeee">
                                <span>{{$percent}}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
          

    <div class="row">
       <div class="col-xl-8 d-flex flex-column mt-4">
          <div class="block block-rounded flex-grow-1 d-flex flex-column">
                <div class="block-header block-header-default">
                    <h8>Expired and Near to Expiry-Department wise</h8>
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
                    <canvas class="js-chartjs-earnings" id="canvas2"></canvas>
                </div>
            </div>
       </div> 
   </div>
</div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
        var depcode = <?php echo $depcode; ?>;
        var calib = <?php echo $calib; ?>;
        var neartoexpiry = <?php echo $near; ?>;
        var expired = <?php echo $exp; ?>;
        var internal = <?php echo $internal;?>;
        var external = <?php echo $external;?>;
        var totalcalib = <?php echo $calibrated;?>;
        var totalnear = <?php echo $neartoexpiry;?>;
        var totalexpired = <?php echo $expired;?>;

    var barChartData1 = {
        labels: depcode
        , datasets: [{
                label: 'External Calibration'
                , backgroundColor: "#b3d9ff"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: external
            },

            {
                label: 'Internal Calibration'
                , backgroundColor: "#0099CC"
                , barThickness: 40
                , data: internal
                , stack: 'Stack 0'
            }
        ]
    };


    var barChartData2 = {
        labels: depcode
        , datasets: [{
                label: 'Calibrated'
                , backgroundColor: "#00cc99"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: calib
            },
            {
                label: 'Near to expiry'
                , backgroundColor: "#fff0b9"
                , barThickness: 40
                , data: neartoexpiry
                , stack: 'Stack 0'
            },
            {
                label: 'Expired'
                , backgroundColor: "#ff6437"
                , barThickness: 40
                , data: expired
                , stack: 'Stack 0'
            }
        ]
    };


    var barChartData3 = {
        labels: depcode
        , datasets: [
            {
                label: 'Near to expiry'
                , backgroundColor: "#fff0b9"
                , data: neartoexpiry
                , stack: 'Stack 0'
            },
            {
                label: 'Expired'
                , backgroundColor: "#ff6437"
                , data: expired
                , stack: 'Stack 0'
            }
        ]
    };

    var barChartData4 = {
        labels:  ["Calibrated", "Near to expiry", "Expired"]
        , datasets: [{
             data: [totalcalib,totalnear,totalexpired]
            , backgroundColor: [
                      'rgb(60,179,113)',
                      'rgb(255, 205, 86)',
                      'rgb(255,99,71)'
                      
                ],
            },                  
        ]};




    window.onload = function() {   
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData1
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Internal Vs External calibration-Department wise'
            }
        }
    });


    var ctx = document.getElementById("canvas1").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData2
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Calibration Status-Department wise'
            }
        }
    });


    var ctx = document.getElementById("canvas2").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData3
        , options: {
            plugins: {
                title: {
                    display: true
                    , text: 'Chart.js Bar Chart - Stacked'
                }
            , }
            , responsive: true
            , interaction: {
                intersect: false
            , }
            , scales: {
                x: {
                    stacked: true
                , }
                , y: {
                    stacked: true
                }
            }
            , legend: {
                position: 'bottom'
            }
            , title: {
                display: true
                , text: 'Expired and Near to Expiry-Department Wise'
            }
        }
    });

    var ctx = document.getElementById("canvas3").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'doughnut'
        , data: barChartData4 
    });




    };
</script>

<script>
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>




@endsection
