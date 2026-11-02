@component('mail::message')
Module: Opportunity
 
An oppoortunity is awaiting for review,<br>
Opportunity ID :<strong>{{$data['dep_id']}} </strong><br>
Opportunity name: <strong>{{$data['oppname']}} </strong><br>
Process stage :<strong>{{$data['status']}} </strong><br>
Department :<strong>{{$data['department']}} </strong><br>
Target date :<strong>{{$data['tardate']}} </strong><br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
