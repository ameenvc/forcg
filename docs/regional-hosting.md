# Tenant domains and regional hosting

## Public addresses

The platform and company domains are separate:

```dotenv
APP_URL=https://devsaas.cgbod.com
TENANCY_ADMIN_DOMAIN=devsaas.cgbod.com
TENANCY_TENANT_BASE_DOMAIN=cgbod.com
TENANCY_HOST_PREFIX=""
```

A tenant with slug `waraq` has public URL `https://waraq.cgbod.com`, regardless of which hosting node runs it. `APP_URL` is not used as the parent domain. The dedicated tenant setting overrides the older `TENANCY_BASE_DOMAIN` when both are present. All URLs in the registration preview, tenant directory, email generation, and CLI output use the same domain policy.

## Central registry and regional nodes

A **hosting node** is a deployment of the same Laravel application, with its own tenant database server, files, scheduler, and workers. All nodes connect to one central registry containing tenant identities, status, and hosting assignments. Use private, authenticated, TLS-protected connectivity to that registry. Keep registry and tenant database credentials in server configuration, never in the tenant web form.

Configure the same node catalog on the platform and every regional deployment. This is an example; entries represent servers your operators must actually provision:

```dotenv
TENANCY_HOSTING_NODES='{"local":{"name":"Original server","region":"Primary region"},"eu-1":{"name":"Europe server 1","region":"Europe"},"me-1":{"name":"Middle East server 1","region":"Middle East"}}'
```

On the existing server keep:

```dotenv
TENANCY_NODE_ID=local
```

Existing registry records with a null hosting assignment remain on `local`. Do not rename this ID without planning how those records will be assigned. The catalog must contain the current node ID. It contains descriptive names and regions only; it does not carry server passwords or control where network traffic is sent.

On a newly provisioned Europe server, for example:

```dotenv
TENANCY_NODE_ID=eu-1
TENANCY_TENANT_CONNECTION=tenant_host
TENANT_DB_HOST=<Europe tenant database hostname>
TENANT_DB_PORT=3306
TENANT_DB_USERNAME=<regional database account>
TENANT_DB_PASSWORD=<secret from server configuration>
# TENANT_MYSQL_ATTR_SSL_CA=/private/path/to/regional-ca.pem
```

The ordinary `DB_*` settings on that node point to the shared central registry. The `TENANT_DB_*` settings point to that node's tenant database server. The supplied `tenant_host` connection keeps them separate. If `TENANCY_TENANT_CONNECTION` is unset, the legacy same-database-server behavior is retained for the original deployment.

The HTTP resolver and database context reject tenants assigned to another node before opening their database or creating tenant storage directories. A mistaken DNS record therefore results in a 404, rather than opening a same-named database on the wrong server. Hosting labels in the platform directory are taken from the configured catalog.

## Register and provision

Choose **Hosting location** when registering a tenant. The platform stores that node ID and shows it in the directory and Edit dialog. Assignment does not copy files, create servers, or edit DNS. A provisioned tenant's hosting assignment is deliberately not editable in the normal tenant form.

The CLI equivalent is:

```bash
php artisan tenants:create waraq "Waraq" owner@example.com --node=eu-1
```

Dashboard registration with an initial owner password now queues provisioning automatically on the assigned node. See [automatic setup and password resets](tenant-automation.md) for the one-time scheduler configuration.

For manual CLI provisioning, run **on the assigned node**:

```bash
php artisan tenants:provision waraq
```

Provisioning creates the database on that node's configured tenant database connection, runs the application migrations and reference seeders, and creates the owner account. The central registry is updated after successful provisioning. Running this command on a different node is rejected before claiming the tenant or issuing database-creation SQL.

For an existing company already hosted on a regional database server, run `tenants:adopt` on that regional node. Adoption selects that node's database settings and records its node ID. Copy only that customer's uploads into its private tenant storage.

## DNS and HTTPS

Create separate records as required:

```text
 devsaas.cgbod.com  -> platform server
 waraq.cgbod.com    -> Waraq's assigned regional server
 other.cgbod.com    -> the other tenant's assigned regional server
```

These may be A/AAAA records, or CNAME records pointing to stable regional ingress names. Install an appropriate certificate on each ingress; a root wildcard `*.cgbod.com` covers these single-level names. Preserve the original hostname when using a load balancer or reverse proxy and configure Laravel's trusted proxies appropriately. Do not redirect users to a region-specific public hostname: the customer's canonical address remains `waraq.cgbod.com`.

No DNS provider, cloud account, or production server is modified by this code. DNS/SSL automation, cloud provisioning, geographic load balancing, and disaster recovery remain deployment work.

## Jobs, schedules, and migrations

Run a database worker on each node:

```bash
php artisan queue:work database --sleep=3 --tries=3
```

Its default database queue is `tenants-<TENANCY_NODE_ID>`, for example `tenants-eu-1`. Payloads carry tenant and node metadata. A worker refuses to execute a job whose tenant is assigned elsewhere, including a tenant moved after the job was enqueued. Explicitly queued jobs must also use the correct node queue. Drain the old `default` queue on the original server before rolling out the partitioned queues; do not discard it.

Run the usual `schedule:run` cron on each node. Scheduled tenant tasks and `tenants:migrate --all` only select tenants hosted on that node. `--tenant=slug` does not bypass the locality check. Upgrade the central registry once, then upgrade application schemas on every regional node.

## Upgrade and moves

For an existing installation, first back up the registry and drain or account for its queued jobs. On the platform server:

```bash
php artisan config:clear
php artisan migrate --database=saas_central --path=database/migrations/central --force
php artisan config:cache
php artisan view:clear
php artisan queue:restart
```

Apply the new environment configuration and code to every node. Keep original tenants on `local` until a deliberate move is complete. Switching the hostname pattern needs matching DNS and certificates and may invalidate browser sessions on previous hosts.

Moving a live tenant is an operator procedure: suspend access, stop its jobs, take and verify a database/files backup, restore to the destination, verify application keys and encrypted data compatibility, update the registry assignment, update DNS/ingress and HTTPS, test access, then reactivate. This change does not provide automatic data relocation, registry replication, or cross-region failover.
