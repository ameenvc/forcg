@component('mail::message')
Module: Near Miss.

Proposed actions have been updated. 

Issue with ID:<strong>{{$data['issueid']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target date as : <strong>{{$data['targetdate']}}</strong>.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent
