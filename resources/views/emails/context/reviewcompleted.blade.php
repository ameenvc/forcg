@component('mail::message')
Module: Context Management.

Change in request has been reviewed.

Request with ID: <strong>{{$data['crid']}} </strong><br> Title: <strong>{{$data['title']}}</strong><br> Priority: <strong>{{ $data['crpriority']}}</strong><br> Severity:<strong>{{$data['crseverity']}}</strong><br>Reviewed by: <strong>{{$data['reviewed_by']}} </strong> <br> has completed the reviewal process.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
