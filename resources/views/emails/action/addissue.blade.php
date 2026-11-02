@component('mail::message')
Module: Audit Management.

An issue has been raised.

Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong>.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent





