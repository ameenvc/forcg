@once
<link rel="stylesheet" href="{{ asset('css/risk-header-actions.css') }}?v={{ filemtime(public_path('css/risk-header-actions.css')) }}">
@endonce
@php
    $formRecord = $formRecord ?? (isset($riskdata) ? $riskdata : (isset($data) ? ($data instanceof \Illuminate\Support\Collection ? $data->first() : $data) : ($risk ?? null)));
    $formRecordId = ($showRecord ?? true) && is_object($formRecord) ? ($formRecord->Rid ?? null) : null;
    $formIcons = ['Initial risk review'=>'fa-clipboard-check','Edit risk'=>'fa-pen','Periodic risk review'=>'fa-sync-alt','Resubmit risk'=>'fa-redo','Change risk owners'=>'fa-user-edit','Risk closure review'=>'fa-check-circle','Reopen risk'=>'fa-lock-open','Request changes'=>'fa-comment-dots','Owner response'=>'fa-user-check','Request risk changes'=>'fa-edit','Add linked opportunity'=>'fa-lightbulb','Review mitigation closure'=>'fa-check-circle','Reopen mitigation action'=>'fa-tasks'];
    $formIcon = $formIcon ?? ($formIcons[$formTitle] ?? 'fa-file-alt');
@endphp
<header class="rf-page-header">
    <a class="rf-back" href="{{ $formRecordId ? route('risk.detail', $formRecordId) : route('risk.index') }}"><i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>{{ $formRecordId ? 'Back to risk' : 'Risk register' }}</a>
    <div class="rf-page-heading">
        <div class="rf-title-group"><span class="rf-header-icon" aria-hidden="true"><i class="fa {{ $formIcon }}"></i></span><div><h1>{{ $formTitle }}</h1></div></div>
        @if($formAiAssist ?? false)<div class="rf-header-actions">@include('user.risks.ai-trigger')</div>@endif
    </div>
    @if($formRecordId)<div class="rf-record-context"><span>{{ $formRecord->grp_id }}</span><strong>{{ $formRecord->riskname }}</strong></div>@endif
</header>
