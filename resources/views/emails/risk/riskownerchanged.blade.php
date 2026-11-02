@component('mail::message')
Module: Enterprise Risk

<strong>{{$riskownerdata['item']}}</strong>

Dear Sir,

{{$riskownerdata['email_content']}}<br/><br>
Risk ID: <strong>{{$riskownerdata['Riskid']}}</strong><br>
Risk Area: <strong>{{$riskownerdata['RiskArea']}}</strong><br>
Risk Group: <strong>{{$riskownerdata['RiskGroup']}}</strong><br>
Target: <strong>{{$riskownerdata['Target']}}</strong><br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent