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
            font-weight:bolder !important;
            background-color:#cbe7e6;
            border-color: #bff0dd;
        }
        .process-stage-point {
            position: relative;
        }
        .process-stage-point span {
            background: #CFCFCF;
            position: relative;
            font-size: 0.65rem;
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
        .process-stage-point span.span-awaiting-action::after {
            content: 'Awaiting actions';
        }
        .process-stage-point span.span-action-proposed::after {
            content: 'Actions Proposed';
        }
        .process-stage-point span.span-action-reviewed-ok::after {
            content: 'Action Approved';
        }
        .process-stage-point span.span-action-reviewed-not-ok::after {
            content: 'Action Rejected';
        }
        .process-stage-point span.span-need-more-action::after {
            content: 'Need More Action';
        }
        .process-stage-point span.span-action-completed::after {
            content: 'Actions Completed';
        }
        .process-stage-point span.span-correction-proposed-rc-awaiting-action::after {
            content: 'Correction Proposed- RC Awaiting';
        }
        .process-stage-point span.span-proposed-review-awaiting-action::after {
            content: 'Actions Proposed- Review Awaiting';
        }
        .process-stage-point span.span-rc-update-correction-awaiting-action::after {
            content: 'RC Updated- Correction Awaiting';
        }
        .process-stage-point span.span-rejected::after {
            content: 'Entry Rejected';
        }
        .process-stage-point span.span-entry-under-review-action::after {
            content: 'Entry Under Review';
            left: 0rem;
        }
        .process-stage-point span.span-close-action:after {
            content: 'Closed';
            left: -10px;
        }
        .process-stage-point:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 35%;
            left: 25%;
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
                margin-right: 2vw !important;
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
                margin-right: 3.5vw !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 2.5vw !important;
            }
        }
        @media only screen and (min-width: 643px) and (max-width: 767px) {
            .content {
                padding: 1rem 1rem 1px !important;
            }
            .d-block-sp {
                display: block !important;
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
            .process-stage .process-stage-point span {
                margin-right: 4.75vw !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 2vw !important;
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
                margin-right: 1rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 40%;
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
        }
        @media only screen and (min-width: 807px) and (max-width: 992px) {
            .process-stage .process-stage-point span {
                margin-right: 1.25rem !important;
            }
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
                margin-right: 2.075rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 30%;
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
                margin-right: 2.5rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 28%;
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
        .scrollable{
            height:100%;
            overflow-x: scroll;
            overflow-y: scroll;
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
            /* 1. Force the container to stack vertically */
            .maintage-adjust {
                display: block !important; /* Overrides flexbox */
            }

            /* 2. Make both inner divs full width */
            .maintage-adjust > div {
                width: 100% !important;
                margin-right: 0 !important;
                margin-bottom: 20px !important; /* Adds spacing between the rows */
            }

            /* 3. Explicitly override the inline width: 65% on the second div */
            #processStage {
                width: 100% !important;
            }
        }
    </style>
    <!-- Page Content -->
    <div class="content px-0" style="-webkit-print-color-adjust: exact">
        <div class="alert alert-success w-100 d-none mt-2" id="success_action" style="font-size:13px;"></div>
    <ul class="alert alert-danger w-100 d-none mt-2" id="error_action" style="font-size:13px;">
        Internal server error, we are trying to fix them soon
    </ul>
    <div class="alert alert-success d-none w-100 mt-2" id="success_issue" style="font-size:12px;"></div>
    <ul class="alert alert-danger d-none w-100 mt-2" id="error_issue" style="font-size:12px;">
        Internal server error, we are trying to fix them soon
    </ul>
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <span class="mr-2">NCR & Corrective Actions</span>
                    <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">{{ $corrective->title }}</span>
                </div>
                <div class="d-flex-right p-2 d-flex d-block">
                    <div class="d-flex mr-4 border-right">
                        @php
                            $resp_user_image = DB::table('users')->where('email',$corrective->responsibility)->value('profile_picture');
                            $posted_user_image = DB::table('users')->where('employeeid',$corrective->postedby)->value('profile_picture');
                        @endphp
                        @if($resp_user_image && File::exists(public_path('/storage/profile/'.$resp_user_image)))
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$resp_user_image) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src=" {{asset('/images/avatar10.jpg')}}" />
                        @endif

                        @if($posted_user_image && File::exists(public_path('/storage/profile/'.$posted_user_image)))
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$posted_user_image) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src=" {{asset('/images/avatar10.jpg')}}" />
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
                                <a class="dropdown-item" href="{{ route('corrective.exportPdf', $corrective->id) }}">PDF</a>
                                {{--<a class="dropdown-item" href="javascript:void(0)">Excel</a>--}}
                                {{-- <a class="dropdown-item" href="javascript:void(0)">Word</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-flex justify-content-around d-block-sp px-xs-adjust maintage-adjust" style="gap: 20px;">
                <div class="px-4 pb-3 mb-2-sp mr-xs-adjust" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;">
                    <label class="mt-2 ml-2"><i class="fa fa-eye mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Overview</label>
                    <div class="d-flex" style="flex-flow: wrap">
                        <span class="status status-red mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                            <img src="{{ asset('images/icons/vpn_key.png') }}" alt="issueid_icon" style="width: 16px">
                            <span class="ml-2">#{{ $corrective->issueid }}</span>
                        </span>
                        @if(!($corrective->type=='-' || $corrective->type==''))
                            <span class="status status-warning mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center;background-color:{{ $corrective->typ ? $corrective->typ->background_color :''}};
                            color:{{ $corrective->typ ?  $corrective->typ->text_color : ''}};">
                                <img src="{{ asset('images/icons/type_' . ($corrective->typ ? $corrective->typ->order : '') . '.png') }}" alt="type_icon" style="width: 16px;border-radius: 5px;">
                                <span class="ml-2">{{ $corrective->typ ? $corrective->typ->name : '' }}</span>
                            </span>
                        @endif
                        @if($dept)
                            <span class="status status-blue mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                                <img src="{{ asset('images/icons/department-icon.png') }}" alt="department_icon" style="width: 16px;border-radius: 5px;">
                                <span class="ml-2">{{ $dept }}</span>
                            </span>
                        @endif
                        @if($corrective->division_id != 0)
                            <span class="status status-blue mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                                <img src="{{ asset('images/icons/division-subdivision-icon.png') }}" alt="division_icon" style="width: 16px;border-radius: 5px;">
                                <span class="ml-2">{{ App\Division::where('id',$corrective->division_id)->value('name') ?? '-'}}</span>
                            </span>
                        @endif

                        @if($corrective->area_id !=0)
                            <span class="status status-blue mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                                <img src="{{ asset('images/icons/division-subdivision-icon.png') }}" alt="subdivision_icon" style="width: 16px;border-radius: 5px;">
                                <span class="ml-2">{{ App\Area::where('id',$corrective->area_id)->value('name') ?? '-'}}</span>
                            </span>
                        @endif
                        @if($cat)
                            <span class="status status-flat mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center; word-wrap: break-word; white-space: normal;">
                                <img src="{{ asset('images/icons/category-icon.png') }}" alt="category_icon" style="width: 16px;border-radius: 5px; flex-shrink: 0;">
                                <span class="ml-2 text-left" style="word-break: break-word;">{{ $cat }}</span>
                            </span>
                        @endif
                        @if($corrective->source)
                            <span class="status status-flat mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                                <img src="{{ asset('images/icons/source-icon.png') }}" alt="source_icon" style="width: 18px;border-radius: 5px;">
                                <span class="ml-2">{{ $corrective->source }}</span>
                            </span>
                        @endif
                        @if($corrective->categoryDetectionStage)
                            <span class="status status-warning mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem; display: inline-flex; align-items: center">
                                <img src="{{ asset('images/icons/detection_stage_icon.png') }}" alt="source_icon" style="width: 18px;border-radius: 5px;">
                                <span class="ml-2">{{ $corrective->categoryDetectionStage->name }}</span>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="px-2" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;" id="processStage">
                    {!! $processStage !!}
                </div>
            </div>
            @if($ncrFields)
                {!!$ncrFields!!}
            @endif
            <div class="mt-4 px-5 d-flex px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 20px" class="col-md-12">
                    <label class="mt-3 ml-4"><i class="fa fa-clipboard-list mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Details</label>
                    <div class="mt-4 px-5 d-flex d-block-sp px-adjust">
                        <div class="observation col-md-7">
                            <label>
                                @if($cat=="Incident Investigation")
                                    Incident # {{$corrective->title}}
                                @else
                                    {{ $corrective->title }}
                                @endif
                            </label>
                            <p class="text-justify">{!! nl2br(strip_tags($corrective->observation)) !!}</p>
                            <label>
                                Recommended Action
                            </label>
                            @if($corrective->agreedaction)
                                <p class="text-justify">{!! nl2br(strip_tags($corrective->agreedaction)) !!}</p>
                            @else
                                <p class="text-secondary fw-500">No details provided</p>
                            @endif
                        </div>
                        <div class="col-md-6 justify-content-center d-block-sp">
                            <p class="m-1" style="margin-bottom: 15px; font-weight: 600;">
                                Financial Cost & Potential Cost Savings
                            </p>
                            <div class="mb-1 @if($corrective->financial_loss) col-lg-7 col-md-9 col-sm-12 @else col-lg-8 col-md-9 col-sm-12 @endif px-3 py-2" style="background: #AF1E1E3D; border-radius: 10px;">
                                <img src="{{ asset('images/icons/financial_icon.png') }}"
                                     class="mr-1"
                                     style="background: #AF1E1E;width: 24px;height: 24px;padding: 5px;border-radius: 5px;">
                                <span class="font-weight-bold" style="color: #AF1E1E; overflow-wrap: anywhere;  word-break: break-all;">
                                    @if($corrective->financial_loss)
                                        {{ $corrective->financial_loss }}
                                    @else
                                        No financial details provided
                                    @endif
                                </span>
                            </div>
                            <div class="@if($corrective->potential_cost_savings) col-lg-7 col-md-9 @else col-lg-8 col-md-9 @endif px-3 py-2" style="background: #1760443D; border-radius: 10px;">
                                <img src="{{ asset('images/icons/money.png') }}"
                                     class="mr-1"
                                     style="width: 24px;height: 24px;border-radius: 5px;">
                                <span class="font-weight-bold" style="color: #176044; text-align:justify; overflow-wrap: anywhere; word-break: break-all; word-wrap: break-word;">
                                    @if($corrective->potential_cost_savings)
                                        {{ $corrective->potential_cost_savings }}
                                    @else
                                        No cost savings recorded
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($corrective->is_previously_occurred=='yes')
                    <div class="mt-5 px-5 px-adjust col-sm-7">
                        <label class="ml-3">Issue History</label>
                        <div class="ml-3 mr-5 mt-2 d-block-sp bg-white p-2 correction-block-adjust" style="border-radius: 10px">
                            @if(($corrective->no_of_occurrence==0 || $corrective->no_of_occurrence==null) && $corrective->previous_ncr_reference==null)
                                <label class="ml-2">
                                    <span class="py-1 px-1 text-center" style="background: #2529453D;border-radius: 5px; font-size: 13px;">
                                        <i class="fa fa-sync" style="font-size:11px;"></i>
                                    </span>
                                    &nbsp;
                                    This issue has been previously recorded.
                                </label>
                            @elseif(($corrective->no_of_occurrence!=0 && $corrective->no_of_occurrence!=null) && $corrective->previous_ncr_reference==null)

                                <label class="ml-2">
                                    <span class="py-1 px-1 text-center" style="background: #2529453D;border-radius: 5px; font-size: 13px;">
                                        <i class="fa fa-sync" style="font-size:11px;"></i>
                                    </span>
                                    &nbsp; This issue has recurred <u>{{ $corrective->no_of_occurrence }}</u> times.
                                </label>
                            @elseif($corrective->previous_ncr_reference==0 && $corrective->other_previous_ncr_reference!=null)
                                @if($corrective->no_of_occurrence!=0 || $corrective->no_of_occurrence!=null)
                                    <label class="ml-2">
                                        <span class="py-1 px-1 text-center" style="background: #2529453D;border-radius: 5px; font-size: 13px;">
                                            <i class="fa fa-sync" style="font-size:11px;"></i>
                                        </span>
                                        &nbsp;
                                        This issue has recurred <u>{{ $corrective->no_of_occurrence }}</u> times.
                                    </label>
                                @endif
                                <div>
                                    <label class="ml-2">External Reference:</label>
                                    <span class="status status-blue mr-3 px-3 py-2" style="border-radius: 0.5rem;font-size: 11px;">{{ $corrective->other_previous_ncr_reference }}</span>
                                </div>
                            @else
                                @if($corrective->no_of_occurrence!=0 || $corrective->no_of_occurrence!=null)
                                    <label class="ml-2">
                                        <span class="py-1 px-1 text-center" style="background: #2529453D;border-radius: 5px; font-size: 13px;">
                                            <i class="fa fa-sync" style="font-size:11px;"></i>
                                        </span>
                                        &nbsp; This issue has recurred <u>{{ $corrective->no_of_occurrence }}</u> times.</label>
                                @endif
                                <div class="ml-3">
                                    <a href="{{ route('corrective.detail', $corrective->previous_ncr_reference) }}" class="ml-3">
                                        @if($corrective->previousNcrReferenceRelation)
                                        <span class="status status-blue mr-3 px-3 py-2" style="border-radius: 0.5rem;font-size: 11px;" data-toggle="tooltip" title="Click to view previous NCR details">
                                                {{ $corrective->previousNcrReferenceRelation->issueid }} - {{ $corrective->previousNcrReferenceRelation->title }}
                                        </span>
                                        @else
                                            <span class="text-secondary fw-500">No details Provided</span>
                                        @endif
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="mt-5 px-5 px-adjust">
                        <label class="ml-3">Correction & Root Cause</label>
                        <div class="ml-3 mr-5 mt-2 d-block-sp bg-white p-2 correction-block-adjust" style="border-radius: 10px">
                            <div class="d-flex mb-3 d-block-sp">
                                <div class="observation col-md-7">
                                    <label>Correction</label>
                                    @if($corrective->correction)
                                        <p class="text-justify">{{ $corrective->correction }}</p>
                                    @else
                                        <p class="text-secondary fw-500">No corrections added</p>
                                    @endif
                                </div>
                                @php
                                    $responsibilities = explode(',', $corrective->correction_responsibility);
                                    $resp_to_show = App\User::whereIn('email', $responsibilities)->pluck('profile_picture', 'name')->toArray();
                                    $respNames = implode(', ', array_keys($resp_to_show));
                                @endphp
                                <div class="col-md-5 justify-content-center d-block-sp">
                                    <div>
                                        <label>Responsibility</label><br>
                                        <div class="align-items-center">
                                            @foreach($resp_to_show as $name => $picture)
                                                @if($picture && File::exists(public_path('/storage/profile/'.$picture)))
                                                    <img class="img-avatar multiple-avatar img-avatar48" alt="{{ $name }}"
                                                         src="{{ asset('/storage/profile/'.$picture) }}">
                                                @else
                                                    <img class="img-avatar multiple-avatar
                                                img-avatar48" alt="No picture"
                                                         src="{{asset('/images/avatar10.jpg')}}" />
                                                @endif
                                            @endforeach
                                            <p class="mb-2 mt-2 {{ $respNames ? 'font-weight-bold' : 'text-secondary fw-500' }}">{{ $respNames ? $respNames : 'Responsibility not assigned yet' }}</p>
                                            <div class="d-flex responsibility-dep-date-adjust">
                                                @if($dept)
                                                    <div class="mr-2 p-2" style="background: #AF6F1E3D; border-radius: 5px">
                                                        <img src="{{ asset('images/icons/quality_assurance_icon.png') }}"
                                                             class="mr-2"
                                                             style="width: 22px; height: 22px">
                                                        <span class="font-weight-bold" style="color: #AF6F1E">{{ $dept }}</span>
                                                    </div>
                                                @endif
                                                @if($corrective->correction_target_date)
                                                    <div class="p-2" style="background: #44B3AB3D; border-radius: 5px">
                                                        <img src="{{ asset('images/icons/calendar_corrective.png') }}"
                                                             class="mr-2"
                                                             style="width: 22px; height: 22px">
                                                        <span class="font-weight-bold" style="color: #44B3AB">{{ date('d-m-Y', strtotime($corrective->correction_target_date)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($corrective->is_req_rc_analysis_management_review=="yes")
                            <div>
                                <div class="observation px-3">
                                    <label>Root Cause</label>
                                </div>
                                <div class="mt-2 mx-5 root-cause-table-adjust" style="overflow-x:auto;" id="rootcauseTable">
                                    {!!$rootcause!!}
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                    @if($corrective->is_moc_required=='yes')
                    <div class="mt-5 px-5 px-adjust">
                        <label class="ml-3">MOC Details</label>
                        <div class="ml-3 mr-5 mt-2 d-block-sp bg-white p-2 correction-block-adjust" style="border-radius: 10px">
                            <div class="d-flex mb-3 d-block-sp">
                                <div class="observation col-md-7 mt-2">
                                    <label><a href="#" class="view-moc-detail" data-toggle="tooltip" title="Click to view details"
                                                data-route="{{ $corrective->moc->id }}">
                                                <span class="status status-blue">{{$corrective->moc->crid}}</span>
                                            </a>

                                    </label><br><br>
                                    <label>Change Description</label>
                                        <p class="text-justify">{{ $corrective->change_description }}</p>

                                </div>
                                <div class="col-md-5 justify-content-center d-block-sp mt-2">
                                    <div>
                                        <label>
                                            @if($corrective->moc->status=="Review Pending")
                                            <span class="badge badge-info" style="font-size:11px;">{{$corrective->moc->status}}</span>
                                            @elseif($corrective->moc->status=="Reviewed")
                                            <span class="badge badge-success" style="font-size:11px;">{{$corrective->moc->status}}</span>
                                            @elseif($corrective->moc->status=="Approved")
                                            <span class="badge badge-primary" style="font-size:11px;">{{$corrective->moc->status}}</span>
                                            @elseif($corrective->moc->status=="Rejected")
                                            <span class="badge badge-danger" style="font-size:11px;"> {{$corrective->moc->status}}</span>
                                            @elseif($corrective->moc->status=="Partially Completed")
                                            <span class="badge badge-warning" style="font-size:11px;"> {{$corrective->moc->status}}</span>
                                            @elseif($corrective->moc->status=="Not Started")
                                            <span class="badge badge-warning" style="font-size:11px;"> {{$corrective->moc->statuss}}</span>
                                            @elseif($corrective->moc->status=="Completed")
                                            <span class="badge badge-info" style="font-size:11px;"> {{$corrective->moc->status}}</span>
                                            @elseif($corrective->moc->status=="Closed")
                                            <span class="badge badge-success" style="font-size:11px;"> {{$corrective->moc->status}}</span>
                                            @else
                                            <span class="badge badge-warning" style="font-size:11px;">{{$corrective->moc->status}}</span>
                                            @endif
                                        </label>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($corrective->is_req_rc_analysis_management_review=="yes")
                    <div class="mt-4 px-5 d-flex mb-4 px-adjust d-block-400 d-block-643">
                        <div class="col-md-7">
                            <label class="ml-1">Investigation Team</label>
                            <div class="d-flex" style="flex-flow: wrap; margin-top: 0rem">
                                @if((isset($investigationTeams) && count($investigationTeams) > 0) || isset($corrective->teamLead))
                                    @if(isset($corrective->teamLead))
                                        <div class="send-notification-item" style="height: 3.5rem; display: flex;">
                                            @if($corrective->teamLead->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->teamLead->profile_picture)))
                                                <img src="{{ asset('/storage/profile/'.$corrective->teamLead->profile_picture) }}">
                                            @else
                                                <img src="{{asset('/images/avatar10.jpg')}}" />
                                            @endif
                                            <div>
                                                <label class="text-white m-0">{{ $corrective->teamLead->name }}</label>
                                                <br><span class="text-white" style="font-size: 10px">Team Lead</span>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach($investigationTeams as $investigationItem)
                                        <div class="send-notification-item">
                                            @if ($investigationItem->user)
                                                @if ($investigationItem->user->profile_picture
                                                    && File::exists(public_path('/storage/profile/'.$investigationItem->user->profile_picture)))
                                                    <img src="{{ asset('/storage/profile/'.$investigationItem->user->profile_picture) }}">
                                                @else
                                                    <img src="{{asset('/images/avatar10.jpg')}}" />
                                                @endif
                                                <label class="text-white m-0">{{ $investigationItem->user->name }}</label>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <label class="text-secondary fw-500 ml-2">No investigation team assigned</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="ml-3">Investigation Report</label>
                            <div class="d-flex ml-3" style="flex-flow: wrap">
                                <button type="button" class="btn btn-success btn-sm mr-3"
                                        title="Click to view report"
                                        data-toggle="tooltip"
                                        onclick="showInvestigationReportModal('{{ $corrective->id }}')"
                                        style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                    View report
                                </button>
                            </div>
                            @if($corrective->team_lead_id==$currentUser || in_array($currentUser,$investigationTeamIds))
                            <div class="d-flex ml-3" style="flex-flow: wrap">
                                <button type="button" class="btn btn-success btn-sm mr-3"
                                        title="Click to add investigation report"
                                        data-toggle="tooltip"
                                        onclick="showInvestigationModal('{{ $corrective->id }}')"
                                        style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                        Add Investigation Report
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="mt-4 px-5 d-flex mb-4 px-adjust d-block-643" style="gap: 1rem">
                        <div class="col-md-7">
                            <label class="ml-1">Send Notifications To</label>
                            <div class="d-flex" style="flex-flow: wrap; margin-top: 0rem">
                                @if(count($notificationUsers) > 0)
                                    @foreach($notificationUsers as $user)
                                        <div class="send-notification-item">
                                            @if($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture)))
                                                <img src="{{ asset('/storage/profile/'.$user->profile_picture) }}">
                                            @else
                                                <img src="{{asset('/images/avatar10.jpg')}}" />
                                            @endif
                                            <label class="text-white m-0">{{ $user->name }}</label>
                                        </div>
                                    @endforeach
                                @else
                                    <label class="text-secondary fw-500 ml-2">No additional users selected for notification</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label>Email reminder for action</label>
                            @if(count($frequency))
                                @foreach($frequency as $freq)
                                    <div class="d-flex align-items-center ml-3 bg-white py-2 px-3 col-6 frequency-adjust" style="border-radius: 10px; margin-top: 0rem">
                                        <div class="mr-2 p-1" style="background: #2529453D; border-radius: 5px">
                                            <img src="{{ asset('images/icons/calendar_of_email_reminder.png') }}"
                                                 style="width: 16px; height: 16px;">
                                        </div>
                                        <span class="font-weight-bold">{{ $freq }}</span>
                                    </div>
                                @endforeach
                            @else
                                <br><label class="text-secondary fw-500 ml-2" style="margin-top: 0rem">No reminder schedule configured</label>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 px-5 px-adjust" id="attachmentSection">
                        {!!$attachments!!}
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem">
                    <label class="mt-3 ml-4"><i class="fa fa-chart-line mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Timeline & Review</label>
                    <div class="d-flex justify-content-center pb-4">
                        <div class="col-md-5 col-sm-6">
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Added By & Date</label>
                            @if($corrective->entry_reviewed_by || $corrective->processstage==='Entry Under Review')
                              <label class="d-block text-right mb-0" style="margin-top: 3rem">Entry Reviewed By & Date</label>
                            @endif
                            <label class="d-block text-right mb-0" style="margin-top: 3.25rem">Action Reviewed By & Date</label>
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Closure Reviewed By & Date</label>
                        </div>
                        <div class="col-md-1 d-none-643" style="padding-left: 2.5rem;">
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto;"></span>
                            @if($corrective->entry_reviewed_by || $corrective->processstage==='Entry Under Review')
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto;"></span>
                            @endif
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="d-flex align-items-center" style="margin-top: 2rem;">
                                @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'. $postedBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $postedBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'. $postedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label>{{ $postedBy ? $postedBy->name : 'Not Specified' }}</label><br>
                                    <label>on {{ $corrective->postdate ? date('d-m-Y', strtotime($corrective->postdate)) : '-' }}</label>
                                </div>
                            </div>
                            <!-- entry reviewed by starts -->
                            @if($corrective->entry_reviewed_by || $corrective->processstage==='Entry Under Review')
                            <div class="d-flex align-items-center" style="margin-top: 1.2rem;">
                                <div class="d-block" style="padding: 0.5rem 0;">
                                    @if($corrective->approvedByCorrective && $corrective->approvedByCorrective->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->approvedByCorrective->profile_picture)))
                                        <img class="img-avatar mr-3" alt="{{ $corrective->approvedByCorrective->name }}"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{ asset('/storage/profile/'.$corrective->approvedByCorrective->profile_picture) }}">
                                    @else
                                        <img class="img-avatar mr-3" alt="No picture"
                                            style="background-color: green; color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                </div>
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label>{{ $corrective->approvedByCorrective ? $corrective->approvedByCorrective->name : 'Pending Entry Review' }}</label><br>
                                    @if($corrective->approvedByCorrective)
                                    <label>on {{ $corrective->entry_reviewed_by_date ? date('d-m-Y', strtotime($corrective->entry_reviewed_by_date)) : '-' }}</label>

                                    @endif
                                </div>
                            </div>
                            @endif
                            <!-- ends entry reviewed by -->
                            <div class="d-flex align-items-center" style="margin-top: 1.2rem;">
                                <div class="d-block" style="padding: 0.5rem 0;">
                                    @if($corrective->actionReviewedByCorrective && $corrective->actionReviewedByCorrective->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->actionReviewedByCorrective->profile_picture)))
                                        <img class="img-avatar mr-3" alt="{{ $corrective->actionReviewedByCorrective->name }}"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{ asset('/storage/profile/'.$corrective->actionReviewedByCorrective->profile_picture) }}">
                                    @else
                                        <img class="img-avatar mr-3" alt="No picture"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                </div>
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label>{{ $corrective->actionReviewedByCorrective ? $corrective->actionReviewedByCorrective->name : 'Pending Action Review' }}</label><br>
                                    @if($corrective->actionReviewedByCorrective)
                                    <label>on {{ $corrective->action_reviewed_at ? date('d-m-Y', strtotime($corrective->action_reviewed_at)) : '-' }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center" style="margin-top: 1.2rem;">
                                <div class="d-block" style="padding: 0.5rem 0">
                                    @if($corrective->closedByCorrective && $corrective->closedByCorrective->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->closedByCorrective->profile_picture)))
                                        <img class="img-avatar mr-3" alt="{{ $corrective->closedByCorrective->name }}"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{ asset('/storage/profile/'.$corrective->closedByCorrective->profile_picture) }}">
                                    @else
                                        <img class="img-avatar mr-3" alt="No picture"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px;"
                                            src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                </div>
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label>{{ $corrective->closedByCorrective ? $corrective->closedByCorrective->name : 'Pending Closure Review' }}</label><br>
                                    @if($corrective->closedByCorrective)
                                    <label>on {{ $corrective->closed ? date('d-m-Y', strtotime($corrective->closed)) : '-' }}</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row pb-4 px-xs-adjust">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem; padding-bottom: 1rem">
                    <label class="mt-3 ml-4"><img src="https://cdn.iconscout.com/icon/free/png-256/free-forum-1779761-1513575.png" class="mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px; width: 30px"> User Interaction</label>
                    @if($corrective->processstage!='Entry Under Review')
                    <div class="d-block mt-4 ml-6">
                        <label>Entry Review Remarks</label>
                        @if($corrective->disapproval_reason)
                            <p>{{ $corrective->disapproval_reason }}</p>
                        @else
                            <p class="text-secondary fw-500">No remarks added</p>
                        @endif
                    </div>
                    @endif
                    @if(in_array($corrective->processstage,[
                        'Action Reviewed, OK','Action Reviewed, Not OK','Actions Completed','Need More Action','Accomplished'
                    ]))
                    <div class="d-block mt-4 ml-6">
                        <label>Action Review Remarks</label>
                        @if($corrective->action_remarks)
                            <p>{{ $corrective->action_remarks }}</p>
                        @else
                            <p class="text-secondary fw-500">No action remarks added</p>
                        @endif
                    </div>
                    @endif
                    @if(in_array($corrective->processstage,[
                        'Need More Action','Accomplished'
                        ]))
                    <div class="d-block mt-4 ml-6">
                        <label>Closure Review Remarks</label>
                        @if($corrective->remarks)
                            <p>{{ $corrective->remarks }}</p>
                        @else
                            <p class="text-secondary fw-500">No closure remarks added</p>
                        @endif
                    </div>
                    @endif
                    @if(in_array($corrective->processstage,['Accomplished']))
                    <div class="d-block mt-4 ml-6">
                        <label>Verification Remarks</label>
                        @if($corrective->verification_remarks)
                            <p>{{ $corrective->verification_remarks }}</p>
                        @else
                            <p class="text-secondary fw-500">No verification remarks added</p>
                        @endif
                    </div>
                    @endif
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
        <!-- END Horizontal -->
        <div class="corrective-area"></div>
        <div id="view-detail"></div>
    </div>
    <!-- END Page Content -->
    <script>
        const recordId = "{{$corrective->id}}";
    </script>
    <script src="{{versionedCache('js/corrective_detail.js')}}"></script>
@endsection
