<div id="viewModal{{$value->Oid}}" class="modal fadebd-example-modal-lg scrollable" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            <table class="table table-striped">
                <tr>
                    <th style="width:70px">Objective ID</th>
                    <td>
                        @if($value->status=="Rejected")
                        <span class="badge badge-danger">{{ $value->objective_id }}</span>
                        @elseif($value->status=="Review Pending")
                        <span class="badge badge-warning">{{ $value->objective_id }}</span>
                        @elseif($value->status=="Reviewed")
                        <span class="badge badge-primary">{{ $value->objective_id }}</span>
                        @else
                        <span class="badge badge-success">{{ $value->objective_id }}</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Objectives type</th>
                    <td>{{ $value->objectiveTypes->pluck('name')->implode(', ') }}</td>
                </tr>
                <tr>
                    <th>Objectives name</th>
                    <td>{{ $value->objectives }}</td>
                </tr>
                <tr>
                    <th>Objectives desription</th>
                    <td>{!! nl2br($value->description) !!}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $value->dep ? $value->dep->departmentname : '-' }}</td>
                </tr>
                <tr>
                    <th>Baseline</th>
                    <td>{{ $value->baseline }}</td>
                </tr>
                @if($value->division)
                <tr>
                    <th>Division</th>
                    <td>{{ $value->division ? $value->division->name : '' }}</td>
                </tr>
                @endif
                @if($value->area)
                <tr>
                    <th>Sub-Division</th>
                    <td>{{ $value->area ? $value->area->name : '' }}</td>
                </tr>
                @endif
                @if($value->unit=='No sign')
                <tr>
                    <th>Target</th>
                    <td>{{ $value->otarget }}</td>
                </tr>
                @else
                <tr>
                    <th>Target</th>
                    <td>{{ $value->otarget }}{{$value->unit}}</td>
                </tr>
                @endif
                <tr>
                    <th>Target Nature</th>
                    <td>{{ $value->target_nature }}</td>
                </tr>

                <tr>
                    <th>Span</th>
                    <td>{{ $value->span }}</td>
                </tr>
                <tr>
                    <th>Target Date</th>
                    <td>{{ $value->tardate }}</td>
                </tr>
                @php
                    $responsibleUsers = getUsersFromEmailsOrNames($value->responsibility);
                    $responsiblePersonsName = implode(', ', $responsibleUsers->pluck('name')->toArray());
                @endphp
                <tr>
                    <th>Responsibility</th>

                    <td>{{ $responsiblePersonsName ? $responsiblePersonsName: '--' }}</td>
                </tr>
                <tr>
                    <th>Period : From</th>
                    <td>{{ $value->fromdate }}</td>
                </tr>
                <tr>
                    <th>Period : To</th>
                    <td>{{ $value->todate }}</td>
                </tr>
                <tr>
                    <th>Methodology</th>
                    <td>{!! nl2br( $value->methodology ) !!}</td>
                </tr>
                <tr>
                    <th>Resources</th>
                    <td>{!! nl2br( $value->resources ) !!}</td>
                </tr>
                <tr>
                    <th>Frequency</th>
                    <td>{{ $value->frequency }}</td>
                </tr>

                <tr>
                    @php
                        $rev = explode(',',$value->reviewers);
                        $rev = DB::table('users')->whereIn('id', $rev)->pluck('name')->toArray();
                        $rev = implode(',',$rev);
                    @endphp

                    @if(!empty($value->reviewers))
                    <th>Reviewers</th>
                    <td>{{ $rev }}</td>
                    @endif
                </tr>
                <tr>
                    @if($value->approverDetails)
                    <th>Approver</th>
                    <td>{{$value->approverDetails->name}}</td>
                    @endif
                </tr>
                <tr>
                    @php
                        $reviewedPersonName = [];
                        if($value->status=="Reviewed" || $value->status=="Approved"){
                            if($value->new_reviewedby==null){
                                $oldReviewedBy = null;
                                if(DB::table('admins')->where('employeeid',$value->approved_by)->first()){
                                    $oldReviewedBy = DB::table('admins')->where('employeeid',$value->reviewed_by)->value('name');
                                }else{
                                    $oldReviewedBy = DB::table('users')->where('employeeid',$value->reviewed_by)->value('name');
                                }
                                if($oldReviewedBy){
                                    $reviewedPersonName[] = $oldReviewedBy;
                                }
                            }
                            else{
                                $newReviewedBy = explode(',',$value->new_reviewedby);
                                foreach($newReviewedBy as $rB){
                                    $reviewedEmployee = null;
                                    $reviewedEmployee = DB::table('users')->where('id', $rB)->value('name');
                                    if($reviewedEmployee){
                                        $reviewedPersonName[] = $reviewedEmployee;
                                    }
                                }
                            }
                        }else{
                            $newReviewedBy = explode(',',$value->new_reviewedby);
                            foreach($newReviewedBy as $rB){
                                $reviewedEmployee = null;
                                $reviewedEmployee = DB::table('users')->where('id', $rB)->value('name');
                                if($reviewedEmployee){
                                    $reviewedPersonName[] = $reviewedEmployee;
                                }
                            }
                        }
                        $reviewedAt = explode(',',$value->reviewed_at);
                        $reviewedByArray = [];
                        if($reviewedAt[0]==null)
                            $reviewedByArray = null;
                        else if (count($reviewedAt)==count($reviewedPersonName)) {
                            for($i=0;$i<count($reviewedPersonName);$i++){
                                $reviewedByArray[$reviewedPersonName[$i]] = $reviewedAt[$i];
                            }
                        }

                    @endphp
                    @if($reviewedByArray!=null)
                    <th>Reviewed By</th>
                    <td>
                        @foreach($reviewedByArray as $key=>$v)
                        {{ $key }} <span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($v))}})</span>,
                        @endforeach
                    </td>
                    @endif
                </tr>
                <tr>
                    <th>Reviewer remarks</th>
                    <td>{{ $value->remarks }}</td>
                </tr>
                <tr>
                    <th>Reviewer proposal</th>
                    <td class="text-danger">{{ $value->proposal }}</td>
                </tr>
                <tr>
                    <th>Approver remarks</th>
                    <td>{{ $value->higherremarks }}</td>
                </tr>
                <tr>
                    <th>Approver proposal</th>
                    <td>{{ $value->higherproposal }}</td>
                </tr>
                <tr>
                    <th>Process stage</th>
                    <td>
                        @if($value->status=="Rejected")
                        <span class="badge badge-danger">{{ $value->status }}</span>
                        @elseif($value->status=="Review Pending")
                        <span class="badge badge-warning">{{ $value->status }}</span>
                        @elseif($value->status=="Reviewed")
                        <span class="badge badge-primary">{{ $value->status }}</span>
                        @else
                        <span class="badge badge-success">{{ $value->status }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Prepared by</th>
                    @php
                        $pre_by = null;
                        if($value->postedby!=null && $value->new_postedby==null){
                            if(DB::table('admins')->where('employeeid',$value->postedby)->first())
                                $pre_by = DB::table('admins')->where('employeeid',$value->postedby)->value('name');
                            else
                                $pre_by = DB::table('users')->where('employeeid',$value->postedby)->value('name');
                        }else{
                            $pre_by = $value->creator ? $value->creator->name : '';
                        }
                    @endphp
                    <td>{{$pre_by}} <span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($value->created_at))}})</span></td>
                </tr>
                <tr>
                    <th>Added Date</th>
                    <td>{{ $value->created_at }}</td>
                </tr>
                <tr>
                    <th>Reviewed date</th>
                    @php
                        $ra = $reviewedAt ? array_pop($reviewedAt):'';
                    @endphp
                    <td>{{ $ra != null ? date('d-m-Y',strtotime($ra)) : '' }}</td>
                </tr>

                <tr>
                    <th>Last reviewed By</th>
                    @if($ra==NULL)
                    <td> </td>
                    @else
                    @php

                        $reviewedByCopy = new ArrayObject($reviewedPersonName);
                        $reviewedByCopy = $reviewedByCopy->getArrayCopy();
                        $re = $reviewedByCopy ? array_pop($reviewedByCopy):'';
                    @endphp
                        <td>{{ $re }} <span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($ra))}})</span> </td>
                    @endif
                </tr>
                <tr>
                    <th>Last Approved By</th>
                    @if($value->approved_at==NULL)
                    <td> </td>
                    @else
                    @php
                        $app_by = null;
                        if($value->approved_by!=null){
                            if(DB::table('admins')->where('employeeid',$value->approved_by)->first()){
                                $app_by = DB::table('admins')->where('employeeid',$value->approved_by)->value('name');
                            }else{
                                $app_by = DB::table('users')->where('employeeid',$value->approved_by)->value('name');
                            }
                        }else if($value->new_approvedby!=null){
                            $app_by = $value->approvedBy ? $value->approvedBy->name : '';
                        }
                    @endphp
                    <td>{{ $app_by }} <span style="font-size:11px;color:brown;">({{date('d-m-Y',strtotime($value->approved_at))}})</span></td>
                    @endif
                </tr>
                <tr>
                    <th>Attachments</th>
                    <td>
                        <div>
                            <button data-toggle="modal"  data-target="#viewModal5{{$value->Oid}}"  data-id="{{ $value->Oid }}" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;"><strong>View Attachment</strong></button>
                            <div id="viewModal5{{$value->Oid}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachment</h3>
                                            <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
                                        </div>
                                        <table class="table table-responsive table-hover table-bordered table-striped" style="font-size:100%;font-family: Arial, Helvetica, sans-serif">
                                            <div class="row justify-content-center">

                                                <?php $o=0; ?>
                                                @if($value->files!='')
                                                    <thead>
                                                        <tr class="table-secondary">
                                                            <th>S.No</th>
                                                            <th>Attachment</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $files = explode(',',$value->files); @endphp
                                                        @foreach($files as $f)
                                                        <tr>
                                                            <td style="font-size:10px; ;">{{ $o=$o+1}}</td>
                                                            <td style="font-size:15px;width: 100%;">{{preg_replace('/_\d+_[^.]+(\.[^.]+)$/', '$1', $f)}}</td>
                                                            <td>
                                                                <a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset('storage/objectives_files/'.trim($f))}}" download="{{trim($f)}}">Download</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                @else
                                                <div class="row justify-content-center">
                                                    <h2>No Files</h2>
                                                </div>
                                                @endif
                                            </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
