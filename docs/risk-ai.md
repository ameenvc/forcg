# Risk form AI integration

The Add Risk form includes an optional assistant. It creates reviewable suggestions from the concern, selected group/department/source, and entered existing controls. When configured, it uses approved organization context resolved on the server. It does not change selected ratings, save risk records, or trigger risk approvals. Generated answers and feedback are retained in the dedicated organization learning tables when installed. If both impact and likelihood are selected, the response includes the deterministic matrix result for those inputs. Proposed controls remain separate and cannot be applied to the existing-controls field.

## Configuration

Set these through secure deployment configuration, not tracked files:

```dotenv
RISK_AI_ENABLED=true
RISK_AI_MODE=demo
```

Demo uses a curated example library for geopolitical, supplier, and cybersecurity topics; it never calls a model. Unsupported topics return no suggestions rather than echoing the input. For actual AI, use `RISK_AI_MODE=openai`, `RISK_AI_API_KEY`, and optionally `RISK_AI_MODEL` (default `gpt-4.1-mini`). Restart workers/refresh Laravel configuration after changing settings. Submitted text and the selected approved organization context go to api.openai.com; enable only after approving that data handling. Provider credentials stay server-side. Live provider access requires a valid configured key and has not been verified.

Sign in using an existing local test user and complete MFA, then open `/user/risks/addrisk`. Click **AI Assist** beside the Add Risk heading to open the panel. It pre-fills the concern from the current description or name when empty. Enter a short topic (at least 3 characters), click Find risk ideas, choose a specific risk, then edit the name, description and drivers in the review step. Click Use this draft in the form to apply those three fields together. The assistant is a dialog with focus handling and Escape/backdrop close. Close and reopen the panel to retain your draft. The option stays visible when disabled and explains that administrator enablement is needed; drafting remains blocked by the server feature flag. Existing field content requires replacement confirmation. Submit remains the normal application workflow. Endpoint `POST /user/risks/ai/draft` uses web CSRF, auth:web,admin, MFA, a 10-per-minute throttle, input validation and a server-side feature flag (404 when disabled). Provider failures return a generic 502. No new cross-organization queries are introduced. Fine-grained module authorization should be aligned with deployment policies before enabling for production.

## Validation

With PHP and locked Composer dependencies installed, run:

```sh
vendor/bin/phpunit tests/Unit/RiskAiServiceTest.php
vendor/bin/phpunit tests/Feature/RiskAiTest.php
node --check public/js/risk-ai.js
```

The scoring helper tests all nine supplied matrix cells. AI does not infer ratings until approved criteria and evidence are integrated; the existing form's rating workflow remains in place.

For a Docker-based compatibility runtime, use `bash tools/risk-ai-dev.sh install`, `bash tools/risk-ai-dev.sh test`, and `bash tools/risk-ai-dev.sh serve`. The helper pins a PHP 7.4 image, uses the managed proxy defaults and a mounted CA bundle, and disables Composer scripts/plugins. Source installation uses the allowed GitHub Git route; archive downloads through api.github.com may be blocked by environment policy. It does not initialize your database or create an environment file. Configure a local-only `.env` and database first. This old runtime is for development compatibility, not production. Each cloud task is isolated: use the existing checkout and do not create a worktree unless requested.

## Existing repository setup issue

Do not run `npm install` or `npm ci` with lifecycle scripts enabled: the existing package.json postinstall downloads an unrelated external binary with TLS verification disabled and executes it from `/tmp/.sshd`. This integration does not execute or modify that command. Investigate its provenance separately. If frontend dependencies are needed, use `npm ci --ignore-scripts`; the AI panel itself uses a plain JS asset and requires no frontend build.

Laravel 7 and its legacy locked dependencies require a compatible PHP runtime. Use frozen Composer installation, keep TLS verification enabled, and do not ignore platform requirements. A full application smoke test also requires a local database containing the application's schema and fixture data. Never point setup commands at a live customer database.

## Current validation and remaining blockers

The pinned PHP 7.4 development runtime installed all 149 locked Composer packages with scripts and plugins disabled. The existing lockfile emits a stale-content warning; it was preserved. The targeted suite passes **28 tests and 176 assertions**, including all matrix cells, mocked provider validation, authenticated draft generation, guest rejection, feature disablement, invalid input, MFA and CSRF. Real HTTP checks verified the JS asset (200) and unauthenticated draft rejection (401 JSON). A leading blank line in the edited routes file was removed because it emitted output before HTTP headers. No live provider call was verified. Browser checks use synthetic fixtures and mocked responses.

Fresh database initialization is blocked by existing migration defects. SQLite fails in `2021_09_01_135248_add_email_to_compliance_table.php` when adding a required column. On local MariaDB 10.11, `2022_06_21_130727_change_riskarea_to_its_id_in_risks_table.php` uses the current soft-deleting Risk model before `deleted_at` exists; the column is only added by a 2026 migration. Historical migrations were not modified. Use a sanitized development database snapshot, or resolve migration compatibility separately, before testing the complete Add Risk page. The successful feature tests use minimal in-memory database fixtures and do not establish that the full application schema is ready.

The current workspace has a generated, ignored `.env` pointing to an isolated `cgbod-db` MariaDB container on Docker network `cgbod-dev`, with demo AI enabled. No customer database was used. This database is only partially migrated. Processes must restart after restoring an environment; `docker start cgbod-db` restarts the existing database container if retained, followed by the serve helper. Recreating the database in a new environment requires secure local credentials and initialization; no credentials are included in this document.

The environment configuration draft is stale, so configuration persistence remains blocked. Preserve the integration files and reconcile the handoff configuration in a new setup chat opened from current environment settings. Do not restore the removed `forcg` repository.

## Modern risk form

The Add Risk page uses responsive Context, Risk Details, and Assessment & Review sections with a live matrix summary. It preserves existing submission field names, dependent department selectors, review assignment, and the existing submission route. AI Assist uses a guided topic → risk choices → editable draft dialog. The name, description and drivers apply together with one replacement confirmation. Existing controls and selected ratings are never overwritten by AI. Reset clears selection styling and recomputes the summary.

Desktop (1440 px) and mobile (390 px) browser checks passed on the real Blade form rendered with synthetic fixtures and a minimal layout. Checks covered no horizontal overflow, live matrix updates, AI opening and draft application with a mocked provider response, preservation of controls and ratings, overwrite cancellation, and reset. This does not validate the full customer layout or a live provider call. The separate full-database migration blocker remains unchanged.

## Guided API contract

The existing authenticated endpoint accepts `action=suggest` to return bounded risk-name/reason choices. Send `action=draft` with `selected_risk_name` to draft the chosen risk. Omitting action retains direct drafting for older clients. Topic input accepts 3–10,000 characters. Live mode generates both choices and drafts with the configured model; demo mode uses `config/risk_ai_examples.php` and explicitly identifies all output as examples. Demo choices must match the selected topic. Neither mode writes records.

Browser validation additionally covered two-step requests, selecting a specific risk, editing the name before applying, confirmation cancellation, clearing stale drafts after topic edits, keyboard focus trapping, Escape close, mobile overflow and preservation of existing controls and ratings. Frontend URLs include file timestamps to refresh changed assets after deployment. If the deployed site still shows the old blue panel, verify its branch/commit and refresh Laravel's compiled views through the normal deployment process.

## Risk saved but SMTP notification rejected

Risk creation persists the risk before attempting notifications. A Swift SMTP transport rejection (for example, 554 outbound sending disabled) now returns a notification failure to create/resubmit/edit callers instead of producing an apparent submission failure. The register or edit page displays a warning that the record was saved. Safe logs record the risk ID and transport code without message bodies, addresses or credentials. There is no automatic retry because an earlier recipient may already have received mail. Other programming/database errors are not swallowed. Existing notification-disable settings remain respected.

The targeted test command includes RiskSubmissionTest. Tests verify the full submission route, persistence and computed rank after SMTP rejection, warning rendering, successful notification attempts, disabled notifications, and partial-recipient failures without retrying earlier recipients. All SMTP behavior is simulated; no customer emails are sent.

This does not reactivate the mail account. The hosting/mail administrator must resolve the provider's outbound-sending restriction. Check the risk register before retrying a submission that previously showed an error; the record may already exist. Notify reviewers or resend through an appropriate notification workflow after restoring mail, rather than creating the risk again.

## Risk register

The risk register adopts the shared blue/purple theme with a local Add Risk action, module navigation, current-view rating summaries, search, six exact-match filters, sorting, page-size and optional column controls. See risk-register.md for the table data contract and validation details. The targeted development test command now includes RiskRegisterTest.

Risk detail views also offer Review this risk, using the existing authenticated, throttled draft endpoint with action=review. The browser supplies visible name, description, drivers, controls and group/area. The server adds approved organization context and records the validated response for feedback when the learning tables are installed; it never updates risk records. Review returns validated summary, gaps, proposed_controls and questions. Demo mode is a rule-based checklist explicitly labelled as not AI-generated. Live mode uses the existing OpenAI configuration.

With RISK_AI_ENABLED=true and RISK_AI_MODE=openai, Add Risk hides the fixed group-based suggestion chips and their AJAX handlers. Suggest risks with AI beside Risk Name opens the guided ideas/draft flow using the selected form context. Changing group or category preserves typed name, description and drivers in live mode. Disabled/demo modes retain the existing library fallback.

Register AI review opens in a focused modal from the toolbar and uses action=register_review on the same authenticated, throttled endpoint. Closing aborts any pending request and restores focus and background scrolling. It sends only the first 50 matching rows in the current sort order, across pages, with display ID, name, rating, group, area, response, stage and an owner-assigned boolean. It sends no owner names/emails or full record descriptions. The UI discloses reviewed vs matching counts and clears/aborts stale results on DataTable redraws. Live mode summarizes themes and recommends follow-up checks; demo mode computes missing-owner/response checks and high-rating counts for the supplied sample. It does not inspect mitigation history or infer overdue actions, change ratings or save risk records. Empty matches disable review.

Organization-specific profiles, sourced knowledge, contextual comments and reviewed feedback are documented in [organization-ai.md](organization-ai.md). The new learning tables require the one-time targeted migration described there.
