# Cloudflare DNS and hosting targets

The superadmin sidebar now contains **Cloudflare DNS**. This connects tenant hostnames such as `waraq.cgbod.com` to an existing server assigned in the hosting-node catalog. It does not install Laravel, provision a virtual machine, or install an origin TLS certificate.

## Deploy once

Deploy the same code to the platform and every regional node. Upgrade the shared registry once, clear configuration/views, and restart existing workers:

```bash
php artisan config:clear
php artisan migrate --database=saas_central --path=database/migrations/central --force
php artisan config:cache
php artisan view:clear
php artisan queue:restart
```

Keep `schedule:run` configured every minute on each node as described in [tenant automation](tenant-automation.md). Manual DNS actions use the same regional `tenants:process-operations` processor. The platform and all regional processors must use the same application encryption key to decrypt the shared token. Protect that key and the registry backups; do not generate a replacement key on an existing deployment just to enable this feature.

## Configure Cloudflare

1. In Cloudflare, select the zone containing the tenant base domain and copy its Zone ID. The zone must be active and authoritative for the domain.
2. Create an API token limited to this zone, with **Zone → Zone → Read** and **Zone → DNS → Edit** permissions. Use an API token, not the global API key.
3. Sign in to the CGBOD superadmin, open **Cloudflare DNS**, and enter the Zone ID and token. The token is stored encrypted in the central registry and never shown again. Leave the token input blank on later saves to keep it; enter a new token to rotate it.
4. Set each hosting location's public target: **A** for IPv4, **AAAA** for IPv6, or **CNAME** for a stable ingress hostname. A CNAME target is a hostname without `https://`, port, or path. Leave a location's target blank to disable DNS actions there. Locations come from `TENANCY_HOSTING_NODES`; this screen configures their DNS targets, not their database credentials or server installations.
5. Select DNS-only or Cloudflare-proxied behavior for each location. Configure the web server to accept the original tenant hostname. With proxying enabled, configure origin HTTPS and Cloudflare **Full (strict)**; the application does not change your Cloudflare SSL mode.
6. Optionally enable **Automatically apply DNS after new tenant provisioning**, then save.
7. Click **Test Cloudflare connection**. The screen shows success/failure and its timestamp. A successful test verifies the active zone, domain coverage, and DNS read access. The first record mutation checks DNS edit permission; a read-only token can pass the connection test but fail Apply DNS.

Use a server's actual public address or your provider's stable ingress hostname, not the documentation example addresses. If token restrictions include source IPs, allow the platform and every regional processor that will make API calls. API access uses the fixed HTTPS `api.cloudflare.com` origin, disables redirects, and has bounded connection/request timeouts.

## Apply and see the result

For an existing provisioned client, open **Tenant directory → DNS details → Apply DNS**. The action is queued on that client's assigned regional node. Refresh the directory after the scheduler runs and reopen **DNS details** to see:

- Status: queued, running, succeeded, or failed.
- The requested hostname, record type, server target, and proxy mode.
- Cloudflare record ID after a successful apply, the last check time, and an explanation of the result.

With automatic mode enabled, dashboard registration provisions the database/owner first and then applies DNS to that node's configured target. A DNS failure is shown separately and leaves the successfully provisioned workspace active; it does not delete data or recreate the database. Repair the setting or Cloudflare conflict, then use Apply DNS again.

Changing a server target or rotating the token does not bulk-update all existing tenant records. Apply DNS for each affected tenant deliberately. The result is a record of the last action, not continuous monitoring: changes made directly in Cloudflare are detected on the next apply.

## Existing records and failures

An existing matching address record is verified and reused without creating duplicates. The application can update a record it previously managed in the same zone, including a changed server target. A different pre-existing record, or multiple A/AAAA/CNAME records at the tenant name, is a conflict requiring review in Cloudflare. The application does not delete records, overwrite unrelated address records, change the platform hostname, or modify mail/TXT records.

API errors show an HTTP status and Cloudflare numeric error code with guidance. Provider response bodies and request tokens are not copied into the dashboard or audit log. Network timeouts show a retryable connection message. A rejected or timed-out request may still need inspection in Cloudflare before retrying; matching-record verification makes an already-completed creation safe to repeat.

Successful API application confirms Cloudflare's configured record. It does not prove worldwide DNS propagation, origin reachability, a valid origin certificate, or successful tenant login. Check these before giving the link to the client. For proxied records, public DNS returns Cloudflare edge addresses, so the configured origin target should be verified through Cloudflare rather than by comparing a public lookup to the server IP.

## Verification during development

Automated tests use a fake Cloudflare API and dummy credentials. They exercise creation, matching records, managed updates, conflict refusal, auth failures, queued regional execution, encrypted-token handling, and failure separation from provisioning. Development screenshots/results generated from those fixtures are demonstrations, not evidence of a change to a real Cloudflare zone. A live result requires your zone/token and target settings in the deployed dashboard.
