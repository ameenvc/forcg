@component('mail::message')

A user is waiting admin approval.

Username: <strong>{{$data->name}} </strong><br> employee id: <strong>{{$data->employeeid}}</strong>.



Thanks,<br>
{{ config('app.name') }}
@endcomponent





