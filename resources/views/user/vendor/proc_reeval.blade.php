@extends('layouts.multiselect')
@section('content')
<!doctype html>
<html lang="en">
<head>
    <!-- script for sending checkbox values to controller function -->
    <script>
        $(function() {
            $('#reeval').submit(function(e) {
                var url = e.target.action // get the target
                var formData = $(this).serialize() // get form data
                $.post(url, formData, function(response) { // send; response.data will be what is returned
                })
            })
        })

    </script>
    <link rel="stylesheet" href="{{asset('css/user/vendor/proc_reeval.css')}}">
    <link rel="stylesheet" href="{{asset('css/user/vendor/gauge_chart.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/flatpickr/flatpickr.min.css')}}">
</head>
<body>

    <!-- Page Content -->
    <div class="content" style="width:1100px;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">

                <!--shift header to white background-->
                <div class="d-flex ml-3">

                    <nav class="flex-sm-00-auto /*ml-sm-3*/" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">Vendor Management</li>
                            <li class="breadcrumb-item" aria-current="page" id="l1">
                                <a id="a1" onclick="" class="link-fx" href="/">Reevaluate</a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <!--end of shift header to white background-->
                @if(count($data)>0)
                @foreach($data as $dat)
                <form class="mb-5">
                    {{csrf_field()}}


                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded">
                        <div class="block-content block-content-full mt-2">
                            <div class="row">
                                <div class="col">
                                    <select class="form-control" name="theType" id="theType" required="true" style="font-size: 13px;width:230px;" onchange="changeType(this)">
                                        <option value="0" selected disabled>Choose Type </option></br>
                                        <option value="product" {{ $vendor_history && $vendor_history->type == 'product' ? 'selected' : '' }}>Product</option></br>
                                        <option value="service_provider" {{ $vendor_history && $vendor_history->type == 'service_provider' ? 'selected' : '' }}>Service Provider</option></br>
                                    </select>
                                    <span style="font-size:11px;color:red;" id="comment_type">Please choose one type: product or service</span>
                                </div>
                                <div class="col text-right">
                                    <button type="button" class="btn btn-primary dropdown-toggle" id="dropdown-default-primary" style="font-size:10px;width: 70px;height:30px;" data-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">

                                        <a href="#" class="dropdown-item" style="font-size:10px;">Edit Evaluation</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item" style="font-size:10px;">Review</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item" style="font-size:10px;">Share</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item" style="font-size:10px;">Export</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="/user/vendor/history?val={{ $id }}" class="dropdown-item" style="font-size:10px;" target="_blank">History</a>

                                    </div>

                                </div>

                            </div>


                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-3 d-none" id="evaluation_table">
                                <tbody>
                                    <tr>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:100px;background-color:#B1B1B1">Evaluation Period</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;">
                                            <input type="text" class="js-flatpickr form-control bg-white" id="from_date_to" name="from_date_to" data-mode="range" placeholder="{{ $vendor_history && $vendor_history->evaluation_date == null ? '' : 'Select Evaluation Period '}}" value="{{ $vendor_history ? $vendor_history->evaluation_period : '' }}">
                                        </td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;background-color:#B1B1B1">Evaluated Date</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $vendor_history ? $vendor_history->evaluation_date : $max_evaluation_date }}</td>
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

                            <table class="table table-bordered table-striped table-vcenter table-responsive js-dataTable-full d-none" id="main_table">
                                <input type="hidden" name="id" id="id" value="{{ $dat->id}}">
                                <input type="hidden" name="type" id="type" value="{{$t}}">


                                <thead id="end_user_thead">
                                    <tr class="z-index-class">
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">Sl.no</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">Questions</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">Excellent</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">Good</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">Average</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">Poor</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize;">N/A</th>
                                        <th class="text-center" style="font-size:10px;text-transform:capitalize">Weightage</th>
                                    </tr>
                                </thead>
                                <tbody id="hse_thead">

                                </tbody>

                            </table>

                            <button type="button" class="btn btn-danger ml-auto d-none" id="add_btn" onclick="add()">Add</button>
                            <p class="mt-2 d-none" id="show_added_person_name">Added person name is: {{ Auth::user()->name }}</p>

                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full mt-3 d-none" id="reviewed_table">
                                <tbody>
                                    <tr>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:100px;background-color:#B1B1B1">Reviewed by</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->reviewedByUser ? $dat->reviewedByUser->name . ' - ' . $dat->reviewedByUser->employeeid : '' }}</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;background-color:#B1B1B1">Reviewed Date</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->reviewed_at }}</td>
                                        <td class="text-left" style="padding:0.2rem!important;width:123px;"></td>
                                    </tr>

                                    <tr>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:100px;background-color:#B1B1B1">Approved by</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->approvedByUser ? $dat->approvedByUser->name . ' - ' . $dat->approvedByUser->employeeid : '' }}</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;background-color:#B1B1B1">Approved Date</td>
                                        <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->approved_at }}</td>
                                        <td class="text-left" style="padding:0.2rem!important;width:123px;"></td>
                                    </tr>


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
                            <div class="col-sm-8 ml-auto">
                                <button type="button" style="margin-left:550px;" class="btn btn-primary d-none" id="submit" onclick="submitForm()">Submit</button>
                            </div>
                        </div>
                        @endforeach
                        @endif
                </form>
            </div>

            <!-- END Dynamic Table Full -->


        </div>
        <!-- END Page Content -->
        <!-- END Main Container -->


        <!-- END Footer -->

        <!-- Apps Modal -->
        <!-- Opens from the modal toggle button in the header -->
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
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{ asset('js/plugins/chart.js/Chart.bundle.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/gauge.min.js')}}"></script>

<script>
    let history_id = "{{ $history_id }}";
    let history_type = "{{ $vendor_history ? $vendor_history->type : 0 }}";
    let reviewed = "{{ $reviewed }}";
    let overall_score = "{{ $score }}";
    overall_score = Number(overall_score)
    // draw score chart

    var opts = {
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
    var target = document.getElementById('score_chart'); // your canvas element

    var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!


    gauge.maxValue = 100; // set max gauge value
    gauge.setMinValue(0); // Prefer setter over gauge.minValue = 0
    gauge.animationSpeed = 32; // set animation speed (32 is default value)

    gauge.set(overall_score) // set actual value

</script>
<script type="text/javascript">
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    let answers = @json($answers);
    let counter = 1;
    let eval_end_text = "";
    let eval_end_NA_text = "";
    let eval_hse_text = "";
    let eval_hse_NA_text = "";
    let eval_proc_text = "";
    let eval_proc_NA_text = "";
    let hse_counter = 0;
    let proc_counter = 0;
    let eval_end_count = '{{ count($eval_end) }}';
    let eval_hse_count = '{{ count($eval_hse) }}';
    let eval_proc_count = '{{ count($eval_proc) }}';
    let count_all = parseInt(eval_end_count) + parseInt(eval_proc_count) + parseInt(eval_hse_count);

    function addSection(selector, text, length, filled_by) {
        $(selector).after(text);

        if (length) {
            $(selector).after(`<tr>
                <td colspan="10" class="font-weight-bold" style="font-size:12px">This section shall be filled by ${filled_by}</td>
            </tr>`);
        }
    }

    function ajaxToShowAllRows(theType, reviewed = 'evaluated') {
        $.ajax({
            data: {
                type: theType,
                id: '{{ request()->get('val') }}',
                'reviewed': reviewed
            }
            , type: 'GET'
            , url: "{{route('vendor.ajax')}}"
            , success: function(response) {

                count_all = response.data.eval_end.length + response.data.eval_hse.length + response.data.eval_proc.length;

                changeQstn(count_all);

                hse_counter = response.data.eval_end.length;

                if (response.data.eval_end.length) {

                    for (var i = 0; i < response.data.eval_end.length; i++) {
                        if (response.data.eval_end[i].score == 10) {
                            eval_end_NA_text = `<input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="${response.data.eval_end[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[i] == '10' ? 'checked' : '' }>`;

                        } else if (response.data.eval_end[i].score == 5) {
                            eval_end_NA_text = `<input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="${response.data.eval_end[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[i] == '5' ? 'checked' : '' }>`;

                        } else {
                            eval_end_NA_text = `<input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="${response.data.eval_end[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[i] == '15' ? 'checked' : '' }>`;

                        }

                        eval_end_text += `<tr>
                            <td class="text-center font-size-sm">${i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${response.data.eval_end[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="0.4"
                                    onclick="changeQstn(${count_all})" ${answers.length && answers[i]== '0.4' ? 'checked' : '' }></td>

                            <td class="text-center font-size-sm"><input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="0.3"
                                    onclick="changeQstn(${count_all})" ${answers.length && answers[i]== '0.3' ? 'checked' : '' }></td>

                            <td class="text-center font-size-sm"><input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="0.2"
                                    onclick="changeQstn(${count_all})" ${answers.length && answers[i]== '0.2' ? 'checked' : '' }></td>

                            <td class="text-center font-size-sm"><input type="radio" name="qstn${i}" data-id="${response.data.eval_end[i].id}" class="bigger-radio" value="0.1"
                                    onclick="changeQstn(${count_all})" ${answers.length && answers[i]== '0.1' ? 'checked' : '' }></td>

                            <td class="text-center font-size-sm">${eval_end_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score" name="score[]"
                                    value="${response.data.eval_end[i].score}">
                                <label for="score" style="font-size: 10px;" name="scoree"> ${response.data.eval_end[i].score}</label>
                            </td>
                        </tr>`;

                    }

                }


                if (response.data.eval_hse.length) {

                    proc_counter = response.data.eval_hse.length;

                    for (var i = 0; i < response.data.eval_hse.length; i++) {
                        if (response.data.eval_hse[i].score == 10) {
                            eval_hse_NA_text = `<input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio" value="${response.data.eval_hse[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i]== '10' ? 'checked' : '' }>`;
                        } else if (response.data.eval_hse[i].score == 5) {
                            eval_hse_NA_text = `<input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio" value="${response.data.eval_hse[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i]== '5' ? 'checked' : '' }>`;
                        } else {
                            eval_hse_NA_text = `<input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio" value="${response.data.eval_hse[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i]== '15' ? 'checked' : '' }>`;
                        }

                        eval_hse_text += `<tr>
                            <td class="text-center font-size-sm">${hse_counter + i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${response.data.eval_hse[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio"
                                    value="0.4" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i] == '0.4' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm"><input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio"
                                    value="0.3" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i] == '0.3' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm"><input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio"
                                    value="0.2" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i] == '0.2' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm"><input type="radio" name="qstn${hse_counter + i}" data-id="${response.data.eval_hse[i].id}" class="bigger-radio"
                                    value="0.1" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + i] == '0.1' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm">${eval_hse_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score" name="score[]"
                                    value="${response.data.eval_hse[i].score}">
                                <label for="score" style="font-size: 10px;" name="scoree"> ${response.data.eval_hse[i].score}</label>
                            </td>
                        </tr>`;

                    }

                }

                if (response.data.eval_proc.length) {

                    for (var i = 0; i < response.data.eval_proc.length; i++) {
                        if (response.data.eval_proc[i].score == 10) {
                            eval_proc_NA_text = `<input type="radio" name="qstn${hse_counter + proc_counter + i}" data-id="${response.data.eval_proc[i].id}" class="bigger-radio" value="${response.data.eval_proc[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='10' ? 'checked' : '' }>`;
                        } else if (response.data.eval_proc[i].score == 5) {
                            eval_proc_NA_text = `<input type="radio" name="qstn${hse_counter + proc_counter + i}" data-id="${response.data.eval_proc[i].id}" class="bigger-radio" value="${response.data.eval_proc[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='5' ? 'checked' : '' }>`;
                        } else {
                            eval_proc_NA_text = `<input type="radio" name="qstn${hse_counter + proc_counter + i}" data-id="${response.data.eval_proc[i].id}" class="bigger-radio" value="${response.data.eval_proc[i].score}" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='15' ? 'checked' : '' }>`;
                        }

                        eval_proc_text += `<tr>
                            <td class="text-center font-size-sm">${hse_counter + proc_counter + i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${response.data.eval_proc[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" data-id="${response.data.eval_proc[i].id}" name="qstn${hse_counter + proc_counter + i}"
                                    class="bigger-radio" value="0.4" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='0.4' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm"><input type="radio" data-id="${response.data.eval_proc[i].id}" name="qstn${hse_counter + proc_counter + i}"
                                    class="bigger-radio" value="0.3" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='0.3' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm"><input type="radio" data-id="${response.data.eval_proc[i].id}" name="qstn${hse_counter + proc_counter + i}"
                                    class="bigger-radio" value="0.2" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='0.2' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm"><input type="radio" data-id="${response.data.eval_proc[i].id}" name="qstn${hse_counter + proc_counter + i}"
                                    class="bigger-radio" value="0.1" onclick="changeQstn(${count_all})" ${answers.length && answers[hse_counter + proc_counter + i]=='0.1' ? 'checked' : '' }></td>
                            <td class="text-center font-size-sm">${eval_proc_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score" name="score[]"
                                    value="${response.data.eval_proc[i].score}">
                                <label for="score" style="font-size: 10px;" name="scoree"> ${response.data.eval_proc[i].score}</label>
                            </td>
                        </tr>`;

                    }

                }

                addSection("#end_user_thead", eval_end_text, response.data.eval_end.length, 'end user')
                addSection("#hse_thead", eval_proc_text, response.data.eval_proc.length, 'Supply chain Representative')
                addSection("#hse_thead", eval_hse_text, response.data.eval_hse.length, 'System & Compliance')

            }
            , error: function(err) {
                console.log(err);
            }
        });

    }


    // To show questions when clicking from history page
    if (history_id != '') {
        $('#comment_type').removeClass('d-block');
        $('#comment_type').addClass('d-none');

        ajaxToShowAllRows(history_type, '{{ $reviewed }}');
        changeType(history_type);
        $('#submit').addClass('d-block');
        $('#submit').removeClass('d-none');

    }


    if (history_id == '') {
        $('#show_chart').hide();
    }

    $('#comment_type').show();

    var i = 0;
    $("#newadd").click(function() {
        ++i;
        $("#newdynamictable").append('<tr><td><select class="form-control" name = "end[' + i + ']" id="end" required="true"style="font-size: 13px;width:230px;" >    @if(count($enduser)> 0 ) <option value="0" selected="" disabled style="font-size:13px;">Select</option>@foreach($enduser as $value)<option value=" {{$value->email}}"> {{ $value->name }}</option></br> @endforeach @endif</select> </td> <td><button type="button" class="btn btn-danger remove-tr">-</button></td></tr>');
    });
    $(document).on('click', '.remove-tr', function() {
        $(this).parents('tr').remove();
    });

    let qstnArr = [];

    let scoreArr = [];
    scoreArr = $("input[name='score[]']").map(function() {
        return this.value;
    }).get();


    function add() {
        $('#error').empty();

        let qstnArr = [];
        for (var i = 0; i < count_all; i++) {
            qstnArr[i] = $(`input[name='qstn${i}']:checked`).val();
        }

        let scoreArr = [];
        scoreArr = $("input[name='score[]']").map(function() {
            return this.value;
        }).get();

        $.ajax({
            data: {
                from_date_to: $("#from_date_to").val()
                , switch: 0
                , id: $("#id").val()
                , history_id: history_id
                , type: $('#type').val()
                , qstn: qstnArr
                , score: scoreArr
                , _token: CSRF_TOKEN
            },

            type: 'POST'
            , url: "{{route('vendor.ajax_score')}}"
            , success: function(response) {
                $('#show_added_person_name').removeClass('d-none');
                $('#show_added_person_name').addClass('d-block');
                $('#full_score').text(response.data.score);
                $('#error').addClass('d-none');
                $('#error').removeClass('d-block');
                gauge.set(response.data.score);
            }
            , error: function(err) {
                $('#error').removeClass('d-none');
                $('#error').addClass('d-block');
                for (let i in err.responseJSON.errors) {
                    $('#error').append(`<li>${err.responseJSON.errors[i]}</li>`)
                }
                $('#full_score').text(0);
                gauge.set(0);
            }
        });

    }


    function submitForm() {
        $('#error').empty();

        let qstnArr = [];
        for (var i = 0; i < count_all; i++) {
            qstnArr[i] = $(`input[name='qstn${i}']:checked`).val();
        }

        let scoreArr = [];
        scoreArr = $("input[name='score[]']").map(function() {
            return this.value;
        }).get();

        $.ajax({
            data: {
                from_date_to: $("#from_date_to").val()
                , switch: 1
                , id: $("#id").val()
                , history_id: history_id
                , reviewed: reviewed
                , type: $('#type').val()
                , theType: $('#theType').val()
                , qstn: qstnArr
                , score: scoreArr
                , _token: CSRF_TOKEN
            },

            type: 'POST'
            , url: "{{route('vendor.ajax_score')}}"
            , success: function(response) {
                $('#error').addClass('d-none');
                $('#error').removeClass('d-block');

                window.location.replace('{{ route("vendor.index")}}');
            }
            , error: function(err) {
                $('#error').removeClass('d-none');
                $('#error').addClass('d-block');
                for (let i in err.responseJSON.errors) {
                    $('#error').append(`<li>${err.responseJSON.errors[i]}</li>`)
                }
            }
        });
    }

    function changeQstn(count_all) {

        for (var i = 0; i < count_all; i++) {
            qstnArr[i] = $(`input[name='qstn${i}']:checked`).val();
        }


        let wholeArr = [];

        for (var j = 0; j < count_all; j++) {
            if (qstnArr[j]) {
                wholeArr[j] = qstnArr[j];
            }
        }
        var filtered = wholeArr.filter(function(el) {
            return el != null;
        });

        if (filtered.length == count_all) {
            $('#submit').removeClass('d-none');
            $('#submit').addClass('d-block');
        }

    }

    function changeType(self) {
        gauge.set(overall_score);

        $('#main_table').removeClass('d-none');
        $('#main_table').addClass('d-block');
        $("#main_table").find("tr:gt(0)").remove();

        $('#evaluation_table').removeClass('d-none');
        $('#evaluation_table').addClass('d-inline-table');

        $('#reviewed_table').removeClass('d-none');
        $('#reviewed_table').addClass('d-inline-table');

        $('#show_chart').show();


        $('#add_btn').removeClass('d-none');
        $('#add_btn').addClass('d-flex');


        $('#full_score').text(overall_score);
        $('#error').removeClass('d-block');
        $('#error').addClass('d-none');

        $('#submit').removeClass('d-block');
        $('#submit').addClass('d-none');

        if ($('#theType').val()) {
            $('#comment_type').hide();
        }


        hse_counter = 0;
        proc_counter = 0;

        eval_end_text = "";
        eval_end_NA_text = "";
        eval_hse_text = "";
        eval_hse_NA_text = "";
        eval_proc_text = "";
        eval_proc_NA_text = "";

        if ($(self).val()) {
            ajaxToShowAllRows($(self).val());
        }

    }

</script>


<script src="{{asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>

</html>
@endsection
