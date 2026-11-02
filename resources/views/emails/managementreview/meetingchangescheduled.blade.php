@component('mail::message')
Module: Management Review

<strong>{{$data['item']}}</strong>

Dear {{$data['name']}},

<b>Meeting Scheduled on {{$data['date']}} at {{$data['time']}} has been Changed </b><br/><br/>
{{$data['email_content']}}<br/><br/>
<b>Rescheduled Date of ManagementReview - {{$data['date']}}</b><br/>
<b>Rescheduled Time  - {{$data['time']}}</b><br/>


Thanks,<br>
{{ config('app.name') }}
@endcomponent