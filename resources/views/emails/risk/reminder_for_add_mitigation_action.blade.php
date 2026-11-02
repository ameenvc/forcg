@component('mail::message')
Module: Enterprise Risk.

<strong>Subject: </strong>Mitigation Action Missing for {{$data['risk_name']}}
{{-- <strong>{{$data['item']}}</strong> --}}

Dear {{$data['risk_owner']}},

{{$data['email_content']}}<br/><br>
Risk ID: <strong>{{$data['rid']}}</strong><br>
Risk Level: <strong>{{$data['risklevel']}}</strong><br>
Risk Description: <strong>{{$data['description']}}</strong><br>
Added Date: <strong>{{$data['addeddate']}}</strong><br>

@component('mail::button', ['url' => $data['app_url'] . 'user/riskmgmt?open_modal=true&id='.$data['riskid']])
Link
@endcomponent

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent