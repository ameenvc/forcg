@component('mail::message')
Module: Health & Safety Risks.

Risk has been reviewed.

OHS risk with ID: <strong>{{$data['actid']}} </strong><br> Department: <strong>{{$data['department']}}</strong><br>Type: <strong>{{$data['type']}} </strong> <br> has completed the reviewal process.


Thanks,<br> 
{{ config('app.name') }}
@endcomponent
