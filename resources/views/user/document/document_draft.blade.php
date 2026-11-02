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
            <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
                @csrf

                <button title="Pdf" onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
                <input type="hidden" name="paper" id="paper" value="a4">
                <input type="hidden" name="orientation" id="orientation" value="portrait">
                <input type="hidden" name="" id="current_user_name" value="{{ Auth::user()->name }}">
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>

    </ul>
</div>
@endsection
@section('content')

    <style>
        .select2-results__options,
        .select2 {
            font-size: 13px;
        }
        .headingselect {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: none;
            line-height: 40px;
            font-size: 10px !important;
            position: relative;
            display: inline-block;
            text-align: center;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            white-space: break-spaces;
            font-weight: 600;
        }
        #dateselectdata .nested,
        #dateselectdata1 .nested {
            display: none;
            padding-left: 20px;
        }
        #dateselectdata .filter-container,
        #dateselectdata1{
            background-color: #fff;
        }
        #dateselectdata .filter-header,
        #dateselectdata1 .filter-header {
            cursor: pointer;
            font-size: 10px;
        }
        #dateselectdata .filter-content,
        #dateselectdata1 .filter-content {
            display: none;
            position: absolute;
            background: white;
            width: 100%;
            border: 1px solid #e1e6e9;
            box-shadow: 2px 2px 2px #e1e6e9;
            max-height: 200px;
            overflow: scroll;
        }
        #dateselectdata .date-filter,
        #dateselectdata1 .date-filter {
            padding: 5px 5px;
            cursor: pointer;
            text-align: left;
            transition: background-color 0.3s;
            position: relative;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        #dateselectdata .date-filter.month,
        #dateselectdata1 .date-filter.month {
            padding: 5px 15px;
            border-left: 1px dashed;
        }
        #dateselectdata .date-filter.day,
        #dateselectdata1 .date-filter.day {
            padding: 5px 30px;
            border-left: 1px dashed;
        }
        #dateselectdata .date-filter.month::before,#dateselectdata .date-filter.day::before,
        #dateselectdata1 .date-filter.month::before,#dateselectdata1 .date-filter.day::before {
            content: " ";
            border-bottom: 1px dashed black;
            position: absolute;
            left: 0px;
            top: 12px;
        }
        #dateselectdata .date-filter.month::before,
        #dateselectdata1 .date-filter.month::before {
            width: 15px;
        }
        #dateselectdata .date-filter.day::before,
        #dateselectdata1 .date-filter.day::before {
            width: 29px;
        }
        #dateselectdata .expand-icon,
        #dateselectdata1 .expand-icon {
            padding: 0.5px 5px;
            border: 1px solid #d9e2f6;
            margin-right: 2px;
            color: #24499c;
        }
        #dateselectdata .nested,
        #dateselectdata1 .nested {
            display: none;
            padding-left: 15px;
        }
        #dateselectdata .nested:not(.year),
        #dateselectdata1 .nested:not(.year) {
            border-left: 1px dashed;
        }
        #tabledata_wrapper {
            min-height: 250px;
        }
        #dateselectdata .date-filter input,
        #dateselectdata1 .date-filter input {
            margin-right: 5px;
        }
        .heading-table {
            text-transform: capitalize !important;
            font-size: 10px !important;
            position:sticky !important;
            top:-1px;
            vertical-align: middle !important;
        }
        .heading-table:after {
            display: none !important;
        }
        .loading-modal, .loading-requestreviewapprove-modal {
            display: none;
            position: absolute;
            background: #80808085;
            width: 100%;
            height: 100%;
        }
        .loading-modal i, .loading-requestreviewapprove-modal i {
            position: absolute;
            top: 50%;
            left: 50%;
            font-size: 50px;
        }
    </style>
    <style>
        .disabled-link {
            pointer-events: none;
            color: #999;
            text-decoration: none;
        }
        .table thead th {
            border: 1px solid #e1e6e9;
            border-bottom: 2px solid #e1e6e9;
            border-left-width: 0;
        }
        .table-responsive {
            padding-bottom: 50px;
        }
    </style>
    <script src="{{ asset('js/jquery.min.js') }}"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="main-container">
        <!-- Page Content -->
        <div class="content" style="width:100%;">
            @if(Session::has('msg'))
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert">×</a>
                {!!Session::get('msg')!!}
            </div>
            @endif
            <div class="alert alert-primary" id="trashsuccess" style="display:none;font-size:12px;">
                Draft moved to trash successfully
            </div>
            <div class="alert alert-danger" id="trashdanger" style="display:none;font-size:12px;">
               Draft deletion process failed
           </div>
            <!-- Topics -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <div class="block-options" style="margin-left:-25px;">
                        <a class="btn btn-sm btn-info" href="{{ route('doc.governancehub')}}">
                            <i class="fa fa-x fa-angle-left mr-1"></i>&nbsp;Back
                        </a>

                    </div>
                    <div>
                        <a href="{{ route('doc.trashdraftlist') }}" class="btn btn-sm btn-alt-primary" title="Trash"><i class="far fa-2x fa-trash-alt" style="font-size:15px;"></i>&nbsp;Trash</a>
                    </div>
                </div>
                <div class="block-content">
                    <!-- Content tab starts -->
                        <div class="block block-rounded">
                            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-tab-name="new" href="#btabs-alt-static-home">New</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-tab-name="change" href="#btabs-alt-static-profile">Change</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <!-- New Draft content starts -->
                                    <div class="tab-pane active" id="btabs-alt-static-home" role="tabpanel">
                                        <div class="table-responsive" id="content">
                                            <table class="table table-striped table-bordered table-vcenter js-dataTable-full" id="tabledata">
                                                <thead class="border-bottom">
                                                    <tr>
                                                        <th class="heading-table" data-orderable="false" style="width:2%;"></th>
                                                        <th class="d-md-table-cell text-center heading-table" data-orderable="false" style="width:5%;">S.No</th>
                                                        <th id="draftedbyselectdata" class="heading-table d-md-table-cell text-center" data-orderable="false" style="width:15%;">Drafted By</th>
                                                        <th class="d-md-table-cell text-center heading-table" data-orderable="false" style="width:20%;">Document Name</th>
                                                        <th id="typeselectdata" class="text-center heading-table" data-orderable="false" style="width:10%;"> </th>
                                                        <th id="dateselectdata" class=" d-md-table-cell text-center heading-table" data-orderable="false" style="width: 10%;">Dated</th>
                                                        <th id="deptselectdata" class="text-center heading-table" data-orderable="false" style="width:15%;"> </th>
                                                        <th id="statusselectdata" class="text-center heading-table" data-orderable="false" style="width:15%;"> </th>
                                                        <th class="text-center d-md-table-cell heading-table" data-orderable="false" style="width: 10%;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $no = 1; @endphp
                                                    @foreach($newdatas as $data)
                                                    <tr>
                                                        @php
                                                        $drafted_name = \App\User::where('email', $data->drafted_by)->first();
                                                        $reviwers_arr = explode(',',$data->reviewer);
                                                        @endphp
                                                        <td><input class="checkbox" type="checkbox" id="{{$data->id}}" name="id[]" ></td>
                                                        <td class="text-center" style="font-size: 10px;">{{ $loop->index + 1 }}</td>
                                                        <td class="text-center" style="font-size: 10px;">
                                                            @if($drafted_name)
                                                            <span>{{ $drafted_name->name  }}</span>
                                                            <span class="d-none">
                                                                {{ 'db_' . $drafted_name->name . '_e' }}
                                                            </span>
                                                            @endif
                                                        </td>
                                                        <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                            <a href="{{ route('doc.procedures_draft',['id' => $data->id]) }}">{{ ucfirst($data->document_name) }}</a>
                                                        </td>
                                                        <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                            <span>{{ $data->doctype ? $data->doctype->typename : ucwords(str_replace("_", " ", $data->type)) }}</span>
                                                        </td>
                                                        <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                            <span>{{ date('d-m-Y',strtotime($data->date)) }}</span>
                                                        </td>
                                                        <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                            @if(is_numeric($data->owner))
                                                            {{ $data->department ? $data->department->departmentname : '--'}}
                                                                <span class="d-none">
                                                                    {{ $data->department ? 'd_' . $data->department->departmentname : '' }}
                                                                </span>
                                                            @else
                                                            {{$data->owner}}
                                                            @endif
                                                        </td>
                                                        <td class="text-center d-sm-table-cell" style="font-size: 10px;">
                                                            @if(($data->status=='Review Requested' || $data->status=='Draft Reviewed') && $data->reviewed_by=='yes')
                                                                @php
                                                                $total_reviewers= count(explode(',',$data->reviewer));
                                                                $total_reviewed_by = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                        ->where('reviewed_status',true)
                                                                                                                        ->get()
                                                                                                                        ->count();

                                                                @endphp
                                                            <span style="color:#1fa98a;font-weight:500;">Draft Reviewed({{$total_reviewed_by}}/{{$total_reviewers}})</span>
                                                                <span style="display: none">s_Draft Reviewed</span>
                                                            @elseif($data->status=='Draft Reviewed' && $data->reviewed_by==NULL)
                                                              <span style="color:#1fa98a;font-weight:500;">Draft Reviewed</span>
                                                                <span style="display: none">s_Draft Reviewed</span>
                                                            @elseif($data->status=='Review Requested')
                                                              <span style="color:#800088;font-weight:500;">{{$data->status}}</span>
                                                                <span style="display: none">s_Review Requested</span>
                                                            @elseif($data->status=='Under Draft')
                                                              <span> {{$data->status}}</span>
                                                                <span style="display: none">s_Under Draft</span>
                                                            @elseif($data->status=='Draft Approved')
                                                              <span style="color:#007bff;font-weight:500;"> {{$data->status}}</span>
                                                                <span style="display: none">s_Draft Approved</span>
                                                            @else
                                                              <span style="color:red;">{{$data->status}}</span>
                                                                <span style="display: none">s_{{$data->status}}</span>
                                                            @endif
                                                        </td>
                                                        <div class="dropdown font-size-sm">
                                                            <td class="d-md-table-cell ">
                                                                <div class="btn-group m-auto d-flex justify-content-center">
                                                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}">
                                                                        @php
                                                                            $email = Auth::user()->email;
                                                                            $docAccess = \App\Doccontroller::where('email', $email)->first();
                                                                            $doc_privileged = $docAccess?true:false;
                                                                            $draft_content_count = App\GovernanceHubDraftContent::where('document_id',$data->id)
                                                                                                                                 ->where('is_deleted',false)
                                                                                                                                 ->get()->count();
                                                                            $reviewers_count = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                 ->get()->count();
                                                                        @endphp
                                                                        @if($draft_content_count==0 || $data->reviewer==NULL || $reviewers_count==0)
                                                                          @if($data->drafted_by==Auth::user()->email || $doc_privileged)
                                                                            <a href="{{ route('doc.add_content_draft', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;color:black;">
                                                                                <i class="fa fa-1x fa-plus" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                                            <a href="{{ route('doc.draftuploadcontent', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;">
                                                                                <i class="fa fa-cloud-upload-alt" style="color:grey;"></i>&nbsp;&nbsp;Upload Content</a>
                                                                            @else
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                <i class="fa fa-1x fa-plus text-gray" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                <i class="fa fa-cloud-upload-alt text-gray"></i>&nbsp;&nbsp;Upload Content</a>
                                                                          @endif
                                                                        @elseif($draft_content_count >0 && ($data->status=='Review Requested' || $data->status=='Draft Reviewed' || $data->status=='Draft Approved'))
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                <i class="fa fa-1x fa-plus text-gray" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                <i class="fa fa-cloud-upload-alt text-gray"></i>&nbsp;&nbsp;Upload Content</a>
                                                                        @endif
                                                                       @if($doc_privileged && $data->status=='Draft Approved')
                                                                       <a href="#" class="dropdown-item btn-release-document"
                                                                          style="font-size:10px;color:black;"
                                                                          data-document-type-id="{{ $data->doctype_id }}"
                                                                          data-document-id="{{ $data->id }}"
                                                                          data-document-name="{{ $data->document_name }}"
                                                                      >
                                                                             <i class="fa fa-1x fa-arrow-alt-circle-up text-dark"></i>&nbsp;&nbsp;Release Document</a>
                                                                       @endif

                                                                       @if($data->status=='Under Draft')
                                                                        @if($data->drafted_by==Auth::user()->email || $doc_privileged)
                                                                            <a href="#" class="dropdown-item btn btn-sm" style="font-size:10px;color:black;" data-toggle="modal"  data-target="#requestreviewapprove" onclick="getidvalue('{{$data->id}}')">
                                                                                <i class="fa fa-1x fa-user-edit" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval
                                                                            </a>
                                                                        @else
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                        @endif

                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fas fa-check-circle fa-1x text-gray" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>
                                                                        @elseif($data->status=='Review Requested' && in_array(Auth::user()->email,$reviwers_arr))
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                            @php
                                                                              $reviwer_id = App\User::where('email',Auth::user()->email)->value('id');
                                                                              $revwr_success = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                    ->where('reviewer_id',$reviwer_id)
                                                                                                                                    ->where('reviewed_status',true)
                                                                                                                                    ->first();
                                                                            @endphp
                                                                            @if($revwr_success)
                                                                               <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                            @else
                                                                                <a href="{{ route('doc.review_draft', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;color:black;"><i class="fa fa-1x fa-eye" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                            @endif
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fas fa-check-circle fa-1x text-gray" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>

                                                                        @elseif($data->status=='Draft Reviewed' && $data->approvers==Auth::user()->email)
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                        <a href="{{ route('doc.approve_draft_details', ['id' => $data->id])}}" class="dropdown-item" style="font-size:10px;color:black;"><i class="fas fa-check-circle fa-1x" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>
                                                                        @else
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                        <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fas fa-check-circle fa-1x text-gray" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>
                                                                        @endif

                                                                        @if($doc_privileged && $data->status=='Review Requested')
                                                                        <a href="#" class="dropdown-item btn btn-sm" style="font-size:10px;color:black;" data-toggle="modal"  data-target="#changereviewers" onclick="passidvalue('{{$data->id}}')">
                                                                           <i class="fa fa-exchange-alt" style="color:grey;"></i>&nbsp;&nbsp;Change Reviewers</a>
                                                                         @endif

                                                                         @if($doc_privileged && $data->status=='Draft Reviewed')
                                                                        <a href="#" class="dropdown-item btn btn-sm" style="font-size:10px;color:black;" data-toggle="modal"  data-target="#changeapprovers" onclick="passid('{{$data->id}}')">
                                                                           <i class="fa fa-exchange-alt" style="color:grey;"></i>&nbsp;&nbsp;Change Approvers</a>
                                                                         @endif
                                                                        @if($data->status=='Review Requested' || $data->status=='Draft Reviewed' || $data->status=='Draft Approved')
                                                                           <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fa fa-1x fa-edit text-gray"></i>&nbsp;&nbsp;Edit</a>
                                                                           <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fas fa-1x fa-trash text-gray"></i>&nbsp;&nbsp;Delete</a>
                                                                        @else
                                                                          @if($data->drafted_by==Auth::user()->email || in_array(Auth::user()->email,$reviwers_arr) || $doc_privileged || $data->approvers==Auth::user()->email)
                                                                           <a href="{{route('doc.editdraft',['id' => $data->id])}}" style="font-size:10px;color:black;" class="dropdown-item"><i class="fa fa-1x fa-edit" style="color:grey;"></i>&nbsp;&nbsp;Edit</a>
                                                                           <a href="#" style="font-size:10px;color:black;" class="dropdown-item" onclick="trashdoc('{{$data->id}}')"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                                          @else
                                                                           <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fa fa-1x fa-edit text-gray"></i>&nbsp;&nbsp;Edit</a>
                                                                           <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fas fa-1x fa-trash text-gray"></i>&nbsp;&nbsp;Delete</a>
                                                                          @endif
                                                                        @endif
                                                                    </div>
                                                                    <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{$data->id}}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn('{{$data->id}}')">
                                                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                                    </a>
                                                                </div>
                                                                <script>
                                                                    document.querySelectorAll('.disabled-link').forEach(function(link) {
                                                                        link.addEventListener('click', function(event) {
                                                                            event.preventDefault();
                                                                        });
                                                                    });
                                                                </script>

                                                                <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                    <div class="modal-dialog modal-lg">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h2 class="modal-title">Detailed View</h2>
                                                                            </div>
                                                                            <table class="table table-striped">
                                                                                <tr>
                                                                                    <th style="width:150px;">Drafted By</th>
                                                                                    @if($drafted_name)
                                                                                    <td>{{ $drafted_name->name}}</td>
                                                                                    @endif
                                                                                </tr>

                                                                                <tr>
                                                                                    <th>Document Name</th>
                                                                                    <td>{{ $data->document_name }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Type</th>
                                                                                    <td>{{ $data->doctype ? $data->doctype->typename : ucwords(str_replace("_", " ", $data->type)) }}</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th>Date</th>
                                                                                    <td>{{ date('d-m-Y',strtotime($data->date)) }}</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <th>Owner</th>
                                                                                    <td>
                                                                                        @if(is_numeric($data->owner))
                                                                                        {{ $data->department ? $data->department->departmentname : '--'}}
                                                                                        @else
                                                                                        {{$data->owner}}
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Reviewers</th>
                                                                                    <td class="d-sm-table-cell">
                                                                                        @if($data->reviewer!=NULL)
                                                                                            @php
                                                                                                $revexists = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)->get();
                                                                                            @endphp
                                                                                            @if(count($revexists)>0)
                                                                                                @foreach($revexists as $rexists)
                                                                                                 {{$rexists->reviewerdetails ? ucfirst($rexists->reviewerdetails->name) : ''}},
                                                                                                @endforeach
                                                                                            @else
                                                                                                @php
                                                                                                  $reviewer_emails=explode(',',$data->reviewer);
                                                                                                @endphp
                                                                                                @foreach($reviewer_emails as $reviewer_email)
                                                                                                    @php
                                                                                                      $rev_name = App\User::where('email',$reviewer_email)->value('name');
                                                                                                    @endphp
                                                                                                      {{$rev_name ? ucfirst($rev_name) : ''}} ,
                                                                                                @endforeach
                                                                                            @endif
                                                                                        @else
                                                                                         --
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Approvers</th>
                                                                                    <td class="d-sm-table-cell">
                                                                                        @if($data->approvers!=NULL)
                                                                                         {{$data->approverdetails ? $data->approverdetails->name : $data->approvers}}
                                                                                        @else
                                                                                         --
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Reviewed by</th>
                                                                                    <td class="d-sm-table-cell">
                                                                                        @php
                                                                                            $revedexists = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                              ->where('reviewed_status',true)
                                                                                                                                              ->get();
                                                                                        @endphp
                                                                                        @if(count($revedexists)>0)
                                                                                            @foreach($revedexists as $revedexist)
                                                                                                @php
                                                                                                    $revd_name = App\User::where('id',$revedexist->reviewer_id)->value('name');
                                                                                                @endphp
                                                                                                      {{$revd_name ? ucfirst($revd_name) : ''}} <span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($revedexist->reviewed_date))}})</span> ,

                                                                                            @endforeach
                                                                                        @else
                                                                                         --
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Status</th>
                                                                                    <td class="d-sm-table-cell">
                                                                                        @if(($data->status=='Review Requested' || $data->status=='Draft Reviewed') && $data->reviewed_by=='yes')
                                                                                            @php
                                                                                            $total_reviewers= count(explode(',',$data->reviewer));
                                                                                            $total_reviewed_by = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                                    ->where('reviewed_status',true)
                                                                                                                                                    ->get()
                                                                                                                                                    ->count();

                                                                                            @endphp
                                                                                        <span style="color:#1fa98a;font-weight:500;">Draft Reviewed({{$total_reviewed_by}}/{{$total_reviewers}})</span>
                                                                                        @elseif($data->status=='Draft Reviewed' && $data->reviewed_by==NULL)
                                                                                        <span style="color:#1fa98a;font-weight:500;">Draft Reviewed</span>
                                                                                        @elseif($data->status=='Review Requested')
                                                                                        <span style="color:#800088;font-weight:500;">{{$data->status}}</span>
                                                                                        @elseif($data->status=='Under Draft')
                                                                                        <span> {{$data->status}}</span>
                                                                                        @elseif($data->status=='Draft Approved')
                                                                                        <span style="color:#007bff;font-weight:500;"> {{$data->status}}</span>
                                                                                        @else
                                                                                        <span style="color:red;">{{$data->status}}</span>
                                                                                        @endif
                                                                                        <span class="d-none">s_{{$data->status}}</span>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                                            </div>
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
                                <!-- Ends New Draft content --->
                                <!-- Change Draft content starts -->
                                    <div class="tab-pane" id="btabs-alt-static-profile" role="tabpanel">
                                        <div class="table-responsive" id="content">
                                                <table class="table table-striped table-bordered table-vcenter js-dataTable-full" id="tabledata1">
                                                    <thead class="border-bottom">
                                                        <tr>
                                                            <th data-orderable="false" class="heading-table" style="width:2%;"></th>
                                                            <th class="heading-table d-md-table-cell text-center" data-orderable="false" style="width:5%;">S.No</th>
                                                            <th id="draftedbyselectdata1" class="heading-table d-md-table-cell text-center" data-orderable="false" style="width:15%;">Drafted By</th>
                                                            <th class="heading-table d-md-table-cell text-center" data-orderable="false" style="width:20%;">Document Name</th>
                                                            <th id="typeselectdata1" class="text-center heading-table" data-orderable="false" style="width:10%;"> </th>
                                                            <th id="dateselectdata1" class=" d-md-table-cell text-center heading-table" data-orderable="false" style="width: 10%;">Dated</th>
                                                            <th id="deptselectdata1" class="text-center heading-table" data-orderable="false" style="width:15%;"> </th>
                                                            <th id="statusselectdata1" class="text-center heading-table" data-orderable="false" style="width:15%;"> </th>
                                                            <th class="text-center d-md-table-cell heading-table" data-orderable="false" style="width: 10%;">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach($changedatas as $data)
                                                        <tr>
                                                            @php
                                                            $drafted_name = \App\User::where('email', $data->drafted_by)->first();
                                                            $reviwers_arr = explode(',',$data->reviewer);
                                                            @endphp
                                                            <td><input class="checkbox" type="checkbox" id="{{$data->id}}" name="id[]" ></td>
                                                            <td class="text-center" style="font-size: 10px;">{{ $loop->index + 1 }}</td>
                                                            <td class="text-center" style="font-size: 10px;">
                                                                @if($drafted_name)
                                                                <span>{{ $drafted_name->name  }}</span>
                                                                <span class="d-none">
                                                                    {{ 'db_' . $drafted_name->name . '_e' }}
                                                                </span>
                                                                @endif
                                                            </td>
                                                            <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                                <a href="{{ route('doc.procedures_draft',['id' => $data->id]) }}">{{ $data->document_name }}</a>
                                                            </td>
                                                            <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                                <span>
                                                                    {{ $data->doctype ? $data->doctype->typename : ucwords(str_replace("_", " ", $data->type)) }}
                                                                </span>
                                                            </td>
                                                            <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                                <span>{{ date('d-m-Y',strtotime($data->date)) }}</span>
                                                            </td>
                                                            <td class=" d-md-table-cell text-center" style="font-size: 10px;">
                                                                @if(is_numeric($data->owner))
                                                                {{ $data->department ? $data->department->departmentname : '--'}}
                                                                <span class="d-none">
                                                                    {{ $data->department ? 'd_' . $data->department->departmentname : '' }}
                                                                </span>
                                                                @else
                                                                {{$data->owner}}
                                                                @endif
                                                            </td>
                                                            <td class="text-center d-sm-table-cell" style="font-size: 10px;">
                                                                @if(($data->status=='Review Requested' || $data->status=='Draft Reviewed') && $data->reviewed_by=='yes')
                                                                    @php
                                                                    $total_reviewers = count(explode(',',$data->reviewer));
                                                                    $total_reviewed_by = $data->documentHubDraftReviewerDetails
                                                                                            ? $data->documentHubDraftReviewerDetails->where('reviewed_status',true)->count()
                                                                                            : 0;
                                                                    @endphp
                                                                <span style="color:#1fa98a;font-weight:500;">Draft Reviewed({{$total_reviewed_by}}/{{$total_reviewers}})</span>
                                                                @elseif($data->status=='Draft Reviewed' && $data->reviewed_by==NULL)
                                                                  <span style="color:#1fa98a;font-weight:500;">Draft Reviewed</span>
                                                                @elseif($data->status=='Review Requested')
                                                                  <span style="color:#800088;font-weight:500;">{{$data->status}}</span>
                                                                @elseif($data->status=='Under Draft')
                                                                  <span> {{$data->status}}</span>
                                                                @elseif($data->status=='Draft Approved')
                                                                  <span style="color:#007bff;font-weight:500;"> {{$data->status}}</span>
                                                                @else
                                                                  <span style="color:red;">{{$data->status}}</span>
                                                                @endif
                                                                <span class="d-none">s_{{$data->status}}</span>
                                                            </td>
                                                            <div class="dropdown font-size-sm">
                                                                <td class="d-md-table-cell ">
                                                                    <div class="btn-group m-auto d-flex justify-content-center">
                                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$data->id}}">
                                                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $data->id }}">
                                                                            @php
                                                                                $email = Auth::user()->email;
                                                                                $docAccess = \App\Doccontroller::where('email', $email)->first();
                                                                                $doc_privileged = $docAccess?true:false;
                                                                            @endphp
                                                                           @if($doc_privileged && $data->status=='Draft Approved')
                                                                           <a href="{{ route('doc.releasechangerequestdraft', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;color:black;">
                                                                                 <i class="fa fa-1x fa-arrow-alt-circle-up text-dark"></i>&nbsp;&nbsp;Release Document</a>
                                                                           @endif
                                                                           @if($data->status=='Review Requested' || $data->status=='Draft Reviewed' || $data->status=='Draft Approved')
                                                                             <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                <i class="fa fa-1x fa-plus text-gray" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                                             <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                <i class="fa fa-cloud-upload-alt text-gray"></i>&nbsp;&nbsp;Upload Content</a>
                                                                           @else
                                                                               @if($data->drafted_by==Auth::user()->email || $doc_privileged)
                                                                                <a href="{{ route('doc.add_content_draft', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;color:black;">
                                                                                    <i class="fa fa-1x fa-plus" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                                                <a href="{{ route('doc.draftuploadcontent', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;color:black;">
                                                                                    <i class="fa fa-cloud-upload-alt" style="color:grey;"></i>&nbsp;&nbsp;Upload Content</a>
                                                                                @else
                                                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                    <i class="fa fa-1x fa-plus text-gray" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                                                    <i class="fa fa-cloud-upload-alt text-gray"></i>&nbsp;&nbsp;Upload Content</a>
                                                                                @endif
                                                                           @endif

                                                                           @if($data->status=='Under Draft')
                                                                             @if($data->drafted_by==Auth::user()->email || $doc_privileged)
                                                                                <a href="#" class="dropdown-item btn btn-sm" style="font-size:10px;color:black;" data-toggle="modal"  data-target="#requestreviewapprove" onclick="getidvalue('{{$data->id}}')">
                                                                                    <i class="fa fa-1x fa-user-edit" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval
                                                                                </a>
                                                                             @else
                                                                               <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                             @endif

                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fas fa-check-circle fa-1x text-gray" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>
                                                                            @elseif($data->status=='Review Requested' && in_array(Auth::user()->email,$reviwers_arr))
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                                @php
                                                                                  $reviwer_id = App\User::where('email',Auth::user()->email)->value('id');
                                                                                  $revwr_success = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                        ->where('reviewer_id',$reviwer_id)
                                                                                                                                        ->where('reviewed_status',true)
                                                                                                                                        ->first();
                                                                                @endphp
                                                                                @if($revwr_success)
                                                                                   <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                                @else
                                                                                    <a href="{{ route('doc.review_draft', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;color:black;"><i class="fa fa-1x fa-eye" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                                @endif
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fas fa-check-circle fa-1x text-gray" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>

                                                                            @elseif($data->status=='Draft Reviewed' && $data->approvers==Auth::user()->email)
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                            <a href="{{ route('doc.approve_draft_details', ['id' => $data->id])}}" class="dropdown-item" style="font-size:10px;color:black;"><i class="fas fa-check-circle fa-1x" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>
                                                                            @else
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-user-edit text-gray" style="color: grey;"></i>&nbsp;&nbsp;Request Review and Approval</a>
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-eye text-gray" style="color: grey;"></i>&nbsp;&nbsp;Review</a>
                                                                            <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fas fa-check-circle fa-1x text-gray" style="color: grey;"></i>&nbsp;&nbsp;Approve</a>
                                                                            @endif

                                                                            @if($doc_privileged && $data->status=='Review Requested')
                                                                            <a href="#" class="dropdown-item btn btn-sm" style="font-size:10px;color:black;" data-toggle="modal"  data-target="#changereviewers" onclick="passidvalue('{{$data->id}}')">
                                                                               <i class="fa fa-exchange-alt" style="color:grey;"></i>&nbsp;&nbsp;Change Reviewers</a>
                                                                             @endif

                                                                             @if($doc_privileged && $data->status=='Draft Reviewed')
                                                                            <a href="#" class="dropdown-item btn btn-sm" style="font-size:10px;color:black;" data-toggle="modal"  data-target="#changeapprovers" onclick="passid('{{$data->id}}')">
                                                                               <i class="fa fa-exchange-alt" style="color:grey;"></i>&nbsp;&nbsp;Change Approvers</a>
                                                                             @endif
                                                                            @if($data->status=='Review Requested' || $data->status=='Draft Reviewed' || $data->status=='Draft Approved')
                                                                               <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fa fa-1x fa-edit text-gray"></i>&nbsp;&nbsp;Edit</a>
                                                                               <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fas fa-1x fa-trash text-gray"></i>&nbsp;&nbsp;Delete</a>
                                                                            @else
                                                                             @if($data->drafted_by==Auth::user()->email || in_array(Auth::user()->email,$reviwers_arr) || $doc_privileged || $data->approvers==Auth::user()->email)
                                                                               <a href="{{route('doc.editdraft',['id' => $data->id])}}" style="font-size:10px;color:black;" class="dropdown-item"><i class="fa fa-1x fa-edit" style="color:grey;"></i>&nbsp;&nbsp;Edit</a>
                                                                               <a href="#" style="font-size:10px;color:black;" class="dropdown-item" onclick="trashdoc('{{$data->id}}')"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                                             @else
                                                                               <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fa fa-1x fa-edit text-gray"></i>&nbsp;&nbsp;Edit</a>
                                                                               <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fas fa-1x fa-trash text-gray"></i>&nbsp;&nbsp;Delete</a>
                                                                             @endif
                                                                            @endif
                                                                        </div>
                                                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{$data->id}}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn('{{$data->id}}')">
                                                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                                                        </a>
                                                                    </div>
                                                                    <script>
                                                                        document.querySelectorAll('.disabled-link').forEach(function(link) {
                                                                            link.addEventListener('click', function(event) {
                                                                                event.preventDefault();
                                                                            });
                                                                        });
                                                                    </script>
                                                                    <input type="hidden" name="revval" id="revval" value="{{$data->reviewer}}"/>

                                                                    <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h2 class="modal-title">Detailed View</h2>
                                                                                </div>
                                                                                <table class="table table-striped">
                                                                                    <tr>
                                                                                        <th style="width:70px">Drafted By</th>
                                                                                        @if($drafted_name)
                                                                                        <td>{{ $drafted_name->name}}</td>
                                                                                        @endif
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th>Document Name</th>
                                                                                        <td>{{ $data->document_name }}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Type</th>
                                                                                        <td>
                                                                                            {{ $data->doctype ? $data->doctype->typename : ucwords(str_replace("_", " ", $data->type)) }}
                                                                                        </td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th>Date</th>
                                                                                        <td>{{ date('d-m-Y',strtotime($data->date)) }}</td>
                                                                                    </tr>

                                                                                    <tr>
                                                                                        <th>Owner</th>
                                                                                        <td>
                                                                                            @if(is_numeric($data->owner))
                                                                                            {{ $data->department ? $data->department->departmentname : '--'}}
                                                                                            @else
                                                                                            {{$data->owner}}
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Reviewers</th>
                                                                                        <td class="d-sm-table-cell">
                                                                                            @if($data->reviewer!=NULL)
                                                                                                @php
                                                                                                    $revexists = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)->get();
                                                                                                @endphp
                                                                                                @if(count($revexists)>0)
                                                                                                    @foreach($revexists as $rexists)
                                                                                                     {{$rexists->reviewerdetails ? ucfirst($rexists->reviewerdetails->name) : ''}},
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    @php
                                                                                                      $reviewer_emails=explode(',',$data->reviewer);
                                                                                                    @endphp
                                                                                                    @foreach($reviewer_emails as $reviewer_email)
                                                                                                        @php
                                                                                                          $rev_name = App\User::where('email',$reviewer_email)->value('name');
                                                                                                        @endphp
                                                                                                          {{$rev_name ? ucfirst($rev_name) : ''}} ,
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            @else
                                                                                             --
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Approvers</th>
                                                                                        <td class="d-sm-table-cell">
                                                                                            @if($data->approvers!=NULL)
                                                                                             {{$data->approverdetails ? $data->approverdetails->name : $data->approvers}}
                                                                                            @else
                                                                                             --
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Reviewed by</th>
                                                                                        <td class="d-sm-table-cell">
                                                                                            @php
                                                                                                $revedexists = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                                  ->where('reviewed_status',true)
                                                                                                                                                  ->get();
                                                                                            @endphp
                                                                                            @if(count($revedexists)>0)
                                                                                                @foreach($revedexists as $revedexist)
                                                                                                    @php
                                                                                                        $revd_name = App\User::where('id',$revedexist->reviewer_id)->value('name');
                                                                                                    @endphp
                                                                                                          {{$revd_name ? ucfirst($revd_name) : ''}} <span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($revedexist->reviewed_date))}})</span> ,

                                                                                                @endforeach
                                                                                            @else
                                                                                             --
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th>Status</th>
                                                                                        <td class="d-sm-table-cell">
                                                                                            @if(($data->status=='Review Requested' || $data->status=='Draft Reviewed') && $data->reviewed_by=='yes')
                                                                                                @php
                                                                                                $total_reviewers= count(explode(',',$data->reviewer));
                                                                                                $total_reviewed_by = App\GovernanceHubDraftReviewerDetail::where('draft_id',$data->id)
                                                                                                                                                        ->where('reviewed_status',true)
                                                                                                                                                        ->get()
                                                                                                                                                        ->count();

                                                                                                @endphp
                                                                                            <span style="color:#1fa98a;font-weight:500;">Draft Reviewed({{$total_reviewed_by}}/{{$total_reviewers}})</span>
                                                                                            @elseif($data->status=='Draft Reviewed' && $data->reviewed_by==NULL)
                                                                                            <span style="color:#1fa98a;font-weight:500;">Draft Reviewed</span>
                                                                                            @elseif($data->status=='Review Requested')
                                                                                            <span style="color:#800088;font-weight:500;">{{$data->status}}</span>
                                                                                            @elseif($data->status=='Under Draft')
                                                                                            <span> {{$data->status}}</span>
                                                                                            @elseif($data->status=='Draft Approved')
                                                                                            <span style="color:#007bff;font-weight:500;"> {{$data->status}}</span>
                                                                                            @else
                                                                                            <span style="color:red;">{{$data->status}}</span>
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                                                                </div>
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
                                <!-- Ends Change Draft content --->
                            </div>
                        </div>
                    <!-- END Content tab -->
                </div>
            </div>
            <!-- END Topics -->
        </div>
        <!-- END Page Content -->
    </div>

    <!-- Request Review and Approve modal starts -->
    <div id="requestreviewapprove" class="modal fadebd-example-modal-md" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="add_reviewer_approver_ajax" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="draft_id" value="" id="draft_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Request Review and Approval</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-primary" id="revappaddedsuccess" style="display:none;font-size:12px;">
                            The draft has been requested for review successfully.
                        </div>

                        <ul class="alert alert-danger" id="revappaddeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul>

                        <ul class="alert alert-danger" id="revappaddeddangernotadded" style="display:none"></ul>

                        <div class="form-group">
                          <label for="reviewer" class="col-sm-12 col-form-label" style="font-size:12px;">Choose Reviewer <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-control selectm"
                                        data-placeholder="Choose Reviewer"
                                        name="reviewer_value[]" id="reviewer_value"
                                style="font-size: 12px;width:100%;" multiple required>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 col-form-label" for="approver" style="font-size:12px;">Choose Approver <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="js-select2-single form-control" name="approver_value" id="approver_value"
                                        data-placeholder="Choose Approver"
                                style="font-size: 12px;width:100%;" required>
                                    <option disabled selected>Choose Approver</option>
                                    @if(count($rev)>0)
                                    @foreach($rev as $r)
                                    <option value="{{$r->email}}">{{$r->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" style="font-size:12px" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-submit-change-review-approval" style="font-size:12px">Submit</button>
                        </div>
                    </form>
                    <div class="loading-requestreviewapprove-modal">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
    </div>
    <!-- Ends Request Review and Approve modal -->

    <!-- Change Reviewers modal starts -->
    <div id="changereviewers" class="modal fadebd-example-modal-md" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="modify_reviewer_ajax" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="draft_rev_id" value="" id="draft_rev_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Reviewers</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-primary" id="reviwerchangedsuccess" style="display:none;font-size:12px;">
                            Reviewer Changed successfully
                        </div>

                        <ul class="alert alert-danger" id="reviwerchangeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul>

                        <ul class="alert alert-danger" id="reviwerchangeddangernotadded" style="display:none"></ul>

                        <div class="form-group">
                          <label for="reviewer" class="col-sm-12 col-form-label" style="font-size:12px;">Choose Reviewer <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="js-select2 form-control selectm" name="reviewer_value_modify[]" id="reviewer_value_modify"
                                style="font-size: 12px;width:100%;" multiple required>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" style="font-size:12px" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-change-reviewer" style="font-size:12px">Submit</button>
                        </div>
                    </form>
                    <div class="loading-modal">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
    </div>
    <!-- Ends Change Reviewers modal -->
    <!-- Change approvers modal starts -->
    <div id="changeapprovers" class="modal fadebd-example-modal-md" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="modify_approver_ajax" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="draft_app_id" value="" id="draft_app_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Approvers</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-primary" id="approverchangedsuccess" style="display:none;font-size:12px;">
                            Approver Changed successfully
                        </div>

                        <ul class="alert alert-danger" id="approverchangeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul>

                        <ul class="alert alert-danger" id="approverchangedangernotadded" style="display:none"></ul>

                        <div class="form-group">
                            <label class="col-sm-12 col-form-label" for="approver" style="font-size:12px;">Choose Approver <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <select class="js-select2-single form-control" name="approver_value_modify" id="approver_value_modify"
                                style="font-size: 12px;width:100%;" required>
                                   <option value="">Choose Approver</option>
                                    @if(count($rev)>0)
                                    @foreach($rev as $r)
                                    <option value="{{$r->email}}">{{$r->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" style="font-size:12px" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    <!-- Ends Change Reviewers modal -->
    <!-- Release Draft Document modal starts -->
    <div id="releasenewdraft" class="modal fadebd-example-modal-md" tabindex="-1" role="dialog" aria-hidden="false">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form id="release_draft_ajax" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="draft_release_id" value="" id="draft_release_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Document Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                         <div class="alert alert-primary" id="releasedocumentsuccess" style="display:none;font-size:12px;">
                            Document Released successfully
                        </div>


                        <ul class="alert alert-danger" id="releasedocumentdanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul>

                        <ul class="alert alert-danger" id="releasedocumentdangernotadded" style="display:none"></ul>

                        <div class="form-group">
                            <label class="col-sm-12 col-form-label" for="documentnumber" style="font-size:12px;">Document Number <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" style="font-size:12px;" name="document_number"
                                    id="document_number" placeholder="Enter Document Number" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12 col-form-label" for="documentname" style="font-size:12px;">Document Name <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                               <input type="text" class="form-control" style="font-size:12px;" name="document_name"
                                    id="document_name" placeholder="Enter Document Name" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark btn-reset-on-releasenewdraft" style="font-size:12px">Reset</button>
                            <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    <!-- Ends Release Draft Document modal -->

    <!-- Opens from the modal toggle button in the header -->
    @include('user.document.governance_hub_headers')
    <!-- END Apps Modal -->
    <script>
        function ViewBtn(id) {
            $('#viewModal' + id).modal('show');
        }

        function rrBtn(id) {
            $('#viewModal2' + id).modal('show');
        }

        // $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
        //     $(".alert-success").slideUp(500);
        // });
    </script>
    <script>
        function getidvalue(id){
            $('#draft_id').val(id);
            $.ajaxSetup({
                         headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
            $.ajax({
                    type: 'POST',
                    url: '/user/doc/selectdraftreviewers',
                    data: {"id_value": id}
                    ,beforeSend: function() {
                        $('#reviewer_value').empty();
                        $('.loading-requestreviewapprove-modal').show();
                    }
                    ,success: function(response) {
                        $('.loading-requestreviewapprove-modal').hide();
                        draftlist = response.drafts;
                        let selectElement = $('#reviewer_value');
                        selectElement.empty();
                        $.each(response.users, function (index, option) {
                            let optionElement = $('<option>', {
                                value: option.email,
                                text: option.name
                            });
                            selectElement.append(optionElement);
                        });

                        reviewerlist = draftlist['reviewer'];
                        if(reviewerlist!=null){
                            reviewerarr = reviewerlist.split(",");
                            for(let i=0;i<reviewerarr.length;i++){
                                let preSelectedemail = reviewerarr[i];
                                let option = selectElement.find('option[value="' + preSelectedemail + '"]');
                                if (option.length > 0) {
                                    option.attr('selected', true);
                                }
                            }
                        }
                    }
                    ,error: function(xhr, status, error) {
                        $('.loading-requestreviewapprove-modal').hide();
                        console.error(xhr.responseText);
                    }
            });
        }

        window.onload = function() {
            $(document).ready(function() {
                    $('#add_reviewer_approver_ajax').submit(function(event) {
                        $('.btn-submit-change-review-approval').attr('disabled', true);
                        event.preventDefault();
                        var formData = $(this).serialize();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                                type: 'POST',
                                url: '/user/doc/insert-reviewer-approver',
                                data: formData
                                ,success: function(response) {
                                    if(response.status == 200)
                                    {
                                        $('#revappaddeddanger').hide();
                                        $('#revappaddeddangernotadded').hide();
                                        $('#revappaddedsuccess').css('display', 'block');
                                        window.location.reload();
                                    }
                                }
                            ,error: function(err) {
                                $('.btn-submit-change-review-approval').attr('disabled', false);
                                if(err.status === 500) {
                                        $('#revappaddeddangernotadded').hide();
                                        $('#revappaddeddanger').css('display', 'block');
                                    }
                                    else if (err.status === 422) {
                                        var error_text='';
                                        $.each(err.responseJSON.errors, function(i) {
                                            $.each(err.responseJSON.errors[i], function(key, val) {
                                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                                            });
                                    });
                                        $('#revappaddeddangernotadded').html(error_text);
                                        $('#revappaddeddanger').hide();
                                        $('#revappaddeddangernotadded').css('display', 'block');
                                }
                            }
                        });
                    });
                });



              //--------- change reviewers starts-----------//
              $(document).ready(function() {
                    $('#modify_reviewer_ajax').submit(function(event) {
                        $('.btn-change-reviewer').attr('disabled', true);
                        event.preventDefault();
                        var formData = $(this).serialize();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                                type: 'POST',
                                url: '/user/doc/updatemodifieddraftreviewer',
                                data: formData
                                ,success: function(response) {
                                    if(response.status == 200)
                                    {
                                        $('#reviwerchangeddanger').hide();
                                        $('#reviwerchangeddangernotadded').hide();
                                        $('#reviwerchangedsuccess').css('display', 'block');
                                        window.location.reload();
                                    }
                                }
                            ,error: function(err) {
                                $('.btn-change-reviewer').attr('disabled', false);
                                if(err.status === 500) {
                                        $('#reviwerchangeddangernotadded').hide();
                                        $('#reviwerchangeddanger').css('display', 'block');
                                    }
                                    else if (err.status === 422) {
                                        var error_text='';
                                        $.each(err.responseJSON.errors, function(i) {
                                            $.each(err.responseJSON.errors[i], function(key, val) {
                                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                                            });
                                    });
                                        $('#reviwerchangeddangernotadded').html(error_text);
                                        $('#reviwerchangeddanger').hide();
                                        $('#reviwerchangeddangernotadded').css('display', 'block');
                                }
                            }
                        });
                    });
                });
              //------------ End change reviewers --------------//

              //--------- change approver starts-----------//
              $(document).ready(function() {
                    $('#modify_approver_ajax').submit(function(event) {
                        event.preventDefault();
                        var formData = $(this).serialize();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                                type: 'POST',
                                url: '/user/doc/updatemodifieddraftapprover',
                                data: formData
                                ,success: function(response) {
                                    if(response.status == 200)
                                    {
                                        $('#approverchangeddanger').hide();
                                        $('#approverchangedangernotadded').hide();
                                        $('#approverchangedsuccess').css('display', 'block');
                                        window.location.reload();
                                    }
                                }
                            ,error: function(err) {
                                if(err.status === 500) {
                                        $('#approverchangedangernotadded').hide();
                                        $('#approverchangeddanger').css('display', 'block');
                                    }
                                    else if (err.status === 422) {
                                        var error_text='';
                                        $.each(err.responseJSON.errors, function(i) {
                                            $.each(err.responseJSON.errors[i], function(key, val) {
                                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                                            });
                                    });
                                        $('#approverchangedangernotadded').html(error_text);
                                        $('#approverchangeddanger').hide();
                                        $('#approverchangedangernotadded').css('display', 'block');
                                }
                            }
                        });
                    });
                });
              //------------ End change approver --------------//

            // Check document number uniqueness on input
            // Debounce the input event to avoid too many requests
            const $docNumber = $('#document_number');
            let debounceTimer;
            $docNumber.on('input', function() {
                const docNumber = $(this).val();
                const $this = $(this);

                // Clear any existing timer
                clearTimeout(debounceTimer);

                // Remove validation styles immediately for better UX
                $this.removeClass('is-invalid');
                $this.next('.invalid-feedback').remove();

                if (docNumber) {
                    // Set a new timer with 500ms delay
                    debounceTimer = setTimeout(function() {
                        $.ajax({
                            url: "{{ route('doc.checkDocumentNumber') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                                'accept': 'application/json'
                            },
                            data: {
                                document_number: docNumber
                            },
                            success: function(response) {
                                if (response.exists) {
                                    $this.addClass('is-invalid');
                                    $this.next('.invalid-feedback').remove();
                                    $this.after('<div class="invalid-feedback">The document number has already been taken.</div>');
                                } else {
                                    $this.removeClass('is-invalid');
                                    $this.next('.invalid-feedback').remove();
                                }
                            },
                            error: function(err) {
                                console.error('Error checking document number:', err);
                            }
                        });
                    }, 500); // 500ms debounce delay
                }
            });

            //--------- release document starts-----------//
               $(document).ready(function() {
                   const initialReleaseNewDraftDocument = function(documentTypeId, documentId, documentName) {
                       const modal = $('#releasenewdraft');
                       const $docNumber = modal.find('#document_number');
                       modal.find('#draft_release_id').val(documentId);
                       modal.find('#document_name').val(documentName);
                       $docNumber.removeClass('is-invalid');
                       $docNumber.next('.invalid-feedback').remove();
                       modal.find('.btn-reset-on-releasenewdraft').attr('data-document-type-id', documentTypeId);
                       modal.find('.btn-reset-on-releasenewdraft').attr('data-document-id', documentId);
                       modal.find('.btn-reset-on-releasenewdraft').attr('data-document-name', documentName);
                       $.ajax({
                           url: "{{ route('doc.retrieveDocumentNumber') }}",
                           type: 'POST',
                           headers: {
                               'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                               'accept': 'application/json'
                           },
                           data: {
                               type: documentTypeId
                           },
                           success: function(response) {
                               $docNumber.val(response.document_number);
                           },
                           error: function(err) {
                               console.error('Error fetching document number:', err);
                               $docNumber.val("");
                           }
                       });
                   }
                    $('body').on('click', '.btn-release-document', function () {
                        const modal = $('#releasenewdraft');
                        const documentTypeId = $(this).attr('data-document-type-id');
                        const documentId = $(this).attr('data-document-id');
                        const documentName = $(this).attr('data-document-name');
                        initialReleaseNewDraftDocument(documentTypeId, documentId, documentName);
                        modal.modal('show');
                    }).on('click', '.btn-reset-on-releasenewdraft', function(){
                        const documentTypeId = $(this).attr('data-document-type-id');
                        const documentId = $(this).attr('data-document-id');
                        const documentName = $(this).attr('data-document-name');
                        initialReleaseNewDraftDocument(documentTypeId, documentId, documentName);
                    })
                    $('#release_draft_ajax').submit(function(event) {
                        event.preventDefault();
                        var formData = $(this).serialize();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                                type: 'POST',
                                url: '/user/doc/releasenewdraftdocument',
                                data: formData
                                ,success: function(response) {
                                    if(response.status == 200)
                                    {
                                        $('#releasedocumentdanger').hide();
                                        $('#releasedocumentdangernotadded').hide();
                                        $('#releasedocumentsuccess').css('display', 'block');
                                        setTimeout(
                                        function() {
                                            window.location.href = '/user/doc/governancehub';
                                        }, 2000);
                                    }


                                }
                            ,error: function(err) {
                                if(err.status === 500) {
                                        $('#releasedocumentdangernotadded').hide();
                                        $('#releasedocumentdanger').css('display', 'block');
                                    }
                                    else if (err.status === 422) {
                                        var error_text='';
                                        $.each(err.responseJSON.errors, function(i) {
                                            $.each(err.responseJSON.errors[i], function(key, val) {
                                                error_text += '<li style="font-size:12px;">' + val + '</li>';
                                            });
                                    });
                                        $('#releasedocumentdangernotadded').html(error_text);
                                        $('#releasedocumentdanger').hide();
                                        $('#releasedocumentdangernotadded').css('display', 'block');
                                }
                            }

                        });
                    });
                });
              //------------ End release document --------------//

        }

//--------- change reviewers starts-----------//
        function passidvalue(id){
            $('#draft_rev_id').val(id);
            $.ajaxSetup({
                         headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
            $.ajax({
                    type: 'POST',
                    url: '/user/doc/modifydraftreviewers',
                    data: {"id_value": id}
                    ,beforeSend: function() {
                        $('#reviewer_value_modify').empty();
                        $('.loading-modal').show();
                    }
                    ,success: function(response) {
                        $('.loading-modal').hide();
                        draftlist = response.drafts;
                        let selectElement = $('#reviewer_value_modify');
                        selectElement.empty();
                        $.each(response.users, function (index, option) {
                            let optionElement = $('<option>', {
                                value: option.email,
                                text: option.name
                            });
                            selectElement.append(optionElement);
                        });

                        reviewerlist = draftlist['reviewer'];
                        if(reviewerlist!=null){
                            reviewerarr = reviewerlist.split(",");
                            for(let i=0;i<reviewerarr.length;i++){
                                let preSelectedemail = reviewerarr[i];
                                let option = selectElement.find('option[value="' + preSelectedemail + '"]');
                                if (option.length > 0) {
                                    option.attr('selected', true);
                                }
                            }
                        }
                    }
                    ,error: function(xhr, status, error) {
                        $('.loading-modal').hide();
                        console.error(xhr.responseText);
                    }
            });
        }
//------------ End change reviewers --------------//

//--------- change approver starts-----------//
        function passid(id){
                    $('#draft_app_id').val(id);
                    $.ajaxSetup({
                                headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                    $.ajax({
                            type: 'POST',
                            url: '/user/doc/modifydraftapprovers',
                            data: {"id_value": id}
                            ,success: function(response) {
                                draftlist = response.drafts;
                                let selectElement = $('#approver_value_modify');
                                selectElement.empty();
                                $.each(response.users, function (index, option) {
                                    let optionElement = $('<option>', {
                                        value: option.email,
                                        text: option.name
                                    });
                                    selectElement.append(optionElement);
                                });

                                approverlist = draftlist['approvers'];
                                if(approverlist!=null){
                                        let preSelectedemail = approverlist;
                                        let option = selectElement.find('option[value="' + preSelectedemail + '"]');
                                        if (option.length > 0) {
                                            option.attr('selected', true);
                                        }
                                    }
                            }
                            ,error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                    });
                }
//------------ End change approver --------------//

//--------- release document starts---------------//
   function getcurrentdatas(draftdetails){
        let details_array = draftdetails.split(',');
        document.getElementById('draft_release_id').value=details_array[0];
        document.getElementById('document_name').value=details_array[1];
   }
//------------ End release document --------------//
    </script>
    <script>
        function trashdoc(id) {
            console.log(id);
            if (confirm('Are you sure you want to delete this draft?')) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('doc.trashdraft') }}",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.status == 200)
                        {
                            $('#trashdanger').hide();
                            $('#trashsuccess').css('display', 'block');
                            window.location.reload();
                        }
                        else if(response.status==404){
                            $('#trashdanger').css('display', 'block');
                        }
                    },
                    error: function(error) {
                        // Handle error, e.g., show an error message
                        alert('Draft has not been moved to the trash.')
                        console.error('Error deleting record: ' + error.statusText);
                    }
                });
            }
        }
    </script>
    <script src="{{ asset('js/datatable_extension_document.js') }}"></script>
    <script>
        const headerFilters = [
            {
                tableId: '#tabledata',
                column: 'drafted_by',
                data: "{{ $allnewdraftedbynames }}",
                label: 'Drafted by',
                columnIndex: 2,
                options: {
                    filter_prefix: 'db_',
                    filter_postfix: '_e'
                }
            },
            {
                tableId: '#tabledata',
                column: 'type',
                data: "{{ $allnewtypenames }}",
                label: 'Type',
                columnIndex: 4,
                options: {}
            },
            {
                tableId: '#tabledata',
                column: 'date',
                data: "{{ $allnewdatenames }}",
                label: 'Dated',
                columnIndex: 5,
                options: { is_date_filter: true }
            },
            {
                tableId: '#tabledata',
                column: 'owner',
                data: "{{ $allnewdeptnames }}",
                label: 'Owner',
                columnIndex: 6,
                options: { filter_prefix: 'd_' }
            },
            {
                tableId: '#tabledata',
                column: 'status',
                data: "{{ $allnewstatusnames }}",
                label: 'Status',
                columnIndex: 7,
                options: { filter_prefix: 's_' }
            }
        ];

        headerFilters.forEach(filter => {
            enableHeaderFilter(
                filter.tableId,
                filter.column,
                filter.data,
                filter.label,
                filter.columnIndex,
                filter.options
            );
        });
    </script>
    <script>
        const headerFiltersOfChangeTab = [
            {
                tableId: '#tabledata1',
                column: 'drafted_by',
                data: "{{ $allchangedraftedbynames }}",
                label: 'Drafted by',
                columnIndex: 2,
                options: {
                    filter_prefix: 'db_',
                    filter_postfix: '_e'
                }
            },
            {
                tableId: '#tabledata1',
                column: 'type',
                data: "{{ $allchangetypenames }}",
                label: 'Type',
                columnIndex: 4,
                options: {}
            },
            {
                tableId: '#tabledata1',
                column: 'date1',
                data: "{{ $allchangedatenames }}",
                label: 'Dated',
                columnIndex: 5,
                options: { is_date_filter: true }
            },
            {
                tableId: '#tabledata1',
                column: 'owner',
                data: "{{ $allchangedeptnames }}",
                label: 'Owner',
                columnIndex: 6,
                options: { filter_prefix: 'd_' }
            },
            {
                tableId: '#tabledata1',
                column: 'status',
                data: "{{ $allchangestatusnames }}",
                label: 'Status',
                columnIndex: 7,
                options: { filter_prefix: 's_' }
            }
        ];

        headerFiltersOfChangeTab.forEach(filter => {
            enableHeaderFilter(
                filter.tableId,
                filter.column,
                filter.data,
                filter.label,
                filter.columnIndex,
                filter.options
            );
        });
        $(function () {
            $('.js-select2').select2({
                width: '100%',
                multiple: true,
                placeholder: $(this).data('placeholder') || 'Select an option'
            });
            $('.js-select2-single').select2({
                width: '100%',
                multiple: false,
                placeholder: $(this).data('placeholder') || 'Select an option'
            });
        })
    </script>
@include('exporting.forgovernancehub_draf')
@section('notification')
  @include('user.document.governance_hub_draft_notification')
@endsection
@endsection
