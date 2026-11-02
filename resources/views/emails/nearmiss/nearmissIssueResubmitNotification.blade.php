@component('mail::message')
Module: Near Miss.

An issue belonging to your department is resubmitted.

Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target Date as : <strong>{{$data['targetdate']}}</strong><br> Is Resubmitted and Awaiting Actions To Be Taken.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
