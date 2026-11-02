
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
    #tabledata_wrapper {
        padding-bottom: 50px;
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
<div class="alert alert-primary" id="trashsuccess" style="display:none;font-size:12px;">
    Document moved to trash successfully
</div> 
<div class="alert alert-danger" id="trashdanger" style="display:none;font-size:12px;">
    Document deletion process failed
</div> 
<div class="content" style="width:100%;">
    <!-- heading part starts -->  
    <div class="block-header block-header-default">
        <div class="block-options" style="margin-left:-25px; font-size: 1rem;">
                    <span style="text-transform: uppercase;font-weight:bold" class="font-w700">
                        Governance Hub</span> /
                    <span style="text-transform: capitalize;font-weight:normal;font-size:15px;"> 
                    Document Management </span>
        </div>
        <div>
            <table width="100%" style="padding-top:5px;padding-right:15px;">
                <tr>
                    <td align="right">
                        <a href="{{ route('doc.document_draft') }}" class="btn btn-sm btn-alt-primary" title="Draft">
                            <i class="fa fa-2x fa-pencil-alt" style="font-size:13px;"></i>&nbsp;Draft</a> 

                        <a href="{{ route('doc.trashdocumentlist') }}" class="btn btn-sm btn-alt-primary" title="Trash">
                            <i class="far fa-2x fa-trash-alt" style="font-size:13px;"></i>&nbsp;Trash</a>

                        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="modal" data-target="#adddocumentModal">
                            <span data-toggle="tooltip" title="Click here to Add New Documents">
                            <i class="fa fa-plus mr-1"></i>&nbsp;Add</span> 
                        </button>
                    </td>                    
                </tr>
            </table>
        </div>
    </div>
        <!-- Ends heading part  -->
    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <div style="overflow-x:hidden;overflow-y:auto;" id="content">
                    <!-- approved documents listing starts -->
                <table class="table table-borderless table-striped table-vcenter js-dataTable-full" id="tabledata">
                    <thead class="border-top">
                        <tr>
                            <th class="text-center" style="font-size:11px;width:1%;text-transform:capitalize;position:sticky;">#</th>
                            <th class="text-center" style="width:15%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;">
                                    Document #</th>
                            <th class="text-center" style="width:20%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;">
                                    Document Name</th>
                            <th id="typeselectdata" class="text-center" data-orderable="false" style="width:10%;text-transform:capitalize;position:sticky;"> </th>
                            <th class="text-center" style="width: 15%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;">
                                Revision #</th>
                            <th class="text-center" style="width: 15%;text-transform: capitalize;font-size: 13px;position:sticky;top:2px;font-weight:600;">
                                Dated</th>
                            <th id="deptselectdata" class="text-center" data-orderable="false" style="width:15%;text-transform:capitalize;position:sticky;"> </th>
                            <th class="text-center" style="width: 10%;text-transform: capitalize;font-size: 13px;z-index: 1;position:sticky;top:2px;font-weight:600;">
                                <span data-toggle="tooltip" title="Reviews"> 
                                    <i class="far fa-star" style="font-size:15px;cursor:pointer;"></i>
                                </span>
                            </th>
                            <th class="text-center" style="width: 10%;text-transform: capitalize;font-size: 13px;z-index: 1;position:sticky;top:2px;font-weight:600;">
                            <span data-toggle="tooltip" title="Views">
                                <i class="far fa-eye" style="font-size:13px;cursor:pointer"></i>
                            </span>
                            </th>
                            <th class="text-center" style="width: 10%;font-size:13px;z-index: 1;text-transform:capitalize;position:sticky;top:2px;font-weight:600;">
                            Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach($datas as $data)
                        <tr>
                            <td class="text-center" style="font-size:12px;">{{$no}}</td>
                            <td style="font-size: 13px;" class="text-center">
                                <span>{{ $data->document_number }}</span>
                            </td>
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <a href="{{ url('/user/doc/procedures/?id='. $data->id) }}">{{ $data->document_name }}</a>
                            </td>
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <span class="text-center">
                                    {{ $data->doctype->typename }}
                                </span>
                            </td>
                            
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <span class="font-size-sm">{{ $data->revision }}</span>
                            </td>
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <span class="text-center">{{date('d-m-Y',strtotime($data->date))}}</span>
                            </td>
                            @php
                            $owner = \App\Departments::where('id',$data->owner)->first();
                            @endphp
                            @if(isset($owner))
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <span class="text-center">{{ ucfirst($owner->departmentname) }}</span>
                            </td>
                            @else
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <span class="text-center">-</span>
                            </td>
                            @endif
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                @php
                                    $average = App\Http\Controllers\DocumentController::getreviewsaverage($data->id);
                                    $percent = App\Http\Controllers\DocumentController::getreviewspercent($average);                                                                       
                                @endphp  
                                <span class="text-center;" data-toggle="tooltip" title="{{$average}}">
                                    @if($average==0)
                                    <i data-star></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=1.0 && $average < 1.5)
                                    <i data-star="1.9"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=1.5 && $average < 2.0)
                                    <i data-star="1.9"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=2.0 && $average < 2.5)
                                    <i data-star="1.9"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=2.5 && $average < 3.0)
                                    <i data-star="2"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=3.0 && $average < 3.5)
                                    <i data-star="2.5"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=3.5 && $average < 4.0)
                                    <i data-star="2.9"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=4.0 && $average < 4.5)
                                    <i data-star="3"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=4.5 && $average < 5.0)
                                    <i data-star="3.8"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @elseif($average>=5.0)
                                    <i data-star="5"></i><span style="font-size: 11px;"> ({{$average}})</span>
                                    @endif
                                </span>                                                                     
                            </td>
                            <td class=" d-md-table-cell text-center" style="font-size: 13px;">
                                <span class="text-center">{{ $data->views }}</span>
                            </td>
                            <div class="dropdown font-size-sm">
                                <td class="d-md-table-cell ">
                                    <div class="btn-group m-auto d-flex justify-content-center">
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions">
                                            <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                        <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                            @php
                                            $email = Auth::user()->email;
                                            $docAccess = \App\Doccontroller::where('email', $email)->first();
                                            $doc_privileged = $docAccess?true:false;
                                            @endphp
                                            @if($data->change_request_submit==1)
                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-plus text-gray"></i>&nbsp;&nbsp;Add Content</a>
                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                        <i class="fa fa-cloud-upload-alt text-gray"></i>&nbsp;&nbsp;Upload Content</a>
                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;">
                                                    <i class="fa fa-1x fa-user-edit text-gray"></i>&nbsp;&nbsp;Request Change</a>
                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;">
                                                    <i class="fa fa-1x fa-book-reader text-gray"></i>&nbsp;&nbsp;Read Request</a>
                                                @if($doc_privileged || $data->change_request_submitted_by==Auth::user()->email)
                                                <a href="{{ route('doc.cancelchangerequesthub', ['id' => $data->id]) }}" style="font-size:10px;" class="dropdown-item" onclick="return confirmcancelrequest()">
                                                    <i class="fa fa-fw fa-times text-primary"></i>&nbsp;&nbsp;<span style="color:black;">Cancel Change Request</span></a>
                                                @endif
                                                <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fa fa-1x fa-edit text-gray"></i>&nbsp;&nbsp;Edit</a>
                                                <a href="#" style="font-size:10px;color:grey;" class="dropdown-item disabled-link"><i class="fas fa-1x fa-trash text-gray"></i>&nbsp;&nbsp;Delete</a>
                                            @else
                                                @if($doc_privileged)
                                                <a href="{{ route('doc.add_content', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;"><i class="fa fa-1x fa-plus" style="color:grey;"></i>&nbsp;&nbsp;Add Content</a>
                                                <a href="{{ route('doc.documentuploadcontent', ['id' => $data->id]) }}" class="dropdown-item" style="font-size:10px;">
                                                        <i class="fa fa-cloud-upload-alt" style="color:grey;"></i>&nbsp;&nbsp;Upload Content</a>
                                                @else
                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;color:grey;"><i class="fa fa-1x fa-plus text-gray"></i>&nbsp;&nbsp;Add Content</a>
                                                <a href="#" class="dropdown-item disabled-link" style="font-size:10px;">
                                                        <i class="fa fa-cloud-upload-alt text-gray"></i>&nbsp;&nbsp;Upload Content</a>
                                                @endif
                                                <a href="#" class="dropdown-item" style="font-size:10px;" data-toggle="modal"  data-target="#changerequestModal" onclick="getidvalue('{{$data->id}}')">
                                                    <i class="fa fa-1x fa-user-edit" style="color: grey;"></i>&nbsp;&nbsp;Request Change</a>
                                                <a href="#" class="dropdown-item" style="font-size:10px;" data-toggle="modal"  data-target="#readrequestModal" onclick="getdocumentidvalue('{{$data->id}}')">
                                                    <i class="fa fa-1x fa-book-reader" style="color: grey;"></i>&nbsp;&nbsp;Read Request</a>
                                                @if(Gate::allows('edit-doc') || $doc_privileged)
                                                    <a href="{{route('doc.edit_document',['id' => $data->id])}}" style="font-size:10px" class="dropdown-item"><i class="fa fa-1x fa-edit" style="color:grey;"></i>&nbsp;&nbsp;Edit</a>
                                                @else
                                                    <a href="" style="font-size:10px;cursor: none;pointer-events: none;color:#999;" class="dropdown-item"><i class="fa fa-1x fa-edit" style="color:#999;"></i>&nbsp;&nbsp;Edit</a>
                                                @endif
                                                @if(Gate::allows('delete-doc') || $doc_privileged)
                                                    <a href="#" style="font-size:10px" class="dropdown-item" onclick="trashdoc('{{$data->id}}')"><i class="fas fa-1x fa-trash" style="color:red;"></i>&nbsp;&nbsp;Delete</a>
                                                @else
                                                    <a href="" style="font-size:10px;cursor: none;pointer-events: none;color:#999;" class="dropdown-item"><i class="fas fa-1x fa-trash" style="color:#999;"></i>&nbsp;&nbsp;Delete</a>
                                                @endif
                                            @endif
                                        </div>
                                        <a href="#" class="btn btn-sm btn-alt-primary" data-id="{{ $data->id }}" data-toggle="tooltip" data-placement="top" title="View" onclick="ViewBtn('{{$data->id}}')">
                                            <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer"></i>
                                        </a>
                                    </div>
                                    <div id="viewModal{{$data->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="modal-title">Detailed View</h2>
                                                </div>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <th style="width:70px">Document Number</th>
                                                        <td>{{$data->document_number}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Document Name</th>
                                                        <td>{{ $data->document_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Type</th>
                                                        <td>
                                                            {{ $data->doctype->typename }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Views</th>
                                                        <td>{{$data->views}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Revision #</th>
                                                        <td>{{ $data->revision }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Date</th>
                                                        <td>{{ date('d-m-Y',strtotime($data->date)) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Owner</th>
                                                        @if(isset($owner))
                                                        <td>{{ $owner->departmentname }}</td>
                                                        @else
                                                        <td>-</td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <th>Prepared By</th>
                                                        <td>{{$data->preparedbydetails ? $data->preparedbydetails->name : $data->prepared_by}}</td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Reviewed By</th>
                                                        <td>
                                                        @if($data->reviewed_by!=NULL)
                                                            @php                                                          
                                                                $reviewer_emails=explode(',',$data->reviewed_by);                       
                                                                $revexists = App\GovernanceHubDraftReviewerDetail::where('new_governance_hub_id',$data->id)
                                                                                                                    ->get();
                                                            @endphp
                                                            @if(count($revexists)>0)
                                                                @foreach($revexists as $rexists)
                                                                    {{$rexists->reviewerdetails ? ucfirst($rexists->reviewerdetails->name) : ''}},<span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($rexists->reviewed_date))}})</span> ,
                                                                @endforeach
                                                            @else
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
                                                        <th>Approved By</th>
                                                        <td>{{$data->approvedbydetails ? $data->approvedbydetails->name : $data->approved_by}}</td>
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

<div class="modal fade" id="adddocumentModal"  aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="dangerBtn" style="font-size: 12px;">
                    You are not authorized to add an existing Procedure
                </div>
                <form>
                    @php
                    $email = Auth::user()->email;
                    @endphp
                    <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">
                        Document added successfully
                    </div>
                    <div class="alert alert-success" id="successBtn1" style="display:none;font-size:12px;">
                        Draft added successfully
                    </div>
                    <ul class="alert alert-danger" id="dangerBtn1" style="display:none;font-size:12px;"></ul>
                    <input type="hidden" name="drafted_by" value="{{$email}}" id="DraftedBy">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="radio" value="existing_procedure" id="existing_procedure" name="radio_procedure"><label for="existing_procedure">&nbsp;&nbsp;Existing Procedure</label>&nbsp;&nbsp;
                            <input type="radio" value="new_procedure" id="new_procedure" name="radio_procedure" checked><label for="new_procedure">&nbsp;&nbsp;New Procedure</label>
                        </div>
                    </div>
                    <div class="form-group" id="doc_name_div">
                        <label for="DocumentName">Document Name<span style="color:red;">*</span></label>
                        <textarea type="name" class="form-control" id="DocumentName" name="document_name" rows="2" required></textarea>
                    </div>
                    <div class="form-group" id="doc_type_div">
                        <label for="DocumentType">Document Type<span style="color:red;">*</span></label>
                        <select class="form-control" id="DocumentType" required name="type" style="font-size: 13px;">
                            <option value="">Choose Document Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->typename }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="docnumber_div">
                        <label class="col-form-label" for="docname">Document Number<span style="color:red;">*</span></label>
                        <input type="text" name="docnumber" id="docnumber" class="form-control" required>
                    </div>
                    <div class="form-group d-none" id="revision_div">
                        <label for="Revision">Revision Status</label>
                        <input type="number" class="form-control" id="Revision" name="revision" min='0'>
                    </div>
                    <div class="form-group d-none" id="date_div">
                        <label for="Date">Date<span style="color:red;">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group" id="doc_owner_div">
                        <label for="DocumentOwner">Owner<span style="color:red;">*</span></label>
                        <select class="js-select2 form-control" id="DocumentOwner" required 
                        name="owner" style="font-size: 13px;width:100%">
                            <option value="">Choose Owner</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->departmentname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="prepared_by_div">
                        <label for="prepared_by" class="col-sm-12" style="padding-left:2px;">Prepared by<span style="color:red;">*</span></label>
                        <select class="form-select js-select2 col-sm-12" id="PreparedBy" name="prepared_by[]" required multiple 
                        style="font-size:12px;width:450px;">
                            @foreach($users as $user)
                            <option value="{{ $user->email }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                                                                    
                    <div class="form-group d-none" id="reviewed_by_div">
                        <label for="reviewed_by" class="col-sm-12" style="padding-left:2px;">Reviewed by<span style="color:red;">*</span></label>
                        <select class="js-select2 col-sm-12" id="ReviewedBy" name="reviewed_by[]" required multiple 
                        style="font-size:12px;width:450px;">
                            @foreach($users as $user)
                            <option value="{{ $user->email }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-none" id="approved_by_div">
                        <label for="approved_by">Approved by<span style="color:red;">*</span></label>
                        <select class="form-control" id="ApprovedBy" name="approved_by" required style="font-size: 13px;width:100%">
                            <option value="">Choose Approver</option>
                            @foreach($users as $user)
                            <option value="{{ $user->email }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn_div">
                <button type="button" class="btn btn-secondary btn-reset">Reset</button>
                <button type="button" id="saveBtn1" class="btn btn-primary d-none" onclick="saveForm1();">Submit</button>
                <button type="button" id="saveBtn2" class="btn btn-primary" onclick="saveForm2();">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- change request starts-->
<div class="modal fade" id="changerequestModal" tabindex="-1" role="dialog" aria-labelledby="changerequestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changerequestModalLabel">Change Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add_change_request_ajax" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="changerequestaddedsuccess" style="display:none">
                            Change request submitted successfully
                        </div>

                        <ul class="alert alert-danger" id="changerequestaddeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul> 

                        <ul class="alert alert-danger" id="changerequestdangernotadded" style="display:none"></ul>   

                        <div class="form-group">
                            <label for="changereason" class="col-sm-12 col-form-label" style="font-size:12px;"> Reason for the Change <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-12">
                                <textarea class="form-control" style="font-size:12px;" name="change_reason"
                                    id="change_reason" placeholder="Enter Reason for the change" required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="proposedchanges" class="col-sm-12 col-form-label" style="font-size:12px;">Proposed Changes
                            </label>
                            <div class="col-sm-12">
                                <textarea class="form-control" style="font-size:12px;" name="proposed_changes"
                                    id="proposed_changes" placeholder="Enter Proposed changes"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="effect" class="col-sm-12 col-form-label" style="font-size:12px;">Effect on other documents
                            </label>
                            <div class="col-sm-12">
                                <textarea class="form-control" style="font-size:12px;" name="effect_documents"
                                    id="effect_documents" placeholder="Enter Effect on other documents"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="priority" class="col-sm-12 col-form-label" style="font-size:12px;">Priority Level 
                            </label>
                            <div class="col-sm-12">
                                <select class="form-control" id="priority" name="priority" 
                                    style="font-size:12px;width:350px;">
                                    <option value="">Choose one</option>
                                    @if(count($priorities)>0)
                                    @foreach($priorities as $priority)
                                    <option value="{{$priority}}">{{$priority}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="main_data_id" id="main_data_id"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary btn-submit-change-request" style="font-size:12px">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- change request  ends-->
    <!-- read request starts-->
<div class="modal fade" id="readrequestModal" tabindex="-1" role="dialog" aria-labelledby="readrequestModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="readrequestModalLabel">Read and Rate Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add_read_request_ajax" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success" id="readrequestaddedsuccess" style="display:none">
                        Read request submitted successfully
                    </div>

                    <ul class="alert alert-danger" id="readrequestaddeddanger" style="font-size:12px;display:none">
                            Internal server error, Unable to process request
                    </ul> 

                    <ul class="alert alert-danger" id="readrequestdangernotadded" style="display:none"></ul> 
                    
                    <div class="form-group">
                        <label for="read_users" class="col-sm-12 col-form-label" style="font-size:12px;">Select Users <span style="color:red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <select class="js-select2" id="read_users" name="read_users[]" 
                                style="font-size:12px;width:350px;" required multiple>
                                <option value="">Please select users</option>
                                @if(count($users)>0)
                                @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>          

                    <div class="form-group">
                        <label for="notification_schedule" class="col-sm-12 col-form-label" style="font-size:12px;">Notification Schedule 
                            <span style="color:red">*</span>
                        </label>
                        <div class="col-sm-12">
                            <select class="form-control" id="notification_schedule" name="notification_schedule" 
                                style="font-size:12px;width:350px;" required>
                                <option value="">Choose one</option>
                                @if(count($notifications)>0)
                                @foreach($notifications as $notification)
                                <option value="{{$notification}}">{{$notification}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>  
                </div>
                <input type="hidden" name="main_document_data_id" id="main_document_data_id"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        style="font-size:12px">Close</button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- read request  ends-->
<script src="{{ asset('js/jquery.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $('#reviewers').select2();
    $('#read_users').select2();
</script>
<script>
    // Function to update sequence numbers in the first column
    function updateSequenceNumbers(table) {
        var info = table.page.info();
        var startIndex = info.start;
        
        table.rows({page: 'current'}).every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            var node = this.node();
            
            // Calculate continuous sequence number across pages
            var sequenceNumber = startIndex + rowLoop + 1;
            
            // Update the first cell (sequence number) with the calculated sequence number
            $(node).find('td:first').text(sequenceNumber);
        });
    }

    $(document).ready(function() {
        $("#tabledata").one("preInit.dt", function() {
            let table = $('#tabledata').DataTable();
            let alltypenames = "{{ $alltypenames }}".split(',');
            let type_text = "";
            for (var i = 0; i < alltypenames.length; i++) {
                type_text += `<option value="${alltypenames[i]}">${alltypenames[i]}</option>`;
            }
                $typeheading = $(`<select class="headingselect" style="width:100px;border:none;font-size:13px;line-height:25px;" id="type_dropdown">
                <option value="">Type&nbsp;&nbsp;&#9207;</option>` +
                    type_text +
                    `</select>`);
            $('#typeselectdata').html($typeheading);
            $("#type_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(3).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#type_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });
                });


                let alldeptnames = "{{ $alldeptnames }}".split(',');
                let dept_text = "";
                for (var i = 0; i < alldeptnames.length; i++) {
                    dept_text += `<option value="${alldeptnames[i]}">${alldeptnames[i]}</option>`;
                }
                $("#tabledata").one("preInit.dt", function() {
                    let table = $('#tabledata').DataTable();
                    $deptheading = $(`<select class="headingselect" style="width:120px;margin-left:10px;font-size:13px;line-height:25px;" id="dept_dropdown">
                    <option value="">Owner&nbsp;&nbsp;&#9207;</option>` +dept_text +
                        `</select>`);

            $('#deptselectdata').html($deptheading);
            $("#dept_dropdown").on('change', function() {
                        if ($(this).val()) {
                            table.columns(6).search($(this).val()).draw();
                            $(this).addClass('text-primary');
                            $("#dept_dropdown option").css('color', 'black')
                        } else {
                            location.reload();
                        }
                    });
                });

        // Add single event listener to update sequence numbers after any search/filter
        $("#tabledata").one("init.dt", function() {
            let table = $('#tabledata').DataTable();
            table.on('draw', function() {
                updateSequenceNumbers(table);
            });
        });

        });
    
</script>

<script>
    function ViewBtn(id) {
        $('#viewModal' + id).modal('show');
    }
</script>
<script>
    function saveForm1() {
        let dangerBtn_selector = $('#dangerBtn1');
        let successBtn_selector = $('#successBtn');

        function showError() {
            successBtn_selector.addClass('d-none');
            successBtn_selector.removeClass('d-block');

            dangerBtn_selector.addClass('d-block');
            dangerBtn_selector.removeClass('d-none');
        }

        function showSuccess() {
            successBtn_selector.addClass('d-block');
            successBtn_selector.removeClass('d-none');

            dangerBtn_selector.addClass('d-none');
            dangerBtn_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('document_name', $('#DocumentName').val());
        formData.append('document_number', $('#docnumber').val());
        formData.append('type', $('#DocumentType').val());
        formData.append('revision', $('#Revision').val());
        formData.append('date', $('#date').val());
        formData.append('owner', $('#DocumentOwner').val());
        formData.append('prepared_by', $('#PreparedBy').val());
        formData.append('reviewed_by', $('#ReviewedBy').val());
        formData.append('approved_by', $('#ApprovedBy').val());
        $.ajax({
            url: '/user/doc/save-document',
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            data: formData,
            processData: false,
            contentType: false,
            error: function(err) {
                showError();
                console.error('Error:', err);
                if (err.status === 500) {
                    dangerBtn_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                } else if (err.status === 422) {
                    for (let i in error.responseJSON.errors) {
                        dangerBtn_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li><br>`)
                    }
                }
            },
            success: function(response) {
                if (response.error == null) {
                    showSuccess();
                    setTimeout(
                        function() {
                            window.location.href = '/user/doc/governancehub';
                        }, 2000);
                } else {
                    showError();
                    dangerBtn_selector.empty();
                    for (let i in response.error) {
                        dangerBtn_selector.append(`<li>${response.error[i]}</li>`);
                    }
                }
            }
        });
    }
</script>
<script>
    function saveForm2() {
        let dangerBtn_selector = $('#dangerBtn1');
        let successBtn_selector = $('#successBtn1');

        function showError() {
            successBtn_selector.addClass('d-none');
            successBtn_selector.removeClass('d-block');

            dangerBtn_selector.addClass('d-block');
            dangerBtn_selector.removeClass('d-none');
        }

        function showSuccess() {
            successBtn_selector.addClass('d-block');
            successBtn_selector.removeClass('d-none');

            dangerBtn_selector.addClass('d-none');
            dangerBtn_selector.removeClass('d-block');
        }
        var formData = new FormData();
        formData.append('drafted_by', $('#DraftedBy').val());
        formData.append('document_name', $('#DocumentName').val());
        formData.append('type', $('#DocumentType').val());
        formData.append('owner', $('#DocumentOwner').val());
        $.ajax({
            url: '/user/doc/save-document-draft', // Update with the correct route URL
            type: 'POST',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                'accept': 'application/json'
            },
            data: formData,
            processData: false,
            contentType: false,
            error: function(err) {
                showError();
                console.error('Error:', err);
                if (err.status === 500) {
                    dangerBtn_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
                } else if (err.status === 422) {
                    for (let i in error.responseJSON.errors) {
                        dangerBtn_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                    }
                }
            },
            success: function(response) {
                if (response.error == null) {
                    showSuccess();
                    setTimeout(
                        function() {
                            window.location.href = '/user/doc/document-draft';
                        }, 2000);
                } else {
                    showError();
                    dangerBtn_selector.empty();
                    for (let i in response.error) {
                        dangerBtn_selector.append(`<li>${response.error[i]}</li>`);
                    }
                }
            }
        });
    }
</script>
@php
$email = Auth::user()->email;
$docAccess = \App\Doccontroller::where('email', $email)->first();
$is_privileged = $docAccess?true:false;
@endphp
<script>
    var existingProcedureRadio = document.getElementById("existing_procedure");
    var newProcedureRadio = document.getElementById("new_procedure");
    var selector = document.getElementById("dangerBtn");
    var docname_div_selector = document.getElementById("doc_name_div");
    var doctype_div_selector = document.getElementById("doc_type_div");
    var revision_div_selector = document.getElementById("revision_div");
    var date_div_selector = document.getElementById("date_div");
    var docowner_div_selector = document.getElementById("doc_owner_div");
    var btn_div_selector = document.getElementById("btn_div");
    var docnumber_selector = document.getElementById("docnumber_div");
    var prepared_by_selector = document.getElementById("prepared_by_div");
    var reviewed_by_selector = document.getElementById("reviewed_by_div");
    var approved_by_selector = document.getElementById("approved_by_div");
    var saveBtn1_selector = document.getElementById("saveBtn1");
    var saveBtn2_selector = document.getElementById("saveBtn2");
    existingProcedureRadio.addEventListener("change", function() {
        if (existingProcedureRadio.checked && "{{!$is_privileged}}") {
            selector.classList.remove('d-none');
            docnumber_selector.classList.add('d-none');
            prepared_by_selector.classList.add('d-none');
            reviewed_by_selector.classList.add('d-none');
            approved_by_selector.classList.add('d-none');
            docname_div_selector.classList.add('d-none');
            doctype_div_selector.classList.add('d-none');
            docowner_div_selector.classList.add('d-none');
            revision_div_selector.classList.add('d-none');
            date_div_selector.classList.add('d-none');
            btn_div_selector.classList.add('d-none');
            saveBtn1_selector.classList.remove('d-none');
            saveBtn2_selector.classList.add('d-none');
        } else {
            prepared_by_selector.classList.remove('d-none');
            docnumber_selector.classList.remove('d-none');
            reviewed_by_selector.classList.remove('d-none');
            approved_by_selector.classList.remove('d-none');
            revision_div_selector.classList.remove('d-none');
            date_div_selector.classList.remove('d-none');
            saveBtn1_selector.classList.remove('d-none');
            saveBtn2_selector.classList.add('d-none');
        }
    });
    newProcedureRadio.addEventListener("change", function() {
        if (newProcedureRadio.checked) {
            selector.classList.add('d-none');
            docnumber_selector.classList.add('d-none');
            prepared_by_selector.classList.add('d-none');
            reviewed_by_selector.classList.add('d-none');
            approved_by_selector.classList.add('d-none');
            docname_div_selector.classList.remove('d-none');
            doctype_div_selector.classList.remove('d-none');
            docowner_div_selector.classList.remove('d-none');
            revision_div_selector.classList.add('d-none');
            date_div_selector.classList.add('d-none');
            btn_div_selector.classList.remove('d-none');
            saveBtn1_selector.classList.add('d-none');
            saveBtn2_selector.classList.remove('d-none');
        } else {
            revision_div_selector.classList.add('d-none');
            date_div_selector.classList.add('d-none');
            saveBtn1_selector.classList.add('d-none');
        }
    });
    $('.btn-reset').on('click', function() {
        const radio_procedure_value = $('input[name="radio_procedure"]:checked').val();
        $('#adddocumentModal').find('form')[0].reset();
        if(radio_procedure_value == 'existing_procedure') {
            existingProcedureRadio.checked = true;
        } else {
            newProcedureRadio.checked = true;
        }
        $('#DocumentType').val('').trigger('change');
        $('#DocumentOwner').val('').trigger('change');
        $('#PreparedBy').val('').trigger('change');
        $('#ReviewedBy').val('').trigger('change');
        $('#ApprovedBy').val('').trigger('change');
        $('#dangerBtn1').addClass('d-none');
        $('#dangerBtn1').removeClass('d-block');
        $('#successBtn').addClass('d-none');
        $('#successBtn').removeClass('d-block');
        $('#successBtn1').addClass('d-none');
        $('#successBtn1').removeClass('d-block');
        $('#docnumber').removeClass('is-invalid');
        $('#docnumber').next('.invalid-feedback').remove();
    });
</script>
<script>
    function trashdoc(id) {
        if (confirm('Are you sure you want to delete this document?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('doc.trashdocument') }}",
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
                    alert('Document has not been moved to the trash.')
                    console.error('Error deleting record: ' + error.statusText);
                }
            });
        }
    }
</script>
<script>
    $(document).ready(function() {
        const $docNumber = $('#docnumber');
        const checkDocumentNumber = () => {
            $.ajax({
                url: "{{ route('doc.checkDocumentNumber') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                data: {
                    document_number: $docNumber.val()
                },
                success: function(response) {
                    if (response.exists) {
                        $docNumber.addClass('is-invalid');
                        $docNumber.next('.invalid-feedback').remove();
                        $docNumber.after('<div class="invalid-feedback">The document number has already been taken.</div>');
                    } else {
                        $docNumber.removeClass('is-invalid');
                        $docNumber.next('.invalid-feedback').remove();
                    }
                },
                error: function(err) {
                    console.error('Error checking document number:', err);
                }
            });
        }
        const retrieveDocumentNumber = (type) => {
            if (!type) {
                if($docNumber.length) {
                    $docNumber.val("");
                }
                return;
            }

            $.ajax({
                url: "{{ route('doc.retrieveDocumentNumber') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                data: {
                    type
                },
                success: function(response) {
                    $docNumber.val(response.document_number);
                    checkDocumentNumber();
                },
                error: function(err) {
                    console.error('Error fetching document number:', err);
                    $docNumber.val("");
                }
            });
        }

        retrieveDocumentNumber($("#DocumentType").val());

        // Initialize Select2 when the modal is shown
        $('#adddocumentModal').on('shown.bs.modal', function () {
            $('#DocumentOwner').select2({
                dropdownParent: $('#adddocumentModal').find('.modal-body')
            });
            $('#PreparedBy').select2({
                dropdownParent: $('#adddocumentModal').find('.modal-body')
            });
            $('#ReviewedBy').select2({
                dropdownParent: $('#adddocumentModal').find('.modal-body')
            });
            $('#ApprovedBy').select2({
                dropdownParent: $('#adddocumentModal').find('.modal-body')
            });
            const $documentType = $('#DocumentType');
            $documentType.select2({
                dropdownParent: $('#adddocumentModal').find('.modal-body'),
                width: '100%'
            });

            // Attach Select2 event listener
            $documentType.on('select2:select', function (e) {
                let selectedOption = e.params.data.id; // Get the selected value
                retrieveDocumentNumber(selectedOption);
            });

            // Check document number uniqueness on input
            // Debounce the input event to avoid too many requests
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
                        checkDocumentNumber();
                    }, 500); // 500ms debounce delay
                }
            });
        });
    });
</script>

<script>
   function getidvalue(id){
       $('#main_data_id').val(id);
   }

   function getdocumentidvalue(id){
       $('#main_document_data_id').val(id);
     }

   window.onload = function() {
      /**-------------change request starts---------------- */
      $(document).ready(function() {
            $('#add_change_request_ajax').submit(function(event) {
                $('.btn-submit-change-request').attr('disabled', true);
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: 'POST',
                        url: '/user/doc/governancehubchangerequests',
                        data: formData
                        ,success: function(response) {
                            if(response.status == 200)
                            {
                                $('#changerequestaddeddanger').hide();
                                $('#changerequestdangernotadded').hide();
                                $('#changerequestaddedsuccess').css('display', 'block');
                                window.location.reload();
                            }
                        }
                       ,error: function(err) {
                            $('.btn-submit-change-request').attr('disabled', false);
                           if(err.status === 500) {
                                $('#changerequestdangernotadded').hide();
                                $('#changerequestaddeddanger').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                    $.each(err.responseJSON.errors[i], function(key, val) {
                                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                                    });
                               }); 
                                $('#changerequestdangernotadded').html(error_text);
                                $('#changerequestaddeddanger').hide();
                                $('#changerequestdangernotadded').css('display', 'block');        
                            }
                       }                   
                });              
            });
        });
        /**-------------End change request ---------------- */ 
        /**-------------Read request starts---------------- */
        $(document).ready(function() {
            $('#add_read_request_ajax').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        type: 'POST',
                        url: '/user/doc/governancehubreaderequest',
                        data: formData
                        ,success: function(response) {
                            console.log(response);
                            if(response.status == 200)
                            {
                                $('#readrequestaddeddanger').hide();
                                $('#readrequestdangernotadded').hide();
                                $('#readrequestaddedsuccess').css('display', 'block');
                                window.location.reload();
                            }
                        }
                       ,error: function(err) { 
                           if(err.status === 500) {
                                $('#readrequestdangernotadded').hide();
                                $('#readrequestaddeddanger').css('display', 'block');
                            }
                            else if (err.status === 422) {
                                var error_text='';                               
                                $.each(err.responseJSON.errors, function(i) {
                                    $.each(err.responseJSON.errors[i], function(key, val) {
                                        error_text += '<li style="font-size:12px;">' + val + '</li>';
                                    });
                               }); 
                                $('#readrequestdangernotadded').html(error_text);
                                $('#readrequestaddeddanger').hide();
                                $('#readrequestdangernotadded').css('display', 'block');        
                            }
                       }                   
                });              
            });
        });
            
        /**-------------End Read request ---------------- */ 
   }
</script>
<script>
    function confirmcancelrequest() {
        message1 = "Are you sure you want to cancel the change request?";
        x = confirm(message1);
        if (x == true) {
            message2 = 'Change Request will be cancelled,Press OK to continue.';
            y = confirm(message2);
            if (y == true) {
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 230);

</script>
@include('user.document.governance_hub_headers')
@include('exporting.forgovernancehub')

@section('notification')
@include('user.document.governance_hub_notification')
@endsection
@endsection
