<style>
    .top-right {
            position: absolute;
            top: -9px;
            margin-left:15px !important;
        }
    .notify_dot {
        height: 20px;
        width: 20px;
        background-color: #dc3545;
        border-radius: 50%;
        display: inline-block;
        color:#fff;
        font-weight:normal;
        font-size:10px;
        line-height:2em;
    }   
</style>

<div class="dropdown d-inline-block ml-2">
        @if($comment_count!==0) 
        <button type="button" class="btn btn-sm btn-dual" id="comment_notification" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false" onclick="getuserdetails('{{Auth::user()->email}}')">
            <i class="fa fa-fw fa-bell"></i>        
           <div class="top-right" id="comment_btn">
             <span class="notify_dot">{{$comment_count}}</span>
           </div>
        </button>
        @else
        <button type="button" class="btn btn-sm btn-dual" id="comment_notification" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>        
        </button>
        @endif   

        <button style="display:none;" type="button" class="btn btn-sm btn-dual" id="comment_notify" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
        </button>
       
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="comment_notification">
            <div class="p-2 bg-primary-dark text-center rounded-top">
                <h5 class="dropdown-header text-uppercase text-white">Latest Comments</h5>
            </div>
            <div style="overflow-x:hidden;overflow-y:auto;width:360px;" id="content">
                <ul class="nav-items mb-0"> 
                    @if(count($document_comments)>0) 
                        @foreach($document_comments as $document_comment)
                        @if($document_comment->user || $document_comment->admin)
                            @php
                                 $user_id = App\User::where('email',Auth::user()->email)->value('id');
                                 $total = App\GovernanceHubCommentViewedDetail::where('comment_id',$document_comment->id)
                                                                                ->where('user_id',$user_id)
                                                                                ->first();
                                                                                      
                            @endphp
                            <li>
                                 <a class="text-dark media py-2" href="{{ url('/user/doc/procedures/?id='.$document_comment->commentable_id.'&comment='.$document_comment->id) }}">
                                    <div class="mr-2 ml-3">
                                    <i class="far fa-comment-dots" style="font-size:11px;"></i>
                                    </div>
                                    <div class="media-body pr-2"> 
                                        @if($total)
                                          <span class="font-w600 text-muted"> 
                                        @else
                                          <span class="font-w600 text-primary">
                                        @endif
                                            @if($document_comment->user)
                                                {{ ucfirst($document_comment->user->name) }}
                                                @elseif($document_comment->admin)
                                                {{ ucfirst($document_comment->admin->name) }}
                                            @endif                                                         
                                            commented on                      
                                            '{{$document_comment->documentdetails?ucfirst($document_comment->documentdetails->document_name):''}}' procedure 
                                        </span>
                                        <span class="font-w400 text-muted">
                                            : {{Str::limit(strip_tags(html_entity_decode($document_comment->comment)), 40)}}                                      
                                        </span>
                                    </div>
                                </a>
                            </li>
                        @endif
                        @endforeach     
                            <li>
                                <a class="text-dark media py-2">&nbsp;</a>
                            </li> 
                    @else
                        <li>
                            <a class="text-danger text-center">
                            <div class="mr-2 ml-3 mt-3">There are no comments posted</div>
                            </a>
                        </li> 
                    @endif                  
                </ul>
            </div>
        </div>
</div>
<script>
    function getuserdetails(email){
        $.ajaxSetup({
                      headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                               }
                    });
                    
                    $.ajax({
                    type: 'POST',
                    url: '/user/doc/updateusercommentnotification',
                    data: {"email_value": email}          
                    ,success: function(response) {
                        if(response.status == 200){
                           document.getElementById('comment_btn').style.display="none";
                           document.getElementById('comment_notification').style.display="none";
                           document.getElementById('comment_notify').style.display="";
                        }                                
                    }
                    ,error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }	
            });
        
    }
</script>