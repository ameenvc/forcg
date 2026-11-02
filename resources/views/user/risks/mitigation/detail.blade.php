@extends('layouts.multiselect')

@section('content')
    <style>
        .status {
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .status-blue {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-red {
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .status-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .status-secondary {
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .status-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .status-flat {
            color: #44b3ab;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .process-stage-point {
            position: relative;
        }
        .process-stage-point span {
            background: #CFCFCF;
            position: relative;
        }
        .process-stage-point span::after {
            content: '';
            position: absolute;
            width: 100%;
            top: 1.5rem;
            left: -2rem;
            white-space: nowrap;
        }
        .process-stage-point span.hide-action::after {
            display: none;
        }
        .process-stage-point span.span-complete-action::after {
            content: 'Completed';
        }
        .process-stage-point span.span-reject-action::after {
            content: 'Rejected';
        }
        .process-stage-point span.span-active-risk-action::after {
            content: 'Active Risk';
        }
        .process-stage-point span.span-under-review-action::after {
            content: 'Under Review';
        }
        .process-stage-point span.span-under-review-action-2::after {
            content: 'Action under review';
        }
        .process-stage .process-stage-point span {
            margin-right: 8rem !important;
        }
        .process-stage-point:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 35%;
            left: 11%;
            width: 100%;
            height: 2px;
            background: #000000;
        }
        .bg-warning {
            background-color: #fbdece !important;
        }
        .bg-danger {
            background-color: #f8d4d4 !important;
        }
        .bg-primary {
            background-color: #d9e2f6 !important;
        }
        .bg-secondary {
            background-color: #E5E4E2 !important;
        }
        .bg-blue {
            background-color: #25499c !important;
        }
        .bg-flat {
            background: #bff0dd !important;
        }
        .send-notification-item {
            background: #343A40;
            border-radius: 0.25rem;
            align-items: center;
            min-height: 2.5rem;
            padding: 0.5rem 1.75rem;
            margin-right: 2rem;
            margin-bottom: 1rem;
            display: flex;
        }
        .send-notification-item img {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .timeline-point {
            position: relative;
        }
        .timeline-point:not(:last-child):after {
            content: '';
            position: absolute;
            top: 3.15rem;
            left: -1.35rem;
            width: 4rem;
            height: 2px;
            background: #000000;
            transform: rotate(90deg);
        }
        .btn-export:focus {
            box-shadow: none !important;
        }
        /* Mobile */
        @media only screen and (max-width: 400px) {
            .d-block-400 {
                display: block !important;
            }
            .d-block-400 div {
                margin-bottom: 10px;
            }
            .d-block-400 div:first-child {
                margin-right: 0 !important;
            }
            .d-block-400 div:first-child button {
                width: 100%;
            }
            .process-stage .process-stage-point span {
                margin-right: 4.5rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 4.5vw !important;
            }
            body {
                width: 100%
            }
            .d-block-643 {
                display: block !important;
            }
            .content {
                padding: 1rem 1rem 1px !important;
            }
            .d-block-sp {
                display: block !important;
            }

            .w-100-sp {
                width: 100% !important;
            }
            .mb-2-sp {
                margin-bottom: 1rem !important;
            }

            .px-adjust {
                padding-right: 0.25rem !important;
                padding-left: 0.25rem !important;
            }

            .px-adjust .correction-block-adjust {
                margin-right: 0.25rem !important;
            }

            .root-cause-table-adjust {
                margin-left: 0.25rem !important;
                margin-right: 0.25rem !important;
            }
            .send-notification-item {
                height: auto;
                text-align: center;
            }
            .frequency-adjust {
                display: block !important;
                text-align: center !important;
            }
            .frequency-adjust div:first-child {
                background: none !important;
            }
            .px-xs-adjust {
                padding-right: 1rem !important;
                padding-left: 1rem !important;
            }
            .mr-xs-adjust {
                margin-right: 0 !important;
            }
            .d-none-643 {
                display: none;
            }
        }
        @media only screen and (min-width: 400px) and (max-width: 643px) {
            body {
                width: 100%
            }
            .d-block-643 {
                display: block !important;
            }
            .content {
                padding: 1rem 1rem 1px !important;
            }
            .d-block-sp {
                display: block !important;
            }
            .w-100-sp {
                width: 100% !important;
            }
            .mb-2-sp {
                margin-bottom: 1rem !important;
            }

            .px-adjust {
                padding-right: 0.25rem !important;
                padding-left: 0.25rem !important;
            }

            .px-adjust .correction-block-adjust {
                margin-right: 0.25rem !important;
            }

            .root-cause-table-adjust {
                margin-left: 0.25rem !important;
                margin-right: 0.25rem !important;
            }
            .send-notification-item {
                height: auto;
                text-align: center;
            }
            .frequency-adjust {
                display: block !important;
                text-align: center !important;
            }
            .frequency-adjust div:first-child {
                background: none !important;
            }
            .px-xs-adjust {
                padding-right: 1rem !important;
                padding-left: 1rem !important;
            }
            .mr-xs-adjust {
                margin-right: 0 !important;
            }
            .d-none-643 {
                display: none;
            }
            .process-stage .process-stage-point span {
                margin-right: 7rem !important;
            }
        }
        @media only screen and (min-width: 643px) and (max-width: 767px) {
            .content {
                padding: 1rem 1rem 1px !important;
            }
            .d-block-sp {
                display: block !important;
            }
            .w-100-sp {
                width: 100% !important;
            }
            .mb-2-sp {
                margin-bottom: 1rem !important;
            }

            .px-adjust {
                padding-right: 0.25rem !important;
                padding-left: 0.25rem !important;
            }

            .px-adjust .correction-block-adjust {
                margin-right: 0.25rem !important;
            }

            .root-cause-table-adjust {
                margin-left: 0.25rem !important;
                margin-right: 0.25rem !important;
            }
            .root-cause-resp {
                display: block !important;
            }
            .root-cause-resp span {
                display: block !important;
                text-align: center !important;
            }
        }
        /* Tablet */
        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .content {
                padding: 1.875rem 1.875rem 1px !important;
            }
            .process-stage {
                margin-left: 0 !important;
            }
            .process-stage .process-stage-point span {
                margin-right: 7rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 12%;
            }
            .w-change {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .px-adjust {
                padding-right: 0.25rem !important;
                padding-left: 0.25rem !important;
            }
            .px-adjust .correction-block-adjust {
                margin-right: 0.25rem !important;
            }
            .root-cause-table-adjust {
                margin-left: 1rem !important;
                margin-right: 1rem !important;
            }
            .root-cause-resp {
                display: block !important;
            }
            .root-cause-resp span {
                display: block !important;
                text-align: center !important;
            }
            .d-block-768 {
                display: block !important;
            }
        }
        @media only screen and (min-width: 807px) and (max-width: 992px) {
            .responsibility-dep-date-adjust {
                display: block !important;
            }
            .responsibility-dep-date-adjust div:first-child {
                margin-right: 0 !important;
                margin-bottom: 5px !important;
            }
            .root-cause-resp {
                display: block !important;
            }
            .root-cause-resp span {
                display: block !important;
                text-align: center !important;
            }
        }
        /* Desktop */
        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            .process-stage-point span.span-action-completed::after {
                left: -5rem;
            }
            .process-stage .process-stage-point span {
                margin-right: 8rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 11%;
            }
            .root-cause-resp {
                display: block !important;
            }
            .root-cause-resp span {
                display: block !important;
                text-align: center !important;
            }
            .px-adjust {
                padding-right: 2rem !important;
                padding-left: 2rem !important;
            }
            .px-adjust .correction-block-adjust {
                margin-right: 0 !important;
            }
            .root-cause-table-adjust {
                margin-left: 1rem !important;
                margin-right: 1rem !important;
            }
        }
        @media only screen and (min-width: 1199px) and (max-width: 1340px) {
            .process-stage .process-stage-point span {
                margin-right: 8rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 11%;
            }
        }
        .multiple-avatar:not(:first-child) {
            margin-left: -1.5rem;
        }
        .table-custom thead th {
            font-weight: 600;
            font-size: 12px;
            text-transform: none;
            letter-spacing: 0;
            text-align: center;
        }
        .table-custom tbody td {
            text-align: center;
            vertical-align: middle;
        }
        .table-custom tbody td:nth-child(3) {
            text-align: left;
        }
        .table-custom {
            border-top: none;
            border-left: none;
            border-right: none;
        }
        .table-custom thead tr th {
            background: #252945;
            color: white;
            border: none;
        }
        .table-custom thead tr th:not(:last-child) {
            border-right: 1px solid #e1e6e9;
        }
        .table-custom thead tr th:first-child {
            border-radius: 10px 0 0 0;
        }
        .table-custom thead tr th:last-child {
            border-radius: 0 10px 0 0;
        }
        .btn-close-modal-custom {
            padding: 0.25rem !important;
            margin: 0 !important;
        }
        .btn-close-modal-custom:focus {
            outline: none;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border-color: #878787;
        }
    </style>
    <!-- Page Content -->
    <div class="content px-0" style="-webkit-print-color-adjust: exact">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <span class="mr-2">Mitigation Action</span>
                    <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">{{ $mitigation->action_name }}</span>
                </div>
                <div class="d-flex-right p-2 d-flex d-block">
                    <div class="d-flex mr-4 border-right">
                        @if($posted_user_image && File::exists(public_path('/storage/profile/' . $posted_user_image)))
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/' . $posted_user_image) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src=" {{ asset('/images/avatar10.jpg') }}" />
                        @endif
                    </div>
                    <div class="d-flex align-items-center d-block-400">
                        <div class="mr-2">
                            <button class="btn btn-primary px-3" onclick="window.print();"
                                    style="background: #252945; border-color:#252945; color: white; font-size: 12px">
                                <i class="fa fa-print mr-2"></i> Print
                            </button>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn btn-success btn-export dropdown-toggle"
                                    id="dropdown-export" style="background: #252945; border-color:#252945; color: white; font-size: 12px"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-file-download mr-2"></i> Export
                            </button>
                            <div class="dropdown-menu font-size-sm" style="min-width: 5rem" aria-labelledby="dropdown-export">
                                <a class="dropdown-item" href="{{ route('mitigations.detail.pdf', $mitigation->id) }}">PDF</a>
{{--                                <a class="dropdown-item" href="javascript:void(0)">Excel</a>--}}
{{--                                <a class="dropdown-item" href="javascript:void(0)">Word</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-4 d-flex justify-content-around d-block-sp px-xs-adjust">
                <div class="px-4 mr-4 pb-3 mb-2-sp mr-xs-adjust w-50 w-100-sp" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;">
                    <label class="mt-2 ml-2"><i class="fa fa-eye mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Overview</label>
                    <div class="d-flex" style="flex-flow: wrap">
                        <span class="status status-blue mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">
                            <img src="{{ asset('images/icons/vpn_key_blue.png') }}" alt="key_icon" style="width: 18px">
                            <span class="ml-2">#{{ $mitigation->action_id }}</span>
                        </span>
                        <span class="status status-warning mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">
                            <img src="{{ asset('images/icons/work.png') }}" alt="work_icon" style="width: 18px">
                            <span class="ml-2">{{ $mitigation->department ? $mitigation->department->departmentname : '-'}}</span>
                        </span>
                        <span class="status status-red mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">
                            <img src="{{ asset('images/icons/trending_up.png') }}" alt="trending_up_icon" style="width: 18px">
                            <span class="ml-2">
                                {{ ucfirst($mitigation->priority_level) }}
                            </span>
                        </span>
                    </div>
                </div>
                <div class="px-4 w-50 w-100-sp" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;">
                    <label class="mt-2 ml-2">
                        <img src="{{ asset('images/icons/timeline.png') }}"
                             alt="timeline_icon" style="width: 20px; padding: 2px; background: #2529453D; border-radius: 5px;">
                        <span class="ml-2">Status & Timeline</span>
                    </label>
                    <div class="mt-2">
                        <label>Current Process Status</label>
                        <div class="mt-3 ml-3">
                            @if($mitigation->status == config('constants.risk_management.mitigation.closed'))
                                <div class="d-flex align-items-center">
                                    <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: 100%; background: #44B3AB" aria-valuenow="10" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">100%</p>
                                </div>
                                <p class="mt-1 mb-1 text-center"><b>Closed</b></p>
                            @else
                                @if ($mitigation->progress == 25)
                                    <div class="d-flex align-items-center">
                                        <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: 25%; background: #44B3AB" aria-valuenow="25" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">25%</p>
                                    </div>
                                    <p class="mt-1 mb-1 text-center"><b>In-progress</b></p>
                                @endif
                                @if ($mitigation->progress == 50)
                                    <div class="d-flex align-items-center">
                                        <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: 50%; background: #44B3AB" aria-valuenow="50" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">50%</p>
                                    </div>
                                    <p class="mt-1 mb-1 text-center"><b>In-progress</b></p>
                                @endif
                                @if ($mitigation->progress == 75)
                                    <div class="d-flex align-items-center">
                                        <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: 75%; background: #44B3AB" aria-valuenow="100" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">75%</p>
                                    </div>
                                    <p class="mt-1 mb-1 text-center"><b>In-progress</b></p>
                                @endif
                                @if ($mitigation->progress == 100)
                                    <div class="d-flex align-items-center">
                                        <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: 100%; background: #44B3AB" aria-valuenow="10" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">100%</p>
                                    </div>
                                    <p class="mt-1 mb-1 text-center"><b>Completed</b></p>
                                @endif
                                @if ($mitigation->progress == 0)
                                    <div class="d-flex align-items-center">
                                        <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: 0%; background: #44B3AB" aria-valuenow="0" aria-valuemin="0"
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">0%</p>
                                    </div>
                                    <p class="mt-1 mb-1 text-center"><b>Not Started</b></p>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between d-block-sp d-block-643 d-block-400 d-block-768">
                        <label>Due date: {{ $mitigation->due_date ? date('d-m-Y', strtotime($mitigation->due_date)) : '' }}</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-4 d-flex px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 20px" class="col-md-12">
                    <label class="mt-3 ml-4"><i class="fa fa-clipboard-list mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Details</label>
                    <div class="mt-4 px-4 d-flex d-block-sp px-adjust">
                        <div class="observation col-md-7">
                            <label>
                                {{ $mitigation->action_name }}
                            </label>
                            <p class="text-justify">{!! nl2br(strip_tags($mitigation->action_description)) !!}</p>
                        </div>
                        <div class="col-md-5 justify-content-center d-block-sp">
                            <div>
                                <label>Owner</label><br>
                                <div class="align-items-center d-flex">
                                    @if($mitigation->user && $mitigation->user->profile_picture && File::exists(public_path('/storage/profile/'. $mitigation->user->profile_picture)))
                                        <img class="img-avatar multiple-avatar img-avatar48" alt="{{ $mitigation->user->name }}"
                                             src="{{ asset('/storage/profile/'. $mitigation->user->profile_picture) }}">
                                    @else
                                        <img class="img-avatar multiple-avatar
                                                img-avatar48" alt="No picture"
                                             src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                    <p class="font-weight-bold mb-2 mt-2 ml-2">{{ $mitigation->user ? $mitigation->user->name : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 px-4 d-flex d-block-sp px-adjust">
                        <div class="observation col-md-7">
                            <label>Required Resources</label>
                            @if($mitigation->resource_required)
                                <div class="d-flex flex-wrap">
                                    <p class="status status-success mr-2 mb-2">{{ $mitigation->resource_required }}</p>
                                </div>
                            @else
                                <p class="text-danger">No required resources added</p>
                            @endif
                        </div>
                        <div class="col-md-5 justify-content-center d-block-sp">
                            <label>Cost Estimate</label>
                            @if($mitigation->cost_estimate)
                                <div class="d-flex flex-wrap px-2 py-2 status status-success col-md-6 align-items-center">
                                    <img src="{{ asset('images/icons/monetization_on.png') }}" alt="monetization_on" style="width: 12px">
                                    <span class="ml-2">${{ is_numeric($mitigation->cost_estimate) ? number_format($mitigation->cost_estimate) : $mitigation->cost_estimate }}</span>
                                </div>
                            @else
                                <p class="text-danger">No cost estimate added</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 px-4 d-flex d-block-sp px-adjust">
                        <div class="w-100 px-2">
                            <label>Linked Risk(s), Likelihood & Impact Changes</label>
                            @if(count($otherRisks))
                                <div>
                                    @foreach($otherRisks as $riskItem)
                                    @if(!$riskItem->deleted_at)
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="{{ route('risk.detail', $riskItem->Rid) }}"
                                           target="_blank"
                                           class="status status-blue mb-0">{{ $riskItem->grp_id }}</a>
                                        <span class="ml-2" style="font-size: 10px; color: #878787">{{ $riskItem->riskname }}</span>
                                    </div>
                                    @else 
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="#" data-toggle="tooltip" title="This linked risk has been deleted."
                                           class="status status-blue mb-0">{{ $riskItem->grp_id }}</a>
                                        <span class="ml-2" style="font-size: 10px; color: #878787">{{ $riskItem->riskname }}</span>
                                    </div>
                                    @endif
                                    @if(count($riskItem->mitigationHistories))
                                        @php
                                            $mitigationHistory = $riskItem->mitigationHistories->where('mitigation_id', $mitigation->id)->first();
                                        @endphp
                                        @if($mitigationHistory)
                                        {{-- <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center mr-3">
                                                <label class="mr-2">・Existing L * I & Risk:</label>
                                                <label class="status status-blue mr-2">
                                                    {{ $mitigationHistory->existing_likelihood >= 3 ? '03' : ($mitigationHistory->existing_likelihood >= 2 ? '02' : '01') }}
                                                </label>
                                                <label class="mr-2">x</label>
                                                <label class="status status-blue mr-2">
                                                    {{ $mitigationHistory->existing_impact >= 3 ? '03' : ($mitigationHistory->existing_impact >= 2 ? '02' : '01') }}
                                                </label>
                                                <div style="border-left: 1px solid; height: 20px; margin-bottom: 0.25rem" class="mr-2"></div>
                                                <label class="status {{ $mitigationHistory->existing_risk == 'High' ? 'status-red' : ($mitigationHistory->existing_risk == 'Moderate' ? 'status-warning' : 'status-success') }}">
                                                    {{ $mitigationHistory->existing_risk }}
                                                </label>
                                            </div>
                                            <div class="d-flex align-items-center mr-3 bg-white">
                                                <div class="p-2" style="font-weight:bold;">
                                                    Actual residual values: L={{$mitigationHistory->new_likelihood}}, I={{$mitigationHistory->new_impact}}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mr-5">
                                                <label class="mr-2">・New L * I & Risk:</label>
                                                <label class="status status-warning mr-2" style="background: #EE9F273D; color: #EE9F27">
                                                    {{ $mitigationHistory->new_likelihood >= 3 ? '03' : ($mitigationHistory->new_likelihood >= 2 ? '02' : '01') }}
                                                </label>
                                                <label class="mr-2">x</label>
                                                <label class="status status-warning mr-2" style="background: #EE9F273D; color: #EE9F27">
                                                    {{ $mitigationHistory->new_impact >= 3 ? '03' : ($mitigationHistory->new_impact >= 2 ? '02' : '01') }}
                                                </label>
                                                <div style="border-left: 1px solid; height: 20px; margin-bottom: 0.25rem" class="mr-2"></div>
                                                <label class="status {{ $mitigationHistory->new_risk == 'High' ? 'status-red' : ($mitigationHistory->new_risk == 'Moderate' ? 'status-warning' : 'status-success') }}">
                                                    {{ $mitigationHistory->new_risk }}
                                                </label>
                                            </div>
                                        </div> --}}
                                        <div class="row align-items-center">
                                            <!-- Existing Risk -->
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <label class="mr-2 mb-0">• Existing L * I & Risk:</label>

                                                    <label class="status status-blue mr-2">
                                                        {{ $mitigationHistory->existing_likelihood >= 3 ? '03' : ($mitigationHistory->existing_likelihood >= 2 ? '02' : '01') }}
                                                    </label>

                                                    <label class="mr-2 mb-0">×</label>

                                                    <label class="status status-blue mr-2">
                                                        {{ $mitigationHistory->existing_impact >= 3 ? '03' : ($mitigationHistory->existing_impact >= 2 ? '02' : '01') }}
                                                    </label>

                                                    <div class="mx-2" style="border-left:1px solid;height:20px;"></div>

                                                    <label class="status {{ $mitigationHistory->existing_risk == 'High' ? 'status-red' : ($mitigationHistory->existing_risk == 'Moderate' ? 'status-warning' : 'status-success') }}">
                                                        {{ $mitigationHistory->existing_risk }}
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Actual Residual Values -->
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="font-weight-bold h-100 d-flex align-items-center justify-content-center" style="width:90% !important;">
                                                    Actual residual values:
                                                    L={{ $mitigationHistory->new_likelihood }},
                                                    I={{ $mitigationHistory->new_impact }}
                                                </div>
                                            </div>
                                            <!-- New Risk -->
                                            <div class="col-lg-4 col-md-6 mb-2">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <label class="mr-2 mb-0">• New L * I & Risk:</label>

                                                    <label class="status status-warning mr-2" style="background:#EE9F273D;color:#EE9F27;">
                                                        {{ $mitigationHistory->new_likelihood >= 3 ? '03' : ($mitigationHistory->new_likelihood >= 2 ? '02' : '01') }}
                                                    </label>

                                                    <label class="mr-2 mb-0">×</label>

                                                    <label class="status status-warning mr-2" style="background:#EE9F273D;color:#EE9F27;">
                                                        {{ $mitigationHistory->new_impact >= 3 ? '03' : ($mitigationHistory->new_impact >= 2 ? '02' : '01') }}
                                                    </label>

                                                    <div class="mx-2" style="border-left:1px solid;height:20px;"></div>

                                                    <label class="status {{ $mitigationHistory->new_risk == 'High' ? 'status-red' : ($mitigationHistory->new_risk == 'Moderate' ? 'status-warning' : 'status-success') }}">
                                                        {{ $mitigationHistory->new_risk }}
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                    @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-danger">No link other risk added</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 px-4 d-flex d-block-sp px-adjust">
                        <div class="justify-content-center d-block-sp px-2">
                            <label>Attachments</label>
                            @if(count($mitigation->attachments))
                                <div class="d-flex flex-wrap">
                                    @foreach($mitigation->attachments as $attachment)
                                        <a href="{{ asset("storage/mitigation/" . $attachment->attachment)}}"
                                           class="status status-success mr-2 mb-2"
                                           style="background: #1760443D; color: #176044">
                                            <img src="{{ asset('images/icons/download_green.png') }}"
                                                 alt="download_green"
                                                style="width: 20px">
                                            <span class="ml-1">{{ $attachment->attachment }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-danger">No attachments added</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-4 d-block row px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem">
                    <label class="mt-3 ml-4"><i class="fa fa-chart-line mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Timeline & History</label>
                    <div class="d-flex justify-content-center pb-1">
                        <div class="col-md-6 col-sm-6">
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Added By & Date</label>
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Last Reviewed By & Date</label>
                        </div>
                        <div class="col-md-1 d-none-643" style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto;"></span>
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="d-flex align-items-center" style="margin-top: 2rem;">
                                @if($mitigation->createdBy && $mitigation->createdBy->profile_picture && File::exists(public_path('/storage/profile/'. $mitigation->createdBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $mitigation->createdBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'. $mitigation->createdBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $mitigation->createdBy ? $mitigation->createdBy->name : '' }}</label><br>
                                    <label>on {{ $mitigation->created_at ? date('d-m-Y', strtotime($mitigation->created_at)) : '-' }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                @if($mitigation->reviewedBy && $mitigation->reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'. $mitigation->reviewedBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $mitigation->reviewedBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'. $mitigation->reviewedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $mitigation->reviewedBy ? $mitigation->reviewedBy->name : 'Not Specified' }}</label><br>
                                    <label>on {{ $mitigation->reviewed_date ? date('d-m-Y', strtotime($mitigation->reviewed_date)) : '-' }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 pb-5 px-4 d-block row px-xs-adjust">
                <div class="d-flex justify-content-end mr-4">
                    <button class="btn btn-primary px-3" onclick="window.print();"
                            style="background: #252945; border-color:#252945; color: white; font-size: 12px">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
    <script>
        $(function () {
            $('.btn-toggle-history').on('click', function () {
                const currentValue = $(this).val();
                $('.table-risk-history').slideToggle('slow')

                if (currentValue == 'load') {
                    $(this).val('hide');
                    $(this).html('<span><i class="fa fa-eye-slash"></i></span><span class="ml-1">Hide History</span>');
                } else {
                    $(this).val('load');
                    $(this).html('<span><i class="fa fa-eye"></i></span><span class="ml-1">Load History</span>');
                }
            })
        })
    </script>
@endsection
