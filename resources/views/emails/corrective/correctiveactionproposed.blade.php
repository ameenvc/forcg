@component('mail::message')
Module: NCR & Corrective Actions.

Actions proposed.

New actions have been proposed for issue with ID:<strong>{{$data['issueid']}} </strong><br> Type: <strong>{{$data['type']}}</strong>  <br>Department: <strong>{{$data['department']}} </strong><br>Due Date as : <strong>{{$data['duedate']}}</strong>.



Thanks,<br> 
{{ config('app.name') }}
@endcomponent
