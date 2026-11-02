@component('mail::message')
Module: Document Management.

Congratulations ! 
Your request for changing the document is Approved.

Document approved for change request,<br>
Document number :<strong>{{$data['docnumber']}} </strong><br>
Document name: <strong>{{$data['document']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
