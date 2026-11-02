@component('mail::message')
Module: Near Miss.

<strong>{{$data['item']}}</strong>

Dear Reviewer,

{!!$data['email_content']!!}<br><br>

Issue ID: <strong>{{$data['issueid']}}</strong><br>
Raised By: <strong>Guest User</strong><br>
Date Submitted: <strong>{{date('d-m-Y')}}</strong><br>
Title: <strong>{{$data['title']}}</strong><br>
Observation: <strong>{{$data['observation']}}</strong><br>

Please review the submitted Near Miss and take the appropriate action to continue the workflow.

@component('mail::button', ['url' => $data['url']])
{{$data['btn_txt']}}
@endcomponent
<br>

Thank you.
{{ config('app.name') }}
@endcomponent







