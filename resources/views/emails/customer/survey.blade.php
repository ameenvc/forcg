@component('mail::message')
We would like to hear from you - Please complete our customer satisfaction survey

Dear {{ $data['name'] }} team,
<p style="font-size: 16px; line-height: 1.5em;text-align: justify">
We hope this email finds you well. As a valued customer of {{ $data['company'] }}, we would like to take this opportunity to thank you for your business and for trusting us with your needs.
To ensure that we continue to provide the best service possible, we are conducting a customer satisfaction survey. Your feedback is incredibly important to us, and we would be grateful if you could take a few minutes to complete the survey.
The survey is being sent through our cgbod web application. Your feedback will help us identify areas where we can improve our service and ensure that we continue to meet your needs.
We would like to assure you that all responses will be kept confidential and will only be used to improve our services. Your feedback is greatly appreciated, and we thank you in advance for taking the time to complete the survey.
If you have any questions or concerns, please do not hesitate to contact us. We look forward to hearing from you soon.
</p>

Best regards,<br>{{ $data['company'] }}

@component('mail::button', ['url' =>  $data['app_url'] . 'user/customer/showQstn/' . $data['id'] . ($data['type'] !== '0' ? '/' . $data['type'] : '')])
Survey Link
@endcomponent

@endcomponent
