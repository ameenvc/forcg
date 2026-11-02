@component('mail::message')
Module: Context Management.

Change in request awaiting approval.

Request with ID: <strong>{{$data['crid']}} </strong>  <br>Reviewed by: <strong>{{$data['reviewed_by']}} </strong><br> Title: <strong>{{$data['title']}}</strong> <br> is awaiting your approval.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent







