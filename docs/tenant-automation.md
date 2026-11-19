# Automatic workspace setup and owner password resets

## Dashboard flow

Register a tenant with its owner email, hosting location, plan, and initial owner password (minimum 12 characters, confirmed twice). Registration and its provisioning request are saved in one central transaction. The dashboard shows **Setup: queued** until the assigned server processes it, then **running**, **succeeded**, or **failed**. Refresh the directory to see updates; there is no live push connection.

The regional processor creates the database, runs the company migrations/reference seeders, creates the owner in `users` and `admins`, removes seeded platform credentials, and activates the tenant. It uses the same provisioner as `tenants:provision`. Database creation never reuses or drops an existing database. Failed setup preserves any partial database for operator inspection and does not retry automatically.

For older pending registrations, **Set up** in the directory asks for the initial password and queues the same process. CLI registrations without a password remain pending until an operator uses Set up or the interactive CLI.

For a provisioned client, select **Reset password** in the directory. The dialog identifies the organization, domain, and registered owner email. Enter and confirm the new owner password, then submit. The assigned server changes that owner's password in both login tables in one transaction and rotates remember tokens. The tenant's active/suspended status is preserved. Other employees, other tenants, and platform administrator passwords are unchanged. Missing or duplicate owner records cause failure and rollback, requiring operator review.

Share passwords through your organization's secure channel. Passwords are not displayed, emailed, written to action logs, flashed back into forms, or stored in plaintext. Only a one-way password hash is queued; it is erased from the operation when processing finishes. Existing signed-in browser sessions may remain active after a reset; this action does not provide global session revocation.

## One-time deployment

Deploy this code to the platform and every hosting node, then upgrade the shared registry once:

```bash
php artisan config:clear
php artisan migrate --database=saas_central --path=database/migrations/central --force
php artisan config:cache
php artisan view:clear
php artisan queue:restart
```

On **every hosting node**, enable Laravel's scheduler under the application service account (adjust paths and PHP binary):

```cron
* * * * * cd /path/to/forcg && /usr/bin/php artisan schedule:run >> /var/log/cgbod-scheduler.log 2>&1
```

The new scheduled `tenants:process-operations` command polls the shared registry every minute. It is separate from the ordinary application job queue so it can provision pending tenants before they have a company database. Each server processes only operations matching its `TENANCY_NODE_ID`, and validates the tenant's current assignment again before touching data. The processor can also be run manually:

```bash
php artisan tenants:process-operations
```

No tenant-by-tenant SSH command is needed after the scheduler is configured. Setup starts on the next scheduler run; a batch processes up to ten actions. Use appropriate application permissions and a database account authorized to create tenant databases on that regional server. Keep ordinary tenant queue workers running as documented in [regional hosting](regional-hosting.md).

The platform stores operation ID, tenant ID, requesting administrator, hosting assignment, action, status, and timestamps in `saas_operations`. The activity log records requests and results without credentials. Dashboard endpoints require platform authentication, CSRF validation, password confirmation, and rate limiting. Duplicate pending actions for one tenant are rejected under a registry row lock. Concurrent processors atomically claim queued actions so each action executes once.

If a process crashes, its operation remains `running` rather than being automatically replayed. Inspect the regional database and logs before repairing that operation. Do not blindly reset it to queued: initial setup may already have created a database or owner account. Operation failures log the operation ID and exception class only; use server-side diagnostics to investigate without exposing customer data or credentials. Monitor scheduler execution and queued/running operations operationally.

## DNS and HTTPS

Application/database setup is automatic with the above deployment. DNS records, TLS certificates, and cloud servers are not created by this processor.

For tenants on one ingress, configure `*.cgbod.com` once, with a matching HTTPS certificate, and point `devsaas.cgbod.com` explicitly to the platform. New tenant names then need no separate DNS change. For tenants spread across regional ingresses, use individual tenant records or a DNS/ingress automation service that sends each tenant to its assigned region. Configure certificates on those ingresses in advance or use the hosting provider's managed TLS.

Provider integration requires the actual DNS/hosting provider and appropriately scoped credentials configured outside the application repository. Never paste those credentials into chat or tenant forms. The public address remains `https://<slug>.cgbod.com` in every region. Provisioning success means the database/account is ready; confirm DNS and HTTPS before sharing the workspace URL.
