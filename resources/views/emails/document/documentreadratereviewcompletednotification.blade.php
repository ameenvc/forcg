<style>
    .colored-star {
        color: #e5ae67 !important;
        font-size: 24px;
    }
    .blank-star {
        color: #C8C8C8 !important;
        font-size: 24px;
        text-shadow: 0 0 1px #000;
    }
</style>  
@component('mail::message')
Module: Document Management

Dear {{$data['rate_requested_user']}},

Congratulations! We are pleased to inform you that <b>{{$data['reviewed_by_name']}}</b> has completed the requested review for the <b>{{$data['document_name']}}</b> document.

Here are the details:<br/>
  Procedure : <b>{{$data['document_name']}}</b><br>
  Reviewer  : <b>{{$data['reviewed_by_name']}}</b><br>
  Status    : <span class="colored-star">{{$data['rating']}}</span> <span class="blank-star">{{$data['rating_blank']}}</span><br>
 
 Thank you for your commitment to maintaining the highest standards.<br><br>


 Thanks,<br>
{{ config('app.name') }}
@endcomponent