@extends('layouts.multiselect')
@section('content')
@include('shared.forms.risk-assets')
@include('shared.forms.assets')
@include('user.risks.review-assets')
<link rel="stylesheet" href="{{ asset('css/risk-tables.css') }}?v={{ filemtime(public_path('css/risk-tables.css')) }}">
<link rel="stylesheet" href="{{ asset('css/risk-detail-workspace.css') }}?v={{ filemtime(public_path('css/risk-detail-workspace.css')) }}">
<script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}">
</script>
@php
    $toneFor = function ($value) { return ['High' => 'high', 'Moderate' => 'moderate', 'Low' => 'low'][$value ?? ''] ?? 'neutral'; };
    $displayDate = function ($value) { if (!$value) return 'Not set'; try { return \Carbon\Carbon::parse($value)->format('d M Y'); } catch (\Exception $exception) { return 'Not set'; } };
    $completedActions = $risk->mitigations->filter(function ($action) { return (int) $action->progress >= 100 || $action->status === config('constants.risk_management.mitigation.closed'); })->count();
    $matrixValues = ['Low' => 1, 'Moderate' => 2, 'High' => 3];
    $impactValue = $matrixValues[$risk->impa ?? ''] ?? null;
    $likelihoodValue = $matrixValues[$risk->like ?? ''] ?? null;
@endphp
<div class="content risk-detail-page rd-workspace" id="risk-detail-workspace">
    <header class="rd-hero">
        <div class="rd-hero-top">
<a href="{{ route('risk.index') }}" class="rd-back">
<i class="fa fa-arrow-left" aria-hidden="true">
</i>Risk register</a>
<div class="rd-hero-actions">
<button type="button" class="btn rd-button" onclick="window.print()">
<i class="fa fa-print mr-1" aria-hidden="true">
</i>Print</button>
<a class="btn btn-primary rd-button" href="{{ route('risk.detail.pdf', $risk->Rid) }}">
<i class="fa fa-download mr-1" aria-hidden="true">
</i>Export PDF</a>
</div>
</div>
        <div class="rd-identity">
<span class="rd-record-id">{{ $risk->grp_id ?: 'Risk record' }}</span> <span class="rd-badge rd-tone-{{ $toneFor($risk->rank) }}" aria-label="Saved risk rating">{{ $risk->rank ?: 'Unassessed' }}</span>
<h1>{{ $risk->riskname }}</h1>
<div class="rd-context">
<span>{{ $risk->riskgroup ?: 'Group not assigned' }}</span>
<span>{{ $risk->department->departmentname ?? 'Department not assigned' }}</span>@if($risk->division)<span>{{ $risk->division->name }}</span>@endif @if($risk->area)<span>{{ $risk->area->name }}</span>@endif</div>
</div>
        <dl class="rd-summary">
<div>
<dt>Process stage</dt>
<dd>
<span class="rd-stage">{{ $risk->action_status ?: 'Not set' }}</span>
</dd>
</div>
<div>
<dt>Risk response</dt>
<dd>{{ $risk->risk_response ?: 'Not set' }}</dd>
</div>
<div>
<dt>Target date</dt>
<dd>{{ $displayDate($risk->action_target) }}</dd>
</div>
<div>
<dt>Mitigation actions</dt>
<dd>{{ $completedActions }} <small>of {{ $risk->mitigations->count() }} completed</small>
</dd>
</div>
</dl>
    </header>
    <nav class="rd-navigation" aria-label="Risk detail sections">
<a href="#risk-overview">Overview</a>
<a href="#risk-assessment">Assessment</a>
<a href="#risk-actions">Mitigation actions <span>{{ $risk->mitigations->count() }}</span>
</a>
<a href="#risk-history">Review history</a>
<a href="#risk-discussion">Discussion <span>{{ $risk_comments_count }}</span>
</a>
</nav>
    <div class="rd-layout">
        <div class="rd-main">
            <section class="rd-card" id="risk-overview" aria-labelledby="risk-overview-title">
<div class="rd-section-heading">
<div>
<span class="rd-eyebrow">RISK CONTEXT</span>
<h2 id="risk-overview-title">What could happen</h2>
</div>
<i class="fa fa-align-left" aria-hidden="true">
</i>
</div>
<div class="rd-description">@if($risk->riskdec){!! nl2br(e(strip_tags($risk->riskdec))) !!}@else<span class="rd-muted">No risk description has been recorded.</span>@endif</div>
<div class="rd-detail-pair">
<div>
<h3>Risk drivers</h3>
<p class="rd-prose">{{ $risk->riskdri ?: 'No risk drivers have been recorded.' }}</p>
</div>
<div>
<h3>Existing controls</h3>
<p class="rd-prose">{{ $risk->existing_controls ?: 'No existing controls have been recorded.' }}</p>
</div>
</div>
<dl class="rd-inline-facts">
<div>
<dt>Risk source</dt>
<dd>{{ $risk->riskscr ?: 'Not recorded' }}</dd>
</div>
<div>
<dt>Linked opportunities</dt>
<dd>@forelse($risk->opportunities as $opportunity)<span class="rd-opportunity">{{ $opportunity->dep_id }}</span>@empty<span class="rd-muted">None linked</span>@endforelse</dd>
</div>
</dl>
</section>
            <details class="rd-card rd-ai">
<summary>
<span>
<i class="fa fa-magic mr-2" aria-hidden="true">
</i>AI risk review</span>
<span class="rd-muted">Explore gaps and suggested controls</span>
</summary>@include('user.risks.review-assist', ['reviewRisk' => $risk])</details>
            <section class="rd-card" id="risk-actions" aria-labelledby="risk-actions-title">
<div class="rd-section-heading">
<div>
<span class="rd-eyebrow">RESPONSE & FOLLOW-UP</span>
<h2 id="risk-actions-title">Mitigation actions</h2>
</div>
<a class="rd-text-link" href="{{ route('mitigations.index', ['riskId' => $risk->Rid]) }}">Manage actions <i class="fa fa-arrow-right ml-1" aria-hidden="true">
</i>
</a>
</div>@if($risk->mitigations->count())<div class="rd-table-scroll" role="region" aria-label="Mitigation actions; scroll for more columns" tabindex="0">
<table class="table risk-module-table rd-action-table">
<thead>
<tr>
<th>Action</th>
<th>Priority</th>
<th>Due date</th>
<th>Progress</th>
<th>
<span class="sr-only">Open action</span>
</th>
</tr>
</thead>
<tbody>@foreach($risk->mitigations as $mitigation)<tr>
<td>
<a href="{{ route('mitigations.detail', $mitigation->id) }}">{{ $mitigation->action_name }}</a>
<small>{{ $mitigation->action_id }}</small>
</td>
<td>{{ ucfirst($mitigation->priority_level ?: 'Not set') }}</td>
<td>{{ $displayDate($mitigation->due_date) }}</td>
<td>@include('user.risks.mitigation._status')</td>
<td>
<a class="rd-text-link" href="{{ route('mitigations.detail', $mitigation->id) }}" aria-label="View action {{ $mitigation->action_name }}" title="View action"><i class="far fa-eye" aria-hidden="true"></i></a>
</td>
</tr>@endforeach</tbody>
</table>
</div>@else<div class="rd-empty">
<i class="fa fa-tasks" aria-hidden="true">
</i>
<strong>No mitigation actions yet</strong>
<p>Manage this risk's actions to assign responsibilities and track progress.</p>
</div>@endif</section>
        </div>
        <aside class="rd-sidebar" aria-label="Assessment and accountability">
            <section class="rd-card" id="risk-assessment" aria-labelledby="risk-assessment-title">
<div class="rd-section-heading">
<div>
<span class="rd-eyebrow">CURRENT ASSESSMENT</span>
<h2 id="risk-assessment-title">Risk assessment</h2>
</div>
</div>
<dl class="rd-assessment-facts">@foreach(['Impact' => $risk->impa, 'Likelihood' => $risk->like, 'Risk rating' => $risk->rank] as $label => $value)<div>
<dt>{{ $label }}</dt>
<dd>
<span class="rd-badge rd-tone-{{ $toneFor($value) }}">{{ $value ?: 'Unassessed' }}</span>
</dd>
</div>@endforeach</dl>
<div class="rd-matrix-heading">
<span>3 × 3 assessment matrix</span>
<small>Impact →</small>
</div>
<div class="rd-matrix" role="img" aria-label="3 by 3 risk matrix. Current impact: {{ $risk->impa ?: 'unassessed' }}. Current likelihood: {{ $risk->like ?: 'unassessed' }}. Likelihood increases downward; impact increases to the right.">@foreach([1,2,3] as $likelihood) @foreach([1,2,3] as $impact) @php $score = $likelihood * $impact; $tone = $score <= 2 ? 'low' : ($score <= 4 ? 'moderate' : 'high'); $current = $impactValue === $impact && $likelihoodValue === $likelihood; @endphp<div class="rd-matrix-cell rd-tone-{{ $tone }} {{ $current ? 'is-current' : '' }}" aria-hidden="true">
<span>{{ $score }}</span>@if($current)<i class="fa fa-map-marker-alt" aria-hidden="true">
</i>@endif</div>@endforeach @endforeach</div>
<p class="rd-matrix-note">Likelihood increases from top to bottom. @if($impactValue && $likelihoodValue)The marker shows the recorded assessment.@else Select impact and likelihood when assessing this risk.@endif The risk rating above is the saved record.</p>
</section>
            <section class="rd-card" aria-labelledby="risk-accountability-title">
<div class="rd-section-heading">
<div>
<span class="rd-eyebrow">ACCOUNTABILITY</span>
<h2 id="risk-accountability-title">Owners & reviewer</h2>
</div>
</div>
<h3>Risk owners</h3>
<div class="rd-people">@forelse($riskOwnerData as $owner)<div class="rd-person">
<span class="rd-person-avatar" aria-hidden="true">{{ mb_substr($owner['name'],0,1) }}</span>
<div>
<strong>{{ $owner['name'] }}</strong>@if($owner['employeeid'] ?? null)<small>{{ $owner['employeeid'] }}</small>@endif</div>
</div>@empty<p class="rd-muted">No owner assigned.</p>@endforelse</div>
<div class="rd-reviewer">
<h3>Risk reviewer</h3>
<p>{{ $risk->riskreviewer->name ?? 'Not assigned' }}</p>
</div>
</section>
            <section class="rd-card" aria-labelledby="risk-escalations-title">
<h2 id="risk-escalations-title">Escalation record</h2>
<dl class="rd-assessment-facts">
<div>
<dt>Escalations</dt>
<dd>{{ $risk->number_of_escalated_times ?? 0 }}</dd>
</div>
<div>
<dt>Last escalated</dt>
<dd>{{ $displayDate($risk->last_escalated_time) }}</dd>
</div>
</dl>
</section>
        </aside>
    </div>
    <section class="rd-card" id="risk-history" aria-labelledby="risk-history-title">
<div class="rd-section-heading">
<div>
<span class="rd-eyebrow">AUDIT TRAIL</span>
<h2 id="risk-history-title">Review history</h2>
</div>
<button type="button" class="btn rd-button btn-toggle-history" value="load" aria-expanded="false" aria-controls="risk-detail-history-table">
<i class="fa fa-history mr-1" aria-hidden="true">
</i>Load periodic reviews</button>
</div>
<div class="rd-milestones">
<div>
<span class="rd-milestone-dot" aria-hidden="true">
</span>
<div>
<strong>Risk recorded</strong>
<p>{{ $risk->postedby == '' ? 'Administrator' : ($risk->postedByRisk->name ?? 'Not recorded') }}<span>{{ $displayDate($risk->created_at) }}</span>
</p>
</div>
</div>
<div>
<span class="rd-milestone-dot" aria-hidden="true">
</span>
<div>
<strong>Initial review</strong>
<p>{{ $risk->approvedByRisk->name ?? 'Reviewer not recorded' }}<span>{{ $displayDate($risk->review_date) }}</span>
</p>
</div>
</div>
</div>
<div id="risk-detail-history-table" class="table-risk-history rd-table-scroll" style="display:none"><table class="risk-module-table table table-bordered table-custom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Reviewed by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($riskHistories))
                                    @foreach($riskHistories as $index => $riskHistory)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left">
                                            @if(
                                                $riskHistory->impa == NULL
                                                && $riskHistory->like == NULL
                                                && $riskHistory->rank== NULL
                                            )
                                                --
                                            @endif
                                            @if($riskHistory->impa != NULL && $riskHistory->action != NULL)
                                                <span>
                                                    Impact changed from
                                                    @if($riskHistory->impa == "High")
                                                        <span class="status status-red">{{$riskHistory->impa }}</span>
                                                    @elseif($riskHistory->impa == "Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa }}</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->impa_updated == "High")
                                                        <span class="status status-red">{{$riskHistory->impa_updated }}</span>
                                                    @elseif($riskHistory->impa_updated == "Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa_updated }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa_updated }}</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->impa != NULL && $riskHistory->action == NULL)
                                                <span>
                                                    Impact changed from
                                                    @if($riskHistory->impa=="High")
                                                        <span class="status status-red">{{$riskHistory->impa }}</span>
                                                    @elseif($riskHistory->impa=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->impa }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->impa }}</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->like != NULL && $riskHistory->action != NULL)
                                                <br><br>
                                                <span>
                                                    Likelihood changed from
                                                    @if($riskHistory->like=="High")
                                                        <span class="status status-red">{{$riskHistory->like }}</span>
                                                    @elseif($riskHistory->like=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like }}</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->like_updated=="High")
                                                        <span class="status status-red">{{$riskHistory->like_updated }}</span>
                                                    @elseif($riskHistory->like_updated=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like_updated }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like_updated }}</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->like != NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>
                                                    Likelihood changed from
                                                    @if($riskHistory->like=="High")
                                                        <span class="status status-red">{{$riskHistory->like }}</span>
                                                    @elseif($riskHistory->like=="Moderate")
                                                        <span class="status status-warning">{{$riskHistory->like }}</span>
                                                    @else
                                                        <span class="status status-success">{{$riskHistory->like }}</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->rank!=NULL && $riskHistory->action!=NULL)
                                                <br><br>
                                                <span>
                                                    Risk changed from
                                                    @if($riskHistory->rank == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                    to
                                                    @if($riskHistory->rank_updated == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank_updated == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                </span>
                                            @elseif($riskHistory->rank!=NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>
                                                    Risk changed from
                                                    @if($riskHistory->rank == 3)
                                                        <span class="status status-red">High</span>
                                                    @elseif($riskHistory->rank == 2)
                                                        <span class="status status-warning">Moderate</span>
                                                    @else
                                                        <span class="status status-success">Low</span>
                                                    @endif
                                                </span>
                                            @endif

                                            @if($riskHistory->mitigating_action!=NULL && $riskHistory->action!=NULL)
                                                <br><br>
                                                <span>
                                                    Mitigating Action changed from <span>{{$riskHistory->mitigating_action}} </span>to <span>{{$riskHistory->mitigating_action_updated}}</span>
                                                </span>
                                            @elseif($riskHistory->mitigating_action!=NULL && $riskHistory->action==NULL)
                                                <br><br>
                                                <span>Mitigating Action changed from <span> {{$riskHistory->mitigating_action}}</span></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ date('d-m-Y',strtotime($riskHistory->created_at)) }}</td>
                                        <td class="text-danger font-weight-bold">{{ $riskHistory->riskOwner ? $riskHistory->riskOwner->name : '--' }}</td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-danger">No matching records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table></div>@if($risk->disapprovereason)<div class="rd-review-note"><h3>Disapproval reason</h3><p class="rd-prose">{{ $risk->disapprovereason }}</p></div>@endif</section>
            <section class="block block-rounded rd-card rd-discussion" id="risk-discussion" aria-label="Risk discussion">
                <ul class="nav nav-tabs nav-tabs-alt align-items-center comment-padding" data-toggle="tabs" role="tablist" id="myTab">
                    <li class="nav-item">
                        <a class="nav-link" href="#ecom-product-comments" data-toggle="tab" id="totalcomments">
                         <i class="far fa-comment-dots" style="font-size:12px;"></i>
                          Comments ({{$risk_comments_count}})
                        </a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <!-- Comments -->
                    <div class="tab-pane pull-x font-size-sm comment-padding active" id="ecom-product-comments" role="tabpanel">
                        @foreach($risk_comments as $comment)
                            @if(!$comment->is_deleted && $comment->parent_id == 0 && ($comment->admin || $comment->user))
                                @include('user.risks._comment', ['comment' => $comment])
                            @endif
                        @endforeach

                        <form class="risk-module-form" id='comment_form' method="post" action="{{ route('riskcomment.store') }}"
                            enctype="multipart/form-data">
                            <div class="form-group mt-2" id="comment_div">
                                <div class="input-group">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="comment"
                                            placeholder="Write a comment..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeComment()">
                                            <i class="fas fa-paper-plane" style=""></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#comment'), {
                                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                        'numberedList', 'blockQuote', 'undo', 'redo'
                                    ],
                                }).then(commentEditor => {
                                    window.commentEditor = commentEditor;
                                }).catch(error => {
                                    console.error(error);
                                });
                        </script>
                        <form id='reply_form' method="post" action="{{ route('riskcomment.store') }}"
                            enctype="multipart/form-data" class="risk-module-form d-none">
                            <div class="form-group mt-2" id="reply_div">
                                <div class="input-group">
                                    <input type="hidden" name="comment_id" id="comment_id" value="">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="reply"
                                            placeholder="Write a reply..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeReply()">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#reply'), {
                                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                        'numberedList', 'blockQuote', 'undo', 'redo'
                                    ],

                                }).then(replyEditor => {
                                    window.replyEditor = replyEditor;
                                }).catch(error => {
                                    console.error(error);
                                });
                        </script>
                        <form id='editcomment_form' method="post"
                            enctype="multipart/form-data" class="risk-module-form d-none">
                            <div class="form-group mt-2" id="editcomment_div">
                                <div class="input-group">
                                    <input type="hidden" name="editcomment_id" id="editcomment_id" value="">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="editcomment"
                                            placeholder="Edit comment..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeEditComment()">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#editcomment'), {
                                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                        'numberedList', 'blockQuote', 'undo', 'redo'
                                    ],

                                }).then(editCommentEditor => {
                                    window.editCommentEditor = editCommentEditor;
                                }).catch(error => {
                                    console.error(error);
                                });
                        </script>
                    </div>
                    <!-- END Comments -->
                </div>
            </section>

</div>
    <!-- END Page Content -->
    <script>
        $(function () {
            $('.btn-toggle-history').on('click', function () {
                const currentValue = $(this).val();
                $(this).attr('aria-expanded', String(currentValue === 'load'));
                $('.table-risk-history').slideToggle('slow')

                if (currentValue == 'load') {
                    $(this).val('hide');
                    $(this).html('<span><i class="fa fa-eye-slash"></i></span><span class="ml-1">Hide Periodic Reviews</span>');
                } else {
                    $(this).val('load');
                    $(this).html('<span><i class="fa fa-eye"></i></span><span class="ml-1">Load Periodic Reviews</span>');
                }
            })
        });
    </script>
    <!--**********************************************Like Comment Reply START***********************************-->
    @php
        $userId = App\User::where('email',Auth::User()->email)->value('id');
    @endphp
    <script type="text/javascript">
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let comment_form_selector = document.getElementById('comment_form');
        let reply_form_selector = document.getElementById('reply_form');
        let reply_comment_id = document.getElementById("comment_id");
        let editcomment_form_selector = document.getElementById('editcomment_form');
        let editcomment_id = document.getElementById("editcomment_id");
        const risk_id = "{{ $risk->Rid }}";
        const userId = "{{$userId}}";
        // Hide reply form and show comment form on document click
        document.addEventListener('click', function(event) {
            if(!reply_comment_id){
                reply_comment_id = document.getElementById("comment_id");
                reply_comment_id.value = '';
            }else{
                reply_comment_id.value = '';
            }
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
                editcomment_id.value = '';
            }else{
                editcomment_id.value = '';
            }
            replyEditor.setData('');
            editCommentEditor.setData('');
            comment_form_selector.classList.remove('d-none');
            reply_form_selector.classList.add('d-none');
            editcomment_form_selector.classList.add('d-none');
        });
        // Prevent hiding reply form on clicking inside it
        document.getElementById('reply_form').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        document.getElementById('editcomment_form').addEventListener('click', function(event) {
            event.stopPropagation();
        });
        function storeComment() {
            let store_comment_url =  window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/add`;

            if(!reply_comment_id){
                reply_comment_id = document.getElementById("comment_id");
                reply_comment_id.value = '';
            }else{
                reply_comment_id.value = '';
            }
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
                editcomment_id.value = '';
            }else{
                editcomment_id.value = '';
            }
            $("form#comment_form").off("submit").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#comment').val(),
                        risk_id: risk_id,
                        reply_id: $('#comment_id').val(),
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.success){
                            commentEditor.setData('');
                            replyEditor.setData('');
                            editCommentEditor.setData('');
                            addCommentToDOM(response.data);
                            $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                                Comments (${response.data.totalcomments})`);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
        }

        function reply(id) {
            event.stopPropagation();
            if(!reply_comment_id){
                reply_comment_id = document.getElementById("comment_id");
            }
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
                editcomment_id.value = '';
            }else{
                editcomment_id.value = '';
            }
            commentEditor.setData('');
            replyEditor.setData('');
            editCommentEditor.setData('');
            reply_comment_id.value = id;
            editcomment_form_selector.classList.add('d-none');
            comment_form_selector.classList.add('d-none');
            reply_form_selector.classList.remove('d-none');
        }

        function storeReply() {
            let store_comment_url = window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/add`;
            $("form#reply_form").off("submit").on("submit", function(e)  {
                e.preventDefault();
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#reply').val(),
                        risk_id: risk_id,
                        reply_id: $('#comment_id').val(),
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.success){
                            commentEditor.setData('');
                            editCommentEditor.setData('');
                            addReplyToDOM(response.data);
                            $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                                Comments (${response.data.totalcomments})`);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
            reply_comment_id = null;
            reply_form_selector.classList.add('d-none');
            comment_form_selector.classList.remove('d-none');
        }

        function deleteComment(id) {
            let delete_comment_url = window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/delete/${id}`;
            $.ajax({
                url: delete_comment_url,
                type: 'GET',
                headers: {
                    'accept': 'application/json'
                },
                success: function(response) {
                    if(response.success){
                        $(`#comment${id}`).remove();
                        $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                            Comments (${response.totalcomments})`);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });

        }

        function deleteReply(id) {
            let delete_reply_url = window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/delete-reply/${id}`;
            $.ajax({
                url: delete_reply_url,
                type: 'GET',
                headers: {
                    'accept': 'application/json'
                },
                success: function(response) {
                    if(response.success){
                        $(`#comment-reply${id}`).remove();
                        $('#totalcomments').html(`<i class="far fa-comment-dots" style="font-size:12px;"></i>
                            Comments (${response.totalcomments})`);
                    }
                },
                error: function(err) {
                    console.log(err);

                }

            });

        }
        function addCommentToDOM(comment) {
            if (!comment.is_reply) {
                $('#ecom-product-comments').find('#comment_form').before(`
                    <div class="media push" style="padding-bottom:6px;margin-bottom:0;" id="comment${comment.id}">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="${comment.profile_picture}" alt="">
                        </a>
                        <div class="media-body">
                            <a class="font-w600" href="javascript:void(0)">${comment.user_name}</a>
                            <span id="commenttext${comment.id}"></span>
                            ${comment.user_id == userId ? `<a class="mr-1" href="javascript:void(0)" onclick="editComment('${comment.id}','${comment.comment}')"><span style="font-size:11px;">Edit</span></a><a class="mr-1" href="javascript:void(0)" onclick="deleteComment('${comment.id}')"><span style="font-size:11px;">Delete</span></a>` : ''}
                            <a class="mr-1" onclick="reply('${comment.id}')"><span style="font-size:11px;color:#5179d6;cursor:pointer;">Reply</span></a>
                            <span class="text-muted"><em><span style="font-size:11px;" id="updatedat${comment.id}">${comment.created_at}</span></em></span>
                            <div id="replysection${comment.id}"></div>
                        </div>
                    </div>
                `);
                $(`#commenttext${comment.id}`).append(decodeHTML(comment.comment));
            }
        }
        function addReplyToDOM(comment) {
            if (comment.is_reply) {
                $(`#ecom-product-comments #comment${comment.parent_id}`).find(`#replysection${comment.parent_id}`).append(`
                    <a class="mr-3" href="javascript:void(0)">
                        <img class="img-avatar img-avatar32" src="${comment.profile_picture}" alt="">
                    </a>
                    <div class="media-body">
                        <a class="font-w600" href="javascript:void(0)">${comment.user_name}</a>
                        <span id="commenttext${comment.id}"></span>
                        ${comment.user_id == userId ? `<a class="mr-1" href="javascript:void(0)" onclick="editComment('${comment.id}','${comment.comment}')"><span style="font-size:11px;">Edit</span></a><a class="mr-1" href="javascript:void(0)" onclick="deleteReply('${comment.id}')"><span style="font-size:11px;">Delete</span></a>` : ''}
                        <span class="text-muted"><em><span style="font-size:11px;" id="updatedat${comment.id}">${comment.created_at}</span></em></span>
                    </div>
                `).addClass(['media','mt-2']).css({'font-size':'13px','padding-bottom':'6px','margin-bottom':'0'}).attr('id',`comment-reply${comment.id}`);
                $(`#commenttext${comment.id}`).append(decodeHTML(comment.comment));
                $(`#comment-reply${comment.id}`).after(`
                    <div id="replysection${comment.parent_id}"></div>
                `);
            }
        }
        function decodeHTML(html) {
            var txt = document.createElement('textarea');
            txt.innerHTML = html;
            return txt.value;
        }

        //Edit functionality
        function editComment(id,commentvalue) {
            event.stopPropagation();
            try {
                commentvalue = JSON.parse(commentvalue);
            } catch (error) {
                // console.log(error.getMessage());
            }
            editCommentEditor.setData(commentvalue);
            if(!editcomment_id){
                editcomment_id = document.getElementById("editcomment_id");
            }
            editcomment_id.value = id;
            editcomment_form_selector.classList.remove('d-none');
            comment_form_selector.classList.add('d-none');
            reply_form_selector.classList.add('d-none');
        }
        function storeEditComment() {
            let store_comment_url =  window.location.protocol + "//" + window.location.host +`/user/riskmgmt/comment/edit-comment`;
            $("form#editcomment_form").off("submit").on("submit", function(e) {
                e.preventDefault();
                var cid = $('#editcomment_id').val();
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#editcomment').val(),
                        risk_id: risk_id,
                        comment_id: cid,
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.success){
                            commentEditor.setData('');
                            replyEditor.setData('');
                            editCommentEditor.setData('');
                            $(`#commenttext${cid}`).html(response.data.comment);
                            $(`#updatedat${cid}`).text(response.data.created_at);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
        }
    </script>
    <!--**********************************************Like Comment Reply END***********************************-->

@endsection
