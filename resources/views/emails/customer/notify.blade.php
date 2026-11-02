@component('mail::message')
You received a new survey response from a valued customer.

Customer Name: {{ $data['name'] }}<br>
Location: {{ $data['location'] }}<br>
Filled out by {{ $data['filled_by_name'] }} ; {{ $data['filled_by_position'] }}.<br>

Take the time to review the survey response and provide any necessary follow-up actions or feedback. Your prompt attention to this matter is greatly appreciated.

@component('mail::button', ['url' => $data['app_url'] . 'user/customer/history/' . $data['id']])
View the response
@endcomponent

@endcomponent

