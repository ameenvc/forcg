@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br><br>
Issue Id: <strong>{{$data['issueid']}} </strong><br>
Resubmitted By: <strong>{{$data['name']}} </strong><br> 
Type: <strong>{{$data['type']}}</strong> <br>
Department: <strong>{{$data['department']}} </strong><br>
Due Date as : <strong>{{$data['duedate']}}</strong>.<br>


Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent