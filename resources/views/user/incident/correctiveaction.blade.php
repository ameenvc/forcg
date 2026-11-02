@extends('pages.datatables')
@section('exportadd')
    <div class="input-group input-group-sm">
        <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">

            <i class="fa fa-print  fa-1x" style="color:grey"></i>

        </a>
    </div>

    {{-- <div class="input-group input-group-sm">
<button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
    <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
</button>
<ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
<li>
    <!--change route as below-->
        <form method="post" action="{{url('/user/convert/file/'.'tvalue1')}}" style="margin-bottom: 0px">
            @csrf
            <button onclick="return fnCovertTable('pdf')" style="width:50px;  font-size:10px;border:none;background-color:white;">Pdf</button>
            <!--change hidden input type id as below-->
            <input type="hidden"  id="tvalue1" name="tvalue1"/>
        </form>
    </li>
    <li><button  onclick="fnCovertTable('excel')" style="width:50px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
    <li><button  onclick="fnCovertTable('word')" style="width:50px;  font-size:10px;border:none;background-color:white;">Word</button></li>
</ul>
</div> --}}

    <div class="input-group input-group-sm">
        <a type="button" href="{{ url('/user/report/add') }}" class="btn btn-sm btn-dual" style="margin-left:8px;"
            style="">
            <i class="fa fa-1x fa-plus" style="color:grey;"></i>
        </a>
    </div>
@endsection
@section('content')
    <!doctype html>
    <html lang="en">

    <body>
        <!-- Page Content -->
        <div class="content" style="width:100%;">
            <!-- Dynamic Table Full -->
            <div class="block block-rounded">
                {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                <div class="block-content block-content-full">
                    <div class="scrollable" style="height:75vh;overflow-y:scroll;">
                        <table class="move" width="15%" style="margin-left:670px">
                            <tr>

                                <th>
                                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                        <ol
                                            class="breadcrumb breadcrumb-alt"style="margin-right:-200px;margin-bottom:-250px;">
                                            <li class="breadcrumb-item" style="margin-left:-595px;margin-top:-8px">Incident
                                                Report</li>
                                            <li class="breadcrumb-item" aria-current="page" style="margin-top:-8px">
                                                <a class="link-fx" href="">Open Corrective Actions</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </th>
                            </tr>
                        </table>
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                            <thead>

                                <tr>
                                    <!--<th></th>-->
                                    <th class="text-center"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">S.No
                                    </th>
                                    <th class="text-center"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Issue
                                        Id</th>
                                    <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Type
                                    </th>
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">
                                        Department</th>
                                    <th style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">
                                        Incident Date</th>
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">
                                        injured Person</th>
                                    <!--<th class="d-none d-sm-table-cell" style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Root Cause</th>-->
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Added
                                        date</th>
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Target
                                        Date</th>
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">Status
                                    </th>
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">
                                        Implementation Status</th>
                                    <th class="d-none d-sm-table-cell"
                                        style="font-size:10px;text-transform:capitalize; position:sticky;top:-1;">View
                                    </th>


                                </tr>
                            </thead>
                        <tbody>
                                <?php $no = 0; ?>
                                @foreach ($corrections as $issue)
                                    <tr>
                                        <!--<td><input class="checkbox" type="checkbox" id="{{ $issue->issueid }}" name="id[]" ></td>-->

                                        <td class="text-center" style="font-size:10px;">{{ $no = $no + 1 }}</td>
                                        <td class="text-center" style="font-size:13px;"><span
                                                class="badge badge bg-success-light">{{ $issue->issueid }}</span></td>
                                        <td>
                                            <em class="text-mute" style="font-size:12px;">
                                                @if ($issue->type == 'Low')
                                                    <span
                                                        style="font-size:10px;background-color:#e0edcf;color:#176044">{{ $issue->type }}</span>
                                                @elseif($issue->type == 'Moderate' || $issue->type == 'Medium')
                                                    <span
                                                        style="font-size:10px;background-color:#ffebcc;color:#cc8800">{{ $issue->type }}</span>
                                                @elseif($issue->type == 'High')
                                                    <span
                                                        style="font-size:10px;background-color:#f8d4d4;color:#af1e1e">{{ $issue->type }}</span>
                                                @elseif($issue->type == '' || $issue->type == '-')
                                                    <span class="status status-blue">-</span>
                                                @elseif($issue->typ)
                                                    <span
                                                        style="font-size:10px;background-color:{{ $issue->typ->background_color }};color:{{ $issue->typ->text_color }}"
                                                        class="badge-class">{{ $issue->typ ? $issue->typ->name : '' }}</span>
                                                @else
                                                    <span class="badge badge-primary"></span>
                                                @endif
                                            </em>
                                        </td>
                                        <td class="" style="font-size:10px;">
                                            <em class="text-muted">
                                                {{ $issue->dept ? $issue->dept->departmentname : '' }}
                                            </em>
                                        </td>
                                        @foreach ($incrpt as $incident)
                                            @if ($incident->id == $issue->incident_id)
                                                <td class="" style="font-size:10px;">
                                                    <em class="text-muted">{{ $incident->incidentdate }}</em>
                                                </td>
                                                <td class="" style="font-size:10px;">
                                                    <em class="text-muted">{{ $incident->injuredperson }}</em>
                                                </td>
                                            @endif
                                        @endforeach
                                        <!--<td class="" style="font-size:10px;"><em class="text-muted">{{ $issue->rootcause }}</em></td>-->
                                        <td class="" style="font-size:10px;"><em
                                                class="text-muted">{{ $issue->postdate }}</em></td>
                                        <td class="" style="font-size:10px;">
                                            <em class="text-muted">
                                                {{ $issue->correction_target_date ? date('d-m-Y', strtotime($issue->correction_target_date)) : '-' }}
                                            </em>
                                        </td>
                                        <td class="d-none d-sm-table-cell" style="font-size:12px;">
                                            @if ($issue->status == 'closed')
                                                <span class="badge badge-success">{{ $issue->status }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $issue->status }}</span>
                                            @endif
                                        </td>
                                        <td class="" style="font-size:12px;">
                                            @if ($issue->impl_status == 'Not Started-0%')
                                                <span class="badge badge-danger">{{ $issue->impl_status }}</span>
                                            @elseif($issue->impl_status == 'Progress-25%')
                                                <span class="badge badge-warning">{{ $issue->impl_status }}</span>
                                            @elseif($issue->impl_status == 'Partially Completed-50%')
                                                <span class="badge badge-info">{{ $issue->impl_status }}</span>
                                            @else
                                                <span class="badge badge-success">{{ $issue->impl_status }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-primary viewDetails"
                                                style="font-size:10px;width: 60px;" data-toggle="modal"
                                                data-id="{{ $issue->id }}"
                                                data-issue-id="{{ $issue->issueid }}"
                                                data-department="{{ $issue->dept ? $issue->dept->departmentname : '--' }}"
                                                data-type='@if($issue->type=="Low")
                                                                <span style="font-size:10px;background-color:#e0edcf;color:#176044">{{$issue->type}}</span>
                                                                @elseif($issue->type=="Moderate"|| $issue->type=="Medium")
                                                                <span style="font-size:10px;background-color:#ffebcc;color:#cc8800">{{$issue->type}}</span>
                                                                @elseif($issue->type=="High")
                                                                <span style="font-size:10px;background-color:#f8d4d4;color:#af1e1e">{{$issue->type}}</span>
                                                                @elseif($issue->type=='' || $issue->type=='-')
                                                                <span class="status status-blue">-</span>
                                                                @elseif($issue->typ)
                                                                <span style="font-size:10px;background-color:{{ $issue->typ->background_color }};color:{{ $issue->typ->text_color}}" class="badge-class">{{$issue->typ ? $issue->typ->name : ''}}</span>
                                                                @else
                                                                <span class="badge badge-primary"></span>
                                                                @endif'
                                                data-title="{{$issue->title}}"
                                                data-observation="<?php echo nl2br($issue->observation )?>"
                                                data-agreed-action="<?php echo nl2br($issue->agreedaction)?>"
                                                data-due-date="{{ $issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) : '-' }}"
                                                data-correction="<?php echo nl2br($issue->correction )?>"
                                                data-root-cause="<?php echo nl2br($issue->rootcause )?>"
                                                data-corrective-action="<?php echo nl2br($issue->correctiveaction )?>"
                                                data-attachment-issues="{{ $issue->incidentReport && $issue->incidentReport->attachmentby_reporter ? '<button type="button" class="btn btn-secondary btn-small view-attachments" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="' .$issue->incidentReport->id. '" data-attachments="' .$issue->incidentReport->attachmentby_reporter. '" data-report-no="'.str_replace('/', '_', $issue->incidentReport->report_no).'" data-target="#viewAttachmentModal"><strong>View Attachments</strong> </button>' : ''}}"
                                                data-attachment-action="{{ $issue->incidentReport && $issue->incidentReport->attachmentby_reviewer ? '<button type="button" class="btn btn-secondary btn-small view-attachments" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="' .$issue->incidentReport->id. '" data-attachments="' .$issue->incidentReport->attachmentby_reviewer. '" data-report-no="'.str_replace('/', '_', $issue->incidentReport->report_no).'" data-target="#viewAttachmentModal"><strong>View Attachments</strong> </button>' : ''}}"
                                                data-remarks="<?php echo nl2br($issue->remarks)?>"
                                                data-action-review-remarks="<?php echo nl2br($issue->action_remarks)?>"
                                                data-posted-by="{{ $issue->postedByCorrective ? $issue->postedByCorrective->name : ''}}"
                                                data-added-date="{{$issue->postdate}}"
                                                data-last-reviewed="{{$issue->updated_at}}"
                                                data-approved-by="{{ $issue->approvedByCorrective ? $issue->approvedByCorrective->name : ''}}"
                                                data-issue-closed-by="{{ $issue->closedByCorrective ? $issue->closedByCorrective->name : ''}}"
                                                data-status="{{$issue->status}}"
                                                data-target="#viewModal"><strong>View</strong>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END Dynamic Table Full -->
            </div>
            <!-- END Page Content -->
            {{-- </main> --}}
            <!-- END Main Container -->


            <!-- END Footer -->

            <!-- Apps Modal -->
            <!-- Opens from the modal toggle button in the header -->
            <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
                aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">More</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-dismiss="modal"
                                        aria-label="Close">
                                        <i class="si si-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <div class="row gutters-tiny">
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body"
                                            href="/user/incidents/register">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Incident Register
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <!-- Products -->
                                        <a class="block block-rounded block-link-shadow bg-body"
                                            href="/user/incidents/closed/incidents">
                                            <div class="block-content text-center">
                                                <i class="far fa-list-alt fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Closed Incident
                                                </p>
                                            </div>
                                        </a>
                                        <!-- END Products -->
                                    </div>
                                    <div class="col-6">
                                        <a class="block block-rounded block-link-shadow bg-body mb-0"
                                            href="/user/incident/correactions">
                                            <div class="block-content text-center">
                                                <i class="fas fa-clone fa-2x text-primary"></i>
                                                <p class="font-w600 font-size-sm mt-2 mb-3">
                                                    Open Corrective Actions
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Apps Modal -->

        {{-- View Modal --}}
        <div class="modal fade" id="viewModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Details</h5>
                        <button class="btn-block-option" data-dismiss="modal"><i class="si si-close"></i></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <tr>
                                <th style="width:70px">Issue id</th>
                                <td><span class="badge badge bg-success-light" id="m_issue_id"></span></td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td id="m_department"></td>
                            </tr>

                            <tr>
                                <th>Type</th>
                                <td class="d-none d-sm-table-cell" id="m_type"></td>
                            </tr>
                            <tr>
                                <th>Title</th>
                                <td><span class="badge badge-info" id="m_title"></span></td>
                            </tr>

                            <tr>
                                <th>Observation</th>
                                <td id="m_observation"></td>
                            </tr>
                            <tr>
                                <th>Agreed action</th>
                                <td id="m_agreed_action"></td>
                            </tr>

                            <tr>
                                <th>Due Date</th>
                                <td id="m_due_date"></td>
                            </tr>
                            <tr>
                                <th>Correction</th>
                                <td id="m_correction"></td>
                            </tr>
                            <tr>
                                <th>Root cause</th>
                                <td id="m_root_cause"></td>
                            </tr>

                            <tr>
                                <th>Corrective action</th>
                                <td id="m_corrective_action"></td>
                            </tr>
                            <tr>
                                <th>Attachments (issues)</th>
                                <td id="m_attachments_issues"></td>
                            </tr>
                            <tr>
                                <th>Attachments (action)</th>
                                <td id="m_attachments_action"></td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td id="m_remarks"></td>
                            </tr>
                            <tr>
                                <th>Action review remarks</th>
                                <td id="m_review_remarks"></td>
                            </tr>
                            <tr>
                                <th>Posted by</th>
                                <td id="m_posted_by"></td>
                            </tr>
                            <tr>
                                <th>Added date</th>
                                <td id="m_added_date"></td>
                            </tr>
                            <tr>
                                <th>Last reviewed</th>
                                <td id="m_last_reviewed"></td>
                            </tr>

                            <tr>
                                <th>Approved by</th>
                                <td id="m_approved_by"></td>
                            </tr>
                            <tr>
                                <th>Issue closed by</th>
                                <td id="m_closed_by"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- View Attachments Modal --}}
        <div class="modal fade" id="viewAttachmentModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attachments</h5>
                        <button class="btn-block-option" data-dismiss="modal"><i class="si si-close"></i></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                            <div class="justify-content-center">
                                <thead>
                                    <tr class="table-secondary">
                                        <th>S.No</th>
                                        <th style="width: 100%">Attachments</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="attachmentBody">
                                </tbody>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).on("click",".viewDetails",function(){
            $("#m_id").text($(this).data("id"));
            $("#m_issue_id").text($(this).data("issueId"));
            $("#m_title").text($(this).data("title"));
            $("#m_department").text($(this).data("department"));
            $("#m_type").html($(this).data("type"));
            $("#m_observation").text($(this).data("observation"));
            $("#m_agreed_action").text($(this).data("agreedAction"));
            $("#m_due_date").text($(this).data("dueDate"));
            $("#m_correction").text($(this).data("correction"));
            $("#m_root_cause").text($(this).data("rootCause"));
            $("#m_corrective_action").text($(this).data("correctiveAction"));
            $("#m_attachments_issues").html($(this).data("attachmentIssues"));
            $("#m_attachments_action").html($(this).data("attachmentAction"));
            $("#m_remarks").text($(this).data("remarks"));
            $("#m_review_remarks").text($(this).data("actionReviewRemarks"));
            $("#m_posted_by").text($(this).data("postedBy"));
            $("#m_added_date").text($(this).data("addedDate"));
            $("#m_last_reviewed").text($(this).data("lastReviewed"));
            $("#m_approved_by").text($(this).data("approvedBy"));
            $("#m_closed_by").text($(this).data("issueClosedBy"));
            $("#m_status").text($(this).data("status"));
        });

        $(document).on("click",".view-attachments",function(){
            var attachments = $(this).data('attachments');
            var dir = $(this).data('reportNo');

            let tbody = $("#attachmentBody");
            tbody.empty();

            if(!attachments){
                tbody.append("<tr><td colspan='3' class='text-center'>No Files</td></tr>");
                return;
            }

            let files = attachments.split(",");
            let basePath = "{{ asset('storage/IncidentFiles') }}/";

            files.forEach(function(file,index){
                let url = basePath + dir + "/" + file;
                let row = `
                    <tr>
                        <td>${index+1}</td>
                        <td>${file}</td>
                        <td>
                            <a class="btn btn-success btn-sm"
                            href="${url}"
                            download>
                            Download
                            </a>
                        </td>
                    </tr>
                `;

                tbody.append(row);
            });
        });

        $(document).on("click",".view-attachments",function(){
            $("#viewModal").modal("hide");
        });

        $('#viewAttachmentModal').on('hidden.bs.modal', function () {
            // remove leftover backdrop if exists
            $('.modal-backdrop').remove();

            // re-enable body scroll handling
            $('body').removeClass('modal-open');

            // reopen first modal
            $('#viewModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        // $(document).on('hidden.bs.modal', '.modal', function () {
        //     $('.modal-backdrop').last().remove();

        //     if ($('.modal.show').length) {
        //         $('body').addClass('modal-open');
        //     }
        // });
    </script>
</html>
@endsection
