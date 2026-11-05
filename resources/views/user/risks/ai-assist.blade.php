<div class="alert alert-info" id="risk-ai-panel" data-url="{{ route('risk.ai.draft') }}" hidden
     role="region" aria-labelledby="risk-ai-title">
    <button type="button" class="close" id="risk-ai-close" aria-label="Close AI assistant"><span aria-hidden="true">&times;</span></button>
    <strong id="risk-ai-title">Risk AI Assist</strong>
    @if(config('risk_ai.enabled'))
    @if(config('risk_ai.mode') === 'demo')
    <span class="badge badge-warning ml-2">Template demo</span>
    @endif
    <p>Describe your concern below. Selected group, department, source and existing controls will also be sent for drafting. Review suggestions before applying. Proposed controls are not existing controls.</p>
    <label for="risk-ai-concern">Concern</label>
    <textarea id="risk-ai-concern" class="form-control" maxlength="10000" placeholder="Describe the process, concern and potential consequences"></textarea>
    <button type="button" class="btn btn-primary mt-2" id="risk-ai-generate">Prepare draft</button>
    <p id="risk-ai-message" role="status" class="mt-2"></p>
    <div id="risk-ai-results"></div>
    @else
    <p class="mb-0 mt-2">AI assistance is not available yet. Contact your administrator to enable it.</p>
    @endif
</div>
<script src="{{ asset('js/risk-ai.js') }}" defer></script>
