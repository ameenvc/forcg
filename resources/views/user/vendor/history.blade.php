@extends('pages.datatables')
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
                        <li class="breadcrumb-item">Vendor Management</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Evaluation History</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/user/vendormgmt">Back</a>
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
                                <td class="text-left blue-td">Vendor ID</td>
                                <td class="text-center white-td">{{ $vendor->id }}</td>
                                <td class="text-left blue-td">Vendor Name</td>
                                <td class="text-center white-td">{{ $vendor->vendor_name }}</td>
                                <td class="text-left blue-td">Location</td>
                                <td class="text-center white-td">{{ $vendor->location }}</td>
                            </tr>

                            <tr>
                                <td class="text-left blue-td">Type</td>
                                <td class="text-center white-td">{{ $vendor->vendor_type }}</td>
                                <td class="text-left blue-td">Frequency</td>
                                <td class="text-center white-td">{{ $vendor->eval }}</td>
                                <td class="text-left blue-td">Added Year</td>
                                <td class="text-center white-td">{{ $vendor->created_at }}</td>
                            </tr>

                            <tr>
                                <td class="text-left blue-td">Product/Service</td>
                                <td class="text-center white-td">{{ $vendor->scope }}</td>
                                <td class="text-center white-td"></td>
                                <td class="text-center white-td"></td>
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
                                    <th class="text-center th-class">Evaluation date</th>
                                    <th class="text-center th-class">Evaluated by</th>
                                    <th class="text-center th-class">Approved by</th>
                                    <th class="text-center th-class">Overall score</th>
                                    <th class="text-center th-class">Status</th>
                                    <th class="text-center th-class">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $dat)
                                <tr>
                                    <td class="text-center second_table_td">{{ $dat->evaluation_date }}</td>
                                    <td class="text-center second_table_td">{{ $dat->evaluatedByUser ?
                                        $dat->evaluatedByUser->name . ' - ' . $dat->evaluatedByUser->employeeid : '' }}
                                    </td>
                                    <td class="text-center second_table_td">{{ $dat->approvedByUser ?
                                        $dat->approvedByUser->name . ' - ' . $dat->approvedByUser->employeeid : '' }}
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
                                    <td class="text-center second_table_td">
                                        @switch($dat->action_status)
                                        @case("awaiting_review")
                                        Awaiting review
                                        @break
                                        @case ("reviewed")
                                        Reviewed
                                        @break
                                        @case ("approved")
                                        Approved
                                        @break
                                        @endswitch
                                    </td>
                                    <div class="dropdown font-size-sm">
                                        <td>
                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$dat->id}}">
                                                    <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                </a>

                                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $dat->id }}">
                                                    @if($dat->reviewed_at == null)
                                                    <a href="{{ route('vendor.peval', ['val' => $dat->vendor_id, 'history_id' => $dat->id, 'reviewed' => 'under_review'])}}" style="font-size:10px" class="dropdown-item" target="blank">Review</a>
                                                    @elseif($dat->approved_at == null)
                                                    <a href="{{ route('vendor.peval', ['val' => $dat->vendor_id, 'history_id' => $dat->id, 'reviewed' => 'reviewed'])}}" style="font-size:10px" class="dropdown-item" target="blank">Approve</a>
                                                    @endif
                                                </div>
                                                <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $dat->id }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn({{$dat->id}})">
                                                    <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                </a>
                                            </div>

                                            <div id="viewModal{{$dat->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">

                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="block block-rounded">
                                                            <div class="block-content block-content-full mt-2">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <input type="hidden" value="{{ $dat->type }}" id="type{{$dat->id}}" />
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
                                                                            <a href="#" class="dropdown-item" style="font-size:10px;" target="_blank">History</a>

                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <input type="hidden" value="{{ implode(',', $dat->answers) }}" id="answers{{$dat->id}}" />

                                                                <table class="table table-bordered table-striped table-vcenter mt-3">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:100px;background-color:#B1B1B1">Evaluation Period</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;">
{{--                                                                                <label for="from_date_to"></label>--}}
                                                                                <input type="text" class="js-flatpickr form-control bg-white" id="from_date_to" name="from_date_to" value="{{ $dat->evaluation_period ?  : 'Select Evaluation Period' }}" data-mode="range" data-min-date="today" disabled>
                                                                            </td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;background-color:#B1B1B1">Evaluated Date</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->evaluation_date }}</td>
                                                                        </tr>

                                                                    </tbody>

                                                                </table>
                                                                <table class="table table-bordered table-striped table-vcenter" id="main_table">
                                                                    <thead id="end_user_thead{{ $dat->id }}">
                                                                        <tr>
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
                                                                    <tbody id="hse_thead{{ $dat->id }}">
                                                                    </tbody>

                                                                </table>

                                                                <table class="table table-bordered table-striped table-vcenter mt-3">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:100px;background-color:#B1B1B1">Reviewed by</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->evaluatedByUser ? $dat->evaluatedByUser->employeeid : ''}}</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;background-color:#B1B1B1">Reviewed Date</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->reviewed_at }}</td>
                                                                            <td class="text-left" style="padding:0.2rem!important;width:123px;"></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:100px;background-color:#B1B1B1">Approved by</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->approvedByUser ? $dat->approvedByUser->employeeid : ''}}</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;background-color:#B1B1B1">Approved Date</td>
                                                                            <td class="text-left" style="font-size:10px;padding:0.2rem!important;width:123px;">{{ $dat->approved_at }}</td>
                                                                            <td class="text-left" style="padding:0.2rem!important;width:123px;"></td>
                                                                        </tr>


                                                                    </tbody>


                                                                </table>

                                                                <br>



                                                                <div class="form-group row">

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
                                                                            <canvas class="js-chartjs-donut" id="score_chart{{ $dat->id }}"></canvas>
                                                                            <input type="hidden" value="{{ $dat->overall_score }}" id="overall_score{{ $dat->id }}" />
                                                                            <p class="total-score ml-5">The actual score is {{ $dat->overall_score }} %</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-4"></div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
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


    </div>
    <!-- END Page Content -->
    {{-- </main> --}}
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


</body>
<script src="{{asset('js/jquery.min.js')}}"></script>

<script src="{{asset('js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script src="{{asset('js/plugins/chart.js/Chart.bundle.min.js')}}"></script>

<script type="text/javascript" src="{{ asset('js/gauge.min.js')}}"></script>
<script>
    let answers = "";
    let eval_end_text = "";
    let eval_end_NA_text = "";
    let eval_hse_text = "";
    let eval_hse_NA_text = "";
    let eval_proc_text = "";
    let eval_proc_NA_text = "";
    let eval_end_section_text = "";
    let eval_hse_section_text = "";
    let eval_proc_section_text = "";
    let hse_counter = 0;
    let proc_counter = 0;
    let eval_end_count = '{{ count($eval_end) }}';
    let eval_hse_count = '{{ count($eval_hse) }}';
    let eval_proc_count = '{{ count($eval_proc) }}';
    let count_all = parseInt(eval_end_count) + parseInt(eval_proc_count) + parseInt(eval_hse_count);



    function addSection(selector, text) {
        $(selector).empty().append(text);
    }


    function ajaxToShowAllRows(theType, id) {
        eval_end_text = "";
        eval_end_NA_text = "";
        eval_hse_text = "";
        eval_hse_NA_text = "";
        eval_proc_text = "";
        eval_proc_NA_text = "";
        eval_end_section_text = "";
        eval_hse_section_text = "";
        eval_proc_section_text = "";

        hse_counter = 0;
        proc_counter = 0;
        answers = $('#answers' + id).val();
        answers = answers.split(",");

        $.ajax({
            data: {
                type: theType
            }
            , type: 'GET'
            , url: "{{route('vendor.ajax')}}"
            , success: function(response) {
                count_all = response.data.eval_end.length + response.data.eval_hse.length + response.data.eval_proc.length;

                hse_counter = response.data.eval_end.length;

                if (response.data.eval_end.length) {

                    eval_end_section_text = `<tr>
                        <td colspan="10" class="font-weight-bold" style="font-size:12px">This section shall be filled by end user</td>
                    </tr>`;


                    for (let i = 0; i < response.data.eval_end.length; i++) {
                        if (response.data.eval_end[i].score === 10) {
                            eval_end_NA_text = `<input type="radio" class="bigger-radio" ${answers[i] === '10' ? 'checked' : '' } disabled>`;
                        } else if (response.data.eval_end[i].score === 5) {
                            eval_end_NA_text = `<input type="radio" class="bigger-radio" ${answers[i] === '5' ? 'checked' : '' } disabled>`;
                        } else {
                            eval_end_NA_text = `<input type="radio" class="bigger-radio" ${answers[i] === '15' ? 'checked' : '' } disabled>`;
                        }

                        eval_end_text += `<tr>
                            <td class="text-center font-size-sm">${i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${response.data.eval_end[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.4' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] ==='0.3' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.2' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[i] === '0.1' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm">${eval_end_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score"
                                    value="${response.data.eval_end[i].score}">
                                <label for="score" style="font-size: 10px;"> ${response.data.eval_end[i].score}</label>
                            </td>
                        </tr>`;
                    }

                }


                if (response.data.eval_hse.length) {
                    eval_hse_section_text = `<tr>
                         <td colspan="10" class="font-weight-bold" style="font-size:12px">This section shall be filled by HSE Representative</td>
                     </tr>`;


                    proc_counter = response.data.eval_hse.length;

                    for (let i = 0; i < response.data.eval_hse.length; i++) {
                        if (response.data.eval_hse[i].score === 10) {
                            eval_hse_NA_text = `<input type="radio" class="bigger-radio" ${answers[hse_counter + i] === '10' ? 'checked' : '' } disabled>`;
                        } else if (response.data.eval_hse[i].score === 5) {
                            eval_hse_NA_text = `<input type="radio" class="bigger-radio" ${answers[hse_counter + i] === '5' ? 'checked' : '' } disabled>`;
                        } else {
                            eval_hse_NA_text = `<input type="radio" class="bigger-radio" ${answers[hse_counter + i] === '15' ? 'checked' : '' } disabled>`;
                        }

                        eval_hse_text += `<tr>
                            <td class="text-center font-size-sm">${hse_counter + i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${response.data.eval_hse[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + i] === '0.4' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + i] === '0.3' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + i] ==='0.2' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + i] === '0.1' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm">${eval_hse_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score"
                                    value="${response.data.eval_hse[i].score}">
                                <label for="score" style="font-size: 10px;"> ${response.data.eval_hse[i].score}</label>
                            </td>
                        </tr>`;

                    }

                }

                if (response.data.eval_proc.length) {
                    eval_proc_section_text = `<tr>
                         <td colspan="10" class="font-weight-bold" style="font-size:12px">This section shall be filled by Procurement Representative</td>
                     </tr>`;


                    for (let i = 0; i < response.data.eval_proc.length; i++) {
                        if (response.data.eval_proc[i].score === 10) {
                            eval_proc_NA_text = `<input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '10' ? 'checked' : '' } disabled>`;
                        } else if (response.data.eval_proc[i].score === 5) {
                            eval_proc_NA_text = `<input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '5' ? 'checked' : '' } disabled>`;
                        } else {
                            eval_proc_NA_text = `<input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '15' ? 'checked' : '' } disabled>`;
                        }

                        eval_proc_text += `<tr>
                            <td class="text-center font-size-sm">${hse_counter + proc_counter + i + 1}</td>
                            <td class="text-left font-size-sm"><label for="qp"
                                    style="font-size:10px;">${response.data.eval_proc[i].question}</label></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '0.4' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '0.3' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '0.2' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm"><input type="radio" class="bigger-radio" ${answers[hse_counter + proc_counter + i] === '0.1' ? 'checked' : '' } disabled></td>
                            <td class="text-center font-size-sm">${eval_proc_NA_text}</td>
                            <td class="text-center " style="font-size:10px;width: 50px;"><input type="hidden" class="score"
                                    value="${response.data.eval_proc[i].score}">
                                <label for="score" style="font-size: 10px;"> ${response.data.eval_proc[i].score}</label>
                            </td>
                        </tr>`;

                    }

                }

                addSection("#hse_thead" + id, eval_end_section_text + eval_end_text + eval_hse_section_text + eval_hse_text + eval_proc_section_text + eval_proc_text, response.data.eval_end.length, 'end user')

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
