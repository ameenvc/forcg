@component('mail::message')
Module: Document Management.

Dear <strong>{{$data['approver']}} </strong>

The Review process for the below draft is completed and added for approval.Please start the approval process<br>
Document name: <strong>{{$data['docname']}} </strong><br>
Document type :<strong>{{$data['type']}} </strong><br>



Thanks,<br>
{{ config('app.name') }}
@endcomponent