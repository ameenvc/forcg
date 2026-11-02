@component('mail::message')
Module: Audit Management<br/>

<strong>{{$data['item']}}</strong><br/>

Dear {{$data['name']}},<br/>

{{$data['email_content']}}<br/>
The Checklist,department and suggestions for improvement are listed below<br/>
<b>Checklist - {{$data['checklistname']}}</b><br/>
<b>Department - {{$data['departmentname']}}</b><br/>
<b>Suggestions - <?php echo nl2br($data['suggestions']);?></b><br/>




Thanks,<br>
{{ config('app.name') }}
@endcomponent