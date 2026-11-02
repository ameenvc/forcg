@component('mail::message')
<strong>Password Reset Request Rejected</strong><br/>
{{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent