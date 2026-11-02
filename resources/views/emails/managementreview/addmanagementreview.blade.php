@component('mail::message')
Module: Management Review

<strong>{{$data['item']}}</strong>

Dear {{$data['name']}},

{{$data['email_content']}}<br/><br/>
<b>Date of ManagementReview - {{$data['date']}}</b><br/>
<b>Time  - {{$data['time']}}</b><br/>


Thanks,<br>
{{ config('app.name') }}
@endcomponent