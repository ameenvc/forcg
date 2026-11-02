
@php
    $stages = [
        'Review Pending',
        'Disapproved',
        'Approved',
        'Completed',
        'Closed'
    ];
    $currentStage = $opportunity->status; 
@endphp

<label class="mt-2 ml-2" id="timelineLabel">
    <i class="fa fa-chart-line mr-2 p-1"
       style="background:#2529453D;border-radius:5px;"></i>
    Status & Timeline
</label>
<div class="process-card mt-2">
    <div class="process-title">
        CURRENT PROCESS STAGE
    </div>
    <div class="stepper">
        @foreach($stages as $stage)
            <div class="step">
                <div class="circle {{ $stage == $currentStage ? 'active' : '' }}">
                    @if($stage == $currentStage)
                        <span class="inner-circle"></span>
                    @endif
                </div>
                <div class="step-label">
                    {{ $stage }}
                </div>
            </div>
        @endforeach
    </div>
    <div class="current-stage">
        {{ $currentStage }}
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12 mb-3">
        <span style="color:gray;">Assigned Approver:</span>
        <strong>{{ optional($opportunity->opportunityapprover)->name ?? 'Not Assigned' }}</strong>
    </div>

    

    <div class="col-md-6 mb-3">
        <span style="color:gray;">Closure Reviewer:</span>
        <strong>{{ optional($opportunity->closurereviewer)->name ?? 'Not Assigned' }}</strong>
    </div>
    <div class="col-md-6 mb-3 text-lg-right text-md-right">
        <span style="color:gray;">Target Date:</span>
        <strong>{{ $opportunity->tardate ? date('d-m-Y', strtotime($opportunity->tardate)) : '-' }}</strong>
    </div>

</div>