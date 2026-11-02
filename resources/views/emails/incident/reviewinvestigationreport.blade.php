@component('mail::message')
Module: Incident Reporting.

<strong>Incident report added for secondary review</strong><br/>
 {{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent