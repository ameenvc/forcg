@component('mail::message')
Module: Incident Reporting.
@if($data['line1'] == '1')
<strong>Incident report added</strong><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
@else
<strong>Incident report added for review </strong><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
@endif
Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent