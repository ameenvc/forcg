@component('mail::message')
Module: Incident Reporting.

<strong>Investigation report rejected</strong><br/>

 {{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
 The reason for rejecting is <strong style="color:red">{{ $data['reason'] }}</strong><br><br>
Thanks & Regards<br>
{{ config('app.name') }}
@endcomponent
