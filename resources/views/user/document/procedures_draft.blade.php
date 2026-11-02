@extends('layouts.backend')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script async charset="utf-8" src="//cdn.embedly.com/widgets/platform.js"></script>
    <style>
    .rich_text_container img {
        max-width: 100% !important;
        max-height: 300px !important;
        height: auto !important;
        object-fit: contain;
    }

    .rich_text_container table tr td {
        border: 1px solid #6f7171 !important;
    }
    .block-content .block, .block-content .items-push > div, .block-content .push, .block-content p {
	    margin-bottom: 0;
    }
    .cursor-pointer {
        cursor: pointer;
    }

    .editor-container {
        width: 90%;
        font-size: 13px;
    }
    .parent-table.hovered.table-bordered > tbody > tr:hover > td {
        background-color: #dddddd;
        border-color: #dddddd;
    }
    .content-item.hovered:hover {
        background-color: #dddddd;
        border-color: #dddddd;
    }
    .draggable-row {
        cursor: grab;
        position: relative;
    }
    .draggable-row:active {
        cursor: grabbing;
    }
    .dragging {
        border: 2px solid #28a745 !important;
        background-color: #f8f9fa !important;
        opacity: 0.7;
    }

    .draggable-row.drag-over {
        border-bottom: 2px dashed #28a745 !important;
    }
    
    /* Drag handle icon */
    .drag-handle {
        display: none;
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
        color: #999;
        cursor: grab;
        z-index: 10;
        padding: 5px 10px;
        background: white;
        border-radius: 4px;
        transition: all 0.2s ease;
        font-weight: bold;
        line-height: 1;
    }
    
    .draggable-row .drag-handle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .draggable-row:hover .drag-handle {
        color: #3366CC;
        background: #e3f2fd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .drag-handle:active {
        cursor: grabbing;
        background: #bbdefb;
    }
    
    /* Drag-drop mode styling */
    .parent-table.js-table-drag-drop {
        /* margin-left: 50px; */
        /* background-color: #fffef5; */
        /* border: 2px dashed #ffc107 !important; */
    }
    
    /* Hide images/videos/iframes/tables in drag-drop mode for better UX */
    .parent-table.js-table-drag-drop .rich_text_container img,
    .parent-table.js-table-drag-drop .rich_text_container iframe,
    .parent-table.js-table-drag-drop .rich_text_container video,
    .parent-table.js-table-drag-drop .rich_text_container table,
    .parent-table.js-table-drag-drop img,
    .parent-table.js-table-drag-drop iframe,
    .parent-table.js-table-drag-drop video {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        max-height: 0 !important;
        overflow: hidden !important;
    }
    
    /* Hide Edit & Delete buttons in drag-drop mode */
    .parent-table.js-table-drag-drop .fa-edit,
    .parent-table.js-table-drag-drop .fa-trash {
        display: none !important;
    }
    
    /* Show drag indicator on hover */
    .draggable-row:hover {
        background-color: #f8f9fa;
        border-left: 3px solid #3366CC;
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script src="https://ckeditor.com/apps/ckfinder/3.5.0/ckfinder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/document/editor.js') }}"></script>
    <script>
        const createEditor = (containerId) => {
            editorInit(containerId);
        }
    </script>
</head>

<body>
    @php
    $data=$data1=[];
    @endphp
    <!-- Page Content -->
    <div class="content" style="width:97%;">
        <div class="alert alert-primary" id="trashsuccess" style="display:none;font-size:12px;">
            Draft Content deleted successfully
        </div> 
        <div class="alert alert-danger" id="trashdanger" style="display:none;font-size:12px;">
            Draft Content deletion process failed
        </div> 
        <!-- Discussion -->
        <div class="block block-rounded">          
            <div class="block-header block-header-default">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a class="link-fx btn btn-sm btn-alt-primary" href="{{ route('doc.document_draft')}}"><i style="font-size:1.3em;color: #3366CC;" class="fa fa-2x fa-arrow-left"></i></a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <h3 class="block-title text-center" style="font-size:1.1em;color: #3366CC;word-wrap: break-word;padding-left:10%;padding-right:10%;">{{$docname->document_name}}  </h3>
                <div class="block-options" >
                    <div class="input-group input-group-sm" style="font-size:1em;">
                        <button type="button" class="btn btn-sm btn-alt-primary mr-1 btn-drag-drop"
                                title="Drag and drop mode"
                                data-toggle="tooltip">
                                <i class="fa fa-hand-pointer"></i></button>
                        <a href="{{route('doc.exportdocumentdraftreportpdf',['id'=>$docname->id])}}" class="btn btn-sm btn-alt-primary mr-1 pdf-export-btn"
                            data-toggle="tooltip"  title="Export to Pdf" onclick="return handlePdfExport(event)">
                                <i class="fa fa-file-export fa-1x" style="color:#3366CC;"></i>
                        </a>                                 
                        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    </div>
                    
                </div>
            </div>
            <div class="block-content">
                @if(Session::has('msg'))
                    <div class="alert alert-success">
                        <a class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
                @if(count($documentContents)>0)
                <div class="table-responsive document-table">
                    @include('user.document._procedures_draft_item')
                </div>
                @else
                <div class="row" style="padding-bottom:10px;">
                    <div class="col-sm-12 text-center text-danger">
                        <p style="font-size: 13px;">There are no contents inside the draft</p>
                    </div>
                </div>
                @endif
            </div>
           <!-- Comments tab starts --> 
            <div class="block block-rounded" style="margin-left:25px;">
                <ul class="nav nav-tabs nav-tabs-alt align-items-center comment-padding" data-toggle="tabs"
                    role="tablist" id="myTab">                     
                    <li class="nav-item">
                        <a class="nav-link active" href="#ecom-product-comments" data-toggle="tab">Comments ({{count($draft_comments)}})</a>
                    </li>                           
                </ul>
                <div class="block-content tab-content">
                    <!-- Comments -->
                    <div class="tab-pane pull-x active comment-padding" id="ecom-product-comments"
                        role="tabpanel">                   
                            @foreach($doc->comments as $comment)
                                @if(!$comment->is_deleted && $comment->parent_id==0)
                                    @if($comment->admin || $comment->user)
                                        <div class="media push" style="padding-bottom:14px;">
                                            <a class="mr-3" href="javascript:void(0)">
                                                @if($comment->admin)
                                                <img class="img-avatar img-avatar32"
                                                    src="{{ ($comment->admin && $comment->admin->profile_picture) ? '/storage/profile/' . $comment->admin->profile_picture : asset('media/avatars/avatar3.jpg') }}"
                                                    alt="">
                                                @else
                                                <img class="img-avatar img-avatar32"
                                                    src="{{ ($comment->user && $comment->user->profile_picture) ? '/storage/profile/' . $comment->user->profile_picture : asset('media/avatars/avatar3.jpg') }}"
                                                    alt="">
                                                @endif
                                            </a>
                                            <div class="media-body">
                                                <a class="font-w600" href="javascript:void(0)">
                                                    @if($comment->user)
                                                    {{ ucfirst($comment->user->name) }}
                                                    @elseif($comment->admin)
                                                    {{ ucfirst($comment->admin->name) }}
                                                    @endif
                                                </a>
                                                @php
                                                    $commentString = preg_replace('/<p>&nbsp;<\/p>/', '', $comment->comment);
                                                    $commentString = preg_replace('/<p>(.*?)<\/p>/', '$1<br>', $commentString);
                                                @endphp
                                                <p class="my-1"><span style="font-size:13px;">{!! $commentString !!}</span></p>
                                                <a class="mr-1" href="javascript:void(0)"><span style="font-size:11px;">Like</span></a>
                                                <a class="mr-1" href="javascript:void(0)"
                                                    onclick="deleteComment('{{ $comment->id }}')"><span style="font-size:11px;">Delete</span></a>
                                                <a class="mr-1" href="#reply"
                                                    onclick="reply('{{ $comment->id }}')"><span style="font-size:11px;">Reply</span></a>
                                                <span
                                                    class="text-muted"><em><span style="font-size:11px;">{{ Carbon\Carbon::createFromDate($comment->created_at)->diffForHumans()}}</span></em></span>
                                                @foreach($comment->replies as $reply)
                                                <div class="media mt-3">
                                                    <a class="mr-3" href="javascript:void(0)">
                                                        @if($reply->admin)
                                                        <img class="img-avatar img-avatar32"
                                                            src="{{ ($reply->admin && $reply->admin->profile_picture) ? '/storage/profile/' . $reply->admin->profile_picture : asset('media/avatars/avatar3.jpg') }}"
                                                            alt="">
                                                        @else
                                                        <img class="img-avatar img-avatar32"
                                                            src="{{ ($reply->user && $reply->user->profile_picture) ? '/storage/profile/' . $reply->user->profile_picture : asset('media/avatars/avatar3.jpg') }}"
                                                            alt="">
                                                        @endif
                                                    </a>
                                                    <div class="media-body">
                                                        <a class="font-w600" href="javascript:void(0)">
                                                            @if($reply->user)
                                                            {{ ucfirst($reply->user->name)}}
                                                            @elseif($reply->admin)
                                                            {{ ucfirst($reply->admin->name) }}
                                                            @endif
                                                        </a>
                                                        @php
                                                            $replyString = preg_replace('/<p>&nbsp;<\/p>/', '', $reply->comment);
                                                            $replyString = preg_replace('/<p>(.*?)<\/p>/', '$1<br>', $replyString);
                                                        @endphp
                                                        <p class="my-1" style="font-size:13px;">{!! strip_tags(str_replace('&nbsp;', '<br>', $reply->comment)) !!}</p>
                                                        <a class="mr-1" href="javascript:void(0)"><span style="font-size:11px;">Like</span></a>
                                                        <a class="mr-1" href="javascript:void(0)"
                                                            onclick="deleteReply('{{ $reply->id }}')"><span style="font-size:11px;">Delete</span></a>
                                                        <span
                                                            class="text-muted"><em><span style="font-size:11px;">{{ Carbon\Carbon::createFromDate($reply->created_at)->diffForHumans()}}</span></em></span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        
                        <form id='comment_form' method="post" action="{{ route('documentdraftcomment.store') }}"
                            enctype="multipart/form-data">
                            <div class="form-group" id="comment_div">
                                <div class="input-group">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="comment"
                                            placeholder="Write a comment..."></textarea>

                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeComment()">
                                            <i class="fas fa-paper-plane" style=""></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                        ClassicEditor
                            .create(document.querySelector('#comment'), {
                                toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                    'numberedList', 'blockQuote', 'undo', 'redo'
                                ],

                            });
                        </script>
                        <form id='reply_form' method="post" action="{{ route('documentdraftcomment.store') }}"
                            enctype="multipart/form-data" class="d-none">
                            <div class="form-group" id="reply_div">
                                <div class="input-group">
                                    <input type="hidden" name="comment_id" id="comment_id" value="">
                                    <div class="editor-container">
                                        <textarea class="form-control form-control-alt" id="reply"
                                            placeholder="Write a reply..."></textarea>
                                    </div>
                                    <div class="input-group-append"
                                        style="cursor:pointer;margin-top: auto;">
                                        <button type="submit" class="input-group-text input-group-text-alt"
                                            onclick="storeReply()">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <script>
                        ClassicEditor
                            .create(document.querySelector('#reply'), {
                                toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList',
                                    'numberedList', 'blockQuote', 'undo', 'redo'
                                ],

                            });
                        </script>
                    </div>
                    <!-- END Comments -->
                </div>
            </div>
         <!-- End Comments tab  --> 
        </div>
    </div>
    <!-- END Page Content -->
    <!-- Page JS Plugins -->
    <script>
    function saveFrom(x, y) {
        var error_selector = $('#dangerBtn' + x);
        var success_selector = $('#successBtn' + x);
        
        function showError() {
            success_selector.addClass('d-none');
            success_selector.removeClass('d-block');

            error_selector.addClass('d-block');
            error_selector.removeClass('d-none');
        }

        function showSuccess() {
            success_selector.addClass('d-block');
            success_selector.removeClass('d-none');

            error_selector.addClass('d-none');
            error_selector.removeClass('d-block');
        }

        $("form#form_id_" + y).submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('doc.update_draft_content_text') }}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                    'accept': 'application/json'
                },
                type: 'POST',
                data: $("#form_id_" + y).serialize(),
                success: function(response) {
                    $('.edit-content-modal').animate({ scrollTop: 0 }, 'slow');
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                    if (response.error != null) {
                        showError();
                        error_selector.empty().append(`<li>${response.error}</li>`)
                    } else {
                        showSuccess();
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                },
                error: function(err) {
                    showError();

                    if (err.status === 500) {
                        error_selector.empty().append(
                            `<li>Some server errors happened, we are trying to fix them soon.</li>`
                        )
                    } else if (err.status === 422) {
                        for (let i in err.responseJSON.errors) {
                            error_selector.empty().append(`<li>${err.responseJSON.errors[i]}</li>`)
                        }
                    }
                }
            });
        });
    }
    </script>
    <script>
    $(document).on('click', '[data-title]', function(e) {
        e.preventDefault();
        const content_id = $(this).data('title');
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: 'POST',
                url: "{{ route('doc.delete_draft_content') }}",
                data: {
                    id: content_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // alert('Record deleted successfully');
                    // if (response) {
                    //     setTimeout(function() {
                    //         window.location.reload();
                    //     }, 1000);
                    // }
                    if(response.status==200){
                        $('#trashdanger').hide();
                        $('#trashsuccess').css('display', 'block');
                        window.location.reload();                    
                    }
                    else if(response.status==404){
                        $('#trashdanger').css('display', 'block');
                    }
                    $(this).closest('tr').remove(); 
                },
                error: function(error) {
                    // Handle error, e.g., show an error message
                    console.error('Error deleting record: ' + error.statusText);
                }
            });
        }
    });
    </script>
    <script>
    function ViewBtn(id) {
        $('#viewModal' + id).modal('show');
    }
    </script>
    <!-- Apps Modal -->
    <!-- Opens from the modal toggle button in the header -->
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Options</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row gutters-tiny">
                            <div class="col-6">
                                <!-- Products -->
                                <a class="block block-rounded block-link-shadow bg-body" href="{{route('doc.home')}}">
                                    <div class="block-content text-center">
                                        <i class="far fa-list-alt fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm  mt-2 mb-3">
                                            Master List
                                        </p>
                                    </div>
                                </a>
                                <!-- END Products -->
                            </div>
                            <div class="col-6">
                                <!-- Sales -->

                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="{{ route('doc.register')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-file-upload fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            DAR&nbspRegister
                                        </p>
                                    </div>
                                </a>
                                <!-- END Sales -->
                            </div>
                            <div class="col-6">
                                {{-- <br> --}}
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-1"
                                    href="{{ route('doc.history')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-history fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm  mt-2 mb-3">
                                            History
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2"
                                    href="{{ route('doc.explorer')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-folder-open fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Explorer
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-2"
                                    href="{{ route('doc.governancehub')}}">
                                    <div class="block-content text-center">
                                        <i class="fa fa-folder-open fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-3">
                                            Governance Hub
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                            <div class="col-6">
                                <!-- Payments -->
                                <a class="block block-rounded block-link-shadow bg-body mb-0"
                                    href="{{ route('doc.analysis')}}">
                                    <div class="block-content text-center">
                                        <i class="fas fa-chart-bar fa-2x text-primary"></i>
                                        <p class="font-w600 font-size-sm mt-2 mb-0">
                                            Analysis
                                        </p>
                                    </div>
                                </a>
                                <!-- END Payments -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Apps Modal -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let comment_form_selector = document.getElementById('comment_form');
        let reply_form_selector = document.getElementById('reply_form');
        let reply_comment_id = document.getElementById("comment_id");
        document.addEventListener('click', function(event) {
            reply_comment_id.value = '';
            comment_form_selector.classList.remove('d-none');
            reply_form_selector.classList.add('d-none');
        });
        document.getElementById('reply_form').addEventListener('click', function(event) {
            event.stopPropagation();
        });

        function storeComment() {
            let document_id = "{{ $doc->id }}";
            let store_comment_url = "{{ route('documentdraftcomment.store') }}";
            reply_comment_id.value = null;
            $("form#comment_form").submit(function(e) {
                e.preventDefault();
                console.log("in comment form: ");
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#comment').val(),
                        document_id: document_id,
                        reply_id: $('#comment_id').val(),
                        _token: CSRF_TOKEN
                    },
                    success: function(response) {
                        if(response.status == 200)
                        {
                          $('#comment').val('');
                          window.location.reload();
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
        }

        function reply(id) {
            event.stopPropagation();
            reply_comment_id.value = id;
            comment_form_selector.classList.add('d-none');
            reply_form_selector.classList.remove('d-none');
        }

        function storeReply() {
            let document_id = "{{ $doc->id }}";
            let store_comment_url = "{{ route('documentdraftcomment.store') }}";
            console.log($('#comment_id').val());
            $("form#reply_form").submit(function(e) {
                e.preventDefault();
                console.log("in reply form: " + $('#comment_id').val());
                $.ajax({
                    url: store_comment_url,
                    headers: {
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: {
                        comment: $('#reply').val(),
                        document_id: document_id,
                        reply_id: $('#comment_id').val(),
                        _token: CSRF_TOKEN
                    },
                    success: function() {
                        $('#reply').val('');
                        window.location.reload();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
            reply_comment_id = null;
            reply_form_selector.classList.add('d-none');
            comment_form_selector.classList.remove('d-none');
        }

        function deleteComment(id) {
            let delete_comment_url = "{{ route('documentdraftcomment.destroy', ':id') }}";
            delete_comment_url = delete_comment_url.replace(':id', id);
            $.ajax({
                url: delete_comment_url,
                type: 'GET',
                headers: {
                    'accept': 'application/json'
                },
                success: function() {
                    window.location.reload();
                },
                error: function(err) {
                    console.log(err);

                }
            });

        }

        function deleteReply(id) {
            let delete_reply_url = "{{ route('documentdraftcomment.delete_reply', ':id') }}";
            delete_reply_url = delete_reply_url.replace(':id', id);

            $.ajax({
                url: delete_reply_url,
                type: 'GET',
                headers: {
                    'accept': 'application/json'
                },
                success: function() {
                    window.location.reload();
                },
                error: function(err) {
                    console.log(err);

                }

            });

        }
</script>
<script>
        function validateimg(){
            message2 = 'Are you sure? Press OK to continue.';
            return confirm(message2);
        }
    </script>
    <script>
        document.querySelectorAll('oembed[url]').forEach(element => {
            const anchor = document.createElement('a');

            anchor.setAttribute('href', element.getAttribute('url'));

            anchor.className = 'embedly-card';

            element.appendChild(anchor);

        });
    </script>
    <script>
        $(function () {
            setTimeout(function() {
                $('.alert').fadeOut('fast');
            }, 5000);
            
            $('body').on('click', '.btn-drag-drop-disabled', function () {
                if (typeof window.forceSavePendingUpdates === 'function') {
                    window.forceSavePendingUpdates();
                }
                
                if (typeof window.pendingUpdates !== 'undefined' && window.hadPendingUpdates) {
                    const notification = $('<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:#2196F3;color:white;padding:20px 30px;border-radius:5px;box-shadow:0 4px 15px rgba(0,0,0,0.3);z-index:99999;font-size:16px;text-align:center;"><i class="fa fa-sync fa-spin mr-2"></i>Changes saved! Refreshing page...</div>');
                    $('body').append(notification);
                    
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                    
                    return;
                }
                
                $(this).html('<i class="fa fa-hand-pointer"></i>');
                $(this).removeClass('btn-drag-drop-disabled');
                $(this).addClass('btn-drag-drop');
                $(this).attr('title', 'Drag and drop mode');
                
                $('.parent-table').removeClass('js-table-drag-drop');
                $('.parent-table').removeClass('hovered');
                $('.content-item').removeClass('hovered');
                
                $('.parent-table').find('.tr-draggable').each(function () {
                    $(this).attr('draggable', false);
                    $(this).removeClass('draggable-row');
                });
                
                // Re-initialize lazy loading for images now that we're back in normal mode
                setTimeout(function() {
                    if (window.initLazyLoading) {
                        window.initLazyLoading();
                    }
                }, 100);
                
            }).on('click', '.btn-drag-drop', function () {
                $(this).html('<i class="fa fa-mouse-pointer"></i>');
                $(this).removeClass('btn-drag-drop');
                $(this).addClass('btn-drag-drop-disabled');
                $(this).attr('title', 'Normal mode');
                
                $('.parent-table').addClass('js-table-drag-drop');
                $('.parent-table').addClass('hovered');
                $('.content-item').addClass('hovered');
                
                $('.parent-table').find('.tr-draggable').each(function () {
                    $(this).attr('draggable', true);
                    $(this).addClass('draggable-row');
                });
                
                dragdrop();
            });
        })
    </script>

    <div id="pdfExportOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:white; padding:40px; border-radius:10px; text-align:center; box-shadow:0 4px 20px rgba(0,0,0,0.3); max-width:400px;">
            <div class="spinner-border text-primary" role="status" style="width:4rem; height:4rem; margin-bottom:20px;">
                <span class="sr-only">Loading...</span>
            </div>
            <h4 style="color:#333; margin-bottom:15px; font-weight:600;">Generating PDF...</h4>
            <p style="color:#666; font-size:14px; margin-bottom:10px;">
                <strong>Please wait.</strong> This may take a few moments.
            </p>
            <p style="color:#999; font-size:12px; margin:0;">
                Processing content and images...
            </p>
            <div class="progress mt-3" style="height:8px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <script>
        function handlePdfExport(event) {
            if (typeof validateimg === 'function' && !validateimg()) {
                return false;
            }
            
            const overlay = document.getElementById('pdfExportOverlay');
            overlay.style.display = 'flex';
            
            let attempts = 0;
            const maxAttempts = 300;
            
            const checkDownloadStatus = setInterval(function() {
                attempts++;
                
                fetch('{{ route("doc.checkPdfDownloadStatus") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.in_progress || attempts >= maxAttempts) {
                        clearInterval(checkDownloadStatus);
                        overlay.style.display = 'none';
                        
                        if (attempts >= maxAttempts) {
                            console.warn('PDF download check timed out after 5 minutes');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking PDF download status:', error);
                    if (attempts >= 10) {
                        clearInterval(checkDownloadStatus);
                        overlay.style.display = 'none';
                    }
                });
            }, 1000);
            
            return true;
        }
    </script>
    
    <!-- Image Lazy Loading Script -->
    <style>
        /* Hide all images and media in drag-and-drop mode */
        .js-table-drag-drop img,
        .js-table-drag-drop video,
        .js-table-drag-drop iframe,
        .js-table-drag-drop .ck-media__wrapper {
            display: none !important;
        }
    </style>
</body>

</html>
@endsection