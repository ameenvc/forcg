@component('mail::message')
Module: Near Miss.

Responsibility changed to you.

Responsibility have been changed to you for issue with ID:<strong>{{$data['issueid']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target Date as : <strong>{{$data['targetdate']}}</strong>.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
