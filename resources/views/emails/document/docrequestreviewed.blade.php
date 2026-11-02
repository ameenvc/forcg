@component('mail::message')
Module: Document Management.

A new document for MR review.

A new document has been requested for MR approval,<br>
Document number :<strong>{{$data['ndocnumber']}} </strong><br>
Document name: <strong>{{$data['ndocname']}} </strong><br>
Document type :<strong>{{$data['doctype']}} </strong><br>
Department :<strong>{{$data['department']}} </strong><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
