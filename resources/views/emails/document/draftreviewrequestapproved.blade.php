@component('mail::message')
Module: Document Management.

The draft is reviewed by {{$data['reviewedby']}}

Draft reviewed for approval request,<br>
Document name: <strong>{{$data['docname']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent
