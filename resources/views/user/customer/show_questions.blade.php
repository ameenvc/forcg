@extends('layouts.simple')
@section('content')
    <!doctype html>
<html lang="en">
<head>
    <title></title>
    <link rel="stylesheet" href="{{asset('css/user/customer/show_question.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/vendor/gauge_chart.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/flatpickr/flatpickr.min.css')}}">
</head>
<body>

<p id="lead_to_top"></p>

<!-- Page Content -->
<div class="content" style="width:1100px;">
    <!-- Dynamic Table Full -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div class="alert alert-success d-none" id="success_message">
                <a class="close" data-dismiss="alert" style="cursor:pointer">×</a>
                Thank you for taking time to complete our customer satisfaction survey. We value your feedback and are committed to providing the best possible experience for our customers.
            </div>

            <!--shift header to white background-->
            <div class="d-flex m-auto">


                <nav class="flex-sm-00-auto m-auto" aria-label="breadcrumb">
                    <p class="font-weight-bold font-size-h3 bg-purple text-white pr-5 pl-5">Customer Satisfaction Survey</p>
                </nav>
                <div class="/*col text-right*/ mr-3">
                    {{-- <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width: 70px;height:30px;" data-toggle="dropdown">Actions</button>
                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                        <a href="#" class="dropdown-item" style="font-size:10px;">Share</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" style="font-size:10px;">Export</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" style="font-size:10px;">Print</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('customer.history', ['id' => $id]) }}" class="dropdown-item" style="font-size:10px;" target="_blank">History</a>
                    </div> --}}
                    <img src="{{ public_path('storage/logo/' . $filename . '.png')}}" width="100px" height="100px" />

                </div>
            </div>
            <!--end of shift header to white background-->
            @if($data)
                <form class="mb-5">
                    {{csrf_field()}}

                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded">
                        <div class="block-content block-content-full mt-2">
                            <table class="table table-bordered table-striped table-vcenter">
                                <tbody>
                                <tr>
                                    <td class="text-left font-w500">Customer Name</td>
                                    <td class="text-left white-td" id="customer_name">{{ $data->customer }}</td>
                                    <td class="text-left font-w500">Product/Services</td>
                                </tr>

                                <tr>
                                    <td class="text-left font-w500 gray-td">Location</td>
                                    <td class="text-left white-td" id="customer_location">{{ $data->location }}</td>
                                    <td class="text-left" rowspan="2">{{ $data->product }}</td>
                                </tr>

                                <tr>
                                    <td class="text-left font-w500">Survey Date</td>
                                    <td class="text-left white-td">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d h:i:s', strtotime(now())))->format('d M Y') }}</td>
                                </tr>
                                </tbody>

                            </table>

                            @if(count($errors) >0 )
                                @foreach($errors->all() as $error)
                                    <div class='alert alert-warning'>
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif


                            <ul class='alert alert-warning d-none' id="error"></ul>

                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->

                            <table
                                class="table table-bordered table-vcenter table-responsive js-dataTable-full"
                                id="main_table">
                                <input type="hidden" name="id" id="id" value="{{ $data->id}}">
                                <input type="hidden" name="type" id="type" value="product">


                                <thead id="end_user_thead">
                                <tr class="z-index-class">
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">Sl.no</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">
                                        Questions
                                    </th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">
                                        Excellent
                                    </th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">Good</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">Average
                                    </th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">Poor</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;">N/A</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize">Weightage
                                    </th>
                                </tr>
                                </thead>
                                @php $no = 0;@endphp
                                <tbody id="hse_thead">
                                @foreach($customer_details as $c)
                                    <tr>
                                        <td class="text-center font-size-sm">{{ $no + 1 }}</td>
                                        <td class="text-left font-size-sm"><label for="qp" style="font-size:10px;">{{ $c->question }}</label></td>
                                        <td class="text-center font-size-sm big" onclick="changeRadio(this, '{{ $customer_details_count }}')">
                                            <label>
                                                <input type="radio" name="qstn{{ $no }}" class="bigger-radio"
                                                       value="0.4"
                                                       onchange="changeQstn('{{ $customer_details_count }}')"
                                                >
                                            </label>
                                        </td>
                                        <td class="text-center font-size-sm big" onclick="changeRadio(this, '{{ $customer_details_count }}')">
                                            <label>
                                                <input type="radio" name="qstn{{ $no }}" class="bigger-radio"
                                                       value="0.3"
                                                       onchange="changeQstn('{{ $customer_details_count }}')"
                                                >
                                            </label>
                                        </td>
                                        <td class="text-center font-size-sm big" onclick="changeRadio(this, '{{ $customer_details_count }}')">
                                            <label>
                                                <input type="radio" name="qstn{{ $no }}" class="bigger-radio"
                                                       value="0.2"
                                                       onchange="changeQstn('{{ $customer_details_count }}')"
                                                >
                                            </label>
                                        </td>
                                        <td class="text-center font-size-sm big" onclick="changeRadio(this, '{{ $customer_details_count }}')">
                                            <label>
                                                <input type="radio" name="qstn{{ $no }}" class="bigger-radio"
                                                       value="0.1"
                                                       onchange="changeQstn('{{ $customer_details_count }}')"
                                                >
                                            </label>
                                        </td>
                                        <td class="text-center font-size-sm big" onclick="changeRadio(this, '{{ $customer_details_count }}')">
                                            <label>
                                                <input type="radio" name="qstn{{ $no }}" class="bigger-radio"
                                                       value="{{ $c->score }}"
                                                       onchange="changeQstn('{{ $customer_details_count }}')"
                                                >
                                            </label>
                                        </td>
                                        <td class="text-center " style="font-size:10px;width: 50px;">
                                            <input type="hidden" class="score" name="score[]" value="{{ $c->score }}">
                                            <label for="score" style="font-size: 10px;"> {{ $c->score }}</label>
                                        </td>
                                    </tr>
                                    @php $no++; @endphp
                                @endforeach

                                </tbody>

                            </table>

                            <br>

                            <div class="form-group row" id="show_chart">
                                <div class="col-4">
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
                                <div class="col-4 mt-4">
                                    <div class="text-center font-weight-bold" style="font-size:30px;" id="total_score">
                                        <canvas class="js-chartjs-donut" id="score_chart"></canvas>
                                        <p class="total-score">The actual score is <span id="full_score">0</span>%</p>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="comment">Comment:<span class="text-danger">*</span></label>
                                <textarea type="text" class="form-control" style="font-size: 13px;" id="comment" name="comment" rows="4" required="" placeholder="Enter Comment"></textarea>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col">
                                <label for="filled_by_name">Filled by(Name):<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="filled_by_name" id="filled_by_name" required/>
                            </div>
                            <div class="col">
                                <label for="filled_by_position">Filled by(Position):</label>
                                <input type="text" class="form-control" name="filled_by_position" id="filled_by_position"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="ml-auto mr-2">
                                <button type="button" class="btn btn-danger /*ml-auto d-flex*/" id="add_btn" onclick="add()">
                                    View score
                                </button>
                                <a type="button" href="#lead_to_top" style="/*margin-left:550px;*/" class="btn btn-primary d-none" id="submit" onclick="submitForm()">
                                    Submit
                                </a>
                            </div>
                        </div>
                    @endif
                </form>
        </div>

        <!-- END Dynamic Table Full -->
        <div>Powered by <a href="https://cgbod.com" target="_blank" class="text-underline">www.cgbod.com</a></div>
        {{-- <img src="{{ asset('images/cgbod_logo.png') }}" alt="cgbod logo" width="110" height="30"> --}}

    </div>
    <!-- END Page Content -->
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
</div>
<!-- END Apps Modal -->

</body>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/chart.js/Chart.bundle.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/gauge.min.js')}}"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let selected_type = "{{ $type }}";
    let count_all = "{{ $customer_details_count }}";
    let error_selector = $('#error');
    let success_selector = $('#success_message');
    let submit_selector = $('#submit');

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
    let target = document.getElementById('score_chart'); // your canvas element

    let gauge = new Gauge(target).setOptions(opts); // create sexy gauge!


    gauge.maxValue = 100; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 32; // set animation speed (32 is default value)

    //gauge.set(overall_score) // set actual value
    gauge.set(0);

</script>
<script>
    let qstnArr = [];

    function changeQstn(count_all) {

        for (let i = 0; i < count_all; i++) {
            qstnArr[i] = $(`input[name='qstn${i}']:checked`).val();
        }

        let wholeArr = [];

        for (let j = 0; j < count_all; j++) {
            if (qstnArr[j]) {
                wholeArr[j] = qstnArr[j];
            }
        }
        let filtered = wholeArr.filter(function (el) {
            return el != null;
        });

        if (filtered.length === Number(count_all)) {
            let submit_selector = $('#submit');
            submit_selector.removeClass('d-none');
            submit_selector.addClass('d-inline-block');
        }

    }

    function changeRadio(self, count_all) {
        $(self).find('label').find('input').prop('checked', true);
        changeQstn(count_all);
    }


    $('.big').on('click',function() {
        $('.hli').removeClass('hli');
        $(this).addClass('hli').find('input').prop('checked', true)
    });

    function add() {

        error_selector.empty();

        let qstnArr = [];
        for (let i = 0; i < count_all; i++) {
            qstnArr[i] = $(`input[name='qstn${i}']:checked`).val();
        }

        let scoreArr = [];
        scoreArr = $("input[name='score[]']").map(function() {
            return this.value;
        }).get();

        $.ajax({
            data: {
                switch: 0
                , id: $("#id").val()
                , type: selected_type
                , qstn: qstnArr
                , score: scoreArr
                , _token: CSRF_TOKEN
            },

            type: 'POST'
            , url: "{{route('customer.ajax_evaluation')}}"
            , success: function(response) {
                $('#full_score').text(response.data.score);
                error_selector.addClass('d-none');
                error_selector.removeClass('d-block');
                gauge.set(response.data.score);
            }
            , error: function(err) {
                error_selector.removeClass('d-none');
                error_selector.addClass('d-block');
                if(err.status === 500) {
                    $('#error').append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                } else if(err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
                $('#full_score').text(0);
                gauge.set(0);
            }
        });

    }

    function submitForm() {
        error_selector.empty();
        submit_selector.attr('disabled', 'disabled');

        let qstnArr = [];
        for (let i = 0; i < count_all; i++) {
            qstnArr[i] = $(`input[name='qstn${i}']:checked`).val();
        }

        let scoreArr = [];
        scoreArr = $("input[name='score[]']").map(function() {
            return this.value;
        }).get();

        $.ajax({
            data: {
                switch: 1
                , id: $("#id").val()
                , type: selected_type
                , qstn: qstnArr
                , score: scoreArr
                , filled_by_name: $('#filled_by_name').val()
                , filled_by_position: $('#filled_by_position').val()
                , customer_name: $('#customer_name').text()
                , customer_location: $('#customer_location').text()
                , comment: $('#comment').val()
                , _token: CSRF_TOKEN
            },

            type: 'POST'
            , url: "{{route('customer.ajax_evaluation')}}"
            , success: function(response) {
                error_selector.addClass('d-none');
                error_selector.removeClass('d-block');

                //Set score
                $('#full_score').text(response.data.score);
                gauge.set(response.data.score);

                submit_selector.attr('disabled', false);

                success_selector.removeClass('d-none');
                success_selector.addClass('d-block');
            }
            , error: function(err) {
                error_selector.removeClass('d-none');
                error_selector.addClass('d-block');
                if(err.status === 500) {
                    $('#error').append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                } else if(err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        $('#error').append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }

            }
        });
    }


</script>

<script src="{{asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>

</html>
@endsection

