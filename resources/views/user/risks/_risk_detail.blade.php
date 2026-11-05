<div id="viewModal{{$value->Rid}}" class="modal fade risk-quick-view" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="risk-title-{{$value->Rid}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div><span class="risk-detail-eyebrow">RISK DETAILS · {{ $value->grp_id }}</span><h2 class="modal-title" id="risk-title-{{$value->Rid}}">{{ $value->riskname }}</h2></div><button type="button" class="close" data-dismiss="modal" aria-label="Close risk details"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
@include('user.risks.review-assist', ['reviewRisk' => $value])
<table class="table risk-detail-facts">
                <tr>
                    <th style="width:200px">Risk id</th>
                    <td>{{ $value->grp_id }}</td>
                </tr>
                <tr>
                    <th>Risk group</th>
                    <td>{{ $value->riskgroup }}</td>
                </tr>
                <tr>
                    <th>Risk area</th>
                    <td>{{ $value->department ? $value->department->departmentname : ''}}{{ $value->division ? ', '. $value->division->name : ''}}{{ $value->area ? ', '.$value->area->name : ''}}
                    </td>
                </tr>
                <tr>
                    <th>Risk name</th>
                    <td>{{ $value->riskname  }}</td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td style="word-break: break-all">{{ $value->riskdec }}</td>
                </tr>
                <tr>
                    <th>Risk drivers</th>
                    <td style="word-break: break-all">{{ $value->riskdri }}</td>
                </tr>

                <tr>
                    <th>Risk source</th>
                    <td>{{$value->riskscr }}</td>
                </tr>

                <tr>
                    <th>Disapproved reason</th>
                    <td class="text-danger">{{$value->disapprovereason }}</td>
                </tr>
                <tr>
                    <th>Last time escalated</th>
                    <td>{{ $value->last_escalated_time }}</td>
                </tr>

                <tr>
                    <th>Number of escalation time</th>
                    <td>{{ $value->number_of_escalated_times }}</td>
                </tr>
                <tr>
                    <th>Impact</th>
                    @if($value->impa =="High")
                        <td>{{$value->impa }}</td>
                    @elseif($value->impa =="Moderate")
                        <td>{{$value->impa }}</td>
                    @else
                        <td>{{$value->impa }}</td>
                    @endif
                </tr>

                <tr>
                    <th>Likelihood</th>
                    @if($value->like=="High")
                        <td>{{$value->like }}</td>
                    @elseif($value->like=="Moderate")
                        <td>{{$value->like }}</td>
                    @else
                        <td>{{$value->like }}</td>
                    @endif
                </tr>

                <tr>
                    <th>Risk</th>
                    <td>
                        @if($value->rank=="High")
                            <span class="status status-red">{{$value->rank }}</span>
                        @elseif($value->rank=="Moderate")
                            <span class="status status-warning">{{$value->rank }}</span>
                        @else
                            <span class="status status-success">{{$value->rank }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Existing controls</th>
                    <td>{{$value->existing_controls }}</td>
                </tr>
                <tr>
                    <th>Risk response</th>
                    <td>{{$value->risk_response }}</td>
                </tr>

                <tr>
                    <th>Opportunity ID</th>
                    <td>
                        @foreach($value->opportunities as $oppdata)
                            <span class="badge badge-success">{{$oppdata->dep_id}}</span>
                        @endforeach
                    </td>
                </tr>


                <tr>
                    <th>Process stage</th>
                    <td>
                        @if($value->action_status=='Active Risk')
                            <span class="status status-success">{{$value->action_status}}</span>
                        @elseif($value->action_status=='Action under review')
                            <span class="status status-secondary">{{$value->action_status}}</span>
                        @elseif($value->action_status=='Completed')
                            <span class="status status-blue">{{$value->action_status}}</span>
                        @elseif($value->action_status=='Rejected')
                            <span class="status status-red">{{$value->action_status}}</span>
                        @elseif($value->action_status=='Under review')
                            <span class="status status-warning">{{$value->action_status}}</span>
                        @endif
                    </td>
                </tr>

                @if($value->action_status=='Active Risk')

                    <tr>
                        <th>Mitigation action status</th>
                        <td>
                            @foreach($value->mitigations as $mitigation)
                                <div class="mt-2 w-25">
                                    @include('user.risks.mitigation._status')
                                    <a href="{{ route('mitigations.index', ['riskId' => $mitigation->risk_id]) }}">
                                        <span class="badge badge-info">{{ $mitigation->action_id }}</span>
                                    </a>
                                </div>
                                <hr>
                            @endforeach
                        </td>
                    </tr>

                @endif
                                <tr>
                    <th>Risk owner</th>
                    <td>
                        @foreach($riskowners as $riskowner)
                            @if(isset($riskOwnerData[$riskowner]))
                                @php
                                    $riskOwnerItem = $riskOwnerData[$riskowner];
                                @endphp
                                {{ $riskOwnerItem['name'] }}-{{ $riskOwnerItem['employeeid'] }} <br />
                            @endif
                        @endforeach
                    </td>
                </tr>
                @if ($value->riskreviewer)
                    <tr>
                        <th>Risk Reviewer</th>
                        <td>
                            {{ $value->riskreviewer->employeeid}} {{$value->riskreviewer->name}}
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>Added by and date & time</th>
                    @if($value->postedby=='')
                        <td>admin</td>
                    @else
                        <td>{{$value->postedby}}
                            &nbsp; {{ $value->postedByRisk ? $value->postedByRisk->name : ''}} <br>
                            {{$value->date }}
                        </td>
                    @endif
                </tr>
                <tr>
                    <th>Last Reviewed by and Date & time</th>
                    <td>{{$value->reviewed_by}}
                        &nbsp; {{ $value->reviewedByRisk ? $value->reviewedByRisk->name : ''}} <br>
                        @if($value->periodic_review_date)
                            @php
                                $periodicDate = \Carbon\Carbon::parse($value->periodic_review_date);
                            @endphp
                            @if($periodicDate->format('H:i:s') == '00:00:00')
                                {{ $periodicDate->format('d-m-Y') }}
                            @else
                                {{ $periodicDate->format('d-m-Y h:i:s A') }}
                            @endif
                        @else
                            {{''}}
                        @endif

                    </td>
                </tr>
                <tr>
                    <th>Initial Reviewed by and Date & time</th>
                    <td>{{$value->approved_by}}
                        &nbsp; {{ $value->approvedByRisk ? $value->approvedByRisk->name : ''}} <br>
                        {{$value->review_date }}
                    </td>
                </tr>
            </table></div>
            <div class="modal-footer"><a href="{{ route('risk.detail', $value->Rid) }}" class="btn btn-primary">Open full record <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></a>
                <button type="button" class="btn btn-primary" style="font-size:11px;" data-dismiss="modal">Close
                </button>
                @if(Gate::allows('escalate-risk-button-access'))
                    <a class="btn btn-success" style="font-size:11px;"
                        href="{{url('user/risks/notification?val='.$rr)}}"
                        onclick="return confirm('Are Sure to Escalate The Risk')">Escalate The Risk</a>
                @endif
            </div>
        </div>
    </div>
</div>
