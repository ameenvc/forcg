@component('mail::message')
Module: Near Miss.

An issue is assigned to your department.

An issue with ID: <strong>{{$data['issueid']}}</strong> <br>Raised by: <strong>{{$data['name']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Target date as : <strong>{{$data['targetdate']}}</strong><br> is awaiting actions to be taken.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent
