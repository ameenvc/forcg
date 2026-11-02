@component('mail::message')
Module: Opportunity.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Opportunity ID: <strong>{{$data['dep_id']}}</strong><br>
Opportunity Name: <strong>{{$data['oppname']}}</strong><br>
Opportunity Description: <strong>{{$data['oppdes']}}</strong><br>
Department: <strong>{{$data['department']}}</strong><br>
Disapproval Reason: <strong style="color:red">{{$data['reason']}} </strong><br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent