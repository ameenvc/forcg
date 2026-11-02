@component('mail::message')
    <strong>OTP Verification</strong><br>
    <br>
    <strong>Your OTP for Email Verification is</strong>
    <p>Your verification code is:</p>
    <h2>{{ $otp }}</h2><br><br>
    Thanks & Regards<br>
    {{ config('app.name') }}
@endcomponent
