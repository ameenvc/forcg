@component('mail::message')
Module: Waste Management

Dear <strong>{{$data['name']}}</strong>,

<strong> A Waste Transfer Request has been rejected by HOD</strong> <br/>

Waste Transfer Request with Request Number:<strong> {{$data['request_no']}}</strong> has been rejected by HOD <br/>

Reason for Rejection is <strong> {{$data['hod_reject_reason']}}</strong>

<a href="https://waraqcg.azurewebsites.net/user/waste/transferschedule/{{$data['yr']}}">Click here to edit/delete</a><br/><br/>


Thanks & Regards<br>
{{ config('app.name') }}
@endcomponent