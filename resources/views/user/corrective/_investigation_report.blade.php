<div id="viewReportModal" class="modal fade"
        aria-labelledby="viewReportModalLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="font-size: 12px">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="p-1 mr-2" style="background: #2529453D; border-radius: 8px;">
                            <img src="{{ asset('images/icons/document_icon.png') }}" style="width: 18px; height: 18px">
                        </div>
                        <span class="font-weight-bold">Investigation Report</span>
                    </div>
                    <button type="button" class="close btn-close-modal-custom" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('images/icons/close_icon.png') }}" style="width: 24px; height: 24px">
                    </button>
                </div>
            </div>
            <div class="modal-body px-4">
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>Was this a one-time occurrence, or does it happen regularly?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <span>{{ $corrective->occurrence ? ($corrective->occurrence == 'one_time' ? 'One time' : 'Regularly') : '-' }}</span>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>What processes or systems are related to the problem?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <span>{{ $corrective->system_related_problems ? $corrective->system_related_problems : '-'  }}</span>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>Were there any recent changes or modifications?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <span>
                            @if($corrective->recent_changes === 1)
                            Yes
                            @elseif(!$corrective->recent_changes)
                            -
                            @else
                            No
                            @endif
                        </span>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>Did everyone follow established procedures?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <span>
                            @if($corrective->follow_procedures === 1)
                            Yes
                            @elseif(!$corrective->follow_procedures)
                            -
                            @else
                            No
                            @endif

                        </span>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>What are the contributing factors?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <div>
                            <span>
                                @if($corrective->contributing_factors)
                                    @php
                                        $contributingFactors = [];
                                        $values = [
                                            'human' => 'Human',
                                            'technical' => 'Technical',
                                            'organizational' => 'Organizational',
                                            'material' => 'Material',
                                            'machine' => 'Machine',
                                            'design' => 'Design',
                                            'method' => 'Method',
                                            'envi_aspects' => 'Environmental aspects',
                                            'others' => 'Others',
                                        ];
                                        foreach(explode(',', $corrective->contributing_factors) as $factor) {
                                            if (isset($values[$factor])) {
                                                $contributingFactors[] = $values[$factor];
                                            }
                                        }
                                    @endphp
                                    {{ implode(', ', $contributingFactors) }}
                                @else
                                    -
                                @endif
                        </span>
                        @if($corrective->contributing_factors_details)
                            <div class="mt-2">
                                <label>Contributing factors details: </label><br>
                                <span>{{ $corrective->contributing_factors_details }}</span>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>Are there any external factors or influences?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <div>
                            <span>
                            @if($corrective->external_factors)
                                @php
                                    $externalFactors = [];
                                    foreach(explode(',', $corrective->external_factors) as $factor) {
                                        switch($factor) {
                                            case 'market_conditions':
                                                $externalFactors[] = 'Market conditions';
                                                break;
                                            case 'regulatory_changes':
                                                $externalFactors[] = 'Regulatory changes';
                                                break;
                                            case 'external_events':
                                                $externalFactors[] = 'External events';
                                                break;
                                            case 'others':
                                                $externalFactors[] = 'Others';
                                                break;
                                            default:
                                                break;
                                        }
                                    }
                                @endphp
                                {{ implode(', ', $externalFactors) }}
                            @else
                                -
                            @endif
                        </span>
                            @if($corrective->external_factors_details)
                                <div class="mt-2">
                                    <label> External factors details: </label><br>
                                    <span>{{ $corrective->external_factors_details }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
                <div>
                    <div class="d-flex mb-3">
                        <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                        <span>Was the issue foreseeable?</span>
                    </div>
                    <div class="d-flex">
                        <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                        <span>
                            @if($corrective->issue_foreseeable === 1)
                            Yes
                            @elseif(!$corrective->issue_foreseeable)
                            -
                            @else
                            No
                            @endif
                        </span>
                    </div>
                </div>
                <hr>
                @if($corrective->prevent_reason)
                    <div>
                        <div class="d-flex mb-3">
                            <span class="font-weight-bold mr-3" style="text-decoration: underline">Question</span>
                            <span> Why wasn't it prevented?</span>
                        </div>
                        <div class="d-flex">
                            <span style="color: #176044; text-decoration: underline" class="font-weight-bold mr-3">Response</span>
                            <span>{{ $corrective->prevent_reason }}</span>
                        </div>
                    </div>
                    <hr>
                @endif
            </div>
        </div>
    </div>
</div>