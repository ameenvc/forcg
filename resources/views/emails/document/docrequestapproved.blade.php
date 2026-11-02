@component('mail::message')
Module: Document Management.

New document requested approved.

A document approved for new document request,<br>
Document number :<strong>{{$data['ndocnumber']}} </strong><br>
Document name: <strong>{{$data['ndocname']}} </strong><br>
Document type :<strong>{{$data['doctype']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
