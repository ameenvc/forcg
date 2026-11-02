@component('mail::message')
Module: Context Management.

Change in request approved.

Request with ID: <strong>{{$data['crid']}} </strong>  <br> Title: <strong>{{$data['title']}}</strong> <br> Priority: <strong>{{ $data['crpriority']}}</strong><br> Severity:<strong>{{$data['crseverity']}}</strong><br> has been approved by: <strong>{{$data['approved_by']}} </strong>.

Thanks,<br>
{{ config('app.name') }}
@endcomponent







