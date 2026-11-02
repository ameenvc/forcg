@component('mail::message')
Module: Context Management.

A context has been completed.

Request with ID: <strong>{{$data['crid']}} </strong><br> Priority: <strong>{{ $data['crpriority']}}</strong><br> Severity:<strong>{{$data['crseverity']}}</strong><br> has been completed.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
