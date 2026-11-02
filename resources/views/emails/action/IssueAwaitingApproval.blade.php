@component('mail::message')
Module: Audit Management.

An issue is awaiting your approval.

An Issue With ID: <strong>{{$data['issueid']}}</strong> <br>Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong><br> Is Awaiting Your Approval.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
