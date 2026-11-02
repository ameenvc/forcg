@component('mail::message')
Module: Vendor Management.

A vendor is waiting for your evaluation.

A vendor with ID: <strong>{{$data['vid']}}</strong> <br>Due date  : <strong>{{$data['duedate']}}</strong><br> is awaiting your evaluation.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent
