@component('mail::message')
Module: Near Miss.

Proposed actions accepted by reviewer.

Issue With ID: <strong>{{$data['issueid']}}</strong> <br>Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target date as : <strong>{{$data['targetdate']}}</strong><br> has been reviewed and <strong>ACCEPTED</strong> by reviewer.
Please update the implementation status.


Thanks,<br> 
{{ config('app.name') }}
@endcomponent
