@component('mail::message')
Module: Audit Management.

An issue belonging to your department is resubmitted.

An Issue With Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong><br> Is Resubmitted and Awaiting Actions To Be Taken.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
