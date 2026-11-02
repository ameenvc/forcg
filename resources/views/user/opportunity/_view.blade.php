<div id="viewModal{{$value->id}}" class="modal fadebd-example-modal-lg scrollable2" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            @php
                $dept = DB::table('departments')->where('id',$value->department)->value('departmentname');
            @endphp
            <table class="table table-striped">
                <tr>
                    <th style="width:70px">Opportunity id</th>
                    <td>{{ $value->dep_id }}</td>
                </tr>
                <tr>
                    <th>Responsible Department</th>
                    <td class="text-primary">{{ $dept }}</td>
                </tr>
                <tr>
                    <th>Division</th>
                    <td class="division-gray">{{ $value->division ? $value->division->name : '' }}</td>
                </tr>
                <tr>
                    <th>Subdivision</th>
                    <td class="subdivision-brown">{{ $value->area ? $value->area->name : '' }}</td>
                </tr>
                <tr>
                    <th>Opportunity name</th>
                    <td>{{ $value->oppname }}</td>
                </tr>

                <tr>
                    <th>Opportunity description</th>
                    <td><?php echo nl2br( $value->oppdes)?></td>
                </tr>
                <tr>
                    <th>Disapproved reason</th>
                    <td class="text-danger">{{ $value->reason }}</td>
                </tr>

                <tr>
                    <th>Target date</th>
                    <td>{{ $value->tardate }}</td>
                </tr>

                <tr>
                    <th>Reviewed at</th>
                    <td>{{ $value->reviewdate }}</td>
                </tr>

                <tr>
                    <th>Action</th>
                    <td><?php echo nl2br($value->action)?></td>
                </tr>
                <tr>
                    <th>Attachments</th>
                    <td>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $value->id }}" data-target="#viewModal4{{$value->id}}"><strong>View Attachments</strong> </button>
                            <div id="viewModal4{{$value->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Attachments</h3>
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
                                                <?php $sno=0; ?>
                                                @if($value->file!='NULL' && $value->file!='')
                                                @php
                                                    $files=explode(",",$value->file);
                                                @endphp
                                                <tbody>
                                                    @foreach($files as $file)
                                                    <tr>
                                                        <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                        <td style="font-size:15px;width: 100%;">{{$file}}</td>
                                                        <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/opportunity/".$file)}}" download="{{$file}}">Download</a></td>
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


                <tr>
                    <th>Likelihood</th>
                    <td>
                        @if($value->likelihood=="Low")
                        <span class="status" style="font-size: 10px; background-color: #e0edcf; color: #176044;">{{$value->likelihood }}</span>
                        @elseif($value->likelihood=="Moderate")
                        <span class="status" style="font-size: 10px; background-color: #ffebcc; color: #cc8800;">{{$value->likelihood }}</span>
                        @else
                        <span class="status" style="font-size: 10px;background-color:#f8d4d4;color:#af1e1e;">{{$value->likelihood }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Benefit</th>
                    <td>
                        @if($value->benefit=="Low")
                        <span class="status" style="font-size: 10px; background-color: #e0edcf; color: #176044;">{{$value->benefit }}</span>
                        @elseif($value->benefit=="Moderate")
                        <span class="status" style="font-size: 10px; background-color: #ffebcc; color: #cc8800;">{{$value->benefit }}</span>
                        @else
                        <span class="status" style="font-size: 10px;background-color:#f8d4d4;color:#af1e1e;">{{$value->benefit }}</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Positive impact</th>
                    <td>
                        @if($value->impact=="Low")
                        <span class="status" style="font-size: 10px; background-color: #e0edcf; color: #176044;">{{$value->impact }}</span>
                        @elseif($value->impact=="Moderate")
                        <span class="status" style="font-size: 10px; background-color: #ffebcc; color: #cc8800;">{{$value->impact }}</span>
                        @else
                        <span class="status" style="font-size: 10px;background-color:#f8d4d4;color:#af1e1e;">{{$value->impact }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Responsible Person</th>
                    <td>
                        {{ $value->byresponsibility ? $value->byresponsibility->name : ''}}-{{ $value->byresponsibility ? $value->byresponsibility->employeeid : ''}}
                    </td>
                </tr>


                <tr>
                    <th>Process stage</th>
                    <td>
                        @if($value->status=='Approved')
                        <span class="status status-success" style="font-size: 10px;">{{$value->status}}</span>
                        @elseif($value->status=='Completed')
                        <span class="status status-blue" style="font-size:10px;">{{$value->status}}</span>
                        @elseif($value->status=='Disapproved')
                        <span class="status status-red" style="font-size:10px;">{{$value->status}}</span>
                        @elseif($value->status=='Review Pending')
                        <span class="status" style="font-size: 10px;background-color: #ffebcc; color: #cc8800;">{{$value->status}}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Action status</th>
                    <td>
                        @if($value->actionstatus)
                        @php
                            if($value->actionstatus=='Not Started-0%'){
                                $bgcolor_0='#D32F2F';
                                $bgcolor_25=$bgcolor_50=$bgcolor_100='';
                                $color_0='#000';
                                $color_25=$color_50=$color_100='#5179d6';
                            }elseif($value->actionstatus=='Progress-25%'){
                                $bgcolor_0 = $bgcolor_25='#F57C00';
                                $bgcolor_50=$bgcolor_100='';
                                $color_25=$color_0='#000';
                                $color_50=$color_100='#5179d6';
                            }elseif($value->actionstatus=='Partially Completed-50%'){
                                $bgcolor_0 = $bgcolor_25=$bgcolor_50='#FFC107';
                                $bgcolor_100='';
                                $color_0 = $color_25=$color_50='#000';
                                $color_100='#5179d6';
                            }elseif($value->actionstatus=='Completed-100%'){
                                $bgcolor_0 = $bgcolor_25=$bgcolor_50=$bgcolor_100='#4CAF50';
                                $color_0 = $color_25=$color_50=$color_100='#000';
                            }
                        @endphp
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-sm btn-outline-primary disabled" id="btn-not-started" style="font-size: 10px;background-color:{{$bgcolor_0}};color:{{$color_0}};" aria-disabled="true">
                                Not started-0%
                            </label>
                            <label class="btn btn-sm btn-outline-primary disabled" id="btn-progress" style="font-size: 10px;background-color:{{$bgcolor_25}};color:{{$color_25}};" aria-disabled="true">
                                Progress-25%
                            </label>
                            <label class="btn btn-sm btn-outline-primary disabled" id="btn-partially-completed" style="font-size: 10px;background-color:{{$bgcolor_50}};color:{{$color_50}};" aria-disabled="true">
                                Partially completed-50%
                            </label>
                            <label class="btn btn-sm btn-outline-primary disabled" id="btn-completed" style="font-size: 10px;background-color:{{$bgcolor_100}};color:{{$color_100}};" aria-disabled="true">
                               Completed-100%
                            </label>
                        </div>
                        @else
                        {{''}}
                        @endif
                    </td>
                </tr>

                @if($value->ndareason)
                <tr>
                    <th>Closure remarks</th>
                    <td><?php echo nl2br( $value->ndareason )?></td>
                </tr>
                @endif

                <tr>
                    <th>Added by</th>
                    @if($value->postedby=='')
                    <td>admin</td>
                    @else
                    <td>{{$value->postedby}} &nbsp; {{ $value->postedByOpportunity ? $value->postedByOpportunity->name : ''}}</td>
                    @endif
                </tr>
                @if ($value->opportunityapprover)
                    <tr>
                        <th>Approver</th>
                        <td>{{$value->opportunityapprover->employeeid}} &nbsp; {{ $value->opportunityapprover->name}}</td>
                    </tr>
                @endif
                @if ($value->closurereviewer)
                    <tr>
                        <th>Closure Reviewer</th>
                        <td>{{$value->closurereviewer->employeeid}} &nbsp; {{ $value->closurereviewer->name}}</td>
                    </tr>
                @endif
                @if ($value->approved_at && $value->opportunityapprover)
                    <tr>
                        <th>Approved by and Date & time</th>
                        <td>
                            {{$value->opportunityapprover->employeeid}} &nbsp; {{ $value->opportunityapprover->name}} <br>
                            {{ $value->approved_at }}
                        </td>
                    </tr>
                @endif
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" style="font-size:10px;" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
