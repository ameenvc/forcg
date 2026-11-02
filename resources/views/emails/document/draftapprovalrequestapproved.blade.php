@component('mail::message')
Module: Document Management.

Draft approval request has been approved,<br>
Document number: <strong>{{$data['docnumber']}} </strong><br>
Document name: <strong>{{$data['docname']}} </strong><br>
Document type: <strong>{{$data['type']}} </strong><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent