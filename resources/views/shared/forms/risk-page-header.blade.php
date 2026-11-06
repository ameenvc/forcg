@php
    $formRecord = $formRecord ?? (isset($riskdata) ? $riskdata : (isset($data) ? ($data instanceof \Illuminate\Support\Collection ? $data->first() : $data) : ($risk ?? null)));
    $formRecordId = is_object($formRecord) ? ($formRecord->Rid ?? null) : null;
@endphp
<header class="rf-page-header">
    <a class="rf-back" href="{{ $formRecordId ? route('risk.detail', $formRecordId) : route('risk.index') }}"><i class="fa fa-arrow-left mr-1" aria-hidden="true"></i>{{ $formRecordId ? 'Back to risk' : 'Risk register' }}</a>
    <div class="rf-page-heading"><div><span class="rf-eyebrow">ENTERPRISE RISK MANAGEMENT</span><h1>{{ $formTitle }}</h1><p>{{ $formDescription }}</p></div><span class="rf-header-icon" aria-hidden="true"><i class="fa fa-clipboard-check"></i></span></div>
    @if($formRecordId)<div class="rf-record-context"><span>{{ $formRecord->grp_id }}</span><strong>{{ $formRecord->riskname }}</strong></div>@endif
</header>
