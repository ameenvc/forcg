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
    .process-stage-point span.span-awaiting-action::after {
        content: 'Approved';
        margin-left: 2vw;
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
        content: 'Under Review';
    }
    .process-stage-point span.span-close-action:after {
        content: 'Closed';
        left: -5px;
    }
    .process-stage-point:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 35%;
        left: 18%;
        width: 100%;
        height: 2px;
        background: #000000;
    }
    .bg-warning {
        background-color: #af6f1e !important;
    }
    .bg-success {
        background-color: #176044 !important;
    }
    .bg-danger {
        background-color: #af1e1e !important;
    }
    .bg-black {
        background-color:#000
    }
    .bg-amethylist-light {
        background-color: #6f42c1 !important;
    }
    .bg-primary {
        background-color: #25499c !important;
    }
    .bg-secondary {
        background-color: #737373 !important;
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
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        display: flex;
        /* width:50%; */
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
    button[data-target="#one-modal-apps"] {
        display: none;
    }
    /* Mobile */
    @media only screen and (max-width: 400px) {
        html {
            font-size: 14px;
        }
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
        .process-stage {
            margin-left: 0 !important;
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
        html {
            font-size: 14px;
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
        .process-stage {
            margin-left: 0 !important;
        }
        .process-stage .process-stage-point span {
            margin-right: 3.5vw !important;
        }
        .process-stage-point:not(:last-child)::after {
            left: 2.5vw !important;
        }
        .process-stage-point span.span-entry-under-review-action::after {
            content: 'Under Review';
            margin-left: 2vw;
        }

    }
    @media only screen and (min-width: 643px) and (max-width: 767px) {
        html {
            font-size: 15px;
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
        .process-stage {
            margin-left: 0 !important;
        }
        .process-stage .process-stage-point span {
            margin-right: 4.75vw !important;
        }
        .process-stage-point:not(:last-child)::after {
            left: 2vw !important;
        }
        .process-stage-point span.span-entry-under-review-action::after {
            content: 'Under Review';
            margin-left: 2vw;
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
            margin-right: 1.25rem !important;
        }
        .process-stage-point:not(:last-child)::after {
            left: 40%;
        }
        .process-stage-point span.span-entry-under-review-action::after {
            content: 'Under Review';
            margin-left: 2vw;
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
            margin-right: 1.5rem !important;
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
        font-size: 12px;
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
    @media print {
        .table-custom thead tr th {
            background-color: #252945 !important;
        }
        * {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }
    .table thead th {
        vertical-align: middle;
    }
    .product {
        color: #25499c;
        background-color: #d9e2f6;
        border-color: #d9e2f6;
        display: inline-block;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        border-radius: 5px;
    }
    .status-blue1 {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        word-break: break-word;
        font-weight: 600;
        border-radius: 8px;
        padding: 2px 0px;
    }
    .text-muted {
        color: #666 !important;
    }
    .rich-text,
    .rich-text * {
        word-break: break-all !important;
        overflow-wrap: anywhere !important;
        white-space: normal !important;
        font-size: 12px;
    }
    .break-text {
        word-break: normal;
        overflow-wrap: break-word;
        white-space: normal;
    }
    .break-text-table {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }
    .section-heading {
        font-size: 13px;
    }
    .inner-heading {
        font-size: 12px;
    }
    .inner-content {
        font-size: 12px;
        font-weight: 400;
    }
</style>

<!-- Page Content -->
<div class="content px-0" style="-webkit-print-color-adjust: exact">
    <div class="content px-0" style="-webkit-print-color-adjust: exact">
        <div class="alert alert-success w-100 d-none mt-2" id="success_action" style="font-size:13px;"></div>
    <ul class="alert alert-danger w-100 d-none mt-2" id="error_action" style="font-size:13px;">
        Internal server error, we are trying to fix them soon
    </ul>
    <div class="alert alert-success d-none w-100 mt-2" id="success_issue" style="font-size:12px;"></div>
    <ul class="alert alert-danger d-none w-100 mt-2" id="error_issue" style="font-size:12px;">
        Internal server error, we are trying to fix them soon
    </ul>
    <div class="alert alert-success d-none w-100 mt-2" id="success_complaint" style="font-size:12px;"></div>
    <ul class="alert alert-danger d-none w-100 mt-2" id="error_complaint" style="font-size:12px;">
        Internal server error, we are trying to fix them soon
    </ul>
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">

        <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
            <div class="d-flex d-flex-left p-2 align-items-center">
                <a href="javascript:history.back()" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <span class="mr-2">Customer Complaint Details</span>
                <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">{{ $complaint->title }}</span>
            </div>
            <div class="d-flex-right p-2 d-flex d-block">
                <div class="d-flex mr-4 border-right">
                    @php
                        $resp_user_image = DB::table('users')->where('email',$complaint->responsibility)->value('profile_picture');
                        $resp_user_name = DB::table('users')->where('email',$complaint->responsibility)->value('name');
                        $posted_user_image = DB::table('users')->where('employeeid',$complaint->addedby)->value('profile_picture');
                        $posted_user_name = DB::table('users')->where('employeeid',$complaint->addedby)->value('name');
                        $activeComplaintTemplateId = $complaint->complaint_template_id;
                        $complaintTemplateSectionFields = json_decode($complaint->complaint_template_section_field_rules, true);

                    @endphp
                    @if($resp_user_image && File::exists(public_path('/storage/profile/'.$resp_user_image)))
                        <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$resp_user_image) }}" data-toggle="tooltip" data-potision="top" title="{{$resp_user_name??''}}">
                    @else
                        <img class="img-avatar img-avatar48 mr-3" src=" {{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$resp_user_name??''}}"/>
                    @endif

                    @if($posted_user_image && File::exists(public_path('/storage/profile/'.$posted_user_image)))
                        <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$posted_user_image) }}" data-toggle="tooltip" data-potision="top" title="{{$posted_user_name??''}}">
                    @else
                        <img class="img-avatar img-avatar48 mr-3" src=" {{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-potision="top" title="{{$posted_user_name??''}}"/>
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
                            <a class="dropdown-item" href="{{ route('complaint.exportPdf', $complaint->id) }}">PDF</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 px-5 d-flex px-xs-adjust">
            <div style="font-size: 0.75rem; background: #edf0f2; border-radius: 20px" class="col-md-12">
                <label class="mt-2 ml-2 section-heading"><i class="fa fa-eye mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Overview</label>
                <div class="row mb-md-1">
                    @php
                        $overviewFields = [];
                        $field = [
                            'title' => 'Complaint ID',
                            'value' => $complaint->trackno,
                            'image_path' => 'images/icons/trackingno.png'
                        ];
                        array_push($overviewFields, $field);
                        if (isset($complaint->sale_order_no) && $complaint->sale_order_no) {
                            $field = [
                                'title' => $activeComplaintTemplateId == 1 ?  $complaintTemplateSectionFields['saleorder']['field_display_label'] : 'Sale Order Number',
                                'value' => $complaint->sale_order_no,
                                'image_path' => 'images/icons/saleordernumbermintgreen.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->delivery_no) && $complaint->delivery_no) {
                            $field = [
                                'title' => $activeComplaintTemplateId == 1 ?  $complaintTemplateSectionFields['deliveryno']['field_display_label'] : 'Delivery Number',
                                'value' => $complaint->delivery_no,
                                'image_path' => 'images/icons/deliveryno.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->delivery_date) && $complaint->delivery_date) {
                            $field = [
                                'title' => $activeComplaintTemplateId == 1 ?  $complaintTemplateSectionFields['deliverydate']['field_display_label'] : 'Delivery Date',
                                'value' => $complaint->delivery_date,
                                'image_path' => 'images/icons/deliverydate2.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->job_order_no) && $complaint->job_order_no) {
                            $field = [
                                'title' => $complaintTemplateSectionFields['job_order_no']['field_display_label'],
                                'value' => $complaint->job_order_no,
                                'image_path' => 'images/icons/jobordernumber.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->product_serial_no) && $complaint->product_serial_no) {
                            $field = [
                                'title' => $complaintTemplateSectionFields['product_serial_no']['field_display_label'],
                                'value' => $complaint->product_serial_no,
                                'image_path' => 'images/icons/productserialnumber.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->project_name) && $complaint->project_name) {
                            $field = [
                                'title' => $complaintTemplateSectionFields['project_name']['field_display_label'],
                                'value' => $complaint->project_name,
                                'image_path' => 'images/icons/projectname2.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->warranty_coverage) && $complaint->warranty_coverage) {
                            if ($complaint->warranty_coverage == 'Under Warranty') {
                                $image_path = 'images/icons/underwarranty.png';
                            } else {
                                $image_path = 'images/icons/outofwarranty2.png';
                            }
                            $field = [
                                'title' => 'Warranty Coverage',
                                'value' => $complaint->warranty_coverage,
                                'image_path' => $image_path
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->type) && $complaint->type) {
                            $image_path = '';
                            switch ($complaint->type) {
                                case 'High':
                                    $image_path = 'images/icons/typehigh.png';
                                    break;

                                case 'Moderate':
                                    $image_path = 'images/icons/typemoderate.png';
                                    break;

                                case 'Low':
                                    $image_path = 'images/icons/typelow.png';
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                            $field = [
                                'title' => 'Type',
                                'value' => $complaint->type,
                                'image_path' => $image_path
                            ];
                            array_push($overviewFields, $field);
                        }
                        if ($plant) {
                            $field = [
                                'title' => 'Plant',
                                'value' => $plant->plant_name,
                                'image_path' => 'images/icons/planticon2.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if ($activeComplaintTemplateId == 2 && isset($projectComplaint)) {
                            $field = [
                                'title' => 'Complaint Category',
                                'value' => $projectComplaint->category_name,
                                'image_path' => 'images/icons/category.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if (isset($complaint->vendor_id) && $complaint->vendor_id && $vendor) {
                            $field = [
                                'title' => 'Complaint Category',
                                'value' => $vendor->vendor_name,
                                'image_path' => 'images/icons/vendororange.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        $field = [
                            'title' => 'Department',
                            'value' => $department->departmentname,
                            'image_path' => 'images/icons/departmentbrown.png'
                        ];
                        array_push($overviewFields, $field);
                        if ($division) {
                            $field = [
                                'title' => 'Division',
                                'value' => $division->name,
                                'image_path' => 'images/icons/subdivisionbrown.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                        if ($area) {
                            $field = [
                                'title' => 'Sub-Division',
                                'value' => $area->name,
                                'image_path' => 'images/icons/subdivisionbrown.png'
                            ];
                            array_push($overviewFields, $field);
                        }
                    @endphp
                    @foreach ($overviewFields as $overviewField)
                        <div class="col-lg-3 col-sm-6 col-md-4 mb-0 pb-0">
                            <div class="block block-rounded d-flex flex-column">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                    <dl class="mb-0">
                                        <dt class="fw-bold mb-2 inner-heading">{{ $overviewField['title'] }}</dt>
                                        <dd class="text-dark mb-0">
                                            <img src="{{ asset($overviewField['image_path']) }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="inner-content">{{$overviewField['value']??"--"}}</span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @php
            $complaint->status = $complaint->getOriginal('status');
        @endphp
        <div class="mt-4 px-5 d-flex px-xs-adjust">
            <div class="px-4" style="background: #edf0f2; border-radius: 20px; font-size: 0.75rem;width:100%;">
                <label class="mt-2 ml-2 section-heading"><i class="fa fa-eye mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Status & Timeline</label>
                <div class="mt-2">
                    <label class="inner-heading">Process Stage</label>
                    <div class="d-flex mt-3 ml-5 process-stage">
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-entry-under-review-action @if($complaint->status != 'Under Review') hide-action @else bg-secondary @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-rejected @if($complaint->status != 'Rejected') hide-action @else bg-danger @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-awaiting-action @if($complaint->status != 'Approved') hide-action @else bg-amethylist-light @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-correction-proposed-rc-awaiting-action @if($complaint->status != 'Correction Proposed- RC Awaiting') hide-action @else bg-blue @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-rc-update-correction-awaiting-action @if($complaint->status != 'RC Updated- Correction Awaiting') hide-action @else bg-blue @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-proposed-review-awaiting-action @if($complaint->status != 'Actions Proposed- Review Awaiting') hide-action @else bg-blue @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-action-reviewed-not-ok @if($complaint->status != 'Action Reviewed, Not OK') hide-action @else bg-danger @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-action-reviewed-ok @if($complaint->status != 'Action Reviewed, OK') hide-action @else bg-success @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-action-completed @if($complaint->status != 'Actions Completed') hide-action @else bg-primary @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-need-more-action @if($complaint->status != 'Need More Action') hide-action @else bg-warning @endif"></span>
                        </label>
                        <label class="process-stage-point">
                            <span class="rounded-circle mr-6 d-inline-block p-2 span-close-action @if($complaint->status != 'Closed') hide-action @else bg-black @endif"></span>
                        </label>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-between row">
                    <div class="col-sm-12 col-md-6">
                        <label class="inner-heading">Target Date: {{ $complaint->tardate ? date('d-m-Y', strtotime($complaint->tardate)) : '--' }}</label>
                    </div>
                    <div class="col-sm-12 col-md-6 text-right">
                        <label class="inner-heading">Target Date(Investigation): {{ $complaint->investigation_date ? date('d-m-Y', strtotime($complaint->investigation_date)) : '--' }}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 px-5 d-flex px-xs-adjust">
            <div style="font-size: 0.75rem; background: #edf0f2; border-radius: 20px" class="col-md-12">
                <label class="mt-3 ml-4 section-heading"><i class="fa fa-clipboard-list mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Details</label>

                <div class="mt-4 px-1 d-flex d-block-sp px-adjust">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="row mb-ml-1" >
                            <!-- start -->
                            <div class="col-lg-4 col-sm-6 col-md-6 mb-0 pr-0 pb-0">
                                <!-- Pending Orders -->
                                <div class="block block-rounded d-flex flex-column">
                                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                        <dl class="mb-0">
                                            <dt class="fw-bold mb-2 inner-heading">Mode of Complaint</dt>
                                            @if($complaint->mode_of_complaint=="in-person visits to customer service center")
                                            <dd class="text-dark font-weight-bold mb-0 row" style="font-size:13px;">
                                                <div class="col-sm-2"><img src="{{ asset('images/icons/modeofcomplaint2.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;"></div>&nbsp;<div class="col-sm-9 inner-content">{{$complaint->mode_of_complaint?ucfirst($complaint->mode_of_complaint):"--"}}</div>
                                            </dd>
                                            @else
                                            <dd class="text-dark font-weight-bold mb-0" style="font-size:13px;">
                                                <img src="{{ asset('images/icons/modeofcomplaint2.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;
                                                @if($complaint->mode_of_complaint)
                                                    <span class="inner-content">{{ucfirst($complaint->mode_of_complaint)}}</span>
                                                @else
                                                    <span class="text-muted inner-content">Not specified</span>
                                                @endif
                                            </dd>
                                            @endif
                                        </dl>
                                    </div>
                                </div>
                                <!-- END Pending Orders -->
                            </div>
                             <!-- start -->
                            <div class="col-lg-4 col-sm-6 col-md-6 mb-0 pr-0 pb-0">
                                <!-- Pending Orders -->
                                <div class="block block-rounded d-flex flex-column">
                                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                        <dl class="mb-0">
                                            <dt class="fw-bold mb-2 inner-heading">Complaint Recieved By</dt>
                                            <dd class="text-dark font-weight-bold mb-0" style="font-size:13px;">
                                                <img src="{{ asset('images/icons/complaintreceivedby.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;
                                                @if ($complaint->employee)
                                                    <span class="inner-content">{{App\EmployeeDetails::where('emp_number',$complaint->employee)->value('emp_name')}}</span>
                                                @else
                                                    <span class="text-muted inner-content">Not specified</span>
                                                @endif

                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <!-- END Pending Orders -->
                            </div>
                            <!-- start -->
                            <div class="col-lg-4 col-sm-6 col-md-6 mb-0 pr-0 pb-0">
                                <!-- Pending Orders -->
                                <div class="block block-rounded d-flex flex-column">
                                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center p-2">
                                        <dl class="mb-0">
                                            <dt class="fw-bold mb-2 inner-heading">Complaint Received On</dt>
                                            <dd class="text-dark font-weight-bold mb-0" style="font-size:13px;">
                                                <img src="{{ asset('images/icons/complaintreceiveddate.png') }}" class="mr-1" style="width: 30px;height: 30px;border-radius: 5px;">&nbsp;&nbsp;<span class="inner-content">{{$complaint->crdate?date('d-m-Y',strtotime($complaint->crdate)):"DD-MM-YYYY"}}</span>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                                <!-- END Pending Orders -->
                            </div>
                        </div>
                        @if ($activeComplaintTemplateId == 2 && $complaintTemplateSectionFields['job_history']['enable_field'])
                            <div class="mb-5">
                                <label class="inner-heading">{{$complaintTemplateSectionFields['job_history']['field_display_label']}}</label>
                                <div class="d-block-sp bg-white p-2 " style="border-radius: 10px">
                                    <div class="pt-2 pb-2">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="text-justify m-0 rich-text">{!! $complaint->job_history??"--" !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex d-block-sp row mb-md-1">
                            <div class="col-md-12">
                                <label class="inner-heading">{{ $complaint->title }}</label>
                                {{-- <p class="text-justify">{!! nl2br(strip_tags($complaint->complaint)) !!}</p> --}}
                                <div class="text-justify rich-text ">{!! $complaint->complaint !!}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 justify-content-center d-block-sp">
                        <label class="inner-heading">Customer Details</label>
                        <div class="row">
                            <div class="col-sm-12">
                                @if ($complaint->customer)
                                    <label style="font-weight: bold;font-size:12px;" for="Customer Name">{{$customer->customer??$complaint->customer}}</label><br>
                                @endif
                                @if ($complaint->customer_email)
                                    <label class="inner-content" style="font-weight:400;" for="Customer Name">{{$complaint->customer_email}}</label><br>
                                @endif
                                @if ($phonenumber)
                                    <label class="inner-content" style="font-weight:400;" for="Customer Name">+{{$countryPhoneCode}}-{{$phonenumber??"00000-00000"}}</label><br>
                                @endif
                                @if ($customeraddress || $stateName || $countryName)
                                    <label class="inner-content" style="font-weight:400;" for="Customer Name">{{$customeraddress? $customeraddress.', ':""}} {{$stateName ? $stateName.", ":""}} {{$countryName??""}}</label><br>
                                @endif
                                @for($p=0;$p<count($productNames);$p++)
                                        <div class="product mt-1" style="font-size:11px;">{{$productNames[$p]}}</div>
                                @endfor
                            </div>
                        </div>
                        <br>

                        @if (isset($complaint->previously_occured_complaint) && $complaint->previously_occured_complaint == 'yes')
                            <label class="inner-heading">Issue History</label>
                            <div class="row">
                                <div class="col-sm-12 block block-rounded py-3">
                                    @if (isset($complaint->no_of_occurrence) && $complaint->no_of_occurrence)
                                        <label style="font-size:12px; color: #666;" for="no of occurence"><span style="background-color: #2529453D; padding: 5px; font-size: 10px; border-radius: 5px; margin-right: 5px"><i class="fa fa-sync" ></i></span>This complaint has recurred <span style="font-weight: bold; color: #000">{{$complaint->no_of_occurrence}} times</span></label><br>
                                    @else
                                        <label style="font-weight: bold; font-size:12px;"><span style="background-color: #2529453D; padding: 5px; font-size: 10px; border-radius: 5px; margin-right: 5px"><i class="fa fa-sync"></i></span>This complaint has been previously recorded.</label><br>
                                    @endif
                                    @if (isset($complaint->previous_complaint_reference))
                                        <div style="height: 8px"></div> <!-- vertical spacing -->
                                        @if ($complaint->previous_complaint_reference == '0')
                                            <label style="display: block; ">
                                                <span style="vertical-align: middle;" class="pr-1">External Reference:</span>
                                                @if(isset($complaint->external_reference) && $complaint->external_reference)
                                                    <span style="vertical-align: middle;" class="status-blue status-blue1 mr-3 px-2">
                                                        <span style="word-break: break-all; overflow-wrap: break-word;">{{$complaint->external_reference}}</span>
                                                    </span>
                                                @else
                                                    <span style="vertical-align: middle; color: #666" >No details Provided</span>
                                                @endif
                                            </label>
                                        @else
                                            @if ($complaint->previousComplaintRef)
                                                @if ($complaint->previousComplaintRef['deleted_at'])
                                                    <a href="{{ route('complaint.detail', ['id' => $complaint->previous_complaint_reference])}}"><span class="status-blue status-blue1 mr-3 px-2" class="tooltipClass" data-toggle="tooltip" data-placement="top" title="Click to view previous Customer Complaint">{{ $complaint->previousComplaintRef['trackno'].' - '.$complaint->previousComplaintRef['title'].' (deleted complaint)'}}</span></a>
                                                @else
                                                    <a href="{{ route('complaint.detail', ['id' => $complaint->previous_complaint_reference])}}"><span class="status-blue status-blue1 mr-3 px-2 br-5" class="tooltipClass" data-toggle="tooltip" data-placement="top" title="Click to view previous Customer Complaint">{{ $complaint->previousComplaintRef['trackno'].' - '.$complaint->previousComplaintRef['title']}}</span></a>
                                                @endif
                                            @else
                                                <span class="status-blue status-blue1 mr-3 px-2" class="tooltipClass">{{$complaint->previous_complaint_reference.'(deleted complaint)'}}</span>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4 px-1 d-flex d-block-sp px-adjust">
                    <div class="observation col-md-8">
                        <label class="inner-heading">Recommended Action</label>
                        @if (!empty($complaint->action))
                            {{-- <p class="text-justify">{!! nl2br(strip_tags($complaint->action)) !!}</p> --}}
                            <div class="text-justify rich-text">{!! $complaint->action !!}</div>
                        @else
                            <p class="text-justify text-muted">No details provided</p>
                        @endif
                    </div>
                    <div class="col-md-4 justify-content-center d-block-sp">
                        <label class="inner-heading">Financial Cost & Potential Cost Savings</label>
                        <div class="mb-1 @if($complaint->financial_loss) col-lg-10 col-md-10 @else col-lg-10 col-md-10 @endif px-3 py-2" style="background: #AF1E1E3D; border-radius: 10px;">
                            <img src="{{ asset('images/icons/financial_icon.png') }}"
                                    class="mr-1"
                                    style="background: #AF1E1E;width: 24px;height: 24px;padding: 5px;border-radius: 5px;">
                            <span class="font-weight-bold" style="color: #AF1E1E">
                                @if($complaint->financial_loss)
                                    {{ $complaint->financial_loss }}
                                @else
                                    <span class="text-muted">No financial details provided</span>
                                @endif
                            </span>
                        </div>
                        <div class="@if($complaint->potential_cost_savings) col-lg-10 col-md-10 @else col-lg-10 col-md-10 @endif px-3 py-2" style="background: #1760443D; border-radius: 10px;">
                            <img src="{{ asset('images/icons/money.png') }}"
                                    class="mr-1"
                                    style="width: 24px;height: 24px;border-radius: 5px;">
                            <span class="font-weight-bold" style="color: #176044">
                                @if($complaint->potential_cost_savings)
                                    {{ $complaint->potential_cost_savings }}
                                @else
                                    <span class="text-muted">No cost savings recorded</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @if ($activeComplaintTemplateId == null)
                    <div class="mt-5 px-3 px-adjust">
                        <label class="ml-3 inner-heading">Products</label>
                        <div class="mt-2 d-block-sp correction-block-adjust" style="border-radius: 10px">
                            <div>
                                <div class="mt-2 mx-3 root-cause-table-adjust" style="overflow-x:auto;">
                                    <table class="table table-bordered table-custom table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;border-right:1px gray solid;">ID No.</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Item Code</th>
                                                <th style="width: 15%;border-right:1px gray solid;">Product Description</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Size of the Item</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Unit</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Total Delivery Quantity</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Affected Quantity</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Cost Impact</th>
                                                <th style="width: 13%;border-right:1px gray solid;">Complaint Category</th>
                                                <th style="width: 9%;border-right:1px gray solid;">Sale Return Request</th>
                                                <th style="width: 9%;">Rework Request</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for($i=0;$i<count($records);$i++){
                                            ?>
                                            <tr>
                                                <td>
                                                    {{$records[$i]->idno??"-"}}
                                                </td>
                                                <td>
                                                    {{$records[$i]->item_code??"-"}}
                                                </td>
                                                <td class="break-text-table">{{$records[$i]->product_description??"-"}}</td>
                                                <td>
                                                    {{$records[$i]->size_of_item??"-"}}
                                                </td>
                                                @php
                                                    $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$records[$i]->unit)->first();
                                                @endphp
                                                <td>
                                                    {{$oldunitforoldrecords ? $oldunitforoldrecords->name : ($records[$i]->unit ?? '—' )}}
                                                </td>
                                                <td>
                                                    {{$records[$i]->total_delivery_quantity??"-"}}
                                                </td>
                                                <td>
                                                    {{$records[$i]->affected_quantity??"-"}}
                                                </td>
                                                <td>
                                                    {{$records[$i]->cost_impact??"-"}}
                                                </td>
                                                @php $catname = DB::table('complaint_categories')->whereIn('id',explode(',',$records[$i]->complaint_category))->pluck('category_name')->toArray() @endphp
                                                <td>
                                                    {{implode(',',$catname)}}
                                                </td>
                                                <td>
                                                    {{$records[$i]->sale_return_request??"-"}}

                                                </td>
                                                <td>
                                                    {{$records[$i]->rework_request??"-"}}
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($activeComplaintTemplateId == 1)
                    <div class="mt-5 px-3 px-adjust">
                        <label class="ml-3 inner-heading">Products</label>
                        <div class="mt-2 d-block-sp correction-block-adjust" style="border-radius: 10px">
                            <div>
                                <div class="mt-2 mx-3 root-cause-table-adjust" style="overflow-x:auto;">
                                    <table class="table table-bordered table-custom table-striped">
                                        <thead>
                                            <tr>
                                                @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                                    <th style="width: 5%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['idno']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['itemcode']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                                    <th style="width: 15%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['productdesc']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['sizeofitem']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['unit']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['totalquant']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['affectquant']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['costimpact']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                                    <th style="width: 13%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['complaintcategory']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                                    <th style="width: 9%;border-right:1px gray solid;">{{$complaintTemplateSectionFields['salereturnreq']['field_display_label']}}</th>
                                                @endif
                                                @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                                    <th style="width: 9%;">{{$complaintTemplateSectionFields['reworkreq']['field_display_label']}}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for($i=0;$i<count($records);$i++){
                                            ?>
                                            <tr>
                                                @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->idno??"—"}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->item_code??"—"}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                                    <td class="break-text-table">{{$records[$i]->product_description??"-"}}</td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->size_of_item??"—"}}
                                                    </td>
                                                @endif
                                                @php
                                                    $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$records[$i]->unit)->first();
                                                @endphp
                                                @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                                    <td>
                                                        {{$oldunitforoldrecords ? $oldunitforoldrecords->name : ($records[$i]->unit ?? '—')}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->total_delivery_quantity??"—"}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->affected_quantity??"—"}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->cost_impact??"—"}}
                                                    </td>
                                                @endif
                                                @php $catname = DB::table('complaint_categories')->whereIn('id',explode(',',$records[$i]->complaint_category))->pluck('category_name')->toArray() @endphp
                                                @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                                    <td>
                                                        {{$catname ? implode(',',$catname) : '—'}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->sale_return_request??"—"}}
                                                    </td>
                                                @endif
                                                @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                                    <td>
                                                        {{$records[$i]->rework_request??"—"}}
                                                    </td>
                                                @endif
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-5 px-3 px-adjust">
                    <label class="ml-3 inner-heading">Correction & Root Cause</label>
                    <div class="ml-3 mr-5 mt-2 d-block-sp bg-white p-2 correction-block-adjust" style="border-radius: 10px">
                        <div class="d-flex mb-3 d-block-sp">
                            <div class="observation col-md-7">
                                <label>Correction</label>
                                @if($complaint->correction)
                                    <p class="text-justify break-text">{{ $complaint->correction }}</p>
                                @else
                                    <p class="text-muted">No corrections added</p>
                                @endif
                            </div>
                            @php
                                $resp = App\User::where('email', $complaint->responsibility)->first();
                                $dept = App\Departments::where('id',$complaint->dept)->first();
                            @endphp
                            <div class="col-md-5 justify-content-center d-block-sp">
                                <div>
                                    <label>Responsibility</label><br>
                                    <div class="align-items-center">
                                        @if($resp && File::exists(public_path('/storage/profile/'.$resp->profile_picture)))
                                            <img class="img-avatar multiple-avatar img-avatar48" alt="{{ $resp->name }}"
                                                    src="{{ asset('/storage/profile/'.$resp->profile_picture) }}" data-toggle="tooltip" data-position="top" title='{{$resp->name??""}}'>
                                        @else
                                            <img class="img-avatar multiple-avatar
                                        img-avatar48" alt="No picture"
                                                    src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title='{{$resp->name??""}}'/>
                                        @endif
                                        <p class="font-weight-bold mb-2 mt-2">@if($resp)<span>{{$resp->name}}</span> @else<span class="text-muted">Responsibility not assigned yet</span>@endif</p>
                                        <div class="d-flex responsibility-dep-date-adjust">
                                            @if($dept)
                                                <div class="mr-2 p-2" style="background: #AF6F1E3D; border-radius: 5px">
                                                    <img src="{{ asset('images/icons/quality_assurance_icon.png') }}"
                                                            class="mr-2"
                                                            style="width: 22px; height: 22px">
                                                    <span class="font-weight-bold" style="color: #AF6F1E">{{ $dept->departmentname }}</span>
                                                </div>
                                            @endif
                                            @if($complaint->tardate)
                                                <div class="p-2" style="background: #44B3AB3D; border-radius: 5px">
                                                    <img src="{{ asset('images/icons/calendar_corrective.png') }}"
                                                            class="mr-2"
                                                            style="width: 22px; height: 22px">
                                                    <span class="font-weight-bold" style="color: #44B3AB">{{ date('d-m-Y', strtotime($complaint->tardate)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            @php
                                $records = DB::table('complaint_root_cause_corrective_action_details')->where('complaint_id',$complaint->id)->get()->toArray();
                            @endphp
                            <div class="observation px-3">
                                <label>Root Cause</label>
                            </div>
                            @if (!empty($records))
                                <div class="mt-2 mx-5 root-cause-table-adjust" style="overflow-x:auto;">
                                    <table class="table table-bordered table-custom">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">ID</th>
                                                <th style="width: 20%">Root cause</th>
                                                <th style="width: 20%">Corrective Action</th>
                                                <th style="width: 15%">Department</th>
                                                <th style="width: 15%">Responsible User</th>
                                                <th style="width: 10%">Target Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            for($i=0;$i<count($records);$i++){
                                            ?>
                                            <tr>

                                                <td>{{ $i+1 }}</td>
                                                <td class="break-text-table">{{$records[$i]->root_cause}}</td>
                                                <td class="break-text-table">{{$records[$i]->corrective_action}}</td>
                                                <td>
                                                    {{DB::table('departments')->where('id',$records[$i]->department)->value('departmentname')??"--"}}
                                                </td>
                                                <td>
                                                    @php
                                                        $respons = App\User::where('id',$records[$i]->responsibility)->first();
                                                    @endphp
                                                    <div class="d-flex p-2 align-items-center root-cause-resp" style="background: #25499C24; border-radius: 10px">
                                                        @if($respons && File::exists(public_path('/storage/profile/'.$respons->profile_picture)))
                                                            <img src="{{ asset('/storage/profile/'.$respons->profile_picture) }}" class="img-avatar" style="width: 28px; height: 28px" data-toggle="tooltip" data-position="top" title="{{$respons->name??''}}">
                                                        @else
                                                            <img src="{{asset('/images/avatar10.jpg')}}" class="img-avatar" style="width: 28px; height: 28px" data-toggle="tooltip" data-position="top" title="{{$respons->name??''}}">
                                                        @endif
                                                        <div class="text-left ml-2">
                                                            <span style="color: #25499C" class="font-weight-bold">{{ $respons ? $respons->name : '' }}</span>
                                                            <span class="font-weight-bold font-italic inner-heading">


                                                                @if($complaint->rootcause_updated_by_investigation_team==1)
                                                                Investigation Team
                                                                @else
                                                                Responsible User
                                                                @endif

                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{$records[$i]->target_date?date('d-m-Y',strtotime($records[$i]->target_date)):"--"}}
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted px-3">No root cause identified</p>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="mt-4 px-3 d-flex mb-4 px-adjust d-block-400 d-block-643">
                    <div class="col-md-9">
                        <label class="ml-1">Investigation Team</label>
                        <div class="d-flex" style="flex-flow: wrap;">
                            @if((isset($investigationTeams) && count($investigationTeams) > 0) || isset($complaint->team_lead_id))
                                @if(isset($complaint->team_lead_id))
                                    @php
                                        $leader = App\User::where('id',$complaint->team_lead_id)->first();
                                    @endphp
                                    @if($leader)
                                    <div class="send-notification-item" style="height: 3.5rem; display: flex;">
                                        @if($leader && File::exists(public_path('/storage/profile/'.$leader->profile_picture)))
                                            <img src="{{ asset('/storage/profile/'.$leader->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$leader->name??''}}">
                                        @else
                                            <img src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$leader->name??''}}"/>
                                        @endif
                                        <div>
                                            <label class="text-white m-0">{{ $leader->name }}</label>
                                            <br><span style="font-size: 10px; color:green;">Leader</span>
                                        </div>
                                    </div>
                                    @endif
                                @endif
                                @foreach($investigationTeams as $investigationItem)
                                    @php
                                        $teammember = App\User::where('id',$investigationItem->user_id)->first();
                                    @endphp
                                    <div class="send-notification-item">
                                        @if($teammember)
                                            @if ($teammember->profile_picture
                                                && File::exists(public_path('/storage/profile/'.$teammember->profile_picture)))
                                                <img src="{{ asset('/storage/profile/'.$teammember->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$teammember->name??''}}">
                                            @else
                                                <img src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$teammember->name??''}}"/>
                                            @endif
                                            <div>
                                                <label class="text-white m-0">{{ $teammember->name }}</label>
                                                <br><span class="text-white" style="font-size: 10px">Member</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <label class="text-muted ml-2">No investigation team assigned</label>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="inner-heading">Investigation Report</label>
                        <div class="d-flex" style="flex-flow: wrap">
                            <button type="button" class="btn btn-success btn-sm mr-3"
                                    data-toggle="modal"
                                    data-placement="top" title="View report"
                                    data-target="#viewReportModal"
                                    style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                View report
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-4 px-3 row mb-4 px-adjust">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <label class="ml-1 inner-heading">Attachments</label>
                        @if(count($complaintdocs) > 0)
                        <div class="ml-1 mb-1" style="min-height: 2rem">
                            <label>・Attachments of Complaint</label>
                            <div class="d-flex" style="flex-flow: wrap">
                                @foreach($complaintdocs as $issue_item)
                                    @if(File::exists(public_path('/storage/complaint/'. $issue_item->files)))
                                        <div class="btn-group" role="group" aria-label="Complaint File Actions">
                                            <a href="{{asset("storage/complaint/". $issue_item->files)}}"
                                                download="{{ $issue_item->files }}"
                                                class="btn btn-success btn-sm"  data-toggle="tooltip" data-placement="top" title="Download Complaint File"
                                                style="font-size: 0.75rem; background: #176044; border-color: #176044; margin-bottom: 0.5rem;max-width:180px;overflow:hidden;">
                                                {{ $issue_item->files }}
                                            </a>
                                            @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $complaint->addedby==Auth::user()->employeeid)
                                            <button type="button"
                                                    class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Delete Complaint File"
                                                    style="font-size: 11px; margin-bottom: 0.5rem;"
                                                    onclick="deleteComplaintFile('{{$issue_item->sno}}','{{ $issue_item->files }}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="btn-group" role="group" aria-label="Complaint File Actions">
                                            <a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Complaint File"
                                                style="font-size: 0.75rem; background: #176044; border-color: #176044;margin-bottom: 0.5rem;max-width:180px;overflow:hidden;">
                                                {{ $issue_item->files }}
                                            </a>
                                            @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $complaint->addedby==Auth::user()->employeeid)
                                            <button type="button"
                                                    class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Delete Complaint File"
                                                    style="font-size: 11px; margin-bottom: 0.5rem;"
                                                    onclick="deleteComplaintFile('{{$issue_item->sno}}','{{ $issue_item->files }}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <div class="ml-1 mb-1" style="min-height: 2rem">
                            <label>・Issue Attachments</label>
                            <div class="d-flex" style="flex-flow: wrap;">
                                @if(count($issuedocs) > 0)
                                    @foreach($issuedocs as $issue_item)
                                        @if(File::exists(public_path('/storage/complaint/'. $issue_item->issue_file)))
                                            <div class="btn-group" role="group" aria-label="Issue File Actions">
                                                <a href="{{asset("storage/complaint/". $issue_item->issue_file)}}"
                                                    download="{{ $issue_item->issue_file }}"
                                                    class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Issue File"
                                                    style="font-size: 0.75rem; background: #176044; border-color: #176044; margin-bottom: 0.5rem;max-width:180px;overflow:hidden;">
                                                    {{ $issue_item->issue_file }}
                                                </a>
                                                @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $complaint->addedby==Auth::user()->employeeid)
                                                <button type="button"
                                                        class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Delete Issue File"
                                                        style="font-size: 11px; margin-bottom: 0.5rem;"
                                                        onclick="deleteIssueFile('{{ $issue_item->id }}','{{ $issue_item->issue_file }}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        @else
                                            <div class="btn-group" role="group" aria-label="Issue File Actions">
                                                <a href="#" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Issue File"
                                                    style="font-size: 0.75rem; background: #176044; border-color: #176044; margin-bottom: 0.5rem;max-width:180px;overflow:hidden;">
                                                    {{ $issue_item->issue_file }}
                                                </a>
                                                @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $complaint->addedby==Auth::user()->employeeid)
                                                <button type="button"
                                                        class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Delete Issue File"
                                                        style="font-size: 11px; margin-bottom: 0.5rem;"
                                                        onclick="deleteIssueFile('{{ $issue_item->id }}','{{$issue_item->issue_file }}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <label class="text-muted ml-2">No issue attachments added</label>
                                @endif
                            </div>
                        </div>
                        <div class="ml-1 mb-4" style="min-height: 2rem">
                            <label>・Action Attachments</label>
                            <div class="d-flex" style="flex-flow: wrap">
                                @if(count($actiondocs) > 0)
                                    @foreach($actiondocs as $issue_item)
                                        <div class="btn-group" role="group" aria-label="Action File Actions">
                                        @if(File::exists(public_path('/storage/complaint/'. $issue_item->action_file)))
                                            <a href="{{asset("storage/complaint/". $issue_item->action_file)}}"
                                                download="{{ $issue_item->action_file }}"
                                                class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download Action File"
                                                style="font-size: 0.75rem; background: #176044; border-color: #176044; margin-bottom: 0.5rem;max-width:180px;overflow:hidden;">
                                                {{ $issue_item->action_file }}
                                            </a>
                                            @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $complaint->addedby==Auth::user()->employeeid)
                                            <button type="button"
                                                    class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Delete Action File"
                                                    style="font-size: 11px; margin-bottom: 0.5rem;"
                                                    onclick="deleteActionFile('{{ $issue_item->id }}','{{$issue_item->action_file }}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            @endif
                                        @else
                                            <a href="#" class="btn btn-success btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Download Action File"
                                                style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 0.5rem;max-width:180px;overflow:hidden;">
                                                {{ $issue_item->action_file }}
                                            </a>
                                            @if(Gate::allows('delete-complaint') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $complaint->addedby==Auth::user()->employeeid)
                                            <button type="button"
                                                    class="btn btn-danger btn-sm mr-3" data-toggle="tooltip" data-placement="top" title="Delete Action File"
                                                    style="font-size: 11px; margin-bottom: 0.5rem;"
                                                    onclick="deleteIssueFile('{{ $issue_item->id }}','{{$issue_item->action_file }}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            @endif
                                        @endif
                                        </div>
                                    @endforeach
                                @else
                                    <label class="text-muted ml-2">No action attachments added</label>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label class="ml-1 d-block inner-heading">Send Notifications To</label>
                        <div class="d-flex" style="flex-flow: wrap;">
                            @if(count($notificationUsers) > 0)
                                @foreach($notificationUsers as $user)
                                    <div class="send-notification-item">
                                        @if($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture)))
                                            <img src="{{ asset('/storage/profile/'.$user->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$user->name??''}}">
                                        @else
                                            <img src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$user->name??''}}"/>
                                        @endif
                                        <label class="text-white m-0">{{ $user->name }}</label>
                                    </div>
                                @endforeach
                            @else
                                <label class="text-muted ml-2">No additional users selected for notification</label>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <label class="ml-1 d-block inner-heading">Email reminder for action</label>
                        @if(count($frequency))
                            @foreach($frequency as $freq)
                                <div class="d-flex align-items-center mt-2 bg-white py-2 px-1 frequency-adjust" style="border-radius: 10px">
                                    <div class="mr-2 p-1" style="background: #2529453D; border-radius: 5px">
                                        <img src="{{ asset('images/icons/calendar_of_email_reminder.png') }}"
                                                style="width: 16px; height: 16px;">
                                    </div>
                                    <span class="font-weight-bold">{{ $freq }}</span>
                                </div>
                            @endforeach
                        @else
                            <label class="text-muted ml-2">No reminder schedule configured</label>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <div class="mt-4 px-5 d-block px-xs-adjust">
            <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem">
                <label class="mt-3 ml-4 section-heading"><i class="fa fa-chart-line mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Timeline & Review</label>
                <div class="d-flex justify-content-center pb-4">
                    <div class="col-md-5 col-sm-6">
                        <label class="d-block text-right mb-0 inner-heading" style="margin-top: 3rem">Added By & Date</label>
                        <label class="d-block text-right mb-0 inner-heading" style="margin-top: 3.25rem">Entry Reviewed By & Date</label>
                        <label class="d-block text-right mb-0 inner-heading" style="margin-top: 3.25rem">Action Reviewed By & Date</label>
                        <label class="d-block text-right mb-0 inner-heading" style="margin-top: 3rem">Closure Reviewed By & Date</label>
                    </div>
                    <div class="col-md-1 d-none-643" style="padding-left: 2.5rem;">
                        <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto;"></span>
                        <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                        <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                        <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin: 3rem auto"></span>
                    </div>
                    @php
                        $approvedat = null;
                        if($complaint->approved_at==null){
                            $approvedat = $complaint->reviewedat;
                        }else{
                            $approvedat = $complaint->approved_at;
                        }
                        if(!$reviewedBy){
                            $complaint->reviewedat = null;
                        }
                    @endphp
                    <div class="col-md-6 col-sm-6">
                        <div class="d-flex align-items-center" style="margin-top: 2rem;">
                            @if($postedBy && $postedBy->profile_picture && File::exists(public_path('/storage/profile/'. $postedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $postedBy->name }}"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{ asset('/storage/profile/'. $postedBy->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$postedBy->name??''}}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$postedBy->name??''}}"/>
                            @endif
                            <div class="d-block" style="color: grey; font-style: italic">
                                <label style="font-size: 1rem">{{ $postedBy ? $postedBy->name : ( $complaint->addedby ? 'Deleted User' : 'Not Specified') }}</label><br>
                                <label>on {{ $complaint->created_at ? date('d-m-Y', strtotime($complaint->created_at)) : '-' }}</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            @if($approvedBy && $approvedBy->profile_picture && File::exists(public_path('/storage/profile/'.$approvedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $approvedBy->name }}"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{ asset('/storage/profile/'.$approvedBy->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$approvedBy->name??''}}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$approvedBy->name??""}}"/>
                            @endif
                            <div class="d-block" style="color: grey; font-style: italic">
                                <label style="font-size: 1rem">{{ $approvedBy ? $approvedBy->name : ($complaint->approved_by ? 'Deleted User' : 'Pending Entry Review') }}</label><br>
                                <label>on {{ $approvedat ? date('d-m-Y', strtotime($approvedat)) : 'DD-MM-YYYY' }}</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            @if($reviewedBy && $reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$reviewedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $reviewedBy->name }}"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{ asset('/storage/profile/'.$reviewedBy->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$reviewedBy->name??''}}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$reviewedBy->name??''}}"/>
                            @endif
                            <div class="d-block" style="color: grey; font-style: italic">
                                <label style="font-size: 1rem">{{ $reviewedBy ? $reviewedBy->name : ($complaint->reviewed_by ? 'Deleted User' : 'Pending Approval') }}</label><br>
                                <label>on {{ $complaint->reviewedat ? date('d-m-Y', strtotime($complaint->reviewedat)) : 'DD-MM-YYYY' }}</label>
                            </div>
                        </div>
                        @php
                            $closedBy = null;
                            $closedat = null;
                            if($complaint->status=="Closed" || $complaint->status=="Need More Action"){
                                if(App\User::where('employeeid',$complaint->reviewed_by)->first()){
                                    $closedBy = App\User::withTrashed()->where('employeeid',$complaint->reviewed_by)->first();
                                    $closedat = $complaint->reviewedat ? date('d-m-Y', strtotime($complaint->reviewedat)):null;
                                }else{
                                    $closedBy = App\User::withTrashed()->where('id',$complaint->closed_by)->first();
                                    $closedat = $complaint->closed_at ? date('d-m-Y', strtotime($complaint->closed_at)):null;
                                }
                            }else{
                                $closedBy = App\User::withTrashed()->where('id',$complaint->closed_by)->first();
                                $closedat = $complaint->closed_at ? date('d-m-Y', strtotime($complaint->closed_at)):null;
                            }
                        @endphp
                        <div class="d-flex align-items-center mt-3">
                            @if($closedBy && $closedBy->profile_picture && File::exists(public_path('/storage/profile/'.$closedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $closedBy->name }}"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{ asset('/storage/profile/'.$closedBy->profile_picture) }}" data-toggle="tooltip" data-position="top" title="{{$closedBy->name??''}}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                        style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                        src="{{asset('/images/avatar10.jpg')}}" data-toggle="tooltip" data-position="top" title="{{$closedBy->name??''}}"/>
                            @endif
                            <div class="d-block" style="color: grey; font-style: italic">
                                <label style="font-size: 1rem">{{ $closedBy ? $closedBy->name : ($complaint->closed_by ? 'Deleted User' : 'Pending Closure')}}</label><br>
                                <label>on {{ $closedat ? $closedat : 'DD-MM-YYYY' }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $step = 0;
            switch ($complaint->status) {
                case 'Under Review':
                    $step = 1;
                    break;
                case 'Rejected':
                    $step = 2;
                    break;
                case 'Approved':
                    $step = 3;
                    break;
                case 'Correction Proposed- RC Awaiting':
                    $step = 3;
                    break;
                case 'Actions Proposed- Review Awaiting':
                    $step = 5;
                    break;
                case 'Action Reviewed, Not OK':
                    $step = 6;
                    break;
               case 'Action Reviewed, OK':
                    $step = 7;
                    break;
                case 'Actions Completed':
                    $step = 8;
                    break;
                case 'Need More Action':
                    $step = 9;
                    break;
                case 'Closed':
                    $step = 10;
                    break;
                default:
                    # code...
                    break;
            }
        @endphp
        <div class="mt-4 px-5 d-block pb-4 px-xs-adjust">
            <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem; padding-bottom: 1rem">
                <label class="mt-3 ml-4 section-heading"><img src="https://cdn.iconscout.com/icon/free/png-256/free-forum-1779761-1513575.png" class="mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px; width: 30px"> User Interaction</label>
                @if ($complaint->reason || $step >= 3)
                    <div class="d-block mt-4 ml-6">
                        <label class="inner-heading">Entry Review remarks</label>
                        @if($complaint->reason)
                            <p>{{ $complaint->reason }}</p>
                        @else
                            <p class="text-muted">No remarks added</p>
                        @endif
                    </div>
                @endif
                @if ($complaint->remarks || $step >= 7)
                    <div class="d-block mt-4 ml-6">
                        <label class="inner-heading">Action Review remarks</label>
                        @if($complaint->remarks)
                            <p>{{ $complaint->remarks }}</p>
                        @else
                            <p class="text-muted">No action remarks added</p>
                        @endif
                    </div>
                @endif
                @if ($complaint->needaction || $step >= 10 )
                    <div class="d-block mt-4 ml-6">
                        <label class="inner-heading">Closure Review remarks</label>
                        @if($complaint->needaction)
                            <p>{{ $complaint->needaction }}</p>
                        @else
                            <p class="text-muted">No closure remarks added</p>
                        @endif
                    </div>
                @endif
                @if($complaint->issue_authenticity)
                <div class="d-block mt-4 ml-6">
                    <label class="inner-heading">Issue Authenticity</label>
                    <div class="d-flex" style="flex-flow: wrap">
                        <button type="button" class="btn btn-success btn-sm mr-3"
                                style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;cursor:none;">
                            {{$complaint->issue_authenticity}}
                        </button>
                    </div>
                </div>
                @endif
                @if (isset($complaint->source_of_issue))
                    <div class="d-block mt-4 ml-6">
                        <label class="inner-heading">{{$complaintTemplateSectionFields['source_of_issue']['field_display_label']}}</label>
                        <div class="d-flex" style="flex-flow: wrap">
                            <button type="button" class="btn btn-success btn-sm mr-3"
                                    style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;cursor:none;">
                                {{$complaint->source_of_issue}}
                            </button>
                        </div>
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
    <div id="viewReportModal" class="modal fade"
            aria-labelledby="viewReportModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-size: 12px">
                <div class="modal-header">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="p-1 mr-2" style="background: #2529453D; border-radius: 8px;">
                                <img src="{{ asset('images/icons/document_icon.png') }}" style="width: 18px; height: 18px">
                            </div>
                            <span class="font-weight-bold">Investigation Report</span>
                        </div>
                        <button type="button" class="close btn-close-modal-custom" data-dismiss="modal" aria-label="Close">
                            <img src="{{ asset('images/icons/close_icon.png') }}" style="width: 24px; height: 24px">
                        </button>
                    </div>
                </div>
                <div class="modal-body px-4">
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>Was this a one-time occurrence, or does it happen regularly?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <span>{{ $complaint->occurrence ? ($complaint->occurrence == 'one_time' ? 'One time' : 'Regularly') : '-' }}</span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>What processes or systems are related to the problem?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <span>{{ $complaint->system_related_problems ? $complaint->system_related_problems : '-'  }}</span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>Were there any recent changes or modifications?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <span>
                                @if($complaint->recent_changes === 1)
                                Yes
                                @elseif(!$complaint->recent_changes)
                                -
                                @else
                                No
                                @endif
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>Did everyone follow established procedures?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <span>
                                @if($complaint->follow_procedures === 1)
                                Yes
                                @elseif(!$complaint->follow_procedures)
                                -
                                @else
                                No
                                @endif

                            </span>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>What are the contributing factors?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <div>
                                <span>
                                    @if($complaint->contributing_factors)
                                        @php
                                            $contributingFactors = [];
                                            $values = [
                                                'human' => 'Human',
                                                'technical' => 'Technical',
                                                'organizational' => 'Organizational',
                                                'material' => 'Material',
                                                'machine' => 'Machine',
                                                'design' => 'Design',
                                                'method' => 'Method',
                                                'envi_aspects' => 'Environmental aspects',
                                                'others' => 'Others',
                                            ];
                                            foreach(explode(',', $complaint->contributing_factors) as $factor) {
                                                if (isset($values[$factor])) {
                                                    $contributingFactors[] = $values[$factor];
                                                }
                                            }
                                        @endphp
                                        {{ implode(', ', $contributingFactors) }}
                                    @else
                                        -
                                    @endif
                            </span>
                            @if($complaint->contributing_factors_details)
                                <div class="mt-2">
                                    <label>Contributing factors details: </label><br>
                                    <span>{{ $complaint->contributing_factors_details }}</span>
                                </div>
                            @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>Are there any external factors or influences?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <div>
                                <span>
                                @if($complaint->external_factors)
                                    @php
                                        $externalFactors = [];
                                        foreach(explode(',', $complaint->external_factors) as $factor) {
                                            switch($factor) {
                                                case 'market_conditions':
                                                    $externalFactors[] = 'Market conditions';
                                                    break;
                                                case 'regulatory_changes':
                                                    $externalFactors[] = 'Regulatory changes';
                                                    break;
                                                case 'external_events':
                                                    $externalFactors[] = 'External events';
                                                    break;
                                                case 'others':
                                                    $externalFactors[] = 'Others';
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }
                                    @endphp
                                    {{ implode(', ', $externalFactors) }}
                                @else
                                    -
                                @endif
                            </span>
                                @if($complaint->external_factors_details)
                                    <div class="mt-2">
                                        <label> External factors details: </label><br>
                                        <span>{{ $complaint->external_factors_details }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span>Was the issue foreseeable?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <span>
                                @if($complaint->issue_foreseeable === 1)
                                Yes
                                @elseif(!$complaint->issue_foreseeable)
                                -
                                @else
                                No
                                @endif
                            </span>
                        </div>
                    </div>
                    <hr>
                    @if($complaint->prevent_reason)
                        <div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                                <span> Why wasn't it prevented?</span>
                            </div>
                            <div class="d-flex">
                                <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                                <span>{{ $complaint->prevent_reason }}</span>
                            </div>
                        </div>
                        <hr>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
<script>
    const recordId = "{{$complaint->id}}";
    function deleteIssueFile(fileid,filename) {
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/complaint/${recordId}/delete-issue`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                fileid : fileid,
            },
            success: function (data) {
                $('#error_issue').addClass('d-none');
                $("#success_issue")
                    .stop(true, true)
                    .text(`Issue file name: ${filename} has been removed successfully!`)
                    .removeClass('d-none')
                    .fadeIn('slow')
                    .delay(2000)
                    .fadeOut('slow', function() {
                        $(this).addClass('d-none').text('');
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
            },
            error: function (response) {
                $('#error_issue').stop(true, true).removeClass('d-none');
            }
        });
    }
    function deleteActionFile(fileid,filename) {
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/complaint/${recordId}/delete-action`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                fileid : fileid,
            },
            success: function (data) {
                $('#error_action').addClass('d-none');
                $("#success_action")
                    .stop(true, true)
                    .text(`Issue file name: ${filename} has been removed successfully!`)
                    .removeClass('d-none')
                    .fadeIn('slow')
                    .delay(2000)
                    .fadeOut('slow', function() {
                        $(this).addClass('d-none').text('');
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
            },
            error: function (error) {
                $('#error_action').stop(true, true).removeClass('d-none');
            }
        });
    }
    function deleteComplaintFile(fileid,filename) {
        $.ajax({
            url : window.location.protocol + "//" + window.location.host +`/user/complaint/${recordId}/delete-complaint`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                fileid : fileid,
            },
            success: function (data) {
                $('#error_complaint').addClass('d-none');
                $("#success_complaint")
                    .stop(true, true)
                    .text(`Complaint file name: ${filename} has been removed successfully!`)
                    .removeClass('d-none')
                    .fadeIn('slow')
                    .delay(2000)
                    .fadeOut('slow', function() {
                        $(this).addClass('d-none').text('');
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
            },
            error: function (error) {
                $('#error_complaint').stop(true, true).removeClass('d-none');
            }
        });
    }
</script>
@endsection
