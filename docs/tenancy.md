# CGBOD SaaS deployment and operation

The existing Laravel application now supports a central administration site and a separate database, login session, cache, and upload directory for each company. SaaS mode is opt-in; leaving `TENANCY_ENABLED=false` retains the existing single-company installation.

| Host | Purpose |
| --- | --- |
| `devsaas.cgbod.com` | Platform login, tenant registration, plans, activation/suspension and activity |
| `leanston.cgbod.com` | Leanston's existing CGBOD modules and company-user login |
| `othercompany.cgbod.com` | Another company with its own database and files |

`TENANCY_HOST_PREFIX=""` is the default: a tenant named `waraq` uses `waraq.cgbod.com`. Set `TENANCY_TENANT_BASE_DOMAIN=cgbod.com` independently of the admin hostname. Legacy nested addresses remain available only when `TENANCY_HOST_PREFIX=tenant.` is explicitly configured. Unknown, pending, failed, and suspended tenant hosts return 404. Company routes are unavailable on the platform host, and platform routes are unavailable on company hosts. Slugs and database assignments cannot be edited through the web interface.

## Custom platform hostname: devsaas.cgbod.com

To use `https://devsaas.cgbod.com/login`, deploy the latest code and configure:

```dotenv
APP_URL=https://devsaas.cgbod.com
TENANCY_ENABLED=true
TENANCY_BASE_DOMAIN=cgbod.com
TENANCY_TENANT_BASE_DOMAIN=cgbod.com
TENANCY_HOST_PREFIX=""
TENANCY_ADMIN_DOMAIN=devsaas.cgbod.com
TENANCY_SCHEME=https
SESSION_SECURE_COOKIE=true
```

`TENANCY_ADMIN_DOMAIN` is a hostname only, without `https://`, a port, or `/login`. It defaults to `admin.<TENANCY_BASE_DOMAIN>` when unset. This changes the platform host; company hosts remain `<slug>.cgbod.com`. Update Apache/Nginx `ServerName`/`server_name` and the TLS certificate to cover the chosen platform hostname.

After updating the server environment, run `php artisan config:clear`, `php artisan config:cache`, and `php artisan queue:restart`. If the platform administrator has not been created, run `php artisan saas:install your-email@example.com` and choose your password at the prompt. Deploying code does not create a default account.

## 1. Prepare the application and central database

Use the existing project's PHP/Composer requirements and required extensions, including PDO MySQL, mysqli, GD, mbstring, XML, ZIP and bcmath. Install dependencies with `composer install`, configure an application key, and make `storage` and `bootstrap/cache` writable by PHP.

Back up the existing database and uploads before changing deployment configuration. Create a **new, empty MySQL database** such as `cgbod_platform`. Do not point the platform registry at the existing company's database.

Configure `.env`:

```dotenv
APP_URL=https://devsaas.cgbod.com
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cgbod_platform
DB_USERNAME=cgbod_service
DB_PASSWORD=<configured on the server>
TENANCY_ENABLED=true
TENANCY_SOURCE_CONNECTION=mysql
TENANCY_ADMIN_DOMAIN=devsaas.cgbod.com
TENANCY_BASE_DOMAIN=cgbod.com
TENANCY_TENANT_BASE_DOMAIN=cgbod.com
TENANCY_HOST_PREFIX=""
TENANCY_DATABASE_PREFIX=cgbod_
TENANCY_SCHEME=https
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database
```

The `saas_central` connection is an immutable copy of `TENANCY_SOURCE_CONNECTION`; it always accesses the platform database. If `DATABASE_URL` is configured, update it to the platform database as well; it overrides individual `DB_*` settings. The service database account needs access to tenant databases. Database creation requires `CREATE DATABASE`; keep provisioning an operator action if the web runtime should not have DDL privileges. Never expose MySQL to tenant users.

```bash
php artisan config:clear
php artisan saas:install platform-admin@example.com --name="Platform administrator"
```

The installer runs **only** `database/migrations/central`, then prompts for a platform password of at least 12 characters. It does not run the company's legacy seeders on the platform database. No default platform password is installed. Keep a dedicated platform database; do not run the ordinary company `migrate` or `db:seed` commands against it.

## 2. Move the current company into a tenant

The existing database can be adopted without deleting records or changing users' passwords. For example, if the current company database is named `existing_cgbod`:

```bash
php artisan tenants:adopt leanston "Leanston" existing_cgbod owner@leanston.com \
  --files=/absolute/path/to/old/public/storage
```

This registers and validates the existing database, copies the old uploads into `storage/tenants/leanston/public/storage`, and activates the tenant. The source files and database records are retained. Run provisioning/adoption as the same operating-system account as PHP-FPM, or configure shared writable ownership of `storage/tenants`; the private tenant directories use mode `0770`. It refuses the central database or a database already attached to a tenant. The copied upload directory must contain only that company's files. Copy any legacy `public/files` or `public/uploads` data into the corresponding private tenant directory as well; no shared public directory should retain customer data after cutover. Keep old copies in a backup outside the web document root.

Users sign in at `https://leanston.cgbod.com/login` using their existing company credentials. Legacy company-superadmin accounts are not SaaS platform accounts and cannot log in on tenant hosts. Use company administrator accounts for workspace access and `saas:install` for platform administrators.

## 3. Create additional tenants

Sign in at `https://devsaas.cgbod.com/login`. Register the organization, slug, owner email, and plan. Alternatively:

```bash
php artisan tenants:create anothercompany "Another Company" owner@example.com --plan=starter
php artisan tenants:provision anothercompany
```

Provisioning prompts for the owner's password, creates a new database, runs the company migration history and reference seeders, and creates matching administrator/user records for the owner. The tenant is activated only after initialization succeeds. Provisioning uses an atomic claim and never overwrites or drops an existing database. On failure it marks the tenant failed and preserves any partially created database for operator inspection. Do not blindly reactivate a failed row; repair the initialization or register a new tenant/database after reviewing the failure.

For sites that maintain a validated, sanitized baseline instead of replaying the legacy migrations:

```bash
php artisan tenants:provision anothercompany --schema=/private/cgbod-baseline.sql
```

A baseline must contain the complete schema and reference data, with **no customer data or user/admin accounts**. It must not contain `USE`, `CREATE DATABASE`, or `DROP DATABASE` statements. This is a trusted operator input, never a tenant upload. Database provisioning is deliberately separate from web registration to keep long-running migrations out of HTTP requests.

Plans are stored as administrative metadata (`starter`, `professional`, `enterprise`). Payment collection, invoices, and automatic subscription enforcement are not connected to a payment provider in this change.

## 4. DNS, HTTPS, and the web server

Point `devsaas.cgbod.com` to the platform server. Point each tenant hostname, such as `waraq.cgbod.com`, to that tenant's assigned hosting server. Separate A/AAAA or CNAME records allow different tenants to use different servers and regions. A wildcard record may be used for a default server, with explicit per-tenant records overriding it.

A TLS certificate for `*.cgbod.com` covers single-level hosts such as `waraq.cgbod.com` and `devsaas.cgbod.com`. The optional legacy nested format (`tenant.waraq.cgbod.com`) needs its own matching certificate; the root wildcard does not cover it. This code does not change DNS or certificates.

For regional hosting, use the [hosting-node setup guide](regional-hosting.md). A hosting choice is an assignment, not an automatic server deployment or data migration.

Set the document root to Laravel's `public` directory. Deploy one of the examples:

- `deploy/saas-apache.conf.example`: enable mod_rewrite, mod_setenvif, SSL, and `.htaccess`. The `CGBOD_SAAS` flag makes the provided `.htaccess` route `/storage`, `/files`, and `/uploads` through Laravel, even if legacy public files exist.
- `deploy/saas-nginx.conf.example`: merge its protected upload locations with your server's TLS and PHP-FPM configuration.

Do not create a shared `public/storage` symlink for tenant files, add static aliases to private tenant directories, or expose `storage/tenants`. File URLs remain `/storage/...`, `/files/...`, and `/uploads/...`, but are resolved within the current tenant. Downloads require a company login; tenant login logos under `storage/clientlogos` are the only public branding exception. Traversal and symlink escapes are rejected and active uploads are downloaded with `nosniff` and a sandbox policy. Access within a tenant continues to use the existing application's permissions; the new file route enforces tenant membership rather than individual record permissions.

After configuration changes:

```bash
php artisan config:cache
php artisan view:clear
php artisan queue:restart
```

The existing application contains closure routes; do not assume `route:cache` is supported. Use standard PHP-FPM/Apache request lifecycles. Long-lived HTTP workers such as Octane have not been validated.

## 5. Queues, scheduled tasks, and updates

Drain old queued jobs before enabling SaaS mode. New payloads carry `tenant_id`; the tenant-aware worker selects the database and paths **before** deserializing queued models and keeps that context through job/failure listeners. Cleanup runs in `finally`. Suspended tenant jobs are released without executing. Their attempts still follow Laravel's retry settings, so choose a retry budget appropriate for operational suspensions.

Run the standard database worker under your process manager:

```bash
php artisan queue:work database --sleep=3 --tries=3
```

Central queue tables and failure records are installed by `saas:install`. Database queues are partitioned as `tenants-<TENANCY_NODE_ID>`. Run a worker on each hosting node; its default queue uses that node ID. Drain the previous `default` queue before upgrading, or have the original local worker process it explicitly during cutover. Sync jobs remain supported inside the active request. Other queue transports retain the tenant payload hook, but this conversion was tested with the database transport.

The existing scheduled tasks automatically run through `tenants:run ... --all`, activating only companies assigned to the current node in turn. Run the scheduler on each hosting server. Keep your standard `php artisan schedule:run` cron entry. To run an allowed application task manually:

```bash
php artisan tenants:run documentreadrequestemailnotification:daily --tenant=leanston
```

Direct invocation of company tasks without tenant context is rejected. CLI commands do not infer a company from `APP_URL`. Existing application commands are allowlisted in `tenancy.tenant_commands`; add new company commands there before using them through the wrapper. Framework and platform commands are not accepted by the tenant task wrapper.

Apply future company schema updates explicitly:

```bash
php artisan tenants:migrate --tenant=leanston
php artisan tenants:migrate --all
```

The tenant migration runner refuses an unspecified tenant selection and only visits databases assigned to the current node. Back up databases and test migrations on staging first. Platform registry migrations remain separate under `database/migrations/central`.

## Upgrade an existing SaaS registry

After pulling this version, keep the existing server as `TENANCY_NODE_ID=local`. Add `TENANCY_TENANT_BASE_DOMAIN=cgbod.com`, set `TENANCY_HOST_PREFIX=""`, and retain `TENANCY_ADMIN_DOMAIN=devsaas.cgbod.com`. Clear cached configuration, apply the central schema upgrade, then rebuild caches:

```bash
php artisan config:clear
php artisan migrate --database=saas_central --path=database/migrations/central --force
php artisan config:cache
php artisan view:clear
php artisan queue:restart
```

The upgrade adds a nullable hosting-node field without rewriting existing tenants. Existing records with no assignment belong to `local`. Changing the hostname format requires matching DNS and HTTPS setup; it does not migrate customer data or preserve login sessions on the previous hostname.

## Local development and checks

Add to your computer's hosts file:

```text
127.0.0.1 devsaas.cgbod.test leanston.cgbod.test othercompany.cgbod.test
```

Use `TENANCY_BASE_DOMAIN=cgbod.test`, `TENANCY_TENANT_BASE_DOMAIN=cgbod.test`, `TENANCY_ADMIN_DOMAIN=devsaas.cgbod.test`, `TENANCY_HOST_PREFIX=""`, `TENANCY_SCHEME=http`, `APP_URL=http://devsaas.cgbod.test:8000`, and `SESSION_SECURE_COOKIE=false` locally. Initialize the platform and adopt/provision the tenant as above. Start the provided local router so legacy shared files cannot bypass Laravel:

```bash
php -S 127.0.0.1:8000 -t public tools/saas-router.php
```

Open `http://devsaas.cgbod.test:8000/login` or `http://leanston.cgbod.test:8000/login`. Tenant URLs retain the development port from `APP_URL`. Omit the explicit port when deploying on standard HTTPS port 443.

```bash
vendor/bin/phpunit tests/Feature/Tenancy
vendor/bin/phpunit tests/Unit/TenantHostnameTest.php
```

The isolation tests use separate SQLite databases and cover matching record IDs, cross-tenant login rejection, file/cache isolation, unknown/suspended hosts, platform/tenant route boundaries, central management, queued model restoration, failed-job cleanup, and verified single-use password resets. The conversion was additionally exercised against a temporary MariaDB instance with the complete application migration/seed history and fresh owner provisioning. Historical data migrations that incorrectly depended on later model scopes were corrected to use query-builder reads/writes.

## Operational limits

This implements application multitenancy, not a payment service or a hosting control panel. DNS, TLS, server deployment, database permissions, backups, and SMTP still need real server configuration. Existing business workflows and within-company authorization remain the application's responsibility. The legacy arbitrary SQL/ZIP restore and platform-reset endpoints are blocked on tenant hosts; restore tenant backups through controlled server operations. Review custom storage integrations and new maintenance commands before adding them to this deployment.

## Dashboard automation

Tenant registration can queue automatic database/account setup on its assigned server. The directory also provides a client owner password-reset dialog and action status. Deploy the new central migration and run the scheduler on every node. See [automatic setup and owner password resets](tenant-automation.md) for setup, failure handling, and DNS/HTTPS boundaries.
