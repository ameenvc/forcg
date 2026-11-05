@php $registerPage = $registerPage ?? 'register'; @endphp
<header class="register-header cgbod-form-header">
    <div><span class="register-eyebrow">ENTERPRISE RISK MANAGEMENT</span><h1>{{ $registerTitle ?? 'Risk register' }}</h1><p>{{ $registerDescription ?? 'A clear view of your risks, owners, and next steps.' }}</p></div>
    @if($registerPage === 'criteria')
    <button type="button" class="btn btn-primary register-add btn-add-racm"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add criteria</button>
    @elseif($registerPage === 'mitigation')
    <a href="{{ route('risk.index') }}" class="btn btn-primary register-add">Choose risk to add action</a>
    @else
    <a href="{{ route('risk.addrisk') }}" class="btn btn-primary register-add"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Risk</a>
    @endif
</header>
<nav class="register-navigation" aria-label="Risk module pages">
    <a href="{{ route('racm.index') }}" @if($registerPage === 'criteria') aria-current="page" @endif>Assessment criteria</a>
    <a href="{{ route('risk.index') }}" @if($registerPage === 'register') aria-current="page" @endif>Register</a>
    <a href="{{ route('mitigations.index') }}" @if($registerPage === 'mitigation') aria-current="page" @endif>Mitigation actions</a>
    <a href="{{ route('risk.analysis') }}" @if($registerPage === 'analysis') aria-current="page" @endif>Analysis</a>
    <a href="{{ route('risk.closedrisks') }}" @if($registerPage === 'closed') aria-current="page" @endif>Closed risks</a>
    @if($registerPage === 'register')
    <button type="button" class="video-btn" id="learn_btn" data-toggle="modal" data-src="https://youtube.com/embed/M2qMFyvFPTI" data-target="#modal-learn-module"><i class="fa fa-video mr-1" aria-hidden="true"></i><span id="learn">Learn module</span></button>
    @endif
</nav>
