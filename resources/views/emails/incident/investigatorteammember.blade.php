@component('mail::message')
Module: Incident Reporting.

Assigned investigaion report.

Your are receiving this mail because you are appointed as team member to investigate the incident report.<br>
Incident report :<strong>{{$data['inc_id']}} </strong><br>
Target date: <strong>{{$data['tardate']}} </strong><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
