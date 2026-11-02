<div class="dropdown d-inline-block ml-2">
        <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-bell"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
            <div class="p-2 bg-primary-dark text-center rounded-top">
                <h5 class="dropdown-header text-uppercase text-white">Latest Comments</h5>
            </div>
            <div class="scrollable table-responsive" style="width:360px;height:550px;">
                <ul class="nav-items mb-0"> 
                    @if(count($document_comments)>0) 
                        @foreach($document_comments as $document_comment)
                           @if($document_comment->user || $document_comment->admin)
                                <li>
                                    <a class="text-dark media py-2" href="{{ route('doc.procedures_draft',['id' => $document_comment->commentable_id]) }}">
                                        <div class="mr-2 ml-3">
                                        <i class="far fa-comment-dots" style="font-size:11px;"></i>
                                        </div>
                                        <div class="media-body pr-2">                          
                                            <span class="font-w600 text-dark">                                                                            
                                                @if($document_comment->user)
                                                    {{ ucfirst($document_comment->user->name) }}
                                                    @elseif($document_comment->admin)
                                                    {{ ucfirst($document_comment->admin->name) }}
                                                @endif                                                         
                                                commented on                      
                                                '{{$document_comment->documentdetails?ucfirst($document_comment->documentdetails->document_name):''}}' procedure 
                                            </span>
                                            <span class="font-w500 text-muted">
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