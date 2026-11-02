@component('mail::message')
Module: Compliance Management.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Compliance ID: <strong>{{$data['cid']}}</strong><br>
License/Permits ID: <strong>{{$data['licenseid']}}</strong><br>
License/Permits Name: <strong>{{$data['name']}}</strong><br>
License Number: <strong>{{$data['licensenumber']}}</strong><br>
Expiry Date: <strong>{{$data['expirydate']}}</strong><br>
Renewed By: <strong>{{$data['renewedby']}}</strong><br>  

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent