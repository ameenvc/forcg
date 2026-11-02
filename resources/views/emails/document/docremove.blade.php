@component('mail::message')
Module: Document Management.

Removal requested document for HOD review.

A document has been requested for removal review,<br>
Document number :<strong>{{$data['docnumber']}} </strong><br>
Document name: <strong>{{$data['document']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
