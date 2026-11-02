@component('mail::message')
Module: Occupational Health

<strong>Injury Status Changed</strong><br/>
 {{$data['line1']}}<br/><br/>
Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent