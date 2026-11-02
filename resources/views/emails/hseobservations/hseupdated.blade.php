@component('mail::message')
Module: HSE Observations
PROPOSED ACTIONS HAVE BEEN UPDATED.

Proposed Actions Have Been Updated For Issue with ID:<strong>{{$data['issueid']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong>.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
