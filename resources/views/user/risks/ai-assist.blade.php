<div class="risk-ai-panel" id="risk-ai-panel" data-url="{{ route('risk.ai.draft') }}" hidden
     role="dialog" aria-modal="true" aria-labelledby="risk-ai-title">
    <div class="risk-ai-dialog-body">
    <div class="risk-ai-panel-header">
        <span class="risk-ai-symbol" aria-hidden="true"><i class="fa fa-magic"></i></span>
        <div><strong id="risk-ai-title">Build a better risk statement</strong><small>Start with an idea. Leave with a draft you can use.</small></div>
        <button type="button" class="close" id="risk-ai-close" aria-label="Close AI assistant"><span aria-hidden="true">&times;</span></button>
    </div>
    @if(config('risk_ai.enabled'))
    <div class="risk-ai-mode-note">
        @if(config('risk_ai.mode') === 'demo')
        <span class="badge badge-warning">Example library</span> Live AI is not connected. Explore curated examples, then adapt them to your business.
        @else
        <span class="badge badge-primary">AI mode</span> Suggestions use the topic and selected form context. Review them before use.
        @endif
    </div>
    <ol class="risk-ai-flow" aria-label="Drafting steps"><li>1. Describe</li><li>2. Choose a risk</li><li>3. Review &amp; use</li></ol>
    <div id="risk-ai-topic-area">
    <label for="risk-ai-concern">What topic or situation would you like to assess?</label>
    <textarea id="risk-ai-concern" class="form-control" maxlength="10000" placeholder="e.g. Geopolitical uncertainty affecting our imported raw materials"></textarea>
    <div class="risk-ai-examples" aria-label="Example topics">
        <span>Try a topic:</span>
        <button type="button" data-risk-topic="Geopolitical uncertainty">Geopolitical</button>
        <button type="button" data-risk-topic="Single supplier dependency">Supplier dependency</button>
        <button type="button" data-risk-topic="Cybersecurity">Cybersecurity</button>
    </div>
    <div class="risk-ai-toolbar"><button type="button" class="btn btn-primary" id="risk-ai-generate">Find risk ideas <i class="fa fa-arrow-right ml-1" aria-hidden="true"></i></button></div>
    </div>
    <p id="risk-ai-message" role="status" aria-live="polite" class="mt-2"></p>
    <div id="risk-ai-ideas" class="risk-ai-ideas"></div>
    <div id="risk-ai-editor" class="risk-ai-editor" hidden>
        <div class="risk-ai-editor-heading"><strong>Review your draft</strong><button type="button" id="risk-ai-back" class="risk-ai-back">Choose another risk</button></div>
        <label for="risk-ai-draft-name">Suggested risk name</label>
        <input type="text" id="risk-ai-draft-name" class="form-control" maxlength="190">
        <label for="risk-ai-draft-description">Risk description</label>
        <textarea id="risk-ai-draft-description" class="form-control" maxlength="10000"></textarea>
        <label for="risk-ai-draft-drivers">Potential drivers <small>One per line</small></label>
        <textarea id="risk-ai-draft-drivers" class="form-control" maxlength="40000"></textarea>
        <details class="risk-ai-support"><summary>Suggested actions &amp; questions to consider</summary><div id="risk-ai-results"></div></details>
        <p class="risk-ai-note">Only the name, description and drivers will be filled. Your existing controls, ratings and reviewer stay as entered.</p>
        <button type="button" class="btn btn-primary" id="risk-ai-apply-all" hidden>Use this draft in the form <i class="fa fa-check ml-1" aria-hidden="true"></i></button>
    </div>
    @else
    <p class="mb-0 mt-2">AI assistance is not available yet. Contact your administrator to enable it.</p>
    @endif
    </div>
</div>
<script src="{{ asset('js/risk-ai.js') }}?v={{ filemtime(public_path('js/risk-ai.js')) }}" defer></script>
