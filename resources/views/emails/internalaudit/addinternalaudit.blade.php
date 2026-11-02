@component('mail::message')
Module: Audit Management.

An audit is scheduled.

An audit is scheduled in your department : <strong>{{$data['department']}} </strong><br> Scope : <strong>{{$data['scope']}}</strong><br> From date : <strong>{{$data['date']}}</strong><br> To date : <strong>{{$data['enddate']}}</strong><br> Audit Id : <strong>{{$data['aud_id']}}</strong><br>

Kindly accpet your schedule through the system.
Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => $data['app_url'] . 'user/internalaudit/auditschedule/' .  session('year') ])

View Schedule
@endcomponent

@endcomponent
