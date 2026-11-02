@component('mail::message')
Module: Enterprise Risk.

{{$data['email_content']}}<br/><br/>
<strong>New value: <span style="color:red">{{ $data['new_process'] }}</span></strong>
<br>
Action detail<br>
Action ID: <strong>{{ $data['action_id'] }}</strong><br>
Department: <strong>{{ $data['department'] }}</strong><br>
Owner: <strong>{{ $data['owner'] }}</strong><br>
Due Date: <strong>{{ $data['due_date'] }}</strong><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
