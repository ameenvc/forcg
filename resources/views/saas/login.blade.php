@extends('saas.layout')
@section('title', 'Sign in · CGBOD platform')
@section('content')
<div class="saas-login-layout">
    <aside class="saas-login-story" aria-label="CGBOD platform">
        <a class="saas-brand text-white" href="{{ route('saas.login') }}"><span class="saas-brand-mark" aria-hidden="true"><i class="si si-layers"></i></span>cgbod<span class="saas-brand-dot">.</span></a>
        <div class="saas-login-message"><span class="saas-eyebrow">THE CGBOD CONTROL CENTER</span><h2>One platform.<br>Every organization.</h2><p>A clear view of your tenants, their workspaces, and your next stage of growth.</p>
            <div class="saas-login-feature"><span><i class="si si-organization" aria-hidden="true"></i></span><div><strong>A workspace for every team</strong><small>Bring organizations together under one platform.</small></div></div>
            <div class="saas-login-feature"><span><i class="si si-lock" aria-hidden="true"></i></span><div><strong>Separate by design</strong><small>Dedicated company databases and workspace access.</small></div></div>
            <div class="saas-login-feature"><span><i class="si si-control-play" aria-hidden="true"></i></span><div><strong>Stay in control</strong><small>Manage each tenant through its entire lifecycle.</small></div></div>
        </div>
        <div class="saas-login-story-footer"><span class="saas-online-dot"></span> Built for better governance.</div>
        <div class="saas-orbit saas-orbit-one" aria-hidden="true"></div><div class="saas-orbit saas-orbit-two" aria-hidden="true"></div>
    </aside>
    <section class="saas-login-main">
        <div class="saas-login-top"><span><i class="si si-shield mr-2" aria-hidden="true"></i>Platform administrator access</span></div>
        <div class="saas-login-form">
            <div class="saas-login-symbol"><i class="si si-login" aria-hidden="true"></i></div>
            <span class="saas-eyebrow text-primary">WELCOME TO CGBOD</span>
            <h1>Welcome back.</h1><p class="text-muted mb-4">Sign in to manage your CGBOD tenants.</p>
            @include('saas.partials.alerts')
            <form method="post" action="{{ route('saas.login') }}">
                @csrf
                <div class="form-group"><label for="login-email">Email address</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="si si-envelope" aria-hidden="true"></i></span></div><input class="form-control form-control-lg @error('email') is-invalid @enderror" id="login-email" name="email" type="email" required autocomplete="username" value="{{ old('email') }}" placeholder="you@company.com" autofocus></div></div>
                <div class="form-group mb-4"><label for="login-password">Password</label><div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="si si-lock" aria-hidden="true"></i></span></div><input class="form-control form-control-lg" id="login-password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password"><div class="input-group-append"><button type="button" class="btn btn-light saas-password-toggle" data-password-toggle="login-password" aria-label="Show password" aria-controls="login-password" aria-pressed="false"><i class="fa fa-eye" aria-hidden="true"></i></button></div></div></div>
                <button type="submit" class="btn btn-primary btn-lg btn-block saas-signin">Sign in <i class="fa fa-arrow-right ml-2" aria-hidden="true"></i></button>
            </form>
            <div class="saas-login-note"><i class="si si-info" aria-hidden="true"></i><p>Looking for your company workspace?<br>Sign in using your organization's tenant address.</p></div>
        </div>
        <footer class="saas-login-footer"><span>© {{ date('Y') }} CGBOD</span><span>Governance starts here.</span></footer>
    </section>
</div>
@endsection
