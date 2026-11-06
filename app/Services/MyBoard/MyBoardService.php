<?php

namespace App\Services\MyBoard;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/** Read-only personal work projection. Never grants access or changes source workflow state. */
class MyBoardService
{
    private $schema;
    private $tokens = [];
    private $directory = [];
    private $tasks = [];
    private $completed = [];
    private $updates = [];
    private $user;
    private $today;
    private $settings;

    public function snapshot(User $user, array $activeModules): array
    {
        $this->user = $user;
        $this->today = Carbon::now(config('app.timezone'))->startOfDay();
        $this->settings = config('my_board') ?: require config_path('my_board.php');
        $this->tasks = $this->completed = $this->updates = $this->tokens = $this->directory = [];
        $modules = [];
        foreach ($this->settings['modules'] as $key => $meta) {
            if (!in_array($key, $activeModules, true)) continue;
            $modules[$key] = ['key' => $key, 'name' => $meta[0], 'url' => Route::has($meta[1]) ? route($meta[1]) : null,
                'icon' => $meta[2], 'area' => $meta[3], 'pending' => 0, 'feeds' => 0, 'connected' => 0, 'state' => 'unavailable'];
        }
        foreach ($this->settings['sources'] as $key => $source) {
            if (!isset($modules[$source['module']])) continue;
            $modules[$source['module']]['feeds']++;
            try {
                if ($this->collect($key, $source, $modules[$source['module']])) $modules[$source['module']]['connected']++;
            } catch (\Throwable $error) {
                // A legacy/optional module must not prevent the personal board from opening.
                Log::warning('My Board work source unavailable', ['source' => $key, 'exception' => get_class($error)]);
            }
        }
        foreach ([['riskmanagement', 'criteria', 'racm_reviewer_details', 'racm_id', 'racm_rejected'],
            ['documentmanagement', 'document_drafts', 'governance_hub_drafts_reviewer_details', 'draft_id', 'draft_rejected']] as $feed) {
            list($moduleKey, $sourceKey, $table, $foreignKey, $rejectedField) = $feed;
            if (!isset($modules[$moduleKey], $this->settings['sources'][$sourceKey])) continue;
            $modules[$moduleKey]['feeds']++;
            try { if ($this->assignedReviews($sourceKey, $table, $foreignKey, $rejectedField, $modules[$moduleKey])) $modules[$moduleKey]['connected']++; }
            catch (\Throwable $error) { Log::warning('My Board assigned reviews unavailable', ['source' => $sourceKey, 'exception' => get_class($error)]); }
        }
        $tasks = array_values($this->tasks);
        usort($tasks, function ($a, $b) {
            return ($b['overdue'] <=> $a['overdue']) ?: (($a['due'] ?: '9999') <=> ($b['due'] ?: '9999'))
                ?: ($b['priority_weight'] <=> $a['priority_weight']) ?: strcmp($b['updated_at'] ?: '', $a['updated_at'] ?: '') ?: strcmp($a['key'], $b['key']);
        });
        $counts = ['all' => count($tasks), 'overdue' => 0, 'due_soon' => 0, 'action' => 0, 'review' => 0, 'approval' => 0];
        foreach ($tasks as $task) {
            $counts[$task['kind']]++;
            if ($task['overdue']) $counts['overdue']++;
            if ($task['due_soon']) $counts['due_soon']++;
            $modules[$task['module']]['pending']++;
        }
        foreach ($modules as &$module) {
            $module['state'] = $module['connected'] === 0 ? 'unavailable' : ($module['connected'] < $module['feeds'] ? 'partial' : 'connected');
        }
        unset($module);
        $updates = array_values($this->updates);
        usort($updates, function ($a, $b) { return strcmp($b['at'], $a['at']) ?: strcmp($a['key'], $b['key']); });
        $recent = $this->recentModules();
        uasort($modules, function ($a, $b) use ($recent) {
            return ($b['pending'] <=> $a['pending']) ?: strcmp($recent[$b['key']] ?? '', $recent[$a['key']] ?? '') ?: strcmp($a['name'], $b['name']);
        });
        return ['tasks' => $tasks, 'counts' => $counts, 'updates' => array_slice($updates, 0, 20), 'modules' => array_values($modules),
            'score' => $this->score($tasks), 'coverage' => ['total' => count($modules), 'connected' => count(array_filter($modules, function ($m) { return $m['connected'] > 0; })),
                'has_gaps' => count(array_filter($modules, function ($m) { return $m['state'] !== 'connected'; })) > 0],
            'updated_at' => Carbon::now(config('app.timezone'))->toIso8601String(), 'timezone' => config('app.timezone')];
    }

    private function columns(string $table): array
    {
        if ($this->schema === null) {
            $this->schema = [];
            if (DB::connection()->getDriverName() === 'mysql') {
                $prefix = DB::connection()->getTablePrefix();
                foreach (DB::select('SELECT TABLE_NAME AS board_table, COLUMN_NAME AS board_column FROM information_schema.columns WHERE table_schema = ?', [DB::connection()->getDatabaseName()]) as $column) {
                    $name = $column->board_table;
                    if ($prefix && strpos($name, $prefix) === 0) $name = substr($name, strlen($prefix));
                    $this->schema[$name][] = $column->board_column;
                }
                $this->schema['__complete'] = true;
            }
        }
        if (!array_key_exists($table, $this->schema)) {
            $this->schema[$table] = isset($this->schema['__complete']) ? [] : (Schema::hasTable($table) ? Schema::getColumnListing($table) : []);
        }
        return $this->schema[$table];
    }

    private function identity(string $kind): array
    {
        if (isset($this->tokens[$kind])) return $this->tokens[$kind];
        if ($kind === 'unique_name') {
            $name = strtolower(trim((string) $this->user->name));
            $unique = $name !== '' && in_array('name', $this->columns('users'), true)
                && DB::table('users')->whereRaw('LOWER(TRIM(name)) = ?', [$name])->count() === 1;
            return $this->tokens[$kind] = $unique ? [$name] : [];
        }
        if ($kind === 'employee_record') {
            $columns = $this->columns('employee_details');
            $employee = $this->user->employeeid;
            return $this->tokens[$kind] = $employee && in_array('emp_number', $columns, true)
                ? DB::table('employee_details')->where('emp_number', $employee)->pluck('id')->map(function ($id) { return (string) $id; })->all() : [];
        }
        $value = $kind === 'id' ? $this->user->id : ($kind === 'employeeid' ? $this->user->employeeid : $this->user->email);
        return $this->tokens[$kind] = ($value === null || trim((string) $value) === '') ? [] : [strtolower(trim((string) $value))];
    }

    private function member(array $role): bool
    {
        if (isset($role['gate'])) {
            $key = 'gate:'.$role['gate'];
            if (!isset($this->directory[$key])) $this->directory[$key] = !array_diff(['key', 'users_id'], $this->columns('user_gates'))
                && DB::table('user_gates')->where('key', $role['gate'])->where('users_id', $this->user->id)->exists();
            return $this->directory[$key];
        }
        $table = $role['directory'];
        if (!isset($this->directory[$table])) {
            $this->directory[$table] = in_array('email', $this->columns($table), true)
                && DB::table($table)->whereRaw('LOWER(email) = ?', [strtolower($this->user->email)])->exists();
        }
        return $this->directory[$table];
    }

    /** CSV and JSON-array identity matching with token boundaries, not substring matching. */
    private function scopeIdentity($query, string $field, array $tokens): void
    {
        if (!$tokens) return;
        $column = DB::connection()->getQueryGrammar()->wrap($field);
        $normal = "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(CAST($column AS CHAR), ' ', ''), '\"', ''), '[', ''), ']', ''))";
        $csv = DB::connection()->getDriverName() === 'mysql' ? "CONCAT(',', $normal, ',')" : "(',' || $normal || ',')";
        foreach ($tokens as $token) {
            $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], str_replace(' ', '', $token));
            $query->orWhereRaw("$csv LIKE ? ESCAPE '!'", ['%,'.$escaped.',%']);
        }
    }

    private function scopeStages($query, string $field, array $values, bool $exclude = false): void
    {
        if (!$values) return;
        $column = DB::connection()->getQueryGrammar()->wrap($field);
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $query->whereRaw('LOWER('.$column.') '.($exclude ? 'NOT IN' : 'IN').' ('.$placeholders.')', array_map('strtolower', $values));
    }

    private function listValues($value): array
    {
        if ($value === null || $value === '') return [];
        if (is_array($value)) return array_values($value);
        $decoded = json_decode((string) $value, true);
        return is_array($decoded) ? array_values($decoded) : explode(',', (string) $value);
    }

    private function matches($value, string $kind): bool
    {
        foreach ($this->listValues($value) as $part) {
            if (is_scalar($part) && in_array(strtolower(trim((string) $part)), $this->identity($kind), true)) return true;
        }
        return false;
    }

    private function collect(string $key, array $source, array $module): bool
    {
        $columns = $this->columns($source['table']);
        if (!in_array($source['id'], $columns, true)) return false;
        $roles = $source['roles'];
        if (isset($source['investigators']) && in_array('user_id', $this->columns($source['investigators']), true)) {
            $ids = DB::table($source['investigators'])->where('user_id', $this->user->id)->pluck($source['investigator_fk'])->map(function ($id) { return (string) $id; })->all();
            $this->tokens['investigations_'.$key] = $ids;
            $roles[] = ['field' => $source['id'], 'identity' => 'investigations_'.$key, 'kind' => 'action', 'pending' => ['Awaiting actions', 'Correction Proposed- RC Awaiting'], 'title' => 'Investigate issue', 'due' => ['investigation_date']];
        }
        $usableRoles = array_filter($roles, function ($role) use ($columns) {
            if (($role['identity'] ?? '') === 'unique_name' && !$this->identity('unique_name')) return false;
            return $role['field'] ? in_array($role['field'], $columns, true) : (isset($role['gate'])
                ? !array_diff(['key', 'users_id'], $this->columns('user_gates')) : in_array('email', $this->columns($role['directory']), true));
        });
        $hasState = in_array($source['stage'], $columns, true) || (isset($source['progress']) && in_array($source['progress'], $columns, true));
        foreach ($source['conditions'] ?? [] as $field => $value) if (!in_array($field, $columns, true)) return false;
        $fields = [$source['id'], $source['stage'], 'deleted_at', 'is_deleted', 'priority_level', 'priority', 'rank', $source['progress'] ?? ''];
        foreach (['title', 'reference', 'due', 'updated', 'completion'] as $group) $fields = array_merge($fields, $source[$group]);
        foreach ($roles as $role) $fields = array_merge($fields, [$role['field']], $role['due'] ?? [], array_values($role['due_pairs'] ?? []));
        foreach ($source['events'] as $event) $fields = array_merge($fields, [$event['field'], $event['date']]);
        $fields = array_values(array_intersect(array_unique(array_filter(array_merge($fields, array_keys($source['watch'])))), $columns));
        $query = DB::table($source['table'])->select($fields);
        foreach ($source['conditions'] ?? [] as $field => $value) $query->where($field, $value);
        if (in_array('deleted_at', $columns, true)) $query->whereNull('deleted_at');
        if (in_array('is_deleted', $columns, true)) $query->where(function ($q) { $q->whereNull('is_deleted')->orWhere('is_deleted', 0); });
        $query->where(function ($q) use ($usableRoles, $source, $columns) {
            $q->whereRaw('1 = 0');
            foreach ($usableRoles as $role) {
                if ($role['field']) $this->scopeIdentity($q, $role['field'], $this->identity($role['identity']));
                elseif ($this->member($role) && in_array($source['stage'], $columns, true)) {
                    $q->orWhere(function ($global) use ($role, $source, $columns) {
                        if ($role['pending'] === '*') { $global->whereNotNull($source['id']); $this->scopeStages($global, $source['stage'], $source['complete'], true); }
                        else $this->scopeStages($global, $source['stage'], $role['pending']);
                        if (isset($role['when_progress'])) {
                            if (!isset($source['progress']) || !in_array($source['progress'], $columns, true)) $global->whereRaw('1 = 0');
                            else $global->where($source['progress'], $role['when_progress']);
                        }
                    });
                }
            }
            foreach ($source['watch'] as $field => $identity) if (in_array($field, $columns, true)) $this->scopeIdentity($q, $field, $this->identity($identity));
            foreach ($source['events'] as $event) if (in_array($event['field'], $columns, true)) $this->scopeIdentity($q, $event['field'], $this->identity($event['identity']));
        });
        $records = !empty($source['course']) ? $query->get() : $query->cursor();
        $courseNames = [];
        if (!empty($source['course']) && !array_diff(['id', 'name'], $this->columns('courses'))) {
            $courseNames = DB::table('courses')->whereIn('id', $records->pluck('course_id')->filter()->unique())->pluck('name', 'id')->all();
        }
        foreach ($records as $record) {
            $row = (array) $record;
            if (!empty($source['course'])) $row['course_name'] = $courseNames[$row['course_id'] ?? ''] ?? 'Assigned course';
            $state = trim((string) ($row[$source['stage']] ?? ''));
            $closed = in_array(strtolower($state), array_map('strtolower', $source['complete']), true)
                || (!empty($source['complete_progress']) && isset($row[$source['progress'] ?? '']) && (float) $row[$source['progress']] >= 100);
            foreach ($usableRoles as $role) {
                $mine = $role['field'] ? $this->matches($row[$role['field']] ?? null, $role['identity']) : $this->member($role);
                if (!$mine || !$hasState) continue;
                if ($key === 'document_drafts' && $role['kind'] === 'review'
                    && !array_diff(['draft_id', 'reviewer_id', 'reviewed_status', 'draft_rejected'], $this->columns('governance_hub_drafts_reviewer_details'))) continue;
                if (isset($role['when_progress']) && (float) ($row[$source['progress'] ?? ''] ?? -1) !== (float) $role['when_progress']) continue;
                $personalRow = $row; $dueFields = $role['due'] ?? null;
                if (isset($role['due_pairs']) && array_key_exists($role['due_pairs']['dates'], $row)) {
                    $owners = $this->listValues($row[$role['due_pairs']['owners']] ?? null);
                    $dates = $this->listValues($row[$role['due_pairs']['dates']] ?? null);
                    $personalRow['__board_personal_due'] = null;
                    if (count($owners) === count($dates)) foreach ($owners as $index => $owner) {
                        if ($this->matches($owner, $role['identity'])) { $personalRow['__board_personal_due'] = $dates[$index]; break; }
                    }
                    $dueFields = ['__board_personal_due'];
                }
                $pending = $role['pending'] === '*' || in_array(strtolower($state), array_map('strtolower', $role['pending']), true);
                if (!$closed && $pending) {
                    $item = $this->item($key, $source, $module, $personalRow, $role['kind'], $role['title'] ?? null, $dueFields);
                    $item['score'] = $role['score'] ?? $source['score'];
                    $existing = $this->tasks[$item['key']] ?? null;
                    if (!$existing || (!$existing['due'] && $item['due']) || ($existing['due'] && $item['due'] && $item['due'] < $existing['due'])) $this->tasks[$item['key']] = $item;
                } elseif ($closed && $role['kind'] === 'action' && $source['score'] && ($role['credit_completion'] ?? true)) {
                    $item = $this->item($key, $source, $module, $personalRow, 'action', null, $dueFields);
                    $item['completed_at'] = $this->firstDate($row, $source['completion']);
                    $this->rememberCompletion($item);
                }
            }
            foreach ($source['events'] as $event) {
                $date = $this->date($row[$event['date']] ?? null);
                if ($date && $this->matches($row[$event['field']] ?? null, $event['identity'])) {
                    $item = $this->item($key, $source, $module, $row, $event['kind'], null, $event['due'] ?? []);
                    $item['score'] = $event['score'] ?? $source['score'];
                    $item['completed_at'] = $date->toDateString();
                    $this->rememberCompletion($item);
                }
            }
            $this->recordUpdate($key, $source, $module, $row, $state);
        }
        return count($usableRoles) > 0 && $hasState;
    }

    private function item(string $key, array $source, array $module, array $row, string $kind, ?string $instruction = null, ?array $dueFields = null): array
    {
        $id = (string) $row[$source['id']];
        $title = $this->firstText($row, $source['title']) ?: $module['name'].' record';
        $reference = $this->firstText($row, $source['reference']) ?: '#'.$id;
        $due = $this->firstDate($row, $dueFields ?? $source['due']);
        $updated = $this->firstDate($row, $source['updated'], true);
        $priority = strtolower(trim((string) ($row['priority_level'] ?? $row['priority'] ?? $row['rank'] ?? '')));
        $priority = in_array($priority, ['high', 'critical', 'urgent']) ? 'High' : (in_array($priority, ['medium', 'moderate']) ? 'Moderate' : ($priority === 'low' ? 'Low' : ''));
        if ($key === 'criteria' && in_array($kind, ['review', 'approval'], true)) {
            $source['path'] = $kind === 'review' ? '/user/riskmgmt/racm/review/{id}/'.$this->user->id : '/user/riskmgmt/racm/approve/{id}';
        }
        return ['key' => $key.':'.$id.':'.$kind, 'module' => $source['module'], 'module_name' => $module['name'], 'area' => $module['area'], 'icon' => $module['icon'],
            'kind' => $kind, 'instruction' => $instruction ?: ['action' => 'Take action', 'review' => 'Review record', 'approval' => 'Approve or return record'][$kind],
            'title' => $title, 'reference' => $reference, 'status' => $this->text($row[$source['stage']] ?? (isset($row[$source['progress'] ?? '']) ? max(0, min(100, (int) $row[$source['progress']])).'% complete' : '')),
            'url' => isset($source['path']) ? url(str_replace('{id}', rawurlencode($id), $source['path'])) : $module['url'],
            'direct' => isset($source['path']), 'due' => $due, 'due_label' => $due ? Carbon::parse($due)->format('d M Y') : 'No due date',
            'overdue' => $due && $due < $this->today->toDateString(), 'due_soon' => $due && $due >= $this->today->toDateString() && $due <= $this->today->copy()->addDays(7)->toDateString(),
            'priority' => $priority, 'priority_weight' => ['High' => 3, 'Moderate' => 2, 'Low' => 1, '' => 0][$priority], 'updated_at' => $updated, 'score' => $source['score']];
    }

    private function rememberCompletion(array $item): void
    {
        if (!$item['score']) return;
        $this->completed[$item['key']] = ['score' => true, 'due' => $item['due'], 'completed_at' => $item['completed_at'] ?? null];
    }

    private function recordUpdate(string $key, array $source, array $module, array $row, string $state): void
    {
        $at = $this->firstDate($row, $source['updated'], true);
        if (!$at) return;
        if (count($this->updates) >= 20) {
            $oldest = end($this->updates);
            if ($at < $oldest['at']) return;
        }
        $id = (string) $row[$source['id']];
        $this->updates[$key.':'.$id] = ['key' => $key.':'.$id,
            'title' => $this->firstText($row, $source['title']) ?: $module['name'].' record',
            'reference' => $this->firstText($row, $source['reference']) ?: '#'.$id,
            'module_name' => $module['name'], 'icon' => $module['icon'], 'status' => $state ?: 'Record updated', 'at' => $at,
            'time_label' => Carbon::parse($at)->diffForHumans(),
            'url' => isset($source['path']) ? url(str_replace('{id}', rawurlencode($id), $source['path'])) : $module['url']];
        if (count($this->updates) >= 20) {
            uasort($this->updates, function ($a, $b) { return strcmp($b['at'], $a['at']) ?: strcmp($a['key'], $b['key']); });
            if (count($this->updates) > 20) array_pop($this->updates);
        }
    }

    private function assignedReviews(string $key, string $table, string $foreignKey, string $rejectedField, array $module): bool
    {
        $source = $this->settings['sources'][$key];
        $columns = $this->columns($table);
        if (array_diff([$foreignKey, 'reviewer_id', 'reviewed_status', $rejectedField], $columns) || array_diff(['id', $source['stage']], $this->columns($source['table']))) return false;
        $assignments = DB::table($table)->where('reviewer_id', $this->user->id)->get();
        if ($assignments->isEmpty()) return true;
        $parents = DB::table($source['table'])->whereIn('id', $assignments->pluck($foreignKey));
        if (in_array('deleted_at', $this->columns($source['table']), true)) $parents->whereNull('deleted_at');
        if (in_array('is_deleted', $this->columns($source['table']), true)) $parents->where('is_deleted', 0);
        $records = $parents->get()->keyBy('id');
        foreach ($assignments as $assignment) {
            if (!isset($records[$assignment->$foreignKey])) continue;
            $record = (array) $records[$assignment->$foreignKey];
            $item = $this->item($key, $source, $module, $record, 'review', $key === 'criteria' ? 'Review assessment thresholds' : 'Review document draft');
            $item['score'] = true;
            if (!$assignment->reviewed_status && !$assignment->$rejectedField && $record['status'] === 'Review Requested') $this->tasks[$item['key']] = $item;
            if ($assignment->reviewed_status && !$assignment->$rejectedField) {
                $item['completed_at'] = $this->firstDate((array) $assignment, ['reviewed_date']);
                $this->rememberCompletion($item);
            }
            $this->recordUpdate($key, $source, $module, $record, $record['status'] ?? '');
        }
        return true;
    }

    private function score(array $tasks): array
    {
        $done = array_values(array_filter($this->completed, function ($item) { return $item['score']; }));
        $pending = array_values(array_filter($tasks, function ($item) { return $item['score']; }));
        $onTime = 0; $dated = 0;
        foreach ($done as $item) {
            if ($item['due'] && $item['completed_at']) { $dated++; if ($item['completed_at'] <= $item['due']) $onTime++; }
        }
        $late = count(array_filter($pending, function ($item) { return $item['overdue']; }));
        $total = count($done) + count($pending);
        $completion = $total ? count($done) / $total : 0;
        $timeliness = $dated + $late ? $onTime / ($dated + $late) : null;
        $value = !$total ? null : (int) round($timeliness === null ? 100 * $completion : 70 * $completion + 30 * $timeliness);
        return ['value' => $value, 'completed' => count($done), 'pending' => count($pending), 'on_time' => $onTime, 'dated_completed' => $dated, 'overdue' => $late,
            'completion_percent' => (int) round(100 * $completion), 'timeliness_percent' => $timeliness === null ? null : (int) round(100 * $timeliness),
            'model' => '70% completion + 30% timeliness. When completion dates are unavailable, completion alone is used.'];
    }

    private function recentModules(): array
    {
        if (array_diff(['user_id', 'module_name', 'last_accessed_at'], $this->columns('module_activity'))) return [];
        return DB::table('module_activity')->where('user_id', $this->user->id)->pluck('last_accessed_at', 'module_name')->all();
    }

    private function text($value): string
    {
        return mb_substr(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $value))), 0, 240);
    }

    private function firstText(array $row, array $fields): string
    {
        foreach ($fields as $field) if (isset($row[$field]) && trim((string) $row[$field]) !== '') return $this->text($row[$field]);
        return '';
    }

    public function date($value): ?Carbon
    {
        if (!$value || !is_scalar($value)) return null;
        $value = trim((string) $value);
        foreach (['Y-m-d H:i:s', 'Y-m-d', 'd-m-Y h:i:s A', 'd-m-Y h:i A', 'd-m-Y H:i:s', 'd-m-Y', 'd/m/Y', 'd/m/Y H:i:s'] as $format) {
            try {
                $date = Carbon::createFromFormat('!'.$format, $value, config('app.timezone'));
                if ($date && $date->format($format) === $value) return $date;
            } catch (\Throwable $error) { /* Try the next documented legacy format. */ }
        }
        return null;
    }

    private function firstDate(array $row, array $fields, bool $timestamp = false): ?string
    {
        foreach ($fields as $field) {
            $date = $this->date($row[$field] ?? null);
            if ($date) return $timestamp ? $date->toIso8601String() : $date->toDateString();
        }
        return null;
    }
}
