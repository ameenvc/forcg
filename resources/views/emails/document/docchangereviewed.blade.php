@component('mail::message')
Module: Document Management.

Change requested document for MR review.

A document has been requested for MR approval review,<br>
Document number :<strong>{{$data['docnumber']}} </strong><br>
Document name: <strong>{{$data['document']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
