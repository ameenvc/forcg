@component('mail::message')
Module: Audit Management<br/>

<strong>{{$data['item']}}</strong><br/>

Dear {{$data['name']}},<br/>

{{$data['email_content']}}.The reason for removal of this checklist is {{$data['reason']}}<br/><br/>
  Checklist details are listed below<br/>
<b>Checklist - {{$data['checklistname']}}</b><br/>
<b>{{$data['belongheading']}} - {{$data['residename']}}</b><br/>


<!-- <a href="{{$data['app_url']}}">Click here to approve the removal request</a><br/><br/> -->
<a href="{{$data['app_url'].'user/internalaudit/checklistdetails/'.$data['checklistid']}}">Click here to approve or cancel the removal request</a><br/><br/>

Thanks,<br>
{{ config('app.name') }}
@endcomponent