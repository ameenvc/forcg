@component('mail::message')
Module: Compliance Management.

<strong>{{$edata['item']}}</strong>

Dear Sir,

{{$edata['email_content']}}<br/><br>
Compliance ID: <strong>{{$edata['cid']}}</strong><br>
License/Permits ID: <strong>{{$edata['licenseid']}}</strong><br>
License/Permits Name: <strong>{{$edata['name']}}</strong><br>
License Number: <strong>{{$edata['licensenumber']}}</strong><br>
Expiry Date: <strong>{{$edata['expirydate']}}</strong><br>
Added By: <strong>{{$edata['addedby']}}</strong><br> 


Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent