@component('mail::message')
Module: Context Management.

A new change in request has been raised.

Request with ID: <strong>{{$data['crid']}} </strong><br> Priority: <strong>{{ $data['crpriority']}}</strong><br> Severity:<strong>{{$data['crseverity']}}</strong><br> has been raised.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
