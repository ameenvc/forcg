@php
    if($issue->status=="Review Pending")
        $class="badge badge-info";
    elseif($issue->status=="Reviewed")
        $class="badge badge-success";
    elseif($issue->status=="Approved")
        $class="badge badge-primary";
    elseif($issue->status=="Rejected")
        $class="badge badge-danger";
    elseif($issue->status=="Partially Completed")
        $class="badge badge-warning";
    elseif($issue->status=="Not Started")
        $class="badge badge-warning";
    elseif($issue->status=="Completed")
        $class="badge badge-info";
    elseif($issue->status=="Closed")
        $class="badge badge-success";
    else
        $class="badge badge-warning";
@endphp
<div id="viewModal{{$issue->id}}" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            <table class="table table-striped">
                <tr>
                    <th class="text-left">CR#</th>
                    <td class="text-left" style="width:80%;">{{$issue->crid}}</td>
                </tr>
                <tr>
                    <th class="text-left">Title</th>
                    <td class="text-left" style="width:80%;">{{$issue->title}}</td>
                </tr>

                <tr>
                    <th class="text-left">Category</th>
                    <td class="text-left" style="width:80%;">
                        @php 
                            if($issue->category){
                                $catIds = $issue->category ? explode(',',$issue->category) : [];
                                $names = $changeCategories->whereIn('id',$catIds)->pluck('category')->toArray();
                                $namesString = implode(',',$names);
                            }else{
                                $namesString = '';
                            }
                        @endphp
                        {{$namesString}}
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Scope</th>
                    <td class="text-left" style="width:80%;"><?php echo nl2br($issue->scope )?></td>
                </tr>

                <tr>
                    <th class="text-left">Reason</th>
                    <td class="text-left" style="width:80%;"><?php echo nl2br($issue->reason )?></td>
                </tr>

                <tr>
                    <th class="text-left">Benefits</th>
                    <td class="text-left" style="width:80%;"><?php echo nl2br($issue->benefits )?></td>
                </tr>
                <tr>
                    <th class="text-left">Impacts</th>
                    <td class="text-left" style="width:80%;"><?php echo nl2br($issue->impacts )?></td>
                </tr>
                <tr>
                    <th class="text-left">Risk Identified</th>
                    <td class="text-left" style="width:80%;"><?php echo nl2br($issue->risk_identified )?></td>
                </tr>

                <tr>
                    <th class="text-left">Initiator</th>
                    <td class="text-left" style="width:80%;">
                        @if($issue->initiatorBy)
                            {{$issue->initiatorBy->name}}
                        @else
                            {{$issue->initiator}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Department</th>
                    <td class="text-left" style="width:80%;">
                        @if($issue->departmentDetails)
                            {{$issue->departmentDetails->departmentname}}
                        @else
                            {{$issue->department}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Changing Product/Service</th>
                    <td class="text-left" style="width:80%;"><?php echo nl2br($issue->changing_product)?></td>
                </tr>
                <tr>
                    <th class="text-left">Priority</th>
                    <td class="text-left" style="width:80%;">
                        @if($issue->priorityDetails)
                            {{$issue->priorityDetails->name}}
                        @else
                            {{$issue->priority}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Status</th>
                    <td class="text-left" style="width:80%;"><span class="{{$class}}">{{$issue->status }}</span></td>
                </tr>
                <tr>
                    <th class="text-left">Health and Safety Risk ID</th>
                    <td class="text-left" style="width:80%;">
                        @foreach($ohs as $ohsr)
                        @if($issue->id == $ohsr->context_id)
                            <span class="badge badge-danger">{{$ohsr->actid}}/{{$ohsr->riskid}}</span>
                        @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Environmental Aspect ID</th>
                    <td class="text-left" style="width:80%;">
                        @foreach($envaspt as $env)
                            @if($issue->id==$env->context_id)
                            <?php
                                $dept = is_numeric($env->department) ? $departments[$env->department] :$env->department;
                                $dep = strtoupper(substr($dept,0,3));
                            ?>
                            <span class="badge badge-success">{{$dep}}{{$env->count}}/{{$env->aspect_id}}</span>
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Review Remarks</th>
                    <td class="text-left" style="width:80%;">{{$issue->remarks }}</td>
                </tr>
                <tr>
                    <th class="text-left">Approve Remarks</th>
                    <td class="text-left" style="width:80%;">{{$issue->higherremarks }}</td>
                </tr>
                <tr>
                    <th class="text-left">Posted By</th>
                    <td class="text-left" style="width:80%;">
                        @if($issue->postedByDetails)
                            {{$issue->postedByDetails->name}}
                        @else
                            {{$issue->postedby }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Attachment</th>
                    <td>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachment</strong> </button>
                            <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachment</h3>
                                        </div>

                                        @if($issue->file != null)

                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                            <div class="row justify-content-center">
                                                <thead>
                                                    <tr class="table-secondary">
                                                        <th>S.No</th>
                                                        <th>Attachment</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td style="font-size:10px;">1</td>
                                                        <td style="font-size:15px;width: 100%;">{{ $issue->file}}</td>
                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/context_files/".$issue->file)}}" download="{{$issue->file}}">Download</a></td>
                                                    </tr>
                                                </tbody>

                                            </div>
                                        </table>
                                            @else
                                            <div class="row justify-content-center">
                                                <h2>No File</h2>
                                            </div>


                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>

                </tr>
                <tr>
                    <th class="text-left">Reviewal Authority </th>
                    <td class="text-left" style="width:80%;">
                        @if($issue->reviewer)
                            {{$issue->reviewer->name}}
                        @else
                            {{$issue->rev_authority }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Approval Authority </th>
                    <td class="text-left" style="width:80%;">
                        @if($issue->approver)
                            {{$issue->approver->name}}
                        @else
                            {{$issue->appr_authority }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-left">Description</th>
                    <td class="text-left" style="width:80%;">{{$issue->description}}</td>
                </tr>
                <tr>
                    <th class="text-left">Added Date</th>
                    <td class="text-left" style="width:80%;">{{date('M/d/Y',strtotime($issue->created_at))}}</td>
                </tr>
                <tr>
                    <th class="text-left">Reviewed Date</th>
                    <!--<td class="text-left" style="width:80%;">{{date('M/d/Y',strtotime($issue->reviewed_at))}}</td>-->
                    <td class="text-left" style="width:80%;">{{$issue->reviewed_at==null?null:date('M/d/Y',strtotime($issue->reviewed_at))}}</td>
                </tr>

            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>