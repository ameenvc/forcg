@component('mail::message')
Module: Customer Complaint.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}} <b>#{{$data['trackno']}}</b> with Target Date <b>{{$data['tardate']}}</b> in Customer Complaint module at CGBOD.<br/>


Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent
