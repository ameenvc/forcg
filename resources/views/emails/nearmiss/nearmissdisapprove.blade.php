@component('mail::message')
Module: Near Miss.

Issue disapproved.

Your Issue no <strong>{{$data['issueid']}} </strong>  Has been dispproved by the Near miss reviewer.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
