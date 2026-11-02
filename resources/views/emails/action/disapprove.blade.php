@component('mail::message')
Module: Audit Management.

Issue disapproved.

Your Issue no <strong>{{$data['issueid']}} </strong>  Has Been Dispproved by the Management Representative.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
