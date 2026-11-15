# CGBOD Laravel SaaS

A Laravel application for corporate governance, audit, risk, compliance, and ISO management, with separate company workspaces on tenant subdomains.

- `admin.cgbod.com`: platform administration and tenant lifecycle management.
- `tenant.leanston.cgbod.com`: a company's CGBOD workspace.
- Each company uses its own database, login sessions, cache, and private upload directory.

## Setup

Follow the [SaaS setup and migration guide](docs/tenancy.md) to configure PHP/MySQL, create the platform database, adopt an existing company, provision tenants, and configure DNS/HTTPS. SaaS mode defaults to disabled until a separate central database is configured.

The runnable application includes the Laravel framework entry points, existing company modules, migrations and reference seeders, templates, assets, and dependency manifests. Those files are required for the tenant workspaces.

## Repository structure

| Path | Purpose |
| --- | --- |
| `app/Tenancy` | Tenant resolution, database/storage context, and queue worker |
| `app/Http/Controllers/Saas` | Platform management and tenant file/password endpoints |
| `app/Console/Commands/Saas` | Platform installation, provisioning, adoption, and tenant tasks |
| `config/tenancy.php` | SaaS configuration |
| `database/migrations/central` | Platform database migrations |
| `routes/saas.php` and `resources/views/saas` | Platform routes and Blade views |
| `app`, `resources`, `routes/web.php` | Existing company application |
| `database/migrations` and `database/seeders` | Company schema and reference data |
| `deploy` | SaaS Apache and Nginx configuration examples |
| `tests/Feature/Tenancy` | Tenant isolation and lifecycle tests |
| `docs` | SaaS deployment and company module documentation |

## Dependencies and assets

Install PHP dependencies using the existing `composer.lock` and a compatible PHP runtime with the extensions required by `composer.json`.

Prebuilt frontend assets remain in `public`. If rebuilding them:

```bash
npm ci --ignore-scripts
npm run production
```

The dependency manifests and Laravel Mix source files are retained so the application can be installed and rebuilt.

## Verify tenancy

With Composer dependencies installed:

```bash
vendor/bin/phpunit tests/Feature/Tenancy
vendor/bin/phpunit tests/Unit/TenantHostnameTest.php
```

For local serving, use the [tenant-aware development router](tools/saas-router.php) with the hosts-file and environment settings in the setup guide.
