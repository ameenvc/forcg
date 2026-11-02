@component('mail::message')
Module: Near Miss.

Implementation status is updated as 100% by the issue owner.

Kindly perform the closure review to close the issue no <strong>{{$data['issueid']}} </strong>.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
