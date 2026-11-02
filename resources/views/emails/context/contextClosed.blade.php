@component('mail::message')
Module: Context Management.

A context has been closed.

Request with ID: <strong>{{$data['crid']}} </strong><br> Priority: <strong>{{ $data['crpriority']}}</strong><br> Severity:<strong>{{$data['crseverity']}}</strong><br> has been closed.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
