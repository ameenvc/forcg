<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Setilas - CGBOD</title>
    <meta name="description" content="Setilas - CGBOD">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Thin.ttf") }}) format("truetype");
            font-weight: 100;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-ExtraLight.ttf") }}) format("truetype");
            font-weight: 200;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Light.ttf") }}) format("truetype");
            font-weight: 300;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Regular.ttf") }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Medium.ttf") }}) format("truetype");
            font-weight: 500;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-SemiBold.ttf") }}) format("truetype");
            font-weight: 600;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Bold.ttf") }}) format("truetype");
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-ExtraBold.ttf") }}) format("truetype");
            font-weight: 800;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Black.ttf") }}) format("truetype");
            font-weight: 900;
            font-style: normal;
        }
        body {
            margin: 0 auto;
            padding: 0 0;
            /*width:794px;*/
            /*height: 1122px;*/
            font-family: 'Inter';
            font-weight: 400;
            font-size: 12px;
        }
        /* Common */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
        .img-avatar48 {
            width: 48px;
            height: 48px;
        }
        .icon-20 {
            width: 20px;
            height: 20px;
        }
        .m-0 {
            margin: 0;
        }
        .mx-5 {
            margin-left: 5px;
            margin-right: 5px;
        }
        .my-5 {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .mx-10 {
            margin-left: 10px;
            margin-right: 10px;
        }
        .my-10 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .my-20 {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .p-0 {
            padding: 0;
        }
        .w-40 {
            width: 40%;
        }
        .w-50 {
            width: 50%;
        }
        .d-inline-block {
            display: inline-block;
        }
        .float-right {
            float: right;
        }
        .text-danger {
            color: red;
        }

        /* Heading table */
        .heading-table tr {
            background: #F5F5F5;
        }
        .heading-table .page-title {
            font-size: 16px;
            /*;*/
            margin-right: 10px;
        }
        .heading-table .issue-title {
            color: grey;
            
        }
        .vertical-align-baseline {
            vertical-align: baseline;
        }


        table, td, th {
            /*border: 1px solid #80808052;*/
        }
        .badge {
            padding: 5px 12px;
            background-color: #f0f0f0;
            color: #000;
            border-radius: 10px 10px 10px 10px;
            font-size: 12px;
            display: inline-block;
        }
        .badge-blue {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }
        .badge-red {
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .badge-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .badge-secondary {
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .badge-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .badge-flat {
            color: #44b3ab;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .bg-success {
            background-color: #30c78d!important;
        }
        .circle {
            width: 20px;
            height: 20px;
            border-radius: 10px 10px 10px 10px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            margin-right: 60px;
        }
        .circle.has-after::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 20px;
            width: 65px;
            height: 2px;
            background: #000000;
        }
        .process-stage-point.span-under-review-action::after {
            content: 'Under review';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-active-risk-action::after {
            content: 'Active Risk';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-reject-action::after {
            content: 'Reject';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .bg-warning {
            background-color: #fbdece;
        }
        .bg-success {
            background-color: #e0edcf;
        }
        .bg-danger {
            background-color: #f8d4d4;
        }
        .bg-primary {
            background-color: #d9e2f6;
        }
        .bg-secondary {
            background-color: #E5E4E2;
        }
        .bg-flat {
            background: #bff0dd;
        }
        .overview-status .block {
            background: #F6F7F9;
            border-radius: 10px 10px 10px 10px;
            display: inline-block;
            vertical-align: top;
            height: 200px;
        }
        .overview-status .block .block-title {
            margin-top: 10px;
            margin-left: 20px
        }
        .overview-status .block .block-title img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            background: #2529453D;
            border-radius: 5px 5px 5px 5px
        }
        .overview-status .block .block-title span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
        }
        .responsibility-block img {
            display: inline-block;
            vertical-align: middle;
            border-radius: 24px;
        }
        .responsibility-block span.res-name {
            display: inline-block;
            vertical-align: middle;
            font-weight: bold;
        }
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
        .status-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .rootcause-table, .rootcause-table td, .rootcause-table th {
            border: 1px solid #878787;
        }
        .rootcause-table thead tr th {
            background: #252945;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <table class="heading-table">
            <tr>
                <td><label class="page-title">Mitigation Action</label><span class="issue-title">{{ $mitigation->action_name }}</span></td>
                <td class="text-right">
                    @if($posted_user_image && File::exists(public_path('/storage/profile/' . $posted_user_image)))
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'.$posted_user_image) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                </td>
            </tr>
        </table>
        <div class="overview-status my-20">
            <div class="block" style="width: 46%; margin-right: 20px;">
                <p class="p-0 m-0 block-title" style="margin-bottom: 20px;">
                    <img src="{{ public_path('images/icons/eye.png') }}">
                    <span>Overview</span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    <span class="badge badge-blue" style="margin-right: 5px">
                        <img src="{{ public_path('images/icons/vpn_key_blue.png') }}" alt="key_icon" style="width: 18px">
                        <span class="ml-2">#{{ $mitigation->action_id }}</span>
                    </span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    <span class="badge badge-warning">
                        <img src="{{ public_path('images/icons/work.png') }}" alt="work_icon" style="width: 18px">
                        <span class="ml-2">{{ $mitigation->department ? $mitigation->department->departmentname : '-' }}</span>
                    </span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    <span class="badge badge-red" style="margin-right: 5px">
                        <img src="{{ public_path('images/icons/trending_up.png') }}" alt="group_icon" style="width: 17px">
                        <span class="ml-2">{{ ucfirst($mitigation->priority_level) }}</span>
                    </span>
                </p>
            </div>
            <div class="block" style="width: 50%;">
                <p class="p-0 m-0 block-title" style="margin-bottom: 10px;">
                    <img src="{{ public_path('images/icons/line-chart.png') }}">
                    <span>Status & Timeline</span>
                </p>
                <p class="m-0" style="margin-bottom: 20px; margin-left: 25px; font-weight: 500">
                    Current Process Stage
                </p>
                <div class="m-0" style="margin-bottom: 10px; margin-left: 25px; font-weight: 500">
                    @if($mitigation->status == config('constants.risk_management.mitigation.closed'))
                        <div class="d-inline-block">
                            <img src="{{ public_path('images/icons/process_100.png') }}"
                                 style="width: 80%; display: inline-block; vertical-align: middle"
                                 alt="Process 25 percent">
                            <span style="display: inline-block; vertical-align: middle; font-weight: bold">100%</span>
                        </div>
                        <p style="text-align: center; margin-bottom: 0; margin-top: 0;">Closed</p>
                    @else
                        @if ($mitigation->progress == 25)
                            <div class="d-inline-block">
                                <img src="{{ public_path('images/icons/process_25.png') }}"
                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                     alt="Process 25 percent">
                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">25%</span>
                            </div>
                            <p style="text-align: center; margin-bottom: 0; margin-top: 0;">In-progress</p>
                        @endif
                        @if ($mitigation->progress == 50)
                            <div class="d-inline-block">
                                <img src="{{ public_path('images/icons/process_50.png') }}"
                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                     alt="Process 25 percent">
                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">50%</span>
                            </div>
                            <p style="text-align: center; margin-bottom: 0; margin-top: 0;">In-progress</p>
                        @endif
                        @if ($mitigation->progress == 75)
                            <div class="d-inline-block">
                                <img src="{{ public_path('images/icons/process_75.png') }}"
                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                     alt="Process 25 percent">
                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">75%</span>
                            </div>
                            <p style="text-align: center; margin-bottom: 0; margin-top: 0;">In-progress</p>
                        @endif
                        @if ($mitigation->progress == 100)
                            <div class="d-inline-block">
                                <img src="{{ public_path('images/icons/process_100.png') }}"
                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                     alt="Process 25 percent">
                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">100%</span>
                            </div>
                            <p style="text-align: center; margin-bottom: 0; margin-top: 0;">Completed</p>
                        @endif
                        @if ($mitigation->progress == 0)
                            <div class="d-inline-block">
                                <img src="{{ public_path('images/icons/process_0.png') }}"
                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                     alt="Process 25 percent">
                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">0%</span>
                            </div>
                            <p style="text-align: center; margin-bottom: 0; margin-top: 0;">Not Started</p>
                        @endif
                    @endif
                </div>
                <p class="m-0" style="margin-left: 25px; font-weight: 500">
                    <span>Due date: {{ $mitigation->due_date ? date('d-m-Y', strtotime($mitigation->due_date)) : '' }}</span>
                </p>
            </div>
        </div>
        <div class="detail my-20">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px;">
                    <p class="p-0 m-0">
                        <img src="{{ public_path('images/icons/clipboard.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Details</span>
                    </p>
                </div>
                <div style="margin-left: 25px;">
                    <div class="d-inline-block" style="padding: 10px; width: 50%; margin-right: 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">{{ $mitigation->action_name }}</p>
                        <p class="m-0" style="font-size: 12px; font-weight: 300">{!! nl2br(strip_tags($mitigation->action_description)) !!}<</p>
                    </div>
                    <div class="d-inline-block" style="padding: 10px; width: 40%; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 25px; font-weight: 500;">Risk Owner</p>
                        <p style="font-size: 12px; font-weight: 300; width: 100%">
                            @if($mitigation->user && $mitigation->user->profile_picture && File::exists(public_path('/storage/profile/'. $mitigation->user->profile_picture)))
                                <img class="img-avatar48" style="display: inline-block; vertical-align: middle" alt="{{ $mitigation->user->name }}"
                                     src="{{ public_path('/storage/profile/'. $mitigation->user->profile_picture) }}">
                            @else
                                <img class="img-avatar48" alt="No picture"
                                     style="display: inline-block; vertical-align: middle"
                                     src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <span style="display: inline-block; vertical-align: middle; font-weight: bold">{{ $mitigation->user ? $mitigation->user->name : '-' }}</span>
                        </p>
                    </div>
                </div>
                <div style="padding-top: 10px; margin-left: 20px; margin-bottom: 10px;">
                    <div class="d-inline-block"
                         style="padding: 10px; width: 50%; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px;font-weight: 500;">Required Resources</p>
                        <p class="m-0" style="font-weight: 300;">
                            @if($mitigation->resource_required)
                                <label>{{ $mitigation->resource_required }}</label>
                            @else
                                <label class="text-danger">No risk resources added</label>
                            @endif
                        </p>
                    </div>
                    <div class="d-inline-block" style="padding: 10px; width: 40%;border-radius: 10px 10px 10px 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">Cost Estimate</p>
                        <div class="m-0" style="font-weight: 300;">
                            @if($mitigation->cost_estimate)
                                <div class="d-inline-block status status-success">
                                    <img style="display: inline-block; vertical-align: middle; width: 14px"
                                         src="{{ public_path('images/icons/monetization_on.png') }}">
                                    <label style="display: inline-block; vertical-align: middle; margin-left: 5px; padding-right: 5px">${{ is_numeric($mitigation->cost_estimate) ? number_format($mitigation->cost_estimate) : $mitigation->cost_estimate }}</label>
                                </div>
                            @else
                                <label class="text-danger">No cost estimate added</label>
                            @endif
                        </div>
                    </div>
                </div>
                <div style="padding-top: 10px; margin-left: 20px; margin-bottom: 10px;">
                    <div style="padding: 10px">
                        <p style="font-weight: 500; margin-bottom: 10px">Linked Risk(s), Likelihood & Impact Changes</p>
                        @if(count($otherRisks))
                            <div>
                                @foreach($otherRisks as $riskItem)
                                    <div style="display: block">
                                        <label class="status status-blue" style="display: inline-block; vertical-align: middle">{{ $riskItem->grp_id }}</label>
                                        <span style="margin-left: 10px; font-size: 10px; color: #878787;display: inline-block; vertical-align: middle">{{ $riskItem->riskname }}</span>
                                    </div>
                                    @if(count($riskItem->mitigationHistories))
                                        @php
                                            $mitigationHistory = $riskItem->mitigationHistories->where('mitigation_id', $mitigation->id)->first();
                                        @endphp
                                        @if($mitigationHistory)
                                            <div style="display: block; width: 100%; margin-top: 10px">
                                                <div class="d-inline-block" style="width: 35%;font-size:10px;">
                                                    <label style="margin-right: 5px; font-weight: 500; display: inline-block; vertical-align: middle">Existing L * I & Risk:</label>
                                                    <label class="status status-blue" style="margin-right: 5px; display: inline-block; vertical-align: middle">
                                                        {{ $mitigationHistory->existing_likelihood >= 3 ? '03' : ($mitigationHistory->existing_likelihood >= 2 ? '02' : '01') }}
                                                    </label>
                                                    <label style="margin-right: 5px; font-weight: 500; display: inline-block; vertical-align: middle">x</label>
                                                    <label class="status status-blue" style="margin-right: 5px; display: inline-block; vertical-align: middle">
                                                        {{ $mitigationHistory->existing_impact >= 3 ? '03' : ($mitigationHistory->existing_impact >= 2 ? '02' : '01') }}
                                                    </label>
                                                    <div style="border-left: 1px solid; height: 20px; margin-right: 5px; display: inline-block; vertical-align: middle"></div>
                                                    <label style="display: inline-block; vertical-align: middle" class="status {{ $mitigationHistory->existing_risk == 'High' ? 'status-red' : ($mitigationHistory->existing_risk == 'Moderate' ? 'status-warning' : 'status-success') }}">
                                                        {{ $mitigationHistory->existing_risk }}
                                                    </label>
                                                </div>
                                                <div class="d-inline-block" style="width: 20%; margin-left: 25px;font-size:10px;">
                                                    <div class="p-2" style="font-weight:bold;">
                                                        Actual residual values: L={{$mitigationHistory->new_likelihood}}, I={{$mitigationHistory->new_impact}}
                                                    </div>
                                                </div>
                                                <div class="d-inline-block" style="width: 35%; margin-left: 15px;font-size:10px;">
                                                    <label style="margin-right: 5px; font-weight: 500; display: inline-block; vertical-align: middle">New L * I & Risk:</label>
                                                    <label class="status status-warning" style="background: #EE9F273D; color: #EE9F27; margin-right: 5px; display: inline-block; vertical-align: middle">
                                                        {{ $mitigationHistory->new_likelihood >= 3 ? '03' : ($mitigationHistory->new_likelihood >= 2 ? '02' : '01') }}
                                                    </label>
                                                    <label style="margin-right: 5px; font-weight: 500; display: inline-block; vertical-align: middle">x</label>
                                                    <label class="status status-warning" style="background: #EE9F273D; color: #EE9F27; margin-right: 5px; display: inline-block; vertical-align: middle">
                                                        {{ $mitigationHistory->new_impact >= 3 ? '03' : ($mitigationHistory->new_impact >= 2 ? '02' : '01') }}
                                                    </label>
                                                    <div style="border-left: 1px solid; height: 20px; margin-right: 5px; display: inline-block; vertical-align: middle"></div>
                                                    <label style="display: inline-block; vertical-align: middle" class="status {{ $mitigationHistory->new_risk == 'High' ? 'status-red' : ($mitigationHistory->new_risk == 'Moderate' ? 'status-warning' : 'status-success') }}">
                                                        {{ $mitigationHistory->new_risk }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <label class="text-danger" style="font-weight: 300">No link other risk added</label>
                        @endif
                    </div>
                </div>
                <div style="padding-top: 10px; margin-left: 20px; margin-bottom: 10px;">
                    <div style="padding: 10px">
                        <p style="font-weight: 500; margin-bottom: 10px">Attachments</p>
                        @if(count($mitigation->attachments))
                            <div class="d-inline-block">
                                @foreach($mitigation->attachments as $attachment)
                                    <p class="status status-success"
                                       style="background: #1760443D; color: #176044; margin-right: 10px; margin-bottom: 10px; padding-right: 10px">
                                        <img src="{{ public_path('images/icons/download_green.png') }}"
                                             alt="download_green"
                                             style="width: 20px; display: inline-block; vertical-align: middle">
                                        <span style="margin-left: 5px; display: inline-block; vertical-align: middle">{{ $attachment->attachment }}</span>
                                    </p>
                                @endforeach
                            </div>
                        @else
                            <label class="text-danger" style="font-weight: 300">No attachments added</label>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="my-20" style="page-break-before: always">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/line-chart.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 16px; height: 16px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Timeline & Review</span>
                    </div>
                </div>
                <div style="margin-top: 20px; margin-left: 50px">
                    <div style="margin-bottom: 20px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Added By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($mitigation->createdBy && $mitigation->createdBy->profile_picture && File::exists(public_path('/storage/profile/'.$mitigation->createdBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $mitigation->createdBy->name }}"
                                     style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                     src="{{ public_path('/storage/profile/'.$mitigation->createdBy->profile_picture) }}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                     style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                     src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $mitigation->createdBy ? $mitigation->createdBy->name : 'Not Specified' }}</span>
                            <br><span>on {{ date('d-m-Y', strtotime($mitigation->created_at)) }}</span>
                        </span>
                    </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 44px; position: absolute; top: -20px; left: 241px">
                    </div>
                    <div style="margin-bottom: 25px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Last Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($mitigation->reviewedBy && $mitigation->reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$mitigation->reviewedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $mitigation->reviewedBy->name }}"
                                     style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                     src="{{ public_path('/storage/profile/'. $mitigation->reviewedBy->profile_picture) }}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                     style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                     src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $mitigation->reviewedBy ? $mitigation->reviewedBy->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px">on {{ date('d-m-Y', strtotime($mitigation->reviewed_date)) }}</span>
                        </span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
