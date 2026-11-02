@component('mail::message')
Module: Opportunity.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Opportunity ID: <strong>{{$data['dep_id']}}</strong><br>
Opportunity Name: <strong>{{$data['oppname']}}</strong><br>
Process Stage: <strong>{{$data['status']}}</strong><br>
Department: <strong>{{$data['department']}}</strong><br>
Target Date: <strong>{{$data['tardate']}}</strong><br> 

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent