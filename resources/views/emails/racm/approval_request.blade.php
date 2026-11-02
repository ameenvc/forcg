@component('mail::message')
Module: Enterprise Risk.

<strong>Subject: <strong>{{$data['item']}}</strong>

Hi {{$data['approver']}},

{{$data['email_content']}}<br/><br>
Click the button below to access the records and submit your feedback:<br>

@component('mail::button', ['url' => $data['app_url'] . 'user/riskmgmt/racm/approve/'.$data['racmids']])
Approve
@endcomponent

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent