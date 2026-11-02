@component('mail::message')
Module: HSE Observations
AN ISSUE BELONGING TO YOUR DEPARTMENT IS RESUBMITTED.

An Issue With Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong><br> Is Resubmitted and Awaiting Actions To Be Taken.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
