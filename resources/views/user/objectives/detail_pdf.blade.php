<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Setilas - CGBOD</title>
    <meta name="description" content="Setilas - CGBOD">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* @font-face {
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
        } */
        @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Regular.ttf") }}) format("truetype");
            font-weight: 400;
            font-style: normal;
        }
        /* @font-face {
            font-family: 'Inter';
            src: url({{ storage_path("fonts/inter/static/Inter-Medium.ttf") }}) format("truetype");
            font-weight: 500;
            font-style: normal;
        } */
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
        /* @font-face {
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
        } */
        @font-face {
            font-family: 'Symbola';
            src: url('{{ storage_path("fonts/Symbola/Symbola.ttf") }}') format('truetype');
        }
        @page {
            margin-top: 100px;
            margin-bottom: 70px;
        }

        /* Style Variables representing the original design */
        :root {
            --font-sans: "Inter";
            --font-display: "Outfit", sans-serif;
            --font-mono: "Inter";

            /* Color Palette */
            --bg-primary: #ffffff;
            --bg-secondary: #f1f5f9;
            --bg-bright-gray:#EDF0F2;
            --border-blueprint: #047cfc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --navy-btn: #14233c;

            /* Badges & Card Accents */
            --badge-id: #4a69bd;
            --badge-name: #38ada9;
            --badge-type: #f6b93b;
            --badge-category: #78e08f;
            --badge-dept: #f8c291;
            --badge-nature: #a5b1c2;
            --badge-from: #2ecc71;
            --badge-to: #e74c3c;
            --badge-span: #82ccdd;

            --pill-bg: #1760443D;
            --pill-border: #a2cbcf;
            --pill-text: #1e5256;
        }

        /* Base Styles */
        body {
            margin: 0 auto;
            padding: 0 0;
            /*width:794px;*/
            /*height: 1122px;*/
            font-family: 'Inter', 'Symbola', sans-serif;
            font-weight: 400;
            font-size: 11px;
            box-sizing: border-box;
        }

        .pdf-header{
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            height: 70px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 40px;
            text-align: center;
        }

        .page-number:before {
            content: counter(page);
        }

        .app-container {
            width: 100%;
            max-width: 100%; /* Override max-width for PDF */
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 4px;
        }

        .app-header {
            display: block;
            width: 100%;
            padding-top: 16px;
            padding-bottom: 8px;
            margin-bottom: 16px;
            background: #EDF0F2;
            vertical-align: middle;
            border-radius: 6px;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
        }

        .header-left {
            display: inline-block;
            width: 49%;
            vertical-align: middle;
        }

        .header-right {
            display: inline-block;
            width: 49%;
            text-align: right;
            vertical-align: middle;
        }

        .app-title {
            font-family: 'Inter';
            color: #1e293b;
            font-weight: 600;
            font-size: 15px;
            padding-left: 8px;
            /* text-transform: uppercase; */
            letter-spacing: 0.05em;
        }

        /* Profile Avatar circle styling */
        .user-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 16px;
            border: 1px solid #cbd5e1;
            display: inline-block;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .user-avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Dotted Blueprint Grid Section containers exactly like design image */
        .sheet-section {
            background-color: #EDF0F2;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 16px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .sheet-section:hover {
            border-color: #005cc4;
        }

        .section-head {
            display: block; /* changed from flex */
            margin-bottom: 16px;
            color:#000000;
        }

        .section-head img, .section-head span {
            display: inline-block;
            vertical-align: middle;
        }

        .section-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .section-icon-box {
            background-color: #e0eafc;
            /* color: var(--border-blueprint); */
            padding: 4px;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .section-title {
            padding-left: 8px;
            color: #1e293b;
            font-weight: 600;
            font-size: 12px; /* changed 16px to 12px*/
            letter-spacing: 0.03em;
            font-family: 'Inter';
        }

        /* SECTION 1: Overview LAYOUT SYSTEM */
        .overview-cards-container {
            display: block;
            font-size: 0px;
        }


        /* Inside Card Item for Overview */
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            padding: 12px;
            border-radius: 6px;
            display: inline-block; /* changed from inline-flex */
            margin: 6px;
            vertical-align: top;
            box-sizing: border-box;
        }

        .info-card2 {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            padding: 12px;
            border-radius: 6px;
            margin: 3px 6px;
            vertical-align: top;
            box-sizing: border-box;
        }

        .info-card-content {
            /* display: inline-block; */
        }

        .card-label {
            font-size: 9px; /* changed from 12px to 9px */
            color: #252945;
            font-weight: 600;
            letter-spacing: 0.05em;
            line-height: 1.5;
            margin-top: 0;
            margin-bottom: 4px;
        }

        .card-icon-value-wrapper {
            display: inline-block;
            vertical-align: middle;
            margin: 0
        }

        .card-icon {
            width: 24px; /*changed from 30 to 24px */
            height: 24px;
            display: inline-block;
            margin: 0;
            vertical-align: middle;
        }

        .card-value {
            font-size: 12px; /* 16 to 12 */
            font-weight: 700;
            color: #334155;
            font-family: 'Inter';
            word-wrap: break-word;
            padding-left: 8px;
            display: inline-block;
            margin: 0;
            vertical-align: middle;
        }

        .card-value-mono {
            font-family: 'Inter';
        }

        .overview-added-by {
            display: block;
            width: 100%;
            text-align: right;
        }

        .added-by-title {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        .added-by-value {
            font-size: 12px;
            color: #000;
            font-weight: 600;
        }

        .bg-id { background-color: var(--badge-id); }
        .bg-name { background-color: var(--badge-name); }
        .bg-type { background-color: var(--badge-type); }
        .bg-category {
            background-color: var(--badge-category);
            color: #1e293b;
        }
        .bg-dept {
            background-color: var(--badge-dept);
            color: #1e293b;
        }
        .bg-nature {
            background-color: var(--badge-nature);
            color: #1e293b;
        }
        .bg-from { background-color: var(--badge-from); }
        .bg-to { background-color: var(--badge-to); }
        .bg-span {
            background-color: var(--badge-span);
            color: #1e293b;
        }

        /* SECTION 2: PROCESS STAGES TIMELINE SIZING */
        .timeline-title-sub {
            font-size: 12px;
            /* text-transform: uppercase; */
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        .stepper-container {
            position: relative;
            padding: 24px 0;
        }

        .timeline-line {
            width: 100%;
            height: 2px;
            background-color: #000000;
            position: relative;
            margin: 20px 0;
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
            top: -4px;
        }

        .timeline-dot.active {
            background-color: #ca8a04;
        }
        .horizontal-dot-line-container {
            display: block;
            width: 60%;
            font-size: 0;           /* Crucial: Removes the invisible white-space gaps between inline-block elements */
            padding-bottom: 20px;   /* Adds bottom space so the absolute labels don't get cut off */
        }

        .stage {
            display: inline-block;
            vertical-align: middle; /* This is the missing piece! It aligns the stage with the line */
            position: relative;     /* Anchors the absolutely positioned label */
        }
        .dot {
            width: 15px;
            height: 15px;
            border-radius: 7px 7px 7px 7px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            margin-right: 120px;
        }
        .has-after::after {
            content: '';
            position: absolute;
            top: 8px;
            left: 15px;
            width: 130px;
            height: 2px;
            background: #000000;
        }
        .process-stage-point.span-under-review-action::after {
            content: 'Under Review';
            position: absolute;
            top: 20px;
            left: -25px;
            white-space: nowrap;
        }
        .process-stage-point.span-rejected-action::after {
            content: 'Rejected';
            position: absolute;
            top: 20px;
            left: -15px;
            white-space: nowrap;
        }
        .process-stage-point.span-reviewed-action::after {
            content: 'Reviewed';
            position: absolute;
            top: 20px;
            left: -15px;
            white-space: nowrap;
        }
        .process-stage-point.span-approved-action::after {
            content: 'Approved';
            position: absolute;
            top: 20px;
            left: -15px;
            white-space: nowrap;
        }
        .bg-under-review {
            background-color: #e5ae67;
        }

        .bg-rejected {
            background-color: #e56767;
        }

        .bg-reviewed {
            background-color: #5179d6;
        }

        .bg-approved {
            background-color: #30c78d;
        }

        .stage-label {
            position: absolute;
            top: 20px;                  /* Pushes the text directly below the dot */
            left: 50%;                  /* Pushes the start of the text to the middle of the dot */
            transform: translateX(-50%);/* Pulls the text back perfectly on center */
            font-size: 12px;
            white-space: nowrap;        /* Prevents text from breaking into multiple lines */
        }

        .line {
            display: inline-block;
            vertical-align: middle; /* Aligns perfectly with the middle of the .stage elements */
            height: 1px;
            background: #000000;
            width: 50px;
            min-width: 15px;
        }

        .target-date-row {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px dotted #e2e8f0;
            display: block; /* changed from flex */
            text-align: left;
        }

        .target-text {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        .target-value {
            font-family: 'Inter';
            font-weight: 600;
            color: #334155;
            margin-left: 4px;
        }


        /* SECTION 3: DETAILS (BENTO LAYOUT SPLIT) */
        .details-split {
            display: block;      /* Force block context */
            width: 100%;         /* Explicitly define the containing block width */
            margin-bottom: 32px;
            box-sizing: border-box;
        }

        .details-left-pane {
            display: inline-block;
            width: 66%;          /* Now safe to calculate */
            vertical-align: top;
            box-sizing: border-box;
        }

        .details-right-pane {
            display: inline-block;
            width: 30%;
            vertical-align: top;
            padding-left: 2%;
            box-sizing: border-box;
        }

        .desc-block-title {
            font-size: 12px; /*changed from 16 to 12 */
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .desc-block-text {
            font-size: 11px; /* from 14 to 11 */
            font-weight: 400;
            color: #475569;
            line-height: 1.6;
        }

        /* Reviewer avatars line-up list */
        .reviewers-list {
            display: block;
            font-size: 0;
        }

        .reviewer-pill {
            display: inline-block;
            background-color: #14233c;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 500;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .mini-avatar-thumb {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 15px;
            vertical-align: middle;
            margin-right: 8px;
        }

        .mini-avatar-thumb img {
            width: 30px;
            height: 30px;
            border-radius: 15px;
        }

        .reviewer-pill span {
            display: inline-block;
            font-size: 9px; /* changed from 11 to 9 */
            font-weight: 600;
            vertical-align: middle;
        }

        /* Downloadable files list */
        .attachments-wrapper {
            display: block;
        }

        .attachment-pill-btn {
            display: inline-block;
            vertical-align: middle;

            background-color: rgba(23, 96, 68, 0.24);
            color: #1e5256;
            font-size: 11px;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 4px;

            margin-right: 8px;
            margin-bottom: 8px;
        }

        .attachment-pill-btn span {
            display: inline-block;
            vertical-align: middle;
        }

        .no-attachment {
            display: inline-block;
            color: #64748b;
            font-size: 12px;
            font-style: italic;
        }

        /* Right-side metadata tags for Details */
        .meta-block {
            display: block;
        }

        .meta-block-title {
            font-size: 12px; /*16 to 12*/
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .responsibility-user {
            margin-top: 5px;
            margin-bottom: 0px;
            display: block;
            align-items: center;
        }

        .multiple-avatar:not(:first-child) {
            margin-left: -2rem;
        }

        .medium-avatar-thumb {
            width: 48px;
            height: 48px;
            display: inline-block;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .medium-avatar-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name-title {
            font-size: 12px; /*16 to 12*/
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .user-role-lbl {
            font-size: 10px;
            color: #64748b;
        }

        /* Static Target Box and Frequency labels */
        .pill-neutral-badge {
            display: inline-block;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 11px;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 8px;
            vertical-align: middle;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .pill-neutral-badge > * {
            display: inline-block;
            vertical-align: middle;
            margin: 0;
        }

        .pill-neutral-badge .small-icon {
            margin-right: 8px;
        }

        .small-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .target-progress-bar-placeholder {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid var(--navy-btn);
            border-right-color: transparent;
            display: block;
        }

        .target-progress-bar-value {
            font-size: 14px;
            font-weight: 600px;
        }

        /* Approver Badge Tag representation */
        .approver-pill-badge {
            display: inline-block;
            background-color: #14233c;
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
        }

        .approver-pill-badge > * {
            display: inline-block;
            vertical-align: middle;
        }

        .approver-pill-badge span {
            font-size: 9px;
            font-weight: 600;
            margin-left: 4px;
        }


        /* SECTION 4: HISTORIC TIMELINE AND STATUS HISTORY FLOW */

        #section-history {
            page-break-before: always;
        }
        .item-left {
            display: inline-block;
            vertical-align: middle;
            width: 200px;
            text-align: right;
            font-weight: 500;
        }
        .circle {
            width: 15px;
            height: 15px;
            border-radius: 7px 7px 7px 7px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
        }
        .circle.has-after::after {
            content: '';
            position: absolute;
            top: 8px;
            left: 15px;
            width: 40px;
            height: 2px;
            background: #000000;
        }

        .item-right {
            margin-left: 50px;
            display: inline-block;
            vertical-align: middle;
        }

        .user-icon {
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            border-radius: 16px;
            width: 32px;
            height: 32px
        }
        .info {
            display: inline-block;
            vertical-align: middle;
        }
        .status {
            font-family: 'Inter';
            font-weight: 600;
            font-size: 12px;
            /* font-style: italic; */
            color: #111827;
        }

        .date {
            font-family: 'Inter';
            font-weight: 500;
            font-size: 12px;
            color: #4b5563;
        }

        /* SECTION 5: USER REMARKS / FEEDBACK REMARKS */
        .remarks-stack {
            display: block;
        }

        .remark-caption {
            font-size: 12px; /* 16 to 12 */
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .remark-content-paragraph {
            font-size: 11px; /*14 to 11*/
            font-weight: 400;
            color: #475569;
            line-height: 1.6;
            max-width: 850px;
        }

        .no-remark-content-paragraph {
            font-size: 11px;
            font-weight: 400;
            color: #64748b;
            line-height: 1.6;
            max-width: 850px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="pdf-header" style="width:100%; margin-bottom:10px;">
        <table width="100%">
            <tr>
                <!-- Logo -->
                <td style="width:25%; text-align:left;">
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
                <td style="width:60%; text-align:center;">
                    <span style="font-size:14px; font-weight:bold;">
                        {{ $clientDetails ? $clientDetails->company_name : '' }}
                    </span>
                </td>

                <!-- Export Date -->
                <td style="width:25%; text-align:right;">
                    <span style="font-size:11px;">
                        Exported: {{ date('d-m-Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <footer>
        Page <span class="page-number"></span>
    </footer>
    <div class="app-container">
        <!-- SECTION 0: HEADER BLOCK -->
        <div class="app-header">
            <div class="header-left">
                <span class="app-title">Objective Module</span>
            </div>
            <div class="header-right">
                @php
                    if ($objective->new_postedby) {
                        $postedBy = getUsersFromUserIds($objective->new_postedby)->first();
                    }elseif ($objective->postedby) {
                        $postedBy = getAdminsFromEmployeeIds($objective->postedby)->first();
                        if (!$postedBy) {
                            $postedBy = getUsersFromEmployeeIds($objective->postedby)->first();
                        }
                    }
                @endphp
                @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'.$postedBy->profile_picture)))
                    <img class="user-avatar-circle" src="{{public_path('/storage/profile/'.$postedBy->profile_picture)}}">
                @else
                    <img class="user-avatar-circle" src="{{public_path('images/avatar10.jpg')}}"/>
                @endif
            </div>
        </div>
        <!-- SECTION 1: OVERVIEW BLOCK -->
        <section class="sheet-section" id="section-overview">
            <div class="section-head">
                <img class="section-icon" src="{{public_path('images/icons/EyeNew.png')}}" alt="">
                <span class="section-title">Overview</span>
            </div>

            @php
                $overViews = [
                    [
                        'title' => 'Objective ID',
                        'image_path' => 'images/icons/ObjectiveID.png',
                        'value' => $objective->objective_id,
                    ],
                    [
                        'title' => 'Objective Name',
                        'image_path' => 'images/icons/ObjectiveName.png',
                        'value' => $objective->objectives,
                    ],
                    [
                        'title' => 'Objective Type',
                        'image_path' => 'images/icons/ObjectiveType.png',
                        'value' => $objective->objectiveTypes ? $objective->objectiveTypes->pluck('name')->implode(', ') : '-',
                    ],
                    // [
                    //     'title' => 'Category',
                    //     'image_path' => 'images/icons/CategoryNew.png',
                    //     'value' => 'no cat',
                    // ],
                    [
                        'title' => 'Department',
                        'image_path' => 'images/icons/DepartmentNew.png',
                        'value' => $objective->dep ? $objective->dep->departmentname : '-',
                    ],
                    [
                        'title' => 'Objective / Target Nature',
                        'image_path' => 'images/icons/ObjectiveTargetNature.png',
                        'value' => $objective->target_nature ?? '-',
                    ],
                    [
                        'title' => 'From Date',
                        'image_path' => 'images/icons/FromDate.png',
                        'value' => $objective->fromdate ? date('d-m-Y', strtotime($objective->fromdate)) : '',
                    ],
                    [
                        'title' => 'To Date',
                        'image_path' => 'images/icons/ToDate.png',
                        'value' => $objective->todate ? date('d-m-Y', strtotime($objective->todate)) : '',
                    ],
                    [
                        'title' => 'Program Span',
                        'image_path' => 'images/icons/ProgramSpan.png',
                        'value' => $objective->span,
                    ],
                ];

                $maxWidth = 660;
                $chunks = [];
                $currentChunk = [];
                $currentWidth = 0;

                foreach ($overViews as $key => $overview) {
                    $valueLength = strlen((string) $overview['value']);
                    $titleLength = strlen((string) $overview['title']);
                    $valueWidth = $valueLength * 12 * 0.6;
                    $titleWidth = $titleLength * 9 * 0.6;
                    $cardWidth = ($valueWidth + 70) > ($titleWidth + 40) ? $valueWidth + 70 : $titleWidth + 40;
                    if (
                        !empty($currentChunk) &&
                        ($currentWidth + $cardWidth ) > $maxWidth
                    ) {
                        $chunks[] = $currentChunk;
                        $currentChunk = [];
                        $currentWidth = 0;
                    }

                    if (($key+1) >= count($overViews)) {
                        $overview['width'] = $cardWidth > $maxWidth ? 620 : $cardWidth;
                        $currentChunk[] = $overview;
                        $chunks[] = $currentChunk;
                        $currentChunk = [];
                        $currentWidth = 0;
                    } else {
                        $overview['width'] = $cardWidth > $maxWidth ? 620 : $cardWidth;
                        $currentChunk[] = $overview;
                        $currentWidth += $cardWidth;
                    }
                }
            @endphp
            <!-- CSS Responsive Grid System -->
            <div class="overview-cards-container">
                @foreach ($chunks as $chunk)
                    <div style="display: block; width: 660px; margin: 0px; padding: 0px; font-size: 0px;">
                        @foreach ($chunk as $item)
                            <div class="info-card2" style="display: inline-block;">
                                <div class="info-card-content">
                                    <p class="card-label">{{ $item['title'] }}</p>
                                    <div class="card-icon-value-wrapper">
                                        <img class="card-icon" src="{{public_path($item['image_path'])}}" />
                                        <span class="card-value card-value-mono">{{ $item['value'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div class="overview-added-by">
                    <span class="added-by-title">Added by: </span><span class="added-by-value">{{ $postedBy ? $postedBy->name : ($objective->new_postedby ?? ($objective->postedby ?? "Deleted User")) }}</span>
                </div>
            </div>
        </section>

        <!-- SECTION 2: STATUS & TIMELINE BLOCK -->
        <section class="sheet-section" id="section-status">
            <div class="section-head">
                <img class="section-icon" src="{{public_path('images/icons/EyeNew.png')}}" alt="">
                <span class="section-title">Status & Timeline</span>
            </div>

            <div class="stepper-container">
                <p class="timeline-title-sub">Process Stage</p>
            </div>
            <div style="width: 100%;">
                <p class="m-0" style="margin-bottom: 50px; margin-left: 100px; font-weight: 500">
                    <label class="dot has-after @if($objective->status == 'Review Pending') bg-under-review @endif">
                        <span class="process-stage-point @if($objective->status == 'Review Pending') span-under-review-action @endif"></span>
                    </label>
                    <label class="dot has-after @if($objective->status == 'Rejected') bg-rejected @endif">
                        <span class="process-stage-point @if($objective->status == 'Rejected') span-rejected-action @endif"></span>
                    </label>
                    <label class="dot has-after @if($objective->status == 'Reviewed') bg-reviewed @endif">
                        <span class="process-stage-point @if($objective->status == 'Reviewed') span-reviewed-action @endif"></span>
                    </label>
                    <label class="dot @if($objective->status == 'Approved') bg-approved @endif">
                        <span class="process-stage-point @if($objective->status == 'Approved') span-approved-action @endif"></span>
                    </label>
                </p>
            </div>

            <div class="target-date-row">
                <span class="target-text">
                    Target Date: <span class="target-value">15-09-2024</span>
                </span>
            </div>
        </section>

        <!-- SECTION 3: DETAILS BLOCK -->

        <section class="sheet-section" id="section-details">
            <div class="section-head">
                <img class="section-icon" src="{{public_path('images/icons/Details.png')}}" alt="">
                <span class="section-title">Details</span>
            </div>
            @php
                $objectiveDetails = [
                    [
                        'title' => 'Objective Description',
                        'value' => $objective->description,
                    ],
                    [
                        'title' => 'Baseline',
                        'value' => $objective->baseline,
                    ],
                    [
                        'title' => 'Methodology',
                        'value' => $objective->methodology,
                    ],
                    [
                        'title' => 'Resources',
                        'value' => $objective->resources
                    ]
                ];
                $responsiblePersons = getUsersFromEmailsOrNames($objective->responsibility);

            @endphp
            <div class="details-split">
                <div class="details-left-pane">
                    @foreach ($objectiveDetails as $objectiveDetail)
                        <div>
                            <h3 class="desc-block-title">{{ $objectiveDetail['title'] }}</h3>
                            <p class="desc-block-text">
                                {{ $objectiveDetail['value'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="details-right-pane">
                    <!-- Responsibility -->
                    <div class="meta-block">
                        <h4 class="meta-block-title">Responsibility</h4>
                        <div style="height: 10px;"></div>
                        <div class="responsibility-user">
                            @foreach ($responsiblePersons as $responsiblePerson)
                                <div class="medium-avatar-thumb multiple-avatar">
                                    @if($responsiblePerson->profile_picture && File::exists(public_path('/storage/profile/'.$responsiblePerson->profile_picture)))
                                        <img src="{{ public_path('/storage/profile/'.$responsiblePerson->profile_picture) }}" >
                                    @else
                                        <img src="{{public_path('/images/avatar10.jpg')}}"/>
                                    @endif
                                </div>
                                @php
                                    $responsiblePersonsName[] = $responsiblePerson->name;
                                @endphp
                            @endforeach
                        </div>
                        <h5 class="user-name-title">{{ implode(', ', $responsiblePersonsName) }}</h5>
                    </div>
                    <!-- Target Count -->
                    <div class="meta-block" id="block-target">
                        <h4 class="meta-block-title">Target Count</h4>
                        <div class="pill-neutral-badge">
                            <img class="small-icon" src="{{ public_path('images/icons/TargetCount.png')}}"/>
                            <span>{{$objective->otarget}} {{$objective->unit ?? ''}}</span>
                        </div>
                    </div>
                    <!-- Frequency of monitoring -->
                    <div class="meta-block" id="block-frequency">
                        <h4 class="meta-block-title">Frequency of monitoring</h4>
                        <div class="pill-neutral-badge">
                            <img class="small-icon" src="{{ public_path('images/icons/CalendarNew.png')}}"/>
                            <span>{{$objective->frequency}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="details-split">
                @php
                    $reviewers = getUsersFromUserIds($objective->reviewers) ?? [];
                @endphp
                @if ($reviewers)
                    <div class="details-left-pane">
                        <div id="block-reviewers">
                            <h3 class="desc-block-title" style="margin-bottom: 8px;">Reviewers</h3>
                            <div class="reviewers-list">
                                @php
                                    $maxWidth = 360;
                                    $chunks = [];
                                    $currentChunk = [];
                                    $currentWidth = 0;

                                    foreach ($reviewers as $key => $reviewer) {
                                        $nameLength = strlen((string) $reviewer->name);
                                        $nameWidth = $nameLength * 9 * 0.6;
                                        $cardWidth = $nameWidth + 42 + 30 + 8;
                                        if (
                                            !empty($currentChunk) &&
                                            ($currentWidth + $cardWidth ) > $maxWidth
                                        ) {
                                            $chunks[] = $currentChunk;
                                            $currentChunk = [];
                                            $currentWidth = 0;
                                        }

                                        if (($key+1) >= $reviewers->count()) {
                                            $reviewer->width = $cardWidth > $maxWidth ? 360 : $cardWidth;
                                            $currentChunk[] = $reviewer;
                                            $chunks[] = $currentChunk;
                                            $currentChunk = [];
                                            $currentWidth = 0;
                                        } else {
                                            $reviewer->width = $cardWidth > $maxWidth ? 360 : $cardWidth;
                                            $currentChunk[] = $reviewer;
                                            $currentWidth += $cardWidth;
                                        }
                                    }
                                @endphp
                                @foreach ($chunks as $chunk)
                                    <div style="display: block">
                                        @foreach ($chunk as $reviewer)
                                            <div class="reviewer-pill">
                                                <span class="mini-avatar-thumb">
                                                    @if($reviewer->profile_picture && File::exists(public_path('/storage/profile/'.$reviewer->profile_picture)))
                                                        <img src="{{ public_path('/storage/profile/'.$reviewer->profile_picture) }}" data-toggle="tooltip" data-potision="top" title="{{$reviewer->name??''}}">
                                                    @else
                                                        <img src="{{public_path('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$reviewer->name??''}}"/>
                                                    @endif
                                                </span>
                                                <span>{{ $reviewer->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($objective->approverDetails)
                    <div class="details-right-pane">
                        <div class="meta-block">
                            <h4 class="meta-block-title" style="margin-bottom: 8px;">Approver</h4>
                            <div class="approver-pill-badge">
                                <span class="mini-avatar-thumb" style="margin-right: 4px;">
                                    @if($objective->approverDetails && $objective->approverDetails->profile_picture && File::exists(public_path('/storage/profile/'.$objective->approverDetails->profile_picture)))
                                        <img src="{{ public_path('/storage/profile/'.$objective->approverDetails->profile_picture) }}" data-toggle="tooltip" data-potision="top" title="{{$objective->approverDetails->name??''}}">
                                    @else
                                        <img src="{{public_path('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$objective->approverDetails->name??''}}"/>
                                    @endif
                                </span>
                                <span>{{ $objective->approverDetails ? $objective->approverDetails->name : '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

             <!-- Attachments panel -->
                <div id="block-attachments">
                    <br>
                    <h3 class="desc-block-title">Attachments</h3>
                    <div class="attachments-wrapper">
                        @php
                            $files = $objective->files ? explode(',', $objective->files) : [];
                            $maxWidth = 660;
                            $chunks = [];
                            $currentChunk = [];
                            $currentWidth = 0;

                            foreach ($files as $key => $file) {
                                $nameLength = strlen((string) preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $file));
                                $nameWidth = $nameLength * 11 * 0.6;
                                $cardWidth = $nameWidth + 32 + 8;
                                if (
                                    !empty($currentChunk) &&
                                    ($currentWidth + $cardWidth ) > $maxWidth
                                ) {
                                    $chunks[] = $currentChunk;
                                    $currentChunk = [];
                                    $currentWidth = 0;
                                }

                                if (($key+1) >= count($files)) {
                                     $newFile = [
                                        'width' => $cardWidth > $maxWidth ? 620 : $cardWidth,
                                        'name' => $file
                                    ];
                                    $currentChunk[] = $newFile;
                                    $chunks[] = $currentChunk;
                                    $currentChunk = [];
                                    $currentWidth = 0;
                                } else {
                                    $newFile = [
                                        'width' => $cardWidth > $maxWidth ? 620 : $cardWidth,
                                        'name' => $file
                                    ];
                                    $currentChunk[] = $newFile;
                                    $currentWidth += $cardWidth;
                                }
                            }
                        @endphp
                        @if ($files)
                            @foreach ($chunks as $chunk)
                                <div style="display: block">
                                    @foreach ($chunk as $file)
                                        <div class="attachment-pill-btn">
                                            <span>{{preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $file['name'])}}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <span class="no-attachment">No Attachments Added</span>
                        @endif
                    </div>
                </div>
        </section>

        <!-- SECTION 4: TIMELINE & HISTORY BLOCK -->
        <section class="sheet-section" id="section-history">
            <div class="section-head">
                <img class="section-icon" src="{{public_path('images/icons/TimelineAndHistory.png')}}"/></i> <span class="section-title">Timeline & History</span>
            </div>
            <div style="margin-top: 16px; margin-left: 50px">
                <div style="margin-bottom: 19px; position: relative">
                    <span class="item-left">Prepared By & Date</span>
                    <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                    <span class="item-right">
                        @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'. $postedBy->profile_picture)))
                            <img alt="{{ $postedBy->name }}" class="user-icon" src="{{ public_path('/storage/profile/'. $postedBy->profile_picture) }}">
                        @else
                            <img alt="No picture" class="user-icon" src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span class="info">
                            <span class="status">{{ $postedBy ? $postedBy->name : 'Not Specified' }}</span>
                            <br><span class="date">on {{ $objective->created_at ? date('d-m-Y', strtotime($objective->created_at)) : 'DD-MM-YYYY'  }}</span>
                        </span>
                    </span>
                </div>
                <div style="position: relative;">
                    <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -17px; left: 236px">
                </div>
                @php
                    if ($objective->new_reviewedby) {
                        $reviewedBy = getUsersFromUserIds($objective->new_reviewedby)->first();
                    } elseif ($objective->reviewed_by) {
                        $reviewedBy = getAdminsFromEmployeeIds($objective->reviewed_by)->first();
                        if (!$reviewedBy) {
                            $reviewedBy = getUsersFromEmployeeIds($objective->reviewed_by)->first();
                        }
                    }
                @endphp
                <div style="margin-bottom: 19px; position: relative">
                    <span class="item-left">Last Reviewed By & Date</span>
                    <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                    <span class="item-right">
                        @if(in_array($objective->status, ['Reviewed', 'Approved']) && $reviewedBy && $reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$reviewedBy->profile_picture)))
                            <img alt="{{ $reviewedBy->name }}" class="user-icon" src="{{ public_path('/storage/profile/'.$reviewedBy->profile_picture) }}">
                        @else
                            <img alt="No picture" class="user-icon" src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span class="info">
                            <span class="status">{{in_array($objective->status, ['Reviewed', 'Approved']) &&  $reviewedBy ? $reviewedBy->name : 'Pending Approval' }}</span>
                            <br><span class="date">on {{in_array($objective->status, ['Reviewed', 'Approved']) &&  $objective->reviewed_at ? date('d-m-Y', strtotime($objective->reviewed_at)) : 'DD-MM-YYYY' }}</span>
                        </span>
                    </span>
                </div>
                <div style="position: relative;">
                    <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -17px; left: 236px">
                </div>
                @php
                    if ($objective->new_approvedby) {
                        $approvedBy = getUsersFromUserIds($objective->new_approvedby)->first();
                    } elseif ($objective->approved_by) {
                        $approvedBy = getAdminsFromEmployeeIds($objective->approved_by)->first();
                        if (!$approvedBy) {
                            $approvedBy = getUsersFromEmployeeIds($objective->approved_by)->first();
                        }
                    }
                @endphp
                <div style="margin-bottom: 23px; position: relative">
                    <span class="item-left">Approved By & Date</span>
                    <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                    <span class="item-right">
                        @if($objective->status == 'Approved' && $approvedBy && $approvedBy->profile_picture && File::exists(public_path('/storage/profile/'.$approvedBy->profile_picture)))
                            <img alt="{{ $approvedBy->name }}" class="user-icon" src="{{ public_path('/storage/profile/'.$approvedBy->profile_picture) }}">
                        @else
                            <img alt="No picture" class="user-icon" src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span class="info">
                            <span class="status">{{$objective->status == 'Approved' &&  $approvedBy ? $approvedBy->name : 'Pending Closure' }}</span>
                            <br><span class="date">on {{$objective->status == 'Approved' && $objective->approved_at ? date('d/m/Y', strtotime($objective->approved_at)) : 'DD-MM-YYYY' }}</span>
                        </span>
                    </span>
                </div>
            </div>
        </section>

      <!-- SECTION 5: USER INTERACTIONS BLOCK -->
      <section class="sheet-section" id="section-remarks">
        <div class="section-head">
            <img class="section-icon" src="{{public_path('images/icons/UserInteractions.png')}}" alt="">
            <span class="section-title">User Interactions</span>
        </div>

        <div class="remarks-stack">

          <!-- Reviewer Remarks -->
          <div>
            <h4 class="remark-caption">Reviewer Remarks</h4>
            <p class="{{ $objective->remarks ? 'remark-content-paragraph' : 'no-remark-content-paragraph'}}">
                {{ $objective->remarks ?? 'No Remarks Added'}}
            </p>
          </div>

          <!-- Reviewer Proposal -->
          <div>
            <h4 class="remark-caption">Reviewer Proposal</h4>
            <p class="{{ $objective->proposal ? 'remark-content-paragraph' : 'no-remark-content-paragraph'}}">
                {{ $objective->proposal ?? 'No Proposal Added'}}
            </p>
          </div>

          <!-- Approver Remarks -->
          <div>
            <h4 class="remark-caption">Approver Remarks</h4>
            <p class="{{ $objective->higherremarks ? 'remark-content-paragraph' : 'no-remark-content-paragraph'}}">
                {{ $objective->higherremarks ?? 'No Remarks Added'}}
            </p>
          </div>

          <!-- Approver Proposal -->
          <div>
            <h4 class="remark-caption">Approver Proposal</h4>
            <p class="{{ $objective->higherproposal ? 'remark-content-paragraph' : 'no-remark-content-paragraph'}}">
                {{ $objective->higherproposal ?? 'No Proposal Added'}}
            </p>
          </div>

        </div>
      </section>
    </div>

</body>
</html>
