<div id="viewModal{{$mitigation->id}}"
     class="modal fadebd-example-modal-lg modal-detail-mitigation"
     tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Detailed View</h2>
            </div>
            <table class="table table-striped text-left">
                <tr>
                    <th style="width:200px">Action&nbspID</th>
                    <td>{{ $mitigation->action_id }}</td>
                </tr>
                <tr>
                    <th>Owner</th>
                    <td>{{ $mitigation->user ? $mitigation->user->name : '' }}</td>
                </tr>
                <tr>
                    <th>Profile Picture</th>
                    <td>
                        @if($mitigation->user)
                            <img src="{{ $mitigation->user->avatar_path }}"
                                 class="avatar"
                                 alt="{{ $mitigation->user->name }}">
                        @else
                            <img src="{{ asset('/images/avatar10.jpg') }}"
                                 class="avatar"
                                 alt="No-avatar">
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $mitigation->department ? $mitigation->department->departmentname : '-'}}</td>
                </tr>
                <tr>
                    <th>Action name</th>
                    <td>{{ $mitigation->action_name }}</td>
                </tr>
                <tr>
                    <th>Action description</th>
                    <td>{!! nl2br(strip_tags($mitigation->action_description)) !!}</td>
                </tr>
                <tr>
                    <th>Priority Level</th>
                    <td>
                        @if($mitigation->priority_level == 'high')
                            <span class="text-danger font-w700">High</span>
                        @elseif($mitigation->priority_level == 'medium')
                            <span class="text-warning font-w700">Medium</span>
                        @elseif($mitigation->priority_level == 'low')
                            <span class="text-success font-w700">Low</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Link other risks</th>
                    <td>
                        @if($mitigation->link_other_risk)
                            @php
                                $linkOtherRisk = json_decode($mitigation->link_other_risk, true);
                                $riskIds = array_keys($linkOtherRisk);
                                $nonDeletedRiskIds = App\Risk::withTrashed()->whereIn('Rid', $riskIds)->pluck('Rid')->toArray();
                            @endphp
                            @if(is_array($linkOtherRisk) || is_object($linkOtherRisk))
                                @foreach($linkOtherRisk as $riskId => $riskItem)
                                    @if(in_array($riskId, $nonDeletedRiskIds))
                                    <span class="badge badge-info">{{ $riskItem }}</span>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>DueDate</th>
                    <td>
                        {{ date("d/m/Y", strtotime($mitigation->due_date)) }}
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <div class="w-25">
                            @include('user.risks.mitigation._status')
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Resource required</th>
                    <td>
                        {{ $mitigation->resource_required }}
                    </td>
                </tr>

                <tr>
                    <th>Cost estimate</th>
                    <td>{{ $mitigation->cost_estimate }}</td>
                </tr>
                <tr>
                    <th>Attachments</th>
                    <td>
                        <button type="button"
                                class="btn btn-secondary btn-small"
                                style="font-size:13px;width: 250px;"
                                data-toggle="modal"
                                data-target="#viewModalAttachment{{ $mitigation->id }}">
                            <strong>View Attachments</strong>
                        </button>
                    </td>
                </tr>
            </table>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="font-size:11px;" data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>
@include('user.risks.mitigation._mitigation_attachment')
