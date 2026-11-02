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
        .process-stage-point.span-entry-under-review-action::after {
            content: 'Entry Under Review';
            position: absolute;
            top: 20px;
            left: -30px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-reviewed-ok::after {
            content: 'Action Approved';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-awaiting::after {
            content: 'Awaiting actions';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-proposed::after {
            content: 'Actions Proposed';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-reviewed-not-ok::after {
            content: 'Action Rejected';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-need-more-action::after {
            content: 'Need More Action';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-complete::after {
            content: 'Actions Completed';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-correction-proposed-rc-awaiting-action::after {
            content: 'Correction Proposed- RC Awaiting';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-proposed-review-awaiting-action::after {
            content: 'Actions Proposed- Review Awaiting';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-rc-update-correction-awaiting-action::after {
            content: 'RC Updated- Correction Awaiting';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-rejected::after {
            content: 'Entry Rejected';
            position: absolute;
            top: 20px;
            left: -40px;
            white-space: nowrap;
        }
        .process-stage-point.span-close-action::after {
            content: 'Closed';
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
        .rootcause-table, .rootcause-table td, .rootcause-table th {
            border: 1px solid #e1e6e9;
        }
        .rootcause-table th {
            color:#fff;
            background-color: #252945 !important;
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
    </style>
</head>
<body>
    <div style="width:100%; margin-bottom:10px;">
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
                        {{-- @else
                            <div style="width: 150px; height: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999;font-size:11px;">
                                [Logo Not Found]
                            </div> --}}
                        @endif
                    @endif
                </td>
                <!-- Company Name -->
                <td style="width:60%; text-align:center;">
                    <span style="font-size:14px; font-weight:bold;">
                        {{ $clientDetails ? $clientDetails->company_name : '' }}
                    </span>
                </td>

                <!-- Export Date -->
                <td style="width:20%; text-align:right;">
                    <span style="font-size:11px;">
                        Exported: {{ date('d-m-Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <div class="container">
        <table class="heading-table">
            <tr>
                <td><label class="page-title">NCR & Corrective Actions</label><span class="issue-title">{{ $corrective->title }}</span></td>
                <td class="text-right">
                    @php
                        $resp_user_image = DB::table('users')->where('email', $corrective->responsibility)->value('profile_picture');
                        $posted_user_image = DB::table('users')->where('employeeid', $corrective->postedby)->value('profile_picture');
                    @endphp
                    @if($resp_user_image && File::exists(public_path('/storage/profile/'.$resp_user_image)))
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'.$resp_user_image) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif

                    @if($posted_user_image && File::exists(public_path('/storage/profile/'.$posted_user_image)))
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'.$posted_user_image) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                </td>
            </tr>
        </table>
        <div class="overview-status my-20">
            <div class="block" style="width: 35%; margin-right: 20px; padding-bottom: 10px;height: auto;">
                <p class="p-0 m-0 block-title" style="margin-bottom: 20px;">
                    <img src="{{ public_path('images/icons/eye.png') }}">
                    <span>Overview</span>
                </p>
                <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                    <span class="badge badge-red" style="margin-right: 5px;">
                        <img src="{{ public_path('images/icons/vpn_key.png') }}" alt="issueid_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                        <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">#{{ $corrective->issueid }}</span>
                    </span>
                </p>
                @if(!($corrective->type=='-' || $corrective->type==''))
                    <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-warning" style="margin-right: 5px;background-color:{{ $corrective->typ ? $corrective->typ->background_color :''}};
                            color:{{ $corrective->typ ?  $corrective->typ->text_color : ''}};">
                            <img src="{{ public_path('images/icons/type_' . ($corrective->typ ? $corrective->typ->order : '') . '.png') }}" alt="type_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $corrective->typ ? $corrective->typ->name : '' }}</span>
                        </span>
                    </p>
                @endif
                
                @if($dept)
                    <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-blue" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/department-icon.png') }}" alt="department_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $dept }}</span>
                        </span>
                    </p>
                @endif
                @if($corrective->division_id != 0)
                    <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-blue" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/division-subdivision-icon.png') }}" alt="division_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ App\Division::where('id',$corrective->division_id)->value('name') ?? '-'}}</span>
                        </span>
                    </p>
                @endif
                @if($corrective->area_id !=0)
                    <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-blue" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/division-subdivision-icon.png') }}" alt="subdivision_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ App\Area::where('id',$corrective->area_id)->value('name') ?? '-'}}</span>
                        </span>
                    </p>
                @endif
                @if($cat)
                <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-flat" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/category-icon.png') }}" alt="category_icon" style="width: 16px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $cat }}</span>
                        </span>
                    </p>
                @endif
                @if($corrective->source)
                    <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-flat" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/source-icon.png') }}" alt="source_icon" style="width: 18px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $corrective->source }}</span>
                        </span>
                    </p>
                @endif
                @if($corrective->categoryDetectionStage)
                    <p class="m-0" style="margin-left: 20px; margin-bottom: 10px; font-weight: 500">
                        <span class="badge badge-warning" style="margin-right: 5px;">
                            <img src="{{ public_path('images/icons/detection_stage_icon.png') }}" alt="source_icon" style="width: 18px; display: inline-block; vertical-align: middle;">
                            <span class="ml-2" style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $corrective->categoryDetectionStage->name }}</span>
                        </span>
                    </p>
                @endif
            </div>
            <div class="block" style="width: 61%; padding-bottom: 10px">
                <p class="p-0 m-0 block-title" style="margin-bottom: 10px;">
                    <img src="{{ public_path('images/icons/line-chart.png') }}">
                    <span>Status & Timeline</span>
                </p>
                <p class="m-0" style="margin-bottom: 20px; margin-left: 25px; font-weight: 500">
                    Current Process Stage
                </p>
                <p class="m-0" style="margin-bottom: 50px; margin-left: 25px; font-weight: 500">
                    <label class="circle has-after @if($corrective->processstage == 'Entry Under Review') bg-warning @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Entry Under Review') span-entry-under-review-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'rejected') bg-danger @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'rejected') span-rejected @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Awaiting actions') bg-secondary @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Awaiting actions') span-action-awaiting @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Correction Proposed- RC Awaiting') bg-blue @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Correction Proposed- RC Awaiting') span-correction-proposed-rc-awaiting-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'RC Updated- Correction Awaiting') bg-blue @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'RC Updated- Correction Awaiting') span-rc-update-correction-awaiting-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Actions Proposed- Review Awaiting') bg-blue @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Actions Proposed- Review Awaiting') span-proposed-review-awaiting-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Action Reviewed, OK') bg-success @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Action Reviewed, OK') span-action-reviewed-ok @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Action Reviewed, Not OK') bg-danger @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Action Reviewed, Not OK') span-action-reviewed-not-ok @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Actions Completed') bg-primary @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Actions Completed') span-action-complete @endif"></span>
                    </label>
                    <label class="circle has-after @if($corrective->processstage == 'Need More Action') bg-warning @endif">
                        <span class="process-stage-point @if($corrective->processstage == 'Need More Action') span-need-more-action @endif"></span>
                    </label>
                    <label class="circle @if($corrective->status == 'closed') bg-black @endif">
                        <span class="process-stage-point @if($corrective->status == 'closed') span-close-action @endif"></span>
                    </label>
                </p>
                <p class="m-0" style=" margin-left: 25px; font-weight: 500">
                    <span class="d-inline-block" style="float: left;font-size:10px;">Recommended due date: {{ $corrective->correction_target_date ? date('d-m-Y', strtotime($corrective->correction_target_date)) : '' }}</span>
                    <span class="d-inline-block" style="float: right;font-size:10px;margin-right:10px;">Target Date(Investigation): {{ $corrective->investigation_date ? date('d-m-Y', strtotime($corrective->investigation_date)):''}}</span>
                </p>
            </div>
        </div>
        @if($ncrFields)
            {!! $ncrFields !!}
        @endif
        <div class="detail my-10" style="{{ $breakPage ? 'page-break-before: always;' : '' }}">
            <div style="border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px; background: #F6F7F9;">
                    <p class="p-0 m-0">
                        <img src="{{ public_path('images/icons/clipboard.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Details</span>
                    </p>
                </div>
                <div style="background: #F6F7F9; ">
                    <div style="margin-left: 45px; margin-right: 45px;">
                        <div class="d-inline-block" style="padding: 10px; width: 55%; margin-right: 10px; vertical-align: top">
                            <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">
                                @if($cat=="Incident Investigation")
                                    Incident # {{$corrective->title}}
                                @else
                                    {{ $corrective->title }}
                                @endif
                            </p>
                            <p class="m-0" style="font-size: 12px; font-weight: 300; text-align: justify">{{ $corrective->observation }}</p>
                            @if($corrective->agreedaction)
                            <p style="margin:10px 0px; font-weight: 500;">
                                Recommended Action
                            </p>
                            <p class="m-0" style="font-size: 12px; font-weight: 300; text-align: justify">{{ $corrective->agreedaction }}</p>
                            @endif
                        </div>
                        <div class="d-inline-block" style="padding: 10px; width: 40%; vertical-align: top">
                            <p class="m-0" style="margin-bottom: 5px; font-weight: 500;">
                                Financial Cost & Potential Cost Savings
                            </p>
                            <div style="background: #AF1E1E3D; border-radius: 10px; margin-bottom: 10px; padding: 4px 8px">
                                <img src="{{ public_path('images/icons/financial_icon.png') }}"
                                     style="background: #AF1E1E;width: 10px;height: 10px;padding: 5px; border-radius: 5px; margin-right: 5px; margin-top: 3px; display: inline-block">
                                <span style="color: #AF1E1E; font-weight: bold; display: inline-block; vertical-align: middle; overflow-wrap: anywhere; word-break: break-all;word-wrap: break-word;">
                                    @if($corrective->financial_loss)
                                        {{ $corrective->financial_loss }}
                                    @else
                                        No financial details provided
                                    @endif
                                </span>
                            </div>
                            <div style="background: #1760443D; border-radius: 10px;  margin-bottom: 10px; padding: 4px 8px">
                                <img src="{{ public_path('images/icons/money.png') }}"
                                        style="width: 20px;height: 20px;border-radius: 5px; margin-right: 5px;margin-top: 3px; display: inline-block;">
                                <span style="color: #176044; font-weight: bold; display: inline-block; vertical-align: middle; overflow-wrap: anywhere; word-break: break-all;word-wrap: break-word;">
                                    @if($corrective->potential_cost_savings)
                                        {{ $corrective->potential_cost_savings }}
                                    @else
                                        No cost-saving details provided
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @if($corrective->is_previously_occurred=='yes')
                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;page-break-before: avoid !important;">
                    <div style="margin-left: 45px; margin-right: 45px; padding-bottom: 20px;width:60% !important;">
                        <div style="padding-top: 20px; margin-bottom: 10px;">
                            <p style="font-weight: 500;">Issue History</p>
                            <div style="background: white;">
                                @if(($corrective->no_of_occurrence==0 || $corrective->no_of_occurrence==null) && $corrective->previous_ncr_reference==null)
                                <label style="margin-left: 0.5rem !important;">
                                    <span style="display:inline-block; margin-top:0.4rem !important;
                                                text-align:center;
                                                background:#c4c6ce;
                                                font-size:10px;
                                                padding:4px 6px;
                                                border-radius:5px;">
                                                
                                        <img src="{{ public_path('images/icons/sync-alt-solid-svgrepo-com.png') }}"
                                            width="16"
                                            style="vertical-align:middle;">
                                    </span>
                                    &nbsp;
                                    This issue has been previously recorded.
                                </label>
                            @elseif(($corrective->no_of_occurrence!=0 && $corrective->no_of_occurrence!=null) && $corrective->previous_ncr_reference==null)
                                    
                                <label style="margin-left: 0.5rem !important;">
                                    <span style="display:inline-block; margin-top:0.4rem !important;
                                                text-align:center;
                                                background:#c4c6ce;
                                                font-size:10px;
                                                padding:4px 6px;
                                                border-radius:5px;">
                                                
                                        <img src="{{ public_path('images/icons/sync-alt-solid-svgrepo-com.png') }}"
                                            width="16"
                                            style="vertical-align:middle;">
                                    </span>
                                    &nbsp; This issue has recurred <u>{{ $corrective->no_of_occurrence }}</u> times.
                                </label>
                            @elseif($corrective->previous_ncr_reference==0 && $corrective->other_previous_ncr_reference!=null)
                                @if($corrective->no_of_occurrence!=0 || $corrective->no_of_occurrence!=null)
                                    <label style="margin-left: 0.5rem !important;">
                                        <span style="display:inline-block; margin-top:0.4rem !important;
                                                    text-align:center;
                                                    background:#c4c6ce;
                                                    font-size:10px;
                                                    padding:4px 6px;
                                                    border-radius:5px;">
                                                    
                                            <img src="{{ public_path('images/icons/sync-alt-solid-svgrepo-com.png') }}"
                                                width="16"
                                                style="vertical-align:middle;">
                                        </span>
                                        &nbsp;
                                        This issue has recurred <u>{{ $corrective->no_of_occurrence }}</u> times.
                                    </label>
                                @endif
                                <div style="">
                                    <label style="margin-left: 0.5rem !important;">External Reference:</label>
                                    <span class="badge badge-blue" style="margin-right: 5px;margin-top: 0.5rem !important;margin-bottom: 0.2rem !important;">
                                        {{ $corrective->other_previous_ncr_reference }}
                                    </span>
                                </div>
                            @else
                                @if($corrective->no_of_occurrence!=0 || $corrective->no_of_occurrence!=null)
                                    <label style="margin-left: 0.5rem !important;">
                                        <span style="display:inline-block; margin-top:0.4rem !important;
                                                    text-align:center;
                                                    background:#c4c6ce;
                                                    font-size:10px;
                                                    padding:4px 6px;
                                                    border-radius:5px;">
                                                    
                                            <img src="{{ public_path('images/icons/sync-alt-solid-svgrepo-com.png') }}"
                                                width="16"
                                                style="vertical-align:middle;">
                                        </span>
                                        &nbsp; This issue has recurred <u>{{ $corrective->no_of_occurrence }}</u> times.</label>
                                @endif
                                <div style="margin-left: 1rem !important;margin-top: 1rem !important;margin-bottom: 0.2rem !important;">
                                    <a href="{{ route('corrective.detail', $corrective->previous_ncr_reference) }}" style="margin-left: 1rem !important;text-decoration: none;">
                                        <span class="badge badge-blue" style="margin-right: 5px">
                                            @if($corrective->previousNcrReferenceRelation)
                                                {{ $corrective->previousNcrReferenceRelation->issueid }} - {{ $corrective->previousNcrReferenceRelation->title }}
                                            @endif
                                        </span>
                                    </a>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;page-break-before: avoid !important;">
                    <div style="margin-left: 45px; margin-right: 45px; padding-bottom: 20px">
                        <div style="padding-top: 20px; margin-bottom: 10px;">
                            <p style="font-weight: 500;">Correction & Root Cause</p>
                            <div style="background: white;">
                                <div class="d-inline-block"
                                     style="padding: 10px; width: 50%; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                                    <p class="m-0" style="margin-bottom: 10px;font-weight: 500;">Correction</p>
                                    <p class="m-0" style="font-weight: 300;">
                                        @if($corrective->correction)
                                            <label>{{ $corrective->correction }}</label>
                                        @else
                                            <label class="text-danger">No correction added</label>
                                        @endif
                                    </p>
                                </div>
                                <div class="d-inline-block" style="padding: 10px; width: 40%;border-radius: 10px 10px 10px 10px; vertical-align: top">
                                    <p class="m-0" style="margin-bottom: 25px; font-weight: 500;">Responsibility</p>
                                    @php
                                        $responsibilities = explode(',', $corrective->correction_responsibility);
                                        $resp_to_show = App\User::whereIn('email', $responsibilities)->pluck('profile_picture', 'name')->toArray();
                                        $respNames = implode(', ', array_keys($resp_to_show));
                                    @endphp
                                    <div class="m-0 responsibility-block" style="font-size: 12px; font-weight: 300">
                                        @foreach($resp_to_show as $name => $picture)
                                            @if($picture && File::exists(public_path('/storage/profile/'.$picture)))
                                                <img class="img-avatar48" style="" alt="{{ $name }}"
                                                     src="{{ public_path('/storage/profile/'.$picture) }}">
                                            @else
                                                <img class="img-avatar48" alt="No picture"
                                                     src="{{ public_path('/images/avatar10.jpg')}}" />
                                            @endif
                                        @endforeach
                                        <br><span class="res-name">{{ $respNames ? $respNames : '-' }}</span><br>
                                        <div class="d-inline-block" style="margin-top: 10px">
                                            <div style="width: 200px">
                                                @if($dept)
                                                    <div style="background: #AF6F1E3D; padding: 5px 10px; border-radius: 3px; width: 100%">
                                                        <img src="{{ public_path('images/icons/quality_assurance_icon.png') }}"
                                                             style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 5px">
                                                        <span style="color: #AF6F1E; font-weight: bold; display: inline-block;vertical-align: middle">{{ $dept }}</span>
                                                    </div>
                                                @endif
                                                @if($corrective->correction_target_date)
                                                    <div style="background: #44B3AB3D; margin-top: 10px; padding: 5px 10px; border-radius: 3px; width: 100%">
                                                        <img src="{{ public_path('images/icons/calendar_corrective.png') }}"
                                                             style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 5px">
                                                        <span style="color: #44B3AB; font-weight: bold; display: inline-block; vertical-align: middle">
                                                        {{ date('d-m-Y', strtotime($corrective->correction_target_date)) }}
                                                    </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($corrective->is_req_rc_analysis_management_review=="yes")
                <div style="padding: 10px 0;">
                    @php
                        $rootcause = explode('$', $corrective->rootcause);
                        $correctiveaction = explode('$', $corrective->correctiveaction);
                        $responsibility = explode(',', $corrective->responsibility);
                        $department = explode(',', $corrective->department);
                        $target_date = explode(',', $corrective->target_date);
                        $count = 1;
                    @endphp
                    <p class="m-0" style="margin-bottom: 10px;font-weight: 500;">Root Cause</p>
                    <table class="rootcause-table" style="page-break-before: avoid !important">
                        <thead>
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th>Root cause</th>
                            <th>Corrective Action</th>
                            <th style="width: 10%">Department</th>
                            <th style="width: 20%">Responsible User</th>
                            <th style="width: 15%">Target Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($rootcause)>0 && $rootcause[0]!='')
                        @foreach($rootcause as $item)
                            @php
                                $dept = isset($department[$count - 1]) ? App\Departments::where('id', $department[$count - 1])->value('departmentname') : '';
                                $resp = isset($responsibility[$count - 1]) ? App\User::where('email', $responsibility[$count - 1])->value('name') : '';
                                $respProfile = isset($responsibility[$count - 1]) ? App\User::where('email', $responsibility[$count - 1])->value('profile_picture') : '';
                            @endphp
                            <tr>
                                <td>{{ $count }}</td>
                                <td>{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : '' }}</td>
                                <td>{{ isset($correctiveaction[$count - 1] ) ? $correctiveaction[$count - 1] : ''}}</td>
                                <td>{{ $dept }}</td>
                                <td>
                                    <div style="background: #25499C24; border-radius: 10px; padding: 10px;">
                                        <div style="text-align: center">
                                            @if($respProfile && File::exists(public_path('/storage/profile/'.$respProfile)))
                                                <img src="{{ public_path('/storage/profile/'.$respProfile) }}"
                                                     style="width: 28px; height: 28px; border-radius: 14px;">
                                            @else
                                                <img src="{{public_path('/images/avatar10.jpg')}}"
                                                     style="width: 28px; height: 28px; border-radius: 14px;">
                                            @endif
                                        </div>
                                        <div style="margin-top: 5px !important; text-align: center">
                                            <span style="color: #25499C; font-weight: bold; font-size: 10px;">{{ $resp }}</span>
                                            <br><span style="font-size: 8px; font-weight: bold; font-style: italic">
                                                @if((
                                                        isset($investigationTeams)
                                                        && count($investigationTeams) > 0
                                                        && isset($responsibility[$count - 1]) && in_array($responsibility[$count - 1], $investigationTeams->pluck('user.email')->toArray())
                                                    )
                                                    || (isset($responsibility[$count - 1]) && $corrective->teamLead && $corrective->teamLead->email == $responsibility[$count - 1])
                                                )
                                                    Investigation Team
                                                @else
                                                    Responsible User
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size: 10px">{{ isset($target_date[$count - 1]) && $target_date[$count - 1] ? date('d-m-Y', strtotime($target_date[$count - 1])) : ''}}</td>
                            </tr>
                            @php
                                $count++;
                            @endphp
                        @endforeach
                        @else 
                            <tr>
                                <td colspan="6" style="text-align: center; color: #f00;">No root cause added yet.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                @endif
                @if($corrective->is_moc_required=='yes')
                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;page-break-before: avoid !important;">
                    <div style="margin-left: 45px; margin-right: 45px; padding-bottom: 20px">
                        <div style="padding-top: 20px; margin-bottom: 10px;">
                            <p style="font-weight: 500;">MOC Details</p>
                            <div style="background: white;">
                                <div class="d-inline-block" style="padding: 10px; width: 40%;border-radius: 10px 10px 10px 10px; vertical-align: top">
                                    <p class="m-0" style="margin-bottom: 15px; font-weight: 500;">
                                        <span class="badge badge-primary" style="color:blue;">{{$corrective->moc->crid}}</span>
                                    </p>
                                    <p class="m-0" style="font-weight: 500;">Change Description</p>
                                    <p class="m-0" style="font-weight: 300;font-size: 12px; text-align: justify;">
                                        <label>{{ $corrective->change_description }}</label>
                                    </p>
                                </div>
                                <div class="d-inline-block"
                                     style="padding: 10px; width: 50%; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">

                                    <p class="m-0" style="margin-bottom: 15px; font-weight: 500;">
                                        @if($corrective->moc->status=="Review Pending")
                                        <span class="badge badge-info" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;">{{$corrective->moc->status}}</span>
                                        @elseif($corrective->moc->status=="Reviewed")
                                        <span class="badge badge-success" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;">{{$corrective->moc->status}}</span>
                                        @elseif($corrective->moc->status=="Approved")
                                        <span class="badge badge-primary" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;">{{$corrective->moc->status}}</span>
                                        @elseif($corrective->moc->status=="Rejected")
                                        <span class="badge badge-danger" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;"> {{$corrective->moc->status}}</span>
                                        @elseif($corrective->moc->status=="Partially Completed")
                                        <span class="badge badge-warning" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;"> {{$corrective->moc->status}}</span>
                                        @elseif($corrective->moc->status=="Not Started")
                                        <span class="badge badge-warning" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;"> {{$corrective->moc->statuss}}</span>
                                        @elseif($corrective->moc->status=="Completed")
                                        <span class="badge badge-info" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;"> {{$corrective->moc->status}}</span>
                                        @elseif($corrective->moc->status=="Closed")
                                        <span class="badge badge-success" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;"> {{$corrective->moc->status}}</span>
                                        @else
                                        <span class="badge badge-warning" style="font-size:11px;padding-left:5px;padding-right:5px;padding-top:3px;padding-bottom:3px;">{{$corrective->moc->status}}</span>
                                        @endif
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;page-break-before: avoid !important;">
                    <div style="margin-left: 45px; margin-right: 45px; padding-bottom: 20px">
                        @if($corrective->is_req_rc_analysis_management_review=="yes")
                        <div style="margin-top: 20px; padding-bottom: 30px;">
                            <div style="vertical-align: top; width: 45%; display: inline-block">
                                <p class="m-0 p-0" style="; margin-bottom: 20px; margin-left: 53px;font-weight: 500;">Investigation Team</p>
                                <div style="margin-left: 55px">
                                    @if((isset($investigationTeams) && count($investigationTeams) > 0) || isset($corrective->teamLead))
                                        @if(isset($corrective->teamLead))
                                            <div style="display: inline-block; padding: 5px 12px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white; margin-bottom: 10px">
                                                @if($corrective->teamLead->profile_picture && File::exists(public_path('/storage/profile/'. $corrective->teamLead->profile_picture)))
                                                    <img src="{{ public_path('/storage/profile/'. $corrective->teamLead->profile_picture) }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @else
                                                    <img src="{{ public_path('/images/avatar10.jpg') }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @endif
                                                <div style="display: inline-block; vertical-align: middle">
                                                    <label>{{ $corrective->teamLead->name }}</label>
                                                    <br><span style="font-size: 10px">Team Lead</span>
                                                </div>
                                            </div><br>
                                        @endif
                                        @foreach($investigationTeams as $investigationItem)
                                            @if ($investigationItem->user)
                                                @php
                                                    $user = $investigationItem->user;
                                                @endphp
                                                <div style="display: inline-block; padding: 5px 12px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white; margin-bottom: 10px">
                                                    @if($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture)))
                                                        <img src="{{ public_path('/storage/profile/'.$user->profile_picture) }}"
                                                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                    @else
                                                        <img src="{{ public_path('/images/avatar10.jpg') }}"
                                                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                    @endif
                                                    <label style="display: inline-block; vertical-align: middle">{{ $user->name }}</label>
                                                </div><br>
                                            @endif
                                        @endforeach
                                    @else
                                        <label class="text-danger ml-2">No investigation team</label>
                                    @endif
                                </div>
                            </div>
                            <div style="vertical-align: top; width: 45%; display: inline-block">
                                <p class="m-0 p-0" style=";margin-bottom: 10px; margin-left: 53px;font-weight: 500;">Investigation Report</p>
                                <button type="button"
                                        style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-left: 55px; color: white; padding: 5px 10px">
                                    View report
                                </button>
                            </div>
                        </div>
                        @endif
                        <div style="margin-top: 20px; padding-bottom: 50px;">
                            <div style="vertical-align: top; width: 45%; display: inline-block">
                                <p class="m-0 p-0" style="; margin-bottom: 20px; margin-left: 53px;font-weight: 500;">Send Notifications To</p>
                                <div style="margin-left: 55px">
                                    @if(count($notificationUsers) > 0)
                                        @foreach($notificationUsers as $user)
                                            <div style="display: inline-block; padding: 5px 12px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white; margin-bottom: 10px">
                                                @if($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture)))
                                                    <img src="{{ public_path('/storage/profile/'.$user->profile_picture) }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @else
                                                    <img src="{{ public_path('/images/avatar10.jpg') }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @endif
                                                <label style="display: inline-block; vertical-align: middle">{{ $user->name }}</label>
                                            </div><br>
                                        @endforeach
                                    @else
                                        <label class="text-danger ml-2">No users to send notifications</label>
                                    @endif
                                </div>
                            </div>
                            <div style="vertical-align: top; width: 45%; display: inline-block">
                                <p class="m-0 p-0" style="; margin-bottom: 10px; margin-left: 53px;font-weight: 500;">Email reminder for action</p>
                                <div style="margin-left: 55px">
                                    @if(count($frequency))
                                        @foreach($frequency as $freq)
                                            <div style="margin-bottom: 10px">
                                                <img src="{{ public_path('images/icons/calendar.png') }}"
                                                     style="display: inline-block; vertical-align: middle; width: 16px; height: 16px">
                                                <span style="display: inline-block; vertical-align: middle">{{ $freq }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <label class="text-danger ml-3">No email reminder for action</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 20px">
                            <div style="vertical-align: baseline; width: 100%">
                                <p class="m-0 p-0" style="; margin-bottom: 10px; margin-left: 53px;font-weight: 500;">Attachments</p>
                                <p class="m-0 p-0" style="; margin-bottom: 5px; margin-left: 55px;font-weight: 500;">Attachments of Issues</p>
                                <div style="margin-left: 55px">
                                    @if(count($issuedocs) > 0)
                                        @foreach($issuedocs as $issue_item)
                                            @if($issue_item && $issue_item != 'NULL')
                                                <span class="badge badge-success"
                                                      style="font-size: 0.75rem;color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                                    {{ $issue_item }}
                                                </span><br>
                                            @else
                                                <label class="text-danger ml-2">No attachments of issues added</label>
                                            @endif
                                        @endforeach
                                    @else
                                        <label class="text-danger ml-2">No attachments of issues added</label>
                                    @endif
                                </div>
                                <p class="m-0 p-0" style="; margin-bottom: 5px; margin-left: 55px; margin-top: 10px;font-weight: 500;">Attachments of Actions</p>
                                <div style="margin-left: 55px">
                                    @if(count($actiondocs) > 0)
                                        @foreach($actiondocs as $action_item)
                                            @if($action_item && $action_item != 'NULL')
                                                <span class="badge badge-success"
                                                      style="color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 10px;">
                                                    {{ $action_item }}
                                                </span><br>
                                            @else
                                                <label class="text-danger ml-2">No attachments of actions added</label>
                                            @endif
                                        @endforeach
                                    @else
                                        <label class="text-danger ml-2">No attachments of actions added</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-20" style="{{ $ncrFields ? 'page-break-before: always !important;' : '' }}">
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
                            @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'. $postedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $postedBy->name }}"
                                     style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                     src="{{ public_path('/storage/profile/'. $postedBy->profile_picture) }}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                     style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                     src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                <span style="font-size: 16px;">{{ $postedBy ? $postedBy->name : 'Not Specified' }}</span>
                                <br><span>on {{ $corrective->postdate ? date('d-m-Y', strtotime($corrective->postdate)) : '-' }}</span>
                            </span>
                        </span>
                    </div>
                    <!-- entry review by starts -->
                    @if($corrective->entry_reviewed_by || $corrective->processstage==='Entry Under Review')
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -20px; left: 236px">
                    </div>
                    <div style="margin-bottom: 25px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Entry Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($corrective->approvedByCorrective && $corrective->approvedByCorrective->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->approvedByCorrective->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $corrective->approvedByCorrective->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$corrective->approvedByCorrective->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $corrective->approvedByCorrective ? $corrective->approvedByCorrective->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px">on {{ $corrective->entry_reviewed_by_date ? date('d-m-Y', strtotime($corrective->entry_reviewed_by_date)) : '-' }}</span>
                        </span>
                    </span>
                    </div>
                    @endif
                    <!-- ends entry review -->
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -20px; left: 236px">
                    </div>
                    <div style="margin-bottom: 25px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Action Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($corrective->actionReviewedByCorrective && $corrective->actionReviewedByCorrective->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->actionReviewedByCorrective->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $corrective->actionReviewedByCorrective->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$corrective->actionReviewedByCorrective->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $corrective->actionReviewedByCorrective ? $corrective->actionReviewedByCorrective->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px">on {{ $corrective->action_reviewed_at ? date('d-m-Y', strtotime($corrective->action_reviewed_at)) : '-' }}</span>
                        </span>
                    </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -26px; left: 236px">
                    </div>
                    <div style="margin-bottom: 20px; padding-bottom: 20px">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Closure Reviewed By & Date</span>
                        <span class="circle circle-line-vertical" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($corrective->closedByCorrective && $corrective->closedByCorrective->profile_picture && File::exists(public_path('/storage/profile/'.$corrective->closedByCorrective->profile_picture)))
                            <img class="img-avatar mr-3"  alt="{{ $corrective->closedByCorrective->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$corrective->closedByCorrective->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px">{{ $corrective->closedByCorrective ? $corrective->closedByCorrective->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px">on {{ $corrective->closed ? date('d-m-Y', strtotime($corrective->closed)) : '-' }}</span>
                        </span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-20" style="page-break-before: avoid !important">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/conversation.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle;font-weight: 600">User Interaction</span>
                    </div>
                    @if($corrective->disapproval_reason)
                    <div style="margin-top: 30px; margin-left: 50px; padding-bottom: 30px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Entry Review Remarks</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            {{ $corrective->disapproval_reason }}
                        </p>
                    </div>
                    @endif
                    <div style="margin-top: 30px; margin-left: 50px; padding-bottom: 30px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Action Review Remarks</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            @if($corrective->action_remarks)
                                {{ $corrective->action_remarks }}
                            @else
                                <span class="text-danger">No action review remarks added</span>
                            @endif
                        </p>
                    </div>
                    <div style="margin-top: 20px; margin-left: 50px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Closure Review Remarks</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            @if($corrective->remarks)
                                {{ $corrective->remarks }}
                            @else
                                <span class="text-danger">No remarks added</span>
                            @endif
                        </p>
                    </div>
                    <div style="margin-top: 20px; margin-left: 50px; padding-bottom: 30px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Verification Remarks</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            @if($corrective->verification_remarks)
                                {{ $corrective->verification_remarks }}
                            @else
                                <span class="text-danger">No verification remarks added</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
