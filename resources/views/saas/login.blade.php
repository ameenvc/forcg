@extends('saas.layout')
@section('title', 'Sign in · CGBOD platform')
@section('content')
<div class="card login"><h1>Welcome back.</h1><p>Sign in to manage your CGBOD tenants.</p><form method="post" action="{{ route('saas.login') }}">@csrf
<label>Email address<input name="email" type="email" required autocomplete="username" value="{{ old('email') }}"></label>
<label>Password<input name="password" type="password" required autocomplete="current-password"></label>
<button style="width:100%">Sign in</button></form><p style="font-size:12px">For platform administrators. Company users should sign in at their own tenant address.</p></div>
@endsection
