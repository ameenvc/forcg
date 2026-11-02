@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

<strong>Rootcause and CA investigation</strong><br/>

Dear Sir,

{{$data['email_content']}} <b>#{{$data['issueid']}}</b> with Target Date <b>{{$data['target_date']}}</b> in ncr & corrective actions module at CGBOD.<br/>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent