@component('mail::message')
Module: NCR & Corrective Actions.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br><br>
Issue ID: <strong>{{$data['issueid']}}</strong><br>
Disapproval reason as: <strong style="color:red;">{{ $data['disapproval_reason'] }}</strong> <br>
has been dispproved by the Management Representative.<br>


Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent