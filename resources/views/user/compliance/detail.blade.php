@extends('layouts.multiselect')

@section('content')
    <style>
        .content {
            width: 92%;
        }
        .fx-12px {
            font-size: 12px;
        }
        .header-block {
            background: #F6F7F9;
            box-shadow: 0px 2px 14px 0px #00000024;
            border-radius: 8px;
        }
        .btn-export:focus {
            box-shadow: none !important;
        }
        .overview-block,
        .status-timeline-block,
        .detail-block,
        .th-block,
        .print-block {
            margin-left: 8vw;
            margin-right: 8vw;
        }
        /*------ Overview -----------*/
        .overview-block {
            background: #F6F7F9;
            border-radius: 10px;
            padding-bottom: 20px;
        }
        .overview-block .icon {
            background: #2529453D;
            padding: 6px;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .overview-block .c-r-l-block,
        .overview-block .v-a-d-block {
            display: flex;
        }
        @media only screen and (max-width: 878px) {
            .overview-block .c-r-l-block,
            .overview-block .v-a-d-block {
                display: block;
            }
        }
        .overview-block .o-info-block {
            border-radius: 10px;
            padding: 12px 22px 12px 12px;
            margin-right: 1rem;
            background: white;
            margin-bottom: 10px;
        }
        .overview-block .o-info-block.compliance-id {
            min-width: 146px;
        }
        .overview-block .o-info-block.regulatory-authority {
            min-width: 25vw;
        }
        .overview-block .o-info-block.legislative-issue {
            min-width: 20vw;
        }
        .overview-block .o-info-block label {
            font-size: 12px;
        }
        .overview-block .o-info-block div {
            margin-top: .5rem;
            display: flex;
            align-items: center;
        }
        .overview-block .o-info-block div img {
            padding: 4px;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .overview-block .o-info-block.compliance-id div img {
            background: #25499C3D;
        }
        .overview-block .o-info-block.regulatory-authority div img {
            background: #1760443D;
        }
        .overview-block .o-info-block.legislative-issue div img {
            background: #EE9F273D;
        }
        .overview-block .o-info-block.v-y-d div img {
            background: #44B3AB3D;
        }
        .overview-block .o-info-block.a-o-r div img {
            background: #AF1E1E3D;
        }
        .overview-block .o-info-block.dp div img {
            background: #30C78D3D;
        }
        .overview-block .o-info-block div span {
            font-size: 1vw;
            font-weight: bolder;
            padding-right: 20px;
        }
        @media only screen and (max-width: 878px) {
            .overview-block .o-info-block div span {
                font-size: 16px;
            }
        }
        /*------ END: Overview -----------*/
        /*------ Status & Timeline -----------*/
        .status {
            display: inline-block;
            font-size: 11px;
            padding: 7px 5px 5px 7px;
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
        .status-timeline-block {
            background: #F6F7F9;
            border-radius: 10px;
            padding-bottom: 20px;
        }
        .status-timeline-block .icon {
            background: #2529453D;
            padding: 6px;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .status-timeline-block .compliance-status {
            background: white;
            padding: 12px 22px 42px 12px;
            border-radius: 8px;
        }
        .status-timeline-block .compliance-status label {
            font-size: 14px;
        }
        .process-stage-point {
            position: relative;
        }
        .process-stage-point span {
            background: #CFCFCF;
            position: relative;
            margin-right: calc(9vw - 27px);
            z-index: 2;
        }
        @media only screen and (max-width: 878px) {
            .overview-block .o-info-block div span {
                font-size: 16px;
            }
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
        .process-stage-point:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 35%;
            left: 9%;
            width: 100%;
            height: 2px;
            background: #000000;
            z-index: 1;
        }
        .process-stage-point span::after {
            content: attr(data-content);
            font-size: 12px;
        }
        .process-stage-point .s-c-ur::after {
            content: "Periodic Evaluation On Due";
            white-space: pre;
        }
        .process-stage-point .s-pc-ur::after {
            content: "Awaiting Periodic Evaluation";
            white-space: pre;
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
        #ape:hover{
            background-color: #af6f1e !important;
        }
        #prod:hover{
            background-color: #af1f1e !important;
        }
        #ec:hover{
            background-color: #78a003 !important;
        }
        #dur:hover{
            background-color:#403a3a !important;;
        }
        #pc:hover{
            background-color: #25499c !important;
        }
        #nc:hover{
            background-color: #af1f1e !important;
        }
        /*------ END: Status & Timeline -----------*/
        /*------ Detail -----------*/
        .detail-block {
            background: #F6F7F9;
            border-radius: 10px;
            padding-bottom: 20px;
        }
        .detail-block .icon {
            background: #2529453D;
            padding: 6px;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .detail-block .re-ow,
        .detail-block .rr-fr,
        .detail-block .vt-at {
            display: flex;
        }
        /*  Requirement / Owner */
        .detail-block .re-ow div label:first-child {
            font-size: 14px;
        }
        .detail-block .re-ow div.re {
            width: 60%;
        }
        .detail-block .re-ow div.re div {
            width: 100%;
            display: block;
            font-size: 12px;
        }
        .detail-block .re-ow div.ow {
            width: 30%;
        }
        .detail-block .re-ow div.ow div {
            width: 100%;
        }
        .detail-block .re-ow div.ow div span {
            font-size: 14px;
            font-weight: bold;
        }
        /*  Related Risk / Frequency */
       
        .detail-block .rr-fr div.rr label:first-child,
        .detail-block .rr-fr div.fr label:first-child {
            font-size: 14px;
        }
        .detail-block .rr-fr div.rr {
            width: 60%;
        }
        .detail-block .rr-fr div.rr div span {
            padding: 10px 22px;
            font-size: 12px;
            margin-right: 10px;
        }
        .detail-block .rr-fr div.fr {
            width: 30%;
        }
        .detail-block .rr-fr div.fr div {
            padding: 12px 22px;
            background: white;
            max-width: 170px;
            border-radius: 8px;
        }
        .detail-block .rr-fr div.fr .calendar-icon {
            background: #2529453D;
            padding: 6px;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .detail-block .rr-fr div.fr div span {
            font-size: 12px;
            font-weight: bold;
        }
        /*  Visibility To / Attachments */
        .detail-block .vt-at div.vt label:first-child,
        .detail-block .vt-at div.at label:first-child {
            font-size: 14px;
        }
        .detail-block .vt-at div.vt {
            width: 60%;
        }
        .detail-block .vt-at div.vt div div {
            padding: 8px 24px;
            border-radius: 8px;
            background: #343A40;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .detail-block .vt-at div.vt div img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
        .detail-block .vt-at div.vt div span {
            font-size: 14px;
            color: white;
            font-weight: 600;
            margin-left: 5px;
        }
        .detail-block .vt-at div.at {
            width: 30%;
        }
        .detail-block .vt-at div.at a {
            display: block;
            padding: 4px 10px;
            background: #1760443D;
            border-radius: 4px;
            margin-bottom: 10px;
            width: 90%;
            word-wrap: break-word;
        }
        .detail-block .vt-at div.at a img {
            width: 20px;
            height: 20px;
        }
        .detail-block .vt-at div.at a span {
            color: #176044;
            font-size: 12px;
            font-weight: 600;
        }
        /* License & Permit ID */
        .detail-block .lpi label {
            font-size: 14px;
        }
        .detail-block .lpi div span {
            padding: 4px 10px;
            border-radius: 4px;
            margin-right: 10px;
            font-size: 12px;
            font-weight: 600;
            background: #7FAB4C3D;
            color: #176044;
        }
        @media only screen and (max-width: 595px) {
            .detail-block .re-ow,
            .detail-block .rr-fr,
            .detail-block .vt-at {
                display: block;
            }
            .detail-block .re-ow div.re,
            .detail-block .re-ow div.ow,
            .detail-block .rr-fr div.rr,
            .detail-block .rr-fr div.fr,
            .detail-block .vt-at div.vt,
            .detail-block .vt-at div.at {
                width: 100%;
                margin-top: 1vw;
                margin-bottom: 1vw;
            }
        }
        /*------ END: Detail -----------*/
        /*------ Timeline & History -----------*/
        .th-block {
            background: #F6F7F9;
            border-radius: 10px;
            padding-bottom: 20px;
        }
        .th-block .icon {
            background: #2529453D;
            padding: 6px;
            width: 24px;
            height: 24px;
            border-radius: 5px;
            margin-right: 10px;
        }
        .th-block .t-p-block {
            padding-left: 3vw;
            padding-right: 3vw;
        }
        .th-block .timeline-point {
            position: relative;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #878787;
            margin-left: 50px;
            margin-right: 50px;
            z-index: 2;
        }
        .th-block .timeline-point.has-after:after {
            content: '';
            position: absolute;
            top: 2.95rem;
            left: -1.55rem;
            width: 4rem;
            height: 2px;
            background: #CFCFCF;
            transform: rotate(90deg);
            z-index: 1;
        }
        .th-block .d-text {
            width: 40%;
        }
        .th-block .d-u-info-block {
            width: 40%;
        }
        .th-block .d-u-info {
            display: flex;
        }
        @media only screen and (max-width: 595px) {
            .th-block .d-text {
                width: 50%;
            }
            .th-block .d-u-info-block {
                width: 50%;
            }
            .th-block .d-u-info {
                margin-left: 2vw;
            }
            .th-block .timeline-point {
                display: none;
            }
        }
        .th-block .d-u-info img {
            color: grey;
            font-size: 1.25rem;
            margin-right: 0.25rem;
            border-radius: 50%;
            width: 32px;
            height: 32px
        }
        .th-block .d-u-info label {
            color: grey;
            font-style: italic;
            display: block;
            margin-bottom: 0;

        }
        /*------ END: Timeline & History -----------*/
        /********************* History Table Style- Start ************/
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
        .table-bordered, .table-bordered td, .table-bordered th {
            border-color: #878787;
        }
        /********************* History Table Style- End ************/
        @media print {
            .table-custom thead tr th {
                background-color: #252945 !important;
            }
            * {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
    <!-- Page Content -->
    <div class="content px-0" style="-webkit-print-color-adjust: exact; background: white; padding-top: 0; margin-top: 1rem; margin-bottom: 30px">
        <!-- Horizontal -->
        <div class="header-block">
            <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 align-items-center">
                    <a href="/user/compliancemgmt"
                       class="btn btn-secondary btn-sm bg-black mr-4 rounded-lg px-4 py-2 border-0">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <label class="mr-2 mb-0">Compliance Management</label>
                </div>
                <div class="d-flex-right p-2 d-flex d-block">
                    <div class="d-flex mr-4 border-right">
                        @if($compliance->postedByCompliance
                            && File::exists(public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture)))
                            <img class="img-avatar img-avatar48 mr-3"
                                 src="{{ asset('/storage/profile/'. $compliance->postedByCompliance->profile_picture) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3"
                                 src=" {{asset('/images/avatar10.jpg')}}" />
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
                                <a class="dropdown-item" href="{{ route('user.compliance.detail.pdf', $compliance->Cid) }}">PDF</a>
                                {{--<a class="dropdown-item" href="javascript:void(0)">Excel</a>--}}
                                {{-- <a class="dropdown-item" href="javascript:void(0)">Word</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Overview -->
        <div class="mt-4 overview-block px-4">
            <div class="py-2 d-flex align-items-center">
                <img alt="visibility" class="icon"
                     src="{{ asset('images/icons/visibility.png') }}">
                <label class="mb-0">Overview</label>
            </div>
            <!-- Compliance ID / Regulatory Authority / Legislative Issue !-->
            <div class="mt-2 c-r-l-block">
                <div class="o-info-block compliance-id">
                    <label>Compliance ID</label>
                    <div>
                        <img alt="label"
                             src="{{ asset('images/icons/label.png') }}">
                        <span>{{ $compliance->auth_id }}</span>
                    </div>
                </div>
                <div class="o-info-block regulatory-authority">
                    <label>Regulatory Authority</label>
                    <div>
                        <img alt="account_balance"
                             src="{{ asset('images/icons/account_balance.png') }}">
                        <span>{{ $compliance->regulatory->name }}</span>
                    </div>
                </div>
                <div class="o-info-block legislative-issue">
                    <label>Legislative Issue</label>
                    <div>
                        <img alt="gavel"
                             src="{{ asset('images/icons/gavel.png') }}">
                        <span>{{ $compliance->legalDocuments->name }}</span>
                    </div>
                </div>
            </div>
            <!-- Version / Year / Dated / Act or Regulation / Department !-->
            <div class="mt-3 pb-3 v-a-d-block">
                <div class="o-info-block v-y-d">
                    <label>Version / Year / Dated</label>
                    <div>
                        <img alt="event"
                             src="{{ asset('images/icons/event.png') }}">
                        <span>{{ $compliance->version }}</span>
                    </div>
                </div>
                <div class="o-info-block a-o-r">
                    <label>Act or Regulation</label>
                    <div>
                        <img alt="library_books"
                             src="{{ asset('images/icons/library_books.png') }}">
                        <span>{{ $compliance->regulation }}</span>
                    </div>
                </div>
                <div class="o-info-block dp">
                    <label>Department</label>
                    <div>
                        <img alt="domain"
                             src="{{ asset('images/icons/domain.png') }}">
                        <span>{{ $compliance->dep ? $compliance->dep->departmentname : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Status & Timeline -->
        @php 
            $statusArray = [
                'Under Review', 'Awaiting Compliance Evaluation', 'Complied, Under Review', 
                'Not Complied, Under Review', 'Partially Complied, Under Review', 
                'Need improvement', 'Compliance Evaluation due'
            ];
        @endphp
        <div class="mt-4 status-timeline-block px-4">
            <div class="py-2 d-flex align-items-center">
                <img alt="visibility" class="icon"
                     src="{{ asset('images/icons/visibility.png') }}">
                <label class="mb-0">Status & Timeline</label>
            </div>
            
            <div class="mt-2 compliance-status">
                <label>Compliance Status</label>
                <div class="d-flex justify-content-center mt-3 process-stage">
                    <!--draft under review -->
                    @if(in_array($compliance->comp_status, $statusArray))
                        <label class="process-stage-point">
                            <span class="rounded-circle d-inline-block p-2 bg-gray"
                                data-content="Draft Under Review"></span>
                        </label>
                    @else
                        <label class="process-stage-point">
                            <span id="dur" class="rounded-circle d-inline-block p-2 hide-action" data-toggle="tooltip" data-title="Draft Under Review"></span>
                        </label>
                    @endif
                    <!-- not complied -->
                    @if(in_array($compliance->comp_status, ['Not Complied']))
                        <label class="process-stage-point">
                            <span class="rounded-circle d-inline-block p-2 bg-dark-red"
                                  data-content="{{ $compliance->comp_status }}"></span>
                        </label>
                    @else
                        <label class="process-stage-point">
                            <span id="nc" class="rounded-circle d-inline-block p-2 hide-action" data-toggle="tooltip" data-title="Not Complied"></span>
                        </label>
                    @endif
                    <!-- Partially Complied -->
                    @if(in_array($compliance->comp_status, [
                            'Partially Complied',
                        ]))
                        <label class="process-stage-point">
                            <span class="rounded-circle d-inline-block p-2 bg-blue"
                                  data-content="{{ $compliance->comp_status }}"></span>
                        </label>
                    @else
                        <label class="process-stage-point">
                            <span id="pc" class="rounded-circle d-inline-block p-2 hide-action" data-toggle="tooltip" data-title="Partially Complied"></span>
                        </label>
                    @endif
                    <!-- complied -->
                    @if(in_array($compliance->comp_status, [
                            'Complied',
                    ]))
                        <label class="process-stage-point">
                            <span class="rounded-circle d-inline-block p-2 bg-dark-green"
                            data-content="Evaluation Completed"></span>
                        </label>
                    @else
                        <label class="process-stage-point">
                            <span id="ec" class="rounded-circle d-inline-block p-2 hide-action" data-toggle="tooltip" data-title="Evaluation Completed"></span>
                        </label>
                    @endif
                    {{-- Awaiting Periodic Evaluation  --}}
                    @if ($compliance->comp_status == 'Awaiting Periodic Evaluation')
                        <label class="process-stage-point">
                            <span class="rounded-circle d-inline-block p-2 bg-brown s-pc-ur"></span>
                        </label>
                    @else
                        <label class="process-stage-point">
                            <span id="ape" class="rounded-circle d-inline-block p-2 hide-action" data-toggle="tooltip" data-title="Awaiting Periodic Evaluation"></span>
                        </label>
                    @endif
                    <!-- Periodic Evalution On Due -->
                    @if(in_array($compliance->comp_status, ['Periodic Evaluation On Due']))
                        <label class="process-stage-point">
                            <span class="rounded-circle d-inline-block p-2 bg-dark-red s-c-ur"></span>
                        </label>
                    @else
                        <label class="process-stage-point">
                            <span id="prod" class="rounded-circle d-inline-block p-2 hide-action" data-toggle="tooltip" data-title="Periodic Evaluation On Due"></span>
                        </label>
                    @endif
                    
                </div>
            </div>
        </div>
        <!-- Detail -->
        <div class="mt-4 detail-block px-4">
            <label class="mt-2 ml-2">
                <img class="icon" alt="assignment"
                     src="{{ asset('images/icons/assignment.png') }}">
                <span>Details</span>
            </label>
            <div class="mt-2">
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
                        <div class="d-flex align-items-center">
                            @if($compliance->postedByCompliance
                                && File::exists(public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture))
                            )
                                <img class="img-avatar img-avatar48 mr-3"
                                     src="{{ asset('/storage/profile/'. $compliance->postedByCompliance->profile_picture) }}">
                            @else
                                <img class="img-avatar img-avatar48 mr-3"
                                     src="{{ asset('/images/avatar10.jpg')}}" />
                            @endif
                            <span>{{ $compliance->postedByCompliance ? $compliance->postedByCompliance->name : '' }}</span>
                        </div>
                    </div>
                </div>
                <!-- Related Risk / Frequency -->
                <div class="mt-4 rr-fr">
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
                        <div class="d-flex align-items-center">
                            <img class="calendar-icon" alt="calendar_month"
                                 src="{{ asset('images/icons/calendar_month.png') }}">
                            <span>{{ $compliance->frequency }}</span>
                        </div>
                    </div>
                </div>
                <!-- Visibility To / Attachments -->
                <div class="mt-4 vt-at">
                    <div class="vt">
                        <label>Visibility To</label>
                        <div class="d-flex flex-wrap">
                            @if(count($visibleToList))
                                @foreach($visibleToList as $item)
                                    <div>
                                        @if(File::exists(public_path('/storage/profile/'. $item->profile_picture)))
                                            <img alt="{{ $item->name }}"
                                                 src="{{ asset('/storage/profile/'. $item->profile_picture) }}">
                                        @else
                                            <img alt="{{ $item->name }}"
                                                 src="{{ asset('/images/avatar10.jpg') }}" />
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
                                <a href="{{ asset('storage/compliance_files/'. $compliance->file) }}" target="_blank">
                                    <img alt="download"
                                         src="{{ asset('images/icons/download_green.png') }}">
                                    <span>{{ $compliance->file }}</span>
                                </a>
                            @else
                                <p class="text-danger fx-12px">No attachments added</p>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- License & Permit ID -->
                <div class="mt-4 lpi">
                    <label>License & Permit ID</label>
                    <div class="d-flex">
                        @if(count($compliance->licensePermits))
                            @foreach($compliance->licensePermits as $item)
                                <span>{{ $item->licensenumber }}</span>
                            @endforeach
                        @else
                            <p class="text-danger fx-12px">No license & permit ID added</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Timeline & History -->
        <div class="mt-4 px-4 d-block px-xs-adjust th-block">
            <label class="mt-2 ml-2">
                <img class="icon" alt="timeline"
                     src="{{ asset('images/icons/timeline.png') }}">
                <span>Timeline & History</span>
            </label>
            <div class="pb-1 d-block" style="font-size: 0.75rem">
                <div class="d-flex justify-content-center align-items-center">
                    <label class="text-right mb-0 d-text">Added By & Date</label>
                    <span class="timeline-point has-after"></span>
                    <div class="d-u-info-block">
                        <div class="d-u-info">
                            @if($compliance->postedByCompliance
                                    && $compliance->postedByCompliance->profile_picture
                                    && File::exists(public_path('/storage/profile/'. $compliance->postedByCompliance->profile_picture)))
                                <img class="img-avatar" alt="{{ $compliance->postedByCompliance->name }}"
                                     src="{{ asset('/storage/profile/'. $compliance->postedByCompliance->profile_picture) }}">
                            @else
                                <img class="img-avatar" alt="Unknown"
                                     src="{{asset('/images/avatar10.jpg')}}" />
                            @endif
                            <div class="d-block">
                                <label style="font-size: 1rem">{{ $compliance->postedByCompliance ? $compliance->postedByCompliance->name : '-' }}</label>
                                <label>{{ $compliance->created_at ? 'on ' . date('d-m-Y', strtotime($compliance->created_at)) : '-' }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <label class="text-right mb-0 d-text">Under Draft Reviewed By & Date</label>
                    <span class="timeline-point"></span>
                    <div class="d-u-info-block">
                        @if($compliance->draft_reviewed_by)
                            <div class="d-u-info">
                                @if($compliance->draftReviewedBy
                                        && $compliance->draftReviewedBy->profile_picture
                                        && File::exists(public_path('/storage/profile/'. $compliance->draftReviewedBy->profile_picture)))
                                    <img class="img-avatar" alt="{{ $compliance->draftReviewedBy->name }}"
                                        src="{{ asset('/storage/profile/'. $compliance->draftReviewedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar" alt="Unknown"
                                        src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block">
                                    <label style="font-size: 1rem">{{ $compliance->draftReviewedBy ? $compliance->draftReviewedBy->name : '-' }}</label>
                                    <label>{{ $compliance->draft_reviewed_at ? 'on ' . date('d-m-Y', strtotime($compliance->draft_reviewed_at)) : '-' }}</label>
                                </div>
                            </div>
                        @elseif(strpos($compliance->review_evaluation_date, '00:00:00') !== false)
                            <div class="d-u-info">
                                @if($compliance->reviewedEvaluationByCompliance
                                        && $compliance->reviewedEvaluationByCompliance->profile_picture
                                        && File::exists(public_path('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture)))
                                    <img class="img-avatar" alt="{{ $compliance->reviewedEvaluationByCompliance->name }}"
                                        src="{{ asset('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture) }}">
                                @else
                                    <img class="img-avatar" alt="Unknown"
                                        src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block">
                                    <label style="font-size: 1rem">{{ $compliance->reviewedEvaluationByCompliance ? $compliance->reviewedEvaluationByCompliance->name : '-' }}</label>
                                    <label>{{ $compliance->review_evaluation_date ? 'on ' . date('d-m-Y', strtotime($compliance->review_evaluation_date)) : '-' }}</label>
                                </div>
                            </div>
                        @else
                            <div class="d-u-info">
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
                                    <img class="img-avatar" alt="{{ $compliance->reviewedEvaluationByCompliance->name }}"
                                        src="{{ asset('/storage/profile/'. $compliance->reviewedEvaluationByCompliance->profile_picture) }}">
                                @else
                                    <img class="img-avatar" alt="Unknown"
                                        src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block">
                                    <label style="font-size: 1rem">{{ $compliance->reviewedEvaluationByCompliance ? $compliance->reviewedEvaluationByCompliance->name : '-' }}</label>
                                    <label>{{ $compliance->review_evaluation_date ? 'on ' . date('d-m-Y', strtotime($compliance->review_evaluation_date)) : '-' }}</label>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center pb-3 mt-4">
                <button class="btn btn-primary btn-toggle-history" value="load"
                        style="box-shadow: 0px 0px 0px 1px #5E5ADB; box-shadow: 0px 1px 1px 0px #0000001A; font-size: 12px">
                    <span><i class="fa fa-eye"></i></span>
                    <span class="ml-1">Periodic Evaluation History</span>
                </button>
            </div>
            <div class="mt-2 col-sm-12 offset-md-1 m-auto table-compliance-history"
                    style="overflow-x:auto; display: none">
                <table class="table table-bordered table-custom">
                    <thead>
                        <tr>
                            <th>Sl. No.</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Periodic Evaluation By</th>
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
                            <tr style="font-size:11px;" class="text-center">
                                <td>{{$index+1}}</td>
                                <td class="text-center">
                                    <span style="font-size:11px !important;" class="status {{$oldStatusClass}}">{{$oldStatus}}</span>
                                    &nbsp;
                                    to
                                    &nbsp;
                                    <span style="font-size:11px !important;" class="status {{$newStatusClass}}">{{$newStatus}}</span>
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
        <!-- Print -->
        <div class="mt-4 pb-5 d-block print-block px-xs-adjust">
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary px-3" onclick="window.print();"
                        style="background: #252945; border-color:#252945; color: white; font-size: 12px">
                    <i class="fa fa-print mr-2"></i> Print
                </button>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
<!-- History toggle button -->
<script>
        $(function () {
            $('.btn-toggle-history').on('click', function () {
                const currentValue = $(this).val();
                $('.table-compliance-history').slideToggle('slow')
                if (currentValue == 'load') {
                    $(this).val('hide');
                    $(this).html('<span><i class="fa fa-eye-slash"></i></span><span class="ml-1">Periodic Evaluation History</span>');
                } else {
                    $(this).val('load');
                    $(this).html('<span><i class="fa fa-eye"></i></span><span class="ml-1">Periodic Evaluation History</span>');
                }
            })
        });
    </script>
@endsection
