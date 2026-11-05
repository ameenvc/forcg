# Shared application typography

All shared user/admin/superadmin shells load app-typography.css once via shared.theme.assets. They use a same-origin Inter UI variable font instead of repeated external Google Fonts stylesheets. Its locally hosted WOFF2 is about 139 KiB, preloaded once, supports weights 400–700, and uses font-display: swap. OFL licensing, modification details and fallback-script coverage are included in public/fonts/inter.

The shared scale uses a 14px body, 13px labels and normal buttons, 12px compact tables/helpers, and 24–30px page headings. Cards and sections use 17–20px headings. Form controls use 14px on desktop and 16px on mobile. Numeric table/chart values use stable sizing. Legacy inline 9–11px table labels are raised to 12px. Compact risk register rows remain 48px; rating/stage column widths accommodate the readable type, and rating tabs can scroll on narrow screens rather than clipping their text.

The font is applied to common controls, navigation, Select2, editors, modal details and chart labels. Font Awesome/simple-line icon fonts and monospace code remain independent. User-selected inline editor fonts and standalone export/PDF templates retain their own formatting. Character coverage includes Latin/extended Latin and combining accents, Greek, Cyrillic, punctuation and common symbols; other scripts use native system fallback fonts.

Chart.js labels, titles, tooltips and axes use the same UI family and redraw after the font loads. Theme switching continues to preserve data colours and restores print/light palettes. SVG chart labels receive the shared family through CSS.

Validation: single local font request and no Google Fonts request, successful browser font load, icon/code font preservation, 12px table labels, full Moderate rating labels, fixed compact row heights, mobile 16px controls, and layouts at 320/390/1440px. Shared-layout tests cover global inclusion. Actual Chart.js, Select2 and DataTables retain functionality and theme/print behaviour in browser checks. Live hosting and every bespoke page have not been visually audited.
