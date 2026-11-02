@component('mail::message')
Module: Compliance Management.

<strong>{{$da['item']}}</strong>

Dear Sir,

{{$da['email_content']}}<br><br>
Compliance ID: <strong>{{$da['auth_id']}}</strong><br>
Legislative Issue: <strong>{{$da['issue']}}</strong><br>
Authority: <strong>{{$da['authority']}}</strong><br>
Version: <strong>{{$da['version']}}</strong><br>
Department: <strong>{{$da['department']}} </strong><br>

Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent