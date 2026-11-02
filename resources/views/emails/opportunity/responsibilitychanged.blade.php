@component('mail::message')
Module: Opportunity.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Opportunity ID: <strong>{{$data['opportunityid']}}</strong><br>
Department: <strong>{{$data['department']}}</strong><br>
Due Date as: <strong>{{$data['duedate']}}</strong><br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent