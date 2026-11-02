@component('mail::message')
Module: NCR & Corrective Actions

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/>
ID: <strong>{{$data['issueid']}} </strong><br> 
Type: <strong>{{$data['type']}}</strong>  <br>
Department: <strong>{{$data['department']}} </strong><br>
Due Date as: <strong>{{$data['duedate']}}</strong>.<br><br>
Click the button below to review the issue:<br>

@component('mail::button', ['url' => $data['app_url'] . 'user/corrective/reviewstatus?val=' . $data['id']])
Review Action
@endcomponent

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent