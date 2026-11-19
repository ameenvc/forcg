@extends('saas.layout')
@section('title', 'Cloudflare DNS · CGBOD')
@section('content')
<div class="saas-page-heading"><div><span class="saas-eyebrow">HOSTING AND DOMAINS</span><h1>Cloudflare DNS</h1><p>Connect tenant addresses to their assigned hosting servers.</p></div><a class="btn btn-alt-primary" href="{{ route('saas.tenants.index') }}#tenants">Tenant directory</a></div>
<div class="block block-rounded"><div class="block-content">
    <p>Tenant domain: <strong>{{ config('tenancy.base_domain') }}</strong>. Create an API token in Cloudflare with <strong>Zone: Read</strong> and <strong>DNS: Edit</strong>, limited to this zone.</p>
    <form method="post" action="{{ route('saas.cloudflare.save') }}">@csrf
        <div class="row"><div class="col-md-6 form-group"><label for="cf-zone">Cloudflare zone ID</label><input id="cf-zone" class="form-control" name="zone_id" required maxlength="32" value="{{ old('zone_id', $zoneId) }}" autocomplete="off"></div>
        <div class="col-md-6 form-group"><label for="cf-token">API token {{ $configured ? '(leave blank to keep saved token)' : '' }}</label><input id="cf-token" class="form-control" name="api_token" type="password" {{ $configured ? '' : 'required' }} maxlength="512" autocomplete="new-password"><small class="form-text text-muted">{{ $configured ? 'A token is saved securely. Enter a new token here to replace it.' : 'Stored encrypted. The saved token is never displayed.' }}</small></div></div>
        <h2 class="h4 mt-3">Hosting server targets</h2><p class="text-muted">Choose the public IPv4 address (A), IPv6 address (AAAA), or ingress hostname (CNAME) for each location. Leave a target blank to disable DNS setup for that location. These settings route traffic to existing servers; they do not create or install servers.</p>
        @foreach($nodes as $id => $node)
            <fieldset class="border rounded p-3 mb-3"><legend class="h5 w-auto px-2">{{ $node['region'] }} · {{ $node['name'] }}</legend><div class="row">
                <div class="col-md-3 form-group"><label for="cf-type-{{ $id }}">Record type</label><select id="cf-type-{{ $id }}" class="form-control" name="targets[{{ $id }}][type]">@foreach(['A', 'AAAA', 'CNAME'] as $type)<option value="{{ $type }}" {{ old('targets.' . $id . '.type', $targets[$id]['type'] ?? 'A') === $type ? 'selected' : '' }}>{{ $type }}</option>@endforeach</select></div>
                <div class="col-md-6 form-group"><label for="cf-target-{{ $id }}">Server IP or hostname</label><input id="cf-target-{{ $id }}" class="form-control" name="targets[{{ $id }}][content]" value="{{ old('targets.' . $id . '.content', $targets[$id]['content'] ?? '') }}" placeholder="203.0.113.10 or ingress.example.com" maxlength="253"></div>
                <div class="col-md-3 form-group"><label class="d-block">Cloudflare proxy</label><input type="hidden" name="targets[{{ $id }}][proxied]" value="0"><label for="cf-proxy-{{ $id }}"><input id="cf-proxy-{{ $id }}" type="checkbox" name="targets[{{ $id }}][proxied]" value="1" {{ old('targets.' . $id . '.proxied', $targets[$id]['proxied'] ?? false) ? 'checked' : '' }}> Proxied</label><small class="form-text text-muted">Unchecked: DNS only.</small></div>
            </div></fieldset>
        @endforeach
        <input type="hidden" name="automatic" value="0"><div class="form-group"><label for="cf-auto"><input id="cf-auto" type="checkbox" name="automatic" value="1" {{ old('automatic', $automatic) ? 'checked' : '' }}> Automatically apply DNS after new tenant provisioning</label><small class="form-text text-muted">Changing targets does not change existing records immediately. Use Apply DNS for existing tenants.</small></div>
        <button type="submit" class="btn btn-primary mb-4">Save Cloudflare settings</button>
    </form>
</div></div>
<div class="block block-rounded"><div class="block-header block-header-default"><h2 class="block-title">Connection result</h2></div><div class="block-content pb-4">
    @if($test && $test['status'])<div class="alert {{ $test['status'] === 'succeeded' ? 'alert-success' : 'alert-danger' }}" role="status"><strong>{{ ucfirst($test['status']) }}</strong> — {{ $test['message'] }}<small class="d-block mt-1">Checked {{ $test['at'] }}</small></div>@else<p class="text-muted">The current configuration has not been tested.</p>@endif
    <form method="post" action="{{ route('saas.cloudflare.test') }}">@csrf<button class="btn btn-alt-primary" type="submit" {{ $configured ? '' : 'disabled' }}>Test Cloudflare connection</button></form>
    <p class="text-muted mt-3 mb-0">Record results appear in the tenant directory under DNS details. API success confirms the Cloudflare record, not global DNS propagation or origin HTTPS. Configure HTTPS on every target server and use Cloudflare Full (strict) when proxying.</p>
</div></div>
@endsection
