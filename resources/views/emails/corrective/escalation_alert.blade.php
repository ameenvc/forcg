@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

Dear Sir,

{!!$data['email_content']!!}<br><br>
Issue ID: <strong>{{$data['issueid']}}</strong><br>
Issue Title: <strong>{{$data['title']}}</strong><br>
@if(!empty($data['targetdate']))
Target Date: <strong>{{$data['targetdate']}}</strong><br>
@endif
Current Status: <strong>{{$data['status']}}</strong><br>
@if(!empty($data['person']))
Responsible Person: <strong>{{$data['person']}}</strong><br>
@endif
@if(!empty($data['department']))
Responsible Department: <strong>{{$data['department']}}</strong><br>
@endif

@component('mail::button', ['url' => $data['url']])
{{$data['btn_text']}}
@endcomponent
<br>

Thanks & Regards,<br> 
{{ config('app.name') }}
@endcomponent