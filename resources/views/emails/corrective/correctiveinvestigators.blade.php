@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

<strong>Rootcause and CA investigation</strong><br/>

Dear Sir,

{{$data['email_content']}} <b>#{{$data['issueid']}}</b> with Target Date <b>{{$data['duedate']}}</b> in ncr & corrective action module at CGBOD.<br>
So please start your investigation process.<br><br>
Thanks & Regards<br>
{{ config('app.name') }}
@endcomponent