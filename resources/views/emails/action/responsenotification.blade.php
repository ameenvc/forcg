@component('mail::message')
Module: Audit Management.

An issue is assigned to your department.

An issue with ID: <strong>{{$data['issueid']}}</strong> <br>Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong><br> is awaiting actions to be taken.

Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => $data['app_url'] . 'user/actionsystem/openissue' ])

Propose Action
@endcomponent

@endcomponent
