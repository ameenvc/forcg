@component('mail::message')
Dear {{ $name }},<br>

Congratulations! We are pleased to inform you that your account has been successfully approved by the administrator.<br>

You can now access the application using the link provided below:
<a href="{{ route('getlogin') }}">{{ route('getlogin') }}</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
