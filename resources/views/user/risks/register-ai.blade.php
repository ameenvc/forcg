<section id="risk-register-ai" class="register-ai-modal" role="dialog" aria-modal="true" tabindex="-1" data-url="{{ route('risk.ai.draft') }}" hidden aria-labelledby="register-ai-title">
    <div class="register-ai-dialog risk-review-assist">
    <button type="button" id="risk-register-ai-close" class="register-ai-close" aria-label="Close register AI assistant">&times;</button>
    <div class="risk-review-heading"><div><h3 id="register-ai-title">Review this register</h3><p>Find information gaps, summarize shared themes, and plan follow-up checks for the risks matching your filters.</p></div>
    @if(config('risk_ai.enabled'))<button type="button" id="risk-register-ai-run" class="btn btn-primary">Review matching risks</button>@endif
    </div>
    <small>{{ !config('risk_ai.enabled') ? 'AI assistance is not enabled. Contact your administrator.' : (config('risk_ai.mode') === 'demo' ? 'Checklist mode · no AI model is called.' : 'AI-generated suggestions · verify before use.') }} Reviews include up to 50 matching risks in the current sort order. Only register fields are reviewed; records and ratings stay unchanged.</small>
    <p class="register-ai-scope" id="risk-register-ai-scope"></p>
    <div id="risk-register-ai-status" class="risk-review-status" role="status" aria-live="polite"></div>
    <div id="risk-register-ai-results" class="risk-review-results" hidden></div>
    </div>
</section>
