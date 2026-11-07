# CGBOD Superadmin

Responsive SaaS superadmin prototype, built with vanilla JavaScript and CSS.

Run `npm start` to serve on port 3000 (`PORT` overrides it). `npm run build` checks JavaScript syntax. No installation is required.

Includes overview analytics, tenant creation/editing/status management, search and filters, CSV export, subscription plans, sample invoice downloads, local team invitations, support tickets, audit activity, and preferences. Changes are persisted in browser localStorage. Clear site storage to reset demo data.

This is a frontend demo. Metrics, service health, invoice statuses, and initial accounts are sample data. Invitations do not send email. Production use requires server-side authentication and authorization, tenant isolation, a database, payment and email providers, actual telemetry, and an immutable audit log.
