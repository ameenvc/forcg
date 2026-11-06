<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title>Risk report — {{ $risk->grp_id }}</title>
<style>
@page { margin:32pt 30pt 42pt; }
body { font-family:"DejaVu Sans",sans-serif; font-size:9pt; line-height:1.5; color:#293d61; margin:0; }
.report-header { width:100%; border-collapse:collapse; border-bottom:3px solid #596bcf; margin-bottom:18px; }
.report-header td { padding:0 0 12px; vertical-align:middle; }
.company { font-size:10pt; font-weight:bold; }
.meta { color:#6b7d97; font-size:8pt; }
.logo { max-width:90px; max-height:40px; }
.title { font-size:18pt; font-weight:bold; line-height:1.35; margin:8px 0 10px; color:#243b61; }
.record-id { color:#6855a4; font-size:9pt; font-weight:bold; }
.context { color:#6b7d97; margin-bottom:16px; }
.summary { width:100%; table-layout:fixed; border-collapse:collapse; margin:0 0 20px; background:#f3f6fc; }
.summary td { width:25%; padding:11px; border:1px solid #dfe5f0; vertical-align:top; }
.label { color:#6b7d97; font-size:8pt; }
.value { font-size:9pt; font-weight:bold; margin-top:5px; }
.section { margin-bottom:19px; }
h2 { font-size:12pt; font-weight:bold; margin:0 0 10px; padding:7px 0; border-bottom:1px solid #dfe5f0; color:#344d80; page-break-after:avoid; }
h3 { font-size:9pt; font-weight:bold; margin:12px 0 5px; page-break-after:avoid; }
p { margin:0 0 8px; word-wrap:break-word; }
.prose { white-space:pre-line; }
.facts { width:100%; border-collapse:collapse; table-layout:fixed; }
.facts td { padding:9px 10px; border-bottom:1px solid #e5eaf3; vertical-align:top; }
.facts td:first-child { width:26%; color:#6b7d97; }
.assessment { width:100%; table-layout:fixed; border-collapse:collapse; }
.assessment>tbody>tr>td { padding:0 16px 0 0; vertical-align:top; }
.assessment>tbody>tr>td:last-child { width:170px; padding:0; }
.matrix { width:100%; table-layout:fixed; border-spacing:4px; }
.matrix td { height:23px; text-align:center; border:1px solid #fff; }
.matrix .current { border:2px solid #6258bf; font-weight:bold; }
.badge { display:inline-block; padding:3px 7px; border-radius:4px; font-size:8pt; font-weight:bold; }
.high,.status-red { background:#fdebee; color:#ac3c56; }
.moderate,.status-warning { background:#fff2da; color:#936422; }
.low,.status-success { background:#e5f4ed; color:#2e7e62; }
.neutral { background:#edf0f5; color:#67758e; }
.status-blue { background:#e9efff; color:#4162b0; }
.status { padding:2px 4px; font-size:8pt; }
.data-table { width:100%; border-collapse:collapse; table-layout:fixed; }
.data-table th { background:#eef2fb; color:#3e5175; text-align:left; font-weight:bold; padding:8px; border-bottom:2px solid #dce3f1; font-size:8pt; }
.data-table td { padding:9px 8px; border-bottom:1px solid #e5eaf3; vertical-align:top; word-wrap:break-word; }
.data-table tr { page-break-inside:avoid; }
.data-table small { font-size:7pt; color:#6b7d97; }
.progress-track { background:#e9edf6; height:5px; margin-top:5px; border-radius:3px; }
.progress-fill { background:#6675d2; height:5px; border-radius:3px; }
.completed { background:#369b80; }
.note { padding:10px 12px; background:#f5f7fc; border-left:3px solid #7668bf; }
.footer { position:fixed; bottom:0; left:0; right:0; border-top:1px solid #dfe5f0; padding-top:7px; font-size:7pt; color:#6b7d97; }
.page-number:after { content:counter(page); }
.footer-right { float:right; }
</style></head><body>
@php
    $toneFor = function ($value) { return ['High'=>'high','Moderate'=>'moderate','Low'=>'low'][$value ?? ''] ?? 'neutral'; };
    $displayDate = function ($value) { if (!$value) return 'Not set'; try { return \Carbon\Carbon::parse($value)->format('d M Y'); } catch (\Exception $exception) { return 'Not set'; } };
    $completed = $risk->mitigations->filter(function ($action) { return (int) $action->progress >= 100 || $action->status === config('constants.risk_management.mitigation.closed'); })->count();
    $values = ['Low'=>1,'Moderate'=>2,'High'=>3];
    $logo = null;
    if (isset($clientDetails) && $clientDetails && $clientDetails->client_logo) {
        $path = public_path('storage/clientlogos/'.basename($clientDetails->client_logo));
        if (is_file($path)) { $info = @getimagesize($path); if ($info && in_array($info['mime'], ['image/png','image/jpeg'])) $logo = 'data:'.$info['mime'].';base64,'.base64_encode(file_get_contents($path)); }
    }
@endphp
<div class="footer"><span>Enterprise risk management · {{ $risk->grp_id }}</span><span class="footer-right">Page <span class="page-number"></span></span></div>
<table class="report-header"><tr><td>@if($logo)<img class="logo" src="{{ $logo }}" alt="Company logo">@else<span class="company">{{ $clientDetails->company_name ?? 'Enterprise risk management' }}</span>@endif</td><td style="text-align:right"><span class="company">Risk assessment report</span><br><span class="meta">Exported {{ now()->format('d M Y') }}</span></td></tr></table>
<div class="record-id">{{ $risk->grp_id }} <span class="badge {{ $toneFor($risk->rank) }}">{{ $risk->rank ?: 'Unassessed' }}</span></div>
<h1 class="title">{{ $risk->riskname }}</h1>
<div class="context">{{ $risk->riskgroup ?: 'Group not assigned' }} · {{ $risk->department->departmentname ?? 'Department not assigned' }} @if($risk->division) · {{ $risk->division->name }} @endif @if($risk->area) · {{ $risk->area->name }} @endif</div>
<table class="summary"><tr><td><div class="label">Process stage</div><div class="value">{{ $risk->action_status ?: 'Not set' }}</div></td><td><div class="label">Risk response</div><div class="value">{{ $risk->risk_response ?: 'Not set' }}</div></td><td><div class="label">Target date</div><div class="value">{{ $displayDate($risk->action_target) }}</div></td><td><div class="label">Mitigation actions</div><div class="value">{{ $completed }} of {{ $risk->mitigations->count() }} completed</div></td></tr></table>
<div class="section"><h2>Risk context</h2><h3>What could happen</h3><p>{!! nl2br(e(strip_tags($risk->riskdec ?: 'No risk description has been recorded.'))) !!}</p><h3>Risk drivers</h3><p class="prose">{{ $risk->riskdri ?: 'No risk drivers have been recorded.' }}</p><h3>Existing controls</h3><p class="prose">{{ $risk->existing_controls ?: 'No existing controls have been recorded.' }}</p><p><span class="label">Risk source:</span> {{ $risk->riskscr ?: 'Not recorded' }}</p></div>
<div class="section"><h2>Current assessment</h2><table class="assessment"><tr><td><table class="facts">@foreach(['Impact'=>$risk->impa,'Likelihood'=>$risk->like,'Risk rating'=>$risk->rank] as $label=>$value)<tr><td>{{ $label }}</td><td><span class="badge {{ $toneFor($value) }}">{{ $value ?: 'Unassessed' }}</span></td></tr>@endforeach</table><p class="meta" style="margin-top:10px">The rating is the saved record. The marker shows the recorded impact and likelihood on the 3 × 3 matrix.</p></td><td><p class="meta">3 × 3 matrix · Impact →</p><table class="matrix">@foreach([1,2,3] as $likelihood)<tr>@foreach([1,2,3] as $impact) @php $score=$likelihood*$impact; $tone=$score<=2?'low':($score<=4?'moderate':'high'); $current=($values[$risk->impa ?? ''] ?? null)===$impact && ($values[$risk->like ?? ''] ?? null)===$likelihood; @endphp<td class="{{ $tone }} {{ $current ? 'current' : '' }}">{{ $score }}{{ $current ? ' *' : '' }}</td>@endforeach</tr>@endforeach</table><p class="meta">Likelihood increases downward.</p></td></tr></table></div>
<div class="section"><h2>Owners & reviewer</h2><table class="facts"><tr><td>Risk owners</td><td>@forelse($riskOwnerData as $owner){{ $owner['name'] }}@if($owner['employeeid'] ?? null) · {{ $owner['employeeid'] }}@endif<br>@empty No owner assigned.@endforelse</td></tr><tr><td>Risk reviewer</td><td>{{ $risk->riskreviewer->name ?? 'Not assigned' }}</td></tr><tr><td>Escalations</td><td>{{ $risk->number_of_escalated_times ?? 0 }} · Last escalated: {{ $displayDate($risk->last_escalated_time) }}</td></tr><tr><td>Linked opportunities</td><td>@forelse($risk->opportunities as $opportunity){{ $opportunity->dep_id }}<br>@empty None linked.@endforelse</td></tr></table></div>
<div class="section"><h2>Mitigation actions</h2>@if($risk->mitigations->count())<table class="data-table"><thead><tr><th style="width:39%">Action</th><th style="width:12%">Priority</th><th style="width:19%">Due date</th><th style="width:30%">Progress</th></tr></thead><tbody>@foreach($risk->mitigations as $action) @php $progress=max(0,min(100,(int)$action->progress)); $closed=$action->status===config('constants.risk_management.mitigation.closed'); @endphp<tr><td>{{ $action->action_name }}<br><small>{{ $action->action_id }}</small></td><td>{{ ucfirst($action->priority_level ?: 'Not set') }}</td><td>{{ $displayDate($action->due_date) }}</td><td>{{ $closed ? 'Closed' : ($progress===100?'Completed':($progress===0?'Not started':'In progress')) }} · {{ $progress }}%<div class="progress-track"><div class="progress-fill {{ $progress===100?'completed':'' }}" style="width:{{ $progress }}%"></div></div></td></tr>@endforeach</tbody></table>@else<p class="note">No mitigation actions have been recorded.</p>@endif</div>
<div class="section"><h2>Review history</h2><table class="facts"><tr><td>Risk recorded</td><td>{{ $risk->postedby == '' ? 'Administrator' : ($risk->postedByRisk->name ?? 'Not recorded') }} · {{ $displayDate($risk->created_at) }}</td></tr><tr><td>Initial review</td><td>{{ $risk->approvedByRisk->name ?? 'Reviewer not recorded' }} · {{ $displayDate($risk->review_date) }}</td></tr></table><h3>Periodic reviews</h3><table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Reviewed by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($riskHistories))
                                    @foreach($riskHistories as $index => $riskHistory)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left">
                                            @if(
                                                $riskHistory->impa == NULL
                                                && $riskHistory->like == NULL
                                                && $riskHistory->rank== NULL
                                            )
                                                --
                                            @endif
                                            @if($riskHistory->impa != NULL && $riskHistory->action != NULL)
                                                <span>
                                                    Impact changed from
                                                    @if($riskHistory->impa == "High")
                                                        <span class="status status-red">{{$riskHistory->impa }}</span>
                                                    @elseif($riskHistory->impa == "Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa }}</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->impa_updated == "High")
                                                        <span class="status status-red">{{$riskHistory->impa_updated }}</span>
                                                    @elseif($riskHistory->impa_updated == "Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa_updated }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa_updated }}</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->impa != NULL && $riskHistory->action == NULL)
                                                <span>
                                                    Impact changed from
                                                    @if($riskHistory->impa=="High")
                                                        <span class="status status-red">{{$riskHistory->impa }}</span>
                                                    @elseif($riskHistory->impa=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa }}</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->like != NULL && $riskHistory->action != NULL)
                                                <br><br>
                                                <span>
                                                    Likelihood changed from
                                                    @if($riskHistory->like=="High")
                                                        <span class="status status-red">{{$riskHistory->like }}</span>
                                                    @elseif($riskHistory->like=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like }}</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->like_updated=="High")
                                                        <span class="status status-red">{{$riskHistory->like_updated }}</span>
                                                    @elseif($riskHistory->like_updated=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like_updated }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like_updated }}</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->like != NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>
                                                    Likelihood changed from
                                                    @if($riskHistory->like=="High")
                                                        <span class="status status-red">{{$riskHistory->like }}</span>
                                                    @elseif($riskHistory->like=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like }}</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->rank!=NULL && $riskHistory->action!=NULL)
                                                <br><br>
                                                <span>
                                                    Risk changed from
                                                    @if($riskHistory->rank == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->rank_updated == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank_updated == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->rank!=NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>
                                                    Risk changed from
                                                    @if($riskHistory->rank == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->mitigating_action!=NULL && $riskHistory->action!=NULL)
                                                <br><br>
                                                <span>
                                                    Mitigating Action changed from <span>{{$riskHistory->mitigating_action}} </span>to <span>{{$riskHistory->mitigating_action_updated}}</span>
                                                </span>
                                            @elseif($riskHistory->mitigating_action!=NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>Mitigating Action changed from <span> {{$riskHistory->mitigating_action}}</span></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d-m-Y',strtotime($riskHistory->created_at)) }}</td>
                                        <td class="text-danger font-weight-bold">{{ $riskHistory->riskOwner ? $riskHistory->riskOwner->name : '--' }}</td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-danger">No matching records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table></div>
@if($risk->disapprovereason)<div class="section"><h2>Disapproval reason</h2><p class="prose">{{ $risk->disapprovereason }}</p></div>@endif
</body></html>
