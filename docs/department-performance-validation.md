# Department performance validation

## Result

The complete repository PHPUnit suite passes: **142 tests, 2,891 assertions**. This includes 34 dedicated department-performance tests and regressions for dashboard, My Board, ESG, risk workflows and evidence handling. Browser checks pass **51 assertions**, and MySQL aggregation/calculation checks pass on a larger synthetic dataset. PHP, JavaScript and shell syntax checks pass.

This validates the local build and rendered fixtures. The signed-in production deployment has not been verified: no authenticated production session or server access was available. No production records were changed. Historical trend baselines, training-plan attribution and review-turnaround metrics remain explicitly outside the current model.

## Issues found and corrected

| Issue | Correction and regression coverage |
| --- | --- |
| A measured zero did not explain its contributing evidence | Add per-measure points, source counts, age/period exclusions, overdue exposure and Completed-but-unclosed mitigation counts. Distinguish rounded tiny results from literal zero. Scoring weights are unchanged. |
| Measured department scores were hidden whenever ranking eligibility failed | Show a clearly labelled available-data partial score, keep ranks gated, expose exact reasons and prioritize measured departments in the overview. No score is invented for absent data. |
| Display rounding admitted a department at 79.96% completeness through an 80% gate | Apply the gate to the exact ratio; display completeness to two decimals. |
| Future/out-of-period records with missing status reduced current-period completeness | Apply known date-window exclusions before missing-status checks. |
| Literal NULL/N/A/none/dash statuses became open/overdue evidence | Classify placeholder statuses as missing evidence. |
| Corrective actions lacked completion proof despite an actual closure timestamp | Use the actual `closed` timestamp, with legacy completion date as fallback. |
| Unfinished audits/objectives due today reduced performance before the day ended | Defer their incomplete denominator until the deadline day ends; keep completed-today records. |
| A completed audit lost credit after moving to Permanently closed | Recognize the actual lifecycle state consistently in department performance, dashboard/My Board and ESG. |
| Non-module configuration flags could activate a department comparison with no enabled modules | Pass recognized enabled module keys to the comparison service. |
| Small secondary text and keyboard focus lacked sufficient contrast | Adjust shared dashboard text/status colors and focus outline; check light/dark text contrast against panel and soft backgrounds. |
| Generated root smoke test expected HTTP 200 from an intentional redirect | Assert the existing `/user/home` redirect, without changing application routing. |

## Automated coverage

- Actual completion dates, severity and overdue-age arithmetic; scores bounded to 0–100 and monotonic checks for worsening severity, age and lateness.
- Minimum record count, exact completeness gate, metric coverage, missing timing proof, invalid/future dates and reporting-window boundaries.
- Exact department IDs, ambiguous CSV/unknown IDs, active departments, soft deletion, explicit deletion flags and cancelled records.
- Disabled modules, missing schemas, failed-source isolation and non-module flags.
- Comparable metric/workflow groups, single-peer behavior, ties and deterministic ordering.
- Deadline-day grace, permanent audit closure and corrective closure proof.
- Authentication regression, escaped names, source privacy, no invented historical change, empty/missing department screens and dashboard integration.
- Query-count checks across growing department/record counts.

## Browser coverage

Chromium checks use Laravel-rendered synthetic fixtures and real local CSS/JS assets:

- Management overview, full department tab, compact comparison and expandable metric breakdowns.
- Partial scores remain visible without unsupported ranks; collapsed rows explain unmet eligibility requirements.
- A zero fixture explains the exact overdue/closed counts, excluded historical records and point contributions, with responsive light/dark rendering. This reproduces a valid zero but does not establish the cause of a particular live department result.
- Native Enter/Space disclosure controls; arrow/Home/End tab navigation; visible keyboard focus.
- Tab/tabpanel ARIA relationships, one selected panel, labelled search fields and unique DOM IDs.
- Direct department hash links, shortcut focus, module search, case/whitespace-insensitive department search and safe no-results text.
- Empty active-department data without JavaScript errors; core comparison content remains readable with JavaScript disabled.
- Long names and horizontal-overflow checks at 1920, 1440, 850, 720, 390 and 320px, in light and dark themes.
- Primary, secondary, link and danger text colors meet 4.5:1 against the dashboard's panel/soft backgrounds. This is a focused contrast check, not a claim of full WCAG certification.

## MySQL fixture load check

The fixture uses temporary tables on the development connection, discarded when that process ends. No persistent application tables are modified.

| Measure | Observed |
| --- | ---: |
| Active departments | 100 |
| Action records | 12,501 |
| Aggregate queries for the comparison | 5 |
| Snapshot time | 0.0852 seconds |
| PHP peak memory, including bootstrap | 28 MiB |

Assertions cover the 79.96% threshold, future/status exclusion, actual corrective closure, permanent audit closure, due-today grace, ties, no-peer handling, ambiguous department IDs, repeatability and read-only behavior. These local timings are not a production performance guarantee.

## Reproduction

Run the complete repository suite, not only the feature-filtered subset:

```bash
bash tools/risk-ai-dev.sh test-all
```

The wrapper uses the pinned PHP 7.4 compatibility container and in-memory SQLite test configuration. Dedicated cases are in `tests/Feature/DepartmentPerformanceTest.php`. The production smoke check still required after deployment is: sign in, open `/user/dashboard`, inspect Departments against known organization records and confirm the new assets/view are served. No migration is required for these fixes.
