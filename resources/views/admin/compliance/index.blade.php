@extends('admin.datatable')
@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/compliance.css') }}">
<script src="{{ asset('js/oneui.core.min.js') }}"></script>
<script src="{{ asset('js/oneui.app.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Page Content -->
<div class="content px-0" style="-webkit-print-color-adjust: exact">
    <!-- Horizontal -->
    <div class="block block-rounded" style="box-shadow: 0 0 15px;background-color: #ffffff;">
        <div class="block-content block-content-full">
            @if(Session::has('errmsg'))
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('errmsg')!!}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert">×</a>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(Session::has('msg'))
                <div class="alert alert-success">
                    <a class="close" data-dismiss="alert">×</a>
                    {!!Session::get('msg')!!}
                </div>
            @endif
            <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#tab-1" data-toggle="tab" id="tab1" style="color: #2e2eb8;font-size:13px;">Regulatory Authority Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-2" data-toggle="tab" style="color: grey;font-size:13px;" id="tab2">Pending Approvals Widget</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active mt-4" id="tab-1" role="tabpanel" aria-labelledby="regulatory-authority-management-tab">
                    <button id="addRac" class="btn btn-sm btn-outline-primary ml-auto d-flex mb-1" style="font-size:12px" data-toggle="modal" data-target="#AddRegulatoryModal">
                        <i class="fa fa-fw fa-plus mt-1"></i>&nbsp;<span>Add Regulatory</span>
                    </button>        
                    <div class="table-responsive">
                        <table id="regulatoryTable" class="table table-bordered table-striped ">
                            <thead>
                                <tr style="position: sticky; top: 0;z-index:5;">
                                    <th class="titleheading text-center" style="width: 5%" data-orderable="false"></th>
                                    <th class="titleheading text-center" style="width: 5%" data-orderable="false">S.No.</th>
                                    <th class="titleheading text-center" style="width:55%;" data-orderable="false">Name</th>
                                    <th id="statusheader" class="titleheading text-center" style="width:5%;" data-orderable="false">Status</th>
                                    <th class="titleheading text-center" style="width:25%;text-transform:none !important;" data-orderable="false">No. of Legal Documents</th>
                                    <th class="titleheading text-center" style="width:5%;" data-orderable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($authorities as $authority)
                                <tr class="text-center" style="font-size:12px;">
                                    <td class="text-center" style="font-size:11px;">
                                        <button class="btn btn-sm btn-alt-secondary toggle-row" style="font-size:10px;" data-id="{{ $authority->id }}">
                                            <i class="fa fa-chevron-down" style="font-size:10px;"></i>
                                        </button>
                                    </td>
                                    <td class="text-center" style="padding-top: 15px !important;">{{ $loop->index+1 }}</td>
                                    <td class="text-center" style="padding-top: 15px !important;">{{ $authority->name }}</td>
                                    <td class="text-center" style="padding-top: 15px !important;">
                                        @if($authority->status == 'Approved')
                                            <span class="status status-success">{{$authority->status}}</span>
                                        @elseif($authority->status == 'Pending Review')
                                            <span class="status status-warning">{{$authority->status}}</span>
                                        @else
                                            <span class="status status-red">{{$authority->status}}</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="padding-top: 15px !important;">{{count($authority->complianceLegalDocuments)}}</td>
                                    <td style="font-size:12px;" class="text-center">
                                        <div class="btn-group m-auto d-flex justify-content-center">
                                            <a href="#" class="btn btn-sm btn-alt-primary view-regulatory" data-id='{{$authority->id}}' data-toggle="tooltip" data-placement="top" title="View">
                                                <i class="fa fa-eye" style="font-size:11px;cursor:pointer"></i>
                                            </a>
                                            <a href="#" class="btn btn-sm btn-alt-primary edit-regulatory" data-id='{{$authority->id}}' data-toggle="tooltip" data-placement="top" title="Edit Regulatory Authority">
                                                <i class="fa fa-pencil-alt" style="font-size:11px;cursor:pointer"></i>
                                            </a>
                                            <a data-id="{{$authority->id}}" class="btn btn-sm btn-alt-primary delete-regulatory" data-toggle="tooltip" data-placement="top" title="Delete Regulatory Authority">
                                                <i class="far fa-trash-alt" style="font-size:11px;cursor:pointer"></i>
                                            </a>
                                        </div>
                                        
                                    </td>
                                </tr>
                                <!-- Nested Table -->
                                <tr class="nested-row d-none" id="nested-{{ $authority->id }}">
                                    <td colspan="6" class="m-0 p-1">
                                        <div class="p-3 bg-light rounded border">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="fw-bold mb-0" style="font-size:13px;">Legal Documents</h6>
                                                <a class="btn btn-sm btn-outline-primary" style="font-size:12px" onclick="addDocModal({{$authority->id}})" aria-label="Add Document">
                                                    <i class="fa fa-fw fa-plus mt-1"></i>&nbsp;<span>Add Document</span>
                                                </a>
                                            </div>
                                            <table class="table table-sm table-hover mb-0 table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="titleheading text-center" style="width: 5%;font-size:12px;" data-orderable="false">S.No.</th>
                                                        <th class="titleheading text-center" style="width: 30%;font-size:12px;" data-orderable="false">Document Name</th>
                                                        <th class="titleheading text-center" style="width: 10%;font-size:12px;" data-orderable="false">Status</th>
                                                        <th class="titleheading text-center" style="width: 25%;font-size:12px;" data-orderable="false">Created On / By</th>
                                                        <th class="titleheading text-center" style="width: 25%;font-size:12px;" data-orderable="false">Last Modified On / By</th>
                                                        <th class="titleheading text-center" style="width: 5%;font-size:12px;" data-orderable="false">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($authority->complianceLegalDocuments->isEmpty())
                                                        <tr>
                                                            <td colspan="6" class="text-center text-danger" style="font-size:12px;">No Legal Documents found.</td>
                                                        </tr>
                                                    @else
                                                    @foreach($authority->complianceLegalDocuments as $doc)
                                                        <tr class="text-center" style="font-size:12px;">
                                                            <td class="text-center" style="padding-top: 12px !important;">{{ $loop->iteration }}</td>
                                                            <td class="text-center" style="padding-top: 12px !important;">{{ $doc->name }}</td>
                                                            <td class="text-center" style="padding-top: 12px !important;">
                                                                @if($doc->status == 'Approved')
                                                                    <span class="status status-success">{{$doc->status}}</span>
                                                                @elseif($doc->status == 'Pending Review')
                                                                    <span class="status status-warning">{{$doc->status}}</span>
                                                                @else
                                                                    <span class="status status-red">{{$doc->status}}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center" style="padding-top: 12px !important;">
                                                                {{$doc->created_at}}
                                                                @if($doc->creator && $doc->creator->name)
                                                                    / {{$doc->creator->name}}
                                                                @endif
                                                            </td>
                                                            <td class="text-center" style="padding-top: 12px !important;">
                                                                @if($doc->modified_at)
                                                                    {{$doc->modified_at}}
                                                                    @if($doc->modifier && $doc->modifier->name)
                                                                        / {{$doc->modifier->name}}
                                                                    @endif
                                                                @else
                                                                    --
                                                                @endif
                                                            </td>
                                                            <td class="text-center" style="padding-top: 12px !important;">
                                                                <div class="btn-group m-auto d-flex justify-content-center">
                                                                    <a href="#" class="btn btn-sm btn-alt-primary edit-document" onclick="editDocModal({{$doc->id}})" data-toggle="tooltip" data-placement="top" title="Edit Legal Document">
                                                                        <i class="fa fa-pencil-alt" style="font-size:11px;cursor:pointer"></i>
                                                                    </a>
                                                                    <a data-id="{{$doc->id}}" class="btn btn-sm btn-alt-primary delete-document" data-toggle="tooltip" data-placement="top" title="Delete Legal Document">
                                                                        <i class="far fa-trash-alt" style="font-size:11px;cursor:pointer"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade mt-4 table-responsive" id="tab-2" role="tabpanel" aria-labelledby="pending-approvals-widget-tab">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="btn-group ml-auto">
                            <span id="regApproveSpan" data-toggle="tooltip" data-placement="top" title="Select the regulatory authority to enable the button.">
                                <a class="btn btn-sm btn-alt-primary disabled" id="approveRegulatories" style="font-size:12px;cursor:not-allowed;" onclick="approveRegulatoryMultiple('Approved')">
                                    Approve RA
                                </a>
                            </span>
                            <span id="regRejectSpan" data-toggle="tooltip" data-placement="top" title="Select the regulatory authority to enable the button.">
                                <a class="btn btn-sm btn-alt-danger disabled" id="rejectRegulatories" style="font-size:12px;cursor:not-allowed;" onclick="approveRegulatoryMultiple('Rejected')">
                                    Reject RA
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="pendingRegulatoryTable" class="table table-bordered table-striped js-table-checkable">
                            <thead>
                                <tr style="position: sticky; top: 0;z-index:5;">
                                    <th class="titleheading text-center" style="width: 1%" data-orderable="false"></th>
                                    <th class="titleheading text-center" style="width: 1%" data-orderable="false">
                                        <div class="custom-control custom-checkbox d-inline-block">
                                            <input type="checkbox" class="custom-control-input" id="selectAll">
                                            <label class="custom-control-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th class="titleheading text-center" style="width: 1%" data-orderable="false">S.No.</th>
                                    <th class="titleheading text-center" style="width:35%;" data-orderable="false">Name</th>
                                    <th class="titleheading text-center" style="width:20%;text-transform:none !important;" data-orderable="false">Submitted By & Date of Submission</th>
                                    <th class="titleheading text-center" style="width:25%;text-transform:none !important;" data-orderable="false">No. of Legal Documents</th>
                                    <th id="statusheader" class="titleheading text-center" style="width:5%;" data-orderable="false">Status</th>
                                    <th class="titleheading text-center" style="width:5%;" data-orderable="false">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingAuthorities as $authority)
                                    <tr class="text-center" style="font-size:12px;">
                                        <td class="text-center" style="font-size:11px;">
                                            <button class="btn btn-sm btn-alt-secondary toggle-row" style="font-size:10px;" data-id="{{ $authority->id }}">
                                                <i class="fa fa-chevron-down" style="font-size:10px;"></i>
                                            </button>
                                        </td>
                                        <td class="text-center" style="font-size:11px;">
                                            @if($authority->status == 'Approved')
                                                <input type="checkbox"
                                                    class="custom-control-input parent-checkbox approved-checkbox"
                                                    data-id="{{ $authority->id }}"
                                                    id="id{{$authority->id}}"
                                                    data-approved="1" disabled>

                                            @else
                                                <div class="custom-control custom-checkbox d-inline-block">
                                                    <input type="checkbox" class="custom-control-input parent-checkbox" 
                                                        data-id="{{ $authority->id }}" id="id{{$authority->id}}" name="id[]">
                                                    <label class="custom-control-label" for="id{{$authority->id}}"></label>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center" style="padding-top: 15px !important;">{{ $loop->index+1 }}</td>
                                        <td class="text-center" style="padding-top: 15px !important;">{{ $authority->name }}</td>
                                        <td class="text-center" style="padding-top: 15px !important;">
                                            {{$authority->created_at}}
                                            @if($authority->creator && $authority->creator->name)
                                                / {{$authority->creator->name}}
                                            @endif
                                        </td>
                                        <td class="text-center" style="padding-top: 15px !important;">{{count($authority->complianceLegalDocuments)}}</td>
                                        <td class="text-center" style="padding-top: 15px !important;">
                                            @if($authority->status == 'Approved')
                                                <span class="status status-success">{{$authority->status}}</span>
                                            @elseif($authority->status == 'Pending Review')
                                                <span class="status status-warning">{{$authority->status}}</span>
                                            @else
                                                <span class="status status-red">{{$authority->status}}</span>
                                            @endif
                                        </td>
                                        <td style="font-size:12px;" class="text-center">
                                            <div class="btn-group m-auto d-flex justify-content-center">
                                                @if($authority->status == 'Approved')
                                                    <a class="btn btn-sm btn-alt-primary disabled" data-toggle="tooltip" data-placement="top" title="Approved items status cannot be changed" aria-disabled="true" tabindex="-1">
                                                        <i class="fa fa-check" style="font-size:11px;cursor:not-allowed"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-alt-danger disabled" data-toggle="tooltip" data-placement="top" title="Approved items status cannot be changed" aria-disabled="true" tabindex="-1">
                                                        <i class="fa fa-times" style="font-size:11px;cursor:not-allowed"></i>
                                                    </a>
                                                @else
                                                    <a data-id="{{$authority->id}}" class="btn btn-sm btn-alt-primary approve-regulatory" data-toggle="tooltip" data-placement="top" title="Approve Regulatory Authority" onclick="approveRegulatory({{$authority->id}},'Approved')">
                                                        <i class="fa fa-check" style="font-size:11px;cursor:pointer"></i>
                                                    </a>
                                                    <a data-id="{{$authority->id}}" class="btn btn-sm btn-alt-danger reject-regulatory" data-toggle="tooltip" data-placement="top" title="Reject Regulatory Authority" onclick="approveRegulatory({{$authority->id}},'Rejected')">
                                                        <i class="fa fa-times" style="font-size:11px;cursor:pointer"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            
                                        </td>
                                    </tr>
                                    <!-- Nested Table -->
                                    <tr class="nested-row d-none" id="nested-{{ $authority->id }}">
                                        <td colspan="6" class="m-0 p-1">
                                            <div class="p-3 bg-light rounded border">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h6 class="fw-bold mb-0" style="font-size:13px;">Legal Documents</h6>
                                                    <div class="btn-group ml-auto">
                                                        @if($authority->status != 'Approved')
                                                            <span data-toggle="tooltip" data-placement="top" title="Approve the Regulatory Authority first and select the Legal Document.">
                                                                <a class="btn btn-sm btn-alt-primary disabled" aria-disabled="true" tabindex="-1" style="font-size:11px;">
                                                                    Approve Doc
                                                                </a>
                                                            </span>
                                                            <span data-toggle="tooltip" data-placement="top" title="Approve the Regulatory Authority first and select the Legal Document.">
                                                                <a class="btn btn-sm btn-alt-danger disabled" data-toggle="tooltip" style="font-size:11px;">
                                                                    Reject Doc
                                                                </a>
                                                            </span>
                                                        @else
                                                            <span id="docApproveSpan{{$authority->id}}" data-toggle="tooltip" data-placement="top" title="Select the legal document to enable the button.">
                                                                <a class="btn btn-sm btn-alt-primary disabled" id="approvelegalDocumentsBtn{{$authority->id}}" style="font-size:11px;cursor:not-allowed;" onclick="approveLegalDocumentMultiple({{$authority->id}},'Approved')">
                                                                    Approve Doc
                                                                </a>
                                                            </span>
                                                            <span id="docRejectSpan{{$authority->id}}" data-toggle="tooltip" data-placement="top" title="Select the legal document to enable the button.">
                                                                <a class="btn btn-sm btn-alt-danger disabled" id="rejectlegalDocumentsBtn{{$authority->id}}" style="font-size:11px;cursor:not-allowed;" onclick="approveLegalDocumentMultiple({{$authority->id}},'Rejected')">
                                                                    Reject Doc
                                                                </a>
                                                            </span>
                                                            
                                                        @endif
                                                        
                                                    </div>
                                                </div>
                                                <table class="table table-sm table-hover mb-0 table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="titleheading text-center" style="width: 1%" data-orderable="false">
                                                                @if($authority->status == 'Approved')
                                                                    <div class="custom-control custom-checkbox d-inline-block">
                                                                        <input type="checkbox" class="custom-control-input" id="selectAllDoc{{$authority->id}}" onchange="childSelectAll({{$authority->id}})">
                                                                        <label class="custom-control-label" for="selectAllDoc{{$authority->id}}"></label>
                                                                    </div>
                                                                @endif
                                                            </th>
                                                            <th class="titleheading text-center" style="width: 5%;font-size:12px;" data-orderable="false">S.No.</th>
                                                            <th class="titleheading text-center" style="width: 30%;font-size:12px;" data-orderable="false">Document Name</th>
                                                            <th class="titleheading text-center" style="width: 25%;font-size:12px;" data-orderable="false">Submitted By & Date of Submission</th>
                                                            <th class="titleheading text-center" style="width: 10%;font-size:12px;" data-orderable="false">Status</th>
                                                            <th class="titleheading text-center" style="width: 5%;font-size:12px;" data-orderable="false">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($authority->complianceLegalDocuments->isEmpty())
                                                            <tr>
                                                                <td colspan="6" class="text-center text-danger" style="font-size:12px;">No Legal Documents found.</td>
                                                            </tr>
                                                        @else
                                                        @foreach($authority->complianceLegalDocuments as $doc)
                                                            <tr class="text-center" style="font-size:12px;">
                                                                <td class="text-center" style="font-size:11px;">
                                                                    <div class="custom-control custom-checkbox d-inline-block">
                                                                        
                                                                        @if($authority->status != 'Approved')
                                                                            {{-- <input type="checkbox"
                                                                                class="custom-control-input child-checkbox approved-checkbox"
                                                                                data-id="{{ $authority->id }}"
                                                                                id="id{{$authority->id}}"
                                                                                data-approved="1" disabled> --}}
                                                                        @else
                                                                            <input type="checkbox" class="custom-control-input child-checkbox" 
                                                                                data-parent="{{ $authority->id }}" id="doc{{$doc->id}}" name="docid[]" data-id="{{ $doc->id }}">
                                                                            <label class="custom-control-label" for="doc{{$doc->id}}"></label>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td class="text-center" style="padding-top: 12px !important;">{{ $loop->iteration }}</td>
                                                                <td class="text-center" style="padding-top: 12px !important;">{{ $doc->name }}</td>
                                                                
                                                                <td class="text-center" style="padding-top: 12px !important;">
                                                                    {{$doc->created_at}}
                                                                    @if($doc->creator && $doc->creator->name)
                                                                        / {{$doc->creator->name}}
                                                                    @endif
                                                                </td>
                                                                <td class="text-center" style="padding-top: 12px !important;">
                                                                    @if($doc->status == 'Approved')
                                                                        <span class="status status-success">{{$doc->status}}</span>
                                                                    @elseif($doc->status == 'Pending Review')
                                                                        <span class="status status-warning">{{$doc->status}}</span>
                                                                    @else
                                                                        <span class="status status-red">{{$doc->status}}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center" style="padding-top: 12px !important;">
                                                                    <div class="btn-group m-auto d-flex justify-content-center">
                                                                        @if($authority->status != 'Approved')
                                                                            <span data-toggle="tooltip"
                                                                                data-placement="top" title="Approve the Regulatory Authority first and select the Legal Document.">

                                                                                <a class="btn btn-sm btn-alt-primary disabled" aria-disabled="true" tabindex="-1">
                                                                                    <i class="fa fa-check" style="font-size:11px;"></i>
                                                                                </a>
                                                                            </span>

                                                                            <span data-toggle="tooltip"
                                                                                data-placement="top"
                                                                                title="Approve the Regulatory Authority first and select the Legal Document.">

                                                                                <a class="btn btn-sm btn-alt-danger disabled"
                                                                                aria-disabled="true"
                                                                                tabindex="-1">
                                                                                    <i class="fa fa-times" style="font-size:11px;"></i>
                                                                                </a>

                                                                            </span>

                                                                        @else
                                                                            <a data-id="{{$doc->id}}" class="btn btn-sm btn-alt-primary approve-document" data-toggle="tooltip" data-placement="top" title="Approve Legal Document" onclick="approveDocument({{$doc->id}},'Approved')">
                                                                                <i class="fa fa-check" style="font-size:11px;cursor:pointer"></i>
                                                                            </a>
                                                                            <a data-id="{{$doc->id}}" class="btn btn-sm btn-alt-danger reject-document" data-toggle="tooltip" data-placement="top" title="Reject Legal Document" onclick="approveDocument({{$doc->id}},'Rejected')">
                                                                                <i class="fa fa-times" style="font-size:11px;cursor:pointer"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>        
                </div>
            </div>
        </div>
    </div>
</div>
<div class="view-regulatory-modal"></div>
<div class="edit-modal"></div>
<div id="AddRegulatoryModal" class="modal fadebd-example-modal-md" aria-labelledby="AddRegulatoryModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">Add Authority</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">Regulatory Authority Name added successfully!</div>
            <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
            <form id="form_id" method="post">
                @csrf
                <div class="modal-body m-body p-2">
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Authority Name <span style="color:red">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size: 13px;" id="authname" placeholder="Enter Authority Name" required name="authname">
                            </div>
                            <div id="duplicate-error" class="text-danger mt-2 d-none" style="font-size:13px;padding-left:15px;">
                                Authority Name already exists. Please choose a different name.
                            </div>
                        </div>
                        <div id="dropdown-options-container" class="col-sm-12 form-group">
                            <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Add Legal Documents</label>
                            <div id="dropdown-options-list" class="col-sm-12">
                                <div class="input-group mb-2 dropdown-option-row">
                                    <input type="text" name="dropdown_options[]" class="form-control dropdown_option" placeholder="Enter Legal Document Name" style="font-size:13px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-alt-primary btn-sm add-option" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
<div id="AddDocumentModal" class="modal fadebd-example-modal-md" aria-labelledby="AddDocumentModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="block-header bg-primary-dark">
                <h3 class="block-title text-white">Add Legal Document</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="alert alert-success" id="successBtnDoc" style="display:none;font-size:12px;">Legal Document Names added successfully!</div>
            <ul class="alert alert-danger" id="dangerBtnDoc" style="display:none;font-size:12px;"></ul>
            <form id="doc_form" method="post">
                @csrf
                <div class="modal-body m-body p-2">
                    <div class="row">
                        <input type="hidden" name="ragid" value="" id='ragid'>
                        <div id="legal-document-container" class="col-sm-12 form-group">
                            <label for="type" class="col-sm-12 col-form-label text-black" style="font-size:12px;">Add Legal Documents <span class="text-danger">*</span></label>
                            <div id="legal-document-list" class="col-sm-12">
                                <div class="input-group mb-2 legal-document-row">
                                    <input id="firstDoc" type="text" name="legal_documents[]" class="form-control legal_documents" placeholder="Enter Legal Document Name" style="font-size:13px;" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-alt-primary btn-sm add-legal-document-plus" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div id="duplicate-error-doc" class="text-danger mt-2 d-none" style="font-size:13px;padding-left:15px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
<script src="{{ asset('js/compliance/compliance.js') }}"></script>
<script src="{{ asset('js/compliance/pending_regulatory.js') }}"></script>
@endsection