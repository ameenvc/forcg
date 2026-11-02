@component('mail::message')
Module: Near Miss.

An issue has been resubmitted.

Resubmitted by <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target Date as : <strong>{{$data['targetdate']}}</strong>.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
