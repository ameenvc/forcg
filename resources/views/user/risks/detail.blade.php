@extends('layouts.multiselect')

@section('content')
@once
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
@endonce

    <style>
        .status {
            display: inline-block;
            padding: 7px 5px 5px 7px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            align-items: bottom;
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
        .process-stage-point span.span-awaiting-periodic-review-action::after {
            content: 'Awaiting Periodic Review';
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
        .bg-success {
            background-color: #e0edcf !important;
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
                margin-right: 3.5rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 3.5vw !important;
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
                margin-right: 4.5rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 3.2vw !important;
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
            .process-stage .process-stage-point span {
                margin-right: 7rem !important;
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
                margin-right: 4.5rem!important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 1.8vw !important;
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
            .process-stage .process-stage-point span {
                margin-right: 4.5rem!important;
            }
        }
        /* Desktop */
        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            .process-stage-point span.span-action-completed::after {
                left: -5rem;
            }
            .process-stage .process-stage-point span {
                margin-right: 5rem!important;;
            }
            .process-stage-point:not(:last-child)::after {
                left: 14%;
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
                margin-right: 8rem!important;;
            }
            .process-stage-point:not(:last-child)::after {
                left: 11%;
            }
        }
        .responsive-block {
            margin-left: 0.80rem;
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
        .comment-padding {
            padding: 1.25rem;
        }
        .editor-container {
            width: 90%;
            font-size: 13px;
        }
        #ecom-product-comments p{
            font-size:13px;
            margin-bottom: 4px;
        }
        .editor-container p{
            margin-bottom: 0.5rem !important;
        }

        .card20 {
            background: white;
            margin: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            width: 25%;
        }

        .card label {
            font-size: clamp(14px, 0.25vw, 16px);
            font-weight: 600;
        }

        /* Values */
        .card span {
            font-size: clamp(14px, 0.25vw, 16px);
        }

        @media screen and (min-width: 1217px) and (max-width: 1400px) {
            .card20 {
                width: 20%;
            }
            .card label {
                font-size: clamp(14px, 0.25vw, 16px);
                font-weight: 600;
            }

        }
        @media screen and (min-width: 851px) and (max-width: 1217px) {
            .card20 {
                width: 25%;
                margin: 0.90rem;
                padding: 0.90rem;
            }
            .card label {
                font-size: clamp(14px, 0.25vw, 16px);
                font-weight: 600;
            }
        }
        @media screen and (min-width: 779px) and (max-width: 880px) {
            .card20 {
                width: 45%;
                margin: 0.80rem;
                padding: 0.80rem;
            }
            .card label {
                font-size: clamp(14px, 0.25vw, 16px);
                font-weight: 600;
            }
        }
        @media screen and (min-width: 660px) and (max-width: 778px) {
            .card20 {
                width: 45%;
                margin: 0.70rem;
                padding: 0.70rem;
            }
            .card label {
                font-size: clamp(12px, 0.25vw, 14px);
                font-weight: 600;
            }
        }
        @media screen and (min-width: 599px) and (max-width: 659px) {
            .card20 {
                width: 45%;
                margin: 0.60rem;
                padding: 0.60rem;
            }
            .card label {
                font-size: clamp(12px, 0.25vw, 14px);
                font-weight: 600;
            }
        }
        @media screen and (min-width: 421px) and (max-width: 599px) {
            .card20 {
                width: 43%;
                margin: 0.60rem;
                padding: 0.40rem;
            }
            .card label {
                font-size: clamp(12px, 0.25vw, 14px);
                font-weight: 600;
            }
            .reviewer-card{
                align-items: center;
                justify-content: center;
            }
        }
        @media screen and (max-width: 420px) {
            .card20 {
                width: 100%;
                margin: 0.60rem;
            }
            .card label {
                font-size: clamp(10px, 2vw, 12px);
                font-weight: 600;
            }
            .reviewer-card{
                align-items: center;
                justify-content: center;
            }
        }
        .reviewer-card img {
            border-radius: 50%;
        }
        /********************* History Table Style- End ************/
        @media print {
            .table-custom thead tr th {
                background-color: #252945 !important;
            }
            * {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
    <script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script src="https://ckeditor.com/apps/ckfinder/3.5.0/ckfinder.js"></script>
    @include('shared.forms.assets')
    @include('user.risks.review-assets')
    <!-- Page Content -->
    <div class="content px-0 cgbod-form risk-detail-page" style="-webkit-print-color-adjust: exact">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <span class="mr-2">Enterprise Risk</span>
                    <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">{{ $risk->riskname }}</span>
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
                                <a class="dropdown-item" href="{{ route('risk.detail.pdf', $risk->Rid) }}">PDF</a>
                                    {{--<a class="dropdown-item" href="javascript:void(0)">Excel</a>--}}
                                    {{--<a class="dropdown-item" href="javascript:void(0)">Word</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('user.risks.review-assist', ['reviewRisk' => $risk])
            <div class="mt-4 px-5 d-flex justify-content-around d-block-sp px-xs-adjust">
                <div class="px-4 mr-4 pb-3 mb-2-sp mr-xs-adjust w-50 w-100-sp" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;">
                    <label class="mt-2 ml-2"><i class="fa fa-eye mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Overview</label>
                    <div class="d-flex" style="flex-flow: wrap">
                        <span class="status status-red mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                            <img src="{{ asset('images/icons/vpn_key.png') }}" alt="key_icon" style="width: 18px">
                            <span class="ml-2">#{{ $risk->grp_id }}</span>
                        </span>
                        <span class="status status-blue mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                            <img src="{{ asset('images/icons/group.png') }}" alt="group_icon" style="width: 17px">
                            <span class="ml-2">{{ $risk->riskgroup }}</span>
                        </span>
                        <span class="status status-warning mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                            <img src="{{ asset('images/icons/work.png') }}" alt="work_icon" style="width: 18px">
                            <span class="ml-2">{{ $risk->department ? $risk->department->departmentname : '-'}}</span>
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
                        <label>Current Process Stage</label>
                        <div class="d-flex mt-3 ml-3 process-stage">
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-under-review-action @if($risk->action_status != 'Under review') hide-action @else bg-warning @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-reject-action @if($risk->action_status != 'Rejected') hide-action @else bg-danger @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-awaiting-periodic-review-action @if($risk->action_status != 'Awaiting Periodic Review') hide-action @else bg-warning @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-active-risk-action @if($risk->action_status != 'Active Risk') hide-action @else bg-success @endif"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between d-block-sp d-block-643 d-block-400 d-block-768">
                        <label>No. of Escalated Times: {{ $risk->number_of_escalated_times }} times</label>
                        <br class="d-none d-block-sp d-block-643 d-block-400 d-block-768">
                        <label>Target Date: {{ $risk->action_target }}</label>
                    </div>
                    <div class="d-flex justify-content-between d-block-sp d-block-643 d-block-400 d-block-768">
                        <label>Last time escalated: {{ $risk->last_escalated_time ? date('d-m-Y', strtotime($risk->last_escalated_time)) : '' }}</label>
                        <br class="d-none d-block-sp d-block-643 d-block-400 d-block-768">
                        <label>Initial Reviewed: {{ $risk->review_date ? date('d-m-Y', strtotime($risk->review_date)) : '' }}</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-flex px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 20px" class="col-md-12">
                    <label class="mt-3 ml-4"><i class="fa fa-clipboard-list mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Details</label>
                    <div class="mt-4 px-5 d-flex d-block-sp px-adjust">
                        <div class="observation col-md-7">
                            <label>
                                {{ $risk->riskname }}
                            </label>
                            <p class="text-justify" style="word-break: keep-all">{!! nl2br(strip_tags($risk->riskdec)) !!}</p>
                        </div>
                        <div class="col-md-5 justify-content-center d-block-sp">
                            <div>
                                <label>Risk Owner</label><br>
                                <div class="align-items-center">
                                    @php
                                        $riskOwnerNames = [];
                                    @endphp
                                    @foreach($riskOwnerData as $email => $riskowner)
                                        @if($riskowner['profile_picture'] && File::exists(public_path('/storage/profile/'. $riskowner['profile_picture'])))
                                            <img class="img-avatar multiple-avatar img-avatar48" alt="{{ $riskowner['name'] }}"
                                                 src="{{ asset('/storage/profile/'. $riskowner['profile_picture']) }}">
                                        @else
                                            <img class="img-avatar multiple-avatar
                                                img-avatar48" alt="No picture"
                                                 src="{{asset('/images/avatar10.jpg')}}" />
                                        @endif
                                            @php
                                                $riskOwnerNames[] = $riskowner['name'];
                                            @endphp
                                    @endforeach
                                    <p class="font-weight-bold mb-2 mt-2">{{ count($riskOwnerNames) ? implode(', ', $riskOwnerNames) : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 px-5 px-adjust">
                        <label class="ml-3">Risk Drivers & Existing Controls</label>
                        <div class="mr-2 mt-2 d-block-sp responsive-block">
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-3 bg-white mr-4 p-3 w-change mb-2-sp" style="border-radius: 0.5rem">
                                <label>Risk Drivers</label>
                                @if($risk->riskdri)
                                    <p style="word-break: keep-all">{{ $risk->riskdri }}</p>
                                @else
                                    <p class="span text-danger">No risk drivers added</p>
                                @endif
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12 mb-3 bg-white mr-4 p-3 w-change mb-2-sp" style="border-radius: 0.5rem">
                                <label>Existing controls</label>
                                @if($risk->existing_controls)
                                    <p style="word-break: keep-all">{{ $risk->existing_controls }}</p>
                                @else
                                    <p class="span text-danger">No existing control added</p>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="gap-responsive mt-3 px-5 px-adjust pr-6 d-flex flex-row flex-wrap justify-content-start">
                        <div class="card20 text-center d-flex flex-column">
                            <label>Risk Source</label>
                            @if($risk->riskscr)
                                <span>{{ $risk->riskscr }}</span>
                            @else
                                <span class="text-danger">No risk source added</span>
                            @endif
                        </div>

                        <div class="card20 text-center d-flex flex-column">
                            <label>Risk Response</label>
                            @if($risk->risk_response)
                                <span>{{ $risk->risk_response }}</span>
                            @else
                                <span class="text-danger">No risk response added</span>
                            @endif
                        </div>

                        @if ($risk->riskreviewer)
                            <div class="card20 reviewer-card d-flex flex-row align-items-start">
                                <span class="font-weight-bold">
                                    @if (optional($risk->riskreviewer)->profile_picture
                                        && File::exists(public_path('/storage/profile/'. $risk->riskreviewer->profile_picture)))
                                        <img class="img-avatar img-avatar48"
                                            alt="{{ $risk->riskreviewer->name }}"
                                            src="{{ asset('/storage/profile/'. $risk->riskreviewer->profile_picture) }}">
                                    @else
                                        <img class="img-avatar img-avatar48"
                                            alt="No picture"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    @endif
                                </span>

                                <div class="d-flex flex-column ml-2">
                                    <label>Risk Reviewer</label>
                                    <span>{{ $risk->riskreviewer->name }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-1 px-5 px-adjust">
                        <div class="d-flex ml-3">
                            <div>
                                <label>Impact</label><br>
                                @if($risk->impa == "High")
                                    <span class="status status-red">{{ $risk->impa }}</span>
                                @elseif($risk->impa == "Moderate")
                                    <span class="status status-warning">{{ $risk->impa }}</span>
                                @else
                                    <span class="status status-success">{{ $risk->impa }}</span>
                                @endif
                            </div>
                            <div class="ml-5">
                                <label>Likelihood</label><br>
                                @if($risk->like == "High")
                                    <span class="status status-red">{{ $risk->like }}</span>
                                @elseif($risk->like == "Moderate")
                                    <span class="status status-warning">{{ $risk->like }}</span>
                                @else
                                    <span class="status status-success">{{ $risk->like }}</span>
                                @endif
                            </div>
                            <div class="ml-5">
                                <label>Risk Level</label><br>
                                @if($risk->rank == "High")
                                    <span class="status status-red">{{ $risk->rank }}</span>
                                @elseif($risk->rank == "Moderate")
                                    <span class="status status-warning">{{ $risk->rank }}</span>
                                @else
                                    <span class="status status-success">{{ $risk->rank }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 px-5 px-adjust d-flex justify-content-between">
                        <div class="ml-3 w-75">
                            <label>Mitigation action status</label>
                            <div>
                                @if(count($risk->mitigations))
                                    @foreach($risk->mitigations as $mitigation)
                                        <div>
                                            <div class="d-flex align-items-baseline">
                                                <a href="{{ route('mitigations.detail', $mitigation->id) }}" target="_blank">
                                                    <span class="status status-blue">{{ $mitigation->action_id }}</span>
                                                </a>
                                                <span class="ml-2" style="color: #878787">{{ $mitigation->action_name }}</span>
                                            </div>
                                            <div class="w-50 mt-2">
                                                @if ($mitigation->progress == 25)
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress" style="height: 5px;width: 90%;margin-right: 0.5rem;">
                                                            <div class="progress-bar" role="progressbar"
                                                                 style="width: 25%; background: #44B3AB" aria-valuenow="25" aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                        <p class="fs-xs fw-semibold mb-0" style="font-style: italic; font-weight: bold">25%</p>
                                                    </div>
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
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-danger">No action status added</p>
                                @endif
                            </div>
                        </div>
                        <div class="ml-3">
                            <label>Opportunity ID</label>
                            @if(count($risk->opportunities))
                                @foreach($risk->opportunities as $oppdata)
                                    <div class="mb-2">
                                        <a href="#">
                                            <span class="status status-blue">{{ $oppdata->dep_id }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-danger">No opportunity added</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem">
                    <label class="mt-3 ml-4"><i class="fa fa-chart-line mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Timeline & History</label>
                    <div class="d-flex justify-content-center pb-1">
                        <div class="col-md-6 col-sm-6">
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Added By & Date</label>
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Initial Reviewed By & Date</label>
                        </div>
                        <div class="col-md-1 d-none-643" style="padding-left: 2.5rem; padding-right: 2.5rem;">
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto;"></span>
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="d-flex align-items-center" style="margin-top: 2rem;">
                                @if($risk->postedby == '')
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @else
                                    @if($risk->postedByRisk && $risk->postedByRisk->profile_picture && File::exists(public_path('/storage/profile/'. $risk->postedByRisk->profile_picture)))
                                        <img class="img-avatar mr-3" alt="{{ $risk->postedByRisk->name }}"
                                             style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                             src="{{ asset('/storage/profile/'. $risk->postedByRisk->profile_picture) }}">
                                    @else
                                        <img class="img-avatar mr-3" alt="No picture"
                                             style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                             src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $risk->postedby == '' ? 'admin' : ($risk->postedByRisk ? $risk->postedByRisk->name : '') }}</label><br>
                                    <label>on {{ $risk->created_at ? date('d-m-Y', strtotime($risk->created_at)) : '-' }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                @if($risk->approved_by && $risk->approvedByRisk && $risk->approvedByRisk->profile_picture && File::exists(public_path('/storage/profile/'. $risk->approvedByRisk->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $risk->approvedByRisk->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'. $risk->approvedByRisk->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $risk->approved_by && $risk->approvedByRisk ? $risk->approvedByRisk->name : 'Not Specified' }}</label><br>
                                    <label>on {{ $risk->review_date ? date('d-m-Y', strtotime($risk->review_date)) : '-' }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center pb-3">
                        <button class="btn btn-primary btn-toggle-history" value="load"
                                style="box-shadow: 0px 0px 0px 1px #5E5ADB; box-shadow: 0px 1px 1px 0px #0000001A; font-size: 12px">
                            <span><i class="fa fa-eye"></i></span>
                            <span class="ml-1">Load Periodic Reviews</span>
                        </button>
                    </div>
                    <div class="mt-2 col-md-10 offset-md-1 m-auto table-risk-history"
                         style="overflow-x:auto; display: none">
                        <table class="risk-module-table table table-bordered table-custom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Reviewed by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($riskHistories))
                                    @foreach($riskHistories as $index => $riskHistory)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left">
                                            @if(
                                                $riskHistory->impa == NULL
                                                && $riskHistory->like == NULL
                                                && $riskHistory->rank== NULL
                                            )
                                                --
                                            @endif
                                            @if($riskHistory->impa != NULL && $riskHistory->action != NULL)
                                                <span>
                                                    Impact changed from
                                                    @if($riskHistory->impa == "High")
                                                        <span class="status status-red">{{$riskHistory->impa }}</span>
                                                    @elseif($riskHistory->impa == "Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa }}</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->impa_updated == "High")
                                                        <span class="status status-red">{{$riskHistory->impa_updated }}</span>
                                                    @elseif($riskHistory->impa_updated == "Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa_updated }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa_updated }}</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->impa != NULL && $riskHistory->action == NULL)
                                                <span>
                                                    Impact changed from
                                                    @if($riskHistory->impa=="High")
                                                        <span class="status status-red">{{$riskHistory->impa }}</span>
                                                    @elseif($riskHistory->impa=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa }}</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->like != NULL && $riskHistory->action != NULL)
                                                <br><br>
                                                <span>
                                                    Likelihood changed from
                                                    @if($riskHistory->like=="High")
                                                        <span class="status status-red">{{$riskHistory->like }}</span>
                                                    @elseif($riskHistory->like=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like }}</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->like_updated=="High")
                                                        <span class="status status-red">{{$riskHistory->like_updated }}</span>
                                                    @elseif($riskHistory->like_updated=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like_updated }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like_updated }}</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->like != NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>
                                                    Likelihood changed from
                                                    @if($riskHistory->like=="High")
                                                        <span class="status status-red">{{$riskHistory->like }}</span>
                                                    @elseif($riskHistory->like=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like }}</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->rank!=NULL && $riskHistory->action!=NULL)
                                                <br><br>
                                                <span>
                                                    Risk changed from
                                                    @if($riskHistory->rank == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->rank_updated == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank_updated == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->rank!=NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>
                                                    Risk changed from
                                                    @if($riskHistory->rank == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->mitigating_action!=NULL && $riskHistory->action!=NULL)
                                                <br><br>
                                                <span>
                                                    Mitigating Action changed from <span>{{$riskHistory->mitigating_action}} </span>to <span>{{$riskHistory->mitigating_action_updated}}</span>
                                                </span>
                                            @elseif($riskHistory->mitigating_action!=NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>Mitigating Action changed from <span> {{$riskHistory->mitigating_action}}</span></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d-m-Y',strtotime($riskHistory->created_at)) }}</td>
                                        <td class="text-danger font-weight-bold">{{ $riskHistory->riskOwner ? $riskHistory->riskOwner->name : '--' }}</td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-danger">No matching records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row pb-4 px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem; padding-bottom: 1rem">
                    <label class="mt-3 ml-4"><img src="https://cdn.iconscout.com/icon/free/png-256/free-forum-1779761-1513575.png" class="mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px; width: 30px"> User Interaction</label>
                    <div class="d-block mt-4 ml-6">
                        <label>Disapproval Reason</label>
                        @if($risk->disapprovereason)
                            <p>{{ $risk->disapprovereason }}</p>
                        @else
                            <p class="text-danger">No disapproval reason added</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="block block-rounded">
                <ul class="nav nav-tabs nav-tabs-alt align-items-center comment-padding" data-toggle="tabs" role="tablist" id="myTab">
                    <li class="nav-item">
                        <a class="nav-link" href="#ecom-product-comments" data-toggle="tab" id="totalcomments">
                         <i class="far fa-comment-dots" style="font-size:12px;"></i>
                          Comments ({{$risk_comments_count}})
                        </a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <!-- Comments -->
                    <div class="tab-pane pull-x font-size-sm comment-padding active" id="ecom-product-comments" role="tabpanel">
                        @foreach($risk_comments as $comment)
                            @if(!$comment->is_deleted && $comment->parent_id == 0 && ($comment->admin || $comment->user))
                                @include('user.risks._comment', ['comment' => $comment])
                            @endif
                        @endforeach

                        <form id='comment_form' method="post" action="{{ route('riskcomment.store') }}"
                            enctype="multipart/form-data">
                            <div class="form-group mt-2" id="comment_div">
                                <div class="input-group">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="comment"
                                            placeholder="Write a comment..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeComment()">
                                            <i class="fas fa-paper-plane" style=""></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#comment'), {
                                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                        'numberedList', 'blockQuote', 'undo', 'redo'
                                    ],
                                }).then(commentEditor => {
                                    window.commentEditor = commentEditor;
                                }).catch(error => {
                                    console.error(error);
                                });
                        </script>
                        <form id='reply_form' method="post" action="{{ route('riskcomment.store') }}"
                            enctype="multipart/form-data" class="d-none">
                            <div class="form-group mt-2" id="reply_div">
                                <div class="input-group">
                                    <input type="hidden" name="comment_id" id="comment_id" value="">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="reply"
                                            placeholder="Write a reply..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeReply()">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#reply'), {
                                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                        'numberedList', 'blockQuote', 'undo', 'redo'
                                    ],

                                }).then(replyEditor => {
                                    window.replyEditor = replyEditor;
                                }).catch(error => {
                                    console.error(error);
                                });
                        </script>
                        <form id='editcomment_form' method="post"
                            enctype="multipart/form-data" class="d-none">
                            <div class="form-group mt-2" id="editcomment_div">
                                <div class="input-group">
                                    <input type="hidden" name="editcomment_id" id="editcomment_id" value="">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="editcomment"
                                            placeholder="Edit comment..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeEditComment()">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#editcomment'), {
                                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                        'numberedList', 'blockQuote', 'undo', 'redo'
                                    ],

                                }).then(editCommentEditor => {
                                    window.editCommentEditor = editCommentEditor;
                                }).catch(error => {
                                    console.error(error);
                                });
                        </script>
                    </div>
                    <!-- END Comments -->
                </div>
            </div>
            <div class="mt2 pb-5 px-5 d-block row px-xs-adjust">
                <div class="d-flex justify-content-end">
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
                    $(this).html('<span><i class="fa fa-eye-slash"></i></span><span class="ml-1">Hide Periodic Reviews</span>');
                } else {
                    $(this).val('load');
                    $(this).html('<span><i class="fa fa-eye"></i></span><span class="ml-1">Load Periodic Reviews</span>');
                }
            })
        });
    </script>
    <!--**********************************************Like Comment Reply START***********************************-->
    @php
        $userId = App\User::where('email',Auth::User()->email)->value('id');
    @endphp
    <script type="text/javascript">
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let comment_form_selector = document.getElementById('comment_form');
        let reply_form_selector = document.getElementById('reply_form');
        let reply_comment_id = document.getElementById("comment_id");
        let editcomment_form_selector = document.getElementById('editcomment_form');
        let editcomment_id = document.getElementById("editcomment_id");
        const risk_id = "{{ $risk->Rid }}";
        const userId = "{{$userId}}";
        // Hide reply form and show comment form on document click
        document.addEventListener('click', function(event) {
            if(!reply_comment_id){
                reply_comment_id = document.getElementById("comment_id");
                reply_comment_id.value = '';
            }else{
                reply_comment_id.value = '';
            }
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
                editcomment_id.value = '';
            }else{
                editcomment_id.value = '';
            }
            replyEditor.setData('');
            editCommentEditor.setData('');
            comment_form_selector.classList.remove('d-none');
            reply_form_selector.classList.add('d-none');
            editcomment_form_selector.classList.add('d-none');
        });
        // Prevent hiding reply form on clicking inside it
        document.getElementById('reply_form').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        document.getElementById('editcomment_form').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        function storeComment() {
            let store_comment_url =  window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/add`;

            if(!reply_comment_id){
                reply_comment_id = document.getElementById("comment_id");
                reply_comment_id.value = '';
            }else{
                reply_comment_id.value = '';
            }
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
                editcomment_id.value = '';
            }else{
                editcomment_id.value = '';
            }
            $("form#comment_form").off("submit").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#comment').val(),
                        risk_id: risk_id,
                        reply_id: $('#comment_id').val(),
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.success){
                            commentEditor.setData('');
                            replyEditor.setData('');
                            editCommentEditor.setData('');
                            addCommentToDOM(response.data);
                            $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                                Comments (${response.data.totalcomments})`);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
        }

        function reply(id) {
            event.stopPropagation();
            if(!reply_comment_id){
                reply_comment_id = document.getElementById("comment_id");
            }
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
                editcomment_id.value = '';
            }else{
                editcomment_id.value = '';
            }
            commentEditor.setData('');
            replyEditor.setData('');
            editCommentEditor.setData('');
            reply_comment_id.value = id;
            editcomment_form_selector.classList.add('d-none');
            comment_form_selector.classList.add('d-none');
            reply_form_selector.classList.remove('d-none');
        }

        function storeReply() {
            let store_comment_url = window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/add`;
            $("form#reply_form").off("submit").on("submit", function(e)  {
                e.preventDefault();
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#reply').val(),
                        risk_id: risk_id,
                        reply_id: $('#comment_id').val(),
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.success){
                            commentEditor.setData('');
                            editCommentEditor.setData('');
                            addReplyToDOM(response.data);
                            $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                                Comments (${response.data.totalcomments})`);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
            reply_comment_id = null;
            reply_form_selector.classList.add('d-none');
            comment_form_selector.classList.remove('d-none');
        }

        function deleteComment(id) {
            let delete_comment_url = window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/delete/${id}`;
            $.ajax({
                url: delete_comment_url,
                type: 'GET',
                headers: {
                    'accept': 'application/json'
                },
                success: function(response) {
                    if(response.success){
                        $(`#comment${id}`).remove();
                        $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                            Comments (${response.totalcomments})`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });

        }

        function deleteReply(id) {
            let delete_reply_url = window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/delete-reply/${id}`;
            $.ajax({
                url: delete_reply_url,
                type: 'GET',
                headers: {
                    'accept': 'application/json'
                },
                success: function(response) {
                    if(response.success){
                        $(`#comment-reply${id}`).remove();
                        $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                            Comments (${response.totalcomments})`);
                    }
                },
                error: function(err) {
                    console.log(err);

                }

            });

        }
        function addCommentToDOM(comment) {
            if (!comment.is_reply) {
                $('#ecom-product-comments').find('#comment_form').before(`
                    <div class="media push" style="padding-bottom:6px;margin-bottom:0;" id="comment${comment.id}">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="${comment.profile_picture}" alt="">
                        </a>
                        <div class="media-body">
                            <a class="font-w600" href="javascript:void(0)">${comment.user_name}</a>
                            <span id="commenttext${comment.id}"></span>
                            ${comment.user_id == userId ? `<a class="mr-1" href="javascript:void(0)" onclick="editComment('${comment.id}','${comment.comment}')"><span style="font-size:11px;">Edit</span></a><a class="mr-1" href="javascript:void(0)" onclick="deleteComment('${comment.id}')"><span style="font-size:11px;">Delete</span></a>` : ''}
                            <a class="mr-1" onclick="reply('${comment.id}')"><span style="font-size:11px;color:#5179d6;cursor:pointer;">Reply</span></a>
                            <span class="text-muted"><em><span style="font-size:11px;" id="updatedat${comment.id}">${comment.created_at}</span></em></span>
                            <div id="replysection${comment.id}"></div>
                        </div>
                    </div>
                `);
                $(`#commenttext${comment.id}`).append(decodeHTML(comment.comment));
            }
        }
        function addReplyToDOM(comment) {
            if (comment.is_reply) {
                $(`#ecom-product-comments #comment${comment.parent_id}`).find(`#replysection${comment.parent_id}`).append(`
                    <a class="mr-3" href="javascript:void(0)">
                        <img class="img-avatar img-avatar32" src="${comment.profile_picture}" alt="">
                    </a>
                    <div class="media-body">
                        <a class="font-w600" href="javascript:void(0)">${comment.user_name}</a>
                        <span id="commenttext${comment.id}"></span>
                        ${comment.user_id == userId ? `<a class="mr-1" href="javascript:void(0)" onclick="editComment('${comment.id}','${comment.comment}')"><span style="font-size:11px;">Edit</span></a><a class="mr-1" href="javascript:void(0)" onclick="deleteReply('${comment.id}')"><span style="font-size:11px;">Delete</span></a>` : ''}
                        <span class="text-muted"><em><span style="font-size:11px;" id="updatedat${comment.id}">${comment.created_at}</span></em></span>
                    </div>
                `).addClass(['media','mt-2']).css({'font-size':'13px','padding-bottom':'6px','margin-bottom':'0'}).attr('id',`comment-reply${comment.id}`);
                $(`#commenttext${comment.id}`).append(decodeHTML(comment.comment));
                $(`#comment-reply${comment.id}`).after(`
                    <div id="replysection${comment.parent_id}"></div>
                `);
            }
        }
        function decodeHTML(html) {
            var txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        //Edit functionality
        function editComment(id,commentvalue) {
            event.stopPropagation();
            try {
                commentvalue = JSON.parse(commentvalue);
            } catch (error) {
                // console.log(error.getMessage());
            }
            editCommentEditor.setData(commentvalue);
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
            }
            editcomment_id.value = id;
            editcomment_form_selector.classList.remove('d-none');
            comment_form_selector.classList.add('d-none');
            reply_form_selector.classList.add('d-none');
        }
        function storeEditComment() {
            let store_comment_url =  window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/edit-comment`;
            $("form#editcomment_form").off("submit").on("submit", function(e) {
                e.preventDefault();
                var cid = $('#editcomment_id').val();
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#editcomment').val(),
                        risk_id: risk_id,
                        comment_id: cid,
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.success){
                            commentEditor.setData('');
                            replyEditor.setData('');
                            editCommentEditor.setData('');
                            $(`#commenttext${cid}`).html(response.data.comment);
                            $(`#updatedat${cid}`).text(response.data.created_at);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
        }
    </script>
    <!--**********************************************Like Comment Reply END***********************************-->

@endsection
