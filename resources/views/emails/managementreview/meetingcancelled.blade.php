@component('mail::message')
Module: Management Review

<strong>{{$data['item']}}</strong>

Dear {{$data['name']}},

<b>Meeting Scheduled on {{$data['date']}} at {{$data['time']}} Cancelled due to {{$data['reason']}}</b><br/>
{{$data['email_content']}}<br/><br/>


Thanks,<br>
{{ config('app.name') }}
@endcomponent