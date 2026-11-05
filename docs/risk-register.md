# Risk register redesign

Only the enterprise risk register adopts this table design. Other modules and their permissions are unchanged. The page uses the shared blue/purple form palette with a top-right Add Risk action, Register/Closed Risks/Analysis navigation and a Learn Module button.

Rating cards summarize the authorized records already loaded for the current register view, including any server-side rank/group query filters. They do not claim organization-wide totals. Search, group, area, rating, owner, response and stage filters operate on the existing DataTable. Clearing client filters does not remove server-side page filters; a separate Clear page filters link is available when applicable.

## Controls and behavior

- Search and filters combine; filters clear existing selection so hidden records are not unintentionally acted on.
- Select-page selects only the current page. Selection can span pages and the selection bar explicitly notes this.
- Optional columns and row numbers can be toggled for the current page session. Primary identity, name, rating and action columns stay available.
- Highest-rating sorting uses numeric severity rather than alphabetical labels. Default order preserves the controller-provided record order.
- PDF, Excel and Word links always carry the IDs of all matching rows across pages. This also preserves an initial server-filtered view. No-result exports are disabled in the UI.
- Existing edit/delete gates and per-row status/owner checks remain in place. Request edit uses the existing batch-edit URL. Delete requires a count confirmation, acts only on selected risk IDs, waits for each response, removes only confirmed successes and leaves failed rows selected. There is no automatic retry or deletion triggered by selection.
- Existing View, initial/periodic/closure review, resubmission, history, owner changes and mitigation links remain. Row action menus use fixed positioning so table scrolling does not clip them.
- Small screens scroll the table internally; search, navigation, summary cards and actions stay within the page width.

## DataTable contract

The table is initialized by `public/js/risk-register.js`, not the shared `.js-dataTable-full` initializer. Keep these data indices consistent with filters, sorting and exports:

| Index | Field |
| --- | --- |
| 0 | Row selection |
| 1 | Optional row number |
| 2 | Hidden internal risk ID (export/edit/delete identity) |
| 3 | Display risk ID |
| 4 | Risk name |
| 5 | Risk group |
| 6 | Risk area |
| 7 | Rating with numeric order metadata |
| 8 | Hidden legacy image slot |
| 9 | Combined owner avatar/name/employee ID |
| 10 | Response |
| 11 | Process stage |
| 12 | Actions |

Blade escapes filter values and row metadata. Owner filters match employee IDs within the owner search text. Backend query, export generation, record authorization and endpoint methods are not replaced by this UI change.

## Validation

`bash tools/risk-ai-dev.sh test` passes 46 tests / 2278 assertions, covering the AI, SMTP-failure handling and register rendering. Register tests check create/export links, existing permission-dependent actions, empty views and 13-cell row/header consistency using synthetic data and a minimal layout.

Browser checks with the real local DataTables assets and 31 synthetic rows passed search, rating/group/owner filtering, filtered export IDs, no-result display, numeric severity sorting, filtering hidden columns, pagination selection, clear-on-filter behavior, delete cancellation, partial failures with mocked requests, visible row menus, and desktop/mobile overflow checks. No real risks were deleted and no customer exports were generated during tests. These checks do not establish production deployment or resolve the separate fresh-database migration blocker.

## Content-aware row layouts

Rating counts are inline tabs inside the table toolbar: All risks, High, Moderate and Low. There is no separate summary panel, percentage display or distribution bar. Counts summarize the loaded register view before table filters, and selecting a tab applies that rating filter.

Compact rows are 48 px high with a one-line preview. Full strings remain in the DOM/search data, native title text and detail view; nothing is shortened in storage or exports. Row widths include padding to avoid pushing the final data columns off the desktop view.

One owner is shown in the row; additional owners open in a keyboard-accessible native details popup. All owner names and employee IDs remain in filtering/search metadata. Opening the popup does not increase row height. It closes on scrolling, paging, outside clicks or Escape.

Browser checks confirm compact 48 px rows, visible serial numbers continuing across pages, responsive detail layout, and review results/retry handling with mocked requests.

The register now uses compact 48 px rows only, with serial numbers visible by default and sequential numbering on filtered pages. The layout selector has been removed. Full content remains available through View and the full record. View uses a responsive detail dialog and a read-only review assistant for information gaps, proposed controls and reviewer questions. Demo mode uses a labelled checklist; live mode uses the configured provider.

AI review in the toolbar opens a modal for filtered-register analysis. It locks background scrolling, traps keyboard focus, closes with Escape/backdrop/Close, and restores focus to its trigger without changing the register height. Review scope is limited to the first 50 matching records in current sort order across all pages. Hidden columns remain available to the review context. Changing filters/sort/page clears old findings and prevents stale responses from displaying. Browser checks cover filtered payloads, cross-page scope, hidden fields, sample limits, request retry and empty matches.

## Closed risks and analysis

Closed risks uses the shared branded header and compact table with sequential numbers, search, exact group/area/rating filters, sort and page-size controls. PDF/Excel/Word exports include matching closed IDs across all pages, never the default current-register export. Empty matches disable exports. Multi-owner names remain searchable; View uses the existing detail endpoint and modal; Reopen keeps its existing route. Department relationships are eager-loaded.

Analysis builds a consistent snapshot from a cursor over minimal risk fields, supporting both standard creation timestamps and legacy date strings. The snapshot includes closed records and excludes soft-deleted records consistently. Missing department references/codes are counted as Unassigned and unknown/missing ratings as Unassessed. Counts reconcile across summaries and breakdowns. Interactive group selection updates the distribution and department bars; department search and count/name sorting work locally. The native charts include text counts and accessible descriptions, require no external chart library, and handle empty data. They show current stored ratings, not historical trends.

Validation covers the aggregate scope (including closed records, excluding deleted records, and retaining unassigned/unassessed records), empty pages, and retained closed-register routes/exports. Browser checks cover modal keyboard/scroll behavior, closed pagination and serial numbers, filters/exports, multi-owner search, View/Reopen, analysis group/search/sort behavior, empty data and desktop/mobile layout. Tests use synthetic records; live deployment was not verified.

## Current analysis and global theme

The analysis page presents the complete snapshot on page load, with rating distribution, group selection, workflow stages, response strategies, owner/response gaps and department comparison. Date/period/year controls, refresh/polling controls and the registration timeline have been removed. Legacy date query strings do not silently filter the page. The snapshot service retains date helpers for other callers; they are not exposed by this page.

System/Light/Dark is now one control in the application header, shared by user, admin and superadmin layouts and the alternate incident/context/OHS shells. Assets load once in the shared document head and the browser preference survives navigation across modules. Risk-local theme controls have been removed. Standalone export/PDF templates retain their own print styling.

The global compatibility layer covers common cards, forms, tables, modals, Select2/DataTables, editor toolbars, calendars, semantic status colours and legacy neutral inline backgrounds/text. Inline styles restore when returning to Light; deliberate status surfaces, images, embedded documents and canvas data colours are preserved. Chart.js axis/legend colours adapt to the theme, restore for print, and restore their original values in Light. The adapter observes dynamically added content without changing field values, text or record data.

Validation follows the inheritance chains of more than 400 user module views to ensure global theme wiring. Browser checks use representative forms, an editor, actual Select2, actual DataTables and actual Chart.js, verifying preserved values/filtering, reversible palettes, print colour restoration, desktop/mobile layout, cross-page preference persistence and the simplified analysis interactions. These checks do not constitute a production visual audit of every bespoke module page.

Organization AI is available from the risk navigation. Its approved profile/notes guide live form and review requests, and answer-level feedback can become shared knowledge only through an authorized, sourced review. See [organization-ai.md](organization-ai.md) for the one-time migration, permissions, organization namespace and persistence details.

Shared font loading and the cross-module type scale are documented in [ui-typography.md](ui-typography.md). The UI now serves a single local Inter variable font and preserves the compact register row layout.
