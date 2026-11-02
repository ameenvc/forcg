@component('mail::message')
Module: Enterprise Risk.

<strong>{{$data['item']}}</strong>

Dear Sir,

{{$data['email_content']}}<br/><br>
Risk ID: <strong>{{$data['grp_id']}} </strong><br>
Risk name: <strong>{{$data['riskname']}} </strong><br>
Risk description: <strong>{{$data['riskdec']}} </strong><br>
Department: <strong>{{$data['riskarea']}} </strong><br>

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
