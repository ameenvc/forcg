<?php

namespace App\Services;

use App\Risk;
use Carbon\Carbon;

class RiskAnalysisService
{
    private function date($value)
    {
        if (!$value) return null;
        $value = trim((string) $value);
        foreach (['Y-m-d H:i:s', 'Y-m-d', 'd-m-Y h:i:s A', 'd-m-Y h:i A', 'd-m-Y H:i:s', 'd-m-Y', 'd/m/Y'] as $format) {
            try {
                $date = Carbon::createFromFormat('!'.$format, $value, config('app.timezone'));
                if ($date && $date->format($format) === $value) return $date->format('Y-m-d');
            } catch (\Exception $exception) {
                // Try the next documented legacy format; never guess ambiguous dates.
            }
        }
        return null;
    }

    private function bucket()
    {
        return ['counts' => ['total' => 0, 'High' => 0, 'Moderate' => 0, 'Low' => 0, 'Unassessed' => 0], 'departments' => [], 'dates' => [], 'stages' => [], 'responses' => [], 'quality' => ['unassigned_owner' => 0, 'missing_response' => 0, 'undated' => 0]];
    }

    private function add(array &$bucket, $rating, $department, $date, $stage, $response, $owner)
    {
        $bucket['counts']['total']++;
        $bucket['counts'][$rating]++;
        if (!isset($bucket['departments'][$department])) $bucket['departments'][$department] = $this->bucket()['counts'];
        $bucket['departments'][$department]['total']++;
        $bucket['departments'][$department][$rating]++;
        if ($date) $bucket['dates'][$date] = ($bucket['dates'][$date] ?? 0) + 1;
        else $bucket['quality']['undated']++;
        $bucket['stages'][$stage] = ($bucket['stages'][$stage] ?? 0) + 1;
        $bucket['responses'][$response ?: 'Not recorded'] = ($bucket['responses'][$response ?: 'Not recorded'] ?? 0) + 1;
        if (!$owner) $bucket['quality']['unassigned_owner']++;
        if (!$response) $bucket['quality']['missing_response']++;
    }

    public function snapshot(array $filters)
    {
        $all = $this->bucket(); $groups = []; $excludedUndated = 0;
        $query = Risk::leftJoin('departments', 'risks.riskarea', '=', 'departments.id')
            ->select('risks.Rid', 'risks.riskgroup', 'risks.rank', 'risks.created_at', 'risks.date', 'risks.action_status', 'risks.risk_owner', 'risks.risk_response', 'departments.code');
        if (($filters['scope'] ?? 'all') === 'closed') $query->where('risks.action_status', 'Closed');
        if (($filters['scope'] ?? 'all') === 'current') $query->whereIn('risks.action_status', ['Under review', 'Rejected', 'Active Risk', 'Action under review', 'Completed', 'Awaiting Periodic Review']);
        foreach ($query->cursor() as $risk) {
            $date = $this->date($risk->getRawOriginal('created_at')) ?: $this->date($risk->getRawOriginal('date'));
            if (!$date && (!empty($filters['from']) || !empty($filters['to']))) { $excludedUndated++; continue; }
            if ($date && ((!empty($filters['from']) && $date < $filters['from']) || (!empty($filters['to']) && $date > $filters['to']))) continue;
            $group = trim((string) $risk->riskgroup) ?: 'Unspecified';
            $department = trim((string) $risk->code) ?: 'Unassigned';
            $rating = in_array($risk->rank, ['High', 'Moderate', 'Low'], true) ? $risk->rank : 'Unassessed';
            $stage = trim((string) $risk->action_status) ?: 'Not recorded';
            $response = trim((string) $risk->risk_response);
            $owner = trim((string) $risk->risk_owner);
            if (!isset($groups[$group])) $groups[$group] = $this->bucket();
            $this->add($all, $rating, $department, $date, $stage, $response, $owner);
            $this->add($groups[$group], $rating, $department, $date, $stage, $response, $owner);
        }
        ksort($groups); ksort($all['departments']); ksort($all['dates']);
        foreach ($groups as &$group) { ksort($group['dates']); ksort($group['departments']); }
        unset($group);
        return ['totals' => $all['counts'], 'groups' => $groups, 'departments' => $all['departments'], 'dates' => $all['dates'], 'stages' => $all['stages'], 'responses' => $all['responses'], 'quality' => $all['quality'], 'excluded_undated' => $excludedUndated];
    }
}
