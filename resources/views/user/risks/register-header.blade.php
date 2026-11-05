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
