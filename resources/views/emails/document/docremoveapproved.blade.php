@component('mail::message')
Module: Document Management.

Removal requested document approved.

Document approved for removal request,<br>
Document number :<strong>{{$data['docnumber']}} </strong><br>
Document name: <strong>{{$data['document']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
