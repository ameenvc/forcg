# SaaS administration theme

The platform screens use the application's bundled OneUI theme. The company workspace keeps its existing layout.

- `resources/views/saas/layout.blade.php`: OneUI sidebar, header, account menu, footer, and asset loading.
- `resources/views/saas/login.blade.php`: responsive platform sign-in and password visibility control.
- `resources/views/saas/tenants.blade.php`: tenant summary cards, search/pagination, registration/edit dialogs, and activity.
- `resources/views/saas/partials/alerts.blade.php`: escaped status and validation messages.
- `public/css/saas-admin.css`: scoped SaaS styling layered over OneUI.
- `public/js/saas-admin.js`: address preview, password visibility, modal validation recovery, copy feedback, and navigation helpers.

The layout loads `jquery.min.js` followed by the existing `oneui.app.js` bundle, which already contains Bootstrap. Do not also load `oneui.core.min.js`: loading Bootstrap twice makes dropdowns toggle twice. No additional npm packages or frontend build are required for the SaaS-specific files.

The central layout does not inherit `layouts.backend`: the company layout loads tenant-specific data and integrations which are not part of platform administration. Counts and activity come from the central registry; payment information and unimplemented sections are not simulated.

All forms retain their existing Laravel routes, CSRF protection, and server-side validation. Dialog validation returns to the submitted form and preserves its values. The table scrolls horizontally within its container on small screens, while the page remains within the viewport. OneUI supplies modal focus handling and responsive navigation. Reduced motion, labelled fields, keyboard focus, and live status feedback are supported.

## Deployment

Pull the latest code, clear compiled Blade views with `php artisan view:clear`, and reload the browser. The SaaS hostname and administrator credentials are unchanged.

## Verification

The Laravel tenancy suites cover platform login, tenant lifecycle changes, and host/database boundaries. Browser verification additionally covers the desktop/mobile layouts, password visibility, native OneUI navigation and dialogs, registration validation and retained input, address preview, search, editing a plan, suspension, and sign-out.
