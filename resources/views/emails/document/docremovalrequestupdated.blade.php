@component('mail::message')
Module: Document Management.

Removal Request document is updated and waiting for your review.

A document has been requested for removal review,<br>
Document number :<strong>{{$removalupdatedata['docnumber']}} </strong><br>
Document name: <strong>{{$removalupdatedata['document']}} </strong><br>
Document type :<strong>{{$removalupdatedata['type']}} </strong><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent