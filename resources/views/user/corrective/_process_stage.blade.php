<label class="mt-2 ml-2"><i class="fa fa-chart-line mr-2 p-1" style="background: #2529453D;border-radius: 5px;"></i> Status & Timeline</label>
<div class="mt-2">
    <label>Current Process Stage</label>
    <div class="d-flex mt-3 ml-3 process-stage">
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-entry-under-review-action @if($corrective->processstage != 'Entry Under Review') hide-action @else bg-warning @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-rejected @if($corrective->processstage != 'rejected') hide-action @else bg-danger @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-awaiting-action @if($corrective->processstage != 'Awaiting actions') hide-action @else bg-secondary @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-correction-proposed-rc-awaiting-action @if($corrective->processstage != 'Correction Proposed- RC Awaiting') hide-action @else bg-blue @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-rc-update-correction-awaiting-action @if($corrective->processstage != 'RC Updated- Correction Awaiting') hide-action @else bg-blue @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-proposed-review-awaiting-action @if($corrective->processstage != 'Actions Proposed- Review Awaiting') hide-action @else bg-blue @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-action-reviewed-ok @if($corrective->processstage != 'Action Reviewed, OK') hide-action @else bg-success @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-action-reviewed-not-ok @if($corrective->processstage != 'Action Reviewed, Not OK') hide-action @else bg-danger @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-action-completed @if($corrective->processstage != 'Actions Completed') hide-action @else bg-primary @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-need-more-action @if($corrective->processstage != 'Need More Action') hide-action @else bg-warning @endif"></span>
        </label>
        <label class="process-stage-point">
            <span class="rounded-circle mr-5 d-inline-block p-2 span-close-action @if($corrective->status != 'closed') hide-action @else bg-black @endif"></span>
        </label>
    </div>
</div>
<div class="mt-4 d-flex justify-content-between">
    <label style="font-size: 0.75rem">Recommended due date: {{ $corrective->correction_target_date ? date('d-m-Y', strtotime($corrective->correction_target_date)) : '' }}</label>
    <label style="font-size: 0.75rem">Target Date(Investigation): {{ $corrective->investigation_date ? date('d-m-Y', strtotime($corrective->investigation_date)) : '--' }}</label>
</div>
