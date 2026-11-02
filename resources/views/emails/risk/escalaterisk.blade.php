@component('mail::message')
Module: Enterprise Risk.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Risk no: <strong>{{$data['id']}} </strong><br>
Risk group :<strong>{{$data['riskgroup']}} </strong><br>
Risk area : <strong>{{$data['riskarea']}} </strong><br>
Risk name  :<strong>{{$data['riskname']}} </strong><br>
Risk description :<strong>{{$data['riskdescription']}} </strong><br>
Risk source : <strong>{{$data['risksource']}} </strong><br>
Risk drive :<strong>{{$data['riskdrive']}} </strong><br>
Risk impact <strong>{{$data['impact']}} </strong><br> 
Risk liklihood :<strong>{{$data['liklihood']}} </strong> has been identified.

Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent
