@component('mail::message')
Module: Near Miss.

Proposed actions not accepted by reviewer.

Issue with ID: <strong>{{$data['issueid']}}</strong>
<br>Raised by: <strong>{{$data['name']}} </strong>
<br> Type: <strong>{{$data['type']}}</strong>
<br>Department: <strong>{{$data['department']}} </strong>
<br>Target date as : <strong>{{$data['targetdate']}}</strong>
<br>Disapproved reason as : <strong style="color:red">{{ $data['disapproved_reason'] }}</strong>
<br> has been reviewed and <strong>NOT ACCEPTED</strong> by reviewer.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
