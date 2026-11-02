@component('mail::message')
Module: Document Management.

New document requested for HOD review.

A new document has been requested for review,<br>
Document number :<strong>{{$data['ndocnumber']}} </strong><br>
Document name: <strong>{{$data['ndocname']}} </strong><br>
Document type :<strong>{{$data['doctype']}} </strong><br>
Department :<strong>{{$data['department']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
