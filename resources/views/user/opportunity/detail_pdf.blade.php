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
            width: 40px;
            height: 40px;
        }
        .img-avatar49 {
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
        .my-2 {
            margin-top: 2px;
            margin-bottom: 2px;
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
        .text-secondary {
            color: #6c757d;
        }
        /* Heading table */
        .heading-table tr {
            background: #F5F5F5;
        }
        .heading-table .page-title {
            font-size: 15px;
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
            background-color:#cbe7e6;
            border-color: #bff0dd;
        }
        .bg-success {
            background-color: #30c78d!important;
        }
        .circle {
            width: 15px;
            height: 15px;
            border-radius: 7px 7px 7px 7px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            margin-right: 20px;
        }
        .circle.has-after::after {
            content: '';
            position: absolute;
            top: 8px;
            left: 15px;
            width: 30px;
            height: 2px;
            background: #000000;
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
        .breakPageBefore {
            page-break-before: always;
        }
        .bg-blue {
            background-color: #25499c !important;
        }
        .bg-black {
            background-color:#000
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
        }
        .send-notification-item {
            background: #343A40;
            border-radius: 0.25rem;
            align-items: center;
            height: 2.5rem;
            padding: 0.5rem 1.75rem;
            margin-right: 2rem;
            margin-bottom: 1rem;
        }
        .send-notification-item img {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 1rem;
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
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }
        tr,
        td,
        th {
            page-break-inside: avoid !important;
        }
        table {
            page-break-inside: auto;
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
                <td ><label class="page-title my-2">Opportunity</label><span class="issue-title">{{ $opportunity->oppname }}</span></td>
                <td class="text-right" style="padding-bottom:0px;">
                    @if($opportunity->byresponsibility && File::exists(public_path('/storage/profile/'.$opportunity->byresponsibility->profile_picture)))
                        <img class="img-avatar48 mx-5 my-2" src="{{ public_path('/storage/profile/'.$opportunity->byresponsibility->profile_picture) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-2" src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                    @if($opportunity->postedByOpportunity && File::exists(public_path('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture)))
                        <img class="img-avatar48 mx-5 my-2" src="{{ public_path('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-2" src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                </td>
            </tr>
        </table>
        <div class="overview-status my-5">
            <div class="block" style="width: 37%; margin-right: 10px; padding-bottom: 10px;height: auto;margin-bottom:5px;">
                <p class="p-0 m-0 block-title" style="margin-bottom: 10px;">
                    <img src="{{ public_path('images/icons/eye.png') }}">
                    <span>Overview</span>
                </p>
                <p class="m-0" style="margin-left: 15px; margin-bottom: 5px; font-weight: 500">
                    <span class="badge badge-red" style="margin-right: 5px;">
                        <img src="{{ public_path('images/icons/vpn_key.png') }}" alt="issueid_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                        <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">#{{ $opportunity->dep_id }}</span>
                    </span>
                </p>
                @if($opportunity->dept)
                    <p class="m-0" style="margin-left: 15px; margin-bottom: 5px; font-weight: 500">
                        <span class="badge badge-blue" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/department-icon.png') }}" alt="department_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $opportunity->dept->departmentname }}</span>
                        </span>
                    </p>
                @endif
                @if($opportunity->division_id != 0 && $opportunity->division)
                    <p class="m-0" style="margin-left: 15px; margin-bottom: 5px; font-weight: 500">
                        <span class="badge badge-blue" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/division-subdivision-icon.png') }}" alt="division_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $opportunity->division->name }}</span>
                        </span>
                    </p>
                @endif
                @if($opportunity->area_id !=0 && $opportunity->area)
                    <p class="m-0" style="margin-left: 15px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-blue" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/division-subdivision-icon.png') }}" alt="subdivision_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{$opportunity->area->name}}</span>
                        </span>
                    </p>
                @endif
                @if($opportunity->ohs == 'Yes')
                    <p class="m-0" style="margin-left: 15px; margin-bottom: 5px; font-weight: 500">
                        <span class="badge badge-warning" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/outofscope.png') }}" alt="within_ohs_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">Within OHS Scope</span>
                        </span>
                    </p>
                @else
                    <p class="m-0" style="margin-left: 15px; margin-bottom: 5px; font-weight: 500">
                        <span class="badge badge-success" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/outsideohs.png') }}" alt="out_side_ohs_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">Outside OHS Scope</span>
                        </span>
                    </p>
                @endif
                @if($opportunity->typeRelation && count($opportunity->typeRelation))
                    @foreach($opportunity->typeRelation as $typeRelation)
                    <p class="m-0" style="margin-left: 15px; margin-bottom: 5px; font-weight: 500">
                        <span class="badge badge-flat" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/opptype.png') }}" alt="type_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $typeRelation->name }}</span>
                        </span>
                    </p>
                    @endforeach
                @endif
                <div style="text-align:right;margin-top:20px;">
                    <label style="font-size:10px;">
                        <span style="color:gray;">Added by:</span>
                        <span style="">
                            {{ optional($opportunity->postedByOpportunity)->name }}
                        </span>
                    </label>
                </div>
            </div>
            <div class="block" style="width: 61%; padding-bottom: 10px;height:260px;">
                @php
                    $stages = [
                        'Review Pending',
                        'Disapproved',
                        'Approved',
                        'Completed',
                        'Closed'
                    ];
                    $currentStage = $opportunity->status;

                    $currentIndex = array_search($currentStage, $stages);
                @endphp
                <p class="p-0 m-0 block-title" style="margin-bottom: 5px;">
                    <img src="{{ public_path('images/icons/line-chart.png') }}">
                    <span>Status & Timeline</span>
                </p>
                <p class="m-0" style="margin-bottom: 20px; margin-left: 25px; font-weight: 500;font-size:11px;">
                    CURRENT PROCESS STAGE
                </p>    
                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:-10px;">
                    <!-- Progress Line -->
                    <tr>
                        @foreach($stages as $index => $stage)
                        <td align="center">
                            @if($index == $currentIndex)
                                <img src="{{ public_path('images/icons/completed.png') }}"
                                    width="18"
                                    height="18">
                            @else
                                <img src="{{ public_path('images/icons/blankcircle.png') }}"
                                    width="16"
                                    height="16">
                            @endif
                        </td>
                        @endforeach
                    </tr>  
                    <tr>
                        @foreach($stages as $stage)
                            <td align="center"
                                style="font-size:10px;font-weight:600;">
                                {{ $stage }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="5" align="center" style="padding-top:10px;font-size:11px;">
                            <strong>{{ $currentStage }}</strong>
                        </td>
                    </tr>
                </table>
                <table width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">
                    <tr>
                        <td width="60%" style="font-size:10px;">
                            Assigned Approver:
                            <strong>{{ optional($opportunity->opportunityapprover)->name ?? 'Not Assigned' }}</strong>
                        </td>
                        <td width="40%"></td>
                    </tr>
                    <tr>
                        <td style="font-size:10px;">
                            Closure Reviewer:
                            <strong>{{ optional($opportunity->closurereviewer)->name ?? 'Not Assigned' }}
                        </td>
                        <td align="right" style="font-size:10px;">
                            Target Date:
                            <strong>{{ $opportunity->tardate ? date('d-m-Y', strtotime($opportunity->tardate)) : '-' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="detail my-5">
            <div style="border-radius: 0px;">
                <table>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <div style="padding: 10px 20px; background: #F6F7F9;border-radius: 10px 10px 0 0;">
                                <p class="p-0 m-0">
                                    <img src="{{ public_path('images/icons/clipboard.png') }}"
                                        style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                                    <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Details</span>
                                </p>
                                <div class="d-inline-block" style="padding: 10px; margin-right: 10px; vertical-align: top;width:67%;">
                                    <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">
                                        {{ $opportunity->oppname }}
                                    </p>
                                    <p class="m-0" style="font-size: 12px; font-weight: 300; text-align: justify">{{ $opportunity->oppdes }}</p>
                                    <p style="margin-bottom: 10px; font-weight: 500;">
                                        Action
                                    </p>
                                    @if($opportunity->action)
                                    <p class="m-0" style="font-size: 11px; font-weight: 300; text-align: justify">{{ $opportunity->action }}</p>
                                    @else
                                    <p class="m-0 text-secondary" style="font-size: 11px; font-weight: 300; text-align: justify;">No details provided</p>
                                    @endif
                                </div>
                                <div class="d-inline-block" style="padding: 10px; width: 30%;border-radius: 10px 10px 10px 10px; vertical-align: top">
                                    <p class="m-0" style="margin-bottom: 12px; font-weight: 500;">Responsibility</p>
                                    <div class="m-0 responsibility-block" style="font-size: 10px; font-weight: 300">
                                        @if($opportunity->byresponsibility && File::exists(public_path('/storage/profile/'.$opportunity->byresponsibility->profile_picture)))
                                            <img class="img-avatar49" style="" alt="{{ $opportunity->byresponsibility->name }}"
                                                    src="{{ public_path('/storage/profile/'.$opportunity->byresponsibility->profile_picture) }}">
                                        @else
                                            <img class="img-avatar49" alt="No picture"
                                                    src="{{ public_path('/images/avatar10.jpg')}}" />
                                        @endif
                                        <div class="res-name {{ $opportunity->byresponsibility ? '' : 'text-secondary' }}" style="font-weight: {{$opportunity->byresponsibility ? '600' : '300' }};">{{ $opportunity->byresponsibility ? $opportunity->byresponsibility->name : 'Responsibility not assigned yet' }}</div>
                                        <div class="d-inline-block" style="margin-top: 6px">
                                            <div style="width: 180px">
                                                @if($opportunity->dept)
                                                    <div style="background: #AF6F1E3D; padding: 5px 10px; border-radius: 3px; width: 100%">
                                                        <img src="{{ public_path('images/icons/quality_assurance_icon.png') }}"
                                                                style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 5px">
                                                        <span style="color: #AF6F1E; font-weight: bold; display: inline-block;vertical-align: middle;font-size: 10px;">{{ $opportunity->dept->departmentname}}</span>
                                                    </div>
                                                @endif
                                                @if($opportunity->tardate)
                                                    <div style="background: #44B3AB3D; margin-top: 6px; padding: 5px 10px; border-radius: 3px; width: 100%">
                                                        <img src="{{ public_path('images/icons/calendar_corrective.png') }}"
                                                                style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 5px">
                                                        <span style="color: #44B3AB; font-weight: bold; display: inline-block; vertical-align: middle;font-size: 10px;">
                                                        {{ date('d-m-Y', strtotime($opportunity->tardate)) }}
                                                    </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <div style="background: #F6F7F9; border-radius: 0px;">
                                <div style="padding-bottom: 10px">
                                    <div style="margin-top: 15px;margin-left:25px;">
                                        <div class="d-inline-block" style="width: 15%">
                                            <label>Likelihood</label><br>
                                            @if($opportunity->likelihood == "High")
                                                <span class="badge badge-red">{{ $opportunity->likelihood }}</span>
                                            @elseif($opportunity->likelihood == "Moderate")
                                                <span class="badge badge-warning">{{ $opportunity->likelihood }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $opportunity->likelihood }}</span>
                                            @endif
                                        </div>
                                        <div class="d-inline-block" style="width: 15%">
                                            <label>Benefit</label><br>
                                            @if($opportunity->benefit == "High")
                                                <span class="badge badge-red">{{ $opportunity->benefit }}</span>
                                            @elseif($opportunity->benefit == "Moderate")
                                                <span class="badge badge-warning">{{ $opportunity->benefit }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $opportunity->benefit }}</span>
                                            @endif
                                        </div>
                                        <div class="d-inline-block" style="width: 15%">
                                            <label>Positive Impact</label><br>
                                            @if($opportunity->impact == "High")
                                                <span class="badge badge-red">{{ $opportunity->impact }}</span>
                                            @elseif($opportunity->impact == "Moderate")
                                                <span class="badge badge-warning">{{ $opportunity->impact }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $opportunity->impact }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <div style="background: #F6F7F9; border-radius: 0 0 10px 10px;margin-bottom:10px;">
                                <div style="padding-bottom: 10px">
                                    <div style="margin-top: 15px">
                                        <div class="d-inline-block" style="vertical-align: baseline; width: 69%">
                                            <p class="m-0 p-0" style="; margin-bottom: 10px; margin-left: 25px;font-weight: 500;">Attachments</p>
                                            @if(count($files) > 0)
                                                @foreach($files as $file)
                                                    @if($file && $file != 'NULL')
                                                        <span class="badge badge-success"
                                                            style="margin-left: 25px;font-size: 0.75rem;color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                                            {{ $file }}
                                                        </span><br>
                                                    @else
                                                        <label class="text-secondary" style="margin-left: 25px;">No attachments added</label>
                                                    @endif
                                                @endforeach
                                            @else
                                                <label class="text-secondary" style="margin-left: 25px;">No attachments added</label>
                                            @endif
                                        </div>
                                        <div class="d-inline-block" style="vertical-align: baseline; width: 28%;{{count($files) > 0 ? 'margin-top:-25%;':''}}">
                                            <p class="m-0 p-0" style="; margin-bottom: 10px; margin-left: 20px;font-weight: 500;">Risk ID</p>
                                            @if($opportunity->risk)
                                                <span class="badge badge-blue"
                                                    style="margin-left: 25px;font-size: 0.75rem; border-radius: 4px; ">
                                                    {{ $opportunity->risk->grp_id }}
                                                </span>
                                            @else
                                                <label class="text-secondary" style="margin-left: 20px;">No risk added</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;margin-bottom:10px;">
                                <div style="padding: 10px 20px">
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
                                        <span style="margin-left: 40px; display: inline-block; vertical-align: middle">
                                            @if($opportunity->postedByOpportunity && $opportunity->postedByOpportunity->profile_picture && File::exists(public_path('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture)))
                                                <img class="img-avatar mr-3" alt="{{ $opportunity->postedByOpportunity->name }}"
                                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                    src="{{ public_path('/storage/profile/'. $opportunity->postedByOpportunity->profile_picture) }}">
                                            @else
                                                <img class="img-avatar mr-3" alt="No picture"
                                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                    src="{{ public_path('/images/avatar10.jpg')}}" />
                                            @endif
                                            <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                                <span>{{ $opportunity->postedByOpportunity ? $opportunity->postedByOpportunity->name : 'Not Specified' }}</span>
                                                <br><span>on {{ $opportunity->createdat ? date('d-m-Y', strtotime($opportunity->createdat)) : '-' }}</span>
                                            </span>
                                        </span>
                                    </div>
                                    <div style="position: relative;">
                                        <hr style="transform: rotate(90deg); width: 50px; position: absolute; top: -18px; left: 236px">
                                    </div>
                                    <div style="margin-bottom: 25px; position: relative">
                                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Approved By & Date</span>
                                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                                        <span style="margin-left: 40px; display: inline-block; vertical-align: middle">
                                            @if($opportunity->is_reviewed)
                                                @if($opportunity->opportunityapprover && $opportunity->opportunityapprover->profile_picture && File::exists(public_path('/storage/profile/'.$opportunity->opportunityapprover->profile_picture)))
                                                    <img class="img-avatar mr-3" alt="{{ $opportunity->opportunityapprover->name }}"
                                                        style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                        src="{{ public_path('/storage/profile/'.$opportunity->opportunityapprover->profile_picture) }}">
                                                @else
                                                    <img class="img-avatar mr-3" alt="No picture"
                                                        style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                        src="{{ public_path('/images/avatar10.jpg')}}" />
                                                @endif
                                                <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                                    <span>{{ $opportunity->opportunityapprover ? $opportunity->opportunityapprover->name : 'Not Specified' }}</span>
                                                    <br>
                                                    @if($opportunity->reviewdate)
                                                    <span style="font-size: 12px">on {{  date('d-m-Y', strtotime($opportunity->reviewdate))  }}</span>
                                                    @else
                                                    <span style="font-size: 12px"></span>
                                                    @endif
                                                </span>
                                            @else 
                                                <img class="img-avatar mr-3" alt="No picture"
                                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                    src="{{ public_path('/images/avatar10.jpg')}}" />
                                                <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                                    <span>Pending Review</span>
                                                    <br>
                                                    <span style="font-size: 12px"></span>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                    <div style="position: relative;">
                                        <hr style="transform: rotate(90deg); width: {{$opportunity->reviewdate ? '48px' :'42px'}}; position: absolute; top: {{$opportunity->reviewdate ? '-23px' :'-22px'}}; left: {{$opportunity->reviewdate ? '237px' :'240px'}}">
                                    </div>
                                    <div style="margin-bottom: 20px; padding-bottom: 20px">
                                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Closure Reviewed By & Date</span>
                                        <span class="circle circle-line-vertical" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                                        <span style="margin-left: 40px; display: inline-block; vertical-align: middle">
                                            @if($opportunity->is_closed)
                                                @if($opportunity->closurereviewer && $opportunity->closurereviewer->profile_picture && File::exists(public_path('/storage/profile/'.$opportunity->closurereviewer->profile_picture)))
                                                    <img class="img-avatar mr-3"  alt="{{ $opportunity->closurereviewer->name }}"
                                                        style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                        src="{{ public_path('/storage/profile/'.$opportunity->closurereviewer->profile_picture) }}">
                                                @else
                                                    <img class="img-avatar mr-3" alt="No picture"
                                                        style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                        src="{{public_path('/images/avatar10.jpg')}}" />
                                                @endif
                                                <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                                    <span>{{ $opportunity->closurereviewer ? $opportunity->closurereviewer->name : 'Not Specified' }}</span>
                                                    <br>
                                                    @if($opportunity->closed_at)
                                                    <span style="font-size: 12px">on {{ date('d-m-Y', strtotime($opportunity->closed_at)) }}</span>
                                                    @else
                                                    <span style="font-size: 12px"></span>
                                                    @endif
                                                </span>
                                            @else
                                                <img class="img-avatar mr-3" alt="No picture"
                                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                                    src="{{public_path('/images/avatar10.jpg')}}" />
                                                <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                                    <span>Pending Closure Review</span>
                                                    <br>
                                                    <span style="font-size: 12px"></span>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                                <div style="padding: 10px 20px">
                                    <div>
                                        <img src="{{ public_path('images/icons/conversation.png') }}"
                                            style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                                        <span style="display: inline-block; vertical-align: middle;font-weight: 600">User Interaction</span>
                                    </div>
                                    @if($opportunity->status!='Review Pending')
                                        <div style="margin-top: 10px; margin-left: 20px;">
                                            <p class="p-0 m-0" style="margin-bottom: 5px; font-weight: 500">Disapproved Reason</p>
                                            @if($opportunity->reason)
                                            <p class="p-0 m-0" style="font-weight: 300">
                                                {{ $opportunity->reason }}
                                            </p>
                                            @else
                                            <p class="p-0 m-0 text-secondary" style="font-weight: 300">No remarks added</p>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($opportunity->is_closed)
                                    <div style="margin-top: 10px;margin-left: 20px">
                                        <p class="p-0 m-0" style="margin-bottom: 5px; font-weight: 500">Closure Review Remarks</p>
                                        <p class="p-0 m-0" style="font-weight: 300">
                                            @if($opportunity->ndareason)
                                                {{ $opportunity->ndareason }}
                                            @else
                                                <span class="text-secondary">No closure remarks added</span>
                                            @endif
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
