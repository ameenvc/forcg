@component('mail::message')
Module: Compliance Management.

<strong>Subject: <strong>{{$data['item']}}</strong>

Dear {{$data['admin_name']}},

{{$data['email_content']}}<br/><br>
Type: <strong>{{$data['type']}}</strong><br>
Regulatory Authority Name: <strong>{{$data['name']}}</strong><br>
Legal Document Name: <strong>{{$data['doc_name']}}</strong><br>
Submitted By: <strong>{{$data['submitted_by']}} ({{$data['department']}})</strong><br>
Submission Date & Time: <strong>{{date('d-m-Y h:i:s A',strtotime($data['date']))}}</strong><br>
Current Status: <strong><span style="color: #af6f1e;">{{$data['status']}}</span></strong><br> 

Click the button below to submit your feedback:<br>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center" style="margin:auto;">
    <tr>
        <td align="center">
            @if($data['approval_link'])
            <a href="{{ $data['approval_link'] }}" target="_blank" rel="noopener noreferrer"
                 style="display:inline-block; padding:10px 18px; background-color:#007bff; color:#ffffff; text-decoration:none; border-radius:4px; font-weight:600;">
                Approve
            </a>
            @else
            <a href="#" rel="noopener noreferrer"
                 style="display:inline-block; padding:10px 18px; background-color:#007bff; color:#ffffff; text-decoration:none; border-radius:4px; font-weight:600;">
                Approve
            </a>
            @endif
        </td>
        <td width="12" style="font-size:0; line-height:0;">&nbsp;</td>
        <td align="center">
            @if($data['reject_link'])
            <a href="{{ $data['reject_link'] }}" target="_blank" rel="noopener noreferrer"
                 style="display:inline-block; padding:10px 18px; background-color:#dc3545; color:#ffffff; text-decoration:none; border-radius:4px; font-weight:600;">
                Reject
            </a>
            @else 
            <a href="#" rel="noopener noreferrer"
                 style="display:inline-block; padding:10px 18px; background-color:#dc3545; color:#ffffff; text-decoration:none; border-radius:4px; font-weight:600;">
                Reject
            </a>
            @endif
        </td>
    </tr>
</table>
<br>
Thanks & Regards,<br>
{{ config('app.name') }}
@endcomponent