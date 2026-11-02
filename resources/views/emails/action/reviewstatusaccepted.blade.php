@component('mail::message')
Module: Audit Management.

Proposed actions accepted by reviewer.

Issue with ID: <strong>{{$data['issueid']}}</strong> <br>Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due date as : <strong>{{$data['duedate']}}</strong><br> has been reviewed and <strong>ACCEPTED</strong> by reviewer.
Please update the implementation status.


Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => $data['app_url'] . 'user/actionsystem/openissue' ])

Update Percentage
@endcomponent

@endcomponent
