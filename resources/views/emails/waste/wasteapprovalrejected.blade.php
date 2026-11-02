@component('mail::message')
Module: Waste Management

<strong>Waste Transfer Request Rejected</strong><br/>
 {{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>

Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent