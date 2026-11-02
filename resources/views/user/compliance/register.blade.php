@extends('pages.datatables')
@section('css_after')
<style>
    .status-red {
        color: #af1e1e;
        background-color: #f8d4d4;
        border-color: #fbeaea;
    }

    .disabled-link {
        pointer-events: none;
        color: #999;
        text-decoration: none;
    }

    .fill-svg {
        fill: #999;
    }
    .select2-results__options,
    .select2 {
        font-size: 12px;
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

    .status {
        display: inline-block;
        padding: 7px 5px 5px 7px;
        font-size: 75%;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .breadcrumb > li + li:before {
        display: none !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    .breadcrumb li:first-child {
        font-size: 1rem;
        text-transform: uppercase;
    }
    .breadcrumb li:last-child {
        font-size: 1rem;
        color: gray;
    }
    .breadcrumb-slash >li+li:before {
        display: none !important;
    }
</style>
@endsection
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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
    </ul>
</div>

<div class="input-group input-group-sm">
    <a type="button" href="{{url('/user/compliance/add')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js">
    {{--<link rel="stylesheet" href="{{ asset('css/workflow.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
    <style>
        .headingselect {
            white-space: break-spaces;
            line-height: 1rem;
        }
        .scrollable {
                height: 100%;
                overflow-x: scroll;
                overflow-y: scroll;
            }
        #content{
            height:500px !important;
        }
        .note-editable{
            height:250px !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/plugins/summernote/summernote-bs4.min.js') }}"></script>
    
</head>

<body>
    <?php
    $data1=[];
    $data2=[];
    ?>
    <input type="hidden" value="a1" id="tabselected">
    <div class="content" style="width:100%;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb d-flex justify-content-between align-items-center">
                <div class="d-flex breadcrumb-slash">
                    <li class="breadcrumb-item font-w700">Register</li>
                    <li class="breadcrumb-item">Compliance Management</li>
                </div>
            </ol>
        </nav>
        <div class="block block-rounded" >
            <?php
                $no=0;
            ?>
            <div class="block-content block-content-full">
                <div class="scrollable table-responsive" id="content">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                        @if(count($errors) >0 )
                            @foreach($errors->all() as $error)
                            <div class='alert alert-warning'>
                                {{ $error }}
                            </div>
                            @endforeach
                        @endif
                        @if(Session::has('msg'))
                        <div class="alert alert-primary">
                            <a class="close" data-dismiss="alert" style="cursor: pointer">×</a>
                            {!!Session::get('msg')!!}
                        </div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-warning">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('error')!!}
                            </div>
                        @endif
                        <thead style="position: sticky; top: 0;z-index:5;background-color:#ffffff;">
                            <tr>
                                <td class="text-center" style="font-size:10px;width:1px;text-transform:capitalize;" data-orderable="false">
                                </td>
                                <td class="text-center" style="font-size:10px;width:5%;text-transform:capitalize;" data-orderable="false">
                                    <span style="position:relative;top:2px;font-weight:600;"> Slno</span>
                                </td>
                                <td class="text-center" style="font-size:10px;min-width:120px;text-transform:capitalize;" data-orderable="false">
                                    <span style="position:relative;top:2px;font-weight:600;"> Compliance ID</span>
                                </td>
                                <td id="authorityselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;"> </td>
                                <td id="issueselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;"> </td>
                                <td class="text-center" style="font-size:10px;min-width:120px;text-transform:capitalize;" data-orderable="false">
                                    <span style="position:relative;top:2px;font-weight:600;"> Title</span>
                                </td>
                                <td id="statusselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;"> </td>

                                <td id="deptselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;"> </td>

                                <td class="text-center" style="font-size:10px;text-transform:capitalize;width:7%;" data-orderable="false">
                                    <i class="far fa-user" style="font-size:14px;">
                                </td>

                                <td id="responsibleselectdata" data-orderable="false" style="width:10%;text-transform:capitalize;"></td>

                                <td class="text-center" style="font-size:10px;width:10%;text-transform:capitalize;" data-orderable="false">
                                    <span style="position:relative;top:2px;font-weight:600;">Actions</span>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=0;
                                $today = Date("Y-m-d");
                                $statusArray = [
                                    'Under Review', 'Awaiting Compliance Evaluation', 'Complied, Under Review', 
                                    'Not Complied, Under Review', 'Partially Complied, Under Review', 
                                    'Need improvement', 'Compliance Evaluation due'
                                ];
                            ?>
                            @foreach($data as $value)
                            <tr>
                                <td class="width-5"><input class="checkbox" type="checkbox" style="font-size:10px;" id="{{ $value->Cid}}" name="C_id[]"></td>
                                <td class="text-center" style="font-size:10px;">{{$no=$no+1}}</td>
                                <td class="text-center" style="font-size:10px;">{{ $value->auth_id }}</td>
                                <td style="font-size:10px;" class="text-center">
                                    <em class="text-muted " style="font-size:10px;">{{ $value->regulatory ? $value->regulatory->name : $value->authority}}</em>
                                </td>
                                <td style="font-size:10px;" class="text-center">
                                    <em class="text-muted " style="font-size:10px;">{{ $value->legalDocuments ? $value->legalDocuments->name : $value->issue}}</em>
                                </td>
                                <td style="font-size:10px;" class="text-center">
                                    <a href="{{ route('user.compliance.detail', $value->Cid) }}">
                                        <em style="font-size:10px;">{{ $value->regulation }}</em>
                                    </a>
                                </td>
                                <td class="text-center" style="font-size:13px;">
                                    
                                    @if(in_array($value->comp_status, $statusArray))
                                        <span class="status status-gray">Draft Under Review</span>
                                    @elseif ($value->comp_status == "Complied")
                                        <span class="status status-success">Evaluation Completed</span>
                                    @elseif ($value->comp_status == "Not Complied")
                                        <span class="status status-red">{{ $value->comp_status}}</span>
                                    @elseif ($value->comp_status == "Partially Complied")
                                        <span class="status status-blue">{{ $value->comp_status}}</span>
                                    @elseif($value->comp_status=='Awaiting Periodic Evaluation')
                                        <span class="status status-warning">{{ $value->comp_status}}</span>
                                    @elseif($value->comp_status=='Periodic Evaluation On Due')
                                        <span class="status status-red">{{ $value->comp_status}}</span>
                                    @endif

                                </td>
                                <td style="font-size:10px;" class="text-center">
                                    @php $department_name_column = $value->dep ? nl2br($value->dep->departmentname ) : "-" ; @endphp
                                    <em class="text-muted " style="font-size:10px;">{{ $department_name_column}} </em>
                                    <span style="display: none;">{{ "d_".$department_name_column."_e" }}</span>
                                </td>
                                <td class="text-center" style="font-size:10px;">
                                    @php
                                    $profile_picture = $value->responsible ? $value->responsible->profile_picture : '';
                                    @endphp

                                    @if($value->responsibility != null && $profile_picture != '' && file_exists("storage/profile/$profile_picture"))
                                    <img class="img-avatar img-avatar48" src="/storage/profile/{{ $value->responsible->profile_picture }}" alt="">
                                    @else
                                    <img class="img-avatar img-avatar48" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="not_found">
                                    @endif
                                </td>
                                <td style="font-size:10px;" class="text-center">
                                    {{ $value->responsible ? $value->responsible->name : '' }}
                                </td>

                                <div class="dropdown font-size-sm">
                                    <td>
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$value->Cid}}">
                                                <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                            </a>
                                            <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $value->Cid }}">
                                                {{-- Review Compliance Evaluation and Change Responsibility Done --}}
                                                @if($value->legalCoordinator && (Auth::user()->email == $value->legalCoordinator->email))
                                                 <a href="{{ route('user.compliance.review_status', ['id' => $value->Cid]) }}" class="dropdown-item {{ $value->comp_status === 'Partially Complied'|| $value->comp_status === 'Not Complied' ? 'disabled-link' : '' }}" style="font-size:10px;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="{{ $value->comp_status === 'Partially Complied'|| $value->comp_status === 'Not Complied' ? 'fill-svg' : '' }}">
                                                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                        <path d="M256 96c38.4 0 73.7 13.5 101.3 36.1l-32.6 32.6c-4.6 4.6-5.9 11.5-3.5 17.4s8.3 9.9 14.8 9.9H448c8.8 0 16-7.2 16-16V64c0-6.5-3.9-12.3-9.9-14.8s-12.9-1.1-17.4 3.5l-34 34C363.4 52.6 312.1 32 256 32c-10.9 0-21.5 .8-32 2.3V99.2c10.3-2.1 21-3.2 32-3.2zM132.1 154.7l32.6 32.6c4.6 4.6 11.5 5.9 17.4 3.5s9.9-8.3 9.9-14.8V64c0-8.8-7.2-16-16-16H64c-6.5 0-12.3 3.9-14.8 9.9s-1.1 12.9 3.5 17.4l34 34C52.6 148.6 32 199.9 32 256c0 10.9 .8 21.5 2.3 32H99.2c-2.1-10.3-3.2-21-3.2-32c0-38.4 13.5-73.7 36.1-101.3zM477.7 224H412.8c2.1 10.3 3.2 21 3.2 32c0 38.4-13.5 73.7-36.1 101.3l-32.6-32.6c-4.6-4.6-11.5-5.9-17.4-3.5s-9.9 8.3-9.9 14.8V448c0 8.8 7.2 16 16 16H448c6.5 0 12.3-3.9 14.8-9.9s1.1-12.9-3.5-17.4l-34-34C459.4 363.4 480 312.1 480 256c0-10.9-.8-21.5-2.3-32zM256 416c-38.4 0-73.7-13.5-101.3-36.1l32.6-32.6c4.6-4.6 5.9-11.5 3.5-17.4s-8.3-9.9-14.8-9.9H64c-8.8 0-16 7.2-16 16l0 112c0 6.5 3.9 12.3 9.9 14.8s12.9 1.1 17.4-3.5l34-34C148.6 459.4 199.9 480 256 480c10.9 0 21.5-.8 32-2.3V412.8c-10.3 2.1-21 3.2-32 3.2z" />
                                                    </svg>
                                                    <span class="ml-1">Review Compliance evaluation</span>
                                                </a>
                                                @endif
                                                @if(in_array(Auth::user()->email, $coordinatorsArray))
                                                    <a href="{{ route('user.compliance.change_responsibility', ['id' => $value->Cid ])}}" class="dropdown-item" style="font-size:10px;">
                                                        <i class="fas fa-edit" style="font-size:10px;"></i><span class="ml-1">Responsibility change</span>
                                                    </a>
                                                    <a href="#" class="dropdown-item" style="font-size:10px;" onclick="changeCoordinator('{{$value->Cid}}','{{$value->legal_coordinator??''}}')">
                                                        <i class="fa fa-user-edit" style="font-size:10px;"></i><span class="ml-1">Change Legal Coordinator</span>
                                                    </a>
                                                @endif

                                                {{-- Need to confirm for added by user--}}
                                                @if(Gate::allows('delete-compliance'))
                                                    <button type="button" class="dropdown-item" style="font-size:10px;" onclick="deleteRecord('{{ $value->Cid }}')">
                                                        <i class="fas fa-trash" style="font-size:10px;"></i><span class="ml-1">Delete</span>
                                                    </button>
                                                @endif
                                                {{-- Resubmit Done --}}
                                                <a href="{{ route('user.compliance.resubmit', ['id' => $value->Cid]) }}" class="dropdown-item {{ Auth::user()->employeeid == $value->postedby && ($value->comp_status === 'Not Complied'  || $value->comp_status === 'Partially Complied') ? '' : 'disabled-link' }}" style="font-size:10px;">
                                                    <i class="fas fa-paper-plane" style="font-size:10px;"></i><span class="ml-1">Resubmit</span>
                                                </a>

                                                @if($value->Hidden=='1')
                                                    <a href="{{ url('/user/compliance/unhide?val='.$value->Cid)}}" class="dropdown-item" onclick="return confirm('Are you sure to unhide?')" style="font-size:10px;">
                                                        <i class="fa fa-eye" style="font-size:10px;"></i><span class="ml-1">Unhide</span>
                                                    </a>
                                                @elseif($value->Hidden=='0')
                                                    <a href="{{ url('/user/compliance/hide?val='.$value->Cid)}}" class="dropdown-item" onclick="return confirm('Are you sure to hide ?')" style="font-size:10px;">
                                                        <i class="fa fa-eye-slash" style="font-size:10px;"></i><span class="ml-1">Hide</span>
                                                    </a>                                                   
                                                @endif
                                                <a href="{{url('user/compliance/choosevisibility?val='.$value->Cid)}}" class="dropdown-item" style="font-size:10px;">
                                                    <i class="fa fa-eye" style="font-size:10px;"></i><span class="ml-1">Choose visibility</span>
                                                </a>
                                                {{-- Add License/Permits Done --}}
                                                <a href="#" class="dropdown-item {{ in_array($value->comp_status,$statusArray) ||  $value->comp_status === 'Not Complied' ? 'disabled-link' : '' }}" style="font-size:10px;" data-toggle="modal"  data-target="#addLicensetModal" title="Add License / Permits" onclick="complidvalue('{{$value->Cid}}','{{ $value->auth_id }}')">
                                                    <i class="fas fa-plus" style="font-size:10px;"></i><span class="ml-1">Add License / Permits</span>
                                                </a>
                                                
                                            </div>
                                            <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $value->Cid }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn({{$value->Cid}})">
                                                <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                            </a>

                                        </div>

                                        <div id="viewModal{{$value->Cid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h2 class="modal-title">Detailed View</h2>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <tr>
                                                            <th style="width:70px">Compliance id</th>
                                                            <td>{{ $value->auth_id }}</td>
                                                        </tr>
                                                        @if($value->corrective_issueid)
                                                        @php
                                                            $ids = explode(',', $value->corrective_issueid);
                                                            $result = DB::table('corrective')->whereIn('id', $ids)->pluck('issueid');
                                                        @endphp
                                                        @if($result)
                                                            <tr>
                                                                <th style="width:70px">Corrective Issue ID</th>
                                                                <td>
                                                                    @for($i=0;$i<count($result);$i++)
                                                                    <span class="status status-blue">{{$result[$i]}}</span>{{$i==count($result)-1 ? '' : ','}} 
                                                                    @endfor
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        @endif

                                                        <tr>
                                                            <th>Regulatory authority</th>
                                                            <td>{{ $value->regulatory ? $value->regulatory->name : $value->authority }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Legislative issue</th>
                                                            <td>{{ $value->legalDocuments ? $value->legalDocuments->name : $value->issue}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Version / year / dated</th>
                                                            <td>{{ $value->version }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Act or regulation</th>
                                                            <td>{{ $value->regulation }}</td>
                                                        </tr>


                                                        <tr>
                                                            <th>Requirement</th>
                                                            <td>
                                                                <?php echo nl2br($value->requirement )?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Related aspect / risk number</th>
                                                            <td>
                                                                <?php echo nl2br($value->aspect )?>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Department</th>
                                                            <td>{{ $value->dep ? $value->dep->departmentname : '-'}}
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <th>Compliance status</th>
                                                            <td>
                                                                @if(in_array($value->comp_status, $statusArray))
                                                                    <span class="status status-gray">Draft Under Review</span>
                                                                @elseif ($value->comp_status == "Complied")
                                                                    <span class="status status-success">Evaluation Completed</span>
                                                                @elseif ($value->comp_status == "Not Complied")
                                                                    <span class="status status-red">{{ $value->comp_status}}</span>
                                                                @elseif ($value->comp_status == "Partially Complied")
                                                                    <span class="status status-blue">{{ $value->comp_status}}</span>
                                                                @elseif($value->comp_status=='Awaiting Periodic Evaluation')
                                                                    <span class="status status-warning">{{ $value->comp_status}}</span>
                                                                @elseif($value->comp_status=='Periodic Evaluation On Due')
                                                                    <span class="status status-red">{{ $value->comp_status}}</span>
                                                                @endif

                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th>Compliance Responsibility</th>
                                                            <td>{{ $value->responsible ? $value->responsible->name : '' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <th>Attachment</th>
                                                            <td id="attachment">
                                                                <button onclick="getAttachment({{ $value->Cid }})" id="actionAttachment" data-id="{{ $value->Cid }}" class="actionAttachment btn btn-secondary btn-small" style="font-size:13px;width: 150px;"><strong>View Attachment</strong></button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Reject reason</th>
                                                            <td>
                                                                <?php echo nl2br($value->reason )?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Posted by</th>
                                                            <td>{{$value->postedby}}&nbsp; {{ $value->postedByCompliance ?
                                                                $value->postedByCompliance->name : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Added date</th>
                                                            <td>{{$value->created_at}}</td>
                                                        </tr>
                                                        @if($value->draft_reviewed_by)
                                                        <tr>
                                                            <th>Draft Under Reviewed By</th>
                                                            <td>{{ $value->draftReviewedBy ?
                                                                $value->draftReviewedBy->name : ''}}</td>
                                                        </tr>
                                                        @endif
                                                        @if($value->draft_reviewed_at)
                                                        <tr>
                                                            <th>Draft Under Reviewed Date</th>
                                                            <td>{{date('d-m-Y H:i:s', strtotime($value->draft_reviewed_at))}}</td>
                                                        </tr>
                                                        @endif
                                                        
                                                        {{-- <tr>
                                                            <th>Compliance Evaluation Date</th>
                                                            <td>{{$value->evaluation_date}}</td>
                                                        </tr> --}}
                                                        <tr>
                                                            <th>Review Compliance Evaluation</th>
                                                            <td>
                                                                @if($value->review_evaluation_date)
                                                                    @php
                                                                        $evaluationDate = \Carbon\Carbon::parse($value->review_evaluation_date);
                                                                    @endphp
                                                                    @if($evaluationDate->format('H:i:s') == '00:00:00')
                                                                        {{ $evaluationDate->format('d-m-Y') }}
                                                                    @else
                                                                        {{ $evaluationDate->format('d-m-Y h:i:s A') }}
                                                                    @endif
                                                                @else
                                                                    {{''}}
                                                                @endif 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Frequency Of Compliance Evaluation</th>
                                                            <td>{{ $value->frequency }}</td>
                                                        </tr>

                                                        {{-- <tr>
                                                            <th>Approved by</th>
                                                            <td>{{$value->approvedby}}&nbsp; {{ $value->approvedByCompliance ?
                                                                $value->approvedByCompliance->name : ''}}</td>
                                                        </tr> --}}
                                                    </table>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                        </div>
                                        
                                    </td>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
    </div>
    <!-- Add LicenseLicense / Permits -->
    <div class="modal fade scrollable" id="addLicensetModal" tabindex="-1" role="dialog" aria-labelledby="addLicensetModallLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add License / Permits</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">License / Permits added successfully!</div>
                <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                <form id="form_id" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-sm-12 col-form-label" style="font-size:12px;">License/Permit Name <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="name" id="name" required placeholder="Enter License/Permit Name" style="font-size:12px;">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="name" class="col-sm-12 col-form-label" style="font-size:12px;">License Number <span style="color:red">*</span>
                                </label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="text" name="licensenumber" id="licensenumber" required placeholder="Enter License Number" style="font-size:12px;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="expirydate" class="col-sm-12 col-form-label" style="font-size:12px;">Expiry Date <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="date" name="expirydate" id="expirydate" required style="font-size:12px;">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="expirydate" class="col-sm-12 col-form-label" style="font-size:12px;">Effective Date</label>
                                <div class="col-sm-12">
                                    <input class="form-control" type="date" name="effdate" id="effdate" style="font-size:12px;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="responsible" class="col-sm-12 col-form-label" style="font-size:12px;">Responsible Person <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control selectm select_responsible" name="responsible[]" id="responsible" style="font-size: 12px;width:100%;" multiple required>
                                        @if(count($users)>0)
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}"> {{$user->name}}</option>
                                        @endforeach 
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-12 col-form-label" for="frequency" style="font-size:12px;">Email reminder for Renewal (Near to expiry)<span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="js-select2 form-control selectm select_frequency" style="font-size:12px;width: 100%;" id="frequency" name="frequency[]" data-placeholder="Choose frequencies" multiple>
                                        <option value="Weekly" style="font-size:13px;">Weekly</option><br>
                                        <option value="Every 5 Days" style="font-size:13px;">Every 5 days</option><br>
                                        <option value="Every 2 Days" style="font-size:13px;">Every 2 days</option><br>
                                        <option value="Daily" style="font-size:13px;">Daily</option><br>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="auth_id" id="auth_id" value="">
                        </div>
                        <div class="row"> 
                            <div class="form-group col-sm-12">
                                <label for="renewal" class="col-sm-12 col-form-label" style="font-size:12px;">Renewal Requirements</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control js-summernote" style="font-size:12px;" name="renewal" id="renewal" ></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="associated" class="col-sm-12 col-form-label" style="font-size:12px;">Associated Regulations</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control js-summernote" style="font-size:12px;" name="associated" id="associated"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="file" class="col-sm-12 col-form-label" style="font-size:12px;">File <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input class="form-control" style="font-size:12px;" type="file" name="document[]" id="document" required multiple>
                                </div>
                            </div> 
                            <input type="hidden" name="compl_id" id="compl_id" value="">
                        </div>        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-size:12px">Close</button>
                        <button type="button" class="btn btn-primary" style="font-size:12px" onclick="saveLicense()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End License  -->
    {{-- --------delete request button for mr --}}
    @section('sideitem')
    @if(Gate::allows('edit-compliance'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" style="margin-left:435px;" class="btn btn-sm btn-dual" id="edit" value=C_id[]>
            <i class="fa fa-1x fa-edit" style="color:grey;"></i>
        </button>
    </div>
    @endif
    @if(Gate::allows('delete-compliance'))
    <div class="input-group input-group-sm ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="del" value=C_id[]>
            <i class="fas fa-1x fa-trash" style="color:red;"></i>
        </button>
    </div>
    @endif
    @endsection
    <!--  --------delete request button   -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        var attachment_route = "{{ route('compliance.getAttachment',": id ")}}";
        $("#del").click(function() {
            var dataArr = new Array();
            if ($('.checkbox:checked').length > 0) {
                $('.checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    $.ajax({
                        type: 'get'
                        , url: "{{route('deletecompliance')}}" + "?val=" + $(this).attr('id')
                        , success: function() {
                            window.location.href = "{{url('/user/compliancemgmt')}}";
                        }
                        , error: function(response) {
                            console.log(response);
                        }
                    });
                });

            } else {                
                alert('No Record Selected');
            }
        });

        function deleteRecord(id) {

            $.ajax({
                type: 'get'
                , url: "{{route('deletecompliance')}}" + "?val=" + id
                , success: function() {
                    window.location.href = "{{url('/user/compliancemgmt')}}";
                }
                , error: function(err) {
                    console.log(err);
                }
            });
        }


        function autoResizeDiv(height) {
            document.getElementById('content').style.height = (height) + 'px';
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv(window.innerHeight - 100);

    </script>
    {{-----------edit button ------------}}

    
    <script>
        $("#edit").click(function() {
            var dataArr = new Array();
            var group = new Array();
            var d;
            var all;
            if($('.checkbox:checked').length > 0) {
                $('.checkbox:checked').each(function() {
                    dataArr.push($(this).attr('id'));
                    all = "" + dataArr;
                    group.push(all); // push all checkbox values into an array
                });
                $.ajax({
                    type: 'get'
                    , url: "{{route('editcompliance')}}" + "?val=" + (all)
                    , success: function(response) {
                        window.location.href = "{{route('editcompliance')}}" + "?val=" + (all);
                    }
                    , error: function(response) {
                        console.log(response);
                    }
                });
            } else {
                alert('No Record Selected');
            }
        });

    </script>
    <script>
        function getAttachment(id) {
            $.ajax({
                url: attachment_route.replace(':id', id)
                , type: 'get'
                , data: {
                    id: id
                }
                , dataType: "json"
                , success: function(response) {
                    if (response.data != null) {
                        var fil_path = "/storage/compliance_files/";
                        var fullUrl = fil_path + response.data
                        $('#actionFile').html(response.data)
                        $('#actionDownloadButton').html(
                            "<a style='margin-left:70px;font-size:11px;width: 80px;' class='btn btn-success' id='downloadFile' download='" + response.data + "'>Download</a>"
                        )
                        $('#downloadFile').attr('href', fullUrl);
                        $('#viewModal5').modal('show');
                    } else {
                        $('#actionFile').html('<span>No File</span>');
                        $('#downloadFile').css('display', 'none');
                        $('#viewModal5').modal('show');
                    }
                }
            });
        }

    </script>
    
    <!-- END Footer -->

    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Options</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="/user/compliancemgmt">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br />
                                            Register
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/compliance/myactions">
                                    <div class="block-content text-center">
                                        <i class="fas fa-clone fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            <br>My Actions
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href='/user/compliance/companalysis'>
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="viewModal5" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Attachment
                    </h3>
                </div>
                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                    <div class="row justify-content-center">
                        <thead>
                            <tr class="table-secondary">
                                <th>S.No</th>
                                <th>Attachment</th>
                                <th></th>
                            </tr>
                        </thead>
                        <?php $sno = 0;
                    ?>
                        <tbody>
                            <tr>
                                <td style="font-size:10px;">
                                    {{ $sno = $sno + 1 }}
                                </td>
                                <td style="font-size:15px;width: 100%;" id="actionFile">
                                    <span></span>
                                </td>
                                <td id="actionDownloadButton">

                                </td>
                            </tr>
                        </tbody>
                    </div>
                </table>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->
    {{-- Change Legal Coordinator Modal --}}
    <div id="ChangeLegalCoordinatorModal" class="modal fadebd-example-modal-md" aria-labelledby="ChangeLegalCoordinatorModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title text-white">Change Legal Coordinator</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="alert alert-success" id="successBtnCoordinator" style="display:none;font-size:12px;">Legal Coordinator has been successfully changed!</div>
                <ul class="alert alert-danger" id="dangerBtnCoordinator" style="display:none;font-size:12px;"></ul>
                <form id="form_id" method="post" onsubmit="event.preventDefault(); changeCoordinatorSubmit();">
                    @csrf
                    <div class="modal-body m-body p-2">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Legal Compliance Reviewer <span style="color:red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="hidden" class="form-control" style="font-size: 13px;" id="compliance_id" name="compliance_id" value="">
                                    <select class="form-control" id="legalCoordinator" name="legalCoordinator" required style="font-size:13px;width:100%;" data-placeholder="Select Legal Coordinator">
                                        <option></option>
                                        @if(count($legalCoordinators)> 0 )
                                            @foreach($legalCoordinators as $value)
                                                <option value="{{$value->id}}" style="font-size:13px;"> {{ $value->name }}</option><br>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" style="font-size:12px" id="resetCoordinatorModal">Reset</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Change</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    {{-- End Change Legal Coordinator Modal --}}
    @include('exporting.forcompliance')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        function ViewBtn(id) {
            $('#viewModal' + id).modal('show');
        }
    </script>
    @if(session('success_email_resubmit'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('user.compliance.send_add_compliance_emails') }}?id={{ session('Cid')}}&type=resubmit")
                .then(response => console.log("Email sending triggered in Resubmit"));
        });
    </script>
    @endif
</body>

</html>
@endsection
<script>
    function complidvalue(comp_id,auth_id){
        document.getElementById('compl_id').value=comp_id;
        document.getElementById('auth_id').value=auth_id;
    }
    function saveLicense() {
        var error_selector = $('#dangerBtn');
        var success_selector = $('#successBtn');
    
        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('licensenumber', $('#licensenumber').val());
        formData.append('expirydate', $('#expirydate').val());
        formData.append('effdate', $('#effdate').val());
        formData.append('renewal', $('#renewal').val());
        formData.append('associated', $('#associated').val());
        formData.append('compl_id', $('#compl_id').val());
        formData.append('auth_id', $('#auth_id').val());
        var files = $('#document')[0].files;
        for (var i = 0; i < files.length; i++) {
            formData.append('document[]', files[i]);
        }
        var selectedValues = $('#responsible').val();
        if (Array.isArray(selectedValues)) {
            for (var i = 0; i < selectedValues.length; i++) {
                formData.append('responsible[]', selectedValues[i]);
            }
        } else if (selectedValues !== null) {
            formData.append('responsible[]', selectedValues);
        }
        var freq = $('#frequency').val();
        if (Array.isArray(freq)) {
            for (var i = 0; i < freq.length; i++) {
                formData.append('frequency[]', freq[i]);
            }
        } else if (freq !== null) {
            formData.append('frequency[]', freq);
        }
        $.ajax({
            url: "{{ route('user.compliance.addlicensepermit') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.error != null) {
                    showError();
                    error_selector.empty();
                    $.each(response.error, function(index, errorMessage) {
                        error_selector.append(`<li>${errorMessage}</li>`);
                    });
                } else {
                    showSuccess();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(err) {
                showError();

                if (err.status === 500) {
                    error_selector.empty().append(
                        `<li>Some server errors happened, we are trying to fix them soon.</li>`
                    )
                } else if (err.status === 422) {
                    for (let i in err.responseJSON.errors) {
                        error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            }
        });
    }
</script>
@section('javascript')
<script>              
    let allusernames = "{{ $allusernames }}".split(',');
    let all_user_text = "";
    for (let i = 0; i < allusernames.length; i++) {
        all_user_text += `<option value="${allusernames[i]}">${allusernames[i]}</option>`;
    }
    $("#tabledata").one("preInit.dt", function() {
        let table = $('#tabledata').DataTable();
        let alluserheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="all_user_dropdown">
            <option value="">Responsibility&nbsp;&nbsp;&#9207;</option>` + all_user_text +
            `
        </select>`);

        $('#responsibleselectdata').html(alluserheading);
        $("#all_user_dropdown").on('change', function() {
            if ($(this).val()) {
                const regex2 = `^${$(this).val()}$`;
                table.columns(9).search(regex2,true,false).draw();
                $(this).addClass('text-primary');
                $("#all_user_dropdown option").css('color', 'black')
            } else {
                location.reload();
            }
        });
    });
    let allstatuslist = "{{ $allstatuslist }}".split('_');
    let status_text = "";
    for (var i = 0; i < allstatuslist.length; i++) {
        status_text += `<option value="${allstatuslist[i]}">${allstatuslist[i]}</option>`;
    }
    $("#tabledata").one("preInit.dt", function() {
        let table = $('#tabledata').DataTable();
        let statusheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="status_dropdown">
            <option value="">Status&nbsp;&nbsp;&#9207;</option>` + status_text +
            `
        </select>`);
        $('#statusselectdata').html(statusheading);
        $("#status_dropdown").on('change', function() {
            if ($(this).val()) {
                const regex3 = `^${$(this).val()}$`;
                table.columns(6).search(regex3,true,false).draw();
                $(this).addClass('text-primary');
                $("#status_dropdown option").css('color', 'black')
            } else {
                location.reload();
            }
        });
    });
    let allissueslist = "{{ $allissueslist }}".split(',');
    let issue_text = "";
    for (var i = 0; i < allissueslist.length; i++) {
        issue_text += `<option value="${allissueslist[i]}">${allissueslist[i]}</option>`;
    }
    $("#tabledata").one("preInit.dt", function() {
        let table = $('#tabledata').DataTable();
        let issueheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="issue_dropdown">
            <option value="">Legislative&nbsp;Issue&nbsp;&nbsp;&#9207;</option>` + issue_text +`</select>`);

        $('#issueselectdata').html(issueheading);
        $("#issue_dropdown").on('change', function() {
            if ($(this).val()) {
                const regex4 = `^${$(this).val()}$`;
                table.columns(4).search(regex4,true,false).draw();
                $(this).addClass('text-primary');
                $("#issue_dropdown option").css('color', 'black')
            } else {
                location.reload();
            }
        });
    });
    let allauthoritieslist = "{{ $allauthoritieslist }}".split(',');
    let authority_text = "";
    for (let i = 0; i < allauthoritieslist.length; i++) {
        authority_text += `<option value="${allauthoritieslist[i]}">${allauthoritieslist[i]}</option>`;
    }
    $("#tabledata").one("preInit.dt", function() {
        let table = $('#tabledata').DataTable();
        let authorityheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="authority_dropdown">
            <option value="">Regulatory&nbsp;Authority&nbsp;&nbsp;&#9207;</option>` + authority_text +
            `
        </select>`);

        $('#authorityselectdata').html(authorityheading);
        $("#authority_dropdown").on('change', function() {
            if ($(this).val()) {
                const regex5 = `^${$(this).val()}$`;
                table.columns(3).search(regex5,true,false).draw();
                $(this).addClass('text-primary');
                $("#authority_dropdown option").css('color', 'black')
            } else {
                location.reload();
            }
        });
    });
    let alldeptnames = "{{ $alldeptnames }}".split(',');
    let dept_text = "";
    for (let i = 0; i < alldeptnames.length; i++) {
        dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
    }
    $("#tabledata").one("preInit.dt", function() {
        let table = $('#tabledata').DataTable();
        let deptheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;" id="dept_dropdown">
            <option value="">Department&nbsp;&nbsp;&#9207;</option>` + dept_text +
            `
        </select>`);

        $('#deptselectdata').html(deptheading);
        $("#dept_dropdown").on('change', function() {
            if ($(this).val()) {
                const regex1 = $(this).val();
                table.columns(7).search('d_'+regex1+'_e').draw();
                $(this).addClass('text-primary');
                $("#dept_dropdown option").css('color', 'black')
            } else {
                location.reload();
            }
        });
    });
</script>
<script>
    function changeCoordinator(id,coordinator=null) {
        $('#compliance_id').val(id);
        if(coordinator){
            $('#legalCoordinator').val(coordinator).trigger('change');
        }
        $('#legalCoordinator').select2();
        $('#ChangeLegalCoordinatorModal').modal('show');
    }
    $('#resetCoordinatorModal').on('click', function() {
        $('#legalCoordinator').val(null).trigger('change');
    });
    var error_selector_coordinator = $("#dangerBtnCoordinator");
    function showSuccessCoordinator() {
        $("#successBtnCoordinator").show();
        $("#dangerBtnCoordinator").hide().empty();
    }

    function showErrorCoordinator() {
        $("#dangerBtnCoordinator").show();
        $("#successBtnCoordinator").hide();
    }
    //Form Submission with AJAX for Adding Regulatory Authority
    function changeCoordinatorSubmit() {
        $compliance_id = $('#compliance_id').val();
        $legalCoordinator = $('#legalCoordinator').val();
        // Proceed with AJAX submission
        Swal.fire({
            title: 'Are you sure?',
            text: 'Are you sure to change the legal coordinator?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, change it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.origin + '/user/compliance/change-legal-coordinator',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        'compliance_id': $compliance_id,
                        'legalCoordinator': $legalCoordinator
                    },
                    beforeSend: function() {
                        let $btn = $('#form_id').find('button[type="submit"]');
                        $btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm mr-2"></span>Changing...'
                        );
                    },
                    complete: function() {
                        let $btn = $('#form_id').find('button[type="submit"]');
                        $btn.prop('disabled', false).html('Change');
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            showSuccessCoordinator();
                            setTimeout(() => {
                                $('#ChangeLegalCoordinatorModal').modal('hide');
                                $("#successBtn").hide();
                                $('#legalCoordinator').val(null).trigger('change');
                                window.location.reload();
                            }, 700);
                        } else {
                            showErrorCoordinator();
                            error_selector_coordinator.html(`<li>${response.message}</li>`);
                        }
                    },
                    error: function(err) {
                        showErrorCoordinator();
                        error_selector_coordinator.empty();
                        if (err.status === 422) {
                            $.each(err.responseJSON.errors, function(key, value) {
                                error_selector_coordinator.append(`<li>${value}</li>`);
                            });
                        } 
                        else {
                            error_selector_coordinator.append(`<li>${err.responseJSON?.message || 'Server error occurred.'}</li>`);
                        }
                    }
                });
            }
        });
        return false;
    }
</script>
<script>
    $('.select_responsible').select2();
    $('.select_frequency').select2();
</script>

@endsection