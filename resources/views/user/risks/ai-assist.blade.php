<div class="risk-ai-panel" id="risk-ai-panel" data-url="{{ route('risk.ai.draft') }}" hidden
     role="region" aria-labelledby="risk-ai-title">
    <div class="risk-ai-panel-header">
        <span class="risk-ai-symbol" aria-hidden="true"><i class="fa fa-magic"></i></span>
        <div><strong id="risk-ai-title">Risk AI Assist</strong><small>A starting point for your assessment</small></div>
        <button type="button" class="close" id="risk-ai-close" aria-label="Close AI assistant"><span aria-hidden="true">&times;</span></button>
    </div>
    @if(config('risk_ai.enabled'))
    @if(config('risk_ai.mode') === 'demo')
    <span class="badge badge-warning ml-2">Template demo</span>
    @endif
    <p>Describe your concern below. Selected group, department, source and existing controls will also be sent for drafting. Review suggestions before applying. Proposed controls are not existing controls.</p>
    <label for="risk-ai-concern">What are you concerned about?</label>
    <textarea id="risk-ai-concern" class="form-control" maxlength="10000" placeholder="Describe the process, concern and potential consequences"></textarea>
    <div class="risk-ai-toolbar">
        <button type="button" class="btn btn-primary" id="risk-ai-generate"><i class="fa fa-magic mr-1" aria-hidden="true"></i> Prepare draft</button>
        <button type="button" class="btn btn-outline-primary" id="risk-ai-apply-all" hidden>Apply all suggested fields</button>
    </div>
    <p id="risk-ai-message" role="status" class="mt-2"></p>
    <div id="risk-ai-results"></div>
    @else
    <p class="mb-0 mt-2">AI assistance is not available yet. Contact your administrator to enable it.</p>
    @endif
</div>
<script src="{{ asset('js/risk-ai.js') }}?v=2" defer></script>
