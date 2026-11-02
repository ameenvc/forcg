@component('mail::message')
Module: Near Miss.

An issue has been raised.

Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target date as : <strong>{{$data['targetdate']}}</strong>.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent





