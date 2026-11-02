@extends('layouts.multiselect')
@section('content')
    <link rel="stylesheet" href="{{ versionedCache('css/user/opportunity/detail-page.css') }}">
    <!-- Page Content -->
    <div class="content px-0" style="-webkit-print-color-adjust: exact">
        <!-- Horizontal -->
        <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
            <div class="d-flex justify-content-between align-items-center d-block-643" style="background: #F5F5F5;">
                <div class="d-flex d-flex-left p-2 align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm bg-black mr-4 rounded-0" style="border: 1px solid">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <span class="mr-2">Opportunity</span>
                    <span style="font-size: 12px; color: grey; padding-top: 0.2rem;">{{ $opportunity->oppname }}</span>
                </div>
                <div class="d-flex-right p-2 d-flex d-block">
                    <div class="d-flex mr-4 border-right">
                        @if($opportunity->byresponsibility && File::exists(public_path('/storage/profile/'.$opportunity->byresponsibility->profile_picture)))
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$opportunity->byresponsibility->profile_picture) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src=" {{asset('/images/avatar10.jpg')}}" />
                        @endif

                        @if($opportunity->postedByOpportunity && File::exists(public_path('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture)))
                            <img class="img-avatar img-avatar48 mr-3" src="{{ asset('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture) }}">
                        @else
                            <img class="img-avatar img-avatar48 mr-3" src=" {{asset('/images/avatar10.jpg')}}" />
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
                                <a class="dropdown-item" href="{{ route('opportunity.detail.pdf', $opportunity->id) }}">PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 px-lg-5 px-4">
                <!-- Overview -->
                <div class="col-lg-5 col-md-6 col-sm-12 mb-3">
                    <div class="px-3 pb-3 h-100"
                        style="background:#F6F7F9; border-radius:20px; font-size:0.75rem;">
                        <label class="mt-2 ml-2">
                            <i class="fa fa-eye mr-2 p-1"
                            style="background:#2529453D;border-radius:5px;"></i>
                            Overview
                        </label>

                        <div class="d-flex flex-wrap">

                            <span class="status status-red mr-2 mt-3 px-3 py-2"
                                style="border-radius:0.5rem; display:inline-flex; align-items:center;">
                                <img src="{{ asset('images/icons/vpn_key.png') }}"
                                    style="width:16px"
                                    alt="issueid_icon">
                                <span class="ml-2">#{{ $opportunity->dep_id }}</span>
                            </span>

                            @if($opportunity->dept)
                                <span class="status status-blue mr-2 mt-3 px-3 py-2"
                                    style="border-radius:0.5rem; display:inline-flex; align-items:center;">
                                    <img src="{{ asset('images/icons/department-icon.png') }}"
                                        style="width:16px;border-radius:5px;"
                                        alt="department_icon">
                                    <span class="ml-2">{{ $opportunity->dept->departmentname }}</span>
                                </span>
                            @endif

                            @if($opportunity->division_id != 0 && $opportunity->division)
                                <span class="status status-blue mr-2 mt-3 px-3 py-2"
                                    style="border-radius:0.5rem; display:inline-flex; align-items:center;">
                                    <img src="{{ asset('images/icons/division-subdivision-icon.png') }}"
                                        style="width:16px;border-radius:5px;"
                                        alt="division_icon">
                                    <span class="ml-2">{{ $opportunity->division->name }}</span>
                                </span>
                            @endif

                            @if($opportunity->area_id != 0 && $opportunity->area)
                                <span class="status status-blue mr-2 mt-3 px-3 py-2"
                                    style="border-radius:0.5rem; display:inline-flex; align-items:center;">
                                    <img src="{{ asset('images/icons/division-subdivision-icon.png') }}"
                                        style="width:16px;border-radius:5px;"
                                        alt="area_icon">
                                    <span class="ml-2">{{ $opportunity->area->name }}</span>
                                </span>
                            @endif

                            @if($opportunity->ohs == 'Yes')
                                <span class="status status-warning mr-2 mt-3 px-3 py-2"
                                    style="border-radius:0.5rem; display:inline-flex; align-items:center; white-space:normal;">
                                    <img src="{{ asset('images/icons/outofscope.png') }}"
                                        style="width:16px;border-radius:5px; flex-shrink:0;"
                                        alt="ohs_icon">
                                    <span class="ml-2">Within OHS Scope</span>
                                </span>
                            @else
                                <span class="status status-success mr-2 mt-3 px-3 py-2"
                                    style="border-radius:0.5rem; display:inline-flex; align-items:center; white-space:normal;">
                                    <img src="{{ asset('images/icons/outsideohs.png') }}"
                                        style="width:16px;border-radius:5px; flex-shrink:0;"
                                        alt="ohs_icon">
                                    <span class="ml-2">Outside OHS Scope</span>
                                </span>
                            @endif

                            @if($opportunity->typeRelation && count($opportunity->typeRelation))
                                @foreach($opportunity->typeRelation as $typeRelation)
                                    <span class="status status-flat mr-2 mt-3 px-3 py-2"
                                        style="border-radius:0.5rem;
                                                display:inline-flex;
                                                align-items:center;">
                                        <img src="{{ asset('images/icons/opptype.png') }}"
                                            style="width:16px;border-radius:5px;"
                                            alt="type_icon">
                                        <span class="ml-2">{{ $typeRelation->name }}</span>
                                    </span>
                                @endforeach
                            @endif

                        </div>

                        <div class="mt-4 text-right">
                            <label style="font-size:12px;">
                                <span style="color:gray;">Added by:</span>
                                <span style="">
                                    {{ optional($opportunity->postedByOpportunity)->name }}
                                </span>
                            </label>
                        </div>

                    </div>
                </div>
                <!-- Process Stage -->
                <div class="col-lg-7 col-md-6 col-sm-12 mb-3">
                    <div class="px-4 h-100"
                        style="background:#F6F7F9; border-radius:20px; font-size:0.75rem;"
                        id="processStage">
                        {!! $processStage !!}
                    </div>
                </div>
            </div>
            <div class="row mt-2 px-lg-5 px-3 py-3 mx-lg-5 mx-4" style="background:#F6F7F9;border-radius:20px;font-size:.75rem;">
                <!-- Heading -->
                <div class="col-12 mb-3" style="margin-left:-35px;" id="detailDiv">
                    <label class="mb-0">
                        <i class="fa fa-clipboard-list mr-2 px-2 py-1"
                        style="background:#2529453D;border-radius:5px;"></i>
                        Details
                    </label>
                </div>
                <!-- LEFT SECTION -->
                <div class="col-lg-8 col-md-7 col-12 mb-4">
                    <label class="font-weight-bold">
                        {{ $opportunity->oppname }}
                    </label>
                    <p class="text-justify"
                    style="word-break:break-word;overflow-wrap:break-word;">
                        {!! nl2br(strip_tags($opportunity->oppdes)) !!}
                    </p>
                    <label class="font-weight-bold">
                        Action
                    </label>
                    @if($opportunity->action)
                        <p class="text-justify"
                        style="word-break:break-word;overflow-wrap:break-word;">
                            {!! nl2br(strip_tags($opportunity->action)) !!}
                        </p>
                    @else
                        <p class="text-secondary">
                            No details provided
                        </p>
                    @endif
                </div>
                <!-- RIGHT SECTION -->
                <div class="col-lg-4 col-md-5 col-12 mb-4">
                    <label class="font-weight-bold">Responsibility</label>
                    <div class="mt-2">
                        @if($opportunity->byresponsibility && $opportunity->byresponsibility->profile_picture &&
                            File::exists(public_path('/storage/profile/'.$opportunity->byresponsibility->profile_picture)))
                            <img class="img-avatar img-avatar48" src="{{ asset('/storage/profile/'.$opportunity->byresponsibility->profile_picture) }}"
                                alt="{{ $opportunity->byresponsibility->name }}">
                        @else
                            <img class="img-avatar img-avatar48" src="{{ asset('/images/avatar10.jpg') }}" alt="No Picture">
                        @endif
                        <p class="mt-2 mb-2 {{ $opportunity->byresponsibility && $opportunity->byresponsibility->name ? 'font-weight-bold' : 'text-secondary' }}">
                            {{ $opportunity->byresponsibility && $opportunity->byresponsibility->name
                                ? $opportunity->byresponsibility->name
                                : 'Responsibility not assigned yet' }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center">
                            @if($opportunity->dept)
                                <div class="mr-2 mb-2 p-2" style="background:#AF6F1E3D;border-radius:5px;">
                                    <img src="{{ asset('images/icons/quality_assurance_icon.png') }}" class="mr-2" style="width:22px;height:22px;">
                                    <span class="font-weight-bold" style="color:#AF6F1E;">
                                        {{ $opportunity->dept->departmentname }}
                                    </span>
                                </div>
                            @endif
                            @if($opportunity->tardate)
                                <div class="mb-2 p-2"
                                    style="background:#44B3AB3D;border-radius:5px;">
                                    <img src="{{ asset('images/icons/calendar_corrective.png') }}"
                                        class="mr-2"
                                        style="width:22px;height:22px;">
                                    <span class="font-weight-bold" style="color:#44B3AB;">
                                        {{ date('d-m-Y', strtotime($opportunity->tardate)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Likelihood / Benefit / Impact -->
                <div class="col-lg-8 col-12 mb-4">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                            <label>Likelihood</label><br>
                            @if($opportunity->likelihood=="High")
                                <span class="status status-red">{{ $opportunity->likelihood }}</span>
                            @elseif($opportunity->likelihood=="Moderate")
                                <span class="status status-warning">{{ $opportunity->likelihood }}</span>
                            @else
                                <span class="status status-success">{{ $opportunity->likelihood }}</span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                            <label>Benefit</label><br>
                            @if($opportunity->benefit=="High")
                                <span class="status status-red">{{ $opportunity->benefit }}</span>
                            @elseif($opportunity->benefit=="Moderate")
                                <span class="status status-warning">{{ $opportunity->benefit }}</span>
                            @else
                                <span class="status status-success">{{ $opportunity->benefit }}</span>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12 mb-3">
                            <label>Positive Impact</label><br>
                            @if($opportunity->impact=="High")
                                <span class="status status-red">{{ $opportunity->impact }}</span>
                            @elseif($opportunity->impact=="Moderate")
                                <span class="status status-warning">{{ $opportunity->impact }}</span>
                            @else
                                <span class="status status-success">{{ $opportunity->impact }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Empty right column to align with desktop -->
                <div class="col-lg-4 d-none d-lg-block"></div>
                <!-- Attachments -->
                <div class="col-lg-8 col-12 mb-3">
                    {!! $attachments !!}
                </div>
                <!-- Risk -->
                <div class="col-lg-4 col-12 mb-3">
                    <label class="font-weight-bold">Risk ID</label>
                    @if($opportunity->risk)
                        <div class="mt-2">
                            <a href="{{ route('risk.detail',$opportunity->risk_id) }}" target="_blank" data-toggle="tooltip" title="View Risk Details">
                                <span class="status status-blue">{{ $opportunity->risk->grp_id }}</span>
                            </a>
                        </div>
                    @else
                        <p class="text-secondary mb-0">No risk added</p>
                    @endif
                </div>
            </div>
            <div class="mt-4 px-5 d-block row px-xs-adjust timelinediv">
                <div class="timeline-card">
                    <label class="mt-3 ml-3 timeline-title">
                        <i class="fa fa-chart-line mr-2 px-2 py-1"
                        style="background:#2529453D;border-radius:5px;"></i>
                        Timeline & Review
                    </label>
                    <div class="timeline-wrapper">
                        <div class="timeline-left">
                            <div class="timeline-label">Added By & Date</div>
                            <div class="timeline-label">Approved By & Date</div>
                            <div class="timeline-label">Closure Reviewed By & Date</div>
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
                                @if($opportunity->postedByOpportunity && $opportunity->postedByOpportunity->profile_picture && File::exists(public_path('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture)))
                                    <img class="timeline-avatar"
                                        src="{{ asset('/storage/profile/'.$opportunity->postedByOpportunity->profile_picture) }}">
                                @else
                                    <img class="timeline-avatar"
                                        src="{{ asset('/images/avatar10.jpg') }}">
                                @endif
                                <div>
                                    <div class="timeline-name" style="font-size:12px;">
                                        {{ optional($opportunity->postedByOpportunity)->name ?? 'Not Specified' }}
                                    </div>

                                    <div class="timeline-date" style="font-size:12px;">
                                        on {{ $opportunity->createdat ? date('d-m-Y',strtotime($opportunity->createdat)) : '-' }}
                                    </div>
                                </div>
                            </div>
                            <!-- Second -->
                            <div class="timeline-user">
                                @if($opportunity->is_reviewed)
                                    @if($opportunity->opportunityapprover && $opportunity->opportunityapprover->profile_picture && File::exists(public_path('/storage/profile/'.$opportunity->opportunityapprover->profile_picture)))
                                        <img class="timeline-avatar"
                                            src="{{ asset('/storage/profile/'.$opportunity->opportunityapprover->profile_picture) }}">
                                    @else
                                        <img class="timeline-avatar"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    @endif
                                    <div>
                                        <div class="timeline-name" style="font-size:12px;">
                                            {{ optional($opportunity->opportunityapprover)->name ?? 'Not Specified' }}
                                        </div>

                                        <div class="timeline-date" style="font-size:12px;">
                                            {{ $opportunity->reviewdate ? 'on '.date('d-m-Y',strtotime($opportunity->reviewdate)) : '' }}
                                        </div>
                                    </div>
                                @else 
                                    <img class="timeline-avatar"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    <div>
                                        <div class="timeline-name" style="font-size:12px;">
                                            Pending Review
                                        </div>

                                        <div class="timeline-date" style="font-size:12px;">
                                            
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- Third -->
                            <div class="timeline-user">
                                @if($opportunity->is_closed)
                                    @if($opportunity->closurereviewer && $opportunity->closurereviewer->profile_picture && File::exists(public_path('/storage/profile/'.$opportunity->closurereviewer->profile_picture)))
                                        <img class="timeline-avatar"
                                            src="{{ asset('/storage/profile/'.$opportunity->closurereviewer->profile_picture) }}">
                                    @else
                                        <img class="timeline-avatar"
                                            src="{{ asset('/images/avatar10.jpg') }}">
                                    @endif
                                    <div>
                                        <div class="timeline-name" style="font-size:12px;">{{ optional($opportunity->closurereviewer)->name ?? 'Not Specified' }}</div>
                                        <div class="timeline-date" style="font-size:12px;">
                                            {{ $opportunity->closed_at ? 'on '.date('d-m-Y',strtotime($opportunity->closed_at)) : '' }}
                                        </div>
                                    </div>
                                @else
                                    <img class="timeline-avatar"
                                        src="{{ asset('/images/avatar10.jpg') }}">
                                    <div>
                                        <div class="timeline-name" style="font-size:12px;">
                                            Pending Closure Review
                                        </div>
                                        <div class="timeline-date" style="font-size:12px;">
                                            
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 px-5 d-block row pb-4 mx-1 px-xs-adjust user-interaction-div">
                <div style="font-size: 0.75rem; background: #F6F7F9;border-radius:1rem;padding-bottom: 1rem">
                    <label class="mt-3 ml-4"><img src="https://cdn.iconscout.com/icon/free/png-256/free-forum-1779761-1513575.png" class="mr-2 px-2 py-1" style="background: #2529453D;border-radius: 5px; width: 30px"> User Interaction</label>
                    @if($opportunity->status!='Review Pending')
                    <div class="d-block mt-4 ml-6">
                        <label>Disapproved Reason</label>
                        @if($opportunity->reason)
                            <p>{{ $opportunity->reason }}</p>
                        @else
                            <p class="text-secondary fw-500">No remarks added</p>
                        @endif
                    </div>
                    @endif
                    @if($opportunity->is_closed)
                    <div class="d-block mt-4 ml-6">
                        <label>Closure Review Remarks</label>
                        @if($opportunity->ndareason)
                            <p>{{ $opportunity->ndareason }}</p>
                        @else
                            <p class="text-secondary fw-500">No closure remarks added</p>
                        @endif
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
    </div>
    <!-- END Page Content -->
@endsection
