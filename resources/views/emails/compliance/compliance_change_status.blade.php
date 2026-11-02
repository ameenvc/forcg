@component('mail::message')
Module: Compliance Management.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br><br>
Compliance ID: <strong>{{$data['auth_id']}}</strong><br>
Legislative Issue: <strong>{{$data['issue']}}</strong><br>
Department: <strong>{{$data['department']}} </strong><br>
Status: <strong>{{$data['status']}} </strong><br>

Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent