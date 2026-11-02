{{--@if(Auth::check())--}}
@extends('pages.no_auth_datatables')
{{--@else--}}
{{--@extends('layouts.simple')--}}
{{--@endif--}}

@section('content')
    <!doctype html>
<html lang="en">

<head>
    <title></title>
    <link rel="stylesheet" href="{{asset('css/user/vendor/history.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/vendor/gauge_chart.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/flatpickr/flatpickr.min.css')}}">
</head>

<body>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0;">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Customer Satisfaction</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Evaluation History</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="/user/customermgmt">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">

    <form class="mb-5" method="POST" id="eval">
        {{csrf_field()}}
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">

            <div class="block-content block-content-full">

                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter">
                    <tbody>
                    <tr>
                        <td class="text-left blue-td">Customer ID</td>
                        <td class="text-center white-td">{{ $customer->id }}</td>
                        <td class="text-left blue-td">Customer Name</td>
                        <td class="text-center white-td">{{ $customer->customer }}</td>
                        <td class="text-left blue-td">Location</td>
                        <td class="text-center white-td">{{ $customer->location }}</td>
                    </tr>

                    <tr>
                        <td class="text-left blue-td">Added Year</td>
                        <td class="text-center white-td">{{ $customer->created_at }}</td>
                        <td class="text-left blue-td">Scope</td>
                        <td class="text-center white-td">{{ $customer->product }}</td>
                        <td class="text-center white-td"></td>
                        <td class="text-center white-td"></td>
                    </tr>

                    </tbody>

                </table>
                @if(count($data) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                            <tr class="z-index-class">
                                <th class="text-center th-class">Survey date</th>
                                <th class="text-center th-class">Filled by(Name)</th>
                                <th class="text-center th-class">Filled by(Position)</th>
                                <th class="text-center th-class">Overall score</th>
                                <th class="text-center th-class">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $dat)
                                <tr>
                                    <td class="text-center second_table_td">{{ $dat->evaluation_date }}</td>
                                    <td class="text-center second_table_td">
                                        {{ $dat->filled_by_name }}
                                    </td>
                                    <td class="text-center second_table_td">
                                        {{ $dat->filled_by_position }}
                                    </td>
                                    <td class="text-center second_table_td">
                                        @switch($dat->status)
                                            @case("poor")
                                                Poor({{ $dat->overall_score }})
                                                @break
                                            @case ("below_average")
                                                Below Average({{ $dat->overall_score }})
                                                @break
                                            @case ("fair")
                                                Fair({{ $dat->overall_score }})
                                                @break
                                            @case ("good")
                                                Good({{ $dat->overall_score }})
                                                @break
                                            @case ("excellent")
                                                Excellent({{ $dat->overall_score }})
                                                @break
                                        @endswitch
                                    </td>
                                    <div class="dropdown font-size-sm">
                                        <td>
                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $dat->id }}"
                                                   data-toggle="tooltip" data-placement="top" title="View"
                                                   onclick="ViewBtn({{$dat->id}})">
                                                    <i class="far fa-eye text-center "
                                                       style="font-size:10px;cursor:pointer"></i>
                                                </a>
                                            </div>

                                            <div id="viewModal{{$dat->id}}" class="modal fadebd-example-modal-lg"
                                                 tabindex="-1" role="dialog" aria-hidden="false">

                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="block block-rounded">
                                                            <div class="block-content block-content-full mt-2">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input type="hidden" value="{{ $dat->type }}"
                                                                               id="type{{$dat->id}}"/>
                                                                    </div>
                                                                    <div class="col text-right">
                                                                        <button type="button"
                                                                                class="btn btn-primary dropdown-toggle"
                                                                                id="dropdown-default-primary"
                                                                                style="font-size:10px;width: 70px;height:30px;"
                                                                                data-toggle="dropdown">Actions
                                                                        </button>
                                                                        <div class="dropdown-menu font-size-sm"
                                                                             aria-labelledby="dropdown-dropleft-outline-dark">

                                                                            <a href="#" class="dropdown-item" style="font-size:10px;">Share</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a href="#" class="dropdown-item" style="font-size:10px;">Export</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a href="#" class="dropdown-item" style="font-size:10px;">Print</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a href="#" class="dropdown-item" style="font-size:10px;" target="_blank">History</a>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <input type="hidden"  value="{{ implode(',', $dat->answers) }}" id="answers{{$dat->id}}"/>

                                                                <table
                                                                    class="table table-bordered table-striped table-vcenter mt-3"
                                                                    id="main_table">
                                                                    <thead id="end_user_thead{{ $dat->id }}">
                                                                    <tr>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            Sl.no
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            Questions
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            Excellent
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            Good
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            Average
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            Poor
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize;">
                                                                            N/A
                                                                        </th>
                                                                        <th class="text-center"
                                                                            style="font-size:10px;text-transform:capitalize">
                                                                            Weightage
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody id="thead{{ $dat->id }}">
                                                                    </tbody>

                                                                </table>

                                                                <br>


                                                                <div class="form-group row">

                                                                    <div class="col-4">
                                                                        <table class="table table-bordered">
                                                                            <tbody
                                                                                class="bg-light small-font font-weight-bold">
                                                                            <tr>
                                                                                <th scope="row"
                                                                                    class="bg-excellent"></th>
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
                                                                                <th scope="row"
                                                                                    class="bg-below-average"></th>
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

                                                                    <div class="col-4 mt-4">
                                                                        <div class="text-center font-weight-bold"
                                                                             style="font-size:30px;" id="total_score">
                                                                            <canvas class="js-chartjs-donut"
                                                                                    id="score_chart{{ $dat->id }}"></canvas>
                                                                            <input type="hidden"
                                                                                   value="{{ $dat->overall_score }}"
                                                                                   id="overall_score{{ $dat->id }}"/>
                                                                            <p class="total-score ml-5">The actual score
                                                                                is {{ $dat->overall_score }} %</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-4"></div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-dark"
                                                                        data-dismiss="modal">Close
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </div>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>

                    </div>
        @endif

    </form>
</div>

<!-- END Dynamic Table Full -->


<!-- END Page Content -->
{{-- </main> --}}
<!-- END Main Container -->


<!-- END Footer -->

<!-- Apps Modal -->
<!-- Opens from the modal toggle button in the header -->
<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
     aria-hidden="true">
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
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/vendormgmt">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Products -->
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/vendor/report">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Vendor report
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Apps Modal -->


</body>
<script src="{{asset('js/jquery.min.js')}}"></script>

<script src="{{asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script src="{{asset('js/plugins/chart.js/Chart.bundle.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('js/gauge.min.js')}}"></script>

<script>
    let answers = "";
    let eval_NA_text = "";
    let eval_section_text = "";

    let counter = 0;


    function addSection(selector, text) {
        $(selector).empty().append(text);
    }

    function ajaxToShowAllRows(theType, id) {
        eval_NA_text = "";
        eval_NA_text = "";
        eval_section_text = "";

        counter = 0;
        answers = $('#answers' + id).val();
        answers = answers.split(",");

        $.ajax({
            data: {
                type: theType
            }
            , type: 'GET'
            , url: "{{route('customer.ajax_get_customer_details')}}"
            , success: function(response) {

                let customer_details = response.data.customer_details;

                count_all = customer_details.length;

                counter = customer_details.length;

                if (customer_details.length) {


                    for (let i = 0; i < customer_details.length; i++) {
                        if (customer_details[i].score === 10) {
                            eval_NA_text = `<input type="radio" class="bigger-radio" ${answers[i] ==='10' ? 'checked' : '' } disabled>`;
                        } else if (customer_details[i].score === 5) {
                            eval_NA_text = `<input type="radio" class="bigger-radio" ${answers[i] === '5' ? 'checked' : '' } disabled>`;
                        } else {
                            eval_NA_text = `<input type="radio" class="bigger-radio" ${answers[i]=== '15' ? 'checked' : '' } disabled>`;
                        }

                        eval_section_text += `<tr>
                            <td class="text-center font-size-sm">${i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${customer_details[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.4' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.3' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.2' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.1' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm">${eval_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score"
                                    value="${customer_details[i].score}">
                                <label for="score" style="font-size: 10px;"> ${customer_details[i].score}</label>
                            </td>
                        </tr>`;
                    }

                }

                addSection("#thead" + id, eval_section_text)

            }
            , error: function(err) {
                console.log(err);
            }
        });

    }

</script>
<script>
    // draw score chart

    let opts = {
        angle: 0.01, // The span of the gauge arc
        lineWidth: 0.5, // The line thickness
        radiusScale: 1, // Relative radius
        pointer: {
            length: 0.4, // // Relative to gauge radius
            strokeWidth: 0.050, // The thickness
            color: '#000000' // Fill color
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

</script>
<script>
    function ViewBtn(id) {
        $('#viewModal' + id).modal('show');

        ajaxToShowAllRows($('#type' + id).val(), id)
        let target = document.getElementById('score_chart' + id); // your canvas element

        let gauge = new Gauge(target, 300, 150).setOptions(opts); // create sexy gauge!

        $('#poor_label' + id).text('Poor')
        $('#below_average_label' + id).text('Below Average')
        $('#fair_label' + id).text('Fair')
        $('#good_label' + id).text('Good')
        $('#excellent_label' + id).text('Excellent')


        gauge.maxValue = 100; // set max gauge value
        gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
        gauge.animationSpeed = 32; // set animation speed (32 is default value)

        gauge.set($("#overall_score" + id).val()); // set actual value
    }

</script>

</html>

@endsection


