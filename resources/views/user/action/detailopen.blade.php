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
        .status-gray {
            color: #737373;
            background-color: #e6e6e6;
            border-color: #fbf4ea;
        }
        .status-warning {
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
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
        .process-stage-point:last-child span::after {
            left: -1rem;
        }
        .process-stage-point span.hide-action::after {
            display: none;
        }
        .process-stage-point span.span-awaiting-action::after {
            content: 'Awaiting actions';
        }
        .process-stage-point span.span-action-proposed::after {
            content: 'Actions Proposed';
        }
        .process-stage-point span.span-action-hod-review-awaiting::after {
            content: 'HOD Review awaiting';
        }
        .process-stage-point span.span-action-hod-rejected::after {
            content: 'HOD Rejected';
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
        .process-stage-point span.span-closed::after {
            content: 'Closed';
        }
        .process-stage-point:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 35%;
            left: 25%;
            width: 100%;
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

        /* Mobile */
        @media only screen and (max-width: 767px) {
            .content {
                padding: 1rem 1rem 1px !important;
            }
            .d-block-sp {
                display: block !important;
            }

            .mb-2-sp {
                margin-bottom: 1rem !important;
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
                margin-right: 2.5rem !important;
            }
            .process-stage-point:not(:last-child)::after {
                left: 27%;
            }
            .w-change {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
        /* Desktop */
        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            .process-stage-point span.span-action-completed::after {
                left: -5rem;
            }
        }
    </style>
    @php
        $dept = DB::table('departments')->where('id', $issue->department)->value('departmentname');
        $switch = 0;
        $type = App\Type::where('order', $issue->type)->first();
        $switch = $type ? 1 : 0;
        $issue_docs_of_one_item = App\Multipleissue::where('id', $issue->id)->where('issue_file', '!=', 'NULL')->where('issue_file', '!=', null)->get();
        $action_docs_of_one_item = App\Mutlipleaction::where('id', $issue->id)->where('action_file', '!=', 'NULL')->where('action_file', '!=', null)->get();
    @endphp
    <!-- Page Content -->
    <div class="content px-0" style="-webkit-print-color-adjust: exact">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <div class="d-flex justify-content-between align-items-center" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <span class="mr-2">Audit Details</span>
                    <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">{{ $issue->title }}</span>
                </div>
                <div class="d-flex-right p-2 d-flex">
                    <div class="d-flex mr-4 border-right">
                        @php
                            $resp_user_image = DB::table('users')->where('email',$issue->responsibility)->value('profile_picture');
                            $posted_user_image = DB::table('users')->where('employeeid',$issue->postedby)->value('profile_picture');
                        @endphp
                        @if($resp_user_image)
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$resp_user_image) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src="{{asset('/images/avatar10.jpg')}}" />
                        @endif

                        @if($posted_user_image)
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$posted_user_image) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src="{{asset('/images/avatar10.jpg')}}" />
                        @endif
                    </div>
                    <div class="d-flex align-items-center">
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
                                <a class="dropdown-item" href="{{ route('user.actionsystem.exportPDF.detailopen', $issue->id) }}">PDF</a>
{{--                                <a class="dropdown-item" href="javascript:void(0)">Excel</a>--}}
{{--                                <a class="dropdown-item" href="javascript:void(0)">Word</a>--}}
                                <a class="dropdown-item" href="{{ route('user.actionsystem.exportreporttemplate.detailopen', $issue->id) }}">Customized Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-flex justify-content-around d-block-sp">
                <div class="col-md-5 mr-4 pb-3 mb-2-sp" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;">
                    <label class="mt-2 ml-2"><i class="fa fa-eye mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Overview</label>
                    <div class="d-flex" style="flex-flow: wrap">
                        <span class="status status-red mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">{{ $issue->issueid }}</span>
                        <!-- <span class="status status-flat mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">{{ $issue->category }}</span> -->
                        <span class="status status-blue mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">{{ $dept }}</span>
                        @if($division)
                        <span class="status status-warning mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">{{ $division }}</span>
                        @endif
                        @if($area)
                        <span class="status status-flat mr-3 mt-3 px-3 py-2" style="border-radius: 0.5rem">{{ $area }}</span>
                        @endif
                        <span class="status mr-3 mt-3 px-3 py-2"
                              style="border-radius: 0.5rem;
                                color:{{ $switch ? $type->text_color : '#25499c'}};
                                 background-color:{{ $switch ? $type->background_color : '#d9e2f6'}}">
                            {{ $switch ? $type->name : '' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-7" style="background: #F6F7F9; border-radius: 20px; font-size: 0.75rem;">
                    <label class="mt-2 ml-2"><i class="fa fa-chart-line mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Status & Timeline</label>
                    <div class="mt-2">
                        <label>Current Process Stage</label>
                        <div class="d-flex mt-3 ml-3 process-stage">
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-awaiting-action @if($issue->processstage != 'Awaiting actions') hide-action @else bg-secondary @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-action-hod-review-awaiting @if($issue->processstage != 'HOD Review awaiting') hide-action @else bg-warning @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-action-hod-rejected @if($issue->processstage != 'HOD Rejected') hide-action @else bg-danger @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-action-proposed @if($issue->processstage != 'Actions Proposed') hide-action @else bg-flat @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-action-reviewed-ok @if($issue->processstage != 'Action Reviewed, OK') hide-action @else bg-success @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-action-reviewed-not-ok @if($issue->processstage != 'Action Reviewed, Not OK') hide-action @else bg-danger @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-need-more-action @if($issue->processstage != 'Need More Action') hide-action @else bg-warning @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-action-completed @if($issue->processstage != 'Actions Completed') hide-action @else bg-primary @endif"></span>
                            </label>
                            <label class="process-stage-point">
                                <span class="rounded-circle mr-5 d-inline-block p-2 span-closed @if(!in_array($issue->status, ['closed', 'closed after due'])) hide-action @else bg-danger @endif"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <label>Due Date: {{ date('d-m-Y', strtotime($issue->duedate)) }}</label>
                        <!-- <label>Last Reviewed: {{ date('d-m-Y', strtotime($issue->updated_at)) }}</label> -->
                        <label>Last Updated: {{ date('d-m-Y', strtotime($issue->updated_at)) }}</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-flex row">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 20px" class="col-md-12">
                    <label class="mt-3 ml-4"><i class="fa fa-clipboard-list mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Details</label>
                    <div class="mt-4 px-5 d-flex d-block-sp">
                        <div class="observation col-md-7">
                            <label>{{ $issue->title }}</label>
                            <p class="text-justify">{{ $issue->observation }}</p>
                        </div>
                        <div class="responsibility col-md-5 d-flex justify-content-center d-block-sp">
                            <div>
                                <label>Responsibility</label><br>
                                <div class="d-flex align-items-center">
                                    @if($issue->responsible && $issue->responsible->profile_picture)
                                        <img class="img-avatar img-avatar48 mr-3" alt="{{ $issue->responsible->name }}"
                                             src="{{ asset('/storage/profile/'.$issue->responsible->profile_picture) }}">
                                    @else
                                        <img class="img-avatar img-avatar48 mr-3" alt="No picture"
                                             src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                    {{ $issue->responsible ? $issue->responsible->name : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 px-5">
                        <label class="ml-3">Agreed Action & Correction</label>
                        <div class="d-flex ml-3 mr-5 mt-2 d-block-sp">
                            <div class="col-md-6 bg-white mr-4 p-3 w-change mb-2-sp" style="border-radius: 1rem">
                                <label>Agreed Action</label>
                                @if($issue->agreedaction)
                                    <p>{{ $issue->agreedaction }}</p>
                                @else
                                    <p class="text-danger">No agreed action added</p>
                                @endif
                            </div>
                            <div class="col-md-6 bg-white p-3 w-change" style="border-radius: 1rem">
                                <label>Correction</label>
                                @if($issue->correction)
                                    <p>{{ $issue->correction }}</p>
                                @else
                                    <p class="text-danger">No correction added</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 px-5">
                        <label class="ml-3">Root Cause & Corrective Action</label>
                        <div class="d-flex ml-3 mr-5 mt-2 d-block-sp">
                            <div class="col-md-6 bg-white mr-4 p-3 w-change mb-2-sp" style="border-radius: 1rem">
                                <label>Root Cause</label>
                                @if($issue->rootcause)
                                    <p>{{ $issue->rootcause }}</p>
                                @else
                                    <p class="text-danger">No root cause added</p>
                                @endif
                            </div>
                            <div class="col-md-6 bg-white p-3 w-change" style="border-radius: 1rem">
                                <label>Corrective Action</label>
                                @if($issue->correctiveaction)
                                    <p>{{ $issue->correctiveaction }}</p>
                                @else
                                    <p class="text-danger">No corrective action added</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 px-5" style="min-height: 5rem">
                        <label class="ml-3">Potential risks</label>
                        <div class="d-flex">
                            @if($issue->related_risks)
                                @php
                                    $relatedRisks = json_decode($issue->related_risks, true);
                                @endphp
                                @if(is_array($relatedRisks) || is_object($relatedRisks))
                                    @foreach($relatedRisks as $riskId => $riskItem)
                                        <a href="#" class="btn btn-primary btn-sm ml-3"
                                           style="font-size: 0.75rem; background: #25499C; border-color: #25499C; border-radius: 4px"> {{ $riskItem }} </a>
                                    @endforeach
                                @endif
                            @else
                                <label class="text-danger ml-3">No potential risks added</label>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 px-5">
                        <label class="ml-3 mb-3">Attachments</label>
                        <div class="ml-3 mb-4" style="min-height: 2rem">
                            <label>・Attachments of Issues</label>
                            <div class="d-flex" style="flex-flow: wrap">
                                @if(count($issue_docs_of_one_item) > 0)
                                    @php
                                        $hasAttachmentOfIssue = false;
                                    @endphp
                                    @foreach($issue_docs_of_one_item as $issue_item)
                                        @if( $issue_item->issue_file)
                                            @if(File::exists(public_path('/storage/issue_files/'.$issue_item->issue_file)))
                                                <a href="{{asset("storage/issue_files/".$issue_item->issue_file)}}"
                                                   download="{{$issue_item->issue_file}}"
                                                   class="btn btn-success btn-sm mr-3"
                                                   style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                                    {{ $issue_item->issue_file}}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-success btn-sm mr-3"
                                                   style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                                    {{ $issue_item->issue_file}}
                                                </a>
                                            @endif
                                            @php
                                                $hasAttachmentOfIssue = true;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if(!$hasAttachmentOfIssue)
                                        <label class="text-danger ml-2">No attachments of issues added</label>
                                    @endif
                                @else
                                    <label class="text-danger ml-2">No attachments of issues added</label>
                                @endif
                            </div>
                        </div>
                        <div class="ml-3" style="min-height: 2rem">
                            <label>・Attachments of Actions</label>
                            <div class="d-flex" style="flex-flow: wrap">
                                @if(count($action_docs_of_one_item) > 0)
                                    @php
                                        $hasAttachmentOfAction = false;
                                    @endphp
                                    @foreach($action_docs_of_one_item as $action_item)
                                        @if($action_item->action_file)
                                            @if(File::exists(public_path('/storage/action_files/'.$action_item->action_file)))
                                                <a href="{{asset("storage/action_files/".$action_item->action_file)}}"
                                                   download="{{$action_item->action_file}}"
                                                   class="btn btn-success btn-sm mr-3"
                                                   style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                                    {{ $action_item->action_file}}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-success btn-sm mr-3"
                                                   style="font-size: 0.75rem; background: #176044; border-color: #176044; border-radius: 4px; margin-bottom: 1rem;">
                                                    {{ $action_item->action_file}}
                                                </a>
                                            @endif
                                            @php
                                                $hasAttachmentOfAction = true;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if(!$hasAttachmentOfAction)
                                        <label class="text-danger ml-2">No attachments of actions added</label>
                                    @endif
                                @else
                                    <label class="text-danger ml-2">No attachments of actions added</label>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 px-5 d-flex mb-4">
                        <div class="col-md-7">
                            <label class="ml-1">Send Notifications To</label>
                            <div class="d-flex" style="flex-flow: wrap; margin-top: 1rem">
                                @if(count($notificationUsers) > 0)
                                    @foreach($notificationUsers as $user)
                                        <div class="send-notification-item">
                                            <img src="{{ asset('/storage/profile/'.$user->profile_picture) }}">
                                            <label class="text-white m-0">{{ $user->name }}</label>
                                        </div>
                                    @endforeach
                                @else
                                    <label class="text-danger ml-2">No users to send notifications</label>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="ml-3">Email reminder for action</label>
                            @if(count($frequency))
                                @foreach($frequency as $freq)
                                    <div class="d-flex align-items-center mt-2 ml-3">
                                        <i class="fa fa-calendar-alt"
                                           style="padding: 0.5rem; color: grey; font-size: 1.25rem; margin-right: 0.75rem; background: white; border-radius: 50%"></i>
                                        {{ $freq }}
                                    </div>
                                @endforeach
                            @else
                                <br><label class="text-danger ml-3" style="margin-top: 1rem">No email reminder for action</label>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem">
                    <label class="mt-3 ml-4"><i class="fa fa-chart-line mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px;"></i> Timeline & Review</label>
                    <div class="d-flex justify-content-center">
                        <div class="col-md-5">
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Added By & Date</label>
                            <!-- <label class="d-block text-right mb-0" style="margin-top: 3.25rem">Last Reviewed By & Date</label> -->
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Action Reviewed By & Date</label>
                            <label class="d-block text-right mb-0" style="margin-top: 3rem">Hod Reviewed By & Date</label>
                            <label class="d-block text-right mb-0" style="margin-top: 3.25rem">Closure reviewed by and Date</label>
                        </div>
                        <div class="col-md-1" style="padding-left: 2.5rem;">
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin-top: 3rem;"></span>
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin-top: 3rem;"></span>
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin-top: 3rem;"></span>
                            <span class="d-block timeline-point" style="width: 20px; height: 20px; border-radius: 50%; background: #CFCFCF; margin-top: 3rem;"></span>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center" style="margin-top: 2rem;">
                                @if($issue->postedBy && $issue->postedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->postedBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $issue->postedBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'.$issue->postedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $issue->postedBy ? $issue->postedBy->name : 'Not Specified' }}</label><br>
                                    <label> {{ date('d-m-Y', strtotime($issue->created_at)) }}</label>
                                </div>
                            </div>
                            <!-- <div class="d-flex align-items-center mt-3">
                                @if($issue->approvedBy && $issue->approvedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->approvedBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $issue->approvedBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'.$issue->approvedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $issue->approvedBy ? $issue->approvedBy->name : 'Not Specified' }}</label><br>
                                    <label>on {{ date('d-m-Y', strtotime($issue->updated_at)) }}</label>
                                </div>
                            </div> -->
                            <div class="d-flex align-items-center mt-3">
                                @if($issue->reviewedBy && $issue->reviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->reviewedBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $issue->reviewedBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'.$issue->reviewedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $issue->reviewedBy ? $issue->reviewedBy->name : '-' }}</label><br>
                                    <label> {{ $issue->reviewed_by_date ? date('d-m-Y', strtotime($issue->reviewed_by_date)) : '' }}</label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                    @if($issue->hodreviewedBy && $issue->hodreviewedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->hodreviewedBy->profile_picture)))
                                        <img class="img-avatar mr-3" alt="{{ $issue->hodreviewedBy->name }}"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{ asset('/storage/profile/'.$issue->hodreviewedBy->profile_picture) }}">
                                    @else
                                        <img class="img-avatar mr-3" alt="No picture"
                                            style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                            src="{{asset('/images/avatar10.jpg')}}" />
                                    @endif
                                    <div class="d-block" style="color: grey; font-style: italic">
                                        <label style="font-size: 1rem">{{ $issue->hodreviewedBy ? $issue->hodreviewedBy->name : '-' }}</label><br>
                                        <label> {{ $issue->hod_reviewed_by_date ? date('d-m-Y', strtotime($issue->hod_reviewed_by_date)) : '' }}</label>
                                    </div>
                            </div>
                            <div class="d-flex align-items-center mb-3" style="margin-top: 2rem;">
                                @if($issue->closedBy && $issue->closedBy->profile_picture && File::exists(public_path('/storage/profile/'.$issue->closedBy->profile_picture)))
                                    <img class="img-avatar mr-3" alt="{{ $issue->closedBy->name }}"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{ asset('/storage/profile/'.$issue->closedBy->profile_picture) }}">
                                @else
                                    <img class="img-avatar mr-3" alt="No picture"
                                         style="color: grey; font-size: 1.25rem; margin-right: 0.75rem; border-radius: 50%; width: 32px; height: 32px"
                                         src="{{asset('/images/avatar10.jpg')}}" />
                                @endif
                                <div class="d-block" style="color: grey; font-style: italic">
                                    <label style="font-size: 1rem">{{ $issue->closedBy ? $issue->closedBy->name : '-' }}</label><br>
                                    <label> {{ $issue->closed ? date('d-m-Y', strtotime($issue->closed)) : '' }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row pb-4">
                <div style="font-size: 0.75rem; background: #F6F7F9; border-radius: 0.5rem; padding-bottom: 1rem">
                    <label class="mt-3 ml-4"><img src="https://cdn.iconscout.com/icon/free/png-256/free-forum-1779761-1513575.png" class="mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px; width: 30px"> User Interaction</label>
                    <div class="d-block mt-4 ml-6">
                        <label>Closure Remarks</label>
                        @if($issue->remarks)
                            <p>{{ $issue->remarks }}</p>
                        @else
                            <p class="text-danger">No remarks added</p>
                        @endif
                    </div>
                    <div class="d-block mt-4 ml-6">
                        <label>Action Review Remarks</label>
                        @if($issue->action_remarks)
                            <p>{{ $issue->action_remarks }}</p>
                        @else
                            <p class="text-danger">No action review remarks added</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt2 pb-5 px-5 d-block row">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary px-3" onclick="window.print();"
                            style="background: #252945; border-color:#252945; color: white; font-size: 12px">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->
    </div>
    <!-- END Page Content -->
@endsection
