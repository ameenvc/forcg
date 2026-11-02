@component('mail::message')
Module: Waste Management

 Waste Transfer Request with Request Number:<strong> {{$data['request_no']}} </strong> is updated and waiting for your review <br/>


 <a href="https://waraqcg.azurewebsites.net/user/waste/transferschedule/{{$data['yr']}}">Click here to review</a><br/><br/>


Thanks & Regards<br>
{{ config('app.name') }}
@endcomponent
