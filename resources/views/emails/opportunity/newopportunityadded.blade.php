@component('mail::message')
Module: Opportunity
<br><br>
@if(isset($data['item']))
<strong>{{$data['item']}}</strong>
@endif
<br><br>
Dear Sir,<br><br>
@if(isset($data['email_content']))
{{$data['email_content']}}<br/><br>
@endif
Opportunity ID: <strong>{{$data['dep_id']}} </strong><br>
Opportunity name: <strong>{{$data['oppname']}} </strong><br>
Opportunity description: <strong>{{$data['oppdes']}} </strong><br>
Department: <strong>{{$data['department']}} </strong><br>

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left" valign="middle">
            <span style="font-size: 1rem; font-weight:400">Thanks & Regards,</span><br>
            <span style="font-size: 1rem; font-weight:400">{{ config('app.name') }}</span>
        </td>
        <td align="right" valign="middle">
            @if (!empty($data['enable_button']) && !empty($data['initial_review_url']))
                <a href="{{ $data['initial_review_url'] }}"
                   style="
                        display:inline-block;
                        padding:10px 18px;
                        background-color:#3869D4;
                        color:#ffffff;
                        text-decoration:none;
                        border-radius:4px;
                        font-weight:600;
                        font-size:14px;
                   ">
                    Review
                </a>
            @endif
        </td>
    </tr>
</table>
@endcomponent
