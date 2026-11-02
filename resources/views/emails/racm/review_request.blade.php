@component('mail::message')
Module: Enterprise Risk.

<strong>Subject: <strong>{{$data['item']}}</strong>

Hi {{$data['reviewer']}},

{{$data['email_content']}}<br/><br>
Click the button below to access the records and submit your feedback:<br>

@component('mail::button', ['url' => $data['app_url'] . 'user/riskmgmt/racm/review/'.$data['racmids'].'/'.$data['reviewer_id']])
Review
@endcomponent

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent