@component('mail::message')
Module: HSE Observations
ISSUE DISAPPROVED.


Your Issue no <strong>{{$data['issueid']}} </strong>  Has Been Dispproved by the Management Representative.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
