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
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue1" name="tvalue1" />
            </form>
        </li>
        <li><button title="Excel" onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button title="Word" onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word </button></li>
    </ul>
</div>

<div class="input-group input-group-sm">

    <a type="button" href="{{route('hseobservation.addissue')}}" class="btn btn-sm btn-dual" style="margin-left:8px;" style="">
        <i class="fa fa-1x fa-plus" style="color:grey;"></i>
    </a>

</div>
@endsection
@section('content')
<link rel="stylesheet" href="{{ asset('css/register_filter.css') }}">
<style>
    body {
        overflow-y: auto;
    }
    .headingselect {
        white-space: break-spaces;
        line-height: 1rem;
    }
    .border-3 {
        border-width: 3px !important;
    }

    .font-15{
        font-size:12px !important;
    }
    .breadcrumb>li+li:before {
        display: none !important;
    }
    .select2-results__options{
        font-size:12px !important;
    }
    .breadcrumb {
        font-size: 15px;
        padding: 0.75rem 1.25rem;
        background: #F6F7F9;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    } 
    .titleheading {
        font-size: 10px !important;
        text-transform: capitalize !important;
        position: sticky !important;
        position: relative !important;
        font-weight:bold !important;
        min-width: 120px !important;
    }
    .scrollable {
        height: 100%;
        overflow-x: scroll;
        overflow-y: scroll;
    }
    @media (max-width: 1800px) {
        #content {
            height: calc(100vh - 220px) !important;
            max-height: 900px !important;
        }
    }
    @media (max-width: 1200px) {
        #content {
            max-height: 600px !important;
        }
    }
    #content{
        height:calc(100vh - 220px); max-height:900px;
    }
</style>
@php
$issues2=$issues1=$issues3=[];
@endphp

<input type="hidden" value="a1" id="tabselected">

<div class="content" style="width:100%">
    <!-- heading part starts -->
    <div class="block block-rounded">
        <nav aria-label="breadcrumb" style="display: flex; justify-content: space-between; background: #f6f7f9;">
            <ol class="breadcrumb">               
                <li class="breadcrumb-item font-w700 text-uppercase" aria-current="page">
                    HSE Observations
                </li>
                <li class="breadcrumb-item">
                    Register
                </li>
            </ol>
        </nav>
        <div class="block-content block-content-full">
            <div class="table-responsive scrollable" id="content">    
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="tabledata">
                    <thead>
                        <tr style="position: sticky; top: -2px; z-index: 5;">
                            <td class="text-center bg-white titleheading" style="min-width:50px !important;" data-orderable="false"></td>
                            <td class="text-center bg-white titleheading" style="font-size:10px;font-weight:bold !important;min-width:50px !important;" data-orderable="false">Slno</td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Id</td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Title</td>
                            <td id="categories101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="departments101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Area</td>
                            <td id="types101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="responsibilities101" class="text-center titleheading bg-white" data-orderable="false"></td>
                            <td id="duedates101" class="titleheading text-center bg-white" data-orderable="false"></td>
                            <td id="processstageshead101" data-orderable="false" class="text-center titleheading bg-white"></td>
                            <td class="text-center titleheading bg-white" data-orderable="false">Action Status</td>
                            <td class="text-center bg-white titleheading" style="font-size:10px;font-weight:bold !important;min-width:fit-content !important;" data-orderable="false">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=0;@endphp
                        @foreach($issues as $issue)
                        @php 
                            $rr=$issue->id;
                            $date = $issue->duedate;
                            $today = Date("Y-m-d");
                            @endphp
                        <tr>
                            <td class="text-center"><input class="checkbox" type="checkbox" id="{{ $issue->id}}" name="id[]"></td>
                            <td class="text-center " style="font-size:10px;">{{ $no=$no+1}}</td>
                            <td class="text-center" style="font-size:12px;">
                                @if ($issue->status == 'open' && $today > $date)
                                <span class="badge badge-danger" data-toggle="popover" data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                                @else
                                <span class="badge badge bg-flat-lighter">{{$issue->issueid}}</span>
                                @endif
                            </td>
                            <td class="text-center font-w600 " style="font-size:10px;">
                                <a href="#">{{$issue->title}}</a>
                            </td>
                            <td class="text-center" style="font-size:10px;color:black">
                                {{$issue->categoryName}}
                                <div style="display: none;">{{'e_'.$issue->categoryName.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                {{$issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department}}
                                <div style="display: none;">{{'e_'.$issue->categoryName.'_f'}}{{$issue->departmentHse ? 'e_'.$issue->departmentHse->departmentname.'_f' : 'e_'.$issue->department.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                <em class="text-muted">{{$issue->area}}</em>
                            </td>
                            <td class="text-center" style="font-size:12px;">
                                @if(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Low")
                                <span class="badge badge-success">{{$hsetypes[$issue->type]}}</span>
                                @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="Moderate")
                                <span class="badge badge-warning">{{$hsetypes[$issue->type]}}</span>
                                @elseif(isset($hsetypes[$issue->type]) && $hsetypes[$issue->type]=="High")
                                <span class="badge badge-danger">{{$hsetypes[$issue->type]}}</span>
                                @else
                                <span class="badge badge-primary">{{$hsetypes[$issue->type]??'--'}}</span>
                                @endif
                                <div style="display: none;">{{isset($hsetypes[$issue->type]) ? 'e_'.$hsetypes[$issue->type].'_f' :''}}</div>
                            </td>
                            
                            <td class="text-center" style="font-size:10px;">
                                {{ $issue->responsible ? $issue->responsible->name : '-' }}
                                <div style="display: none;">{{$issue->responsible ? 'e_'.$issue->responsible->name.'_f': '-'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                <em class="text-muted">{{date('d-m-Y',strtotime($issue->duedate))}}</em>
                                <div style="display: none;">{{'e_'.date('Y',strtotime($issue->duedate)).'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:12px;">
                                @if($issue->processstage=='Awaiting actions')
                                <span class="badge badge-secondary">{{$issue->processstage}}</span>
                                @elseif($issue->processstage=='Actions Proposed')
                                <span class="badge badge bg-flat" style="color:white">{{$issue->processstage}}</span>
                                @elseif($issue->processstage=='Action Reviewed, OK')
                                <span class="badge badge-success">Action Approved</span>
                                <div style="display: none;">{{'e_Action Approved_f'}}</div>
                                @elseif($issue->processstage=='Action Reviewed, Not OK')
                                <span class="badge badge-danger">Action Rejected</span>
                                <div style="display: none;">{{'e_Action Rejected_f'}}</div>
                                @elseif($issue->processstage=='Need More Action')
                                <span class="badge badge-warning">{{$issue->processstage}}</span>
                                @elseif($issue->processstage=='Actions Completed')
                                <span class="badge badge-info">{{$issue->processstage}}</span>
                                @endif
                                <div style="display: none;">{{'e_'.$issue->processstage.'_f'}}</div>
                            </td>
                            <td class="text-center" style="font-size:10px;">
                                @php
                                    $impl_per = 0;
                                    switch ($issue->impl_status) {
                                        case 'Progress-10%':
                                            $impl_per = 10;
                                            break;
                                        case 'Progress-25%':
                                            $impl_per = 25;
                                            break;
                                        case 'Partially Completed-50%': 
                                            $impl_per = 50;
                                            break;
                                        case 'Completed-100%':
                                            $impl_per = 100;
                                            break;
                                    }
                                @endphp
                                <div class="progress mb-1" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$impl_per}}%;" aria-valuenow="{{$impl_per}}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="fs-xs fw-semibold mb-0">{{$impl_per}}%</p>
                            </td>
                            <td class="font-w600 font-size-sm text-center">
                                <div class="btn-group m-auto d-flex justify-content-center">
                                    <a href="#" class="btn btn-sm btn-alt-primary" data-toggle="dropdown" data-placement="top" title="Actions" id="drop{{$issue->id}}">
                                        <i class="fa fa-fw fa-ellipsis-v" style="font-size:10px;cursor:pointer"></i>
                                    </a>
                                    <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark" id="dropdownMenu{{ $issue->id }}">
                                        <a href="#" class="dropdown-item" style="font-size:10px;" onclick="theAlert({{ $issue->id }})">Add attachment</a>
                                        @if(Gate::allows('hse-responsibility-change'))
                                            <a href="{{ route('hseobservation.editresp', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Responsibility change</a>
                                        @endif
                    
                                        @if(Gate::allows('hse-observation-reviewer'))
                                            @if( $issue->processstage=="Actions Proposed" )
                                                <a href="{{ route('hseobservation.reviewstatus', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Review Action</a>
                                            @elseif( $issue->processstage=="Actions Completed" OR $issue->impl_status=="Completed-100%" )
                                                <a href="{{ route('hseobservation.closure', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Closure Review</a>
                                            @endif
                                        @endif
                                        @if(Gate::allows('hse-observation-reviewer') && $issue->processstage=="Accomplished")
                                            <a href="{{ route('hseobservation.reopen', ["id" => $rr])}}" class="dropdown-item" style="font-size:10px;">Reopen</a>
                                        @endif
                                    </div>
                                    <a href="#" class="btn btn-sm btn-alt-primary viewDetails" data-id="{{ $issue->id }}"  id="viewDetails" data-toggle="modal" data-placement="top" title="View" data-target="#viewModalDetails{{ $issue->id}}">
                                        <i class="far fa-eye text-center " style="font-size:10px;cursor:pointer" ></i>
                                    </a>
                                </div>


                                <!-- <button data-id="{{ $issue->id }}" id="viewDetails" class="viewDetails btn btn-primary" style="font-size:10px;width: 60px;"><Strong>View</Strong></button> -->

                                <!-- <button type="button" class="btn btn-primary viewDetails" style="font-size:10px;width: 60px;" data-toggle="modal" data-id="{{  $issue->id  }}" data-target="#viewModal{{ $issue->id }}"><strong>View</strong> </button> -->
                                <div id="viewModalDetails" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title" style="padding:1rem;">Detailed View</h2>
                                                <div class="p-1 text-center rounded-top" style="padding:1rem">

                                                    <img class="img-avatar img-avatar48 img-avatar-thumb" data-toggle="modal" data-target="#modal-block-fadein" alt="" id="responsible{{$issue->id}}">
                                                    <img class="img-avatar img-avatar48 img-avatar-thumb" data-toggle="modal" data-target="#modal-block-fadein" alt="" id="postedbyUser{{$issue->id}}">
                                                </div>
                                            </div>
                                            <table class="table table-striped">
                                                <tr>
                                                    <th class="text-left" style="width:70px">Issue id</th>
                                                    <td class="text-left">
                                                        <span class="badge badge-danger" id="issueId"></span>


                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Category</th>
                                                    <td style="text-transform:capitalize;font-weight:lighter;" class="text-left" id="category">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Department</th>
                                                    <td style="text-transform:capitalize" id="department" class="text-primary text-left">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Division</th>
                                                    <td class="division-gray text-left" id="division"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Subdivision</th>
                                                    <td class="subdivision-brown text-left" id="sub_div"></td>
                                                </tr>

                                                <tr>
                                                    <th class="text-left">Type</th>
                                                    <td class="text-left">
                                                        <span class="badge badge-success" id="type"></span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-left">Process stage</th>
                                                    <td class="text-left">
                                                        <span class="badge badge-secondary" id="processstage"></span>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th class="text-left">Title</th>
                                                    <td style="text-transform:capitalize;font-weight:lighter;" class="text-left" id="title">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-left">Observation</th>
                                                    <td class="text-left" style="text-transform:capitalize;font-weight:lighter;" id="observation">
                                                        <span></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Responsibility</th>
                                                    <td class="text-left" style="text-transform:lowercase;font-weight:lighter;" id="responsibility">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-left">Agreed action</th>
                                                    <td class="text-left" id="agreedaction" style="font-weight:lighter;"></td>
                                                </tr>

                                                <tr>
                                                    <th class="text-left">Due date</th>
                                                    <td class="text-left" style="font-weight:lighter;" id="duedate"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Correction</th>
                                                    <td class="text-left" style="text-transform:capitalize;font-weight:lighter;" id="correction"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Root cause</th>
                                                    <td class="text-left" style="text-transform:capitalize;font-weight:lighter;" id="rootcause"></td>
                                                </tr>


                                                <tr>
                                                    <th class="text-left">Corrective action</th>
                                                    <td class="text-left" style="text-transform:capitalize;font-weight:lighter;" id="correctiveaction">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Attachments (issues)</th>
                                                    <td class="text-left" id="issue_attachments">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Attachments (action)</th>
                                                    <td class="text-left" id="action_attachments">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Remarks</th>
                                                    <td class="text-left" style="text-transform:capitalize;color:red;font-weight:lighter;" id="remarks"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Action review remarks</th>
                                                    <td class="text-left" style="text-transform:capitalize;color:red;font-weight:lighter;" id="action_remarks">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Posted by</th>
                                                    <td class="text-left" style="font-weight:lighter;" id="postedby">&nbsp; {{ $issue->postedBy ? $issue->postedBy->name : ''}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Added date</th>
                                                    <td class="text-left" style="font-weight:lighter;" id="postdate"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Last reviewed</th>
                                                    <td class="text-left" style="font-weight:lighter;" id="updated_at"></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Approved by</th>
                                                    <td class="text-left" style="font-weight:lighter;" id="approvedby">&nbsp; {{ $issue->approvedByHse ? $issue->approvedByHse->name : ''}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-left">Issue closed by</th>
                                                    <td class="text-left" style="font-weight:lighter;" id="closedby">&nbsp; {{ $issue->closedByHse ? $issue->closedByHse->name : ''}}</td>
                                                </tr>
                                            </table>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="viewModal4" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Attachments
                    </h3>
                </div>
                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                    <div class="row justify-content-center">
                        <thead>
                            <tr class="table-secondary">
                                <th>S.No</th>
                                <th>Attachments</th>
                                {{-- <th>Image</th> --}}
                            </tr>
                        </thead>
                        <?php $sno = 0;
                        ?>
                        <tbody>
                            <tr>
                                <td style="font-size:10px;">
                                    {{ $sno = $sno + 1 }}
                                </td>
                                <td style="font-size:15px;width: 100%;" id="issueFile">
                                    <span></span>
                                </td>
                                {{-- <td style="font-size:15px;width:100%;">
                                    <img id="issue_file" />
                                </td> --}}
                                <td id="downloadButton">

                                </td>
                            </tr>
                        </tbody>
                    </div>
                </table>
            </div>
        </div>
    </div>
    <div id="viewModal5" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Attachments
                    </h3>
                </div>
                <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                    <div class="row justify-content-center">
                        <thead>
                            <tr class="table-secondary">
                                <th>S.No</th>
                                <th>Attachments</th>
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
</div>
{{----------delete request button for doc controllers --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/datatable_extension_for_complaint.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".viewDetails").on('click', function() {
            var id = $(this).data('id');
            $.ajax({
                url: "/show/" + id + "/" + "hseissues"
                , type: 'get'
                , data: {
                    id: id
                }
                , dataType: "json"
                , success: function(response) {
                    response.data.responsibleUser ? $('#responsible' + id).attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                    response.data.postedByUser ? $('#postedbyUser' + id).attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                }
            });
        });
    });

    function autoResizeDiv(height) {
        document.getElementById('content').style.height = (height) + 'px';
    }
    window.onresize = autoResizeDiv;
    autoResizeDiv(window.innerHeight - 100);

</script>
<script>
    $(document).ready(function () {
        function initializeDropdowns(tableId, dropdownConfigs) {
            $(tableId).one("preInit.dt", function () {
                let table = $(tableId).DataTable();
                let previousSelectedValue = null;
                dropdownConfigs.forEach(config => {
                    let { dropdownId, columnHeaderId, optionsArray, placeholder,isDateFilter} = config;
                    let columnIndex = table.column(columnHeaderId).index();
                    let optionsHTML = optionsArray.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                    let dropdownHTML = '';
                    if (isDateFilter) {
                        const tree = createTree(optionsArray);
                        dropdownHTML = buildTreeHtml(tree, placeholder.replace(/ /g, '-'), placeholder);
                    } else {
                        dropdownHTML = `
                            <select class="headingselect text-center" style="border:none;-webkit-appearance: none;min-width:fit-content;background-color:#fff;font-weight:600 !important;" id="${dropdownId}">
                                <option value="">${placeholder}&nbsp;&nbsp;&#9207;</option>
                                ${optionsHTML}
                            </select>
                        `;
                    }
                    $(columnHeaderId).html(dropdownHTML);
                    if (isDateFilter) {
                        const dateFilterElement = $('.' + placeholder.replace(/ /g, '-'));
                        dateFilterElement.find('.filter-header').click(function() {
                            $(this).next('.filter-content').slideToggle();
                        });
                        dateFilterElement.find('.expand').click(function(e) {
                            e.stopPropagation();
                            const dateFilter = $(this).closest('.date-filter')
                            if (dateFilter.next('.nested').length) {
                                dateFilter.next('.nested').slideToggle();
                                $(this).text($(this).text() === '-' ? '+' : '-');
                            }
                        });
                        dateFilterElement.find(".cke-filter").change(function() {
                            const eleName = $(this).attr('data-name')
                            const value = $(this).val();
                            let searchDataList = []
                            if(this.checked) {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', true);
                                }
                                else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', true);
                                }
                            } else {
                                if (eleName === "year") {
                                    dateFilterElement.find(".cke-filter[data-year=" + value + "]")
                                        .prop('checked', false);
                                } else if (eleName === "month") {
                                    dateFilterElement.find(".cke-filter[data-month=" + value + "]")
                                        .prop('checked', false);
                                }
                            }

                            const yearHasBeenSelected = $(this).attr('data-year');
                            const monthHasBeenSelected = $(this).attr('data-month');

                            let isSelectAllMonth = true;
                            dateFilterElement.find(".cke-filter[data-month=" + monthHasBeenSelected + "]").each(function (index, item) {
                                if(!$(item).is(":checked")) {
                                    isSelectAllMonth = false;
                                    return false;
                                }
                            })
                            if (isSelectAllMonth) {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=month][value="+ monthHasBeenSelected + "]")
                                    .prop('checked', false);
                            }
                            let isSelectAllYear = false;
                            dateFilterElement.find(".cke-filter[data-year=" + yearHasBeenSelected + "]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    isSelectAllYear = true;
                                    return false;
                                }
                            })
                            if (isSelectAllYear) {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', true);
                            } else {
                                dateFilterElement.find(".cke-filter[data-name=year][value="+ yearHasBeenSelected + "]")
                                    .prop('checked', false);
                            }

                            dateFilterElement.find(".cke-filter[data-name=month]").each(function (index, item) {
                                if($(item).is(":checked")) {
                                    searchDataList.push($(item).val())
                                }
                            })
                            const searchKeyWord = searchDataList.join('|');
                            console.log(searchKeyWord);
                            table.column(columnIndex).search(searchKeyWord,true,false).draw()
                            $(this).closest('.filter-content').slideToggle();
                        });
                    }else{
                        $(`#${dropdownId}`).on('change', function () {
                            let selectedValue = $(this).val();
                            let $select = $(this);

                            let firstOption = $select.find("option:first");
                            if (selectedValue) {
                                previousSelectedValue = selectedValue;
                                table.column(columnIndex).search('e_' + selectedValue + '_f').draw();
                                $(this).addClass('text-primary');
                                $("#" + dropdownId + " option").css('color', 'black');
                            } else {
                                table.column(columnIndex).search('').draw();
                                $(this).removeClass('text-primary').val('');
                                if (previousSelectedValue) {
                                    const $prevOption = $select.find(`option[value="${previousSelectedValue}"]`);
                                    if ($prevOption.length) {
                                        $prevOption.text(previousSelectedValue);
                                    }
                                }
                                firstOption.html(`${placeholder}&nbsp;&nbsp;&#9207;`);
                            }
                        });
                    }
                });
            });
        }

        // Prepare dropdown data from Blade
        let processStages = "{{ $allpstages }}".split(',');
        let types101 = "{{ $alltypenames }}".split(',');
        let responsibilities101 = "{{ $allusernames }}".split(',');
        let departments101 = "{{ $alldeptnames }}".split(',');
        let dueDates101 = "{{ $years }}".split(',');
        let categories101 = "{{ $allcategoriesname }}".split(',');
        // Initialize dropdowns for table #tabledata_1
        initializeDropdowns('#tabledata', [
            {
                dropdownId: 'processstages101_dropdown',
                columnHeaderId: '#processstageshead101',
                optionsArray: processStages,
                placeholder: 'Process Stage',
                isDateFilter:false
            },
            {
                dropdownId: 'types101_dropdown',
                columnHeaderId: '#types101',
                optionsArray: types101,
                placeholder: 'Type',
                isDateFilter:false
            },
            {
                dropdownId: 'responsibilities101_dropdown',
                columnHeaderId: '#responsibilities101',
                optionsArray: responsibilities101,
                placeholder: 'Responsibility',
                isDateFilter:false
            },
            {
                dropdownId: 'departments101_dropdown',
                columnHeaderId: '#departments101',
                optionsArray: departments101,
                placeholder: 'Department',
                isDateFilter:false
            },
            {
                dropdownId: 'categories101_dropdown',
                columnHeaderId: '#categories101',
                optionsArray: categories101,
                placeholder: 'Category',
                isDateFilter:false
            },
            {
                dropdownId: 'duedates101_dropdown',
                columnHeaderId: '#duedates101',
                optionsArray: dueDates101,
                placeholder: 'Due Date',
                isDateFilter:false
            }
        ]);
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                // Reset all dropdowns
                $(".headingselect").each(function () {
                    $(this).val(""); // reset dropdown
                    $(this).removeClass('text-primary');                    
                    const placeholder = $(this).find("option:first").text().split(' ')[0];
                    $(this).find("option:first").html(`${placeholder}`);
                });
            }
        });

    });
</script>
<!-- END Dynamic Table Full -->
<script>
    var route_hseobservation_index = "{{ route('hseobservation.index') }}";
</script>
<script src="{{ asset('js/hse/hse_script.js') }}"></script>

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
                            <a class="block block-rounded block-link-shadow bg-body" href="/user/hseobservation/issues">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="far fa-list-alt fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Register
                                    </p>
                                </div>
                            </a>
                            <!-- END Products -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/closedissue">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-check-square fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Closed Issues
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/openissue">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-clone fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        My Open Issues
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>
                        <div class="col-6">
                            <!-- Sales -->
                            <a class="block block-rounded block-link-shadow bg-body mb-0" href="/user/hseobservation/analysis">
                                <div class="block-content text-center" style="height:130px">
                                    <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                    <p class="font-w600 font-size-sm mt-2 mb-3">
                                        Analysis
                                    </p>
                                </div>
                            </a>
                            <!-- END Sales -->
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- </div>-->



</div>
<!-- END Apps Modal -->

@section('sideitem')
@if(Gate::allows('edit-hse') || Gate::allows('edit-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" style="margin-left:310px;" class="btn btn-sm btn-dual" id="edit" value=id[]>
        <i class="fa fa-1x fa-edit" style="color:grey;"></i>
    </button>
</div>
@endif
@if(Gate::allows('delete-hse') || Gate::allows('delete-all'))
<div class="input-group input-group-sm ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="del" value=id[]>
        <i class="fas fa-1x fa-trash" style="color:red;"></i>
    </button>
</div>
@endif
@endsection
@section('notification')
<div class="dropdown d-inline-block ml-2">
    <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-bell"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
        <div class="p-2 bg-primary-dark text-center rounded-top">
            <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
        </div>
        <ul class="nav-items mb-0">

            @foreach($issues as $issue)
            @php $today = date('d-m-Y');
            $postdate =$issue->postdate;
            $date=date('d-m-Y',strtotime($postdate));
            $t = Date("Y-m-d");
            $dep = $issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department;
            @endphp
            @if($dep==Auth::user()->deptid)
            @if($issue->processstage=="Awaiting actions" && $today==$date)
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-check-circle text-success"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">A new issue raised</div>
                        <span class="font-w500 text-muted">An issue raised in {{$issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department}} department with id {{$issue->issueid}}</span>
                    </div>
                </a>
            </li>
            @endif
            @if($issue->status == 'open' && $t > $issue->duedate)
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-times-circle text-danger"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">Due</div>
                        <span class="font-w500 text-muted">Due on {{$issue->issueid}} </span>
                    </div>
                </a>
            </li>
            @endif
            @if($issue->impl_status!="Completed-100%")
            <li>
                <a class="text-dark media py-2">
                    <div class="mr-2 ml-3">
                        <i class="fa fa-fw fa-plus-circle text-primary"></i>
                    </div>
                    <div class="media-body pr-2">
                        <div class="font-w600">Pending Actions</div>
                        <span class="font-w500 text-muted">Actions pending in {{$issue->issueid}}</span>
                    </div>
                </a>
            </li>
            @endif

            @endif

            @endforeach
        </ul>

    </div>
</div>
@endsection
@include('exporting.foractionsystem')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function theAlert(id) {
        let url = '{{ route("hse.saveActionAttachment", ":id") }}';
        url = url.replace(':id', id);
        Swal.fire({
            title: 'Select files'
            , html: '<label>Action Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>' +
                '<label>Issue Files: </label><input type="file"  class="swal2-file" accept="image/*,.pdf,.doc,.docs,.xlsx,.xls,.csv" multiple>'
            , showCancelButton: true
            , confirmButtonText: 'Upload'
            , onBeforeOpen: () => {
                $(".swal2-file").change(function() {
                    var reader = new FileReader();
                    reader.readAsDataURL(this.files);
                });
            }
        }).then((file) => {
            if (file.value) {
                var formData = new FormData();
                var files = $('.swal2-file')[0].files;
                var issueFiles = $('.swal2-file')[1].files;
                $.each(files, function(i, x) {
                    formData.append('fileToUpload[]', x);
                });
                $.each(issueFiles, function(i, x) {
                    formData.append('fileToUpload1[]', x);
                });
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                    , method: 'post'
                    , url: url
                    , data: formData
                    , processData: false
                    , contentType: false
                    , success: function(resp) {
                        Swal.fire({
                            title: 'Uploaded'
                            , text: 'Your files have been uploaded'
                            , icon: 'success'
                        })
                    }
                    , error: function() {
                        Swal.fire({
                            title: 'Oops'
                            , text: 'Something went wrong!'
                            , icon: 'error'
                        })
                    }
                })
            }
        })


    }

</script>
<script>
    $(document).ready(function() {
        $(".viewDetails").click(function() {
            var id = $(this).data('id')
            $.ajax({
                url: "/show/" + id + "/" + "hseissues"
                , type: 'get'
                , data: {
                    id: id
                }
                , dataType: "json"
                , success: function(response) {
                    console.log(response.data);
                    $('#issueId').html(response.data.issueid);
                    $('#category').html(response.data.hse_category ? response.data.hse_category.categoryname : '');
                    $('#department').html(response.data.department);
                    response.data.division ? $("#division").html(response.data.division.name) : '';
                    response.data.subdiv ? $('#sub_div').html(response.data.subdiv.name) : '';
                    $('#type').html(response.data.type);
                    $('#processstage').html(response.data.processstage);
                    $('#title').html(response.data.title);
                    response.data.responsibleUser ? $('#responsible').attr('src', '/storage/profile/' + response.data.responsibleUser.profile_picture) : $('#responsible').attr('src', '');
                    response.data.postedByUser ? $('#postedbyUser').attr('src', '/storage/profile/' + response.data.postedByUser.profile_picture) : $('#postedbyUser').attr('src', '');
                    $('#observation span').html(response.data.observation);
                    $('#responsibility').html(response.data.responsibility);
                    $('#agreedaction').html(response.data.agreedaction);
                    $('#duedate').html(response.data.duedate);
                    $('#correction').html(response.data.correction);
                    $('#rootcause').html(response.data.rootcause);
                    $('#correctiveaction').html(response.data.correctiveaction);
                    $('#remarks').html(response.data.remarks);
                    $('#action_remarks').html(response.data.action_remarks);
                    $('#postedby').html(response.data.postedByUser ? response.data.postedByUser.name : '');
                    $('#postdate').html(response.data.postdate);
                    $('#updated_at').html(response.data.last_reviewed_at);
                    // $('#approvedby').html(response.data.approvedby.name);
                    $('#closedby').html(response.data.closedby);
                    $('#issue_attachments').html('<button onclick="getIssueAttachment(' + response.data.id + ')" id="issueAttachment" data-id="' + response.data.id + '" class="issueAttachment btn btn-secondary btn-small" style="font-size:13px;width: 150px;"><strong>View Attachments</strong></button>');
                    $('#action_attachments').html('<button onclick="getActionAttachment(' + response.data.id + ')" id="actionAttachment" data-id="' + response.data.id + '" class="actionAttachment btn btn-secondary btn-small" style="font-size:13px;width: 150px;"><strong>View Attachments</strong></button>');
                    $('#viewModalDetails').modal('show');
                }
            });
        });
    });

    function getIssueAttachment(id) {
        let issue_url = "{{ route('hseobservation.issue', ':id')}}";
        issue_url = issue_url.replace(':id', id);
        $.ajax({
            url: issue_url
            , type: 'get'
            , data: {
                id: id
            }
            , dataType: "json"
            , success: function(response) {
                if (response.data.length > 0) {
                    var fil_path = "/storage/issue_hse/";
                    for (let i = 0; i < response.data.length; i++) {
                        var fullUrl = fil_path + response.data[i].issue_file
                        $('#issueFile').html(response.data[i].issue_file)
                        $('#downloadButton').html(
                            "<a style='margin-left:70px;font-size:11px;width: 80px;' class='btn btn-success' id='downloadFile' download='" + response.data[i].issue_file + "'>Download</a>"
                        )
                        $('#downloadFile').attr('href', fullUrl);
                        $('#image').attr('src', fullUrl);
                        $('#viewModal4').modal('show');
                    }
                } else {
                    $('#issueFile').html('<span>No File</span>')
                    $('#viewModal4').modal('show');
                }
            }
        });
    }


    function getActionAttachment(id) {

        let action_url = "{{ route('hseobservation.action', ':id')}}";
        action_url = action_url.replace(':id', id);
        $.ajax({
            url: action_url
            , type: 'get'
            , data: {
                id: id
            }
            , dataType: "json"
            , success: function(response) {
                if (response.data.length > 0) {
                    var fil_path = "/storage/issue_hse/";
                    for (let i = 0; i < response.data.length; i++) {
                        var fullUrl = fil_path + response.data[i].action_file;
                        console.log(fullUrl);
                        $('#actionFile').html(response.data[i].action_file)
                        $('#actionDownloadButton').html(
                            "<a style='margin-left:70px;font-size:11px;width: 80px;' class='btn btn-success' id='actionDownloadFile' download='" + response.data[i].action_file + "'>Download</a>"
                        )
                        $('#actionDownloadFile').attr('href', fullUrl);
                        // $('#downloadFile').attr('download', response.data[i].action_file);
                        $('#viewModal5').modal('show');
                    }
                } else {
                    $('#issueFile').html('<span>No File</span>')
                    $('#viewModal5').modal('show');
                }
            }
        });
    }

</script>
@endsection