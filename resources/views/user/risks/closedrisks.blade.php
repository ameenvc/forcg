@extends('pages.datatables')
@section('content')
@once
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
@endonce

@include('shared.forms.assets')
@include('user.risks.review-assets')
<link rel="stylesheet" href="{{ asset('css/risk-register.css') }}?v={{ filemtime(public_path('css/risk-register.css')) }}">
<link rel="stylesheet" href="{{ asset('css/risk-pages.css') }}?v={{ filemtime(public_path('css/risk-pages.css')) }}">
<script src="{{ asset('js/risk-closed-register.js') }}?v={{ filemtime(public_path('js/risk-closed-register.js')) }}" defer></script>
<div class="content cgbod-form risk-register closed-risk-register" id="risk-closed-register" data-density="compact">
    @include('user.risks.register-header', ['registerPage' => 'closed', 'registerTitle' => 'Closed risks', 'registerDescription' => 'Review closed risks and reopen a record when its circumstances change.'])
    <div class="block block-rounded risk-register-card"><div class="block-content block-content-full">
        @include('user.risks.notification-warning')
        @foreach(['msg' => 'success', 'errmsg' => 'danger'] as $message => $tone)
        @if(Session::has($message))<div class="alert alert-{{ $tone }}">{{ Session::get($message) }}</div>@endif
        @endforeach
        <div class="register-toolbar">
            <label class="register-search"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search closed risks</span><input type="search" id="closed-risk-search" placeholder="Search closed risks, IDs, or owners…"></label>
            <div class="register-toolbar-actions">
                <button type="button" class="btn register-secondary" id="closed-risk-filter-toggle" aria-controls="closed-risk-filters" aria-expanded="false"><i class="fa fa-filter mr-1" aria-hidden="true"></i>Filters</button>
                <details class="register-menu"><summary><i class="fa fa-download mr-1" aria-hidden="true"></i>Export <i class="fa fa-chevron-down ml-1" aria-hidden="true"></i></summary><div class="register-menu-body"><small>Exports all matching closed risks.</small>
                @foreach(['pdf' => 'PDF document', 'excel' => 'Excel workbook', 'word' => 'Word document'] as $format => $label)
                <a href="{{ route('risk.exportData', ['format' => $format, 'ids' => json_encode($data2->pluck('Rid')->all())]) }}" data-closed-export="{{ route('risk.exportData', $format) }}">{{ $label }}</a>
                @endforeach
                </div></details>
            </div>
        </div>
        <div class="register-filters" id="closed-risk-filters" hidden>
            <label>Risk group<select data-closed-filter="4"><option value="">All groups</option>@foreach($data2->pluck('riskgroup')->filter()->unique()->sort() as $group)<option>{{ $group }}</option>@endforeach</select></label>
            <label>Risk area<select data-closed-filter="5"><option value="">All areas</option>@foreach($data2->pluck('department.departmentname')->filter()->unique()->sort() as $area)<option>{{ $area }}</option>@endforeach</select></label>
            <label>Rating<select data-closed-filter="6"><option value="">All ratings</option><option>High</option><option>Moderate</option><option>Low</option></select></label>
        </div>
        <div class="register-view-options"><span id="closed-risk-count" aria-live="polite">{{ count($data2) }} closed risks</span><button type="button" id="closed-risk-clear" hidden>Clear search &amp; filters</button><label class="register-sort">Sort by<select id="closed-risk-sort"><option value="default">Latest added first</option><option value="rating">Highest rating</option><option value="name">Risk name A–Z</option><option value="target">Target date</option></select></label><label>Rows<select id="closed-risk-length"><option>25</option><option>50</option><option>100</option></select></label></div>
        <div id="closed-risk-feedback" role="status" aria-live="polite"></div>
        <div class="register-table-hint" aria-hidden="true"><i class="fa fa-arrows-alt-h"></i>Swipe the table to see more columns</div>
        <div class="risk-table-scroll" tabindex="0" role="region" aria-label="Closed risk register; scroll horizontally for more columns">
        <table id="tabledata" class="risk-module-table table table-vcenter risk-register-table"><thead><tr><th>S.No</th><th>Internal ID</th><th>Risk ID</th><th>Risk name</th><th>Risk group</th><th>Risk area</th><th>Rating</th><th>Risk owner</th><th>Response</th><th>Target date</th><th>Process stage</th><th>Action status</th><th>Actions</th></tr></thead><tbody>
        @php $ownerMap = $users->keyBy('email'); @endphp
        @foreach($data2 as $risk)
        @php
            $owners = collect(explode(',', (string) $risk->risk_owner))->map(function ($email) use ($ownerMap) { $user = $ownerMap->get(trim($email)); return $user ? $user->name.' · '.$user->employeeid : null; })->filter()->implode(', ');
            $targetOrder = $risk->action_target ? (strtotime($risk->action_target) ?: 0) : 0;
        @endphp
        <tr><td>{{ $loop->iteration }}</td><td>{{ $risk->Rid }}</td><td><span class="register-id-text">{{ $risk->grp_id }}</span></td>
            <td class="register-risk-name"><a href="{{ route('risk.detail', $risk->Rid) }}"><span class="register-name-text" title="{{ $risk->riskname }}">{{ $risk->riskname }}</span></a></td>
            <td data-search="{{ $risk->riskgroup }}"><span class="register-cell-text" title="{{ $risk->riskgroup }}">{{ $risk->riskgroup }}</span></td>
            <td data-search="{{ $risk->department ? $risk->department->departmentname : 'Unassigned' }}"><span class="register-cell-text" title="{{ $risk->department ? $risk->department->departmentname : 'Unassigned' }}">{{ $risk->department ? $risk->department->departmentname : 'Unassigned' }}</span></td>
            <td data-search="{{ $risk->rank }}" data-order="{{ $risk->rank === 'High' ? 3 : ($risk->rank === 'Moderate' ? 2 : 1) }}"><span class="status {{ $risk->rank === 'High' ? 'status-red' : ($risk->rank === 'Moderate' ? 'status-warning' : 'status-success') }}">{{ $risk->rank ?: 'Unassessed' }}</span></td>
            <td><span class="register-cell-text" title="{{ $owners ?: 'Unassigned' }}">{{ $owners ?: 'Unassigned' }}</span></td><td><span class="register-cell-text" title="{{ $risk->risk_response }}">{{ $risk->risk_response ?: '—' }}</span></td>
            <td data-order="{{ $targetOrder }}"><span class="register-cell-text" title="{{ $risk->action_target }}">{{ $risk->action_target ?: '—' }}</span></td><td><span class="status status-blue">Closed</span></td><td><span class="register-cell-text" title="{{ $risk->actionst }}">{{ $risk->actionst ?: '—' }}</span></td>
            <td class="register-row-actions closed-risk-actions"><a class="closed-risk-reopen" href="{{ route('risk.reopenrisk', ['val' => $risk->Rid]) }}">Reopen</a><button type="button" class="btn register-view-button" data-closed-view="{{ route('risk.show', $risk->Rid) }}" data-modal-id="viewModal{{ $risk->Rid }}" aria-label="View risk {{ $risk->grp_id }}" title="View risk"><i class="far fa-eye" aria-hidden="true"></i></button></td>
        </tr>
        @endforeach
        </tbody></table></div><div id="closed-risk-footer"></div>
    </div></div><div class="risk-area"></div>
</div>
@endsection
