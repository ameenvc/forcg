@component('mail::message')
Module: Audit Management.

The scheduled audit confirmed.

The scheduled audit is confirmed by the process owner of the department : <strong>{{$data['department']}} </strong><br> Status :<strong>{{$data['status']}} </strong><br> Scope : <strong>{{$data['scope']}}</strong><br> From date : <strong>{{$data['date']}}</strong><br> To date   : <strong>{{$data['enddate']}}</strong><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent