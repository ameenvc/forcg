@component('mail::message')
Module: Audit Management.

An audit is assigned to you.

An audit is assigned to you for the department : <strong>{{$data['department']}} </strong><br> Scope : <strong>{{$data['scope']}}</strong><br> From Date : <strong>{{$data['date']}}</strong><br> To date : <strong>{{$data['enddate']}}</strong><br> Audit Id : <strong>{{$data['aud_id']}}</strong><br>




Thanks,<br>
{{ config('app.name') }}
@endcomponent
