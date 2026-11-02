@component('mail::message')
Module: Incident Reporting

<strong>Approver Appointed</strong>

Dear {{$data['name']}}

Your are receiving this email because you are appointed as Approver for  Incident Report <strong>{{$data['inc_id']}} </strong><br>
Please start your Approval process<br><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent