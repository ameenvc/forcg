@component('mail::message')
Module: HSE Observations.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
An issue with ID: <strong>{{$data['issueid']}}</strong><br>
Raised by: <strong>{{$data['name']}} </strong><br>
Type: <strong>{{$data['type']}}</strong><br>
Department: <strong>{{$data['department']}} </strong><br>
Due Date as : <strong>{{$data['duedate']}}</strong><br> is awaiting actions to be taken.

Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent