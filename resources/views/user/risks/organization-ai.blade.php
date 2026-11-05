@extends('layouts.backend')
@section('content')
@include('shared.forms.assets')
<link rel="stylesheet" href="{{ asset('css/risk-register.css') }}?v={{ filemtime(public_path('css/risk-register.css')) }}">
<link rel="stylesheet" href="{{ asset('css/risk-organization.css') }}?v={{ filemtime(public_path('css/risk-organization.css')) }}">
<script src="{{ asset('js/risk-organization.js') }}?v={{ filemtime(public_path('js/risk-organization.js')) }}" defer></script>
<div class="content cgbod-form risk-register organization-ai">
    @include('user.risks.register-header', ['registerPage' => 'organization', 'registerTitle' => 'Organization AI', 'registerDescription' => 'Give your assistant the business context it needs, and improve its guidance through reviewed feedback.'])
    @if(Session::has('msg'))<div class="alert alert-success mt-3" role="status">{{ Session::get('msg') }}</div>@endif
    @if(isset($errors) && $errors->any())<div class="alert alert-danger mt-3">{{ $errors->first() }}</div>@endif
    @if(!$ready)
    <section class="org-card"><h2>One-time setup required</h2><p>Organization learning needs a database update on the hosting server. Existing risk AI can continue without saved organization context.</p>
        @if($canManage)<p>Run this from the Laravel project directory:</p><code class="org-setup-command">php artisan migrate --path=database/migrations/2026_11_05_200000_create_risk_ai_organization_learning_tables.php --force</code>@else<p>Ask your administrator to apply the organization AI migration.</p>@endif
    </section>
    @else
    <div class="org-status"><span>{{ $profile && $profile->enabled ? 'Organization context enabled' : 'Organization context not enabled' }}</span><span>{{ $profile ? 'Profile version '.$profile->revision : 'No approved profile yet' }}</span><span>{{ config('risk_ai.enabled') && config('risk_ai.mode') === 'openai' ? 'Live AI mode' : 'Demo / offline mode · saved context is used by live AI' }}</span></div>
    <section class="org-card"><div class="org-heading"><div><h2>Organization profile</h2><p>Describe verified facts and approved criteria. Future AI answers can then reflect your actual operations and priorities.</p></div>@if(!$canManage)<span>Read only</span>@endif</div>
    @if($canManage)
        @php $profileOld = session()->hasOldInput('revision'); @endphp
        <form method="post" data-organization-profile action="{{ route('risk.ai.organization.profile') }}">@csrf<input type="hidden" name="revision" value="{{ old('revision', $profile ? $profile->revision : 0) }}">
        <div class="org-fields">
        @foreach(['company_name' => 'Organization name', 'industry' => 'Industry / sector'] as $field => $label)<label>{{ $label }}<input type="text" name="{{ $field }}" maxlength="190" value="{{ $profileOld ? old($field, $content[$field] ?? '') : ($content[$field] ?? '') }}" @if($field === 'company_name') required @endif></label>@endforeach
        @foreach(['operations' => 'Products, services and key processes', 'locations' => 'Sites, regions and operating environment', 'objectives' => 'Business objectives and priorities', 'risk_appetite' => 'Approved risk appetite and escalation expectations', 'assessment_criteria' => 'Approved impact / likelihood criteria (3 × 3)', 'terminology' => 'Organization terminology and abbreviations'] as $field => $label)<label>{{ $label }}<textarea name="{{ $field }}" maxlength="3000" rows="4">{{ $profileOld ? old($field, $content[$field] ?? '') : ($content[$field] ?? '') }}</textarea></label>@endforeach
        <label class="org-wide">Source / approval reference<input type="text" name="source_reference" maxlength="500" value="{{ $profileOld ? old('source_reference', $profile->source_reference ?? '') : ($profile->source_reference ?? '') }}" placeholder="e.g. Approved risk policy, version 3, dated 2026-10-01"></label>
        </div><input type="hidden" name="enabled" value="0"><label class="org-check"><input type="checkbox" name="enabled" value="1" @if(old('enabled', $profile && $profile->enabled)) checked @endif>Use this approved profile and relevant approved notes in future AI requests</label>
        <p class="org-note">Saving creates a new approved version. Disabling stops future use of the profile and notes. The calculation matrix and saved risk records stay unchanged.</p><button class="btn btn-primary" type="submit">Save approved profile</button></form>
    @else
        @if(!$profile)<p>No approved profile yet. Ask an organization AI manager to complete it.</p>@else<div class="org-profile-readonly">@foreach($content as $field => $value)@if($value)<div><h3>{{ ucwords(str_replace('_', ' ', $field)) }}</h3><p>{{ $value }}</p></div>@endif
@endforeach</div>@endif
    @endif</section>
    <section class="org-card"><div class="org-heading"><div><h2>Approved knowledge</h2><p>Facts, lessons and policy guidance with a source. Only relevant, unexpired notes are supplied to the AI.</p></div><span>Latest {{ count($notes) }} notes</span></div>
        @forelse($notes as $note)<article class="org-note-card"><div class="org-heading"><strong>K{{ $note->id }} · {{ $note->title }}</strong><span>{{ $note->status === 'withdrawn' ? 'Withdrawn' : ($note->review_by && $note->review_by < now()->format('Y-m-d') ? 'Review date passed · excluded' : 'Approved') }}</span></div><p>{{ $note->content }}</p><small>Source: {{ $note->source_reference }} @if($note->review_by) · Review by {{ $note->review_by }} @endif @if($note->keywords) · Topics: {{ $note->keywords }} @else · General guidance @endif</small>
        @if($canManage && $note->status === 'approved')<form method="post" action="{{ route('risk.ai.organization.withdraw', $note->id) }}">@csrf<button type="submit" class="org-link-button">Withdraw from AI context</button></form>@endif</article>@empty<p>No approved knowledge notes yet.</p>@endforelse
        @if($canManage)<details class="org-editor"><summary>Add approved knowledge</summary><form method="post" action="{{ route('risk.ai.organization.notes') }}">@csrf
            <div class="org-fields"><label class="org-wide">Title<input name="title" maxlength="190" required></label><label class="org-wide">Verified fact or approved guidance<textarea name="content" rows="4" maxlength="2000" required></textarea></label><label>Relevant topics, separated by commas<input name="keywords" maxlength="500" placeholder="e.g. supplier, procurement; leave blank for general guidance"></label><label>Review by (optional)<input type="date" name="review_by"></label><label class="org-wide">Source / approval reference<input name="source_reference" maxlength="500" required></label></div><button type="submit" class="btn btn-primary">Add approved knowledge</button></form></details>@endif
    </section>
    <section class="org-card"><div class="org-heading"><div><h2>{{ $canManage ? 'Feedback to review' : 'Your feedback' }}</h2><p>Feedback is kept separate from approved knowledge. AI answers and user comments are not learned automatically.</p></div></div>
        @forelse($feedback as $item)<details class="org-feedback-item"><summary>{{ ucfirst(str_replace('_', ' ', $item->rating)) }} · {{ $item->topic }} <small>{{ $item->status }} · {{ $item->created_at }}</small></summary><div class="org-feedback-body"><h3>User feedback</h3><p>{{ $item->comment ?: 'Marked helpful without an additional comment.' }}</p><details><summary>Original assistant answer</summary><div class="org-original-answer">@include('user.risks.organization-ai-answer')</div></details>
        @if($canManage)<form method="post" data-organization-feedback-review action="{{ route('risk.ai.feedback.review', $item->id) }}">@csrf<label>Decision<select name="decision"><option value="acknowledge">Acknowledge feedback</option><option value="dismiss">Dismiss</option><option value="approve">Approve corrected knowledge below</option></select></label><details class="org-editor"><summary>Corrected knowledge for approval</summary><p>Write the verified correction. The original AI answer is not copied into shared knowledge.</p><div class="org-fields"><label class="org-wide">Title<input name="title" maxlength="190"></label><label class="org-wide">Approved fact / correction<textarea name="content" maxlength="2000" rows="4"></textarea></label><label>Relevant topics (comma-separated)<input name="keywords" maxlength="500"></label><label>Review by<input type="date" name="review_by"></label><label class="org-wide">Source / approval reference<input name="source_reference" maxlength="500"></label></div></details><button class="btn btn-primary" type="submit">Save review decision</button></form>@endif
        </div></details>@empty<p>No feedback to display.</p>@endforelse
        @if($feedback){{ $feedback->links() }}@endif
    </section>
    @endif
</div>
@endsection
