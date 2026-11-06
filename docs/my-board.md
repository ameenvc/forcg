# My Board personal work hub

`GET /user/my-board` renders the workspace. The same authenticated URL with `Accept: application/json` supplies a fresh snapshot for the Update board button. Both responses use `Cache-Control: private, no-store`. Module workflow pages remain responsible for permission checks and making changes; the board is read-only.

## Personal work sources

`config/my_board.php` registers enabled modules, actual source tables, identity types, pending stages, completion evidence and destination URLs. `app/Services/MyBoard/MyBoardService.php` queries only matching users, assignments or recorded actors. It handles scalar, CSV and JSON-array assignments with token boundaries. User IDs, employee IDs and reviewer-directory IDs are distinct. Legacy name-based assignments are included only when that full name identifies exactly one user. Ambiguous names are not guessed.

The registry covers risk reviews, mitigations, RACM, corrective actions and investigations, audit findings, HSE observations, near misses, complaints, opportunities, objectives, compliance ownership, document reviews/approvals, learning, training, maintenance, calibration holders, emergency drill teams, incidents, waste and vendor evaluations. Actual availability depends on the installed schema and the organization's enabled modules. Occupational-health medical records and external customer survey responses are deliberately not treated as user task assignments. They remain accessible through module shortcuts.

Module coverage explicitly distinguishes connected, partially connected and unavailable work feeds. A zero count means no collected assignments, not proof that a module with an unavailable workflow has no work. Add specific adapters for any additional workflow before claiming complete coverage. Do not identify users by fuzzy names or broaden visibility to a whole module simply to fill the board.

There is no arbitrary backend task limit. Items are ordered by overdue status, due date and priority, and paged ten at a time in the UI. Record updates are the latest twenty matching source records; they are not a history of sent email notifications. Update board refreshes source status without changing it. There is no recurring background poll or notification-email dispatch.

The service retrieves database schema metadata in one query on MySQL, batches related lookups, projects the fields it needs and avoids queries per task. It tolerates optional module tables without reporting their work as complete.

## Contribution score, version 1

This is a descriptive activity indicator, not an ESG assessment, certification, ranking or redeemable rewards balance. It uses recorded completed responsibilities and explicit review/approval actor evidence on connected records. It awards no credit for visits, refreshes, adding uncompleted records or continuous oversight. Repeated reads do not create points.

- Completion rate = eligible completed items / (eligible completed items + eligible pending items).
- Timeliness = known on-time completions / (completions with known due and completion dates + overdue eligible pending items).
- Score = round(70 × completion rate + 30 × timeliness).
- If timing evidence is unavailable, the score is 100 × completion rate.
- If there is no eligible work, the score is unavailable, rather than a fictional 100.

Unknown dates remain unknown. A last-modified date is not treated as a completion date. The score reflects the currently available workflow evidence; it is not a permanent event ledger or a measure of environmental impact. Explain any future changes to this model in the visible calculation details.

## Deployment and validation

No new database migration is needed. The service falls back to the checked-in configuration if an older Laravel config cache does not include `my_board` yet. The page uses its existing URL for refresh, so an older cached route name does not break the page. Deploy the service, configuration, views and both board assets together; clear compiled views after deployment if the old design remains.

Run `bash tools/risk-ai-dev.sh test` for the board and risk regressions. `tests/Feature/MyBoardTest.php` covers identity isolation, reviewer resolution, completed reviews, legacy dates, disabled/missing modules, authentication, score evidence and query scaling. It can produce isolated HTML previews through `MY_BOARD_PREVIEW_PATH` and `MY_BOARD_POPULATED_PREVIEW_PATH`.
