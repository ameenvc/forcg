@php
    $progress = max(0, min(100, (int) $mitigation->progress));
    $isClosed = $mitigation->status === config('constants.risk_management.mitigation.closed');
    $progressLabel = $isClosed ? 'Closed' : ($progress === 100 ? 'Completed' : ($progress === 0 ? 'Not started' : 'In progress'));
    $progressFilter = $isClosed ? 'Closed' : ($progress === 100 ? 'Completed - 100%' : ($progress === 0 ? 'Not started - 0%' : 'In progress - 25%|50%|75%'));
@endphp
<div class="mitigation-progress {{ $isClosed || $progress === 100 ? 'is-complete' : '' }}">
    <div class="mitigation-progress-label"><span>{{ $progressLabel }}</span><strong>{{ $progress }}%</strong></div>
    <div class="mitigation-progress-track" role="progressbar" aria-label="Mitigation progress: {{ $progressLabel }}" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
        <div class="mitigation-progress-fill" style="width:{{ $progress }}%"></div>
    </div>
    <span class="sr-only">{{ $progressFilter }}</span>
</div>
