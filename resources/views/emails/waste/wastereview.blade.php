@component('mail::message')
Module: Waste Management

<strong> A Waste Transfer Request is waiting for your review</strong> <br/>

Waste Transfer Request with Request Number:<strong> {{$data['request_no']}}</strong> is waiting for review <br/>

<!--<a href="https://waraqcg.azurewebsites.net/user/waste/review?val={{$data['request_no']}}">Click here to review</a><br/><br/>-->
<a href="https://waraqcg.azurewebsites.net/user/waste/transferschedule/{{$data['yr']}}">Click here to review</a><br/><br/>


Thanks & Regards<br>
{{ config('app.name') }}
@endcomponent
