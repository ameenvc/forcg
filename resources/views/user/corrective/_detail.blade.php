@php
    $cat = $issue->cat ? $issue->cat->categoryname : '';
    $dept = $issue->correctionDept ? $issue->correctionDept->departmentname : '';
    $responsibilities = explode(',', $issue->responsibility);
    $target_date = explode(',', $issue->target_date);
    $resp_to_show = $correctionResponsibilities->pluck('name')->toArray();
    $rootcause = explode('$', $issue->rootcause);
    $correctiveaction = explode('$', $issue->correctiveaction);
    $responsibility = explode(',', $issue->responsibility);
    $department = explode(',', $issue->department);
    $count = 1;
    $today = date('d-m-Y');
    $postdate = $issue->postdate;
    $date= date('d-m-Y',strtotime($postdate));

    $initialStageFlag = in_array($issue->processstage, ['Awaiting actions','rejected','Entry Under Review']);    
@endphp
<div id="viewModal{{ $issue->id }}"
     class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" style="padding:1rem;">Detailed View</h2>
                <div class="p-1 text-center rounded-top" style="padding:1rem">
                    <img class="img-avatar img-avatar48 img-avatar-thumb"
                         data-toggle="modal" data-target="#modal-block-fadein" alt="" id="responsible{{$issue->id}}">
                    <img class="img-avatar img-avatar48 img-avatar-thumb"
                         data-toggle="modal" data-target="#modal-block-fadein" alt="" id="postedbyUser{{$issue->id}}">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th style="width:222px">Issue id</th>
                        <td>
                            @if (($issue->status == 'open' || $issue->status == 'NULL') && $today > $date)
                                <span class="status-red badge-class" data-toggle="popover"
                                    data-placement="right" data-content="Due">{{$issue->issueid}}</span>
                            @else
                                <span class="status-success badge-class">{{$issue->issueid}}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        @if($issue->category=='Legal Requirement')
                            <td>{{$issue->category}}</td>
                        @elseif($issue->category=='Incident Investigation')
                            <td>{{$issue->category}}</td>
                        @elseif($issue->category=='Management Review')
                            <td>{{$issue->category}}</td>
                        @elseif($cat)
                            <td>{{$cat}}</td>
                        @endif
                    </tr>
                    @if($issue->source)
                    <tr>
                        <th>Source</th>
                        <td >{{$issue->source}}</td>
                    </tr>
                    @endif
                    @if($issue->categoryDetectionStage)
                    <tr>
                        <th>Detection Stage</th>
                        <td >{{$issue->categoryDetectionStage->name}}</td>
                    </tr>
                    @endif
                    @if($ncrFields)
                        {!! $ncrFields !!}
                    @endif
                    @if($initialStageFlag)
                        <tr>
                            <th>Department</th>
                            <td>{{$dept?$dept:'-'}}</td>
                        </tr>
                        @if($issue->division)
                        <tr>
                            <th>Division</th>
                            <td>{{ $issue->division ? $issue->division->name : '' }}</td>
                        </tr>
                        @endif
                        @if($issue->area)
                        <tr>
                            <th>Subdivision</th>
                            <td>{{ $issue->area ? $issue->area->name : '' }}</td>
                        </tr>
                        @endif
                    @endif
                    @if($issue->typ)
                        <tr>
                            <th>Type</th>
                            <td>
                                <span style="background-color:{{ $issue->typ->background_color }};color:{{ $issue->typ->text_color}}" class="badge-class">{{$issue->typ ? $issue->typ->name : ''}}</span>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>Process stage</th>
                        <td>
                            @if($issue->processstage=='Awaiting actions')
                                <span class="badge-class status-gray">Awaiting actions</span>
                            @elseif($issue->processstage == 'Correction Proposed- RC Awaiting' || $issue->processstage == 'Actions Proposed- Review Awaiting' || $issue->processstage == 'RC Updated- Correction Awaiting')
                                <span class="badge-class status-blue">{{$issue->processstage}}</span>
                            @elseif($issue->processstage=='Action Reviewed, OK')
                                <span class="badge-class status-success">Action Approved</span>
                            @elseif($issue->processstage=='Action Reviewed, Not OK')
                                <span class="badge-class status-red">Action Rejected</span>
                            @elseif($issue->processstage=='rejected')
                                <span class="badge-class status-red">Entry Rejected</span>
                            @elseif($issue->processstage=='Need More Action' || $issue->processstage=='Entry Under Review')
                                <span class="badge-class status-warning">{{$issue->processstage}}</span>
                            @elseif($issue->processstage=='Actions Completed')
                                <span class="badge-class status-blue">{{$issue->processstage}}</span>
                            @elseif($issue->status == 'closed' && $issue->closed <= $issue->correction_target_date) 
                                <span class="badge-class status-success">{{ucfirst($issue->status)}}</span>
                            @elseif($issue->status == 'closed' && $issue->closed > $issue->correction_target_date)
                                @php $issue->status='Closed After Due' @endphp
                                <span class="badge-class status-warning">{{$issue->status}}</span>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        @if($cat=="Incident Investigation")
                            <td >Incident # {{$issue->title}}</td>
                        @else
                            <td >{{$issue->title}}</td>
                        @endif
                    </tr>

                    <tr>
                        <th>Observation</th>
                        <td><?php echo nl2br($issue->observation )?></td>
                    </tr>
                    <tr>
                        <th>Is this a previously occurred NCR?</th>
                        <td>{{ucfirst($issue->is_previously_occurred)}}</td>
                    </tr>
                    @if($issue->is_previously_occurred=='yes')
                        <tr>
                            <th>No. of Occurrence</th>
                            <td>{{$issue->no_of_occurrence}}</td>
                        </tr>
                        <tr>
                            <th>Previous NCR Reference</th>
                            <td>
                                @if($issue->previousNcrReferenceRelation)
                                    {{$issue->previousNcrReferenceRelation->issueid.' - '.$issue->previousNcrReferenceRelation->title}}
                                @elseif($issue->previous_ncr_reference==0)
                                    {{$issue->other_previous_ncr_reference}}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endif
                    @if($initialStageFlag && $issue->correction_responsibility)
                    <tr>
                        <th>Responsibility</th>
                        <td style="word-break: break-word; white-space: normal;">
                            @php
                                $responsibilities = explode(',', $issue->correction_responsibility);
                                $usersByEmail      = $users->keyBy('email');
                            @endphp
                            @foreach($responsibilities as $userEmail)
                                <div>
                                    <span class="tooltipClass" data-toggle="tooltip" title="{{ $userEmail }}" data-placement="top">
                                        {{ optional($usersByEmail->get($userEmail))->name }}
                                    </span>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @if($issue->agreedaction)
                    <tr>
                        <th>Recommended action</th>
                        <td><?php echo nl2br($issue->agreedaction)?></td>
                    </tr>
                    @endif
                    @if($issue->correction_target_date && $initialStageFlag)
                        <tr>
                            <th>Recommended due date</th>
                            <td>{{$issue->correction_target_date ? date('d-m-Y',strtotime($issue->correction_target_date)) : '' }}</td>
                        </tr>
                    @endif
                    @if(!$initialStageFlag)
                        <tr>
                            <th>MOC Required</th>
                            <td>{{ucfirst($issue->is_moc_required) }}</td>
                        </tr>
                    @endif
                    @if($issue->is_moc_required=='yes')
                        <tr>
                            <th>MOC ID</th>
                            <td>
                                <a href="#" class="view-moc-detail" data-toggle="tooltip" title="Click to view MOC details"
                                    data-route="{{ $issue->moc->id }}">
                                    {{$issue->moc->crid}}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Status of MOC</th>
                            <td style="">
                                @if($issue->moc->status=="Review Pending")
                                <span class="badge-class badge-info">{{$issue->moc->status}}</span>
                                @elseif($issue->moc->status=="Reviewed")
                                <span class="badge-class badge-success">{{$issue->moc->status}}</span>
                                @elseif($issue->moc->status=="Approved")
                                <span class="badge-class badge-primary">{{$issue->moc->status}}</span>
                                @elseif($issue->moc->status=="Rejected")
                                <span class="badge-class badge-danger"> {{$issue->moc->status}}</span>
                                @elseif($issue->moc->status=="Partially Completed")
                                <span class="badge-class badge-warning"> {{$issue->moc->status}}</span>
                                @elseif($issue->moc->status=="Not Started")
                                <span class="badge-class badge-warning"> {{$issue->moc->statuss}}</span>
                                @elseif($issue->moc->status=="Completed")
                                <span class="badge-class badge-info"> {{$issue->moc->status}}</span>
                                @elseif($issue->moc->status=="Closed")
                                <span class="badge-class badge-success"> {{$issue->moc->status}}</span>
                                @else
                                <span class="badge-class badge-warning">{{$issue->moc->status}}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Change description</th>
                            <td>{{nl2br($issue->change_description)}}</td>
                        </tr>
                    @endif
                    {{-- </table> --}}
                    <tr>
                        <td colspan="2" class="p-0">
                            @if(!$initialStageFlag)
                                <table class="table table-bordered table-vcenter table-striped" style="width:auto;margin-bottom: 0 !important;">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center titleheading" style="{{ $issue->division==null && $issue->area==null ? 'width: 50%': 'width: 25%' }}">Correction</th>
                                            <th class="text-center titleheading" style="{{ $issue->division==null && $issue->area==null ? 'width: 19%': '' }}">Department</th>
                                            @if($issue->division)
                                            <th class="text-center titleheading">Division</th>   
                                            @endif  
                                            @if($issue->area)                                           
                                            <th class="text-center titleheading">Sub Division</th>
                                            @endif
                                            <th class="text-center titleheading" style="{{ $issue->division==null && $issue->area==null ? 'width: 19%': '' }}">Responsibility</th>
                                            <th class="text-center titleheading" style="{{ $issue->division==null && $issue->area==null ? 'width: 10%': '' }}">Target Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="td-class px-1">
                                                <textarea rows="3" class="form-control" name="correction" style="font-size:13px;" readonly>{{$issue->correction}}</textarea>
                                            </td>
                                            <td class="td-class px-1">
                                                <input class="form-control" style="font-size:13px;" value="{{ $issue->correctionDept ? $issue->correctionDept->departmentname : '' }}" readonly />
                                            </td>
                                            @if($issue->division)
                                                <td class="td-class px-1">
                                                <input type="text" value="{{ $issue->division->name}}" class="form-control" style="font-size:13px;" readonly>  
                                                </td> 
                                            @endif                                                       
                                            @if($issue->area)       
                                                <td class="td-class px-1">
                                                <input type="text" value="{{ $issue->area->name}}" class="form-control" style="font-size:13px;" readonly>
                                                </td>
                                            @endif
                                            <td class="td-class px-1">
                                                @if(count($resp_to_show)>=2)
                                                <textarea class="form-control" style="font-size:13px;" rows="3" readonly>{{implode(', ',$resp_to_show)}}</textarea>
                                                @else 
                                                <input class="form-control" style="font-size:13px;" value="{{ implode(',',$resp_to_show) }}" readonly />
                                                @endif
                                            </td>
                                            <td class="td-class px-1">
                                                <input type="date" value="{{ $issue->correction_target_date }}" class="form-control" style="font-size:13px;" disabled />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        @if($issue->is_req_rc_analysis_management_review=="yes" && !$initialStageFlag)
                            <td colspan="2" class="p-0">
                                @if($issue->processstage!='Correction Proposed- RC Awaiting')
                                <table class="table table-bordered  table-vcenter" style="width:auto;margin-bottom: 0 !important;">
                                    <thead class="bg-white">
                                    <tr>
                                        <th class="text-center titleheading">S #</th>
                                        <th class="text-center titleheading">Root Cause</th>
                                        <th class="text-center titleheading">Corrective Action</th>
                                        <th class="text-center titleheading">Department</th>
                                        <th class="text-center titleheading">Responsibility</th>
                                        <th class="text-center titleheading">Target Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rootcause as $item)
                                        <tr>
                                            @php
                                                $dept = isset($department[$count - 1]) ? App\Departments::where('id', $department[$count - 1])->value('departmentname') : '';
                                                $resp = isset($responsibility[$count - 1]) ? App\User::where('email', $responsibility[$count - 1])->value('name') : '';
                                            @endphp
                                            <td class="td-class">{{ $count }}</td>
                                            <td class="td-class"><textarea class="form-control" cols="30" rows="5" style="font-size:13px;" readonly>{{ isset($rootcause[$count - 1]) ? $rootcause[$count - 1] : '' }}</textarea></td>
                                            <td class="td-class"><textarea class="form-control" cols="30" rows="5" style="font-size:13px;" readonly>{{ isset($correctiveaction[$count - 1] ) ? $correctiveaction[$count - 1] : ''}}</textarea></td>
                                            <td class="td-class">
                                                <input class="form-control" style="font-size:13px;" value="{{ $dept }}" readonly />
                                            </td>
                                            <td class="td-class">
                                                <input class="form-control" style="font-size:13px;" value="{{ $resp }}" readonly />
                                            </td>
                                            <td class="td-class">
                                                <input type="date" class="js-datepicker form-control" style="font-size:13px;" value="{{ isset($target_date[$count - 1]) ? $target_date[$count - 1] : ''}}" disabled />
                                            </td>
                                        </tr>
                                        @php
                                            $count++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </td>
                        @endif
                    </tr>
                    {{-- <table class="table table-striped table-bordered" style="margin-bottom: 0 !important;"> --}}
                    @if($issue->financial_loss)
                        <tr>
                            <th>Financial Loss</th>
                            <td>{{ $issue->financial_loss }}</td>
                        </tr>
                    @endif
                    @if($issue->potential_cost_savings)
                        <tr>
                            <th>Potential Cost Saving</th>
                            <td>{{ $issue->potential_cost_savings }}</td>
                        </tr>
                    @endif
                    @if($issue->frequency)
                        <tr>
                            <th>Email Reminder for Actions</th>
                            <td>{{ $issue->frequency }}</td>
                        </tr>
                    @endif
                    @if($issue->notification)
                        <tr>
                            <th>Email Notification</th>
                            <td style="word-break: break-word; white-space: normal;">
                                @php
                                    $notificationUsers = array_filter(explode(',', $issue->notification));
                                    $usersByEmail      = $users->keyBy('email');
                                @endphp
                                @foreach($notificationUsers as $userEmail)
                                    <div>
                                        <span class="tooltipClass" data-toggle="tooltip" title="{{ $userEmail }}" data-placement="top">
                                            {{ optional($usersByEmail->get($userEmail))->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                    @if(count($issueFiles) > 0 )
                        <tr>
                            <th>Attachments (issues)</th>
                            <td>
                                <div class="col-sm-8">
                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachments</strong> </button>
                                    <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg modal-second" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments (issues)</h3>
                                                </div>
                                                <div class="alert alert-success d-none w-100" id="success_issue" style="font-size:12px;">
                                                    
                                                </div>

                                                <ul class="alert alert-danger d-none w-100" id="error_issue" style="font-size:12px;">
                                                    Internal server error, we are trying to fix them soon
                                                </ul>
                                                <div class="modal-body">
                                                    <table id="issueAttachmentTable" class="table table-responsive table-hover table-bordered table-striped p-5" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                        <thead>
                                                        <tr class="table-secondary">
                                                            <th style="width: 5%">S.No</th>
                                                            <th style="width:65%;">Attachments</th>
                                                            <th style="width: 30%">Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        
                                                        @foreach($issueFiles as $value1)
                                                            @if($value1 !="NULL" && $value1 != "")
                                                                <tr>
                                                                    <td style="font-size:10px; text-align: center">{{ $loop->iteration }}</td>
                                                                    <td style="font-size:12px;">{{ $value1}}</td>
                                                                    <td>
                                                                        <a style="font-size:10px;" class="btn btn-success btn-sm"
                                                                            href="{{asset("storage/issue_files/".$value1)}}"
                                                                            download="{{ $value1 }}">Download</a>
                                                                        @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $issue->postedby==Auth::user()->employeeid)
                                                                        <button class="btn btn-secondary btn-sm remove-issues" data-filename="{{ $value1 }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                
                                                            @endif
                                                        @endforeach
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endif
                    @if(count($actionFiles) > 0 )
                        <tr>
                            <th>Attachments (action)</th>
                            <td>
                                <div class="col-sm-8">
                                    <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal5{{$issue->id}}"><strong>View Attachments</strong> </button>
                                    <div id="viewModal5{{$issue->id}}" class="modal fadebd-example-modal-lg modal-second" tabindex="-1" role="dialog" aria-hidden="false">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Attachments (action)</h3>
                                                </div>
                                                <div class="alert alert-success d-none w-100" id="success_action" style="font-size:12px;">
                                                    
                                                </div>

                                                <ul class="alert alert-danger d-none w-100" id="error_action" style="font-size:12px;">
                                                    Internal server error, we are trying to fix them soon
                                                </ul>
                                                <div class="modal-body">
                                                    <table id="actionAttachmentTable" class="table table-responsive table-hover table-bordered table-striped p-5" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                                    <thead>
                                                        <tr class="table-secondary">
                                                            <th style="width: 5%">S.No</th>
                                                            <th style="width: 65%;">Attachments</th>
                                                            <th style="width: 30%">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($actionFiles as $value2)
                                                            @if($value2 !="NULL" && $value2 != "")
                                                                <tr>
                                                                    <td style="font-size:10px; text-align: center">{{ $loop->iteration }}</td>
                                                                    <td style="font-size:12px;">{{ $value2 }}</td>
                                                                    <td><a style="font-size:10px;" class="btn btn-success"
                                                                            href="{{asset("storage/issue_files/".$value2)}}"
                                                                            download="{{ $value2 }}">Download</a>
                                                                        @if(Gate::allows('delete-corrective') || Gate::allows('delete-all') || in_array(Auth::user()->email,$issue_reviewers) || $issue->postedby==Auth::user()->employeeid)
                                                                        <button class="btn btn-secondary btn-sm remove-actions" data-filename="{{ $value2 }}" style="font-size:10px;" title="Delete">Remove</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @endif
                    @if($issue->disapproval_reason)
                    <tr>
                        <th>Entry Review Remarks</th>
                        <td><?php echo nl2br($issue->disapproval_reason)?></td>
                    </tr>
                    @endif
                    @if($issue->action_remarks)
                    <tr>
                        <th>Action Review Remarks</th>
                        <td><?php echo nl2br($issue->action_remarks)?></td>
                    </tr>
                    @endif
                    @if($issue->remarks)
                    <tr>
                        <th>Closure Review Remarks</th>
                        <td><?php echo nl2br($issue->remarks)?></td>
                    </tr>
                    @endif
                    @if($issue->verification_remarks)
                    <tr>
                        <th>Verification Remarks</th>
                        <td><?php echo nl2br($issue->verification_remarks)?></td>
                    </tr>
                    @endif
                    <tr>
                        <th style="width:222px">Posted by</th>
                        <td>
                            <span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{$issue->postedByCorrective ? $issue->postedByCorrective->email : ''}}">
                                {{ $issue->postedByCorrective ? $issue->postedByCorrective->name : ''}} 
                            </span>
                            {{ $issue->postdate ? date('- d-m-Y - h:i:s A',strtotime($issue->postdate)) : '' }}
                        </td>
                    </tr>
                    @if($issue->teamLead)
                    <tr>
                        <th>Team leader</th>
                        <td>
                            <span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $issue->teamLead->email}}">
                                {{ $issue->teamLead->name}}
                            </span>
                        </td>
                    </tr>
                    @endif
                    @php
                        $team_mem_ids = DB::table('corrective_investigators')->where('corrective_id',$issue->id)->pluck('user_id')->toArray();
                    @endphp
                    @if(count($team_mem_ids)>0)
                    <tr>
                        <th>Team Members</th>
                        <td style="word-break: break-word; white-space: normal;">
                            @php
                                $usersByEmail      = $users->keyBy('id');
                            @endphp
                            @foreach($team_mem_ids as $id)
                                <div>
                                    <span class="tooltipClass" data-toggle="tooltip" title="{{ optional($usersByEmail->get($id))->email }}" data-placement="top">
                                        {{ optional($usersByEmail->get($id))->name }}
                                    </span>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @if($issue->investigation_date)
                    <tr>
                        <th>Target date for investigation</th>
                        <td>{{$issue->investigation_date ? date('d-m-Y', strtotime($issue->investigation_date)) : ''}}</td>
                    </tr>
                    @endif
                    @if($issue->entryReviewedByCorrective)
                    <tr>
                        <th>Entry Reviewed By</th>
                        <td>
                            <span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $issue->entryReviewedByCorrective ? $issue->entryReviewedByCorrective->email : ''}}">
                                {{ $issue->entryReviewedByCorrective ? $issue->entryReviewedByCorrective->name : ''}}
                            </span>
                            @php
                                $reviewedDate = $issue->entry_reviewed_by_date;
                                if ($reviewedDate) {
                                    $timestamp  = strtotime($reviewedDate);
                                    $format     = date('H:i:s', $timestamp) === '00:00:00' ? '- d-m-Y' : '- d-m-Y - h:i:s A';
                                    $reviewedDate = date($format, $timestamp);
                                }
                            @endphp
                            {{ $reviewedDate ?? '' }}
                        </td>
                    </tr>
                    @endif
                    @if($issue->actionReviewedByCorrective)
                    <tr>
                        <th>Action Reviewed By</th>
                        <td>
                            <span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->email : ''}}">
                                    {{ $issue->actionReviewedByCorrective ? $issue->actionReviewedByCorrective->name : ''}} 
                            </span>
                            @php
                                $reviewedDate = $issue->action_reviewed_at;
                                if ($reviewedDate) {
                                    $timestamp  = strtotime($reviewedDate);
                                    $format     = date('H:i:s', $timestamp) === '00:00:00' ? '- d-m-Y' : '- d-m-Y - h:i:s A';
                                    $reviewedDate = date($format, $timestamp);
                                }
                            @endphp
                            {{ $reviewedDate ?? '' }}
                        </td>
                    </tr>
                    @endif
                    @if($issue->closedByCorrective)
                    <tr>
                        <th>Closure Review By</th>
                        <td>
                            <span class="tooltipClass" data-toggle="tooltip" data-placement="top" title="{{ $issue->closedByCorrective ? $issue->closedByCorrective->email : ''}}">
                                {{ $issue->closedByCorrective ? $issue->closedByCorrective->name : ''}} 
                            </span>
                            @php
                                $reviewedDate =$issue->closed;
                                if ($reviewedDate) {
                                    $timestamp  = strtotime($reviewedDate);
                                    $format     = date('H:i:s', $timestamp) === '00:00:00' ? '- d-m-Y' : '- d-m-Y - h:i:s A';
                                    $reviewedDate = date($format, $timestamp);
                                }
                            @endphp
                            {{ $reviewedDate ?? '' }}
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.tooltipClass').tooltip();
        const recordId = "{{$issue->id}}";
        $('.remove-issues').on('click', function () {
            var filename = $(this).data('filename');
            var btnId = this;
            $.ajax({
                url : window.location.protocol + "//" + window.location.host +`/user/corrective/${recordId}/delete-issue`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    filename : filename,
                },
                success: function (data) {
                    $(btnId).closest('tr').remove();                    
                    $('#issueAttachmentTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        $(this).find('td:first').css('font-size', '10px');
                    });
                    $('#error_issue').addClass('d-none');
                    $("#success_issue")
                        .stop(true, true)
                        .text(`Issue file name: ${filename} has been removed successfully!`)
                        .removeClass('d-none')
                        .fadeIn('slow')
                        .delay(3000)
                        .fadeOut('slow', function() {
                            $(this).addClass('d-none').text(''); 
                        });
                },
                error: function (response) {
                    $('#error_issue').stop(true, true).removeClass('d-none');
                }
            });
        });
        $('.remove-actions').on('click', function () {
            var filename = $(this).data('filename');
            var btnId = this;
            $.ajax({
                url : window.location.protocol + "//" + window.location.host +`/user/corrective/${recordId}/delete-action`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : {
                    filename : filename,
                },
                success: function (data) {
                    $(btnId).closest('tr').remove();
                    $('#actionAttachmentTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                        $(this).find('td:first').css('font-size', '10px');
                    });
                    $('#error_action').addClass('d-none');
                    $("#success_action")
                        .stop(true, true)
                        .text(`Action file name: ${filename} has been removed successfully!`)
                        .removeClass('d-none')
                        .fadeIn('slow')
                        .delay(3000)
                        .fadeOut('slow', function() {
                            $(this).addClass('d-none').text(''); 
                        });
                },
                error: function (error) {
                    $('#error_action').stop(true, true).removeClass('d-none');
                }
            });
        });
    });
</script>