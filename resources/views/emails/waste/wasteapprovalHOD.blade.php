@component('mail::message')
Module: Waste Management

Dear <strong>{{$data['hodname']}}</strong>,

<strong> A Waste Transfer Request is waiting for your approval</strong> <br/>

Waste Transfer Request with Request Number:<strong> {{$data['request_no']}}</strong> is waiting for approval <br/>

<a href="https://waraqcg.azurewebsites.net/user/waste/hodapprove?val={{$data['request_no']}}&dept={{$data['dept']}}&yr={{$data['yr']}}">Click here to approve</a><br/><br/>


Thanks & Regards<br>
{{ config('app.name') }}
@endcomponent
