# Risk form AI integration

The Add Risk form includes an optional assistant. It creates reviewable suggestions from the concern, selected group/department/source, and entered existing controls. It does not read organization records, change selected ratings, save risks, or trigger approvals. If both impact and likelihood are selected, the response includes the deterministic matrix result for those inputs. Proposed controls remain separate and cannot be applied to the existing-controls field.

## Configuration

Set these through secure deployment configuration, not tracked files:

```dotenv
RISK_AI_ENABLED=true
RISK_AI_MODE=demo
```

Demo is a fixed template demonstration, not a model call. For actual AI, use `RISK_AI_MODE=openai`, `RISK_AI_API_KEY`, and optionally `RISK_AI_MODEL` (default `gpt-4.1-mini`). Restart workers/refresh Laravel configuration after changing settings. Submitted text goes to api.openai.com; enable only after approving that data handling. Provider credentials stay server-side. Live provider access requires a valid configured key and has not been verified.

Sign in using an existing local test user and complete MFA, then open `/user/risks/addrisk`. Click **AI Assist** beside the Add Risk heading to open the panel. It pre-fills the concern from the current description or name when empty. Enter a concern, prepare a draft, and apply selected fields. Close and reopen the panel to retain your draft. The option stays visible when disabled and explains that administrator enablement is needed; drafting remains blocked by the server feature flag. Existing field content requires replacement confirmation. Submit remains the normal application workflow. Endpoint `POST /user/risks/ai/draft` uses web CSRF, auth:web,admin, MFA, a 10-per-minute throttle, input validation and a server-side feature flag (404 when disabled). Provider failures return a generic 502. No new cross-organization queries are introduced. Fine-grained module authorization should be aligned with deployment policies before enabling for production.

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

The pinned PHP 7.4 development runtime installed all 149 locked Composer packages with scripts and plugins disabled. The existing lockfile emits a stale-content warning; it was preserved. The targeted suite passes **12 tests and 44 assertions**, including all matrix cells, mocked provider validation, authenticated draft generation, guest rejection, feature disablement, invalid input, MFA and CSRF. Real HTTP checks verified the JS asset (200) and unauthenticated draft rejection (401 JSON). A leading blank line in the edited routes file was removed because it emitted output before HTTP headers. No live provider call or browser interaction was verified.

Fresh database initialization is blocked by existing migration defects. SQLite fails in `2021_09_01_135248_add_email_to_compliance_table.php` when adding a required column. On local MariaDB 10.11, `2022_06_21_130727_change_riskarea_to_its_id_in_risks_table.php` uses the current soft-deleting Risk model before `deleted_at` exists; the column is only added by a 2026 migration. Historical migrations were not modified. Use a sanitized development database snapshot, or resolve migration compatibility separately, before testing the complete Add Risk page. The successful feature tests use minimal in-memory database fixtures and do not establish that the full application schema is ready.

The current workspace has a generated, ignored `.env` pointing to an isolated `cgbod-db` MariaDB container on Docker network `cgbod-dev`, with demo AI enabled. No customer database was used. This database is only partially migrated. Processes must restart after restoring an environment; `docker start cgbod-db` restarts the existing database container if retained, followed by the serve helper. Recreating the database in a new environment requires secure local credentials and initialization; no credentials are included in this document.

The environment configuration draft is stale, so configuration persistence remains blocked. Preserve the integration files and reconcile the handoff configuration in a new setup chat opened from current environment settings. Do not restore the removed `forcg` repository.
