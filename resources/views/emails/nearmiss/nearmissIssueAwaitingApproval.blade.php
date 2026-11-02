@component('mail::message')
Module: Near Miss.

An issue is awaiting your approval.

An Issue With ID: <strong>{{$data['issueid']}}</strong> <br>Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target Date as : <strong>{{$data['targetdate']}}</strong><br> Is Awaiting Your Approval.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
