@component('mail::message')

Dear <strong>{{$data['name']}}</strong>,<br/>

Thank you for signing up for CGBOD! Your account has been created successfully.<br/>
To complete your registration process, please confirm your email address by clicking the button below
@component('mail::button', ['url' => route('user.verify', ['token' => $data['token']])])
Verify My Email
@endcomponent
You are receiving this email because you signed up for CGBOD. If you did not create an account, please ignore this email and no further action is needed.<br/><br/>

Thanks,<br>
{{ config('app.name') }}
@endcomponent

