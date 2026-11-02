@component('mail::message')

Dear <b>{{ $data->name }}</b>,<br/>

Thank you for registering with CGBOD! Your account has been created successfully and is now awaiting approval from our administration team.<br/><br/>
<b>Next Steps</b><br/>
Our team will review your details and you will receive an update once your account has been approved. This typically takes up to 24-48 hours.<br/><br/>

Thank you for your patience and we look forward to having you on board.

Thanks,<br> 
{{ config('app.name') }}
@endcomponent





