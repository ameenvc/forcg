@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br><br>
Issue with ID: <strong>{{$data['issueid']}}</strong>
<br> Type: <strong>{{$data['type']}}</strong>
<br>Department: <strong>{{$data['department']}}</strong>
<br>Due date as : <strong>{{$data['duedate']}}</strong>
<br> Remarks as: <strong style="color:red;">{{ $data['remarks'] }}</strong>
<br> has been reviewed and <strong>NOT ACCEPTED</strong> by reviewer.

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent