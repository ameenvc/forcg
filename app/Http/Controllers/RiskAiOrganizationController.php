<?php

namespace App\Http\Controllers;

use App\Services\RiskOrganizationKnowledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class RiskAiOrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa', 'throttle:30,1']);
    }

    private function manager(RiskOrganizationKnowledge $knowledge)
    {
        abort_unless(Gate::allows('manage-risk-ai-knowledge'), 403);
        abort_unless($knowledge->ready(), 503, 'Organization AI setup is required.');
    }

    public function index(RiskOrganizationKnowledge $knowledge)
    {
        $ready = $knowledge->ready(); $canManage = Gate::allows('manage-risk-ai-knowledge');
        $profile = $knowledge->profile(); $content = $profile ? json_decode($profile->content, true) : [];
        if (!$profile && Schema::hasTable('client_details')) $content['company_name'] = DB::table('client_details')->value('company_name');
        $notes = collect(); $feedback = null;
        if ($ready) {
            $notes = DB::table('risk_ai_knowledge_notes')->where('organization_key', $knowledge->organizationKey());
            if (!$canManage) $notes->where('status', 'approved')->where(function ($query) { $query->whereNull('review_by')->orWhere('review_by', '>=', now()->format('Y-m-d')); });
            $notes = $notes->orderByDesc('id')->limit(50)->get();
            $feedback = DB::table('risk_ai_feedback as feedback')->join('risk_ai_runs as runs', 'runs.id', '=', 'feedback.run_id')
                ->where('feedback.organization_key', $knowledge->organizationKey())->where('runs.organization_key', $knowledge->organizationKey());
            if ($canManage) $feedback->where('feedback.status', 'pending');
            else $feedback->where('feedback.actor', $knowledge->actor());
            $feedback = $feedback->select('feedback.*', 'runs.topic', 'runs.action', 'runs.result')->orderByDesc('feedback.id')->paginate(15);
        }
        $model = Schema::hasTable('modenadisable') ? DB::table('modenadisable')->first() : null;
        return response()->view('user.risks.organization-ai', compact('ready', 'canManage', 'profile', 'content', 'notes', 'feedback', 'model'))->header('Cache-Control', 'no-store');
    }

    public function saveProfile(Request $request, RiskOrganizationKnowledge $knowledge)
    {
        $this->manager($knowledge);
        $rules = ['revision' => 'required|integer|min:0', 'enabled' => 'nullable|boolean', 'company_name' => 'required|string|max:190', 'industry' => 'nullable|string|max:190',
            'source_reference' => ($request->boolean('enabled') ? 'required' : 'nullable').'|string|max:500'];
        foreach (['operations', 'locations', 'objectives', 'risk_appetite', 'terminology', 'assessment_criteria'] as $field) $rules[$field] = 'nullable|string|max:3000';
        $data = $request->validate($rules);
        try {
            DB::transaction(function () use ($data, $knowledge, $request) {
                $previous = DB::table('risk_ai_profiles')->where('organization_key', $knowledge->organizationKey())->orderByDesc('revision')->lockForUpdate()->first();
                $revision = $previous ? (int) $previous->revision : 0;
                abort_unless($revision === (int) $data['revision'], 409, 'The profile changed. Reload before saving.');
                $content = array_intersect_key($data, array_flip(['company_name', 'industry', 'operations', 'locations', 'objectives', 'risk_appetite', 'terminology', 'assessment_criteria']));
                DB::table('risk_ai_profiles')->insert(['organization_key' => $knowledge->organizationKey(), 'revision' => $revision + 1, 'enabled' => $request->boolean('enabled'),
                    'content' => json_encode($content), 'source_reference' => $data['source_reference'] ?? '', 'created_by' => $knowledge->actor(), 'created_at' => now()]);
            });
        } catch (\Illuminate\Database\QueryException $exception) { abort(409, 'The profile could not be saved. Reload and try again.'); }
        return redirect()->route('risk.ai.organization')->with('msg', 'Approved organization profile saved. Future live AI requests will use this version when enabled.');
    }

    private function noteRules()
    {
        return ['title' => 'required|string|max:190', 'content' => 'required|string|max:2000', 'keywords' => 'nullable|string|max:500', 'source_reference' => 'required|string|max:500', 'review_by' => 'nullable|date_format:Y-m-d'];
    }

    public function addNote(Request $request, RiskOrganizationKnowledge $knowledge)
    {
        $this->manager($knowledge);
        $knowledge->addNote($request->validate($this->noteRules()));
        return redirect()->route('risk.ai.organization')->with('msg', 'Approved knowledge added. Relevant, unexpired notes can guide future live AI requests.');
    }

    public function withdrawNote($id, RiskOrganizationKnowledge $knowledge)
    {
        $this->manager($knowledge);
        $count = DB::table('risk_ai_knowledge_notes')->where('organization_key', $knowledge->organizationKey())->where('id', $id)->where('status', 'approved')
            ->update(['status' => 'withdrawn', 'withdrawn_by' => $knowledge->actor(), 'updated_at' => now()]);
        abort_unless($count, 404);
        return redirect()->route('risk.ai.organization')->with('msg', 'Knowledge withdrawn. It will no longer be used for AI requests.');
    }

    public function feedback(Request $request, RiskOrganizationKnowledge $knowledge)
    {
        abort_unless($knowledge->ready(), 503, 'Organization AI setup is required.');
        $data = $request->validate(['run_id' => 'required|uuid', 'rating' => 'required|in:helpful,not_relevant,incorrect', 'comment' => 'nullable|required_if:rating,not_relevant,incorrect|string|max:2000']);
        $run = DB::table('risk_ai_runs')->where('id', $data['run_id'])->where('organization_key', $knowledge->organizationKey())->where('actor', $knowledge->actor())->first();
        abort_unless($run, 404);
        try {
            DB::table('risk_ai_feedback')->insert(['organization_key' => $knowledge->organizationKey(), 'run_id' => $run->id, 'actor' => $knowledge->actor(),
                'rating' => $data['rating'], 'comment' => $data['comment'] ?? '', 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()]);
        } catch (\Illuminate\Database\QueryException $exception) { abort(409, 'Feedback for this answer has already been submitted or could not be saved.'); }
        return response()->json(['message' => 'Feedback saved for review. Shared knowledge changes only after manager approval.'])->header('Cache-Control', 'no-store');
    }

    public function reviewFeedback(Request $request, $id, RiskOrganizationKnowledge $knowledge)
    {
        $this->manager($knowledge);
        $rules = ['decision' => 'required|in:approve,acknowledge,dismiss'];
        if ($request->input('decision') === 'approve') $rules += $this->noteRules();
        $data = $request->validate($rules);
        DB::transaction(function () use ($data, $id, $knowledge) {
            $feedback = DB::table('risk_ai_feedback')->where('organization_key', $knowledge->organizationKey())->where('id', $id)->lockForUpdate()->first();
            abort_unless($feedback, 404); abort_unless($feedback->status === 'pending', 409, 'This feedback has already been reviewed.');
            if ($data['decision'] === 'approve') $knowledge->addNote($data, $feedback->id);
            DB::table('risk_ai_feedback')->where('id', $feedback->id)->where('organization_key', $knowledge->organizationKey())->update([
                'status' => ['approve' => 'approved', 'acknowledge' => 'reviewed', 'dismiss' => 'dismissed'][$data['decision']],
                'reviewed_by' => $knowledge->actor(), 'reviewed_at' => now(), 'updated_at' => now()]);
        });
        return redirect()->route('risk.ai.organization')->with('msg', 'Feedback reviewed. Only explicitly approved knowledge is used in future requests.');
    }
}
