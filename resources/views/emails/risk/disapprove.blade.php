@component('mail::message')
Module: Enterprise Risk.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Disapprove Reason: <strong style="color:red">{{ $data['disapprovereason'] }}</strong><br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent
