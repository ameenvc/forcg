@component('mail::message')
@if($data['value']==1)
<strong>Password Changed</strong><br/>

 {{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
@elseif($data['value']==2)
<strong>Password Reset Request Accepted</strong><br/>
{{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
@elseif($data['value']==3)
<strong>Password Reset Request Rejected</strong><br/>
{{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
 @endif
Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent