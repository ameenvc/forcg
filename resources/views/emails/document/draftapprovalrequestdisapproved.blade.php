@component('mail::message')
Module: Document Management.

Draft approval request has been disapproved.,<br>
Document name: <strong>{{$data['docname']}} </strong><br>
Document type: <strong>{{$data['type']}} </strong><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent