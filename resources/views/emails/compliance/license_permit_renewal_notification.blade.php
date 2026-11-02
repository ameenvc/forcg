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
Expiry Status: <strong>@if($data['status']=='Expired') <span style="color:red;">{{$data['status']}}</span> @else <span style="color: #af6f1e;">{{$data['status']}} @endif</span></strong><br>

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent