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
        @font-face {
            font-family: 'Symbola';
            src: url('{{ storage_path("fonts/Symbola/Symbola.ttf") }}') format('truetype');
        }
        @page {
            margin-top: 100px;
            margin-bottom: 70px;
        }
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

        .page-count:before {
            content: counter(pages);
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
        .text-muted {
            color: #666666
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
        .circle {
            width: 15px;
            height: 15px;
            border-radius: 7px 7px 7px 7px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            margin-right: 30px;
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
        .process-stage-point.span-entry-under-review-action::after {
            content: 'Under Review';
            position: absolute;
            top: 20px;
            left: -25px;
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
            content: 'Approved';
            position: absolute;
            top: 20px;
            left: -20px;
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
            left: -60px;
            white-space: nowrap;
        }
        .process-stage-point.span-rc-update-correction-awaiting-action::after {
            content: 'RC Updated- Correction Awaiting';
            position: absolute;
            top: 20px;
            left: -60px;
            white-space: nowrap;
        }
        .process-stage-point.span-rejected::after {
            content: 'Rejected';
            position: absolute;
            top: 20px;
            left: -20px;
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
            background-color: #af6f1e;
        }
        .bg-success {
            background-color: #176044;
        }
        .bg-danger {
            background-color: #af1e1e;
        }
        .bg-primary {
            background-color: #25499c;
        }
        .bg-secondary {
            background-color: #737373;
        }
        .bg-flat {
            background: #bff0dd;
        }
        .overview-status .block {
            background: #F6F7F9;
            border-radius: 10px 10px 10px 10px;
            display: block;
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

        .overview-status1 .block {
            background: #F6F7F9;
            border-radius: 10px 10px 10px 10px;
            display: block;
            vertical-align: top;
            /* height: 200px; */
        }
        .overview-status1 .block .block-title {
            /* margin-top: 10px; */
            margin-left: 20px
        }
        .overview-status1 .block .block-title img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            background: #2529453D;
            border-radius: 5px 5px 5px 5px
        }
        .overview-status1 .block .block-title span {
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
        .bg-amethylist-light {
            background-color: #6f42c1 !important;
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
        .rootcause-table, .rootcause-table td, .rootcause-table th, .product-table, .product-table td, .product-table th {
            border: 1px solid #e1e6e9;
        }
        .send-notification-item {
            background: #343A40;
            border-radius: 0.25rem;
            align-items: center;
            height: 2.5rem;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .send-notification-item img {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .row {
            display:flex;
            flex-wrap:wrap;
            margin-right:20px;
            margin-left:20px
        }
        .col-2 {
            position:relative;
            width:100%;
            min-height:1px;
            padding-right:15px;
            padding-left:14px;
            flex:0 0 16.666667%;
            max-width:16.666667%
        }
        .col-4 {
            position:relative;
            width:100%;
            min-height:1px;
            flex:0 0 33.33333%;
            max-width:33.33333%
        }
        .mb-0{
            margin-bottom:0;
            box-sizing: border-box;
        }
        .pr-0{
            padding-right:0;
        }
        .pb-0{
            padding-bottom: 0;
        }
        .block1{
            background-color: #fff;
            box-shadow: none;
            margin-bottom: 1rem;
            width:93%;
        }
        .block-rounded {
            border-radius: .25rem;
        }
        .flex-column {
            flex-direction: column !important;
            -webkit-box-orient: vertical !important;
            -webkit-box-direction: normal !important;
        }
        .d-flex {
            display: flex !important;
        }
        .block-rounded > .block-content:last-child {
            border-bottom-right-radius: .2rem;
            border-bottom-left-radius: .2rem;
        }
        .block-rounded > .block-content:first-child {
            border-top-left-radius: .2rem;
            border-top-right-radius: .2rem;
        }
        .block-content {
            transition: opacity .25s ease-out;
            width: 100%;
            margin: 0 auto;
            padding: 1.25rem 1.25rem 1px;
            overflow-x: visible;
        }
        .p-2 {
            padding: .5rem !important;
        }
        .align-items-center {
            align-items: center !important;
        }
        .justify-content-between {
            justify-content: space-between !important;
        }
        .flex-grow-1 {
            flex-grow: 1 !important;
        }

        .text-dark {
            color: #343a40 !important;
        }
        .font-weight-bold {
            font-weight: 600 !important;
        }
        .mr-1, .mx-1 {
            margin-right: .25rem !important;
        }
        .mb-2, .my-2 {
            margin-bottom: .5rem !important;
        }
        .mt-0{
            margin-top:0;
        }
        .btn {
            display: inline-block;
            font-weight: 600;
            color: #343a40;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            white-space: wrap;
            font-family: Inter,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .btn-alt-primary {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }
        .btn-sm {
            padding: .25rem .25rem;
            font-size: .575rem;
            line-height: 1;
            border-radius: .2rem;
        }
        .mt-1, .my-1 {
            margin-top: .25rem !important;
        }
        .table thead th {
            vertical-align: middle;
        }
        .status-blue1 {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            word-break: break-word;
            font-weight: 600;
        }
        .rich-text,
        .rich-text * {
            word-break: break-all !important;
            overflow-wrap: anywhere !important;
            white-space: normal !important;
            font-family: 'Inter', 'Symbola', sans-serif;
            font-size: 11px;
        }
        .break-text {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-all;
        }
        .break-text-table {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-all;
        }
        a {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-all;
        }
        .section-heading {
            font-size: 12px;
            font-weight: 600;
        }
        .inner-heading {
            font-size: 11px;
            font-weight: 600;
        }
        .inner-content {
            font-size: 11px;
            font-weight: 400;
        }
        .product-table thead th {
            background-color:#252945;
            color:white;
            font-size: 8px !important;
            height: 50px;
        }
        .product-table tbody td {
            font-size: 8px !important;
        }
        .rootcause-table thead th {
            background-color:#252945;
            color:white;
            font-size:10px;
        }
        .rootcause-table tbody td {
            font-size:9px;
        }
        #section-history {
            page-break-before: always;
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
        {{-- <span class="page-count"></span> --}}
    </footer>
    <!-- Page Content -->
    <div class="container">
        <table class="heading-table">
            <tr>
                <td><label class="page-title">Complaint</label><span class="issue-title">{{ $complaint->title }}</span></td>
                <td class="text-right">
                    @php
                        $resp_user_image = DB::table('users')->where('email',$complaint->responsibility)->value('profile_picture');
                        $posted_user_image = DB::table('users')->where('employeeid',$complaint->addedby)->value('profile_picture');
                        $activeComplaintTemplateId = $complaint->complaint_template_id;
                        $complaintTemplateSectionFields = json_decode($complaint->complaint_template_section_field_rules, true);
                    @endphp
                    @if($resp_user_image && File::exists(public_path('/storage/profile/'.$resp_user_image)))
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'.$resp_user_image) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-5" src="{{public_path('/images/avatar10.jpg')}}"/>
                    @endif

                    @if($posted_user_image && File::exists(public_path('/storage/profile/'.$posted_user_image)))
                        <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'.$posted_user_image) }}">
                    @else
                        <img class="img-avatar48 mx-5 my-5" src="{{public_path('/images/avatar10.jpg')}}"/>
                    @endif
                </td>
            </tr>
        </table>
        <div style="height: 20px"></div>
        <div class="overview-status1 section-heading my-20" style="">
            <div class="block" style="padding-bottom: 10px">
                <div style="padding: 10px 30px; background: #F6F7F9; ">
                    <p class="p-0 m-0">
                        <img src="{{ public_path('images/icons/eye.png')}}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle; font-weight: 600;">Overview</span>
                    </p>
                </div>
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
                    $chunckOverviewFields = array_chunk($overviewFields, 4);
                @endphp
                <div style="background: #F6F7F9;margin-left: 5px; margin-right: 5px;margin-top:-10px;">
                    <table style="border:none;">
                        @foreach ($chunckOverviewFields as $overviewFieldRow)
                            <tr>
                                @foreach ($overviewFieldRow as $overviewField)
                                    <td style="width:25%;">
                                        <dl class="mb-0 mt-0" style="background-color: #ffffff;">
                                            <dt class="mb-2" style="padding-top:2px;padding-left:4px;"><span class="inner-heading">{{$overviewField['title']}}</span></dt>
                                            <dt class="text-dark mb-0" style="padding-top:2px;padding-left:4px;">
                                                <img src="{{ public_path($overviewField['image_path']) }}" class="mr-1" style="width: 25px;height: 25px;border-radius: 5px;vertical-align: middle;">&nbsp;&nbsp;<span class="inner-content" style="vertical-align: middle;">{{$overviewField['value']??"--"}}</span>
                                            </dt>
                                        </dl>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div style="height: 20px"></div>
        <div class="overview-status my-20">
            @php
                $complaint->status = $complaint->getOriginal('status');
            @endphp
            <div class="block" style="padding-bottom: 10px">
                <div style="padding: 10px 30px; background: #F6F7F9; ">
                    <p class="p-0 m-0">
                        <img src="{{ public_path('images/icons/line-chart.png')}}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span class="section-heading" style="display: inline-block; vertical-align: middle;">Status & Timeline</span>
                    </p>
                </div>
                <p class="m-0 inner-heading" style="margin-bottom: 20px; margin-left: 25px;">
                    Process Stage
                </p>
                <p class="m-0" style="margin-bottom: 50px; margin-left: 100px; font-weight: 500">
                    <label class="circle has-after @if($complaint->status == 'Under Review') bg-secondary @endif">
                        <span class="process-stage-point @if($complaint->status == 'Under Review') span-entry-under-review-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Rejected') bg-danger @endif">
                        <span class="process-stage-point @if($complaint->status == 'Rejected') span-rejected @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Approved') bg-amethylist-light @endif">
                        <span class="process-stage-point @if($complaint->status == 'Approved') span-action-awaiting @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Correction Proposed- RC Awaiting') bg-blue @endif">
                        <span class="process-stage-point @if($complaint->status == 'Correction Proposed- RC Awaiting') span-correction-proposed-rc-awaiting-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'RC Updated- Correction Awaiting') bg-blue @endif">
                        <span class="process-stage-point @if($complaint->status == 'RC Updated- Correction Awaiting') span-rc-update-correction-awaiting-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Actions Proposed- Review Awaiting') bg-blue @endif">
                        <span class="process-stage-point @if($complaint->status == 'Actions Proposed- Review Awaiting') span-proposed-review-awaiting-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Action Reviewed, OK') bg-success @endif">
                        <span class="process-stage-point @if($complaint->status == 'Action Reviewed, OK') span-action-reviewed-ok @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Action Reviewed, Not OK') bg-danger @endif">
                        <span class="process-stage-point @if($complaint->status == 'Action Reviewed, Not OK') span-action-reviewed-not-ok @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Actions Completed') bg-primary @endif">
                        <span class="process-stage-point @if($complaint->status == 'Actions Completed') span-action-complete @endif"></span>
                    </label>
                    <label class="circle has-after @if($complaint->status == 'Need More Action') bg-warning @endif">
                        <span class="process-stage-point @if($complaint->status == 'Need More Action') span-need-more-action @endif"></span>
                    </label>
                    <label class="circle @if($complaint->status == 'Closed') bg-black @endif">
                        <span class="process-stage-point @if($complaint->status == 'Closed') span-close-action @endif"></span>
                    </label>
                </p>
                <p class="m-0" style=" margin-left: 25px; margin-right:25px;">
                    <span class="d-inline-block inner-heading" style="float: left">Target Date: {{ $complaint->tardate ? date('d-m-Y', strtotime($complaint->tardate)) : '--'}}</span>
                    <span class="d-inline-block inner-heading" style="float: right">Target Date(Investigation): {{ $complaint->investigation_date ? date('d-m-Y', strtotime($complaint->investigation_date)) : '--' }}</span>
                </p>
            </div>
        </div>
        <div style="height: 20px"></div>
        <div class="detail my-20">
            <div style="border-radius: 10px 10px 10px 10px;background: #F6F7F9;">
                <div style="padding: 10px 30px; background: #F6F7F9; ">
                    <p class="p-0 m-0">
                        <img src="{{ public_path('images/icons/clipboard.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span class="section-heading" style="display: inline-block; vertical-align: middle;">Details</span>
                    </p>
                </div>
                <div style="background: #F6F7F9;">
                    <div style="margin-left: 10px; margin-right: 10px;">
                        <table style="padding:0; border-collapse: separate; border-spacing: 0 6px;">
                            <tr style="padding:0;">
                                    <td style="width:22%;padding:0; vertical-align: top">
                                        <!-- Mode of Complaint -->
                                        <dl class="mb-0 mt-0" style="background-color: #ffffff;">
                                            <dt class="mb-2" style="padding-top:2px;padding-left:4px;"><span class="inner-heading">Mode of Complaint</span></dt>
                                            <dt class="text-dark mb-0" style="padding-top:2px;padding-left:4px;">
                                                <div class="d-inline-block" style="width:20%;">
                                                    <img src="{{ public_path('images/icons/modeofcomplaint2.png') }}"  style="width: 25px;height: 25px;border-radius: 5px;vertical-align: middle;">
                                                </div>&nbsp;
                                                @if ($complaint->mode_of_complaint)
                                                    <div class="d-inline-block inner-content" style="vertical-align: middle; width:78%;">{{ucfirst($complaint->mode_of_complaint)}}</div>
                                                @else
                                                    <div class="d-inline-block inner-content" style="vertical-align: middle;width:78%; color: #666666">Not specified</div>
                                                @endif

                                            </dt>
                                        </dl>
                                        <!-- END Mode of Complaint -->
                                    </td>
                                    <td style="width:22%;padding-top:0;padding-bottom:0;padding-right:4px;padding-left:4px;vertical-align: top">
                                        <!-- Complaint Received by -->
                                        <dl class="mb-0 mt-0" style="background-color: #ffffff;">
                                            <dt class="fw-bold mb-2" style="padding-top:2px;padding-left:4px;"><span class="inner-heading">Complaint Recieved By</span></dt>
                                            <dt class="text-dark mb-0" style="padding-top:2px;padding-left:4px;">
                                                <div class="d-inline-block" style="width:20%;">
                                                    <img src="{{ public_path('images/icons/complaintreceivedby.png') }}" style="width: 25px;height: 25px;border-radius: 5px;vertical-align: middle;">
                                                </div>&nbsp;
                                                @if ($employee)
                                                    <div class="d-inline-block inner-content" style="width:78%;vertical-align: middle;">{{$employee}}</div>
                                                @else
                                                    <div class="d-inline-block inner-content" style="width:78%;vertical-align: middle;">Not specified</div>
                                                @endif

                                            </dt>
                                        </dl>
                                        <!-- END Complaint Received by -->
                                    </td>
                                    <td style="width:22%;padding:0;vertical-align: top">
                                        <!-- Complaint Received on -->
                                        <dl class="mb-0 mt-0" style="background-color: #ffffff;">
                                            <dt class="fw-bold mb-2" style="padding-top:2px;padding-left:4px;"><span class="inner-heading">Complaint Recieved On</span></dt>
                                            <dt class="text-dark mb-0" style="padding-top:2px;padding-left:4px;">
                                                <div class="d-inline-block" style="width:20%;">
                                                    <img src="{{ public_path('images/icons/complaintreceiveddate.png') }}" style="width: 25px;height: 25px;border-radius: 5px;vertical-align: middle;">
                                                </div>&nbsp;
                                                <div class="d-inline-block inner-content" style="width:78%;vertical-align: middle;">{{$complaint->crdate?date('d-m-Y',strtotime($complaint->crdate)):"DD-MM-YYYY"}}</div>
                                            </dt>
                                        </dl>
                                        <!-- END Complaint Received On -->
                                    </td>
                                    <td style="width: 33%;vertical-align: top; padding-top:0">
                                        <span class="m-0 inner-heading" style="margin-bottom: 10px;">
                                            Customer Details
                                        </span>
                                        <div class="d-block" style="width: 99%; vertical-align: top;">
                                            @if ($complaint->customer)
                                                <label class="inner-heading" for="Customer Name">{{$customer->customer??$complaint->customer}}</label><br>
                                            @endif
                                            @if ($complaint->customer_email)
                                                <label class="inner-content" for="Customer Name">{{$complaint->customer_email}}</label><br>
                                            @endif
                                            @if ($phonenumber)
                                                <label class="inner-content" for="Customer Name">+{{$countryPhoneCode}}-{{$phonenumber}}</label><br>
                                            @endif
                                            @if ($customeraddress  || $stateName || $countryName)
                                                <label class="inner-content" for="Customer Name">{{$customeraddress? $customeraddress.", ":""}} {{$stateName?$stateName.", ":""}} {{$countryName??""}}</label><br>
                                            @endif
                                            @for($p=0;$p<count($productNames);$p++)
                                            <p class="m-0"><button class="btn btn-alt-primary btn-sm"  style="font-size:8px;padding:3px;">{{$productNames[$p]}}</button></p>
                                            @endfor
                                        </div>
                                    </td>
                            </tr>
                        </table>
                        @if ($activeComplaintTemplateId == 2 && $complaintTemplateSectionFields['job_history']['enable_field'])
                            <div>
                                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                                    <div style="margin-left: 5px; margin-right: 5px; padding-bottom: 20px">
                                        <div style="padding-top: 20px; margin-bottom: 10px;">
                                            <p style="font-weight: 500;">{{$complaintTemplateSectionFields['job_history']['field_display_label']}}</p>
                                            <div style="background: white;">
                                                <div class="d-block" style="padding: 5px; width: 95%; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                                                    <div class="m-0 rich-text break-text" style="font-weight: 300; text-align: justify; padding: 5px;">{!! $complaint->job_history??"--" !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($complaint->previously_occured_complaint) && $complaint->previously_occured_complaint == 'yes')
                            <div>
                                <span class="inner-heading">Issue History</span>
                                <div style="height:10px"></div>
                                <div style="padding:10px; background-color: #fff">
                                    <label style="text-align: center">
                                        <img src="{{ public_path('images/icons/sync.png')}}" style="height: 14px; width: 14px; vertical-align:middle;">
                                        @if (!empty($complaint->no_of_occurrence))
                                            <span style="vertical-align:middle; line-height: 11px; font-size: 11px;">
                                                This complaint has recurred
                                                <span style="font-weight:bold; color:#000; vertical-align:middle;">
                                                    {{ $complaint->no_of_occurrence }} times,
                                                </span>
                                            </span>

                                        @else
                                            <span style="vertical-align:middle;">
                                                This complaint has been previously recorded,
                                            </span>
                                        @endif
                                        @if (isset($complaint->previous_complaint_reference))
                                            @if ($complaint->previous_complaint_reference == '0')
                                                <span style="vertical-align: middle; font-size: 10px" class="pr-1">External Reference:</span>
                                                @if(isset($complaint->external_reference) && $complaint->external_reference)
                                                    <span style="vertical-align: middle;" class="btn btn-alt-primary btn-sm">
                                                        <span style="word-break: break-all; overflow-wrap: break-word;">{{$complaint->external_reference}}</span>
                                                    </span>
                                                @else
                                                    <span style="vertical-align: middle; color: #666" >No details Provided</span>
                                                @endif
                                            @else
                                                @if ($complaint->previousComplaintRef)
                                                    <span class="btn btn-alt-primary btn-sm">
                                                        {{ $complaint->previousComplaintRef['trackno'] ?? '' }} -
                                                        {{ $complaint->previousComplaintRef['title'] ?? '' }}
                                                    </span>
                                                @else
                                                    <span class="btn btn-alt-primary btn-sm">
                                                        {{ $complaint->previous_complaint_reference.'(deleted complaint)' }}
                                                    </span>
                                                @endif
                                            @endif
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div>
                            <p class="m-0 inner-heading" style="margin-bottom: 8px;margin-top:20px;">
                                {{$complaint->title}}
                            </p>
                            <div class="m-0 rich-text break-text" class="inner-content" style="text-align: justify">{!! $complaint->complaint !!}</div>
                        </div>
                        <div style="height: 20px"></div>
                        <div>
                            <p class="m-0 inner-heading">
                                Recommended Action
                            </p>
                            <div class="m-0 rich-text break-text inner-content" style="text-align: justify">{!! $complaint->action !!}</div>
                        </div>
                        <div style="height: 20px"></div>
                        <div>
                            <p class="m-0 inner-heading" style="margin-bottom: 10px;">
                                Financial Cost & Potential Cost Savings
                            </p>
                            <div style="display:inline-block; width: 44%; background: #AF1E1E3D; border-radius: 10px; margin-bottom: 10px; padding: 8px 18px">
                                <img src="{{ public_path('images/icons/financial_icon.png') }}"
                                    style="background: #AF1E1E;width: 14px;height: 14px;padding: 5px; border-radius: 5px; margin-right: 5px; margin-top: 3px; display: inline-block">
                                @if($complaint->financial_loss)
                                    <span style="color: #AF1E1E; font-weight: bold; display: inline-block; vertical-align: middle">
                                        {{ $complaint->financial_loss }}
                                    </span>
                                @else
                                    <span style="color: #666666; font-weight: bold; display: inline-block; vertical-align: middle">
                                        No financial details provided
                                    </span>
                                @endif
                            </div>
                            <div style="display:inline-block; width: 44%; background: #1760443D; border-radius: 10px;  margin-bottom: 10px; padding: 8px 18px">
                                <img src="{{ public_path('images/icons/money.png') }}"
                                    style="width: 24px;height: 24px;border-radius: 5px; margin-right: 5px;margin-top: 3px; display: inline-block;">
                                @if($complaint->potential_cost_savings)
                                    <span style="color: #176044; font-weight: bold; display: inline-block; vertical-align: middle">
                                        {{ $complaint->potential_cost_savings }}
                                    </span>
                                @else
                                    <span style="color: #666666; font-weight: bold; display: inline-block; vertical-align: middle">
                                        No cost-saving details provided
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ($activeComplaintTemplateId == null)
                    <div style="background: #F6F7F9;">
                        <div style="margin-left: 5px; margin-right:5px;">
                            <div class="d-inline-block" style="padding: 5px; vertical-align: top">
                                <p class="m-0 inner-heading" style="margin-bottom: 10px;">Products</p>
                                <table class="product-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%;">ID No.</th>
                                            <th style="width: 5%;">Item Code</th>
                                            <th style="width: 25%;">Product Description</th>
                                            <th style="width: 5%;">Size of the Item</th>
                                            <th style="width: 5%;">Unit</th>
                                            <th style="width: 9%;">Total Delivery Quantity</th>
                                            <th style="width: 9%;">Affected Quantity</th>
                                            <th style="width: 9%;">Cost Impact</th>
                                            <th style="width: 9%;">Complaint Category</th>
                                            <th style="width: 8%;">Sale Return Request</th>
                                            <th style="width: 8%;">Rework Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for($i=0;$i<count($records2);$i++){
                                        ?>
                                        <tr>
                                            <td >
                                                {{$records2[$i]->idno??"-"}}
                                            </td>
                                            <td >
                                                {{$records2[$i]->item_code??"-"}}
                                            </td>
                                            <td ><p class="break-text-table"  style="text-align: justify">{!!$records2[$i]->product_description ? nl2br($records2[$i]->product_description):"-"!!}</p></td>
                                            <td >
                                                {{$records2[$i]->size_of_item??"-"}}
                                            </td>
                                            @php
                                                $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$records2[$i]->unit)->first();
                                            @endphp
                                            <td >
                                                {{$oldunitforoldrecords ? $oldunitforoldrecords->name : $records2[$i]->unit}}
                                            </td>
                                            <td >
                                                {{$records2[$i]->total_delivery_quantity??"-"}}
                                            </td>
                                            <td >
                                                {{$records2[$i]->affected_quantity??"-"}}
                                            </td>
                                            <td >
                                                {{$records2[$i]->cost_impact??"-"}}
                                            </td>
                                            @php $catname = DB::table('complaint_categories')->whereIn('id',explode(',',$records2[$i]->complaint_category))->pluck('category_name')->toArray() @endphp
                                            <td >
                                                {{implode(',',$catname)}}
                                            </td>
                                            <td ">
                                                {{$records2[$i]->sale_return_request??"-"}}

                                            </td>
                                            <td class="inner-content">
                                                {{$records2[$i]->rework_request??"-"}}
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif ($activeComplaintTemplateId == 1)
                    <div style="background: #F6F7F9;">
                        <div style="margin-left: 5px; margin-right:5px;">
                            <div class="d-inline-block" style="padding: 5px; vertical-align: top">
                                <p class="m-0 inner-heading" style="margin-bottom: 10px;">Products</p>
                                <table class="product-table">
                                    <thead>
                                        <tr>
                                            @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                                <th style="width: 3%;">{{$complaintTemplateSectionFields['idno']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                                <th style="min-width: 5%;">{{$complaintTemplateSectionFields['itemcode']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                                <th style="min-width: 25%;">{{$complaintTemplateSectionFields['productdesc']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                                <th style="min-width: 5%;">{{$complaintTemplateSectionFields['sizeofitem']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                                <th style="min-width: 5%;">{{$complaintTemplateSectionFields['unit']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                                <th style="min-width: 9%;">{{$complaintTemplateSectionFields['totalquant']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                                <th style="min-width: 9%;">{{$complaintTemplateSectionFields['affectquant']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                                <th style="min-width: 9%;">{{$complaintTemplateSectionFields['costimpact']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                                <th style="min-width: 9%;">{{$complaintTemplateSectionFields['complaintcategory']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                                <th style="min-width: 8%;">{{$complaintTemplateSectionFields['salereturnreq']['field_display_label']}}</th>
                                            @endif
                                            @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                                <th style="min-width: 8%;">{{$complaintTemplateSectionFields['reworkreq']['field_display_label']}}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for($i=0;$i<count($records2);$i++){
                                        ?>
                                        <tr>
                                            @if ($complaintTemplateSectionFields['idno']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->idno??"-"}}
                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['itemcode']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->item_code??"-"}}
                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['productdesc']['enable_field'])
                                                <td><p class="break-text-table" style="text-align: justify">{!!$records2[$i]->product_description ? nl2br($records2[$i]->product_description):"-"!!}</p></td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['sizeofitem']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->size_of_item??"-"}}
                                                </td>
                                            @endif
                                            @php
                                                $oldunitforoldrecords = App\ComplaintUnit::where('is_deleted',false)->where('id',$records2[$i]->unit)->first();
                                            @endphp
                                            @if ($complaintTemplateSectionFields['unit']['enable_field'])
                                                <td>
                                                    {{$oldunitforoldrecords ? $oldunitforoldrecords->name : $records2[$i]->unit}}
                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['totalquant']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->total_delivery_quantity??"-"}}
                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['affectquant']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->affected_quantity??"-"}}
                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['costimpact']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->cost_impact??"-"}}
                                                </td>
                                            @endif
                                            @php $catname = DB::table('complaint_categories')->whereIn('id',explode(',',$records2[$i]->complaint_category))->pluck('category_name')->toArray() @endphp
                                            @if ($complaintTemplateSectionFields['complaintcategory']['enable_field'])
                                                <td>
                                                    {{implode(',',$catname)}}
                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['salereturnreq']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->sale_return_request??"-"}}

                                                </td>
                                            @endif
                                            @if ($complaintTemplateSectionFields['reworkreq']['enable_field'])
                                                <td>
                                                    {{$records2[$i]->rework_request??"-"}}
                                                </td>
                                            @endif
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                    <div style="margin-left: 5px; margin-right: 5px; padding-bottom: 20px">
                        <div style="padding-top: 20px; margin-bottom: 10px;">
                            <p class="inner-heading">Correction & Root Cause</p>
                            <div style="background: white;">
                                <div class="d-block" style="padding: 5px; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                                    <p class="m-0" style="margin-bottom: 10px;font-weight: 500;">Correction</p>
                                    <p class="m-0" style="font-weight: 300;">
                                        @if($complaint->correction)
                                            <p class="break-text">{{ $complaint->correction }}</p>
                                        @else
                                            <label class="text-muted">No correction added</label>
                                        @endif
                                    </p>
                                </div>
                                <table>
                                    <tr>
                                        <td align="center" valign="top" style="width:80px;">
                                            <div style="font-weight:bold; font-size:12px; margin-bottom:10px;">
                                                Responsibility
                                            </div>
                                            @if($resp && \Illuminate\Support\Facades\File::exists(public_path('/storage/profile/'.$resp->profile_picture)))
                                                <img
                                                    src="{{ public_path('/storage/profile/'.$resp->profile_picture) }}"
                                                    width="48"
                                                    height="48"
                                                    style="display:block; margin-bottom:8px;"
                                                    alt="{{ $resp->name }}"
                                                >
                                            @else
                                                <img
                                                    src="{{ public_path('/images/avatar10.jpg') }}"
                                                    width="48"
                                                    height="48"
                                                    style="display:block; margin-bottom:8px;"
                                                    alt="No picture"
                                                >
                                            @endif

                                            <div style="font-size:10px; line-height:12px;">
                                                @if($resp)
                                                    {{ $resp->name }}
                                                @else
                                                    Responsibility not assigned yet
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div style="width: 200px">
                                                @if($dept)
                                                    <div style="background: #AF6F1E3D; padding: 5px 10px; border-radius: 3px; width: 100%">
                                                        <img src="{{ public_path('images/icons/quality_assurance_icon.png') }}"
                                                            style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 5px">
                                                        <span style="color: #AF6F1E; font-weight: bold; display: inline-block;vertical-align: middle">{{ $dept }}</span>
                                                    </div>
                                                @endif
                                                @if($complaint->tardate)
                                                    <div style="background: #44B3AB3D; margin-top: 10px; padding: 5px 10px; border-radius: 3px; width: 100%">
                                                        <img src="{{ public_path('images/icons/calendar_corrective.png') }}"
                                                            style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 5px">
                                                        <span style="color: #44B3AB; font-weight: bold; display: inline-block; vertical-align: middle">
                                                        {{ date('d-m-Y', strtotime($complaint->tardate)) }}
                                                    </span>
                                                    </div>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                    <div style="margin-left: 5px; margin-right: 5px; padding-bottom: 20px">
                        <div style="padding-top: 20px; margin-bottom: 10px;">
                            <p class="m-0 inner-heading" style="margin-bottom: 10px;">Root Cause</p>
                            <div style="background: white;">
                                <table class="rootcause-table">
                                    <thead>
                                    <tr>
                                        <th style="width: 6%;height:40px;">ID</th>
                                        <th style="width: 27%;">Root cause</th>
                                        <th style="width: 27%;">Corrective Action</th>
                                        <th style="width: 15%;">Department</th>
                                        <th style="width: 15%;">Responsible User</th>
                                        <th style="width: 10%;">Target Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        for($i=0;$i<count($records);$i++){
                                        ?>
                                        <tr>

                                            <td>{{ $i+1 }}</td>
                                            <td><p class="break-text" style="text-align: justify">{{$records[$i]->root_cause}}</p></td>
                                            <td><p class="break-text" style="text-align: justify">{{$records[$i]->corrective_action}}</p></td>
                                            <td style="font-size:9px;text-align: center;">
                                                {{DB::table('departments')->where('id',$records[$i]->department)->value('departmentname')??"--"}}
                                            </td>
                                            <td>
                                                @php
                                                    $respons = App\User::where('id',$records[$i]->responsibility)->first();
                                                @endphp
                                                <div style="background: #25499C24; border-radius: 10px; padding: 5px;">
                                                    <div style="text-align: center">
                                                        @if($respons && File::exists(public_path('/storage/profile/'.$respons->profile_picture)))
                                                            <img src="{{ public_path('/storage/profile/'.$respons->profile_picture) }}"
                                                                style="width: 28px; height: 28px; border-radius: 14px;" alt="">
                                                        @else
                                                            <img src="{{public_path('/images/avatar10.jpg')}}"
                                                                style="width: 28px; height: 28px; border-radius: 14px;" alt="">
                                                        @endif
                                                    </div>
                                                    <div style="margin-top: 5px !important; text-align: center">
                                                        <span style="color: #25499C; font-weight: bold; font-size: 9px;">{{ $respons ? $respons->name :'' }}</span>
                                                        <br><span class="inner-heading" style="font-style: italic">
                                                            @if($complaint->rootcause_updated_by_investigation_team==1)
                                                                Investigation Team
                                                            @else
                                                                Responsible User
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="font-size:9px;">
                                                {{$records[$i]->target_date?date('d-m-Y',strtotime($records[$i]->target_date)):"--"}}
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                    <div style="margin-left: 15px; margin-right: 15px; padding-bottom: 20px">
                        <div style="margin-top: 20px; padding-bottom: 30px;">
                            <div style="vertical-align: top; width: 60%; display: inline-block">
                                <p class="m-0 p-0 inner-heading" style="; margin-bottom: 10px; margin-left: 30px;">Investigation Team</p>
                                <div style="margin-left: 30px">
                                    @if((isset($investigationTeams) && count($investigationTeams) > 0) || isset($complaint->team_lead_id))
                                        @if(isset($complaint->team_lead_id))
                                            @php
                                                $leader = App\User::where('id',$complaint->team_lead_id)->first();
                                            @endphp
                                            @if($leader)
                                            <div style="display: inline-block; padding: 5px 12px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white; margin-bottom: 10px;">
                                                @if($leader->profile_picture && File::exists(public_path('/storage/profile/'. $leader->profile_picture)))
                                                    <img src="{{ public_path('/storage/profile/'. $leader->profile_picture) }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @else
                                                    <img src="{{ public_path('/images/avatar10.jpg') }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @endif
                                                <div style="display: inline-block; vertical-align: middle">
                                                    <label style="font-size:10px;">{{ $leader->name }}</label>
                                                    <br><span style="font-size: 8px;color:green;">Leader</span>
                                                </div>
                                            </div>
                                            @endif
                                        @endif
                                        @foreach($investigationTeams as $investigationItem)
                                            @php
                                                $user = App\User::where('id',$investigationItem->user_id)->first();
                                            @endphp
                                            @if ($user)
                                                <div style="display: inline-block; padding: 5px 12px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white; margin-bottom: 10px">
                                                    @if($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture)))
                                                        <img src="{{ public_path('/storage/profile/'.$user->profile_picture) }}"
                                                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                    @else
                                                        <img src="{{ public_path('/images/avatar10.jpg') }}"
                                                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                    @endif
                                                    <div style="display: inline-block; vertical-align: middle">
                                                        <label style="font-size:10px;">{{ $user->name }}</label>
                                                        <br><span style="font-size: 8px;">Member</span>
                                                    </div>
                                                </div><br>
                                            @endif
                                        @endforeach
                                    @else
                                        <label class="text-muted ml-2">No investigation team assigned</label>
                                    @endif
                                </div>
                            </div>
                            <div style="vertical-align: top; width: 39%; display: inline-block">
                                <p class="m-0 p-0 inner-heading" style=";margin-bottom: 10px; margin-left: 53px;">Investigation Report</p>
                                <button type="button"
                                        style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-left: 55px; color: white; padding: 5px 10px">
                                    View report
                                </button>
                            </div>

                        </div>
                        <div style="margin-top: 20px; padding-bottom: 50px;">

                            <div style="vertical-align: top; width: 30%;display: inline-block">
                                <p class="m-0 p-0 inner-heading" style=" margin-bottom: 10px; margin-left: 30px;">Attachments</p>
                                @if(count($complaintdocs) > 0)
                                <p class="m-0 p-0" style="font-size:10px; margin-bottom: 5px; margin-left: 30px;font-weight: 500;">Attachments of Complaint</p>
                                <div style="margin-left: 30px">
                                    @foreach($complaintdocs as $issue_item)
                                        @if($issue_item && $issue_item != 'NULL')
                                            <span class="badge badge-success"
                                                    style="font-size: 0.75rem;color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 0.5rem;">
                                                {{ $issue_item }}
                                            </span><br>
                                        @else
                                            <label class="text-muted ml-2">No attachments of complaint added</label>
                                        @endif
                                    @endforeach
                                </div>
                                @endif
                                <p class="m-0 p-0" style="font-size:10px; margin-bottom: 5px; margin-left: 30px;font-weight: 500;">Issue Attachments</p>
                                <div style="margin-left: 30px">
                                    @if(count($issuedocs) > 0)
                                        @foreach($issuedocs as $issue_item)
                                            <span class="badge badge-success"
                                                    style="font-size: 0.75rem;color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 0.5rem;">
                                                {{ $issue_item->issue_file }}
                                            </span><br>
                                        @endforeach
                                    @else
                                        <label class="text-muted ml-2" style="font-size:10px;">No issue attachments added</label>
                                    @endif
                                </div>
                                <div style="height: 20px"></div>
                                <p class="m-0 p-0" style="font-size:10px; margin-bottom: 5px; margin-left: 30px;font-weight: 500;">Action Attachments</p>
                                <div style="margin-left: 30px">
                                    @if(count($actiondocs) > 0)
                                        @foreach($actiondocs as $issue_item)
                                            <span class="badge badge-success"
                                                    style="font-size: 0.75rem;color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 0.5rem;">
                                                {{ $issue_item->action_file }}
                                            </span><br>
                                        @endforeach
                                    @else
                                        <label class="text-muted ml-2"  style="font-size:10px;">No action Attachment added</label><br>
                                    @endif
                                </div>
                            </div>
                            <div style="vertical-align: top; width: 39%; display: inline-block">
                                <p class="m-0 p-0 inner-heading" style="margin-bottom: 10px; margin-left: 30px;">Send Notifications To</p>
                                <div style="margin-left: 30px">
                                    @if(count($notificationUsers) > 0)
                                        @foreach($notificationUsers as $user)
                                            <div style="display: inline-block; padding: 5px 5px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white; margin-bottom: 10px">
                                                @if($user->profile_picture && File::exists(public_path('/storage/profile/'.$user->profile_picture)))
                                                    <img src="{{ public_path('/storage/profile/'.$user->profile_picture) }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @else
                                                    <img src="{{ public_path('/images/avatar10.jpg') }}"
                                                         style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px; border-radius: 12px">
                                                @endif
                                                <label style="display: inline-block; vertical-align: middle;font-size:10px;">{{ $user->name }}</label>
                                            </div><br>
                                        @endforeach
                                    @else
                                        <label class="text-muted ml-2" style="font-size:10px;">No additional users selected for notification</label>
                                    @endif
                                </div>
                            </div>
                            <div style="vertical-align: top; width: 30%; display: inline-block">
                                <p class="m-0 p-0 inner-heading" style="margin-bottom: 10px; margin-left: 53px;">Email reminder for action</p>
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
                                        <label class="text-muted ml-3"  style="font-size:10px;">No reminder schedule configured</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 20px"></div>
        <div class="my-20" style="" id="section-history">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/line-chart.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 16px; height: 16px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span class="section-heading" style="display: inline-block; vertical-align: middle;">Timeline & Review</span>
                    </div>
                </div>
                <div style="margin-top: 16px; margin-left: 50px">
                    <div style="margin-bottom: 19px; position: relative;">
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
                                <br><span>on {{ $complaint->created_at ? date('d-m-Y', strtotime($complaint->created_at)) : 'DD-MM-YYYY'  }}</span>
                            </span>
                        </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -20px; left: 236px">
                    </div>
                    <div style="margin-bottom: 19px; position: relative;">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Entry Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                            @if($approvedBy && $approvedBy->profile_picture && File::exists(public_path('/storage/profile/'.$approvedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $approvedBy->name }}"
                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                    src="{{ public_path('/storage/profile/'.$approvedBy->profile_picture) }}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                    src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                <span style="font-size: 16px;">{{ $approvedBy ? $approvedBy->name : 'Pending Enrty Review' }}</span>
                                <br><span style="font-size: 12px">on {{ $approvedat ? date('d-m-Y', strtotime($approvedat)) : 'DD-MM-YYYY' }}</span>
                            </span>
                        </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -20px; left: 236px">
                    </div>
                    <div style="margin-bottom: 19px; position: relative;">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Action Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                            @if($reviewedBy && $reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$reviewedBy->profile_picture)))
                                <img class="img-avatar mr-3" alt="{{ $reviewedBy->name }}"
                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                    src="{{ public_path('/storage/profile/'.$reviewedBy->profile_picture) }}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                    src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                <span style="font-size: 16px;">{{ $reviewedBy ? $reviewedBy->name : 'Pending Approval' }}</span>
                                <br><span style="font-size: 12px">on {{ $complaint->reviewedat ? date('d/m/Y', strtotime($complaint->reviewedat)) : 'DD-MM-YYYY' }}</span>
                            </span>
                        </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 48px; position: absolute; top: -20px; left: 236px;">
                    </div>
                    <div style="margin-bottom: 20px; padding-bottom: 20px;">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Closure Reviewed By & Date</span>
                        <span class="circle circle-line-vertical" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                            @if($closedBy && $closedBy->profile_picture && File::exists(public_path('/storage/profile/'.$closedBy->profile_picture)))
                                <img class="img-avatar mr-3"  alt="{{ $closedBy->name }}"
                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                    src="{{ public_path('/storage/profile/'.$closedBy->profile_picture) }}">
                            @else
                                <img class="img-avatar mr-3" alt="No picture"
                                    style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                    src="{{public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                                <span style="font-size: 16px">{{ $closedBy ? $closedBy->name : 'Pending Closure' }}</span>
                                <br><span style="font-size: 12px">on {{ $closedat ? $closedat : 'DD-MM-YYYY' }}</span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 20px"></div>
        <div class="my-20">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/conversation.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span class="section-heading" style="display: inline-block; vertical-align: middle;">User Interaction</span>
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
                    @if ($complaint->reason || $step >= 3)
                        <div style="margin-top: 20px; margin-left: 50px">
                            <p class="p-0 m-0 inner-heading" style="margin-bottom: 10px;">Entry Review Remarks</p>
                            <p class="p-0 m-0" style="font-weight: 300">
                                @if($complaint->reason)
                                    {{ $complaint->reason }}
                                @else
                                    <span class="text-muted">No remarks added</span>
                                @endif
                            </p>
                        </div>
                    @endif
                    @if ($complaint->remarks || $step >= 7)
                        <div style="margin-top: 20px; margin-left: 50px">
                            <p class="p-0 m-0 inner-heading" style="margin-bottom: 10px;">Action Review remarks</p>
                            <p class="p-0 m-0" style="font-weight: 300">
                                @if($complaint->remarks)
                                    {{ $complaint->remarks }}
                                @else
                                    <span class="text-muted">No action remarks added</span>
                                @endif
                            </p>
                        </div>
                    @endif
                    @if ($complaint->needaction || $step >= 10)
                        <div style="margin-top: 30px; margin-left: 50px; padding-bottom: 30px">
                            <p class="p-0 m-0 inner-heading" style="margin-bottom: 10px;">Action Review Remarks</p>
                            <p class="p-0 m-0" style="font-weight: 300">
                                @if($complaint->needaction)
                                    {{ $complaint->needaction}}
                                @else
                                    <span class="text-muted">No closure remarks added</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    @if($complaint->issue_authenticity)
                    <p class="m-0 p-0 inner-heading" style=";margin-bottom: 10px; margin-left: 53px;">Issue Authencity</p>
                    <button type="button"
                            style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-left: 55px; color: white; padding: 5px 10px">
                        {{$complaint->issue_authenticity}}
                    </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <!-- END Page Content -->
</body>
</html>
