@component('mail::message')
Module: Document Management.

Change Request document is updated and waiting for your review.

A document has been requested for review,<br>
Document number :<strong>{{$updateddata['docnumber']}} </strong><br>
Document name: <strong>{{$updateddata['document']}} </strong><br>
Document type :<strong>{{$updateddata['type']}} </strong><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent