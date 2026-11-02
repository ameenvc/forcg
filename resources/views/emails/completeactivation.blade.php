@component('mail::message')
<strong>Client Activation Completed</strong>

 {{$data['line1']}}<br/>
 {{$data['line2']}}<br/>

Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent