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

`bash tools/risk-ai-dev.sh test` passes 28 tests / 176 assertions, covering the AI, SMTP-failure handling and register rendering. Register tests check create/export links, existing permission-dependent actions, empty views and 13-cell row/header consistency using synthetic data and a minimal layout.

Browser checks with the real local DataTables assets and 31 synthetic rows passed search, rating/group/owner filtering, filtered export IDs, no-result display, numeric severity sorting, filtering hidden columns, pagination selection, clear-on-filter behavior, delete cancellation, partial failures with mocked requests, visible row menus, and desktop/mobile overflow checks. No real risks were deleted and no customer exports were generated during tests. These checks do not establish production deployment or resolve the separate fresh-database migration blocker.

## Content-aware row layouts

The overview is a compact distribution strip with counts, percentages and a proportional rating bar, rather than separate large dashboard cards. It summarizes the loaded register view before table filters.

Standard rows are 64 px high and clamp risk names, group, area and response to two lines. Compact rows are 48 px high with a one-line preview. Full text deliberately removes the clamp and allows automatic height. Full strings remain in the DOM/search data, native title text and the existing detail view; nothing is shortened in storage or exports. Row widths include padding to avoid pushing the final data columns off the desktop view.

One owner is shown in the row; additional owners open in a keyboard-accessible native details popup. All owner names and employee IDs remain in filtering/search metadata. Opening the popup does not increase row height. It closes on scrolling, paging, outside clicks or Escape.

Stress validation uses long and unbroken risk names, long groups/areas/responses, a long primary owner name and four owners. Browser measurements confirmed all standard rows at 64 px and compact rows at 48 px on desktop and mobile; variable heights appeared only after explicitly selecting Full text. Tests also checked that additional owners are visible and searchable, that the popup is not clipped, and that summary labels/counts fit on mobile.
