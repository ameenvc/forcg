@extends('pages.datatables')
@section('content')
<div class="content" style="width:100%;">
@include('user.internalaudit.checklistheader') 
    <div class="block block-rounded" >
        <div class="block-content block-content-full">           
                <div class="content" style="width:100%;">
                    <div class="block block-rounded" >
                        <div class="block-content block-content-full">
                        <div class="row" style="margin-left:40px;margin-right:40px;">
                                <div class="col-lg-12 col-md-6">                       
                                    <div class="text-danger text-center">                      
                                        No matching records
                                    </div> 
                                    <div class="text-dark text-center">                      
                                        <i class="fa fa-backward" style="font-size:15px; vertical-align:middle;"></i>
                                        <a class="link-fx text-black" style="font-size:14px;" href="{{url('/user/internalaudit/checklistdetails/'.$checklist->id)}}">Back</a>
                                    </div>                     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
            
@endsection

             