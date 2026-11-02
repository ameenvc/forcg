@component('mail::message')
Module: Vendor Management.

A vendor is waiting for your evaluation.

A vendor with ID: <strong>{{$data['vid']}}</strong> <br>Added by: <strong>{{$data['created_by']}} </strong><br> Type: <strong>{{$data['vendor_type']}}</strong> <br>Due date as : <strong>{{$data['duedate']}}</strong><br> is awaiting your evaluation.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent
