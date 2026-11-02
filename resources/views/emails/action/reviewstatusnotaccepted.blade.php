@component('mail::message')
Module: Audit Management.

Proposed actions not accepted by reviewer.

Issue with ID: <strong>{{$data['issueid']}}</strong>
<br>Raised by: <strong>{{$data['name']}} </strong>
<br> Type: <strong>{{$data['type']}}</strong>
<br>Department: <strong>{{$data['department']}} </strong>
<br>Due date as : <strong>{{$data['duedate']}}</strong>
<br>Disapproved reason as : <strong style="color:red">{{$data['remarks']}}</strong>
<br> has been reviewed and <strong>NOT ACCEPTED</strong> by reviewer.

Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => $data['app_url'] . 'user/actionsystem/openissue' ])

Edit Actions
@endcomponent

@endcomponent
