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
        @page {
            size: A4;
            margin: 5mm 10mm;
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
            /* margin-top: 20px;
            margin-bottom: 20px; */
        }
        .py-20 {
            padding-top: 20px;
            padding-bottom: 20px;
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
        .process-stage-point.span-awaiting-periodic-review-action::after {
            content: 'Awaiting Periodic Review';
            position: absolute;
            top: 20px;
            left: -2.5rem;
            white-space: nowrap;
        }
        .process-stage-point.span-reject-action::after {
            content: 'Rejected';
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
            height: 250px;
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
        .responsibility-block img:not(:first-child) {
            margin-left: -10px;
        }
        .responsibility-block span.res-name {
            display: inline-block;
            vertical-align: middle;
            font-weight: bold;
            margin-top: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
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
        .card25 {
            margin-right: 8px;
            background: white;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            border-radius: 8px;
            text-align: left;
            min-width: 200px;
            height: 50px;
        }
        @page {
            margin-top: 100px;
            margin-bottom: 50px;
        }

        .pdf-header{
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 70px;
        }
    </style>
</head>
<body>
    <div class="pdf-header" style="width:100%; margin-bottom:10px;">
        <table width="100%">
            <tr>
                <!-- Logo -->
                <td style="width:20%; text-align:left;">
                    @if($clientDetails->client_logo)
                        @php
                            $logoPath = public_path("storage/clientlogos/" . $clientDetails->client_logo);
                            $logoBase64 = null;

                            if (file_exists($logoPath)) {
                                try {
                                    $imageData = file_get_contents($logoPath);
                                    if ($imageData !== false) {
                                        $imageInfo = getimagesize($logoPath);
                                        $mimeType = $imageInfo['mime'] ?? 'image/png';
                                        $logoBase64 = "data:{$mimeType};base64," . base64_encode($imageData);
                                    }
                                } catch (Exception $e) {
                                    // Handle error silently
                                }
                            }
                        @endphp
                        @if($logoBase64)
                            <img id="imgPreview" src="{{ $logoBase64 }}"
                                    alt="CompanyLogo"
                                    style="max-width: 100px; max-height: 50px; object-fit: contain;">
                        @endif
                    @endif
                </td>
                <!-- Company Name -->
                <td style="width:50%; text-align:center;">
                    <span style="font-size:14px; font-weight:bold;">
                        {{ $clientDetails ? $clientDetails->company_name : '' }}
                    </span>
                </td>
                <!-- Export Date -->
                <td style="width:30%; text-align:right;">
                    <span style="font-size:11px;">
                        Exported: {{ date('d-m-Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div class="container pdf-content">
        <table class="heading-table">
            <tr>
                <td><label class="page-title">Enterprise Risk</label><span class="issue-title">{{ $risk->riskname }}</span></td>
                <td class="text-right">
                    @if($posted_user_image && File::exists(public_path('/storage/profile/' . $posted_user_image)))
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'.$posted_user_image) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                </td>
            </tr>
        </table>
        <div style="height: 10px"></div>
        <div class="overview-status">
            <div class="block" style="width: 46%; margin-right: 20px;">
                <p class="p-0 m-0 block-title" style="margin-bottom: 20px;">
                    <img src="{{ public_path('images/icons/eye.png') }}">
                    <span>Overview</span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500;">
                    <span class="badge badge-red" style="margin-right: 5px;">
                        <img src="{{ public_path('images/icons/vpn_key.png') }}" alt="key_icon" style="width: 18px; display: inline-block; vertical-align: middle;">
                        <span class="ml-2" style="display: inline-block; vertical-align: middle;">#{{ $risk->grp_id }}</span>
                    </span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    <span class="badge badge-blue" style="margin-right: 5px;">
                        <img src="{{ public_path('images/icons/group.png') }}" alt="group_icon" style="width: 17px; display: inline-block; vertical-align: middle;">
                        <span class="ml-2" style="display: inline-block; vertical-align: middle;">{{ $risk->riskgroup }}</span>
                    </span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500;">
                    <span class="badge badge-warning" style="margin-right: 5px;">
                        <img src="{{ public_path('images/icons/work.png') }}" alt="key_icon" style="width: 18px;display: inline-block; vertical-align: middle;">
                        <span style="display: inline-block; vertical-align: middle;">{{ $risk->department ? $risk->department->departmentname : '-' }}</span>
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
                <p class="m-0" style="margin-bottom: 50px; margin-left: 25px; font-weight: 500">
                    <label class="circle has-after @if($risk->action_status == 'Under review') bg-warning @endif"><span class="process-stage-point @if($risk->action_status == 'Under review') span-under-review-action @endif"></span></label>
                    <label class="circle has-after @if($risk->action_status == 'Rejected') bg-danger @endif"><span class="process-stage-point @if($risk->action_status == 'Rejected') span-reject-action @endif"></span></label>
                    <label class="circle has-after @if($risk->action_status == 'Awaiting Periodic Review') bg-warning @endif"><span class="process-stage-point @if($risk->action_status == 'Awaiting Periodic Review') span-awaiting-periodic-review-action @endif"></span></label>
                    <label class="circle @if($risk->action_status == 'Active Risk') bg-success @endif"><span class="process-stage-point @if($risk->action_status == 'Active Risk') span-active-risk-action @endif"></span></label>
                </p>
                <p class="m-0" style="margin-left: 25px; font-weight: 500">
                    <span>No. of Escalated Times: {{ $risk->number_of_escalated_times }} times</span><br>
                    <span>Target Date: {{ $risk->action_target }}</span><br>
                    <span>Last time escalated: {{ $risk->last_escalated_time ? date('d-m-Y', strtotime($risk->last_escalated_time)) : '' }}</span><br>
                    <span>Initial Reviewed: {{ $risk->review_date ? date('d-m-Y', strtotime($risk->review_date)) : '' }}</span>
                </p>
            </div>
        </div>

        <div style="height: 20px"></div> <!-- instead of margin top/bottom -->
        <div class="detail my-20">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px;">
                    <p class="p-0 m-0">
                        <img src="{{ public_path('images/icons/clipboard.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Details</span>
                    </p>
                </div>
                <div style="margin-left: 45px;">
                    <div class="d-inline-block" style="padding: 10px; width: 60%; margin-right: 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">{{ $risk->riskname }}</p>
                        <p class="m-0" style="font-size: 12px; font-weight: 300">{!! nl2br(strip_tags($risk->riskdec)) !!}</p>
                    </div>
                    <div class="d-inline-block" style="padding: 10px; width: 30%; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 25px; font-weight: 500;">Risk Owner</p>
                        <div class="m-0 responsibility-block" style="font-size: 12px; font-weight: 300">
                            @php
                                $riskOwnerNames = [];
                            @endphp
                            @foreach($riskOwnerData as $email => $riskowner)
                                @if($riskowner['profile_picture']
                                    && File::exists(public_path('/storage/profile/'. $riskowner['profile_picture'])))
                                    <img class="img-avatar48" style="" alt="{{ $riskowner['name'] }}"
                                         src="{{ public_path('/storage/profile/'. $riskowner['profile_picture']) }}">
                                @else
                                    <img class="img-avatar48" alt="No picture"
                                         src="{{ public_path('/images/avatar10.jpg')}}" />
                                @endif
                                @php
                                    $riskOwnerNames[] = $riskowner['name'];
                                @endphp
                            @endforeach
                            <br><span class="res-name">{{ count($riskOwnerNames) ? implode(', ', $riskOwnerNames) : '-' }}</span><br>
                        </div>
                    </div>
                </div>
                <div style="padding-top: 20px; margin-left: 53px; margin-right: 53px; margin-bottom: 10px;">
                    <p style="font-weight: 500;">Risk Drivers & Existing Controls</p>
                    <div class="block"
                         style="background: white; padding: 10px; margin-right: 10px; margin-bottom: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                        <p class="m-0" style="font-weight: 500;">Risk Drivers</p>
                        <div style="height: 10px"></div> <!-- instead of margin top/bottom 10px -->
                        <p class="m-0" style="font-weight: 300;">
                            @if($risk->riskdri)
                                <label>{{ $risk->riskdri }}</label>
                            @else
                                <label class="text-danger">No risk drivers added</label>
                            @endif
                        </p>
                    </div>
                    <div class="block" style="background: white; padding: 10px; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                        <p class="m-0" style="font-weight: 500;">Existing Controls</p>
                        <div style="height: 10px"></div> <!-- instead of margin top/bottom 10px -->
                        <p class="m-0" style="font-weight: 300;">
                            @if($risk->existing_controls)
                                <label>{{ $risk->existing_controls }}</label>
                            @else
                                <label class="text-danger">No existing control added</label>
                            @endif
                        </p>
                    </div>
                </div>
                <div style="display: block; padding-top: 2rem; margin-left: 53px;">
                    <div class="card25" style="display: inline-block;">
                        <label style="font-weight: 500;">Risk Source</label>
                        <p class="m-0" style="font-weight: 300;">
                            @if($risk->riskscr)
                                <label>{{ $risk->riskscr }}</label>
                            @else
                                <label class="text-danger">No risk source added</label>
                            @endif
                        </p>
                    </div>
                    <div class="card25" style="display: inline-block;">
                        <label style="font-weight: 500;">Risk Response</label>
                        <p class="m-0" style="font-weight: 300;">
                            @if($risk->risk_response)
                                <label>{{ $risk->risk_response }}</label>
                            @else
                                <label class="text-danger">No risk response added</label>
                            @endif
                        </p>
                    </div>
                    @if ($risk->riskreviewer)
                        <div class="card25" style="display: inline-block;">
                            @if ($risk->riskreviewer->profile_picture && File::exists(public_path('/storage/profile/'. $risk->riskreviewer->profile_picture)))
                                <img class="img-avatar multiple-avatar img-avatar48" alt="{{ $risk->riskreviewer->name }}" src="{{ public_path('/storage/profile/'. $risk->riskreviewer->profile_picture)}}">
                            @else
                                <img class="img-avatar multiple-avatar img-avatar48" alt="No picture" src="{{public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <div style="display: inline-block; margin-left: 5px; align-self:flex-end">
                                <label style="font-weight: 500;">Risk Reviewer</label>
                                <p class="m-0" style="font-weight: 300;">
                                    <label>{{ $risk->riskreviewer->name }}</label>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div style="padding-top: 45px; margin-left: 53px;">
                    <div class="d-inline-block" style="width: 15%">
                        <label>Impact</label><br>
                        @if($risk->impa == "High")
                            <span class="status status-red">{{ $risk->impa }}</span>
                        @elseif($risk->impa == "Moderate")
                            <span class="status status-warning">{{ $risk->impa }}</span>
                        @else
                            <span class="status status-success">{{ $risk->impa }}</span>
                        @endif
                    </div>
                    <div class="d-inline-block" style="width: 15%">
                        <label>Likelihood</label><br>
                        @if($risk->like == "High")
                            <span class="status status-red">{{ $risk->like }}</span>
                        @elseif($risk->like == "Moderate")
                            <span class="status status-warning">{{ $risk->like }}</span>
                        @else
                            <span class="status status-success">{{ $risk->like }}</span>
                        @endif
                    </div>
                    <div class="d-inline-block" style="width: 15%">
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
                <div style="padding-top: 80px; margin-left: 53px;">
                    <div class="d-inline-block" style="width: 75%; float: left;">
                        <label>Mitigation action status</label>
                        @if(count($risk->mitigations))
                            @foreach($risk->mitigations as $mitigation)
                                <div>
                                    <div>
                                        <p class="d-inline-block" style="vertical-align: middle">
                                            <span class="status status-blue">{{ $mitigation->action_id }}</span>
                                        </p>
                                        <span style="color: #878787; margin-left: 5px; display: inline-block; vertical-align: middle">
                                            {{ $mitigation->action_name }}
                                        </span>
                                    </div>
                                    <div style="width: 80%">
                                        @if ($mitigation->progress == 25)
                                            <div class="d-inline-block">
                                                <img src="{{ public_path('images/icons/process_25.png') }}"
                                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                                     alt="Process 25 percent">
                                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">25%</span>
                                            </div>
                                        @endif
                                        @if ($mitigation->progress == 50)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ public_path('images/icons/process_50.png') }}"
                                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                                     alt="Process 50 percent">
                                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">50%</span>
                                            </div>
                                        @endif
                                        @if ($mitigation->progress == 75)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ public_path('images/icons/process_75.png') }}"
                                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                                     alt="Process 75 percent">
                                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">75%</span>
                                            </div>
                                        @endif
                                        @if ($mitigation->progress == 100)
                                                <img src="{{ public_path('images/icons/process_100.png') }}"
                                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                                     alt="Process 100 percent">
                                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">100%</span>
                                        @endif
                                        @if ($mitigation->progress == 0)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ public_path('images/icons/process_0.png') }}"
                                                     style="width: 80%; display: inline-block; vertical-align: middle"
                                                     alt="Process 0 percent">
                                                <span style="display: inline-block; vertical-align: middle; font-weight: bold">0%</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-danger">No action status added</p>
                        @endif
                    </div>
                    <div class="d-inline-block" style="width: 25%; float: left;">
                        <label>Opportunity ID</label>
                        @if(count($risk->opportunities))
                            @foreach($risk->opportunities as $oppdata)
                                <div class="mb-2" style="margin-top: 10px">
                                    <span class="status status-blue">{{ $oppdata->dep_id }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-danger">No opportunity added</p>
                        @endif
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
        <div style="height: 40px"></div> <!-- instead of margin top/bottom -->
        <div class="my-20">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/line-chart.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 16px; height: 16px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Timeline & Review</span>
                    </div>
                </div>
                <div style="height: 20px"></div> <!-- instead of margin top/bottom -->
                <div style="margin-top: 0px; margin-left: 50px">
                    <div style="margin-bottom: 20px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Added By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($risk->postedByRisk && $risk->postedByRisk->profile_picture && File::exists(public_path('/storage/profile/'.$risk->postedByRisk->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $risk->postedByRisk->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$risk->postedByRisk->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $risk->postedByRisk ? $risk->postedByRisk->name : 'Not Specified' }}</span>
                            <br><span>on {{ date('d-m-Y', strtotime($risk->created_at)) }}</span>
                        </span>
                    </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 44px; position: absolute; top: -20px; left: 241px">
                    </div>
                    <div style="margin-bottom: 25px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Initial Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($risk->approvedByRisk && $risk->approvedByRisk->profile_picture && File::exists(public_path('/storage/profile/'.$risk->approvedByRisk->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $risk->approvedByRisk->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'. $risk->approvedByRisk->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $risk->approvedByRisk ? $risk->approvedByRisk->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px">on {{ $risk->review_date ? date('d-m-Y', strtotime($risk->review_date)) : '-' }}</span>
                        </span>
                    </span>
                    </div>
                </div>
                <div style="height: 40px"></div> <!-- instead of margin top/bottom -->
                <div style="margin: 0px 50px;">
                    <label style="text-align: center; font-weight: bold; display: block">Risk history</label>
                    <table class="rootcause-table" style="margin-top: 20px; padding-bottom: 20px">
                        <thead>
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th>Description</th>
                            <th style="width: 15%">Date</th>
                            <th style="width: 15%">Reviewed by</th>
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
                                    <td style="text-align: center">{{ date('d-m-Y',strtotime($riskHistory->created_at)) }}</td>
                                    <td class="text-danger" style="font-weight: bold; text-align: center">{{ $riskHistory->riskOwner ? $riskHistory->riskOwner->name : '--' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-danger" style="text-align: center">No matching records found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div style="height: 20px"></div> <!-- instead of margin top/bottom -->
            </div>
        </div>
        <div style="height: 40px"></div> <!-- instead of margin top/bottom -->
        <div class="my-20">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/conversation.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle;font-weight: 600">User Interaction</span>
                    </div>
                    <div style="margin-top: 20px; margin-left: 50px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Disapproval Reason</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            @if($risk->disapprovereason)
                                {{ $risk->disapprovereason }}
                            @else
                                <span class="text-danger">No disapproval reason added</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 20px"></div> <!-- instead of margin top/bottom -->
    </div>
</body>
</html>
