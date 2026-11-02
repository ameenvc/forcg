@component('mail::message')
<strong>Client Activation</strong>

 {{$data['line1']}}<br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/>

Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent