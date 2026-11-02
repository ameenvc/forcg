@extends('pages.datatables')
@section('css_before')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Outfit:wght@400;500;600;700&display=swap');

    /* Style Variables representing the original design */
    :root {
        --font-sans: "Inter";
        --font-display: "Outfit", sans-serif;
        --font-mono: "Inter";

        /* Color Palette */
        --bg-primary: #ffffff;
        --bg-secondary: #f1f5f9;
        --bg-header-gray: #F6F7F9;
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
    * {
    box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: var(--font-sans);
        background-color: var(--bg-primary);
        color: var(--text-dark);
        line-height: 1;
        /* padding: 12px; */
    }

    @media (min-width: 768px) {
        body {
            /* padding: 24px; */
        }
    }

    @media (min-width: 1024px) {
        body {
            /* padding: 32px; */
        }
    }

    /* Master Outer Container with solid royal blue framing */
    .app-container {
        max-width: 1200px;
        margin: 0 auto;
        background-color: var(--bg-primary);
        border-radius: 4px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    .app-container-inner {
        padding: 16px;
    }

    @media (min-width: 768px) {
        .app-container-inner {
            padding: 24px;
        }
    }

    @media (min-width: 1024px) {
        .app-container-inner {
            padding: 32px;
        }
    }

    /* Top Navbar Header */
    .app-header {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 16px;
        /* margin-bottom: 4px; */
        gap: 16px;
        background: var(--bg-header-gray);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    @media (min-width: 640px) {
        .app-header {
            flex-direction: row;
            align-items: center;
        }
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        align-self: flex-end;
    }

    @media (min-width: 640px) {
        .header-right {
            align-self: auto;
        }
    }

    /* Elegant Navy Button elements */
    .btn-navy {
        background-color: var(--navy-btn);
        color: #ffffff;
        font-family: var(--font-sans);
        font-size: 11px;
        font-weight: 600;
        /* text-transform: uppercase; */
        letter-spacing: 0.05em;
        padding: 8px 16px;
        border: none;
        border-radius: 2px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: opacity 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .btn-navy:hover {
        opacity: 0.9;
    }

    .app-title {
        font-family: var(--font-sans);
        color: var(--text-dark);
        font-weight: 600;
        font-size: 15px;
        /* text-transform: uppercase; */
        letter-spacing: 0.05em;
    }

    /* Profile Avatar circle styling */
    .user-avatar-circle {
        position: relative;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid #cbd5e1;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        flex-shrink: 0;
    }

    .user-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Dotted Blueprint Grid Section containers exactly like design image */
    .sheet-section {
        background-color: var(--bg-bright-gray);
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 32px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .sheet-section:hover {
        border-color: #005cc4;
    }

    .section-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        color:#000000;
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
        color: var(--text-dark);
        font-weight: 600;
        /* font-size: 16px; */
        font-size: 13px;
        letter-spacing: 0.03em;
        font-family: var(--font-sans);
    }

    /* SECTION 1: RESPONSIVE GRID LAYOUT SYSTEM */
    .overview-cards-container {
        display: block;
        margin: -6px;
    }


    /* Inside Card Item for Overview */
    .info-card {
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        padding: 12px;
        border-radius: 6px;
        display: inline-flex;
        justify-content: space-between;
        align-items: flex-start;
        margin: 6px;
        vertical-align: top;
    }

    .card-label {
        font-size: 12px;
        /* text-transform: uppercase; */
        color: #252945;
        font-weight: 600;
        letter-spacing: 0.05em;
        line-height: 1.5;
        margin-bottom: 4px;
    }

    .card-icon-value-wrapper {
        display: inline-block;
    }

    .card-icon {
        width: 30px;
        height: 30px;
    }

    .card-value {
        /* font-size: 16px; */
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        font-family: var(--font-sans);
        word-wrap: break-word;
        padding-left: 0.5rem;
    }

    .card-value-mono {
        font-family: var(--font-mono);
    }

    .overview-added-by {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }

    .added-by-title {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .added-by-value {
        font-size: 12px;
        color: var(--text-dark);
        font-weight: 600;
    }
    /* Styled icon markers inside cards */
    .badge-icon {
        color: #ffffff;
        padding: 6px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        flex-shrink: 0;
        margin-left: 12px;
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
        color: var(--text-dark);
        letter-spacing: 0.05em;
        margin-bottom: 12px;
    }

    .stepper-container {
        position: relative;
        padding: 24px 0;
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
        display: block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #CFCFCF;
        margin: 0;
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
        width: calc((100% - 48px) / 3);
        min-width: 15px;
    }

    .target-date-row {
        margin-top: 32px;
        padding-top: 16px;
        border-top: 1px dotted #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .target-text {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
    }

    .target-value {
        font-family: var(--font-mono);
        font-weight: 600;
        color: #334155;
        margin-left: 4px;
    }


    /* SECTION 3: DETAILS (BENTO LAYOUT SPLIT) */
    .details-split {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
    }

    @media (min-width: 1024px) {
        .details-split {
            grid-template-columns: 2fr 1fr;
        }
    }

    .details-left-pane {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .desc-block-title {
        /* font-size: 16px; */
        font-size: 12px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .desc-block-text {
        /* font-size: 14px; */
        font-size: 11px;
        font-weight: 400;
        color: #475569;
        line-height: 1.6;
    }

    /* Reviewer avatars line-up list */
    .reviewers-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .reviewer-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: var(--navy-btn);
        color: #ffffff;
        padding: 8px 24px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 500;
    }

    .reviewer-pill span {
        font-size: 11px;
        font-weight: 600;
    }

    .mini-avatar-thumb {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
    }

    .mini-avatar-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Downloadable files list */
    .attachments-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    @media (min-width: 640px) {
        .attachments-wrapper {
            flex-direction: row;
            flex-wrap: wrap;
        }
    }

    .attachment-pill-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: var(--pill-bg);
        /* border: 1px solid var(--pill-border); */
        color: var(--pill-text);
        font-size: 11px;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        transition: background-color 0.15s ease;
    }

    .attachment-pill-btn:hover {
        background-color: #bce0e2;
    }

    .attachment-pill-btn svg {
        flex-shrink: 0;
    }

    .no-attachment {
        color: var(--text-muted);
        font-size: 12px;
        font-style: italic;
    }
    /* Right-side metadata tags for Details */
    .details-right-pane {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .meta-block {
        display: flex;
        flex-direction: column;
    }

    .meta-block-title {
        /* font-size: 16px; */
        font-size: 12px;
        font-weight: 600;
        /* text-transform: uppercase; */
        letter-spacing: 0.05em;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .responsibility-user {
        margin-top: 5px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .multiple-avatar:not(:first-child) {
        margin-left: -2rem;
    }

    .medium-avatar-thumb {
        width: 48px;
        height: 48px;
        border-radius: 50%;
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
        /* font-size: 16px; */
        font-size: 12px;
        font-weight: 400;
        color: var(--text-dark);
        margin-bottom: 0;
    }

    .user-role-lbl {
        font-size: 10px;
        color: var(--text-muted);
    }

    /* Static Target Box and Frequency labels */
    .pill-neutral-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 11px;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        width: max-content;
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
        /* font-size: 14px; */
        font-size: 11px;
        font-weight: 600px;
    }

    .frequency-value {
        font-size: 11px;
        font-weight: 600px;
    }

    /* Approver Badge Tag representation */
    .approver-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: var(--navy-btn);
        color: #ffffff;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        width: max-content;
    }

    .approver-pill-badge span {
        font-size: 11px;
        font-weight: 600;
    }


    /* SECTION 4: HISTORIC TIMELINE AND STATUS HISTORY FLOW */
     .timeline-wrapper{
        display:flex;
        align-items:flex-start;
        gap:25px;
    }
    .timeline-left{
        width:40%;
    }
    .timeline-middle{
        width:40px;
        position:relative;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:space-between;
    }
    .timeline-line{
        position:absolute;
        top:2px;
        bottom:9px;
        width:2px;
        background:#bdbdbd;
        z-index:1;
        height:60%;
        margin-top:52px;
    }
    .timeline-dot{
        width:18px;
        height:18px;
        border-radius:50%;
        background:#d5d5d5;
        position:relative;
        z-index:2;
        margin:36px 0;
    }
    .timeline-right{
        flex:1;
    }
    .timeline-label{
        height:90px;
        display:flex;
        justify-content:flex-end;
        align-items:center;
        font-weight:600;
        /* font-size:14px; */
        font-size:11px;
        color: #000;
    }
    .timeline-user{
        height:90px;
        display:flex;
        align-items:center;
    }

    .timeline-avatar{
        width:40px;
        height:40px;
        border-radius:50%;
        margin-right:15px;
    }

    .timeline-name {
        /* font-size: 14px; */
        font-size: 11px;
        font-family: 'Inter';
        font-style: italic;
        font-weight: 500;
        color: #000;
    }

    .timeline-date {
        font-size: 10px;
        font-family: 'Inter';
        font-style: italic;
        font-weight: 500;
        color: #000;
    }


    /* SECTION 5: USER REMARKS / FEEDBACK REMARKS */
    .remarks-stack {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .remark-caption {
        /* font-size: 16px; */
        font-size: 12px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 6px;
    }

    .remark-content-paragraph {
        /* font-size: 14px; */
        font-size: 11px;
        font-weight: 400;
        color: #475569;
        line-height: 1.6;
        max-width: 850px;
    }

    .no-remark-content-paragraph {
        /* font-size: 14px; */
        font-size: 11px;
        font-weight: 400;
        color: var(--text-muted);
        line-height: 1.6;
        max-width: 850px;
        font-style: italic;
    }

    /* Print and Page Footer */
    .layout-footer-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 16px;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Printing Optimization Styles */
    @media print {
/* 1. Force the document to expand to its full height */
        html, body {
            background-color: #ffffff !important;
            color: #000000 !important;
            padding: 0 !important;
            margin: 0 !important;
            height: auto !important;
            overflow: visible !important;
        }

        /* 2. Target common layout wrappers from your extended layout file to prevent them from cropping content */
        /* You may need to add your specific layout wrapper class/id here if it's not one of these */
        #app, .wrapper, .main-panel, .content-wrapper, main {
            height: auto !important;
            overflow: visible !important;
            position: static !important;
        }

        /* 3. Reset your main container */
        .app-container {
            border: none !important;
            box-shadow: none !important;
            background-color: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: visible !important;
            height: auto !important;
            max-width: 100% !important;
            width: 100% !important;
        }


        /* 4. Hide interactive elements */
        /* .btn-navy, .header-right,  */
        .layout-footer-actions {
            display: none !important;
        }

        /* 5. Fix Section Breaks */
        .sheet-section {
            /* border: 1px solid #000000 !important; */
            margin-bottom: 20px !important;
            /* Allow sections to break across pages if they are too long.
               'avoid' causes massive layout issues on large blocks. */
            page-break-inside: auto !important;
            break-inside: auto !important;
        }

        /* Optional: Force grid layouts to stack sequentially in print to avoid truncation */
        .details-split {
            display: block !important;
        }

        .details-left-pane, .details-right-pane {
            margin-bottom: 20px !important;
        }

        /* 1. CRITICAL: Force the printer to render background colors, lines, and dots */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        /* 2. Restore the Details Split Layout (Left/Right Pane) */
        .details-split {
            display: grid !important;
            grid-template-columns: 2fr 1fr !important; /* Forces the 2-column layout */
            gap: 24px !important;
            align-items: start !important;
        }

        /* 3. Fix the Horizontal Status Stepper (prevent squishing/overlapping) */
        .horizontal-dot-line-container {
            width: 80% !important; /* Expand to full paper width */
            padding-bottom: 40px !important; /* Give absolute positioned text room to breathe */
            text-align: center !important;
        }

        .line {
            width: calc((100% - 60px) / 3) !important; /* Recalculate line width for paper */
        }

        /* 4. Fix Vertical Timeline & History breaks */
        .timeline-container {
            padding-top: 20px !important;
        }

        .timeline-item {
            page-break-inside: avoid !important; /* Stop individual rows from splitting across two pages */
            margin-bottom: 30px !important;
        }

        /* Ensure timeline lines don't disappear */
        .timeline-line {
            background-color: #374151 !important;
        }
    }

</style>
@endsection
@section('content')
    <div style="margin-top: 20px"></div>
    <div class="app-container">

        <!-- Top Navbar Header -->
        <header class="app-header">
            <div class="header-left">
                <button class="btn-navy" id="btn-back" onclick="javascript:history.back()">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Back</span>
                </button>
                <span class="app-title">Objective Module</span>
            </div>

            <div class="header-right">
                <!-- User Profile avatar -->
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
                <div class="user-avatar-circle" title="Objective Details">
                    @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'.$postedBy->profile_picture)))
                        <img src="{{ asset('/storage/profile/'.$postedBy->profile_picture) }}" data-toggle="tooltip" data-potision="top" title="{{$postedBy->name??''}}">
                    @else
                        <img src=" {{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$postedBy->name??''}}"/>
                    @endif
                </div>

                <button class="btn-navy" id="btn-print-top" onclick="window.print()">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span>Print</span>
                </button>

                <div class="dropdown">
                    <button type="button" class="btn btn-success btn-export dropdown-toggle"
                            id="dropdown-export" style="background: #252945; border-color:#252945; color: white; font-size: 12px"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-file-download mr-2"></i> Export
                    </button>
                    <div class="dropdown-menu font-size-sm" style="min-width: 5rem" aria-labelledby="dropdown-export">
                        <a class="dropdown-item" href="{{ route('user.objective.exportPdf', $objective->Oid)}}">PDF</a>
                    </div>
                </div>
            </div>
        </header>

        <div class="app-container-inner">
            <!-- SECTION 1: OVERVIEW BLOCK -->
            <section class="sheet-section" id="section-overview">
                <div class="section-head">
                    <img class="section-icon" src="{{asset('images/icons/EyeNew.png')}}" alt="">
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
                    ]
                @endphp
                <!-- CSS Responsive Grid System -->
                <div class="overview-cards-container">
                    @foreach ($overViews as $overview)
                        <div class="info-card" id="card-id">
                            <div>
                                <p class="card-label">{{ $overview['title'] }}</p>
                                <div class="card-icon-value-wrapper">
                                    <img class="card-icon" src="{{asset($overview['image_path'])}}" />
                                    <span class="card-value card-value-mono">{{ $overview['value'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="overview-added-by">
                        <span class="added-by-title">Added by:</span>&nbsp;
                        <span class="added-by-value">{{ $postedBy ? $postedBy->name : ($objective->new_postedby ?? ($objective->postedby ?? "Deleted User")) }}</span>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: STATUS & TIMELINE BLOCK -->
            <section class="sheet-section" id="section-status">
                <div class="section-head">
                    <img class="section-icon" src="{{asset('images/icons/EyeNew.png')}}" alt="">
                    <span class="section-title">Status & Timeline</span>
                </div>

                <div class="stepper-container">
                    <p class="timeline-title-sub">Process Stage</p>
                </div>
                <div style="display: flex; width: 100%; justify-content:center; alignItems: center">
                    <div class="horizontal-dot-line-container">
                        <div class="stage">
                            <div class="dot {{ $objective->status == 'Review Pending' ? 'bg-under-review' : ''}}"></div>
                            <span class="stage-label">{{ $objective->status == 'Review Pending' ? 'Under review' : ''}}</span>
                        </div>
                        <div class="line"></div>
                        <div class="stage">
                            <div class="dot {{ $objective->status == 'Rejected' ? 'bg-rejected' : ''}}"></div>
                            <span class="stage-label">{{ $objective->status == 'Rejected' ? 'Rejected' : ''}}</span>
                        </div>
                        <div class="line"></div>
                        <div class="stage">
                            <div class="dot {{ $objective->status == 'Reviewed' ? 'bg-reviewed' : ''}}"></div>
                            <span class="stage-label">{{ $objective->status == 'Reviewed' ? 'Reviewed' : ''}}</span>
                        </div>
                        <div class="line"></div>
                        <div class="stage">
                            <div class="dot {{ $objective->status == 'Approved' ? 'bg-approved' : ''}}"></div>
                            <span class="stage-label">{{ $objective->status == 'Approved' ? 'Approved' : ''}}</span>
                        </div>
                    </div>
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
                    <img class="section-icon" src="{{asset('images/icons/Details.png')}}" alt="">
                    <span class="section-title">Details</span>
                </div>

                <!-- Layout split pane using CSS Grid system -->
                <div class="details-split">
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
                    @endphp
                    <!-- Left Column Section -->
                    <div class="details-left-pane">
                        @foreach ($objectiveDetails as $objectiveDetail)
                            <div id="block-desc">
                                <h3 class="desc-block-title">{{ $objectiveDetail['title'] }}</h3>
                                <p class="desc-block-text">
                                    {{ $objectiveDetail['value'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <!-- Right Column Section: Responsibility, Target info -->
                    <div class="details-right-pane">
                        @php
                            $responsiblePersons = getUsersFromEmailsOrNames($objective->responsibility);
                        @endphp
                        <!-- Responsibility -->
                        <div class="meta-block" id="block-responsibility">
                            <h4 class="meta-block-title">Responsibility</h4>
                            <div class="responsibility-user">
                                @foreach ($responsiblePersons as $responsiblePerson)
                                    <div class="medium-avatar-thumb multiple-avatar">
                                        @if($responsiblePerson->profile_picture && File::exists(public_path('/storage/profile/'.$responsiblePerson->profile_picture)))
                                            <img src="{{ asset('/storage/profile/'.$responsiblePerson->profile_picture) }}" data-toggle="tooltip" data-potision="top" title="{{$responsiblePerson->name??''}}">
                                        @else
                                            <img src=" {{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$responsiblePerson->name??''}}"/>
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
                                <img class="small-icon" src="{{ asset('images/icons/TargetCount.png')}}"/>
                                <span class="target-progress-bar-value">{{$objective->otarget}} {{$objective->unit ?? ''}}</span>
                            </div>
                        </div>
                        <!-- Frequency of monitoring -->
                        <div class="meta-block" id="block-frequency">
                            <h4 class="meta-block-title">Frequency of monitoring</h4>
                            <div class="pill-neutral-badge">
                                <img class="small-icon" src="{{ asset('images/icons/CalendarNew.png')}}"/>
                                <span class="frequency-value">{{ $objective->frequency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Layout split pane using CSS Grid system -->
                <br>
                <div class="details-split">
                    <!-- Left Column Section -->
                    @php
                        $reviewers = getUsersFromUserIds($objective->reviewers) ?? [];
                    @endphp
                    @if ($reviewers)
                        <div class="details-left-pane">
                            <!-- Reviewers list -->
                            <div id="block-reviewers">
                                <h3 class="desc-block-title" style="margin-bottom: 12px;">Reviewers</h3>
                                <div class="reviewers-list">

                                    @foreach ($reviewers as $reviewer)
                                        <div class="reviewer-pill">
                                            <span class="mini-avatar-thumb">
                                                @if($reviewer->profile_picture && File::exists(public_path('/storage/profile/'.$reviewer->profile_picture)))
                                                    <img src="{{ asset('/storage/profile/'.$reviewer->profile_picture) }}" data-toggle="tooltip" data-potision="top" title="{{$reviewer->name??''}}">
                                                @else
                                                    <img src=" {{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$reviewer->name??''}}"/>
                                                @endif
                                            </span>
                                            <span>{{ $reviewer->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Right Column Section: Approver info -->
                    @if ($objective->approverDetails)
                        <div class="details-right-pane">
                            <!-- Approver Tag -->
                            <div class="meta-block" id="block-approver">
                                <h4 class="meta-block-title">Approver</h4>
                                <div class="approver-pill-badge">
                                    <span class="mini-avatar-thumb" style="border: 1px solid #ffffff; margin-right: 4px;">
                                        @if($objective->approverDetails && $objective->approverDetails->profile_picture && File::exists(public_path('/storage/profile/'.$objective->approverDetails->profile_picture)))
                                            <img src="{{ asset('/storage/profile/'.$objective->approverDetails->profile_picture) }}" data-toggle="tooltip" data-potision="top" title="{{$objective->approverDetails->name??''}}">
                                        @else
                                            <img src=" {{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$objective->approverDetails->name??''}}"/>
                                        @endif
                                    </span>
                                    <span>{{$objective->approverDetails ?  $objective->approverDetails->name : ''}}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Attachments panel -->
                    <div id="block-attachments">
                        <br>
                        <h3 class="desc-block-title" style="margin-bottom: 12px;">Attachments</h3>
                        <div class="attachments-wrapper">
                            @php
                                $files = $objective->files ? explode(',', $objective->files) : [];
                            @endphp
                            @if ($files)
                                @foreach ($files as $file)
                                    <a class="attachment-pill-btn" href="{{asset('storage/objectives_files/'.trim($file))}}" download="{{trim($file)}}">
                                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        <span>{{preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $file)}}</span>
                                    </a>
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
                    <img class="section-icon" src="{{asset('images/icons/TimelineAndHistory.png')}}"/></i> <span class="section-title">Timeline & History</span>
                </div>
                <div class="timeline-wrapper">
                            <div class="timeline-left">
                                <div class="timeline-label">Prepared By & Date</div>
                                <div class="timeline-label">Last Reviewed By & Date</div>
                                <div class="timeline-label">Approved By & Date</div>
                            </div>
                            <div class="timeline-middle">
                                <div class="timeline-line"></div>
                                <span class="timeline-dot"></span>
                                <span class="timeline-dot"></span>
                                <span class="timeline-dot"></span>
                            </div>
                            <div class="timeline-right">
                                <!-- First Person -->
                                <div class="timeline-user">
                                    @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'.$postedBy->profile_picture)))
                                        <img class="timeline-avatar"
                                            src="{{ asset('/storage/profile/'.$postedBy->profile_picture) }}">
                                    @else
                                        <img class="timeline-avatar"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    @endif
                                    <div>
                                        <div class="timeline-name">
                                            {{ $postedBy->name ?? 'Not Specified' }}
                                        </div>

                                        <div class="timeline-date">
                                            on {{ $objective->created_at ? date('d-m-Y', strtotime($objective->created_at)) : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Second -->
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
                                <div class="timeline-user">
                                    @if(in_array($objective->status, ['Reviewed', 'Approved']) && $reviewedBy && $reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$reviewedBy->profile_picture)))
                                        <img class="timeline-avatar"
                                            src="{{ asset('/storage/profile/'.$reviewedBy->profile_picture) }}">
                                    @else
                                        <img class="timeline-avatar"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    @endif
                                    <div>
                                        <div class="timeline-name">
                                            {{ in_array($objective->status, ['Reviewed', 'Approved']) && $reviewedBy && $reviewedBy->name ? $reviewedBy->name : 'Pending Approval' }}
                                        </div>

                                        <div class="timeline-date">
                                            {{ in_array($objective->status, ['Reviewed', 'Approved']) && $reviewedBy && $objective->reviewed_at ? date('d-m-Y', strtotime($objective->reviewed_at)) : 'DD-MM-YYYY' }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Third -->
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
                                <div class="timeline-user">
                                    @if($objective->status == 'Approved' && $approvedBy && $approvedBy->profile_picture && File::exists(public_path('/storage/profile/'.$approvedBy->profile_picture)))
                                        <img class="timeline-avatar"
                                            src="{{ asset('/storage/profile/'.$approvedBy->profile_picture) }}">
                                    @else
                                        <img class="timeline-avatar"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    @endif
                                    <div>
                                        <div class="timeline-name">{{ $objective->status == 'Approved' && $approvedBy && $approvedBy->name ? $approvedBy->name : 'Pending Closure' }}</div>
                                        <div class="timeline-date">
                                            on {{$objective->status == 'Approved' && $objective->approved_at ? date('d-m-Y', strtotime($objective->approved_at)) : 'DD-MM-YYYY'}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
            </section>

            <!-- SECTION 5: USER INTERACTIONS BLOCK -->
            <section class="sheet-section" id="section-remarks">
                <div class="section-head">
                    <img class="section-icon" src="{{asset('images/icons/UserInteractions.png')}}" alt="">
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

            <!-- Footer shortcut -->
            <div class="layout-footer-actions">
                <button class="btn-navy" id="btn-print-bottom" onclick="window.print()">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    <span>Print</span>
                </button>
            </div>
        </div>
    </div>
    <div style="margin-bottom: 20px"></div>
@endsection
