@extends('layouts.backend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/risk-history.css') }}?v={{ filemtime(public_path('css/risk-history.css')) }}">
<script defer src="{{ asset('js/risk-history.js') }}?v={{ filemtime(public_path('js/risk-history.js')) }}"></script>
@php
    $ratingLabel = function ($value) { return [1=>'Low',2=>'Moderate',3=>'High'][$value ?? ''] ?? $value; };
    $toneFor = function ($value) { return ['High'=>'high','Moderate'=>'moderate','Low'=>'low'][$value ?? ''] ?? 'neutral'; };
    $eventLabel = function ($value) { return $value === 'Intial Review' ? 'Initial Review' : ($value ?: 'Assessment snapshot'); };
    $formatDate = function ($value) { if (!$value) return 'Date not recorded'; try { return \Carbon\Carbon::parse($value)->format('d M Y · H:i'); } catch (\Exception $exception) { return 'Date not recorded'; } };
    $eventTypes = $riskhistory->map(function ($event) use ($eventLabel) { return $eventLabel($event->action); })->unique()->sort();
@endphp
<div class="content rh-workspace risk-module-page" id="risk-history-workspace">
    <header class="rh-header">
        <div class="rh-topline"><a href="{{ route('risk.index') }}" class="rh-back"><i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>Risk register</a><div class="rh-header-actions"><button type="button" class="btn rh-button" onclick="window.print()"><i class="fa fa-print mr-1" aria-hidden="true"></i>Print</button><a class="btn rh-button rh-primary" href="{{ route('risk.detail', $riskdata->Rid) }}">Open risk <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></a></div></div>
        <div class="rh-title"><span class="rh-icon" aria-hidden="true"><i class="fa fa-history"></i></span><div><h1>Risk history</h1></div></div>
        <div class="rh-record"><span class="rh-id">{{ $riskdata->grp_id }}</span><strong>{{ $riskdata->riskname }}</strong></div>
        <dl class="rh-summary"><div><dt>Risk group</dt><dd>{{ $riskdata->riskgroup ?: 'Not assigned' }}</dd></div><div><dt>Risk area</dt><dd>{{ $riskdata->department->departmentname ?? 'Not assigned' }}</dd></div><div><dt>Current rating</dt><dd><span class="rh-badge rh-tone-{{ $toneFor($riskdata->rank) }}">{{ $riskdata->rank ?: 'Unassessed' }}</span></dd></div><div><dt>History entries</dt><dd>{{ $riskhistory->count() }}</dd></div></dl>
        <details class="rh-description"><summary>Current risk description</summary><p>{{ strip_tags($riskdata->riskdec ?: 'No description has been recorded.') }}</p></details>
    </header>
    <section class="rh-history" aria-labelledby="rh-history-heading">
        <div class="rh-section-heading"><div><h2 id="rh-history-heading">Recorded activity</h2><p>Times shown in {{ config('app.timezone', 'UTC') }}. Current risk information appears above.</p></div></div>
        @if($riskhistory->count())
        <div class="rh-toolbar"><label class="rh-search"><i class="fa fa-search" aria-hidden="true"></i><span class="sr-only">Search history</span><input id="history-search" type="search" placeholder="Search changes, events, or reviewers…" autocomplete="off"></label><label class="rh-filter"><span class="sr-only">Filter event type</span><select id="history-event-type"><option value="">All events</option>@foreach($eventTypes as $type)<option>{{ $type }}</option>@endforeach</select></label><label class="rh-filter"><span class="sr-only">Sort history</span><select id="history-sort"><option value="newest">Newest first</option><option value="oldest">Oldest first</option></select></label></div>
        <div class="rh-view-info"><span id="history-match-count" role="status" aria-live="polite">{{ $riskhistory->count() }} entries</span><button type="button" id="history-clear" hidden>Clear search & filters</button></div>
        <ol class="rh-timeline" id="history-timeline">
            @foreach($riskhistory as $index => $event)
            @php
                $changes = [];
                foreach (['impa'=>'Impact','like'=>'Likelihood','rank'=>'Risk rating','mitigating_action'=>'Mitigation action'] as $field=>$label) {
                    $before = $event->{$field}; $after = $event->{$field.'_updated'};
                    if ($before === null && $after === null) continue;
                    if ($field === 'rank') { $before = $ratingLabel($before); $after = $ratingLabel($after); }
                    $changes[] = ['label'=>$label,'before'=>$before,'after'=>$after,'rating'=>$field !== 'mitigating_action'];
                }
                $type = $eventLabel($event->action);
            @endphp
            <li class="rh-event" data-history-event data-event-type="{{ $type }}" data-event-order="{{ $index }}">
                <span class="rh-event-marker" aria-hidden="true"><i class="fa {{ stripos($type, 'review') !== false ? 'fa-clipboard-check' : 'fa-history' }}"></i></span>
                <article class="rh-event-card"><div class="rh-event-heading"><div><h3>{{ $type }}</h3><span class="rh-actor"><i class="fa fa-user mr-1" aria-hidden="true"></i>{{ $event->riskOwner->name ?? 'User not recorded' }}</span></div><time>{{ $formatDate($event->created_at) }}</time></div>
                    @if(count($changes))<dl class="rh-changes">@foreach($changes as $change)<div class="rh-change"><dt>{{ $change['label'] }}</dt><dd>
                        @if($change['before'] !== null)<span class="rh-change-value {{ $change['rating'] ? 'rh-badge rh-tone-'.$toneFor($change['before']) : 'rh-text-value' }}">{{ $change['before'] }}</span>@endif
                        @if($change['after'] !== null)
                            @if($change['before'] !== null && $change['before'] !== $change['after'])<span class="rh-change-arrow"><span class="sr-only">changed to</span><i class="fa fa-arrow-right" aria-hidden="true"></i></span>@endif
                            @if($change['before'] !== $change['after'])<span class="rh-change-value {{ $change['rating'] ? 'rh-badge rh-tone-'.$toneFor($change['after']) : 'rh-text-value' }}">{{ $change['after'] }}</span>@else<small>Unchanged</small>@endif
                            @if($change['before'] === null)<small>Recorded value</small>@endif
                        @else<small>{{ $event->action ? 'Updated value not recorded' : 'Recorded value' }}</small>@endif
                    </dd></div>@endforeach</dl>@else<p class="rh-no-changes">No assessment changes were recorded for this event.</p>@endif
                </article>
            </li>
            @endforeach
        </ol>
        <div class="rh-empty" id="history-no-matches" hidden><i class="fa fa-search" aria-hidden="true"></i><h3>No matching history</h3><p>Try another search or clear the event filter.</p></div>
        @else<div class="rh-empty"><i class="fa fa-history" aria-hidden="true"></i><h3>No history recorded yet</h3><p>This risk's reviews and assessment changes will appear here when recorded.</p><a href="{{ route('risk.detail', $riskdata->Rid) }}" class="rh-back">View current risk</a></div>@endif
    </section>
    <div class="modal fade rh-navigation-modal" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="history-module-navigation" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h2 class="modal-title" id="history-module-navigation">Risk module</h2><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body rh-module-links"><a href="{{ route('risk.index') }}"><i class="fa fa-list-alt mr-2" aria-hidden="true"></i>Register</a><a href="{{ route('risk.closedrisks') }}"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Closed risks</a><a href="{{ route('risk.analysis') }}"><i class="fa fa-chart-pie mr-2" aria-hidden="true"></i>Analysis</a></div></div></div></div>
</div>
@endsection
