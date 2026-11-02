@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

<b>Congratulations !!!</b><br><br>
Dear Sir,

{{$data['email_content']}}<br><br>
Issue with ID: <strong>{{$data['issueid']}}</strong><br>
Type: <strong>{{$data['type']}}</strong><br>
Department: <strong>{{$data['department']}}</strong><br>

Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent