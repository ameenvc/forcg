<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RiskOrganizationKnowledge
{
    private $ready;

    public function organizationKey()
    {
        // The existing application uses one organization per installation/database.
        // This key is server configuration, never an ID accepted from a browser.
        return hash('sha256', (string) config('risk_ai.organization_key', 'installation'));
    }

    public function actor()
    {
        $user = auth()->user();
        return ($user instanceof \App\Admin ? 'admin:' : 'web:').$user->getAuthIdentifier();
    }

    public function ready()
    {
        if ($this->ready === null) {
            $this->ready = true;
            foreach (['risk_ai_profiles', 'risk_ai_knowledge_notes', 'risk_ai_runs', 'risk_ai_feedback'] as $table) {
                if (!Schema::hasTable($table)) { $this->ready = false; break; }
            }
        }
        return $this->ready;
    }

    public function profile()
    {
        return $this->ready() ? DB::table('risk_ai_profiles')->where('organization_key', $this->organizationKey())->orderByDesc('revision')->first() : null;
    }

    public function context(array $request)
    {
        $profile = $this->profile();
        if (!$profile || !$profile->enabled) return null;
        $content = json_decode($profile->content, true);
        if (!is_array($content)) return null;
        $parts = [$request['concern'] ?? '', $request['risk_group'] ?? '', $request['risk_area'] ?? '', $request['selected_risk_name'] ?? ''];
        foreach (array_slice($request['risks'] ?? [], 0, 50) as $risk) { $parts[] = $risk['name'] ?? ''; $parts[] = $risk['group'] ?? ''; $parts[] = $risk['area'] ?? ''; }
        $query = mb_strtolower(mb_substr(implode(' ', array_filter($parts)), 0, 30000));
        $notes = DB::table('risk_ai_knowledge_notes')->where('organization_key', $this->organizationKey())->where('status', 'approved')
            ->where(function ($query) { $query->whereNull('review_by')->orWhere('review_by', '>=', now()->format('Y-m-d')); })
            ->orderByDesc('id')->limit(100)->get();
        $selected = [];
        foreach ($notes as $note) {
            $keywords = array_filter(array_map('trim', explode(',', mb_strtolower((string) $note->keywords))));
            $score = $keywords ? 0 : 1;
            foreach ($keywords as $keyword) { if (mb_strpos($query, $keyword) !== false) $score += 3; }
            if ($score) $selected[] = ['score' => $score, 'note' => ['id' => 'K'.$note->id, 'title' => $note->title, 'content' => $note->content, 'source_reference' => $note->source_reference, 'review_by' => $note->review_by]];
        }
        usort($selected, function ($a, $b) { return $b['score'] <=> $a['score']; });
        return ['profile_revision' => (int) $profile->revision, 'profile' => $content, 'source_reference' => $profile->source_reference,
            'approved_notes' => array_column(array_slice($selected, 0, 5), 'note')];
    }

    public function complete(array $context, array $result)
    {
        $organization = $context['organization'] ?? null;
        $result['organization_context'] = ['used' => (bool) ($organization && $result['mode'] === 'openai'),
            'name' => $organization['profile']['company_name'] ?? null,
            'profile_revision' => $organization['profile_revision'] ?? null,
            'source_reference' => $organization['source_reference'] ?? null,
            'notes' => array_map(function ($note) { return array_intersect_key($note, array_flip(['id', 'title', 'source_reference', 'review_by'])); }, $organization['approved_notes'] ?? []),
            'url' => config('risk_ai.organization_workspace_enabled', false) ? route('risk.ai.organization') : null];
        if (!$this->ready()) return $result;
        $id = (string) Str::uuid();
        DB::table('risk_ai_runs')->insert(['id' => $id, 'organization_key' => $this->organizationKey(), 'actor' => $this->actor(),
            'action' => $context['action'] ?? 'draft', 'mode' => $result['mode'], 'topic' => mb_substr($context['concern'], 0, 500),
            'result' => json_encode($result), 'profile_revision' => $organization['profile_revision'] ?? null,
            'note_ids' => json_encode(array_column($organization['approved_notes'] ?? [], 'id')), 'created_at' => now()]);
        $result['feedback'] = ['run_id' => $id, 'url' => route('risk.ai.feedback')];
        return $result;
    }

    public function addNote(array $data, $feedbackId = null)
    {
        return DB::table('risk_ai_knowledge_notes')->insertGetId([
            'organization_key' => $this->organizationKey(), 'title' => $data['title'], 'content' => $data['content'],
            'keywords' => $data['keywords'] ?? '', 'source_reference' => $data['source_reference'], 'review_by' => $data['review_by'] ?? null,
            'status' => 'approved', 'created_by' => $this->actor(), 'source_feedback_id' => $feedbackId, 'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
