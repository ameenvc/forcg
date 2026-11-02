@component('mail::message')
Module: Document Management.

A draft has been requested for your review,<br>
Document name: <strong>{{$data['docname']}} </strong><br>
Document type: <strong>{{$data['type']}} </strong><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
 