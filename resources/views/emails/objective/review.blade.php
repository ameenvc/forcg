@component('mail::message')
Module: Objectives.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br><br>
Objectives ID: <strong>{{$data['objective_id']}} </strong><br>
Objectives Name: <strong>{{$data['objectives']}} </strong><br>
Department: <strong>{{$data['department']}} </strong><br>
Objectives Description: <strong>{{$data['description']}} </strong><br>
Objectives Status: <strong>{{$data['status']}} </strong><br>
Target Date: <strong>{{$data['targetdate']}} </strong><br>


Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent