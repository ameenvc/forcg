@component('mail::message')
Module: HSE Observations.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
@if(isset($data['issueid']))
Issue ID: <strong>{{$data['issueid']}}</strong><br> 
@endif
Raised by: <strong>{{$data['name']}}</strong><br>
@if(isset($data['type'])) 
Type: <strong>{{$data['type']}}</strong><br>
@endif
Department: <strong>{{$data['department']}}</strong><br>
@if(isset($data['duedate']))
Due Date as : <strong>{{$data['duedate']}}</strong><br>
@endif


Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent



