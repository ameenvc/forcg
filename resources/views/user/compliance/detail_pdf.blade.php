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
            margin-right: 10px;
            font-weight: 600;
        }
        .heading-table .issue-title {
            color: grey;

        }

        table, td, th {
            /*border: 1px solid #80808052;*/
        }
        .o-block,
        .s-t-block,
        .detail-block,
        .t-h-block {
            background: #F6F7F9;
            border-radius: 10px 10px 10px 10px;
            display: block;
            padding-bottom: 10px;
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            padding-top: 10px;
        }
        .o-block {
            height: 250px;
        }
        .o-block .title,
        .s-t-block .title,
        .detail-block .title,
        .t-h-block .title {
            margin-left: 20px;
            margin-bottom: 20px;
        }
        .o-block .title img,
        .s-t-block .title img,
        .detail-block .title img,
        .t-h-block .title img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            background: #2529453D;
            border-radius: 5px 5px 5px 5px
        }
        .o-block .title span,
        .s-t-block .title span,
        .detail-block .title span,
        .t-h-block .title span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
        }
        .o-block .o-content-block,
        .s-t-block .s-t-content-block,
        .detail-block .dt-content-block,
        .t-h-block .t-h-content-block {
            margin-top: 20px;
            display: block;
            width: 100%;
            margin-left: 20px;
        }
        .o-block .o-content-block .o-item-block {
            background: white;
            margin-right: 10px;
            padding: 12px 22px 12px 12px;
            border-radius: 10px;
            display: inline-block;
        }
        .o-block .o-content-block .o-item-block label {
            font-weight: 600;
        }
        .o-block .o-content-block .o-item-block div {
            margin-top: 10px;
        }
        .o-block .o-content-block .o-item-block div img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            border-radius: 5px 5px 5px 5px
        }
        .o-block .o-content-block .o-item-block div span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
            font-size: 14px;
            text-align: justify;
        }
        .o-block .o-content-block .o-item-block.c-id-block {
            width: 15%;
        }
        .o-block .o-content-block .o-item-block.c-id-block div img {
            background: #25499C3D;
        }
        .o-block .o-content-block .o-item-block.r-a-block {
            width: 30%;
        }
        .o-block .o-content-block .o-item-block.r-a-block div img {
            background: #1760443D;
        }
        .o-block .o-content-block .o-item-block.l-i-block {
            width: 30%;
        }
        .o-block .o-content-block .o-item-block.l-i-block div img {
            background: #EE9F273D;
        }
        .o-block .o-content-block .o-item-block.v-y-d-block {
            width: 20%;
        }
        .o-block .o-content-block .o-item-block.v-y-d-block div img {
            background: #44B3AB3D;
        }
        .o-block .o-content-block .o-item-block.a-r-block {
            width: 35%;
        }
        .o-block .o-content-block .o-item-block.a-r-block div img {
            background: #AF1E1E3D;
        }
        .o-block .o-content-block .o-item-block.dp-block {
            width: 25%;
        }
        .o-block .o-content-block .o-item-block.dp-block div img {
            background: #30C78D3D;
        }
        .s-t-block {
            height: 200px;
        }
        .s-t-block .s-t-content-block {
            background: white;
            margin-right: 10px;
            padding: 12px 22px 12px 12px;
            border-radius: 10px;
            display: inline-block;
        }
        .s-t-block .s-t-content-block label {
            font-weight: 600;
        }
        .s-t-block .s-t-content-block .process-block {
            padding-left: 60px;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .s-t-block .s-t-content-block .circle {
            width: 15px;
            height: 15px;
            border-radius: 7px 7px 7px 7px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            margin-right: 65px;
        }
        .s-t-block .s-t-content-block .circle.has-after::after {
            content: '';
            position: absolute;
            top: 8px;
            left: 15px;
            width: 70px;
            height: 2px;
            background: #000000;
        }
        .s-t-block .s-t-content-block .process-stage-point::after {
            content: attr(data-content);
            position: absolute;
            top: 20px;
            left: -35px;
            white-space: nowrap;
            font-size:9px !important;
        }
        .bg-blue {
            background-color: #25499c !important;
        }
        .bg-dark-red {
            background-color: #af1f1e !important;
        }
        .bg-brown {
            background-color: #af6f1e !important;
        }
        .bg-dark-green {
            background-color: #78a003 !important;
        }
        .bg-gray {
            background-color:#403a3a !important;
        }
        .detail-block .dt-content-block .re-ow,
        .detail-block .dt-content-block .rr-fr,
        .detail-block .dt-content-block .vt-at,
        .detail-block .dt-content-block .lpi {
            display: block;
            width: 100%;
        }
        .detail-block .dt-content-block .re-ow .re,
        .detail-block .dt-content-block .rr-fr .rr,
        .detail-block .dt-content-block .vt-at .vt {
            display: inline-block;
            width: 55%;
            margin-right: 10px;
            vertical-align: top;
        }
        .detail-block .dt-content-block .re-ow .re label,
        .detail-block .dt-content-block .re-ow .ow label,
        .detail-block .dt-content-block .rr-fr .rr label,
        .detail-block .dt-content-block .rr-fr .fr label,
        .detail-block .dt-content-block .vt-at .vt label,
        .detail-block .dt-content-block .vt-at .at label,
        .detail-block .dt-content-block .lpi label {
            font-weight: 600;
            font-size: 14px;
        }
        .detail-block .dt-content-block .re-ow .re div,
        .detail-block .dt-content-block .re-ow .ow div,
        .detail-block .dt-content-block .rr-fr .rr div,
        .detail-block .dt-content-block .rr-fr .fr div,
        .detail-block .dt-content-block .vt-at .vt div,
        .detail-block .dt-content-block .vt-at .at div,
        .detail-block .dt-content-block .lpi div {
            margin-top: 10px;
        }
        .detail-block .dt-content-block .re-ow .ow,
        .detail-block .dt-content-block .rr-fr .fr,
        .detail-block .dt-content-block .vt-at .at {
            display: inline-block;
            width: 35%;
            vertical-align: top;
        }
        .detail-block .dt-content-block .re-ow .ow div img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 32px;
            height: 32px;
            border-radius: 16px 16px 16px 16px
        }
        .detail-block .dt-content-block .re-ow .ow div span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
            text-align: justify;
        }
        .detail-block .dt-content-block .rr-fr,
        .detail-block .dt-content-block .vt-at,
        .detail-block .dt-content-block .lpi {
            margin-top: 20px;
        }
        .detail-block .dt-content-block .rr-fr .fr div {
            background: white;
            padding: 12px 22px;
            border-radius: 8px;
        }
        .detail-block .dt-content-block .rr-fr .fr div img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            background: #2529453D;
            border-radius: 5px 5px 5px 5px
        }
        .detail-block .dt-content-block .rr-fr .fr div span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
            text-align: justify;
        }
        .status {
            display: inline-block;
            padding: 5px 7px;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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

        .status-gray {
            color: #737373;
            background-color: #e6e6e6;
            border-color: #fbf4ea;
        }
        .status-blue {
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }
        .status-success {
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .detail-block .dt-content-block .vt-at {
            page-break-before: always;
        }
        .detail-block .dt-content-block .vt-at .vt div {
            display: block;
            width: 100%;
        }
        .detail-block .dt-content-block .vt-at .vt div div {
            display: block;
            background: #343A40;
            border-radius: 8px;
            padding: 8px 16px;
            margin-right: 10px;
            width: 60%;
        }
        .detail-block .dt-content-block .vt-at .vt div div img {
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
            width: 32px;
            height: 32px;
            padding: 5px;
            border-radius: 16px 16px 16px 16px
        }
        .detail-block .dt-content-block .vt-at .vt div div span {
            display: inline-block;
            vertical-align: middle;
            font-weight: 600;
            text-align: justify;
            color: white;
        }
        .detail-block .dt-content-block .vt-at .at div {
            display: block;
            padding: 4px 10px;
            background: #1760443D;
            border-radius: 4px;
            word-wrap: break-word;
        }
        .detail-block .dt-content-block .vt-at .at div img {
            vertical-align: middle;
            margin-right: 5px;
            width: 16px;
            height: 16px;
            padding: 5px;
            border-radius: 5px 5px 5px 5px
        }
        .detail-block .dt-content-block .vt-at .at div span {
            vertical-align: middle;
            font-weight: 600;
            text-align: justify;
            color: #176044;
        }
        .status-success{
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .t-h-block .t-h-content-block {
            margin-top: 20px;
            margin-left: 50px
        }
        .t-h-block .t-h-content-block .t-h-item {
            margin-bottom: 30px;
            position: relative
        }
        .t-h-block .t-h-content-block .t-h-item .t-h-item-title {
            display: inline-block;
            vertical-align: middle;
            width: 200px;
            text-align: right;
            font-weight: 500;
        }
        .t-h-block .t-h-content-block .t-h-item .t-h-item-circle {
            margin-left: 50px;
            margin-right: 30px;
            display: inline-block;
            vertical-align: middle
        }
        .t-h-block .t-h-content-block .t-h-item div {
            margin-left: 20px;
            display: inline-block;
            vertical-align: middle
        }
        .t-h-block .t-h-content-block .t-h-item div img {
            display: inline-block;
            vertical-align: middle;
            border-radius: 50%;
            width: 32px;
            height: 32px
        }
        .t-h-block .t-h-content-block .t-h-item div .o-info {
            display: inline-block;
            vertical-align: middle;
            color: grey;
            font-weight: 500
        }
        .t-h-block .t-h-content-block .t-h-item div .o-info span:first-child {
            font-size: 16px;
            display: block;
        }
        .t-h-block .t-h-content-block .t-h-item div .o-info span:last-child {
            font-size: 12px;
            display: block;
        }
        .t-h-block .t-h-content-block .separate-line {
            position: relative;
        }
        .t-h-block .t-h-content-block .separate-line hr {
            transform: rotate(90deg);
            width: 54px;
            position: absolute;
            top: -27px;
            left: 235px;
            z-index: 1;
            border-color: #878787;
            border-width: 1px;
        }
        .circle {
            width: 20px;
            height: 20px;
            border-radius: 10px 10px 10px 10px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            z-index: 2;
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
@php 
    $statusArray = [
        'Under Review', 'Awaiting Compliance Evaluation', 'Complied, Under Review', 
        'Not Complied, Under Review', 'Partially Complied, Under Review', 
        'Need improvement', 'Compliance Evaluation due'
    ];
@endphp
<div class="container">
    <table class="heading-table">
        <tr>
            <td><label class="page-title">Compliance Management</label></td>
            <td class="text-right">
                @if($compliance->postedByCompliance
                && File::exists(public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture)))
                    <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture) }}">
                @else
                    <img class="img-avatar48 mx-5 my-5" src="{{ public_path('/images/avatar10.jpg')}}" />
                @endif
            </td>
        </tr>
    </table>
    <div class="o-block">
        <div class="title">
            <img src="{{ public_path('images/icons/eye.png') }}">
            <span>Overview</span>
        </div>
        <div class="o-content-block">
            <div class="c-id-block o-item-block">
                <label>Compliance ID</label>
                <div>
                    <img src="{{ public_path('images/icons/label.png') }}">
                    <span>{{ $compliance->auth_id }}</span>
                </div>
            </div>
            <div class="r-a-block o-item-block">
                <label>Regulatory Authority</label>
                <div>
                    <img src="{{ public_path('images/icons/account_balance.png') }}">
                    <span>{{ $compliance->regulatory->name }}</span>
                </div>
            </div>
            <div class="l-i-block o-item-block">
                <label>Legislative Issue</label>
                <div>
                    <img src="{{ public_path('images/icons/gavel.png') }}">
                    <span>{{ $compliance->legalDocuments->name}}</span>
                </div>
            </div>
        </div>
        <div class="o-content-block">
            <div class="v-y-d-block o-item-block">
                <label>Version / Year / Dated</label>
                <div>
                    <img src="{{ public_path('images/icons/event.png') }}">
                    <span>{{ $compliance->version }}</span>
                </div>
            </div>
            <div class="a-r-block o-item-block">
                <label>Act or Regulation</label>
                <div>
                    <img src="{{ public_path('images/icons/library_books.png') }}">
                    <span>{{ $compliance->regulation }}</span>
                </div>
            </div>
            <div class="dp-block o-item-block">
                <label>Department</label>
                <div>
                    <img src="{{ public_path('images/icons/domain.png') }}">
                    <span>{{ $compliance->dep ? $compliance->dep->departmentname : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="s-t-block">
        <div class="title">
            <img src="{{ public_path('images/icons/visibility.png') }}">
            <span>Status & Timeline</span>
        </div>
        <div class="s-t-content-block">
            <label>Compliance Status</label>
            <div class="process-block">
                <!-- draft under review -->
                @if(in_array($compliance->comp_status, $statusArray))
                    <label class="circle has-after bg-gray">
                        <span class="process-stage-point"
                              data-content="Draft Under Review"></span>
                    </label>
                @else
                    <label class="circle has-after">
                        <span class="process-stage-point"></span>
                    </label>
                @endif
                <!-- not complied -->
                @if(in_array($compliance->comp_status, [
                    'Not Complied',
                ]))
                    <label class="circle has-after bg-dark-red">
                        <span class="process-stage-point"
                              data-content="{{ $compliance->comp_status }}"></span>
                    </label>
                @else
                    <label class="circle has-after">
                        <span class="process-stage-point"></span>
                    </label>
                @endif
                <!-- Partially Complied -->
                @if(in_array($compliance->comp_status, ['Partially Complied']))
                    <label class="circle has-after bg-blue">
                        <span class="process-stage-point"
                              data-content="{{ $compliance->comp_status }}"></span>
                    </label>
                @else
                    <label class="circle has-after">
                        <span class="process-stage-point"></span>
                    </label>
                @endif
                <!-- complied -->
                @if(in_array($compliance->comp_status, [
                    'Complied',
                ]))
                    <label class="circle has-after bg-dark-green">
                        <span class="process-stage-point"
                              data-content="Evaluation Completed"></span>
                    </label>
                @else
                    <label class="circle has-after">
                        <span class="process-stage-point"></span>
                    </label>
                @endif
                <!-- Awaiting Periodic Evaluation -->
                @if(in_array($compliance->comp_status, [
                     'Awaiting Periodic Evaluation',
                 ]))
                    <label class="circle has-after bg-brown">
                        <span class="process-stage-point"
                              data-content="{{ $compliance->comp_status }}"></span>
                    </label>
                @else
                    <label class="circle has-after">
                        <span class="process-stage-point"></span>
                    </label>
                @endif
                <!-- Periodic Evaluation On Due -->
                @if(in_array($compliance->comp_status, [
                    'Periodic Evaluation On Due',
                ]))
                    <label class="circle bg-dark-red">
                        <span class="process-stage-point"
                              data-content="{{ $compliance->comp_status }}"></span>
                    </label>
                @else
                    <label class="circle">
                        <span class="process-stage-point"></span>
                    </label>
                @endif
            </div>
        </div>
    </div>
    <div class="detail-block">
        <div class="title">
            <img src="{{ public_path('images/icons/assignment.png') }}">
            <span>Details</span>
        </div>
        <div class="dt-content-block">
            <!-- Requirement / Owner -->
            <div class="re-ow">
                <div class="re">
                    <label>Requirement</label>
                    <div>
                        {{ nl2br($compliance->requirement) }}
                    </div>
                </div>
                <div class="ow">
                    <label>Owner</label>
                    <div>
                        @if($compliance->postedByCompliance
                            && File::exists(public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture))
                        )
                            <img class="img-avatar img-avatar48 mr-3"
                                 src="{{ public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span>{{ $compliance->postedByCompliance ? $compliance->postedByCompliance->name : '' }}</span>
                    </div>
                </div>
            </div>
            <!-- Related Risk / Frequency -->
            <div class="rr-fr">
                <div class="rr">
                    <label>Related Risk</label>
                    <div class="d-flex flex-wrap">
                        @if(count($relatedRisk))
                            @foreach($relatedRisk as $item)
                                <span class="status status-blue">{{ $item->grp_id }}</span>
                            @endforeach
                        @elseif ($compliance->aspect)
                            <span class="status status-blue">{{ $compliance->aspect }}</span>
                        @else
                            <p class="text-danger fx-12px">No related risk added</p>
                        @endif
                    </div>
                </div>
                <div class="fr">
                    <label>Frequency</label>
                    <div>
                        <img class="calendar-icon" alt="calendar_month"
                             src="{{ public_path('images/icons/calendar_month.png') }}">
                        <span>{{ $compliance->frequency }}</span>
                    </div>
                </div>
            </div>
            <!-- Visibility To / Attachments -->
            <div class="vt-at">
                <div class="vt">
                    <label>Visibility To</label>
                    <div>
                        @if(count($visibleToList))
                            @foreach($visibleToList as $item)
                                <div>
                                    @if(File::exists(public_path('/storage/profile/'. $item->profile_picture)))
                                        <img alt="{{ $item->name }}"
                                             src="{{ public_path('/storage/profile/'. $item->profile_picture) }}">
                                    @else
                                        <img alt="{{ $item->name }}"
                                             src="{{ public_path('/images/avatar10.jpg') }}" />
                                    @endif
                                    <span>{{ $item->name }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-danger fx-12px">No visibility to added</p>
                        @endif
                    </div>
                </div>
                <div class="at">
                    <label>Attachments</label>
                    <div>
                        @if($compliance->file && $compliance->file != 'NULL')
                            <img alt="download"
                                 src="{{ public_path('images/icons/download_green.png') }}">
                            <span>{{ $compliance->file }}</span>
                        @else
                            <p class="text-danger fx-12px">No attachments added</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- License & Permit ID -->
            <div class="mt-4 lpi">
                <label>License & Permit ID</label>
                <div>
                    @if(count($compliance->licensePermits))
                        @foreach($compliance->licensePermits as $item)
                            <span class="status status-success">{{ $item->licensenumber }}</span>
                        @endforeach
                    @else
                        <p class="text-danger fx-12px">No license & permit ID added</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="t-h-block">
        <div class="title">
            <img src="{{ public_path('images/icons/timeline.png') }}">
            <span>Timeline & History</span>
        </div>
        <div class="t-h-content-block">
            <div class="t-h-item">
                <span class="t-h-item-title">Added By & Date</span>
                <span class="t-h-item-circle circle"></span>
                <div>
                    @if($compliance->postedByCompliance
                        && $compliance->postedByCompliance->profile_picture
                        && File::exists(public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture)))
                        <img class="img-avatar mr-3"
                             src="{{ public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture) }}">
                    @else
                        <img class="img-avatar mr-3" alt="No picture"
                             src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                    <span class="o-info">
                        <span>{{ $compliance->postedByCompliance ? $compliance->postedByCompliance->name : '-' }}</span>
                        <span>{{ $compliance->created_at ? 'on ' . date('d-m-Y', strtotime($compliance->created_at)) : '-' }}</span>
                    </span>
                </div>
            </div>
            <div class="separate-line"><hr></div>
            <div class="t-h-item">
                <span class="t-h-item-title">Under Draft Reviewed By & Date</span>
                <span class="t-h-item-circle circle"></span>
                @if($compliance->draft_reviewed_by)
                <div>
                    @if($compliance->draftReviewedBy
                        && $compliance->draftReviewedBy->profile_picture
                        && File::exists(public_path('/storage/profile/'. $compliance->draftReviewedBy->profile_picture)))
                        <img class="img-avatar mr-3"
                             src="{{ public_path('/storage/profile/'. $compliance->draftReviewedBy->profile_picture) }}">
                    @else
                        <img class="img-avatar mr-3" alt="No picture"
                             src="{{ public_path('/images/avatar10.jpg')}}" />
                    @endif
                    <span class="o-info">
                        <span>{{ $compliance->draftReviewedBy ? $compliance->draftReviewedBy->name : '-' }}</span>
                        <span>{{ $compliance->draft_reviewed_at ? 'on ' . date('d-m-Y', strtotime($compliance->draft_reviewed_at)) : '-' }}</span>
                    </span>
                </div>
                @elseif(strpos($compliance->review_evaluation_date, '00:00:00') !== false)
                    <div>
                        @if($compliance->reviewedEvaluationByCompliance
                            && $compliance->reviewedEvaluationByCompliance->profile_picture
                            && File::exists(public_path('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture)))
                            <img class="img-avatar mr-3"
                                src="{{ public_path('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span class="o-info">
                            <span>{{ $compliance->reviewedEvaluationByCompliance ? $compliance->reviewedEvaluationByCompliance->name : '-' }}</span>
                            <span>{{ $compliance->review_evaluation_date ? 'on ' . date('d-m-Y', strtotime($compliance->review_evaluation_date)) : '-' }}</span>
                        </span>
                    </div>
                @else
                    <div>
                        @php 
                            if($oldReviewDetails){
                                $compliance->reviewedEvaluationByCompliance = $oldReviewDetails->periodicEvaluationBy;
                                $compliance->review_evaluation_date = $oldReviewDetails->periodic_evaluation_at;
                            }else{
                                $compliance->reviewedEvaluationByCompliance = null;
                                $compliance->review_evaluation_date = null;
                            }
                        @endphp
                        @if($compliance->reviewedEvaluationByCompliance
                            && $compliance->reviewedEvaluationByCompliance->profile_picture
                            && File::exists(public_path('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture)))
                            <img class="img-avatar mr-3"
                                src="{{ public_path('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span class="o-info">
                            <span>{{ $compliance->reviewedEvaluationByCompliance ? $compliance->reviewedEvaluationByCompliance->name : '-' }}</span>
                            <span>{{ $compliance->review_evaluation_date ? 'on ' . date('d-m-Y', strtotime($compliance->review_evaluation_date)) : '-' }}</span>
                        </span>
                    </div>
                @endif
            </div>
            <div style="margin-right:30px !important;">
                <label style="text-align: center; font-weight: bold; display: block">Periodic Evaluation History</label>
                <table class="rootcause-table" style="margin-top: 20px; padding-bottom: 20px">
                    <thead>
                        <tr>
                            <th style="width: 5%">Sl. No.</th>
                            <th style="width:65%;">Description</th>
                            <th style="width: 15%">Date</th>
                            <th style="width: 15%">Periodic Evaluation By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($histories))
                            @foreach($histories as $index => $history)
                            @php 
                                $oldStatusClass = $newStatusClass = $oldStatus = $newStatus = '';

                                // Old status 
                                if(in_array($history->old_status, $statusArray)){
                                    $oldStatus = 'Draft Under Review';
                                    $oldStatusClass = 'status-gray';
                                }elseif ($history->old_status == "Complied"){
                                    $oldStatus = 'Evaluation Completed';
                                    $oldStatusClass = 'status-success';
                                }elseif ($history->old_status == "Not Complied"){
                                    $oldStatus = 'Not Complied';
                                    $oldStatusClass = 'status-red';
                                }elseif ($history->old_status == "Partially Complied"){
                                    $oldStatus = 'Partially Complied';
                                    $oldStatusClass = 'status-blue';
                                }elseif($history->old_status=='Awaiting Periodic Evaluation'){
                                    $oldStatus = 'Awaiting Periodic Evaluation';
                                    $oldStatusClass = 'status-warning';
                                }elseif($history->old_status=='Periodic Evaluation On Due'){
                                    $oldStatus = 'Periodic Evaluation On Due';
                                    $oldStatusClass = 'status-red';
                                }
                                //new status
                                if(in_array($history->new_status, $statusArray)){
                                    $newStatus = 'Draft Under Review';
                                    $newStatusClass = 'status-gray';
                                }elseif ($history->new_status == "Complied"){
                                    $newStatus = 'Evaluation Completed';
                                    $newStatusClass = 'status-success';
                                }elseif ($history->new_status == "Not Complied"){
                                    $newStatus = 'Not Complied';
                                    $newStatusClass = 'status-red';
                                }elseif ($history->new_status == "Partially Complied"){
                                    $newStatus = 'Partially Complied';
                                    $newStatusClass = 'status-blue';
                                }elseif($history->new_status=='Awaiting Periodic Evaluation'){
                                    $newStatus = 'Awaiting Periodic Evaluation';
                                    $newStatusClass = 'status-warning';
                                }elseif($history->new_status=='Periodic Evaluation On Due'){
                                    $newStatus = 'Periodic Evaluation On Due';
                                    $newStatusClass = 'status-red';
                                }
                            @endphp
                            <tr style="font-size:12px;" class="text-center">
                                <td>{{$index+1}}</td>
                                <td class="text-center">
                                    <span style="font-size:12px !important;" class="status {{$oldStatusClass}}">{{$oldStatus}}</span>
                                    &nbsp;
                                    <span>to</span>
                                    &nbsp;
                                    <span style="font-size:12px !important;" class="status {{$newStatusClass}}">{{$newStatus}}</span>
                                </td>
                                <td class="text-center">{{date('d-m-Y H:i:s',strtotime($history->periodic_evaluation_at))}}</td>
                                <td class="text-danger font-weight-bold">{{$history->periodicEvaluationBy->name??$history->periodic_evaluation_by}}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr style="font-size:12px;">
                                <td colspan="4" class="text-danger">No matching records found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>