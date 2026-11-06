@once
<link rel="stylesheet" href="{{ asset('css/risk-header-actions.css') }}?v={{ filemtime(public_path('css/risk-header-actions.css')) }}">
@endonce
@php
    $registerPage = $registerPage ?? 'register';
    $pageTitles = ['register' => 'Enterprise risk register', 'mitigation' => 'Risk mitigation actions', 'analysis' => 'Enterprise risk analysis', 'closed' => 'Closed risk register', 'criteria' => 'Risk assessment criteria matrix (RACM)'];
    $pageIcons = ['register' => 'fa-shield-alt', 'mitigation' => 'fa-tasks', 'analysis' => 'fa-chart-pie', 'closed' => 'fa-check-circle', 'criteria' => 'fa-sliders-h'];
@endphp
<header class="register-header cgbod-form-header risk-page-banner register-header-compact">
    <div class="register-header-main">
        <div class="register-header-copy">
            <div class="register-title-line"><span class="register-title-icon" aria-hidden="true"><i class="fa {{ $pageIcons[$registerPage] ?? 'fa-shield-alt' }}"></i></span><h1>{{ $pageTitles[$registerPage] ?? ($registerTitle ?? 'Enterprise risk register') }}</h1></div>
        </div>
    </div>
    @if($registerPage === 'criteria')
    <button type="button" class="btn btn-primary register-add risk-header-button btn-add-racm"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add criteria</button>
    @elseif($registerPage === 'mitigation')
    <button type="button" class="btn btn-primary register-add risk-header-button btn-create-mitigation" data-route="{{ route('mitigations.create', array_filter(['riskId' => $riskId ?? null])) }}"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add mitigation action</button>
    @elseif($registerPage === 'analysis')
    <div class="risk-header-actions"><div class="dropdown"><button type="button" id="analysis-export" class="btn btn-outline-primary risk-header-button dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download" aria-hidden="true"></i>Export analysis</button><div class="dropdown-menu dropdown-menu-right" aria-labelledby="analysis-export"><button type="button" class="dropdown-item" id="analysis-export-pptx"><i class="fa fa-file-powerpoint mr-2" aria-hidden="true"></i>PowerPoint with charts (.pptx)</button><button type="button" class="dropdown-item" id="analysis-export-print"><i class="fa fa-file-pdf mr-2" aria-hidden="true"></i>Print / save PDF with charts</button><div class="dropdown-divider"></div><button type="button" class="dropdown-item" id="analysis-export-csv"><i class="fa fa-file-csv mr-2" aria-hidden="true"></i>Data spreadsheet (.csv)</button></div></div><a href="{{ route('risk.addrisk') }}" class="btn btn-primary register-add risk-header-button"><i class="fa fa-plus" aria-hidden="true"></i>Add Risk</a></div>
    @else
    <a href="{{ route('risk.addrisk') }}" class="btn btn-primary register-add risk-header-button"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Risk</a>
    @endif
</header>
<nav class="register-navigation" aria-label="Risk module pages">
    <a href="{{ route('risk.index') }}" @if($registerPage === 'register') aria-current="page" @endif>Register</a>
    <a href="{{ route('mitigations.index') }}" @if($registerPage === 'mitigation') aria-current="page" @endif>Mitigation actions</a>
    <a href="{{ route('risk.analysis') }}" @if($registerPage === 'analysis') aria-current="page" @endif>Analysis</a>
    <a href="{{ route('risk.closedrisks') }}" @if($registerPage === 'closed') aria-current="page" @endif>Closed risks</a>
    <a href="{{ route('racm.index') }}" @if($registerPage === 'criteria') aria-current="page" @endif>Assessment criteria</a>
    @if($registerPage === 'register')
    <button type="button" class="video-btn" id="learn_btn" aria-label="Learn module" title="Learn module" data-toggle="modal" data-src="https://youtube.com/embed/M2qMFyvFPTI" data-target="#modal-learn-module"><i class="fa fa-video mr-1" aria-hidden="true"></i><span id="learn">Learn module</span></button>
    @endif
</nav>
