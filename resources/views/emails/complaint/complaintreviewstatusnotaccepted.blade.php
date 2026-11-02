@component('mail::message')
Module: Customer Complaint.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Raised By: <strong>{{$data['addedby']}}</strong><br>
Complaint ID: <strong>{{$data['trackno']}}</strong><br>
@if($data['type'])
Type: <strong>{{$data['type']}}</strong><br>
@endif
Department: <strong>{{$data['dept']}}</strong><br>
@if($data['tardate'])
Target Date as : <strong>{{$data['tardate']}}</strong><br>
@endif
<br>
This issue has been reviewed and <strong style="color:red;">NOT ACCEPTED</strong> by the reviewer.<br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent
