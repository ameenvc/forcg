@component('mail::message')
Module: Document Management.

Review requested draft disapproved.

A draft disapproved for new document request,<br>
Document name: <strong>{{$data['docname']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent