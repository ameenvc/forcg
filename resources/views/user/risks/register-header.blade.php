<header class="register-header cgbod-form-header">
    <div><span class="register-eyebrow">ENTERPRISE RISK MANAGEMENT</span><h1>Risk register</h1><p>A clear view of your risks, owners, and next steps.</p></div>
    <a href="{{ route('risk.addrisk') }}" class="btn btn-primary register-add"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Risk</a>
</header>
<nav class="register-navigation" aria-label="Risk module pages">
    <a href="{{ route('risk.index') }}" aria-current="page">Register</a>
    <a href="{{ route('risk.closedrisks') }}">Closed risks</a>
    <a href="{{ route('risk.analysis') }}">Analysis</a>
    <button type="button" class="video-btn" id="learn_btn" data-toggle="modal" data-src="https://youtube.com/embed/M2qMFyvFPTI" data-target="#modal-learn-module"><i class="fa fa-video mr-1" aria-hidden="true"></i><span id="learn">Learn module</span></button>
</nav>
<section class="register-insights" aria-label="Risk distribution in this register view">
    <div class="register-insights-heading"><strong>Risk distribution</strong><span>Before table filters</span></div>
    <div class="register-summary" aria-label="Filter by risk rating">
        @foreach(['' => 'All risks', 'High' => 'High risk', 'Moderate' => 'Moderate risk', 'Low' => 'Low risk'] as $rating => $label)
        <button type="button" data-register-rating="{{ $rating }}" aria-pressed="{{ $rating === '' ? 'true' : 'false' }}" class="register-summary-card" data-tone="{{ $rating ?: 'all' }}">
            <span class="register-rating-dot" aria-hidden="true"></span><span class="register-summary-label">{{ $label }}</span>
            <strong data-register-count="{{ $rating }}">{{ $rating === '' ? count($data) : $data->where('rank', $rating)->count() }}</strong>
            @if($rating !== '')<small data-register-share="{{ $rating }}">{{ count($data) ? round(100 * $data->where('rank', $rating)->count() / count($data)) : 0 }}%</small>@endif
        </button>
        @endforeach
    </div>
    <div class="register-distribution-track" aria-hidden="true">@foreach(['High', 'Moderate', 'Low'] as $rating)<span data-register-distribution="{{ $rating }}" style="width:{{ count($data) ? 100 * $data->where('rank', $rating)->count() / count($data) : 0 }}%"></span>@endforeach</div>
</section>
