@component('mail::message')

<h3>Module: Customer Complaint</h3>

<p>
    <strong>{{ $data['email_item'] }}</strong>
</p>

<p>
    Dear Sir,
</p>

<p>
    {!! $data['email_content'] !!}
</p>

<p>
    Complaint ID:
    <strong>{{ $data['complaint_id'] }}</strong>
</p>

<p>
    Complaint Title:
    <strong>{{ $data['title'] }}</strong>
</p>

@if(!empty($data['target_date']))
<p>
    Correction Target Date:
    <strong>{{ $data['target_date'] }}</strong>
</p>
@endif

<p>
    Current Status:
    <strong>{{ $data['status'] }}</strong>
</p>

@if(!empty($data['responsible_person']))
<p>
    Responsible Person:
    <strong>{{ $data['responsible_person'] }}</strong>
</p>
@endif

@if(!empty($data['responsible_department']))
<p>
    Responsible Department:
    <strong>{{ $data['responsible_department'] }}</strong>
</p>
@endif

@component('mail::button', ['url' => $data['url']])
{{$data['btn_text']}}
@endcomponent
<br>

Thanks & Regards,<br>
{{ config('app.name') }}

@endcomponent
