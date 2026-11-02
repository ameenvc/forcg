@extends('pages.datatables')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

        <i class="fa fa-print  fa-1x" style="color:grey"></i>

    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{ url('/user/convert/file/' . 'tvalue1') }}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel </button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{ url('/user/vendor/addvendor') }}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
    <style>
        body {
            overflow-y: auto;
        }

        .popover {
            background: #ffff00;
        }

        .bs-popover-right {
            .arrow::after {
                border-right-color: #ffff00;
            }
        }



        .badge-poor {
            background: #BF0A1E;
        }

        .badge-below-average {
            background: #EF6B19;
        }

        .badge-fair {
            background: #FDC40D;
        }

        .badge-good {
            background: #89C32E;
        }

        .badge-excellent {
            background: #1A8C37;
        }

        .label-invalid {
            color: red;
            font-size: 13px;
        }
        .btn-request-evaluation {
            position: absolute;
            right: 20rem;
            z-index: 2;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        @media only screen and (min-width: 2020px) {
            .btn-request-evaluation {
                right: 28rem;
            }
        }
        #DataTables_Table_0_wrapper .row:nth-child(2) .col-sm-12:first-child {
            z-index: 3;
        }
    </style>

</head>

<body>

    <input type="hidden" value="a1" id="tabselected">
    <!-- Page Content -->

    <div class="content" style="width:100%;">
        <!-- Dynamic Table Full -->
        <div class="block block-rounded" style="margin-left:-1%;">

            <div class="block-content block-content-full">
                <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                    <button class="btn btn-primary btn-sm btn-request-evaluation js-request-evaluation" disabled>Request Evaluation</button>
                    <table class="move" width="15%" style="margin-left:670px">
                        <tr>

                            <th>
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt" style="margin-right:-200px;margin-bottom:-250px;">
                                        <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Vendor
                                            Management</li>
                                        <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                            <a class="link-fx" href="/user/vendormgmt">Register</a>
                                        </li>
                                    </ol>
                                </nav>
                            </th>
                        </tr>
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>
                                <tr>
                                    <th style="width: 5%;position:sticky;top:-1px"></th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">S.No
                                    </th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Vendor
                                        &nbsp ID</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Vendor
                                        Name</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Vendor
                                        Type</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">
                                        Frequency of Evaluation</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Vendor
                                        Status</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Last
                                        Evaluation Date</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">
                                        Evaluation Result</th>
                                    <th class="text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px">Action
                                        Comment</th>
                                    <th class="d-none d-sm-table-cell text-center" style="font-size:10px;text-transform:capitalize;position:sticky;top:-1px;z-index: 1">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0; ?>
                                <?php $d = date('Y-m-d');
                                    $t = date('Y-m-d', strtotime('-14 days'));
                                    ?>
                                @foreach ($data as $value)
                                <?php $rr = $value->id; ?>
                                <tr>
                                    <td><input class="text-center font-size-sm js-vendor-item" type="checkbox" id="{{ $value->id }}" value="{{ $value->id }}" name="id[]"></td>
                                    <td class="text-center " style="font-size:10px;height:30px;">{{ $no = $no + 1 }}
                                    </td>

                                    <td class="text-center " style="font-size:10px;">
                                        @if($value->vendor_group == 'New')
                                        @if ($value->reviewed_at == null)
                                        <span class="badge badge-warning" data-toggle="popover" data-placement="right" data-content="awaiting review">VEN{{ $value->id }}</span>
                                        @elseif($value->reviewed_at && $value->approved_at == null)
                                        <span class="badge badge-primary" data-toggle="popover" data-placement="right" data-content="awaiting approval">VEN{{ $value->id }}</span>
                                        @elseif($value->reviewed_at && $value->approved_at)
                                        <span class="badge badge-success" data-toggle="popover" data-placement="right" data-content="approved">VEN{{ $value->id }}</span>
                                        @endif
                                        @else
                                        <span class="badge badge-success" data-toggle="popover" data-placement="right" data-content="approved">VEN{{ $value->id }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center " style="font-size:10px;text-transform:capitalize">
                                        {{ $value->vendor_name }}</td>
                                    <td class="text-center " style="font-size:10px;">
                                        @switch($value->vendor_type)
                                        @case('critical')
                                        Critical
                                        @break

                                        @case('semi_critical')
                                        SemiCritical
                                        @break

                                        @case('non_critical')
                                        Non critical
                                        @break

                                        @default
                                        {{ $value->vendor_type }}
                                        @break
                                        @endswitch
                                    </td>
                                    <td class="text-center" style="font-size:10px;">
                                        @switch($value->eval)
                                        @case('quarterly')
                                        Quarterly
                                        @break

                                        @case('half_year')
                                        Half year
                                        @break

                                        @case('one_year')
                                        One year
                                        @break

                                        @case('one_half_year')
                                        1.5 year
                                        @break

                                        @case('two_year')
                                        Two year
                                        @break

                                        @case('three_year')
                                        Three year
                                        @break

                                        @case('four_year')
                                        Four year
                                        @break

                                        @case('five_year')
                                        Five year
                                        @break

                                        @default
                                        {{ $value->eval }}
                                        @break
                                        @endswitch
                                    </td>
                                    @php
                                    $q = $value->due_date;
                                    $z = date('Y-m-d', strtotime($q . ' - 1 months'));

                                    $last_evaluation_date = count($value->histories->pluck('evaluation_date')->toArray()) && max($value->histories->pluck('evaluation_date')->toArray()) != null ? date('Y-m-d',
                                    strtotime(max($value->histories->pluck('evaluation_date')->toArray()))) : null;

                                    $origin = new DateTime($q);
                                    $target = new DateTime($d);
                                    $days = abs(intval($origin->diff($target)->format('%R%a')));

                                    @endphp

                                    @switch($value->status)
                                    @case('under_review')
                                    <td class="d-none d-sm-table-cell" style="font-size:13px;"> <span class="badge badge-warning">Under Review</span></td>
                                    @break
                                    @case('awaiting_approval')
                                    <td class="d-none d-sm-table-cell" style="font-size:13px;"> <span class="badge badge-primary">Awaiting approval</span></td>
                                    @break
                                    @case('approved')
                                    @if ($last_evaluation_date)
                                    @if($last_evaluation_date <= $d) <td class="d-none d-sm-table-cell" style="font-size:13px;"> <span class="badge badge-success">Evaluated</span></td>
                                        @endif
                                        @else
                                        @if ($q < $d) <td class="d-none d-sm-table-cell" style="font-size:13px;"><span class="badge badge-danger">Evaluation Expired</span></td>
                                            @elseif ($q >= $d && $days <=14) <td class="d-none d-sm-table-cell" style="font-size:13px;"> <span class="badge badge-warning">Near to evaluation</span></td>
                                                @else
                                                <td class="d-none d-sm-table-cell" style="font-size:13px;"> <span class="badge badge-success">Newly approved</span></td>
                                                @endif
                                                @endif
                                                @break

                                                @default

                                                @endswitch

                                                <td class="d-none d-sm-table-cell" style="font-size:10px;">
                                                    <em class="text-muted">{{ $last_evaluation_date ? $last_evaluation_date : '-' }}</em>
                                                </td>

                                                <td class="d-sm-table-cell" style="font-size:10px;"><span class="badge {{ $data[$no - 1]->last_evaluation_color != null ? $data[$no - 1]->last_evaluation_color . ' text-white' : 'text-black' }}">{{
                                                $data[$no - 1]->last_evaluation_status ? $data[$no -
                                                1]->last_evaluation_status : '-' }}</span>
                                                </td>
                                                <td class="text-center " style="font-size:10px;">
                                                    {{ $data[$no - 1]->last_evaluation_comment ? $data[$no -
                                            1]->last_evaluation_comment : '' }}
                                                </td>

                                                <div class="dropdown font-size-sm">
                                                    <td class="/*font-w600 font-size-sm*/">
                                                        <div class="btn-group m-auto d-flex justify-content-center">
                                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{ $rr }}">
                                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                            </a>
                                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->Oid }}">
                                                                @if($value->status != 'under_review' && $value->status != 'awaiting_approval')
                                                                <a href="{{ url('/user/vendor/peval?val=' . $rr) }}" class="dropdown-item" style="font-size:10px;">Evaluate
                                                                    Vendor</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a href="{{ url('/user/vendor/history?val=' . $rr) }}" class="dropdown-item" style="font-size:10px;">Evaluation
                                                                    History</a>
                                                                @endif

                                                                {{-- @if (in_array(Auth::user()->email, $sa))
                                                        @if($value->status != 'under_review' && $value->status != 'awaiting_approval')
                                                            <div class="dropdown-divider"></div>
                                                        @endif
                                                        <a class="dropdown-item" style="font-size:10px;"
                                                            data-toggle="modal" data-id="{{ $value->id }}"
                                                                data-target="#viewrole{{ $value->id }}">Set
                                                                Procurement</a>
                                                                @endif --}}
                                                                @if(/*in_array(Auth::user()->email, $sa) ||*/ ($value->status != 'under_review' && $value->status != 'awaiting_approval'))
                                                                <div class="dropdown-divider"></div>
                                                                @endif
                                                                @if ($value->status != 'Evaluated')
                                                                <a href="{{ url('/user/vendor/editvendor?val=' . $rr) }}" class="dropdown-item" style="font-size:10px;">Edit</a>
                                                                @endif
                                                                @if (Gate::allows('review-vendor') && $value->reviewed_at == null && $value->vendor_group == 'New')
                                                                <div class="dropdown-divider"></div>
                                                                <a href="{{ route('vendor.reviewvendor', ['id' => $rr]) }}" class="dropdown-item" style="font-size:10px;">Review</a>
                                                                @endif
                                                                @if (Gate::allows('approve-vendor') && $value->reviewed_at != null && $value->approved_at == null)
                                                                <div class="dropdown-divider"></div>
                                                                <a href="{{ route('vendor.approvevendor', ['id' => $rr]) }}" class=" dropdown-item" style="font-size:10px;">Approve</a>
                                                                @endif

                                                            </div>
                                                            <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $rr }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn({{ $rr }})">
                                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                            </a>
                                                        </div>
                                                        <div id="viewModal{{ $value->id }}" class="modal fadebd-example-modal-lg detail-modal" tabindex="-1" role="dialog" aria-hidden="false">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h2 class="modal-title">Detailed View</h2>
                                                                    </div>
                                                                    <table class="table table-striped">
                                                                        <tr>
                                                                            <th style="width:70px">Vendor id</th>
                                                                            <td>{{ $value->id }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Vendor name</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $value->vendor_name }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Purpose of addition</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $value->vendor_group }}</td>
                                                                        </tr>
                                                                        @if ($value->vendor_group == 'Existing')
                                                                        <tr>
                                                                            <th> Approved date</th>
                                                                            <td>{{ $value->approved_date }}</td>
                                                                        </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <th>Scope of work/product/service</th>
                                                                            <td style="text-transform:capitalize">
                                                                                <?php echo nl2br($value->scope); ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Country</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $value->country ? $value->country->country_name : '' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Location</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $value->location }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Contact</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $value->contact }}</td>
                                                                        </tr>
                                                                        @if ($value->vendor_group == 'New')
                                                                        <tr>
                                                                            <th>Addition of new vendor based on</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $addition_array_to_show[$no - 1] }}
                                                                            </td>
                                                                        </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <th>Certified iso standards</th>
                                                                            <td style="text-transform:capitalize">
                                                                                @if (count($value->standards) > 0)
                                                                                    @php $i = 0; @endphp
                                                                                    @foreach ($value->standards as $s)
                                                                                    {{ $s->name }}@if ($i !=
                                                                                    count($value->standards) - 1)
                                                                                    ,
                                                                                    @endif
                                                                                    @php $i++; @endphp
                                                                                    @endforeach
                                                                                @else
                                                                                    Not certified
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Year of establishment</th>
                                                                            <td>{{ $value->Yoe }}</td>
                                                                        </tr>
                                                                        @if ($value->vendor_group == 'Existing')
                                                                        <tr>
                                                                            <th>Basis of approval</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $approval_array_to_show[$no - 1] }}</td>
                                                                        </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <th>Vendor type</th>
                                                                            <td style="text-transform:capitalize">
                                                                                @switch($value->vendor_type)
                                                                                @case('critical')
                                                                                Critical
                                                                                @break

                                                                                @case('semi_critical')
                                                                                SemiCritical
                                                                                @break

                                                                                @case('non_critical')
                                                                                Non critical
                                                                                @break

                                                                                @default
                                                                                {{ $value->vendor_type }}
                                                                                @break
                                                                                @endswitch
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Frequency of evaluation</th>
                                                                            <td style="text-transform:capitalize">
                                                                                @switch($value->eval)
                                                                                @case('quarterly')
                                                                                Quarterly
                                                                                @break

                                                                                @case('half_year')
                                                                                Half year
                                                                                @break

                                                                                @case('one_year')
                                                                                One year
                                                                                @break

                                                                                @case('one_half_year')
                                                                                1.5 year
                                                                                @break

                                                                                @case('two_year')
                                                                                Two year
                                                                                @break

                                                                                @case('three_year')
                                                                                Three year
                                                                                @break

                                                                                @case('four_year')
                                                                                Four year
                                                                                @break

                                                                                @case('five_year')
                                                                                Five year
                                                                                @break

                                                                                @default
                                                                                {{ $value->eval }}
                                                                                @break
                                                                                @endswitch
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th> Trial order made</th>
                                                                            <td style="text-transform:capitalize">
                                                                                {{ $value->trial_order == 'yes' ? 'Yes' : 'No'
                                                                        }}
                                                                            </td>
                                                                        </tr>
                                                                        @if ($value->trial_order == 'yes')
                                                                        <tr>
                                                                            <th> Outcome of trial order</th>
                                                                            <td style="text-transform:capitalize">
                                                                                @switch($value->outcome)
                                                                                @case('good')
                                                                                Good
                                                                                @break

                                                                                @case('satisfactory')
                                                                                Satisfactory
                                                                                @break

                                                                                @case('poor')
                                                                                Poor
                                                                                @break

                                                                                @default
                                                                                {{ $value->outcome }}
                                                                                @break
                                                                                @endswitch
                                                                            </td>
                                                                        </tr>
                                                                        @endif
                                                                        <tr>
                                                                            <th>Added by</th>
                                                                            <td>{{ $value->added_by }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Added date</th>
                                                                            <td>{{ $value->created_at }}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th>Last Re-evaluated at</th>
                                                                            <td>{{
                                                                        count($value->histories->pluck('evaluation_date')->toArray())
                                                                        ?
                                                                        max($value->histories->pluck('evaluation_date')->toArray())
                                                                        : '-' }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Comment by added person</th>
                                                                            <td>{{ $value->comments_of_added_person }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Comment by reviewed person</th>
                                                                            <td>{{ $value->comments_of_reviewed_person }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Comment by approved person</th>
                                                                            <td>{{ $value->comments_of_approved_person }}</td>
                                                                        </tr>

                                                                        <tr>
                                                                            <th>Percentage</th>
                                                                            @if ($value->per !== null)
                                                                            <td>{{ $value->per }}%</td>
                                                                            @else
                                                                            <td> </td>
                                                                            @endif
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Evaluation Requested By</th>
                                                                            <td>{{ $value->requestEvaluatedByUser ? $value->requestEvaluatedByUser->name : '' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Evaluation requested date & time</th>
                                                                            <td>{{ $value->evaluation_requested_time ? $value->evaluation_requested_time : '' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>End user</th>
                                                                            <td>{{ $value->endUser ? $value->endUser->name : '' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>System & compliance</th>
                                                                            <td>{{ $value->systemAndCompliance ? $value->systemAndCompliance->name : '' }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Supply chain Representative</th>
                                                                            <td>{{ $value->supplyChainRepresentative ? $value->supplyChainRepresentative->name : '' }}</td>
                                                                        </tr>
                                                                    </table>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="viewrole{{ $value->id }}" class="modal fadebd-example-modal-lg" role="dialog" aria-hidden="false" tabindex="-1">
                                                            <form name="form" class="mb-5" action="{{ url('/user/vendor/role') }}" method="POST">
                                                                {{ csrf_field() }}
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                        </div>
                                                                        <div class="block-content font-size-sm">

                                                                            <label style="font-size:12px;">Please Select User
                                                                                from Procurement for Reevaluation</label>
                                                                            <select class="form-control" name="proc" id="proc" required="true" style="font-size: 13px;width:230px;">
                                                                                @if (count($procrole) > 0)
                                                                                <option value="0" selected="" disabled style="font-size:13px;">Select
                                                                                </option>
                                                                                @foreach ($procrole as $p)
                                                                                <option value=" {{ $p->email }}">
                                                                                    {{ $p->name }}</option></br>
                                                                                @endforeach
                                                                                @endif
                                                                            </select>
                                                                            <input type="hidden" name="id" id="id" value="{{ $value->id }}">

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-dark style" style="font-size:10px;width: 60px;">Submit</button>
                                                                            <button type="button" class="btn btn-dark" data-dismiss="modal" style="font-size:10px;width: 60px;">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </td>
                                                </div>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
        {{-- ---------delete button for mr ---------- --}}
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script>
            function ViewBtn(id) {
                $('#viewModal' + id).modal('show');
                $('#DataTables_Table_0_wrapper .row:nth-child(2) .col-sm-12:first-child').css('z-index', 'initial');
                $(this).on('hide.bs.modal', function(){
                    $('#DataTables_Table_0_wrapper .row:nth-child(2) .col-sm-12:first-child').css('z-index', '3');
                });
            }
            $(document).ready(function() {
                let ids = "{{ $vendor_ids }}";
                ids = ids.split(',');
                for (var i = 0; i < ids.length; i++) {
                    $('#drop' + ids[i]).tooltip();
                }
            });

        </script>
        <script>
            $("#del").click(function() {
                var dataArr = new Array();
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));
                        $.ajax({
                            type: 'get'
                            , url: "{{ route('vendor.deletevendor') }}" + "?val=" + $(this).attr('id')
                            , success: function(response) {
                                window.location.href = "{{ url('/user/vendormgmt') }}";
                            }
                            , error: function(errResponse) {
                                console.log(errResponse);
                            }
                        });
                    });

                } else {
                    alert('No Record Selected');
                }
            });

            function autoResizeDiv(height) {
                document.getElementById('content').style.height = (height) + 'px';
            }
            window.onresize = autoResizeDiv;
            autoResizeDiv(window.innerHeight - 100);

        </script>
        {{-- ---------edit button for mr ---------- --}}

        <script>
            $("#edit").click(function() {
                var dataArr = new Array();
                var group = new Array();
                var d;
                var all;
                if ($('input:checkbox:checked').length > 0) {
                    $('input:checkbox:checked').each(function() {
                        dataArr.push($(this).attr('id'));

                        all = "" + dataArr;
                        group.push(all); // push all checkbox values into an array

                    });
                    $.ajax({
                        type: 'get'
                        , url: "{{ route('vendor.editv') }}" + "?val=" + (all)
                        , success: function(response) {

                            window.location.href = "{{ route('vendor.editv') }}" + "?val=" + (all);

                        }
                        , error: function(errResponse) {
                            alert(errResponse);
                        }
                    });

                } else {
                    alert('No Record Selected');
                }
            });

        </script>

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
                            <!-- <div class="col-6">

                                                <a class="block block-rounded block-link-shadow bg-body" href="{{ url('/user/vendor/addvendor') }}" >
                                                    <div class="block-content text-center"style="height:130px" >
                                                     <i class="fas fa-plus-circle fa-2x text-primary"></i>
                                                        <p class="font-w600 font-size-sm mt-2 mb-4">
                                                        Add Vendor
                                                        </p>
                                                    </div>
                                                </a>

                                            </div> -->
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

    @include('exporting.forvendormanagement')
    <!-- END Apps Modal -->

    @section('sideitem')
    {{-- @foreach ($mr as $mrs) --}}
    {{-- @if (Auth::user()->email == $mrs) --}}
    @if (Gate::allows('edit-vendor') || Gate::allows('edit-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="margin-left:460px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if (Gate::allows('delete-vendor') || Gate::allows('delete-all'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    {{-- @endif --}}
    {{-- @endforeach --}}
    @endsection

    <!-- Request Evaluation -->
    <div class="modal fade" id="requestEvaluationModal" tabindex="-1" role="dialog" aria-labelledby="requestEvaluationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestEvaluationModalLabel">Request Evaluation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="flash-msg">
                    <div class="label-invalid error-vendor_ids"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-form-label" style="font-size: 13px;"
                           for="end_user_id">End User <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control" name ="end_user_id"
                                id="end_user_id" style="font-size: 13px;width:100%;" required>
                            <option value="">Please select....</option>
                            @if(count($users)>0)
                                @foreach($users as $value)
                                    <option value="{{$value->id}}"> {{$value->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="label-invalid error-end_user_id"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-form-label" style="font-size: 13px;"
                           for="system_compliance_user_id">System & compliance <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control" name ="system_compliance_user_id" id="system_compliance_user_id"
                                style="font-size: 13px;width:100%;" required>
                            <option value="">Please select....</option>
                            @if(count($users)>0)
                                @foreach($users as $value)
                                    <option value="{{$value->id}}"> {{$value->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="label-invalid error-system_compliance_user_id"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 col-form-label" style="font-size: 13px;"
                           for="supply_chain_representative_user_id">Supply chain Representative <span class="text-danger">*</span></label>
                    <div class="col-sm-12">
                        <select class="form-control" name ="supply_chain_representative_user_id"
                                id="supply_chain_representative_user_id" style="font-size: 13px;width:100%;" required>
                            <option value="">Please select....</option>
                            @if(count($users)>0)
                                @foreach($users as $value)
                                    <option value="{{$value->id}}"> {{$value->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="label-invalid error-supply_chain_representative_user_id"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="button" class="btn btn-primary js-submit-request"
                            data-route="{{ route('vendor.request.evaluation') }}"
                            style="font-size:12px">
                        Request Evaluation
                    </button>
                </div>
            </div>
        </div>

    </div>
</body>
<!-- to display alert msg for reevaluate button -->
<script>
    var msg = '{{ Session::get('
    alert ') }}';
    var exist = '{{ Session::has('
    alert ') }}';
    if (exist) {
        alert(msg);
    }
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".js-vendor-item").change(function() {
            let isEnableRequestEvaluation = false;
            $(".js-vendor-item").each(function (key, item) {
                if($(item).prop('checked') === true) {
                    isEnableRequestEvaluation = true;
                    return;
                }
            })

            if(isEnableRequestEvaluation) {
                $('.js-request-evaluation').prop('disabled', false)
            } else {
                $('.js-request-evaluation').prop('disabled', true)
            }
        });

        $('.js-request-evaluation').click(function () {
            if($('#requestEvaluationModal').length) {
                $('#requestEvaluationModal').modal('show')
            }
        })
        $('.js-submit-request').on('click', function () {
            $('#requestEvaluationModal').find('.label-invalid').html('');
            $('#requestEvaluationModal').find('.is-invalid').removeClass('is-invalid')
            const url = $(this).attr('data-route');
            const submitButton = $(this);
            const vendorIds = [];
            $(".js-vendor-item").each(function (key, item) {
                if($(item).prop('checked') === true) {
                    vendorIds.push($(item).val())
                }
            })
            $.ajax({
                url,
                type: 'POST',
                data: {
                    'vendor_ids': vendorIds,
                    'end_user_id': $('#end_user_id').find(":selected").val(),
                    'system_compliance_user_id': $('#system_compliance_user_id').find(":selected").val(),
                    'supply_chain_representative_user_id': $('#supply_chain_representative_user_id').find(":selected").val(),
                },
                beforeSend: function () {
                    submitButton.html(`<span class="spinner mx-1"><i class="fa fa-spinner fa-spin"></i></span>Loading`)
                },
                success: function (data) {
                    submitButton.html(`Request Evaluation`)
                    if (data.success) {
                        $('#requestEvaluationModal').find('.flash-msg')
                            .html(`<div class="flash_message alert-success text-center py-1 my-0">${data.message}</div>`);
                        setTimeout(function () {
                            location.reload()
                        }, 3000)
                    } else {
                        $('#requestEvaluationModal').find('.flash-msg')
                            .html(`<div class="flash_message alert-danger text-center py-1 my-0">${data.message}</div>`)
                    }
                },
                error: function (response) {
                    submitButton.html(`Request Evaluation`)
                    const status = response.status;
                    const data = response.responseJSON;
                    if (status === 422) {
                        $.each(data.errors, function (field, object) {
                            const errorString = object.join(',')
                            $('.error-' + field).html(errorString)
                            $('#' + field).addClass('is-invalid')
                        })
                    } else {
                        $('#requestEvaluationModal').find('.flash-msg')
                            .html(`<div class="flash_message alert-danger text-center py-1 my-0">${data.message}</div>`)
                    }
                }
            })
        })
    })
</script>

</html>
@endsection
