@component('mail::message')

The account with username {{$data['name']}} and email id {{$data['email']}} has been approved by the admin.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent





