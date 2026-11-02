@component('mail::message')
Module: Audit Management.

Issue closed.

<b> Congratulations !!!</b><br>
The issue no <strong>{{$data['issueid']}} </strong>  has been closed by the issue reviewer.

Thanks,<br>
{{ config('app.name') }}
@component('mail::button', ['url' => $data['app_url'] . 'user/actionsystem/closedissues/' . $data['year'] ])

View Closed Issue
@endcomponent

@endcomponent







