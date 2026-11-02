@extends('admin.nav')

@section('content')

<main id="main-container">
         <!-- Services -->
       
                <!-- END Hero -->

    <!-- Page Content -->
    <div class="content" style="width:88%;margin-left:70px;margin-top:-75px">
                  <!-- Horizontal -->
                   <div class="block block-rounded">  
                   
                   <div class="block-header">
                       <h3 class="block-title">Edit Email Notification</h3>
                       <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                                <ol class="breadcrumb breadcrumb-alt">
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                     
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">
                                        <a class="link-fx" href="{{route('admin.emailnotificationlist')}}">Back</a>
                                    </li>
                                </ol>
                            </nav>
                            
                       
                        </div> 
                        @if(Session::has('msg'))
                                       <div class="alert alert-danger">
                                           <a class="close" data-dismiss="alert">×</a>
                                           {!!Session::get('msg')!!}
                                       </div>
                                     @endif                       
                       <div class="block-content block-content-full" style="width:90%;margin-left:100px">
                           <div class="row">                                
                               <div class="col-lg-10">
                               
                                   <!-- Form Horizontal - Default Style -->
                           <form class="mb-5" method="POST" action="{{route('admin.updateemailnotification')}} " onsubmit="return validation()" enctype="multipart/form-data" >
                               {{csrf_field()}}
                                   
                               <div class="col-lg-10">
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Module</label>
                                           <div class="col-sm-8">
                                                <input type="text" style="font-size: 13px;" class="form-control" name="item" id="item" value="{{ucfirst($data->module)}}" readonly>
                                            </div>           
                                       </div>

                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="item">Item </label>
                                           <div class="col-sm-8">
                                              <input type="text" style="font-size: 13px;" class="form-control" name="item" id="item" value="{{$data->item}}" readonly>      
                                           </div>           
                                       </div>
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="frequently">Frequently </label>
                                           <div class="col-sm-8">
                                                @php 
                                                    $data->frequently = !$data->frequently ? 'monthly' : $data->frequently;
                                                @endphp
                                               <select class="form-control js-select2" name="frequently" id="frequently" data-placeholder="Select Frequency" style="font-size: 14px">
                                                   <option value=""></option>
                                                    <option value="{{ \App\EmailNotification::DAILY_STATUS }}"
                                                            {{ $data->frequently == \App\EmailNotification::DAILY_STATUS ? 'selected' : '' }}>Daily</option>
                                                    <option value="{{ \App\EmailNotification::WEEKLY_STATUS }}"
                                                           {{ $data->frequently == \App\EmailNotification::WEEKLY_STATUS ? 'selected' : '' }}>Weekly</option>
                                                    <option value="{{ \App\EmailNotification::MONTHLY_STATUS }}"
                                                           {{ $data->frequently == \App\EmailNotification::MONTHLY_STATUS ? 'selected' : '' }}>Monthly</option>
                                                    <option value="{{ \App\EmailNotification::YEARLY_STATUS }}"
                                                           {{ $data->frequently == \App\EmailNotification::YEARLY_STATUS ? 'selected' : '' }}>Yearly</option>
                                               </select>
                                           </div>
                                       </div>


                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="decription">Description </label>
                                           <div class="col-sm-8">
                                               <textarea class="form-control"  style="font-size: 13px;"   id="description"  name="description"  rows="5" cols="20" required>{{$data->description}}</textarea>
                                            </div>           
                                       </div>

                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="emailcontent">Email Content </label>
                                           <div class="col-sm-8">
                                               <textarea class="form-control"  style="font-size: 13px;"   id="content"  name="content" rows="10" cols="30" required>{{$data->email_content}}</textarea>
                                            </div>           
                                       </div>
                                    <!--     
                                       <div class="form-group row">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="emailnot">Select Users for Email notification</label>
                                           <div class="col-sm-8">
                                              <input style="margin-right:4px;margin-left:10px;" type="radio" value="yes" id="useremail"  name="useremail" required onclick="display()" <?php if($data->user_email_notifications=='yes'){?> checked<?php } ?>>
                                                     <label style="font-size: 13px;">Yes</label>
                                              <input style="margin-right:4px;margin-left:10px;" type="radio" value="no" id="useremail1"  name="useremail" onclick="display()"<?php if($data->user_email_notifications=='no'){?> checked<?php } ?>>
                                                     <label style="font-size: 13px;">No</label>                      
                                            </div>           
                                       </div>

                                        @if($data->user_email_notifications=='yes')
                                       <div class="form-group row" id="emailnotify">
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type"></label>
                                           <div class="col-sm-8">
                                             <select class="js-select2 form-control" name ="email_users[]" id="email_users" style="font-size: 13px;width:300px;"  multiple data-placeholder="Select Users for Email Notification"> 
                                               @php
                                                 $user_assigned = explode(',',$data->email_send_to_users);

                                               @endphp                       
                                                @foreach($users as $value)
                                                    <option value="{{$value->id}}" {{ (collect($user_assigned)->contains($value->id)) ? 'selected':'' }}> {{$value->name}}</option>
                                                @endforeach               
                                              </select>                                                       
                                            </div>           
                                       </div>
                                       @else
                                       <div class="form-group row" id="emailnotify" >
                                           <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type"></label>
                                           <div class="col-sm-8">
                                             <select class="js-select2 form-control" name ="email_users[]" id="email_users" style="font-size: 13px;width:300px;"  multiple data-placeholder="Select Users for Email Notification">                        
                                                @foreach($users as $value)
                                                    <option value="{{$value->id}}"> {{$value->name}}</option>
                                                @endforeach               
                                              </select>                                                       
                                            </div>           
                                       </div>
                                       @endif -->

                                       <input type="hidden" name="id" value="{{$data->id}}"/>

                                        <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" style="font-size: 13px;"> </label>
                                            <div class="col-sm-8">
                                               <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;margin-left:350px" value="Update">
                                            </div>
                                        </div>
                               </div>
                            </form>                                             
                        </div>
               </div>
           </div>
       </div>

       <script>
        // window.onload = function(){
        // $(document).ready(function(){
        //     if($("#useremail1:checked").val()=='no')
        //         {
        //             $('#emailnotify').hide();
        //         }        
        //     });

        //     $("#useremail").change(function(){
        //     if($("#useremail:checked").val()=='yes') 
        //     {
        //         $('#emailnotify').show();           
        //     }
        // });
        
        // $("#useremail1").change(function(){
        //     if($("#useremail1:checked").val()=='no') 
        //     {
        //         $('#emailnotify').hide();           
        //     }
        // });
            
    
        // }
        //   function display()
        //   {
        //     if(document.getElementById('useremail').checked){
        //         document.getElementById('email_users').required=true;
        //     }
        //     else if(document.getElementById('useremail1').checked){
        //         document.getElementById('email_users').required=false;
        //     }
        //   }
       </script>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
@endsection