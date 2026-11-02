@component('mail::message')
Module: Compliance Management.

<strong>Subject: <strong>{{$data['item']}}</strong>

Dear {{$data['creator']}},

{{$data['email_content']}}<br/><br>
Type: <strong>{{$data['type']}}</strong><br>
Regulatory Authority Name: <strong>{{$data['name']}}</strong><br>
Legal Document Name: <strong>{{$data['doc_name']}}</strong><br>
Action Taken: <strong><span style="color:red;">Rejected</span></strong><br>
Action By: <strong>{{$data['action_by']}}</strong><br>
Date & Time of Action: <strong>{{$data['date']}}</strong><br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent 