@extends('layouts.backend')
@section('content')
@include('shared.forms.assets')
<link rel="stylesheet" href="{{ asset('css/risk-register.css') }}?v={{ filemtime(public_path('css/risk-register.css')) }}">
<link rel="stylesheet" href="{{ asset('css/risk-pages.css') }}?v={{ filemtime(public_path('css/risk-pages.css')) }}">
<script src="{{ asset('js/risk-analysis.js') }}?v={{ filemtime(public_path('js/risk-analysis.js')) }}" defer></script>
@php
    $counts = $analysisData['totals'];
    $highShare = $counts['total'] ? round(100 * $counts['High'] / $counts['total']) : 0;
@endphp
<div class="content cgbod-form risk-register risk-analysis" id="risk-analysis" data-analysis="{{ json_encode($analysisData) }}" data-filters="{{ json_encode($filters) }}" data-generated-at="{{ $generatedAt }}" data-url="{{ route('risk.analysis') }}">
    @include('user.risks.register-header', ['registerPage' => 'analysis', 'registerTitle' => 'Risk analysis', 'registerDescription' => 'Understand your risk profile, spot concentration, and focus the next review.'])
    <form class="analysis-date-controls" id="analysis-date-form" action="{{ route('risk.analysis') }}" method="get">
        <label>Period<select id="analysis-period"><option value="all">All time</option><option value="30">Last 30 days</option><option value="90">Last 90 days</option><option value="year">This year</option><option value="custom">Custom dates</option></select></label>
        <label>Created from<input type="date" id="analysis-from" name="from" value="{{ $filters['from'] }}"></label>
        <label>Created to<input type="date" id="analysis-to" name="to" value="{{ $filters['to'] }}"></label>
        <label>Records<select id="analysis-record-scope" name="scope">@foreach(['all' => 'All records', 'current' => 'Current register', 'closed' => 'Closed risks'] as $value => $label)<option value="{{ $value }}" @if($filters['scope'] === $value) selected @endif>{{ $label }}</option>@endforeach</select></label>
        <button type="submit" class="btn btn-primary" id="analysis-apply">Apply filters</button><a href="{{ route('risk.analysis') }}">Clear dates</a>
    </form>
    <div class="analysis-live-controls"><span id="analysis-data-status" role="status" aria-live="polite">Snapshot loaded</span><div><label>Auto-refresh<select id="analysis-live"><option value="0">Off</option><option value="60">Every minute</option><option value="300">Every 5 minutes</option></select></label><button type="button" class="btn register-secondary" id="analysis-refresh"><i class="fa fa-sync-alt mr-1" aria-hidden="true"></i>Refresh now</button></div></div>
    @if(isset($errors) && $errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="analysis-context"><div><span class="analysis-eyebrow">CURRENT PORTFOLIO</span><p><span id="analysis-applied-scope">All recorded risks, including closed records.</span> Deleted records are excluded. Counts reflect stored ratings. <span id="analysis-undated-note"></span></p></div><label>Risk group<select id="analysis-group"><option value="">All groups</option>@foreach($analysisData['groups'] as $group => $groupData)<option>{{ $group }}</option>@endforeach</select></label></div>
    <div class="analysis-rating-strip" aria-label="Risk counts for the selected group">
        @foreach(['total' => 'Total risks', 'High' => 'High', 'Moderate' => 'Moderate', 'Low' => 'Low', 'Unassessed' => 'Unassessed'] as $rating => $label)
        <div data-tone="{{ $rating }}"><span>{{ $label }}</span><strong data-analysis-count="{{ $rating }}">{{ $counts[$rating] }}</strong></div>
        @endforeach
    </div>
    <div class="analysis-quality" aria-label="Information coverage"><div><span>Without an owner</span><strong data-analysis-quality="unassigned_owner">{{ $analysisData['quality']['unassigned_owner'] }}</strong></div><div><span>Response not recorded</span><strong data-analysis-quality="missing_response">{{ $analysisData['quality']['missing_response'] }}</strong></div><div><span>Creation date unavailable</span><strong data-analysis-quality="undated">{{ $analysisData['quality']['undated'] }}</strong></div></div>
    <section class="analysis-card analysis-trends"><div class="analysis-card-heading"><div><h2>Risks added over time</h2><p>Registration counts by creation date. These are not historical rating changes.</p></div><label>Group by<select id="analysis-trend-unit"><option value="month">Month</option><option value="day">Day</option></select></label></div><p class="analysis-note" id="analysis-trend-scope"></p><div class="analysis-trend-chart" id="analysis-trend-chart" role="list" aria-label="Risks registered by period"></div></section>
    <div class="analysis-overview">
        <section class="analysis-card"><div class="analysis-card-heading"><div><h2>Rating distribution</h2><p id="analysis-selected-scope">All groups</p></div><span class="analysis-current">Current snapshot</span></div>
            <div class="analysis-distribution"><div class="analysis-donut" id="analysis-donut" role="img" aria-label="Risk distribution"><div><strong id="analysis-donut-total">{{ $counts['total'] }}</strong><span>recorded risks</span></div></div><div class="analysis-legend">
                @foreach(['High', 'Moderate', 'Low', 'Unassessed'] as $rating)<div><span><i data-rating="{{ $rating }}" aria-hidden="true"></i>{{ $rating }}</span><strong data-analysis-legend="{{ $rating }}">{{ $counts[$rating] }}</strong><small data-analysis-percent="{{ $rating }}">{{ $counts['total'] ? round(100 * $counts[$rating] / $counts['total']) : 0 }}%</small></div>@endforeach
            </div></div><p class="analysis-note" id="analysis-rating-note">{{ $highShare }}% of recorded risks are rated High. Ratings use the approved 3 × 3 matrix.</p>
        </section>
        <section class="analysis-card"><div class="analysis-card-heading"><div><h2>Risk groups</h2><p>Select a group to explore its profile.</p></div><button type="button" class="analysis-reset" id="analysis-reset">Reset view</button></div>
            <p class="analysis-group-hint">Swipe to see all ratings</p><div class="analysis-group-scroll" role="region" aria-label="Risk group ratings; scroll for more columns" tabindex="0"><table class="analysis-group-table"><thead><tr><th scope="col">Group</th><th scope="col">Total</th><th scope="col">High</th><th scope="col">Moderate</th><th scope="col">Low</th><th scope="col">Unassessed</th></tr></thead><tbody>
                @forelse($analysisData['groups'] as $group => $groupData)<tr data-analysis-group-row="{{ $group }}"><th scope="row"><button type="button" data-analysis-group="{{ $group }}" aria-pressed="false">{{ $group }}</button></th>@foreach(['total', 'High', 'Moderate', 'Low', 'Unassessed'] as $rating)<td>{{ $groupData['counts'][$rating] }}</td>@endforeach</tr>@empty<tr><td colspan="6">No risk groups to display yet.</td></tr>@endforelse
            </tbody></table></div>
        </section>
    </div>
    <div class="analysis-overview analysis-workflow"><section class="analysis-card"><div class="analysis-card-heading"><div><h2>Workflow stages</h2><p>Current stage of the selected records.</p></div></div><div id="analysis-stages" class="analysis-breakdown"></div></section><section class="analysis-card"><div class="analysis-card-heading"><div><h2>Response strategies</h2><p>Recorded treatment choices and gaps.</p></div></div><div id="analysis-responses" class="analysis-breakdown"></div></section></div>
    <section class="analysis-card analysis-departments"><div class="analysis-card-heading"><div><h2>Department comparison</h2><p>Compare the volume and rating mix. Department codes follow your existing setup.</p></div><div class="analysis-chart-controls"><label><span class="sr-only">Search departments</span><input type="search" id="analysis-department-search" placeholder="Find a department…"></label><label><span class="sr-only">Sort departments</span><select id="analysis-department-sort"><option value="total">Most risks</option><option value="high">Most high risks</option><option value="name">Department A–Z</option></select></label></div></div>
        <div class="analysis-chart-legend" aria-hidden="true">@foreach(['High', 'Moderate', 'Low', 'Unassessed'] as $rating)<span><i data-rating="{{ $rating }}"></i>{{ $rating }}</span>@endforeach</div>
        <p id="analysis-department-count" class="analysis-note" role="status" aria-live="polite"></p>
        <div id="analysis-department-chart" class="analysis-department-chart" role="list" aria-label="Department risk counts"></div>
        <noscript><p>Enable JavaScript to explore the department comparison.</p></noscript>
        <div class="analysis-footer"><span>Unassigned includes risks without a matching department code. The timeline counts new registrations; it does not reconstruct past ratings.</span><a href="{{ route('risk.index') }}">Open current register <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></a></div>
    </section>
</div>
@endsection
