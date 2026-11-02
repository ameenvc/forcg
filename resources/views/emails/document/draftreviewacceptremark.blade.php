<style>
    .colored {
        color: #dc3545 !important;
    }
</style>
@component('mail::message')
Module: Document Management.

<strong>Remark added by {{$data['reviewedby']}}</strong><br/>

Dear reviewer,<br/>

We are pleased to inform you that some remarks have been added while accepting the review of the below draft. 
Please review the remarks<br>

Document name: <strong>{{$data['docname']}} </strong><br>
Document type : <strong>{{$data['type']}} </strong><br>
Remarks : <span class="colored">{{$data['remarks']}} </span><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent