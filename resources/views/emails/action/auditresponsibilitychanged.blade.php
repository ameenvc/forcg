@component('mail::message')
Module: Audit Management.

Responsibility changed to you.

Responsibility have been changed to you for issue with ID:<strong>{{$data['issueid']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong>.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
