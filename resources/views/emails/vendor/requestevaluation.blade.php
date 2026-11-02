@component('mail::message')
Module: Vendor Management.

Dear {{ $data['name'] }},<br/>

{!! nl2br($data['email_content']) !!}

Vendor ID: {{ $data['vendor_id'] }}<br>
Vendor Name: {{ $data['vendor_name'] }}

@component('mail::button', ['url' => $data['app_url'] . 'user/vendor/peval?val=' . $data['vendor_id']])
Link
@endcomponent

Thanks,<br> 
{{ config('app.name') }}
@endcomponent
