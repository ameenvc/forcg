@component('mail::message')
Module: Incident Reporting.

<strong>Assigned investigaion report</strong><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent