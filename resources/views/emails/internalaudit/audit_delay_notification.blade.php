@component('mail::message')
Module: Audit Management

Dear {{ $data['name'] }},<br/>

{!! nl2br($data['email_content']) !!}
<br>Below are the delayed audit details:<br>
Department : {{ $data['department'] }}<br>
Scope : {{ $data['scope'] }}<br>
From Date : {{ $data['from_date'] }}<br>
To date : {{ $data['to_date'] }}<br>
Audit Id : {{ $data['audit_id'] }}<br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent