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
        .badge-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .bg-success {
            background-color: #30c78d!important;
        }
        .circle {
            /* width: 20px;
            height: 20px;
            border-radius: 10px 10px 10px 10px; */
            width: 10px;
            height: 10px;
            border-radius: 5px 5px 5px 5px;
            display: inline-block;
            background: #CFCFCF;
            position: relative;
            margin-right: 25px;
        }
        .circle.has-after::after {
            content: '';
            position: absolute;
            /* top: 10px;
            left: 20px;
            width: 30px; */
            top: 5px;
            left: 10px;
            width: 30px;
            height: 2px;
            background: #000000;
        }
        .process-stage-point.span-action-reviewed-ok::after {
            content: 'Action Approved';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-awaiting::after {
            content: 'Awaiting actions';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-proposed::after {
            content: 'Actions Proposed';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-hod-review-awaiting::after {
            content: 'HOD Review awaiting';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-hod-rejected::after {
            content: 'HOD Rejected';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-reviewed-not-ok::after {
            content: 'Action Rejected';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-need-more-action::after {
            content: 'Need More Action';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-action-complete::after {
            content: 'Actions Completed';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .process-stage-point.span-closed::after {
            content: 'Closed';
            position: absolute;
            top: 20px;
            left: -20px;
            white-space: nowrap;
        }
        .bg-warning {
            /* background-color: #fbdece; */
            background-color: #ffc107;
        }
        .bg-success {
            /* background-color: #e0edcf; */
            background-color: #28a745;
        }
        .bg-danger {
            /* background-color: #f8d4d4; */
            background-color: #dc3545;
        }
        .bg-primary {
            /* background-color: #d9e2f6; */
              background-color: #007bff;;
        }
        .bg-secondary {
            /* background-color: #E5E4E2; */
            background-color: #6c757d;
        }
        .bg-flat {
            /* background: #bff0dd; */
            background: #17a2b8;
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
    </style>
</head>
<body>
    <div class="container">
        <table class="heading-table">
            <tr>
                <td><label class="page-title">Audit Details</label><span class="issue-title">{{ $issue->title }}</span></td>
                <td class="text-right">
                    @php
                        $resp_user_image = DB::table('users')->where('email',$issue->responsibility)->value('profile_picture');
                        $posted_user_image = DB::table('users')->where('employeeid',$issue->postedby)->value('profile_picture');
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
            <div class="block" style="width: 46%; margin-right: 20px;">
                <p class="p-0 m-0 block-title" style="margin-bottom: 20px;">
                    <img src="{{ public_path('images/icons/eye.png') }}">
                    <span>Overview</span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    <span class="badge badge-red" style="margin-right: 5px">{{ $issue->issueid }}</span>
                    <!-- <span class="badge badge-flat">{{ $issue->category }}</span> -->
                    <span class="badge badge-blue" style="margin-right: 5px">{{ $dept }}</span>
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    @if($division)
                      <span class="badge badge-warning" style="margin-right: 5px">{{ $division }}</span>
                    @endif
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    @if($area)
                      <span class="badge badge-flat" style="margin-right: 5px">{{ $area }}</span>
                    @endif
                </p>
                <p class="m-0" style="margin-left: 25px; margin-bottom: 10px; font-weight: 500">
                    <!-- <span class="badge badge-blue" style="margin-right: 5px">{{ $dept }}</span> -->
                    <span class="badge" style="color:{{ $switch ? $type->text_color : '#25499c'}};
                             background-color:{{ $switch ? $type->background_color : '#d9e2f6'}}">
                        {{ $switch ? $type->name : '' }}
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
                <p class="m-0" style="margin-bottom: 50px; margin-left: 25px; font-weight: 200">
                    <label class="circle has-after @if($issue->processstage == 'Awaiting actions') bg-secondary @endif">
                        <span class="process-stage-point @if($issue->processstage == 'Awaiting actions') span-action-awaiting @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'HOD Review awaiting') bg-warning @endif">
                        <span class="process-stage-point @if($issue->processstage == 'HOD Review awaiting') span-action-hod-review-awaiting @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'HOD Rejected') bg-danger @endif">
                        <span class="process-stage-point @if($issue->processstage == 'HOD Rejected') span-action-hod-rejected @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'Actions Proposed') bg-flat @endif">
                        <span class="process-stage-point @if($issue->processstage == 'Actions Proposed') span-action-proposed @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'Action Reviewed, OK') bg-success @endif">
                        <span class="process-stage-point @if($issue->processstage == 'Action Reviewed, OK') span-action-reviewed-ok @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'Action Reviewed, Not OK') bg-danger @endif">
                        <span class="process-stage-point @if($issue->processstage == 'Action Reviewed, Not OK') span-action-reviewed-not-ok @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'Need More Action') bg-warning @endif">
                        <span class="process-stage-point @if($issue->processstage == 'Need More Action') span-need-more-action @endif"></span>
                    </label>
                    <label class="circle has-after @if($issue->processstage == 'Actions Completed') bg-primary @endif">
                        <span class="process-stage-point @if($issue->processstage == 'Actions Completed') span-action-complete @endif"></span>
                    </label>
                    <label class="circle @if(in_array($issue->status, ['closed', 'closed after due'])) bg-danger @endif">
                        <span class="process-stage-point @if(in_array($issue->status, ['closed', 'closed after due'])) span-closed @endif"></span>
                    </label>
                </p>
                <p class="m-0" style=" margin-left: 25px; font-weight: 500">
                    <span class="d-inline-block" style="float: left">Due Date: {{ date('d-m-Y', strtotime($issue->duedate)) }}</span>
                    <!-- <span class="d-inline-block" style="float: right; margin-right: 20px">Last Reviewed: {{ date('d-m-Y', strtotime($issue->updated_at)) }}</span> -->
                    <span class="d-inline-block" style="float: right; margin-right: 20px">Last Updated: {{ date('d-m-Y', strtotime($issue->updated_at)) }}</span>
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
                <div style="margin-left: 45px;">
                    <div class="d-inline-block" style="padding: 10px; width: 60%; margin-right: 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">{{ $issue->title }}</p>
                        <p class="m-0" style="font-size: 12px; font-weight: 300">{{ $issue->observation }}</p>
                    </div>
                    <div class="d-inline-block" style="padding: 10px; width: 30%; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 25px; font-weight: 500;">Responsibility</p>
                        <div class="m-0" style="font-size: 12px; font-weight: 300">
                            @if($issue->responsible && $issue->responsible->profile_picture && File::exists(public_path('/storage/profile/'.$issue->responsible->profile_picture)))
                                <img class="img-avatar48" style="margin-right: 5px; display: inline-block; vertical-align: middle;"
                                     alt="{{ $issue->responsible->name }}"
                                     src="{{ public_path('/storage/profile/'.$issue->responsible->profile_picture) }}">
                            @else
                                <img class="img-avatar48" alt="No picture"
                                     style="margin-right: 5px; display: inline-block; vertical-align: middle;"
                                     src="{{ public_path('/images/avatar10.jpg')}}" />
                            @endif
                            <span style="display: inline-block; vertical-align: middle;">{{ $issue->responsible ? $issue->responsible->name : '-' }}</span>
                        </div>
                    </div>
                </div>
                @php
                    $totalCharsFromObservationToAgreedAction = strlen($issue->observation) + max(strlen($issue->agreedaction), strlen($issue->correction));
                @endphp
                <div style="padding-top: 20px; margin-left: 53px; margin-bottom: 10px;"
                    class="{{ $totalCharsFromObservationToAgreedAction > 1000 ? 'breakPageBefore' : '' }}">
                    <p style="font-weight: 500;">Agreed Action & Correction</p>
                    <div class="d-inline-block"
                         style="background: white;padding: 10px; width: 40%; margin-right: 10px; border-radius: 10px 10px 10px 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px;font-weight: 500;">Agreed Action</p>
                        <p class="m-0" style="font-weight: 300;">
                            @if($issue->agreedaction)
                                <label>{{ $issue->agreedaction }}</label>
                            @else
                                <label class="text-danger">No agreed action added</label>
                            @endif
                        </p>
                    </div>
                    <div class="d-inline-block" style="background: white; padding: 10px; width: 40%;border-radius: 10px 10px 10px 10px; vertical-align: top">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">Correction</p>
                        <p class="m-0" style="font-weight: 300;">
                            @if($issue->correction)
                                <label>{{ $issue->correction }}</label>
                            @else
                                <label class="text-danger">No correction added</label>
                            @endif
                        </p>
                    </div>
                </div>
                @php
                    $totalCharsFromObservationToRootCause = strlen($issue->observation) + max(strlen($issue->agreedaction), strlen($issue->correction)) + max(strlen($issue->rootcause), strlen($issue->correctiveaction));
                @endphp
                <div style="padding-top: 20px; margin-left: 53px;"
                     class="{{ $totalCharsFromObservationToRootCause > 700 && $totalCharsFromObservationToAgreedAction < 1000 ? 'breakPageBefore' : '' }}">
                    <p style="font-weight: 500;">Root Cause & Corrective Action</p>
                    <div class="d-inline-block" style="background: white; padding: 10px; width: 40%; margin-right: 10px; vertical-align: top; border-radius: 10px 10px 10px 10px">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">Root Cause</p>
                        <p class="m-0" style="font-weight: 300;">
                            @if($issue->rootcause)
                                <label>{{ $issue->rootcause }}</label>
                            @else
                                <label class="text-danger">No root cause added</label>
                            @endif
                        </p>
                    </div>
                    <div class="d-inline-block" style="background: white; padding: 10px; width: 40%; vertical-align: top;  border-radius: 10px 10px 10px 10px">
                        <p class="m-0" style="margin-bottom: 10px; font-weight: 500;">Corrective Action</p>
                        <p class="m-0" style="font-weight: 300;">
                            @if($issue->correctiveaction)
                                <label>{{ $issue->correctiveaction }}</label>
                            @else
                                <label class="text-danger">No corrective action added</label>
                            @endif
                        </p>
                    </div>
                </div>
                <div style="margin-top: 20px">
                    <div style="vertical-align: baseline; width: 100%">
                        <p class="m-0 p-0" style="; margin-bottom: 10px; margin-left: 53px; font-weight: 500;">Potential risks</p>
                        <div style="margin-left: 55px">
                            @if($issue->related_risks)
                                @php
                                    $relatedRisks = json_decode($issue->related_risks, true);
                                @endphp
                                @if(is_array($relatedRisks) || is_object($relatedRisks))
                                    @foreach($relatedRisks as $riskId => $riskItem)
                                        <span class="badge badge-blue" style="background: #25499C; border-color: #25499C; color: white;border-radius: 4px 4px 4px 4px; margin-bottom: 10px;"> {{ $riskItem }} </span><br>
                                    @endforeach
                                @endif
                            @else
                                <label class="text-danger ml-3">No potential risks added</label>
                            @endif
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px">
                    <div style="vertical-align: baseline; width: 100%">
                        <p class="m-0 p-0" style="; margin-bottom: 10px; margin-left: 53px;font-weight: 500;">Attachments</p>
                        <p class="m-0 p-0" style="; margin-bottom: 5px; margin-left: 55px;font-weight: 500;">Attachments of Issues</p>
                        <div style="margin-left: 55px">
                            @if(count($issue_docs_of_one_item) > 0)
                                @foreach($issue_docs_of_one_item as $issue_item)
                                    @if( $issue_item->issue_file)
                                        <span class="badge badge-success"
                                              style="font-size: 0.75rem;color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                            {{ $issue_item->issue_file}}
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
                            @if(count($action_docs_of_one_item) > 0)
                                @foreach($action_docs_of_one_item as $action_item)
                                    @if( $action_item->action_file)
                                        <span class="badge badge-success"
                                              style="color: white; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 10px;">
                                            {{ $action_item->action_file}}
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
                <div style="margin-top: 20px; padding-bottom: 50px">
                    <div style="vertical-align: top; width: 45%; display: inline-block">
                        <p class="m-0 p-0" style="; margin-bottom: 20px; margin-left: 53px;font-weight: 500;">Send Notifications To</p>
                        <div style="margin-left: 55px">
                            @if(count($notificationUsers) > 0)
                                @foreach($notificationUsers as $user)
                                    <div style="display: inline-block; padding: 5px 12px; border-radius: 5px 5px 5px 5px; background: #343A40; color: white">
                                        <img src="{{ public_path('/storage/profile/'.$user->profile_picture) }}"
                                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 24px">
                                        <label style="display: inline-block; vertical-align: middle">{{ $user->name }}</label>
                                    </div>
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
            </div>
        </div>
        @php
            $breakPageBefore = false;
            if ($issue->related_risks
                || ($issue->issue_docs_of_one_item && count($issue_docs_of_one_item) > 1)
                || ($issue->action_docs_of_one_item && count($action_docs_of_one_item) > 1)
                || ($issue->notificationUsers && count($notificationUsers) > 1)
                || ($issue->frequency && count($frequency) > 3)
                || max(strlen($issue->rootcause), strlen($issue->correctiveaction)) > 300
            ) {
                $breakPageBefore = true;
            }
        @endphp
        <div class="my-20" @if($breakPageBefore)style="page-break-before: always"@endif>
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
                        @if($issue->postedBy && $issue->postedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->postedBy->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $issue->postedBy->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$issue->postedBy->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $issue->postedBy ? $issue->postedBy->name : 'Not Specified' }}</span>
                            <br><span> {{ date('d-m-Y', strtotime($issue->created_at)) }}</span>
                        </span>
                    </span>
                    </div>
                    <!-- <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 44px; position: absolute; top: -20px; left: 241px">
                    </div>
                    <div style="margin-bottom: 25px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Last Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($issue->approvedBy && $issue->approvedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->approvedBy->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $issue->approvedBy->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$issue->approvedBy->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $issue->approvedBy ? $issue->approvedBy->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px">on {{ date('d-m-Y', strtotime($issue->updated_at)) }}</span>
                        </span>
                    </span>
                    </div> -->
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 40px; position: absolute; top: -26px; left: 243px">
                    </div>
                    <div style="margin-bottom: 30px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Action Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($issue->reviewedBy && $issue->reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->reviewedBy->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $issue->reviewedBy->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$issue->reviewedBy->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px">{{ $issue->reviewedBy ? $issue->reviewedBy->name : '-' }}</span>
                            <br><span style="font-size: 12px"> {{ $issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)) : '' }}</span>
                        </span>
                    </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 44px; position: absolute; top: -20px; left: 241px">
                    </div>
                    <div style="margin-bottom: 25px; position: relative">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right;font-weight: 500;">Hod Reviewed By & Date</span>
                        <span class="circle circle-line-vertical has-vertical-after" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($issue->hodreviewedBy && $issue->hodreviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->hodreviewedBy->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $issue->hodreviewedBy->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$issue->hodreviewedBy->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px;">{{ $issue->hodreviewedBy ? $issue->hodreviewedBy->name : 'Not Specified' }}</span>
                            <br><span style="font-size: 12px"> {{ $issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)) : '' }}</span>
                        </span>
                    </span>
                    </div>
                    <div style="position: relative;">
                        <hr style="transform: rotate(90deg); width: 33px; position: absolute; top: -26px; left: 247px">
                    </div>
                    <div style="margin-bottom: 20px; padding-bottom: 20px">
                        <span style="display: inline-block; vertical-align: middle; width: 200px; text-align: right; font-weight: 500;">Closure reviewed by and Date</span>
                        <span class="circle circle-line-vertical" style="margin-left: 50px; display: inline-block; vertical-align: middle"></span>
                        <span style="margin-left: 50px; display: inline-block; vertical-align: middle">
                        @if($issue->closedBy && $issue->closedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->closedBy->profile_picture)))
                            <img class="img-avatar mr-3" alt="{{ $issue->closedBy->name }}"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{ public_path('/storage/profile/'.$issue->closedBy->profile_picture) }}">
                        @else
                            <img class="img-avatar mr-3" alt="No picture"
                                 style="display: inline-block; vertical-align: middle; margin-right: 10px; border-radius: 50%; width: 32px; height: 32px"
                                 src="{{public_path('/images/avatar10.jpg')}}" />
                        @endif
                        <span style="display: inline-block; vertical-align: middle; color: grey; font-weight: 500">
                            <span style="font-size: 16px">{{ $issue->closedBy ? $issue->closedBy->name : '-' }}</span>
                            <br><span style="font-size: 12px"> {{ $issue->closed ? date('d-m-Y', strtotime($issue->closed)) : '' }}</span>
                        </span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-20">
            <div style="background: #F6F7F9; border-radius: 10px 10px 10px 10px;">
                <div style="padding: 10px 30px">
                    <div>
                        <img src="{{ public_path('images/icons/conversation.png') }}"
                             style="display: inline-block; vertical-align: middle; margin-right: 5px; width: 15px; height: 15px; padding: 5px; background: #2529453D; border-radius: 5px 5px 5px 5px">
                        <span style="display: inline-block; vertical-align: middle;font-weight: 600">User Interaction</span>
                    </div>
                    <div style="margin-top: 20px; margin-left: 50px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Closure Remarks</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            @if($issue->remarks)
                                {{ $issue->remarks }}
                            @else
                                <span class="text-danger">No remarks added</span>
                            @endif
                        </p>
                    </div>
                    <div style="margin-top: 30px; margin-left: 50px; padding-bottom: 30px">
                        <p class="p-0 m-0" style="margin-bottom: 10px; font-weight: 500">Action Review Remarks</p>
                        <p class="p-0 m-0" style="font-weight: 300">
                            @if($issue->action_remarks)
                                {{ $issue->action_remarks }}
                            @else
                                <span class="text-danger">No action review remarks added</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
