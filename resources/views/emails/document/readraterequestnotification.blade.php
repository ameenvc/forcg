@component('mail::message')
Module: Document Management

Dear {{$data['rate_user']}},

Mr.{{$data['sender_name']}} has specifically requested your input on the <strong>{{$data['document_name']}}</strong> document. 
Your expertise and feedback are invaluable. 

Procedure: {{$data['document_name']}}<br>
Sender: {{$data['sender_name']}}<br>


 To complete the request, please follow these steps:

 1. Click on the "Read & Rate" button below.
 2. Thoroughly review the <strong>{{$data['document_name']}}</strong> document.
 3. Provide your rating using the star system provided within the document.

 @component('mail::button', ['url' => $data['app_url'] . 'user/doc/procedures/?id=' . $data['id']])
 Read and Rate
 @endcomponent

 Your thoughtful evaluation will contribute to the enhancement of our procedures. 
 If you have any queries or require further information, please feel free to reach out. 
 Thank you for your time and commitment to this review.<br><br>


 Thanks,<br>
{{ config('app.name') }}
@endcomponent