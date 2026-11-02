@component('mail::message')
Module: Incident Reporting

<strong>Reviewer Appointed</strong>

Dear {{$data['name']}}

Your are receiving this email because you are appointed as Reviewer for Incident Report <strong>{{$data['inc_id']}} </strong><br>
Please start your review process<br><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent