@extends('admin.datatable')
@section('css_before')
<link rel="stylesheet" href="{{asset('css/user/vendor/proc_reeval.css')}}">
<link rel="stylesheet" href="{{asset('css/user/vendor/gauge_chart.css')}}">
<style>
    .dataTables_filter,
    .dataTables_info,
    .dataTables_length {
        display: none;
    }

</style>
@endsection

@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">


        <div class="block-header">
            <h3 class="block-title">Evaluation result table</h3>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/admin/vendorEvaluationDetails">Back</a>
                    </li>
                </ol>
            </nav>


        </div>
        <div class="block-content block-content-full">
            <div class="table-responsive">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">SL No.</th>
                            <th class="d-sm-table-cell text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Percent</th>
                            <th class="d-sm-table-cell text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Result</th>
                            <th class="d-sm-table-cell text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Action Comment</th>
                            <th class="text-center" style="width: 5%;font-size:10px;text-transform:capitalize">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 1; ?>
                        @foreach($ven as $c)

                        {{csrf_field()}}

                        <?php $id = $c->id  ?>
                        <tr>

                            <td style="font-size:10px" class="text-center">
                                <span>{{ $number }}</span>
                            </td>


                            <?php $number++; ?>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$c->percent}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$c->result}}</span>
                            </td>
                            <td style="font-size:10px" class="text-center">
                                <span>{{$c->comment}}</span>
                            </td>



                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{url('/admin/editres?val='.$id)}}" style="font-size:10px;" class="btn btn-sm btn-alt-primary" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <table class="table table-bordered">
                            <tbody class="bg-light small-font font-weight-bold">
                                <tr>
                                    <th scope="row" class="bg-excellent"></th>
                                    <td>89-100</td>
                                    <td>Excellent</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="bg-good"></th>
                                    <td>74-89</td>
                                    <td>Good</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="bg-fair"></th>
                                    <td>59-74</td>
                                    <td>Fair</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="bg-below-average"></th>
                                    <td>39-59</td>
                                    <td>Below Average</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="bg-poor"></th>
                                    <td>0-39</td>
                                    <td>Poor</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <div class="col-sm-6 mt-4">
                        <div class="text-center font-weight-bold" style="font-size:30px;" id="total_score">
                            <canvas class="js-chartjs-donut" id="score_chart"></canvas>
                        </div>
                    </div>

                    {{-- <div class="col-sm-4"></div> --}}

                </div>
            </div>
        </div>
    </div>
    <!-- END Dynamic Table Full -->
</div>
<!-- END Page Content -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{asset('js/plugins/chart.js/Chart.bundle.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('js/gauge.min.js')}}"></script>

<script>
    // draw score chart

    var opts = {
        angle: 0.01, // The span of the gauge arc
        lineWidth: 0.5, // The line thickness
        radiusScale: 1, // Relative radius
        pointer: {
            length: 0, // // Relative to gauge radius
            strokeWidth: 0 // The thickness
        }
        , limitMax: 100, // If false, max value increases automatically if value > maxValue
        limitMin: 0, // If true, the min value of the gauge will be fixed
        colorStart: '#BF091E', // Colors
        colorStop: '#ff00bf', // just experiment with them
        strokeColor: '#E0E0E0', // to see which ones work best for you
        generateGradient: true
        , highDpiSupport: true, // High resolution support
        staticZones: [{
                strokeStyle: "#BF091E"
                , min: 0
                , max: 39
            }
            , {
                strokeStyle: "#F06B1A"
                , min: 40
                , max: 59
            }
            , {
                strokeStyle: "#FDC40E"
                , min: 60
                , max: 74
            }
            , {
                strokeStyle: "#88C32E"
                , min: 74
                , max: 89
            }
            , {
                strokeStyle: "#1A8D38"
                , min: 90
                , max: 100
            }
        ]
        , staticLabels: {
            font: "10px sans-serif", // Specifies font
            labels: [39, 59, 74, 89, 100], // Print labels at these values
            color: "#000000", // Optional: Label text color
            fractionDigits: 0 // Optional: Numerical precision. 0=round off.
        }

    };
    var target = document.getElementById('score_chart'); // your canvas element

    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!


    gauge.maxValue = 100; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 32; // set animation speed (32 is default value)

    gauge.set(0); // set actual value

</script>


@endsection
