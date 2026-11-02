<div id="viewModal{{ $issue->id }}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                    <th style="width:70px">Issue id</th>
                    <td>{{$issue->issueid}}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{$issue->categoryName}}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{$issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department}}</td>
                </tr>

                <tr>
                    <th>Type</th>
                    <td class="d-none d-sm-table-cell">
                        @if($issue->type=="Low")
                            <span class="badge badge-success">{{$issue->type}}</span>
                        @elseif($issue->type=="Moderate")
                            <span class="badge badge-warning">{{$issue->type}}</span>
                        @elseif($issue->type=="High")
                            <span class="badge badge-danger">{{$issue->type}}</span>
                        @else
                            <span class="badge badge-primary">{{$issue->type}}</span>
                        @endif
                    </td>
                </tr>


                <tr>
                    <th>Title</th>
                    <td>{{$issue->title}}</td>
                </tr>

                <tr>
                    <th>Observation</th>
                    <td><?php echo nl2br($issue->observation )?></td>
                </tr>
                <tr>
                    <th>Responsibility</th>
                    <td>{{$issue->responsibility}}</td>
                </tr>

                <tr>
                    <th>Agreed action</th>
                    <td><?php echo nl2br($issue->agreedaction)?></td>
                </tr>

                <tr>
                    <th>Due date</th>
                    <td>{{date('M/d/Y',strtotime($issue->duedate))}}</td>
                </tr>
                <tr>
                    <th>Correction</th>
                    <td><?php echo nl2br($issue->correction )?></td>
                </tr>
                <tr>
                    <th>Root cause</th>
                    <td><?php echo nl2br($issue->rootcause )?></td>
                </tr>

                <tr>
                    <th>Corrective action</th>
                    <td><?php echo nl2br($issue->correctiveaction )?></td>
                </tr>
                <tr>
                    <th>Attachments (issues)</th>
                    <td>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal4{{$issue->id}}"><strong>View Attachments</strong> </button>
                            <div id="viewModal4{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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
                                                <?php $n=0; ?>

                                                @if( count($issuedocs) > 0 )
                                                    @foreach($issuedocs as $value1)
                                                        @if( $value1->id == $issue->id )
                                                            @if( $value1->issue_file!="NULL" )
                                                                <tbody>
                                                                <tr>
                                                                    <td style="font-size:10px; ;">{{ $n=$n+1}}</td>
                                                                    <td style="font-size:15px;width: 100%;">{{ $value1->issue_file}}</td>
                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value1->issue_file)}}" download="{{$value1->issue_file}}">Download</a></td>
                                                                </tr>
                                                                </tbody>
                                                            @endif
                                                        @endif
                                                    @endforeach
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
                    <th>Attachments (action)</th>
                    <td>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-secondary btn-small" style="font-size:13px;width: 150px;" data-toggle="modal" data-id="{{ $issue->id }}" data-target="#viewModal6{{$issue->id}}"><strong>View Attachments</strong> </button>
                            <div id="viewModal6{{$issue->id}}" class="modal fadebd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="false">
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

                                                @if( count($actiondocs) > 0 )
                                                    @foreach($actiondocs as $value2)
                                                        @if( $value2->id == $issue->id )
                                                            @if( $value2->action_file!="NULL" )
                                                                <tbody>
                                                                <tr>
                                                                    <td style="font-size:10px; ;">{{ $sno=$sno+1}}</td>
                                                                    <td style="font-size:15px;width: 100%;">{{ $value2->action_file}}</td>
                                                                    <td><a style="margin-left:70px;font-size:11px;width: 80px;" class="btn btn-success" href="{{asset("storage/issue_hse/".$value2->action_file)}}" download="{{$value2->action_file}}">Download</a></td>
                                                                </tr>
                                                                </tbody>
                                                            @endif
                                                        @endif
                                                    @endforeach
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
                    <th>Remarks</th>
                    <td><?php echo nl2br($issue->remarks)?></td>
                </tr>
                <tr>
                    <th>Action review remarks</th>
                    <td><?php echo nl2br($issue->action_remarks)?></td>
                </tr>
                <tr>
                    <th>Posted by</th>
                    <td>{{$issue->postedby}}&nbsp; {{ $issue->postedBy ? $issue->postedBy->name : ''}}</td>
                </tr>
                <tr>
                    <th>Added date</th>
                    <td>{{$issue->postdate}}</td>
                    <!-- <td>{{date('M/d/Y',strtotime($issue->postdate))}}</td>                                                            -->
                </tr>
                <tr>
                    <th>Last reviewed</th>
                    <td>{{$issue->last_reviewed_at}}</td>
                </tr>
                <tr>
                    <th>Approved by</th>
                    <td>{{$issue->approvedby}}&nbsp; {{ $issue->approvedByHse ? $issue->approvedByHse->name : ''}}</td>
                </tr>
                <tr>
                    <th>Issue closed by</th>
                    <td>{{$issue->closedby}}&nbsp; {{ $issue->closedByHse ? $issue->closedByHse->name : ''}}</td>
                </tr>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>