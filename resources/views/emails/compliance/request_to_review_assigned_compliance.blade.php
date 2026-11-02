@component('mail::message')
Module: Compliance Management.

<strong>Subject: <strong>{{str_replace('[Compliance ID]', $data['auth_id'], $data['item'])}}</strong>

Dear <strong>{{$data['new_coordinator']}}</strong>,

{{$data['email_content']}}<br/><br>
Compliance ID: <strong>{{$data['auth_id']}}</strong><br>
Regulatory Authority Name: <strong>{{$data['reg_name']}}</strong><br>
Legal Document Name: <strong>{{$data['doc_name']}}</strong><br>
Legal Document Title: <strong>{{$data['title']}}</strong><br>
Requirement: <strong>{{$data['requirement']}}</strong><br>
Responsible Person: <strong>{{$data['responsible']}}</strong><br>
Department: <strong>{{$data['department']}}</strong><br>
Added By: <strong>{{$data['added_by']}}</strong><br>
Added On: <strong>{{$data['added_on']}}</strong><br>

Kindly review it at your earliest convenience:<br>

@component('mail::button', ['url' => $data['app_url'] . 'user/compliance/reviewcompliancestatus/'.$data['id']])
Review Compliance
@endcomponent
<br>
Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent