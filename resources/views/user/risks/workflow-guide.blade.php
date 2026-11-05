<nav class="workflow-guide" aria-label="Risk management flow">
    <a href="{{ route('racm.index') }}" @if($workflowStep === 'criteria') aria-current="step" @endif><span>01</span><div><strong>Define criteria</strong><small>Set thresholds · review · approve</small></div></a>
    <a href="{{ route('risk.index') }}"><span>02</span><div><strong>Assess risks</strong><small>Use the approved 3 × 3 matrix</small></div></a>
    <a href="{{ route('mitigations.index') }}" @if($workflowStep === 'mitigation') aria-current="step" @endif><span>03</span><div><strong>Manage actions</strong><small>Assign · track · review closure</small></div></a>
    <a href="{{ route('risk.analysis') }}"><span>04</span><div><strong>Review outcomes</strong><small>Monitor the overall risk profile</small></div></a>
</nav>
