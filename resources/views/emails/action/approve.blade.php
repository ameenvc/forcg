@component('mail::message')
Module: Audit Management.

Congratulations ! Your issue has been Approved.

Your Issue no <strong>{{$data['issueid']}} </strong>  Has Been Approved by the Management Representative.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent







