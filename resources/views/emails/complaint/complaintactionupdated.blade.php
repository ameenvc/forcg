@component('mail::message')
Module: Customer Complaint.

Proposed actions have been updated.

Proposed actions have been updated for Tacking Number: <strong>{{$data['trackno']}}</strong> <br>Raised by: <strong>{{$data['addedby']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['dept']}} </strong><br>Target Date as : <strong>{{$data['tardate']}}</strong><br>.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
