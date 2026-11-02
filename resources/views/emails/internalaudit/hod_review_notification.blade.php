@component('mail::message')
Module: Audit Management

Dear {{ $data['name'] }},<br/>

<br>Please verify the action proposed by {{ $data['responsibility'] }} in the Audit Management module at your earliest convenience:<br>
Issue ID : {{ $data['issueid'] }}<br>
Responsibility : {{ $data['responsibility'] }}<br>
Type : {{ $data['type'] }}<br>
Department : {{ $data['department'] }}<br>
Due Date : {{ $data['duedate'] }}<br>

@component('mail::button', ['url' => $data['app_url'] . 'user/auditmgmt'])
Review Action
@endcomponent

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent