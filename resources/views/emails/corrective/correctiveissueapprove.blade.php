@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br><br>
Issue ID: <strong>{{$data['issueid']}}</strong><br>
Type: <strong>{{$data['type']}}</strong><br>
Department: <strong>{{$data['department']}}</strong>
<br>has been approved by the Management Representative.


Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent
