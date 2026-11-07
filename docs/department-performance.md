# Department performance model 1.0

The organization dashboard has a compact department comparison and a searchable **Departments** tab with per-department breakdowns. It is a provisional operational comparison, not an employee appraisal, ESG rating or cross-industry benchmark. No database migration or external API is required.

## Sources and attribution

Only company-enabled modules are queried. `config/department_performance.php` declares source columns: mitigations use `department_id`; corrective, audit findings, HSE and near-miss actions use their record-owning `department` ID. Audit schedules and objectives use `department`. IDs must be exact positive integer identifiers of active departments; CSV lists, unknown IDs, names and record authors are never guessed. The metric describes record-owning departments, not necessarily every employee or department contributing to an action. Inactive/soft-deleted departments are excluded. Deleted/cancelled/archived source records are excluded.

Metadata is shared with the parent dashboard service. Each enabled source uses one grouped aggregate query; no record titles or employee details are loaded and query count does not grow with department count. Missing department/status/deadline columns are shown as unavailable. A failed source contributes no partial points. The model covers mapped workflows only; raw incident counts, headcount, site size or number of submitted records earn no points.

## Period and metrics

The window is 90 calendar days through today inclusive, based on obligation due dates, not record creation. Open overdue actions older than the window remain included in overdue control; old completed actions are excluded. Strict ISO/day-first dates are supported; literal NULL/N/A/none/dash status placeholders are treated as missing; malformed, missing or composite dates/statuses are excluded and counted as gaps. Future obligations and out-of-period audit/objective obligations are excluded before checking status completeness. Unfinished actions, audits and objectives due today are not treated as failed on-time obligations before their day ends; completed obligations due today are included.

* On-time action completion, 35%: recorded completed/closed actions due in the window with valid recorded completion/review date on or before the deadline, divided by due actions including currently overdue open actions. Valid completion dates must not be in the future. Missing completion proof earns no on-time credit; over 20% unknown timing makes this metric unavailable. For mitigations, formal closure uses `reviewed_date`; corrective actions use their actual `closed` timestamp with legacy `completion_date` as fallback; other sources use the declared completion/closure date. It is not proof of when work physically occurred.
* Overdue action control, 25%: `100 * max(0, 1 - weightedOverdueExposure / totalEligibleActionWeight)`. Priority weights Low/Moderate/High are 1/2/3, with critical/urgent treated as High. Unknown priority is explicitly counted and defaults to Moderate. An overdue action's weight is multiplied by `1 + min(3, daysOverdue / 30)`. Denominator includes completed due actions in the window and open actions due through today, including older overdue backlog. Closing or adding future records cannot add future-record credit. Missing deadlines are excluded rather than treated as on time.
* Scheduled audits completed, 20%: schedules recorded Completed or Permanently closed / schedules due in the window, using `enddate`.
* Recorded objectives achieved, 20%: objectives recorded Achieved / objectives due in the window, using `todate`. This does not infer target attainment from incomplete measured values.

The provisional score is the model-weighted average of available measures. Missing measures do not earn 100 and reduce metric coverage. Reporting another safety event is never itself a penalty; overdue handling of an assigned follow-up can affect action-control measures. Training completion and review turnaround are explicitly not scored until required training plans, reliable department attribution and defined review timing targets are connected.

## Eligibility, comparison and ties

A department needs at least five distinct eligible source records (not double-counted across on-time/backlog metrics), 60% available metric weight and 80% date/status completeness, evaluated before display rounding. Completeness is eligible records / (eligible records + excluded invalid-date/status records); period cannot be established for undated records, so these gaps remain visible conservatively. Departments that do not meet these gates display **Insufficient data**, without a score or rank.

A comparison group contains departments with the exact same available metrics and metric-specific source-workflow sets. This avoids comparing a department's actions-only score directly against a department scored on audits and objectives too. Same evidence scope does not establish similar business functions or workload difficulty: the UI states that limitation. Ranks use the displayed one-decimal scores, with ties such as 1, 1, 3. A department with a valid score but no comparable peer displays **No comparable peer** and no rank, rather than first place.

No trend or change from earlier periods is fabricated from mutable current statuses. `change` remains null, with the missing historical baseline explained in the breakdown. Historical snapshots, approved peer/job-family categories and policy-specific weights are future extensions; do not imply an unavailable improvement trend or statistically validated performance appraisal.

## Validation

Tests cover weighted arithmetic, severity and overdue age, actual dates, unknown timing, minimum evidence, incomplete/malformed dates, deleted/cancelled/future/old obligations, active/exact department attribution, disabled-module query exclusion, comparable groups, ties and no-peer cases, bounded query counts, escaped names and dashboard integration. Existing dashboard authentication/MFA and private/no-store response remain in effect.

The comprehensive regression results and corrected edge cases are recorded in [department-performance-validation.md](department-performance-validation.md). Deployment should refresh the normal Laravel view/config caches so shared source-registry changes are picked up.
