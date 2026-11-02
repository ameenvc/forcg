@component('mail::message')
Module: Health & Safety Risks.

A new risk has been raised.

A new risk with ID: <strong>{{$data['actid']}} </strong>  <br> has been raised.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent
