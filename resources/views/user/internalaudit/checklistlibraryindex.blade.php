@extends('layouts.backend')
@section('content')

<div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                    <li class="breadcrumb-item">Audit Management</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="">Checklist Library</a>
                                    </li>
                                    
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

<div class="content">
                @if(Session::has('msg'))
                    <div class="alert alert-success">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
     @include('user.internalaudit.checklistheader')
       
                        <!--analysis starts -->

                            <div class="row">
                                <div class="col-xl-7 d-flex flex-column">
                                    <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                            <div class="block-header block-header-default">
                                                <h8>Checklist-Department wise</h8>
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

                                <div class="col-xl-5 d-flex flex-column">
                                  <div class="row row-deck flex-grow-1">

                                    <!-- <div class="col-md-6 col-xl-12" style="height:150px;">
                                        <div class="block block-rounded d-flex flex-column">
                                            <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                                                <dl class="mb-0">
                                                    <dt class="font-size-h2 font-w700" style="color:#003366">{{$totalchecklist}}</dt>
                                                    <dd class="text-muted mb-0">Total Checklists</dd>
                                                </dl>
                                                <div class="item item-rounded bg-body">
                                                    <i class="fa fa-inbox font-size-h3"style="color:#003366"></i>
                                                </div>                  
                                            </div>              
                                        </div>
                                      </div> -->

                                      <div class="col-md-6 col-xl-12" style="height:150px;">
                                        <div class="block block-rounded d-flex flex-column">
                                            <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                                                <dl class="mb-0">
                                                    <dt class="font-size-h2 font-w700" style="color:#003366">{{$totalrecords}}</dt>
                                                    <dd class="text-muted mb-0">Total Records Created</dd>
                                                </dl>
                                                <div class="item item-rounded bg-body">
                                                    <i class="fa fa-inbox font-size-h3"style="color:#003366"></i>
                                                </div>                  
                                            </div>              
                                        </div>
                                      </div>

                                      <div class="col-md-6 col-xl-12" style="height:150px;">
                                        <div class="block block-rounded d-flex flex-column">
                                            <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                                                <dl class="mb-0">
                                                    <dt class="font-size-h2 font-w700 text-success">{{$completedrecords}}</dt>
                                                    <dd class="text-muted mb-0">Completed Records </dd>
                                                </dl>
                                                <div class="item item-rounded bg-body">
                                                    <i class="fa fa-inbox font-size-h3 text-success"></i>
                                                </div>                  
                                            </div>              
                                        </div>
                                     </div>

                                     <div class="col-md-6 col-xl-12" style="height:150px;">
                                     <div class="block block-rounded d-flex flex-column">
                                            <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between">
                                                <dl class="mb-0">
                                                    <dt class="font-size-h2 font-w700 text-danger">{{$pendingrecords}}</dt>
                                                    <dd class="text-muted mb-0">Pending Records </dd>
                                                </dl>
                                                <div class="item item-rounded bg-body">
                                                    <i class="fa fa-inbox font-size-h3 text-danger"></i>
                                                </div>                  
                                            </div>              
                                        </div>
                                    </div>



                                  </div>
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-xl-7 d-flex flex-column">
                                    <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                            <div class="block-header block-header-default">
                                                <h8>Stages-Department wise</h8>
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

                                <div class="col-xl-5 d-flex flex-column">
                                    <div style="height:500px;">
                                        <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                            <div class="block-header block-header-default">
                                                <h8>Non-conformance Summary</h8>
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
                                <div class="col-xl-6 d-flex flex-column mt-4">
                                    <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                            <div class="block-header block-header-default">
                                                <h8>Completed Records-Department wise</h8>
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
                                                <canvas class="js-chartjs-earnings" id="canvas4"></canvas>
                                            </div>
                                    </div>
                                </div>
       
                                <div class="col-xl-6 d-flex flex-column mt-4">
                                    <div class="block block-rounded flex-grow-1 d-flex flex-column">
                                            <div class="block-header block-header-default">
                                                <h8>Pending Records-Department wise</h8>
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
                                                <canvas class="js-chartjs-earnings" id="canvas5"></canvas>
                                            </div>
                                        </div>
                                </div>
                            </div>






                        <!--analysis ends -->
                       
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
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/auditschedule">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Audit Schedule
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditorslist">
                                    <div class="block-content text-center">
                                        <i class="fas fa-address-card fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br> Auditors List
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/internalaudit/auditlog">
                                    <div class="block-content text-center">
                                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>Audit Log
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="/user/internalaudit/closedaudits">
                                    <div class="block-content text-center">
                                        <i class="fas fa-check-circle fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Closed Audits
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/internalaudit/analysis'>
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<script>
        var depcode = <?php echo $depcode; ?>;
        var checklist = <?php echo $checklist;?>;
        var ok=<?php echo $ok;?>;
        var nca=<?php echo $nc_a;?>;
        var ncb=<?php echo $nc_b;?>;
        var pi=<?php echo $pi;?>;
        var na=<?php echo $na;?>;
        var total_ok=<?php echo $total_ok;?>;
        var total_nca=<?php echo $total_nca;?>;
        var total_ncb=<?php echo $total_ncb;?>;
        var total_pi=<?php echo $total_pi;?>;
        var total_na=<?php echo $total_na;?>;
        var completedrecordsdept=<?php echo $completedrecordsdept;?>;
        var pendingrecordsdept=<?php echo $pendingrecordsdept;?>;
        

        var barChartData1 = {
        labels: depcode
        , datasets: [{
                label: 'Checklist'
                , backgroundColor: "#003366"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: checklist
            },
        ]
    };

    
    var barChartData2 = {
        labels: depcode
        , datasets: [{
                label: 'OK'
                , backgroundColor: "#28a745"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: ok
            },
            {
                label: 'NC A'
                , backgroundColor: "#dc3545"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: nca             
            },
            {
                label: 'NC B'
                , backgroundColor: "#ffc107"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: ncb            
            },
            {
                label: 'PI'
                , backgroundColor: "#007bff"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: pi        
            },
            {
                label: 'NA'
                , backgroundColor: "#6c757d"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: na        
            }
        ]
    };

    var barChartData3 = {
        labels:  ["OK", "NC A", "NC B","PI","NA"]
        , datasets: [{
             data: [total_ok,total_nca,total_ncb,total_pi,total_na]
            , backgroundColor: [
                      'rgb(60,179,113)',
                      'rgb(255,99,71)',
                      'rgb(255, 205, 86)',
                      "#007bff",
                      "#6c757d"                    
                ],
            },                  
        ]};


    var barChartData4 = {
        labels: depcode
        , datasets: [{
                label: 'Completed Records'
                , backgroundColor: "#28a745"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: completedrecordsdept
            },
        ]
    };

    var barChartData5 = {
        labels: depcode
        , datasets: [{
                label: 'Pending Records'
                , backgroundColor: "#dc3545"
                , barThickness: 40
                , stack: 'Stack 0'
                , data: pendingrecordsdept
            },
        ]
    };


    var ctx = document.getElementById("canvas1").getContext("2d");
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
                , text: 'Checklist-Department wise'
            }
        }
    });

    var ctx = document.getElementById("canvas2").getContext("2d");
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
                , text: 'Stages-Department wise'
            }
        }
    });

    var ctx = document.getElementById("canvas3").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'doughnut'
        , data: barChartData3 
    });

    var ctx = document.getElementById("canvas4").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData4
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
                , text: 'Completed Records-Department wise'
            }
        }
    });

    var ctx = document.getElementById("canvas5").getContext("2d");
    window.myBar = new Chart(ctx, {
        type: 'bar'
        , data: barChartData5
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
                , text: 'Pending Records-Department wise'
            }
        }
    });
</script>
@endsection