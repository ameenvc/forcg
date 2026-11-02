@extends('pages.datatables')
@section('exportadd')
    <div class="input-group input-group-sm">
        <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
            <i class="fa fa-print  fa-1x" style="color:grey"></i>
        </a>
    </div>
    <div class="input-group input-group-sm">
        <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
            <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
        </button>
        <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
            <li>
                <!--change route as below-->
                <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                    @csrf
                    <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                    <!--change hidden input type id as below-->
                    <input type="hidden" id="tvalue3" name="tvalue3" />
                </form>
            </li>
            <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
            <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>

        </ul>
    </div>
@endsection

@section('content')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <style>
        .select2-results__options,
        .select2 {
            font-size: 13px;
        }
        .blank-star {
            color: #F4F5F6 !important;
            text-shadow: 0 0 1px #000;
        }
        .colored-star {
            color: #e5ae67 !important;
        }
        [data-star] {
            text-align:left;
            font-style:normal;
            display:inline-block;
            position: relative;
            unicode-bidi: bidi-override;
            font-size:20px;
        }
        [data-star]::before {
            display:block;
            content: '★';
            /* color: #eee; */
            color: #C0C0C0;
        }
        [data-star]::after {
            position: absolute;
            top: 0;
            content: '★';
            width: 0;
            color: orange;
            overflow: hidden;
            height: 100%;
            display: flex;
        }

        [data-star^="1.9"]::after{width:38%}
        [data-star^="2"]::after{width:40%}
        [data-star^="2.5"]::after{width:50%}
        [data-star^="2.9"]::after{width:58%}
        [data-star^="3"]::after{width:60%}
        [data-star^="3.8"]::after{width:76%}
        [data-star^="5"]::after{width:100%}

        .headingselect {
            white-space: break-spaces;
        }
        .fa-book-reader {
            background: conic-gradient(from -45deg, #8a2be2 110deg,
            #0047AB 90deg 180deg, #34a853 180deg 270deg,
            #fbbc05 270deg) 73% 55%/150% 150% no-repeat;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }
        .label-invalid {
            color: red;
        }
        .status{
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

        .status-blue{
            color: #25499c;
            background-color: #d9e2f6;
            border-color: #d9e2f6;
        }

        .status-red{
            color: #af1e1e;
            background-color: #f8d4d4;
            border-color: #fbeaea;
        }
        .status-warning{
            color: #af6f1e;
            background-color: #fbdece;
            border-color: #fbf4ea;
        }
        .status-secondary{
            color: #343a40;
            background-color: #E5E4E2;
            border-color: #edf0f2;
        }
        .status-success{
            color: #176044;
            background-color: #e0edcf;
            border-color: #bff0dd;
        }
        .main-img-aud-container {
            position: relative;
            text-align: center;
        }
        .avatar-dot {
            height: 20px;
            width: 20px;
            background-color: #E5E4E2;
            border-radius: 50%;
            line-height:2.5em;
            font-size:8px;        
        }
        .avatar-dropdown {
            position: relative;
            display: inline-block;
        }
        .avatar-dropdown-content {
            display: none;
            position: absolute;
            padding-bottom:15px;
            padding-right:5px;
            background-color: #ffffff;
            min-width: 220px;
            box-shadow:  0px 0px 15px;background-color: #ffffff;
            z-index: 1;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .avatar-dropdown-content a {
            color: black;
            padding-top:12px;
            padding-left:15px;
            text-decoration: none;
            display: block;
            font-size:12px;
        }
        .avatar-dropdown-content a:hover {background-color: #ffffff;}
        .avatar-dropdown:hover .avatar-dropdown-content {display: block;}
        .avatar-dropdown:hover .avatar-dot {background-color: #E5E4E2;}
        @media screen and (max-width: 2000px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:75px !important;
            }
            
        }
        @media screen and (max-width: 1800px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:70px !important;
            }
            
        }
        @media screen and (max-width: 1600px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:60px !important;
            }
            
        }
        @media screen and (max-width: 1500px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:49px !important;
            }
        }
        @media screen and (max-width: 1400px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:40px !important;
            }
        }
        @media screen and (max-width: 1320px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:37px !important;
            }
        }
        @media screen and (max-width: 1230px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:33px !important;
            }
        }
        @media screen and (max-width: 1090px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:29px !important;

            }
        }

        @media screen and (max-width: 900px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:25px !important;
            }
        }
        @media screen and (max-width: 600px) {
        .top-right {
            position: absolute;
            top: -10px;
            margin-left:25px !important;
        }
        }

        @media screen and (max-width: 300px) {
            .top-right {
                position: absolute;
                top: -10px;
                margin-left:30px !important;
            }
        }
        .avatar-dropdown-content{
            margin-left:120%;
        }
        table tr:first-child .avatar-dropdown-content{
            top: 0;
            margin-left:120%;
        }
        table tr:last-child .avatar-dropdown-content{
            top: -170%;
            margin-left:120%;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    @if(count($errors) >0 )
        @foreach($errors->all() as $error)
            <div class='alert alert-warning'>
                {{ $error }}
            </div>
        @endforeach
    @endif
    @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-warning">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('error')!!}
        </div>
    @endif
    <div class="content" style="width:100%;">
        <!-- heading part starts -->
        <div class="block-header block-header-default">
            <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                       <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                          Risk Assessment Criteria Matrix</span> /
                <span style="text-transform: capitalize;font-weight:normal;font-size:15px;">
                        Enterprise Risk </span>
            </div>
            <div>
                <table width="100%" style="padding-top:5px;padding-right:15px;">
                    <tr>
                        <td align="right">
                            <button type="button"
                                    class="btn btn-sm btn-alt-primary" id="request-review-approve">
                               <span style="font-size:11px;" data-toggle="tooltip" title="Click here to Add Request Review & Approval"><i class="fa fa-1x fa-user-edit"></i>&nbsp;Request Review & Approval</span>
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-alt-primary btn-add-racm">
                               <span data-toggle="tooltip" title="Click here to Add New RACM">
                                <i class="fa fa-plus mr-1"></i>&nbsp;Add</span>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="table-responsive" id="content">
                    <p style="font-size: 12px;">
                        <span>RACM, or Risk Assessment Criteria Matrix, refers to predefined criteria established by organizations prior to conducting risk assessments. It serves as a valuable reference for risk assessment processes, enabling organizations to decide what scenario will go as high/moderate/low for each factor in the risk assessment.</span>
                    </p>
                    <!-- approved documents listing starts -->
                    <table class="table table-borderless table-striped table-vcenter js-dataTable-full" id="tabledata">
                        <thead class="border-top">
                            <tr>
                                <th class="text-center" style="width: 1%;position:sticky;" id="firstColumn"></th>
                                <th class="text-center" data-orderable="false" style="font-size:11px;width:1%;text-transform:capitalize;position:sticky;vertical-align: middle">#</th>
                                <td id="deptselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;font-size: 12px;top:2px;font-weight:600;"></td>
                                {{--<th class="text-center" style="width: 15%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;">--}}
                                {{--Process name</th>--}}
                                <th class="text-center" data-orderable="false" style="width:18%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Risk factor title</th>
                                <th class="text-center" data-orderable="false" style="width:18%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Risk factor description</th>
                                <th class="text-center" data-orderable="false" style="width: 6%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    <span class="p-2" style="background: #ff686a; color: white">High</span>
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 6%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    <span class="p-2" style="background: #ffbf00; color: white">Moderate</span>
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 6%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    <span class="p-2" style="background: #349967; color: white">Low</span>
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 10%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Reviewer
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 8%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Approver
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 8%;text-transform: capitalize;font-size: 12px;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Status
                                </th>
                                <th class="text-center" data-orderable="false" style="width: 8%;font-size:12px;z-index: 1;text-transform:capitalize;position:sticky;top:2px;font-weight:600;vertical-align: middle">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1; ?>
                        @foreach($riskAcms as $riskAcm)
                            @php
                                $unit = '';
                                if ($riskAcm->unit == 2) {
                                    $unit = '%';
                                }
                                if ($riskAcm->unit == 3) {
                                    $unit = 'No sign';
                                }
                                if ($riskAcm->unit == 4) {
                                    $unit = $riskAcm->unit_other ? $riskAcm->unit_other : 'Other';
                                }
                                $totalreviewers = App\RacmReviewerDetail::where('racm_id',$riskAcm->id)->pluck('reviewer_id')->unique()->toArray();
                                $reviewedreviewers = App\RacmReviewerDetail::where('racm_id',$riskAcm->id)->where('reviewed_status',true)->where('racm_rejected',false)->pluck('reviewer_id')->unique()->toArray();
                                $reviewers = App\RacmReviewerDetail::where('racm_id',$riskAcm->id)->pluck('reviewer_id')->unique()->toArray();
                                $first_reviewer = null;
                                if(count($reviewers)>0){
                                    $first_reviewer = array_shift($reviewers);
                                    $first_reviewer = App\User::where('id',$first_reviewer)->first();
                                }
                                $reviewers_details = App\User::whereIn('id',$reviewers)->get();
                            @endphp
                            <tr>
                                <td><input class="text-center font-size-sm" type="checkbox" id="{{ $riskAcm->id}}" name="id[]"></td>
                                <td class="text-center" style="font-size:11px;">{{$no}}</td>
                                <td style="font-size: 11px;" class="text-center">
                                    <span>{{ $riskAcm->department ? $riskAcm->department->departmentname : '--' }}</span>
                                </td>
                                <td style="font-size: 11px;" class="text-center">
                                    <span>{{ $riskAcm->risk_factor_title }}</span>
                                </td>
                                <td style="font-size: 11px;" class="text-left">
                                    <span>{{ $riskAcm->risk_factor_description }}</span>
                                </td>
                                <td style="font-size: 11px;" class="text-center">
                                    @if($riskAcm->high)
                                        {{ $riskAcm->high . $unit }}
                                    @endif
                                </td>
                                <td style="font-size: 11px;" class="text-center">
                                    @if($riskAcm->moderate)
                                        {{ $riskAcm->moderate . $unit }}
                                    @endif
                                </td>
                                <td style="font-size: 11px;" class="text-center">
                                    @if($riskAcm->low)
                                        {{ $riskAcm->low . $unit }}
                                    @endif
                                </td>
                                <td class="text-center" style="font-size:10px;">
                                    <div class="main-img-aud-container">
                                        @if($first_reviewer)
                                            <img src="{{($first_reviewer->profile_picture && File::exists(public_path('/storage/profile/'.$first_reviewer->profile_picture))) ? asset('storage/profile/'.$first_reviewer->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$first_reviewer->name}}"/>
                                            
                                            @if(count($reviewers_details) > 0)
                                            <div class="top-right">
                                                <div class="avatar-dropdown">
                                                    <div style="top:2px;" class="avatar-dot text-center">+{{count($reviewers_details)}} </div>
                                                    <div class="avatar-dropdown-content">
                                                        @foreach($reviewers_details as $reviewer_arr)
                                                            @if($reviewer_arr)
                                                                <a href="#">
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <img style="margin-bottom:5px;" src="{{($reviewer_arr->profile_picture && File::exists(public_path('/storage/profile/'.$reviewer_arr->profile_picture))) ? asset('storage/profile/'.$reviewer_arr->profile_picture) : asset('/images/avatar10.jpg')}}" class="avatar" title="{{$reviewer_arr->name}}"/>
                                                                        </div>
                                                                        <div class="col-md-9 text-left">
                                                                            <span style="top: 50%;position: absolute;vertical-align:middle;margin-top:-1em;">{{ucfirst($reviewer_arr->name)}}</span>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @else
                                        <img src="{{ asset('/images/avatar10.jpg') }}"
                                                class="avatar"
                                                data-toggle="tooltip" data-placement="top" title="--"
                                                alt="No-avatar">
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center" style="font-size:10px;">
                                    @if($riskAcm->approver)
                                        <img src="{{ $riskAcm->approver->avatar_path??'' }}"
                                             class="avatar"
                                             data-toggle="tooltip" data-placement="top" title="{{ $riskAcm->approver->name??'' }}"
                                             alt="{{ $riskAcm->approver->name??'' }}">
                                    @else
                                        <img src="{{ asset('/images/avatar10.jpg') }}"
                                             class="avatar"
                                             data-toggle="tooltip" data-placement="top" title="--"
                                             alt="No-avatar">
                                    @endif
                                </td>
                                
                                <td class="text-center" style="font-size:10px;">
                                    @if(($riskAcm->status=='Review Awaiting'))
                                    <span style="color:#fe9c05;font-weight:500;">{{$riskAcm->status}}</span>
                                    @elseif($riskAcm->status=='Review Requested')
                                    <span style="color:#6aa84f;font-weight:500;">{{$riskAcm->status}}({{count($reviewedreviewers)}}/{{count($totalreviewers)}})</span>
                                    @elseif($riskAcm->status=='Reviewed')
                                    <span style="color:#0720fc;font-weight:500;">{{$riskAcm->status}}({{count($reviewedreviewers)}}/{{count($totalreviewers)}})</span>
                                    @elseif($riskAcm->status=='Review Rejected')
                                    <span style="color:red;font-weight:500;">{{$riskAcm->status}}</span>
                                    @elseif($riskAcm->status=='Awaiting Approval')
                                    <span style="color:#fe9e2d;font-weight:500;">{{$riskAcm->status}}</span>
                                    @elseif($riskAcm->status=='Approved')
                                    <span style="color:#6aa84f;font-weight:500;">{{$riskAcm->status}}</span>
                                    @elseif($riskAcm->status=='Rejected')
                                    <span style="color:red;font-weight:500;">{{$riskAcm->status}}</span>
                                    @else
                                    <span data-placement="top" data-toggle="tooltip" title="Unknown Status">--</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <!-- Action: Edit/Delete  -->
                                        <div>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown"
                                               style="border-radius: 0;"
                                               data-placement="top" title="Actions" id="drop{{ $riskAcm->id }}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark"
                                                 id="dropdownMenu{{ $riskAcm->id }}">
                                                <a href="javascript:void(0)" class="dropdown-item btn-edit-racm"
                                                   style="font-size:10px;"
                                                   data-route="{{ $riskAcm->id }}">
                                                    <i class="fas fa-edit" style="font-size:10px;"></i>
                                                    <span class="ml-1">Edit</span>
                                                </a>
                                                <a href="javascript:void(0)" class="dropdown-item btn-delete-racm"
                                                   style="font-size:10px;"
                                                   data-route="{{ $riskAcm->id }}">
                                                    <i class="fas fa-trash" style="font-size:10px;"></i>
                                                    <span class="ml-1">Delete</span>
                                                </a>
                                            </div>
                                        </div>
                                        <a href="#" class="btn btn-sm btn-alt-primary"
                                           data-id="{{ $riskAcm->id }}" data-toggle="tooltip" data-placement="top"
                                           title="View" onclick="ViewBtn('{{$riskAcm->id}}')">
                                            <i class="far fa-eye text-center" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                    </div>
                                    <div id="viewModal{{$riskAcm->id}}"
                                         class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-bordered text-left">
                                                    <tr>
                                                        <th>Risk factor title</th>
                                                        <td>{{ $riskAcm->risk_factor_title }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Process name</th>
                                                        <td>{{ $riskAcm->department ? $riskAcm->department->departmentname : '--' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Risk factor description</th>
                                                        <td>{{ $riskAcm->risk_factor_description }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status</th>
                                                        <td>
                                                            @if(($riskAcm->status=='Review Awaiting'))
                                                            <span style="color:#fe9c05;font-weight:500;">{{$riskAcm->status}}</span>
                                                            @elseif($riskAcm->status=='Review Requested')
                                                            <span style="color:#6aa84f;font-weight:500;">{{$riskAcm->status}}({{count($reviewedreviewers)}}/{{count($totalreviewers)}})</span>
                                                            @elseif($riskAcm->status=='Reviewed')
                                                            <span style="color:#0720fc;font-weight:500;">{{$riskAcm->status}}({{count($reviewedreviewers)}}/{{count($totalreviewers)}})</span>
                                                            @elseif($riskAcm->status=='Review Rejected')
                                                            <span style="color:red;font-weight:500;">{{$riskAcm->status}}</span>
                                                            @elseif($riskAcm->status=='Awaiting Approval')
                                                            <span style="color:#fe9e2d;font-weight:500;">{{$riskAcm->status}}</span>
                                                            @elseif($riskAcm->status=='Approved')
                                                            <span style="color:#6aa84f;font-weight:500;">{{$riskAcm->status}}</span>
                                                            @elseif($riskAcm->status=='Rejected')
                                                            <span style="color:red;font-weight:500;">{{$riskAcm->status}}</span>
                                                            @else
                                                            <span data-placement="top" data-toggle="tooltip" title="Unknown Status">--</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>High</th>
                                                        <td>
                                                            @if($riskAcm->high)
                                                                {{ $riskAcm->high . $unit }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Moderate</th>
                                                        <td>
                                                            @if($riskAcm->moderate)
                                                                {{ $riskAcm->moderate . $unit }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Low</th>
                                                        <td>
                                                            @if($riskAcm->low)
                                                                {{ $riskAcm->low . $unit }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php 
                                                        $reviewers_ids = App\RacmReviewerDetail::where('racm_id',$riskAcm->id)->pluck('reviewer_id')->unique()->toArray();
                                                        $reviewers_names = App\User::whereIn('id',$reviewers_ids)->pluck('name')->toArray();
                                                    
                                                        $reviewed_by = App\RacmReviewerDetail::where('racm_id',$riskAcm->id)->where('reviewed_status',true)->with('reviewerdetails')->get()->unique();
                                                        $last_reviewed_by = App\RacmReviewerDetail::where('racm_id',$riskAcm->id)->where('reviewed_status',true)->with('reviewerdetails')->orderBy('updated_at', 'DESC')->first();
                                                    @endphp
                                                    @if(count($reviewers_names))
                                                    <tr>
                                                        <th>Reviewers</th>
                                                        <td>{{ count($reviewers_names) ? implode(',',$reviewers_names) : '' }}</td>
                                                    </tr>
                                                    @endif
                                                    @if(count($reviewed_by))
                                                    <tr>
                                                        <th>Reviewed By</th>
                                                        <td>
                                                            @foreach($reviewed_by as $r)
                                                            {{ $r->reviewerdetails->name??"" }}<span style="color:red;font-size:10px;">({{$r->reviewed_date ? date('d-m-Y',strtotime($r->reviewed_date)):''}})</span>,
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if($last_reviewed_by)
                                                    <tr>
                                                        <th>Last Reviewed By</th>
                                                        <td>{{ $last_reviewed_by->reviewerdetails->name??"" }}<span style="color:red;font-size:10px;">({{$last_reviewed_by->reviewed_date ? date('d-m-Y',strtotime($last_reviewed_by->reviewed_date)) : ''}})</span></td>
                                                    </tr>
                                                    @endif
                                                    @if($last_reviewed_by && $last_reviewed_by->racm_reject_reason)
                                                    <tr>
                                                        <th>Reviewer remarks</th>
                                                        <td style="color:red;">{{ $last_reviewed_by->racm_reject_reason??''}}</td>
                                                    </tr>
                                                    @endif
                                                    @if($riskAcm->approver)
                                                    <tr>
                                                        <th>Approver</th>
                                                        <td>{{ $riskAcm->approver ? $riskAcm->approver->name : '' }}</td>
                                                    </tr>
                                                    @endif
                                                    
                                                    @if($riskAcm->approvedBy)
                                                    <tr>
                                                        <th>Approved By</th>
                                                        <td>{{ $riskAcm->approvedBy->name??""}}<span style="color:red;font-size:10px;">({{$riskAcm->approved_date ? date('d-m-Y',strtotime($riskAcm->approved_date)) : ''}})</span></td>
                                                    </tr>
                                                    @endif
                                                    
                                                    @if($riskAcm->reject_reason_by_approver)
                                                    <tr>
                                                        <th>Approver remarks</th>
                                                        <td style="color:red;">{{ $riskAcm->reject_reason_by_approver }}</td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <th>Posted by</th>
                                                        <td>{{ $riskAcm->postedBy ? $riskAcm->postedBy->name : '' }}</td>
                                                    </tr>
                                                </table>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <?php $no++; ?>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Ends approved documents listing -->
                </div>
            </div>
        </div>
    </div>
    <div class="racm-area"></div>
    <div id="add-request-review-approve" class="modal fadebd-example-modal-sm" aria-labelledby="exampleModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-transparent mb-0">
                    <div class="block-header bg-primary-darker">
                      <h3 class="block-title text-white">Add Request Review & Approval</h3>
                      <div class="block-options">
                        <button type="button" class="btn-block-option" onclick="closeModal()" aria-label="Close">
                          <i class="fa fa-fw fa-times"></i>
                        </button>
                      </div>
                    </div>
                    <div class="block-content fs-sm">
                        <input type="hidden" name="recordids" id="recordids" value="">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;padding-left:0;">Choose Reviewer <span class="text-danger">*</span></label>
                                <div class="col-sm-12 row">
                                    <select class="select2 form-control" id="reviewers" required
                                            name="reviewers" style="font-size: 13px;width:100%" multiple data-placeholder="Choose Reviewers name">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="label-invalid error-reviewers"></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="col-sm-12 col-form-label" style="font-size:13px;padding-left:0;">Choose Approver <span class="text-danger">*</span></label>
                                <div class="col-sm-12 row">
                                    <select class="select2 form-control" id="approver" required
                                            name="approver" style="font-size: 13px;width:100%">
                                        <option value="" selected disabled>Choose Approver name</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="label-invalid error-approver"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body text-right">
                      <button type="button" class="btn btn-sm btn-secondary me-1" onclick="closeModal()">Close</button>
                      <button type="button" class="btn btn-sm btn-primary" id="save-request">Submit</button>
                    </div>
                  </div>
                
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    {{-- --------edit  button ------------}}
    <script>
        $("#request-review-approve").click(function() {
            var dataArr = new Array();
            var all;
            if ($('input:checkbox:checked').length > 0) {
                $('input:checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                });
                all = dataArr.join(",");
                if(all){
                    $('#add-request-review-approve').show();
                    $('#recordids').val(all);
                    $('.select2').select2();
                }else{
                    Swal.fire({
                        text: "No Record Selected."
                        , icon: "error"
                        , showCancelButton: true
                        , showConfirmButton: false
                        , cancelButtonColor: "#d33"
                        , cancelButtonText: "Close", 
                    });
                }

            } else {
                Swal.fire({
                    text: "No Record Selected."
                    , icon: "error"
                    , showCancelButton: true
                    , showConfirmButton: false
                    , cancelButtonColor: "#d33"
                    , cancelButtonText: "Close"
                , });
            }
        });
        function closeModal(){
            $('#add-request-review-approve').hide();
        }
    </script>
    <script>
        function ViewBtn(id) {
            $('#viewModal' + id).modal('show');
        }
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('body').on('change', '#unit', function () {
                const modal = $(this).parents('.modal')
                if ($(this).val() == 4) {
                    modal.find('#unit_other').removeClass('d-none');
                } else {
                    modal.find('#unit_other').addClass('d-none');
                }
            })
            $('body').on('click', '.auto-fill_risk-factor-name', function () {
                const modal = $(this).parents('.modal')
                const name = $(this).attr('data-name');
                const description = $(this).attr('data-description');
                modal.find('#risk_factor_title').val(name);
                modal.find('#risk_factor_description').val(description);
                modal.find('.auto-fill_risk-factor-name').addClass('btn-outline-primary').removeClass('btn-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
            })
            $('body').on('click', '.btn-add-racm', function () {
                const url = window.location.protocol + '//'+ window.location.host+`/user/riskmgmt/racm/create`;
                $.ajax({
                    url:url,
                    type: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('.racm-area').html(data.view)
                            const modalEle = $(`#racmModal`);
                            $('.racm-area').find('.js-select2').each(function() {
                                $(this).select2({
                                    width: '100%',
                                });
                            });
                            modalEle.modal('show');
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            }).on('click', '#save_racm', function () {
                $('.label-invalid').html('')
                const modal = $(this).parents('.modal')
                const url = window.location.protocol+'//'+window.location.host+$(this).attr('data-route');
                const risk_factor_title = modal.find('#risk_factor_title').val();
                const process_id = modal.find('#process_id').find(":selected").val();
                const risk_factor_description = modal.find('#risk_factor_description').val();
                const low = modal.find('#low').val();
                const moderate = modal.find('#moderate').val();
                const high = modal.find('#high').val();
                const unit = modal.find('#unit').find(":selected").val();
                const unit_other = modal.find('#unit_other').val();
                // const reviewer_id = modal.find('#reviewer_id').find(":selected").val();
                // const approver_id = modal.find('#approver_id').find(":selected").val();
                const $this = $(this);
                $.ajax({
                    url,
                    type: 'POST',
                    data: {
                        risk_factor_title,
                        process_id,
                        risk_factor_description,
                        low,
                        moderate,
                        high,
                        unit,
                        unit_other,
                        // reviewer_id,
                        // approver_id
                    },
                    success: function (data) {
                        if (data.success) {
                            if ($this.parents('.modal.racm-popup').length !== 0) {
                                $this.parents('.modal.racm-popup').modal('hide');
                            }
                            location.reload()
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (response) {
                        const status = response.status;
                        const data = response.responseJSON;
                        if (status === 422) {
                            $.each(data.errors, function (field, object) {
                                const errorString = object.join(',')
                                $('.error-' + field).html(errorString)
                            })
                            $('#racmModal').animate({ scrollTop: 0 }, 'slow');
                        } else {
                            alert('Process failure!')
                        }
                    }
                })
            }).on('click', '.btn-edit-racm', function () {
                const id = $(this).attr('data-route');
                $.ajax({
                    url : window.location.protocol+'//'+window.location.host+`/user/riskmgmt/racm/${id}/edit`,
                    type: 'POST',
                    success: function (data) {
                        if (data.success) {
                            $('.racm-area').html(data.view)
                            const modalEle = $(`#racmModal`);
                            $('.racm-area').find('.js-select2').each(function() {
                                $(this).select2({
                                    width: '100%',
                                });
                            });
                            const risk_factor_title = modalEle.find('#risk_factor_title').val();
                            modalEle.find('.auto-fill_risk-factor-name').each(function() {
                                const name = $(this).attr('data-name');
                                if(name == risk_factor_title) {
                                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                                }
                            });
                            modalEle.modal('show');
                        } else {
                            alert('Data is not found')
                        }
                    },
                    error: function () {
                        alert('Handle error');
                    }
                })
            }).on('click', '.btn-delete-racm', function () {
                if (confirm('Are you sure to delete the racm?')) {
                    const id = $(this).attr('data-route');
                    $.ajax({
                        url: window.location.protocol+'//'+window.location.host+`/user/riskmgmt/racm/${id}/delete`,
                        type: 'DELETE',
                        success: function (data) {
                            location.reload();
                        },
                        error: function () {
                            alert('Handle error');
                        }
                    })
                }
            }).on('click','#save-request',function(){
                $('.label-invalid').html('')
                const url = window.location.protocol+'//'+window.location.host+'/user/riskmgmt/racm/request-add-reviewers-approver';
                const reviewers = $('#reviewers').val();
                const approver = $('#approver').val();
                const recordids = $('#recordids').val();
                $.ajax({
                    url,
                    type: 'POST',
                    data: {
                        reviewers,
                        approver,
                        recordids
                    },
                    success: function (data) {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (response) {
                        const status = response.status;
                        const data = response.responseJSON;
                        if (status === 422) {
                            $.each(data.errors, function (field, object) {
                                const errorString = object.join(',')
                                $('.error-' + field).html(errorString);
                                $('#add-request-review-approve').animate({ scrollTop: 0 }, 'slow');
                            })
                        } else {
                            alert('Process failure!')
                        }
                    }
                })
            });

            const createHeadingSelect = (
                headingName = '', headingId = '',
                values = '', dropdownId = '', columnIndex = 0, caseInsensitive = true
            ) => {
                let nameArr = values.split(',');
                let optionText = "";
                for (let i = 0; i < nameArr.length; i++) {
                    if(nameArr[i]) {
                        optionText += `<option value="${nameArr[i]}">${nameArr[i].replaceAll('|', ', ')}</option>`;
                    }
                }
                $("#tabledata").one("preInit.dt", function() {
                    let table = $('#tabledata').DataTable();
                    const heading = $(`<select class="headingselect"
                            style="margin: 0 auto; font-size: 13px; display: block" id="${dropdownId}">
                                <option value="">${headingName}&nbsp;&nbsp;&#9207;</option>${optionText}
                            </select>`);

                    $('#' + headingId).html(heading);
                    $("#" + dropdownId).on('change', function() {
                        if ($(this).val()) {
                            table.columns(columnIndex).search($(this).val(), null, null, caseInsensitive).draw();
                            $(this).addClass('text-primary');
                            $("#" + dropdownId +  "option").css('color', 'black');
                        } else {
                            location.reload();
                        }
                    });
                });
            }

            // Department
            const departmentNames = "{{ $allDeptNames }}";
            createHeadingSelect('Process name', 'deptselectdata', departmentNames, 'dept_dropdown', 1);
        });
    </script>
@endsection

