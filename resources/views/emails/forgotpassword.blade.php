@component('mail::message')
<strong>Reset Password Verification</strong><br/>
 @if($data['value']==1)
 {{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
 {{$data['line4']}}<br/><br>
 @else
 {{$data['line1']}}<br/><br/>
 {{$data['line2']}}<br/><br/>
 {{$data['line3']}}<br/><br/>
 <a style="background-color: green;padding: 8px 16px;display: inline-block;text-decoration: none;color: #FFFFFF;border-radius: 3px;" href="{{$data['line4']}}">Accept</a><span> </span><a style="background-color: red;padding: 8px 16px;display: inline-block;text-decoration: none;color: #FFFFFF;border-radius: 3px;" href="{{$data['line5']}}">Reject</a><br/><br/>
 @endif

Thanks & Regards<br> 
{{ config('app.name') }}
@endcomponent


