@component('mail::message')
Module: Near Miss.

Issue approved.

Your Issue no <strong>{{$data['issueid']}} </strong>  Has Been Approved by the Management Representative.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent







