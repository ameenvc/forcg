@component('mail::message')
Module: Context Management.

A context needs More action.

Request with ID: <strong>{{$data['crid']}} </strong><br> Priority: <strong>{{ $data['crpriority']}}</strong><br> Severity:<strong>{{$data['crseverity']}}</strong><br> Description:<strong>{{$data['description']}}</strong><br> needs more action.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
