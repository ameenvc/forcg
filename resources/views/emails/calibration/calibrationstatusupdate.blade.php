@component('mail::message')
Module: Calibration Management.

Dear {{ $data['name'] }},<br/>
@php
    $data['email_content'] = str_replace('Instrument No', $data['instrument_no'], $data['email_content']);
    $data['email_content'] = str_replace('Previous status', $data['previous_status'], $data['email_content']);
    $data['email_content'] = str_replace('New status', $data['new_status'], $data['email_content']);
@endphp
{!! nl2br(htmlspecialchars($data['email_content'])) !!}

Instrument Number: {{ $data['instrument_no'] }}<br>
Department: {{ $data['department'] }}<br>
Calibration Type: {{ ucfirst($data['type']) }}<br>
Calibration Status: {{ $data['new_status'] }}<br>
Calibration Due Date: {{ $data['due_date'] }}<br>

Click on the below link to view more details<br>
<a href="{{ $data['url'] }}">Login to System</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
