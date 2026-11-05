<section class="risk-review-assist" data-review-url="{{ route('risk.ai.draft') }}" data-review-context="{{ json_encode(['concern' => mb_substr(strip_tags($reviewRisk->riskname ?: 'Risk review'), 0, 190), 'risk_description' => mb_substr(strip_tags($reviewRisk->riskdec ?? ''), 0, 10000), 'risk_drivers' => mb_substr(strip_tags($reviewRisk->riskdri ?? ''), 0, 10000), 'existing_controls' => mb_substr(strip_tags($reviewRisk->existing_controls ?? ''), 0, 10000), 'risk_group' => $reviewRisk->riskgroup, 'risk_area' => $reviewRisk->department ? $reviewRisk->department->departmentname : '']) }}">
    <div class="risk-review-heading"><div><h3><i class="fa fa-magic mr-2" aria-hidden="true"></i>Risk review assistant</h3><p>Check information gaps, explore controls, and prepare reviewer questions.</p></div>
    @if(config('risk_ai.enabled'))
    <button type="button" class="btn btn-primary" data-risk-review>Review this risk</button>
    @endif
    </div>
    <small>{{ !config('risk_ai.enabled') ? 'AI assistance is not enabled. Contact your administrator.' : (config('risk_ai.mode') === 'demo' ? 'Checklist mode · no AI model is called.' : 'AI suggestions · review before use.') }} Suggestions do not change the record or its rating.</small>
    <div class="risk-review-status" role="status" aria-live="polite"></div>
    <div class="risk-review-results" hidden></div>
</section>
