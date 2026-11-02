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
    .rich_text_container table tr td {
        border: 1px solid #6f7171 !important;
    }

    .comment-padding {
        padding: 1.25rem;
    }

    .font-13 {
        font-size: 13px !important;
    }

    .blank-star {
        color: #F4F5F6 !important;
        text-shadow: 0 0 1px #000;
    }

    .fa-stack i.fa-star {
        color: transparent;
        -webkit-text-stroke-width: 1px;
        -webkit-text-stroke-color: #ccc;
    }

    .fa-stack i.fa-star-half {
        color: #e5ae67;
        -webkit-text-stroke-width: 1px;
        -webkit-text-stroke-color: #e5ae67;
        text-shadow: 0 0 1px #000;
    }

    .mb-12 {
        margin-bottom: 12.5px;
    }

    .colored-star {
        color: #e5ae67 !important;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .editor-container {
        width: 90%;
        font-size: 13px;
    }
    .block-content .block, .block-content .items-push > div, .block-content .push, .block-content p {
	    margin-bottom: 0;
    }
    .reference-content{
      font-size:12px;
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script src="https://ckeditor.com/apps/ckfinder/3.5.0/ckfinder.js"></script>
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
    <div class="content" style="width:97%;">
        <div class="alert alert-primary" id="trashsuccess" style="display:none;font-size:12px;">
            Document Content deleted successfully
        </div> 
        <div class="alert alert-danger" id="trashdanger" style="display:none;font-size:12px;">
            Document Content deletion process failed
        </div>
        <div class="content content-boxed" style="margin-top:-3%;">
                <div class="row" style="margin-left:85px;margin-bottom:14px;">
                    <div class="col-lg-12 col-md-6" align="right">
                        <form class="d-none d-md-inline-block" action="{{url('/user/doc/search/procedures/'.$id)}}" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search.." style="border-color:#5179d6;" 
                                    id="search" name="search" value="{{$serchval}}">
                                    <div class="input-group-append">
                                         <button type="submit" class="btn btn-primary btn btn-sm">
                                            <i class="fa fa-fw fa-search" style="font-size:12px;"></i>
                                        </button>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
            <div class="row">
                <div class="col">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-alt">
                                        <li class="breadcrumb-item">
                                            <a class="link-fx btn btn-sm btn-alt-primary" href="{{ route('doc.governancehub')}}"><i style="font-size:1.3em;color: #3366CC;" class="fa fa-2x fa-arrow-left"></i></a>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                            <h3 class="block-title text-center" style="font-size:1.1em;color: #3366CC;word-wrap: break-word;padding-left:10%;padding-right:10%;">{{$docname->document_name}}  </h3>
                            <div class="block-options" >
                                <div class="input-group input-group-sm" style="font-size:1em;">
                                    <a href="{{route('doc.exportdocumentreportpdf',['id'=>$docname->id])}}" class="btn btn-sm btn-alt-primary pdf-export-btn" 
                                        data-toggle="tooltip"  title="Export to Pdf" onclick="return handlePdfExport(event)">
                                          <i class="fa fa-file-export fa-1x" style="color:#3366CC;"></i>
                                     </a> 
                                    &nbsp;&nbsp;
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
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
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <tbody>
                                        @php $i = 0; $n = 1; @endphp
                                        @foreach ($documentContents as $documentContent)
                                        @php
                                            if (isset($relatedDocumentIds) && isset($relatedDocumentIds[$i])) {
                                                $contents = \App\GovernanceHubDocumentContentRichText::where('content_id',$relatedDocumentIds[$i])
                                                ->orderBy('position', 'asc')->get();
                                            } else {
                                                $contents = [];
                                            }
                                        @endphp
                                        
                                        <tr class="table-active">
                                            <td class="text-center" style="width:50px;border-right: 1px solid #D3D3D3;">
                                                <span style="color: #3366CC;font-size:13px;" class="font-w600"><?php printf("%.1f", $n); ?></span>
                                            </td>
                                            <td class="text-left"  style="width:230px;border-right: 1px solid #D3D3D3;">
                                                <span  class="font-w600 text-uppercase">Responsibility</span>
                                            </td>
                                            <td class="text-left">
                                                <span  class="font-w600">{{ucfirst($documentContent->title)}} </span>
                                            </td>
                                            @php
                                            $email = Auth::user()->email;
                                            $docAccess = \App\Doccontroller::where('email', $email)->first();
                                            $doc_privileged = $docAccess?true:false;
                                            @endphp
                                            <td class="text-right" style="width:50px;">
                                                @if(Gate::allows('edit-doc') || $doc_privileged)
                                                <a href="#" class="font-w600" style="font-size:10px;cursor:pointer;" data-id="{{$i}}" data-toggle="modal" data-target="#edit_content_{{$i}}" title="Edit content"><i class="fa fa-2x fa-edit" style="color:grey;"></i></a>
                                                @endif
                                            </td>
                                            <td class="text-right" style="width:50px;">
                                                @if(Gate::allows('delete-doc') || $doc_privileged)
                                                <a href="#" class="font-w600" style="font-size:10px;cursor:pointer;" title="Delete" data-title=<?= $relatedDocumentIds[$i] ?>><i class="fas fa-2x fa-trash" style="color:red;"></i></a>
                                                @endif
                                            </td>    
                                        </tr>

                                            @php $m = 1; @endphp
                                            @foreach($contents as $con)
                                            <?php
                                               $pointsvalue  = str_ireplace($serchval,"<span class='badge badge-warning'>$serchval</span>",$con->rich_text);
                                            ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <span style="font-size:13px;">@php echo $n . '.' . $m; $m++; @endphp</span>
                                                    </td>
                                                    <td class="text-left">
                                                        @if(isset($con->responsibility))
                                                        <span style="word-wrap:break-word;font-size:13px;">{{ucfirst($con->responsibility)}}</span>
                                                        @else
                                                        <span>-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-left" colspan="3">
                                                        <div class="rich_text_container">
                                                            @if(isset($con->rich_text))
                                                            <span style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">{!! $pointsvalue!!}</span>
                                                            @else
                                                            <span>-</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                             @endforeach
                                        <tr>
                                            <td style="padding:0;" colspan="5">
                                                <div class="modal fade edit-content-modal" id="edit_content_{{$i}}" tabindex="-1" role="dialog" aria-labelledby="edit_contentLabel{{$i}}" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="edit_contentLabel{{$i}}">Edit Content</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="alert alert-success" id="successBtn{{$i}}" style="display:none;font-size:12px;">Content edited successfully!</div>
                                                            <ul class="alert alert-danger" id="dangerBtn{{$i}}" style="display:none;font-size:12px;"></ul>
                                                            <div class="modal-body">
                                                                <form class="mb-5 dynamic-form" id=<?= 'form_id_' . $relatedDocumentIds[$i] ?> method="post" action="{{ route('doc.update_content_text') }}" enctype="multipart/form-data" name=<?= 'form_id_' . $relatedDocumentIds[$i] ?>>
                                                                    @csrf
                                                                    <div class="table-responsive">    
                                                                        <table class="table table-borderless dynamic_field">
                                                                            <tbody id="dynamic_field{{$i}}">
                                                                                <tr>
                                                                                    <input type="hidden" class="form-control col-sm-12" name="content_id" id="<?= 'content_id_' . $relatedDocumentIds[$i] ?>" value="<?= $relatedDocumentIds[$i] ?>" />
                                                                                    <input type="hidden" class="form-control" name="document_id" id="document_id_{{$i}}" value="{{ $documentContent->document_id }}"/>
                                                                                    <td colspan="3">
                                                                                        <label for="document_title" class="col-sm-12 col-form-label px-0">Section Title<span class="text-danger">*</span></label>
                                                                                        <div class="col-sm-6 px-0">
                                                                                            <input type="text" class="form-control" name="title" id="title_{{$i}}" value="{{ $documentContent->title }}">
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                @php $rows = 0; @endphp
                                                                                @foreach($contents as $con)    
                                                                                <tr class="dynamic-added">
                                                                                    <td>
                                                                                        <input type="hidden" class="form-control col-sm-12" name="text_id[]" id="text_id{{$i}}{{$rows}}" value="{{ isset($con->id) ? $con->id : '' }}" /> 
                                                                                        <label for="responsibility" class="col-sm-12 col-form-label px-0 pt-0">Responsibility(Position)</label>
                                                                                        <input type="text" class="form-control res_list col-sm-12" id="responsibility_{{$i}}{{$rows}}" name="responsibility[]" value="{{$con->responsibility}}"/>
                                                                                    </td>
                                                                                    <td>
                                                                                        <textarea class="form-control rich_list col-sm-12" id="rich_text_{{$i}}{{$rows}}" name="rich_text[]">{{$con->rich_text}}</textarea>
                                                                                    </td>
                                                                                    <td>
                                                                                        @if(!$loop->first)
                                                                                            <button type="button" id="{{ $con->id }}"
                                                                                                    class="btn btn-danger btn_remove" data-toggle="tooltip" title="remove">X</button>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <script>
                                                                                    createEditor("#rich_text_{{$i}}{{$rows}}");
                                                                                </script>
                                                                                @php $rows++; @endphp
                                                                                @endforeach 
                                                                                <tr>
                                                                                    <td colspan="3" class="text-right">
                                                                                        <button type="button" name="add" id="newadd_{{$i}}" class="btn btn-sm btn-light" data-toggle="tooltip" title="Add more">
                                                                                            <i class="fa fa-fw fa-plus"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>   
                                                                            </tbody>
                                                                        </table>    
                                                                        <div class="modal-footer">
                                                                            <input type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px" value="Cancel"/>
                                                                            <button type="submit" class="btn btn-primary btn-save" style="font-size:12px" onclick="saveFrom(<?= $i ?>,<?= $relatedDocumentIds[$i] ?>)">Save</button>
                                                                        </div> 
                                                                    </div> 
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    var j = 0;
                                                    $("#newadd_{{$i}}").on('click', function() {
                                                        ++j;   
                            
                                                        var newField = '<tr id="row'+j+'" class="dynamic-added">' +
                                                                '<input type="hidden" class="form-control col-sm-12" name="text_id[]" id="text_id'+j+'" value="null" />'+
                                                                '<td>' +
                                                                    '<label for="responsibility" class="col-sm-12 col-form-label px-0 pt-0">Responsibility(Position)</label>' +
                                                                    '<input type="text" class="form-control res_list" id="responsibility'+j+'" name="responsibility[]">' +
                                                                '</td>' +
                                                                '<td>' +
                                                                    '<textarea id="rich_text'+j+'" name="rich_text[]" class="form-control rich_list"></textarea>' +
                                                                '</td>' +
                                                                '<td>' +
                                                                    '<button type="button" name="remove" id="'+j+'" class="btn btn-danger btn_remove" data-toggle="tooltip" title="remove">X</button>' +
                                                                '</td>' +
                                                            '</tr>';
                                                    
                                                        $('#dynamic_field{{$i}}').append(newField);
                                                        $(document).ready(function() {
                                                                $('#rich_text'+j).summernote({
                                                                    height: 200,
                                                                    dialogsInBody: true
                                                                });
                                                            });
                                                    });

                                                </script>
                                            </td>
                                        </tr>
                                        <?php $i++; $n++;?>
                                        @endforeach
                                        <script>
                                            document.querySelectorAll('oembed[url]').forEach(element => {
                                                // Create the <a href="..." class="embedly-card"></a> element that Embedly uses
                                                // to discover the media.
                                                const anchor = document.createElement('a');

                                                anchor.setAttribute('href', element.getAttribute('url'));

                                                anchor.className = 'embedly-card';

                                                element.appendChild(anchor);

                                            });
                                        </script>
                                        
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-danger text-center" style="font-size:13px;">There are no contents added for this document</div>
                            @endif
                        </div>
                        @if(!$doc->is_deleted)
                        <div class="block block-rounded">
                            <ul class="nav nav-tabs nav-tabs-alt align-items-center comment-padding" data-toggle="tabs"
                                role="tablist" id="myTab">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#ecom-product-info" data-toggle="tab">
                                      <i class="si si-info fa-2x" style="font-size:12px;"></i>&nbsp;Info
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ecom-product-comments" data-toggle="tab">
                                     <i class="far fa-comment-dots" style="font-size:12px;"></i>
                                      Comments ({{count($document_comments)}})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ecom-product-reviews" data-toggle="tab">
                                     <i class="far fa-star" style="font-size:12px;"></i>
                                        Reviews ({{count($document_reviews)}})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ecom-product-reference" data-toggle="tab">
                                       <i class="fa fa-edit" style="font-size:12px;"></i>
                                        Reference
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ecom-product-definition" data-toggle="tab">
                                    <i class="far fa-file-alt"  style="font-size:13px;"></i>
                                        Definition
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#ecom-product-change_log" data-toggle="tab">
                                    <i class="fa fa-history" style="font-size:13px;"></i>
                                        Change Log
                                    </a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <div class="tab-pane pull-x active comment-padding" id="ecom-product-info"
                                    role="tabpanel">
                                    <table class="table table-striped table-borderless">
                                        <tbody>
                                            <tr>
                                                <td style="width: 20%;" class="font-size-sm">Document Name</td>
                                                <td class="font-size-sm">
                                                    <span>{{$docname->document_name}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-size-sm">Document Number</td>
                                                <td class="font-size-sm">
                                                    <span>{{$docname->document_number}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-size-sm">Revision #</td>
                                                <td class="font-size-sm">
                                                    <span>{{$docname->revision}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                @php
                                                   $prepared_by_emails = explode(',',$docname->prepared_by);
                                                @endphp
                                                <td class="font-size-sm">Prepared By</td>
                                                <td>
                                                    @foreach($prepared_by_emails as $prepared_by_email)
                                                    @php
                                                       $prepared = App\User::where('email', $prepared_by_email)->first();
                                                    @endphp
                                                    @if($prepared)
                                                    <img class="img-avatar img-avatar32"
                                                        src="{{ $prepared->profile_picture ? '/storage/profile/' . $prepared->profile_picture : asset('media/avatars/avatar1.jpg')}}"
                                                        alt="" title="{{ $prepared->name }}">
                                                    @else
                                                    <img class="img-avatar img-avatar32"
                                                        src="{{ asset('media/avatars/avatar1.jpg')}}"
                                                        alt="" title="User Deleted">&nbsp;&nbsp;
                                                    @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                @php
                                                $email = $docname->reviewed_by;
                                                $emails = explode(',', $email);
                                                @endphp
                                                <td class="font-size-sm">Reviewed By</td>
                                                <td>
                                                    @foreach($emails as $e)
                                                    @php
                                                    $reviewed = App\User::where('email', $e)->first();
                                                    @endphp
                                                    @if($reviewed)
                                                    <img class="img-avatar img-avatar32"
                                                        src="{{ $reviewed->profile_picture ? '/storage/profile/' . $reviewed->profile_picture : asset('media/avatars/avatar1.jpg')}}"
                                                        alt="" title="{{ $reviewed->name }}">&nbsp;&nbsp;
                                                    @else
                                                    <img class="img-avatar img-avatar32"
                                                        src="{{ asset('media/avatars/avatar1.jpg')}}"
                                                        alt="" title="User Deleted">&nbsp;&nbsp;
                                                    @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                @php
                                                $email = $docname->approved_by;
                                                $approved = App\User::where('email', $email)->first();
                                                @endphp

                                                <td class="font-size-sm">Approved By</td>
                                                <td>
                                                    @if($approved)
                                                    <img class="img-avatar img-avatar32"
                                                        src="{{ $approved->profile_picture ? '/storage/profile/' . $approved->profile_picture : asset('media/avatars/avatar1.jpg') }}"
                                                        alt="" title="{{ $approved->name }}">
                                                    @else
                                                    <img class="img-avatar img-avatar32"
                                                        src="{{ asset('media/avatars/avatar1.jpg')}}"
                                                        alt="" title="User deleted">&nbsp;&nbsp;
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-size-sm">Revision Date</td>
                                                <td class="font-size-sm">
                                                    <span>{{date('d-m-Y',strtotime($docname->date))}}</span> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END Info -->
                                <!-- Comments -->
                                <div class="tab-pane pull-x font-size-sm comment-padding" id="ecom-product-comments"
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
                                                @elseif($comment->user)
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

                                                <p class="my-1"><span style="font-size:13px;">{!! $comment->comment !!}</span></p>
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
                                                        <!-- <p class="my-1">{!! $reply->comment !!}</p> -->
                                                        <p class="my-1" style="font-size:13px;">{!! $reply->comment !!}</p>
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
                                    <form id='comment_form' method="post" action="{{ route('documentcomment.store') }}"
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
                                    <form id='reply_form' method="post" action="{{ route('documentcomment.store') }}"
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
                                @php
                                $doc_review = App\GovernanceHubDocumentReview::where('email',
                                Auth::user()->email)->where('document_id', $id)->first();
                                @endphp
                                <div class="modal" id="add_review" tabindex="-1" role="dialog"
                                    aria-labelledby="add_review" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">

                                            <div class="block block-rounded block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Review</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option"
                                                            data-dismiss="modal" aria-label="Close"
                                                            onclick="closeReviewModal()">
                                                            <i class="fa fa-fw fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="block-content font-13">
                                                    <div class="alert alert-success text-left d-none"
                                                        id="success_review">
                                                        Your review saved successfully!
                                                    </div>
                                                    <ul class="alert alert-danger text-left d-none" id="error_review">
                                                    </ul>
                                                </div>
                                                <div class="block-content font-size-sm">
                                                    <div class="row">
                                                        <div class="col mt-2">
                                                            <p>Click on the star to review</p>
                                                        </div>
                                                        <div class="col">
                                                            <span
                                                                class="mt-1 mb-3 d-flex justify-content-center cursor-pointer">
                                                                <i class="fa fa-star fa-2x {{ $doc_review && $doc_review->score >= 1 ? 'colored-star' : 'blank-star' }}"
                                                                    onclick="addFirstStar()" id="first_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $doc_review && $doc_review->score >= 2 ? 'colored-star' : 'blank-star' }}"
                                                                    onclick="addSecondStar()" id="second_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $doc_review && $doc_review->score >= 3 ? 'colored-star' : 'blank-star'}}"
                                                                    onclick="addThirdStar()" id="third_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $doc_review && $doc_review->score >= 4 ? 'colored-star' : 'blank-star'}}"
                                                                    onclick="addForthStar()" id="forth_star"></i>
                                                                <i class="fa fa-star fa-2x {{ $doc_review && $doc_review->score >= 5 ? 'colored-star' : 'blank-star'}}"
                                                                    onclick="addFifthStar()" id="fifth_star"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="block-content block-content-full text-right border-top">
                                                    <button type="button" class="btn btn-alt-primary mr-1"
                                                        data-dismiss="modal" onclick="closeReviewModal()">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="save_review">Ok</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Reviews -->
                                <div class="tab-pane pull-x font-size-sm comment-padding" id="ecom-product-reviews"
                                    role="tabpanel">

                                    <!-- Average Rating -->
                                    <div class="block block-rounded bg-body-light">
                                        <div class="block-content text-center">
                                            <p class="mb-2" id="stars">
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                                <i class="fa fa-star fa-2x blank-star"></i>
                                            </p>
                                            <p>
                                                <strong>{{ $average_score }}</strong>/5.0 out of <strong>5</strong>
                                                Ratings
                                            </p>
                                            <button type="button" class="btn btn-sm btn-primary mb-2"
                                                data-toggle="modal" data-target="#add_review">Add your review</button>
                                        </div>
                                    </div>
                                    <!-- END Average Rating -->

                                    <!-- Ratings -->
                                    @foreach($document_reviews as $review)
                                    @php
                                    $email = $review->email;
                                    $user = App\User::where('email', $email)->first();
                                    $score = $review->score;
                                    @endphp
                                    @if($user)
                                    <div class="media push">
                                        <a class="mr-3" href="javascript:void(0)">
                                            <img class="img-avatar img-avatar32"
                                                src="{{ $user->profile_picture ? '/storage/profile/' . $user->profile_picture : asset('media/avatars/avatar1.jpg') }}"
                                                alt="">
                                        </a>
                                        <div class="media-body">
                                            <span>
                                                <i
                                                    class="fa fa-star {{ $score >= 1 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i
                                                    class="fa fa-star {{ $score >= 2 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i
                                                    class="fa fa-star {{ $score >= 3 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i
                                                    class="fa fa-star {{ $score >= 4 ? 'colored-star' : 'blank-star' }}"></i>
                                                <i
                                                    class="fa fa-star {{$score >= 5 ? 'colored-star' : 'blank-star' }}"></i>
                                            </span>
                                            <a class="font-w600" href="javascript:void(0)">{{ $user?$user->name:'' }}</a>
                                            <span
                                                class="text-muted"><em>{{ Carbon\Carbon::createFromDate($review->created_at)->diffForHumans()}}</em></span>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    <!-- END Ratings -->
                                </div>
                                <!-- END Reviews -->

                                 <!-- Reference -->
                                <div class="tab-pane pull-x font-size-sm comment-padding" id="ecom-product-reference"
                                    role="tabpanel">                              
                                        <table class="table table-bordered" width="50%" align="center">
                                                <tr>
                                                    <td colspan="3" align="right">
                                                      <button type="button" class="btn btn-sm btn-primary mb-2"
                                                         data-toggle="modal" data-target="#addreferenceModal" onclick="passdocumentid('{{$id}}')">
                                                         Add Reference
                                                       </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="10%"><b>Sl No</b></td>
                                                    <td align="center"><b>Ref doc number</b></td>
                                                    <td align="center"><b>Ref Doc name</b></td>
                                                </tr>                                         
                                                @php
                                                  $no = 1;                       
                                                  $reference_datas = App\GovernanceHubDocumentReferenceDetail::where('document_id',$id)->get();                                                
                                                @endphp
                                                @if(count($reference_datas)>0)
                                                    @foreach($reference_datas as $reference_data)
                                                    <tr>
                                                        <td align="center" class="reference-content">{{$no}}</td>
                                                        <td align="center" class="reference-content">
                                                            {{$reference_data->reference_doc_number ? $reference_data->reference_doc_number:'--'}}
                                                        </td>
                                                        <td align="center" class="reference-content">
                                                            {{$reference_data->reference_doc_name ? $reference_data->reference_doc_name :'--'}}</td>
                                                        @php $no++;  @endphp
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td align="center" class="reference-content text-danger" colspan="3">
                                                            There are no references added
                                                        </td>
                                                    </tr>
                                                @endif
                                        </table>                                                                     
                                </div>
                                <!-- END Reference -->

                                 <!-- Definition -->
                                 <div class="tab-pane pull-x font-size-sm comment-padding" id="ecom-product-definition"
                                    role="tabpanel">                              
                                        <table class="table table-bordered" width="50%" align="center">
                                                <tr>
                                                    <td colspan="3" align="right">
                                                      <button type="button" class="btn btn-sm btn-primary mb-2"
                                                         data-toggle="modal" data-target="#adddefinitionModal" onclick="passdocumentdefid('{{$id}}')">
                                                         Add Definition
                                                       </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="10%"><b>Sl No</b></td>
                                                    <td align="center"><b>Term</b></td>
                                                    <td align="center"><b>Definition</b></td>
                                                </tr>                                         
                                                @php
                                                  $no = 1;
                                                  $definition_datas = App\GovernanceHubDocumentDefinitionDetail::where('document_id',$id)->get();
                                                @endphp
                                                @if(count($definition_datas)>0)
                                                    @foreach($definition_datas as $definition_data)
                                                    <tr>
                                                        <td align="center" class="reference-content">{{$no}}</td>
                                                        <td align="center" class="reference-content">
                                                            {{$definition_data->term ? $definition_data->term:'--'}}
                                                        </td>
                                                        <td align="center" class="reference-content">
                                                            {{$definition_data->definition ? $definition_data->definition :'--'}}</td>
                                                        @php $no++;  @endphp
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td align="center" class="reference-content text-danger" colspan="3">
                                                            There are no definitions added
                                                        </td>
                                                    </tr>
                                                @endif
                                        </table>                                                                     
                                </div>
                                <!-- END Definition -->

                                 <!-- Change Log -->
                                 <div class="tab-pane pull-x font-size-sm comment-padding" id="ecom-product-change_log"
                                    role="tabpanel">                              
                                        <table class="table table-bordered" width="50%" align="center">
                                                <tr>
                                                    <td align="center" width="8%"><b>Sl No</b></td>
                                                    <td align="center" width="10%"><b>Rev #</b></td>
                                                    <td align="center" width="15%"><b>Date</b></td>
                                                    <td align="left"><b>Changes Made</b></td>
                                                </tr>                                         
                                                @php
                                                  $no = 1;
                                                  $changelogs = App\GovernanceHubChangeRequestDetail::where('parent_governance_id',$id)
                                                                                                     ->where('change_requested_completed_status',true)
                                                                                                     ->get();                   
                                                @endphp
                                                @if(count($changelogs)>0)
                                                    @foreach($changelogs as $changelog) 
                                                      @php 
                                                        $rev_num = sprintf("%02d", $changelog->new_revision)
                                                      @endphp                          
                                                        <tr>
                                                            <td align="center" class="reference-content">{{$no}}</td>
                                                            <td align="center" class="reference-content">{{$rev_num}}</td>
                                                            <td align="center" class="reference-content">
                                                             {{date('d-m-Y',strtotime($changelog->change_request_completed_date))}}
                                                            </td>
                                                            <td align="left" class="reference-content">
                                                               {{nl2br(ucfirst($changelog->changes_made))}}
                                                            </td>
                                                            @php $no++;  @endphp
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td align="center" class="reference-content text-danger" colspan="4">
                                                            No Change Requested for this document
                                                        </td>
                                                    </tr>
                                                @endif                                             
                                        </table>                                                                     
                                </div>
                                <!-- END Change Log -->
                            </div>
                        </div>
                        @endif
                        <!-- END Extra Info Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.document.governance_hub_headers')

    <!-- add reference starts-->
<div class="modal fade" id="addreferenceModal" tabindex="-1" role="dialog" aria-labelledby="addreferenceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addreferenceModalLabel">Add Reference</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add_reference_ajax" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="referenceaddedsuccess" style="display:none">
                           Reference submitted successfully
                        </div>

                        <ul class="alert alert-danger" id="referenceaddeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul> 

                        <ul class="alert alert-danger" id="referencedangernotadded" style="display:none"></ul> 
                        
                        <ul class="alert alert-danger" id="referencealreadyexists" style="font-size:12px;display:none">
                             This Reference document number already exists for this document
                        </ul> 

                        <div class="form-group">
                            <label for="ref_doc_number" class="col-sm-12 col-form-label" style="font-size:12px;"> Reference Document Number
                            </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size:12px;" name="ref_doc_number"
                                    id="ref_doc_number" placeholder="Enter Reference Document Number">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ref_doc_name" class="col-sm-12 col-form-label" style="font-size:12px;">Reference Document Name
                            </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"  style="font-size:12px;" name="ref_doc_name"
                                    id="ref_doc_name" placeholder="Enter Reference Document Name">
                            </div>
                        </div>  
                    </div>
                    <input type="hidden" name="main_document_id" id="main_document_id"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add reference ends-->

      <!-- add definition starts-->
   <div class="modal fade" id="adddefinitionModal" tabindex="-1" role="dialog" aria-labelledby="adddefinitionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adddefinitionModalLabel">Add Definition</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add_definition_ajax" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-success" id="definitionaddedsuccess" style="display:none">
                           Definition submitted successfully
                        </div>

                        <ul class="alert alert-danger" id="definitionaddeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul> 

                        <ul class="alert alert-danger" id="definitiondangernotadded" style="display:none"></ul> 
                        
                        <ul class="alert alert-danger" id="definitionalreadyexists" style="font-size:12px;display:none">
                             This Definition term already exists for this document
                        </ul> 

                        <div class="form-group">
                            <label for="def_term" class="col-sm-12 col-form-label" style="font-size:12px;"> Term
                            </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" style="font-size:12px;" name="def_term"
                                    id="def_term" placeholder="Enter term">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="definition" class="col-sm-12 col-form-label" style="font-size:12px;">Definition
                            </label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control"  style="font-size:12px;" name="definition"
                                    id="definition" placeholder="Enter definition">
                            </div>
                        </div>  
                    </div>
                    <input type="hidden" name="main_document_def_id" id="main_document_def_id"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add definition ends-->

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
                    url: "{{ route('doc.update_content_text') }}",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data:$("#form_id_" + y).serialize(),  
                    success: function(response) {
                        $('.edit-content-modal').animate({ scrollTop: 0 }, 'slow');
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
        let startTime;
        let isDocumentActive = false;
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        let hasUpdatedViews = false;

        function startTimer() {
            startTime = Date.now();
        }
        const documentId = id;
        startTime = Date.now();
        // Function to update the timer
        function updateTimer() {
            if (!hasUpdatedViews && isDocumentActive) {
                const currentTime = Date.now();
                const elapsedSeconds = Math.floor((currentTime - startTime) / 1000);
                let temp = 0;
                if (elapsedSeconds >= 60 && temp == 0) { // If at least 1 minute has passed
                    // Send AJAX request to update the views count on the server
                    $.ajax({
                        url: `/user/doc/update-views/${documentId}`,
                        type: 'POST',
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        processData: false,
                        contentType: false,
                        data: {
                            viewingTime: elapsedSeconds
                        },
                        success: function(response) {
                            // console.log('Views count updated successfully.');
                            hasUpdatedViews = true;
                            temp = temp + 1;
                        },
                        error: function(error) {
                            // console.error('Error updating views count:', error);
                        }
                    });
                }
            }
        }
        // Event listeners to track user activity
        window.addEventListener('mousemove', () => {
            isDocumentActive = true;
            updateTimer();
        });

        window.addEventListener('keydown', () => {
            isDocumentActive = true;
            updateTimer();
        });

        // When the user leaves the page
        window.addEventListener('beforeunload', () => {
            updateTimer();
        });
        startTimer();
    </script>
    <script>
        $(document).on('click', '[data-title]', function(e) {
            e.preventDefault();
            const content_id = $(this).data('title');
            if (confirm('Are you sure you want to delete this record?')) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('doc.delete_content') }}",
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
        // let reply_comment_id = null;
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
            let store_comment_url = "{{ route('documentcomment.store') }}";
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
                    success: function() {
                        $('#comment').val('');
                        window.location.reload();
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
            let store_comment_url = "{{ route('documentcomment.store') }}";
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
            let delete_comment_url = "{{ route('documentcomment.destroy', ':id') }}";
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
            let delete_reply_url = "{{ route('documentcomment.delete_reply', ':id') }}";
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

        function addFirstStar() {
            $('#first_star').removeClass('blank-star');
            $('#first_star').addClass('colored-star');

            $('#second_star').removeClass('colored-star');
            $('#second_star').addClass('blank-star');

            $('#third_star').removeClass('colored-star');
            $('#third_star').addClass('blank-star');

            $('#forth_star').removeClass('colored-star');
            $('#forth_star').addClass('blank-star');

            $('#fifth_star').removeClass('colored-star');
            $('#fifth_star').addClass('blank-star');
        }

        function addSecondStar() {
            $('#first_star').removeClass('blank-star');
            $('#first_star').addClass('colored-star');

            $('#second_star').removeClass('blank-star');
            $('#second_star').addClass('colored-star');

            $('#third_star').removeClass('colored-star');
            $('#third_star').addClass('blank-star');

            $('#forth_star').removeClass('colored-star');
            $('#forth_star').addClass('blank-star');

            $('#fifth_star').removeClass('colored-star');
            $('#fifth_star').addClass('blank-star');
        }

        function addThirdStar() {
            $('#first_star').removeClass('blank-star');
            $('#first_star').addClass('colored-star');

            $('#second_star').removeClass('blank-star');
            $('#second_star').addClass('colored-star');

            $('#third_star').removeClass('blank-star');
            $('#third_star').addClass('colored-star');

            $('#forth_star').removeClass('colored-star');
            $('#forth_star').addClass('blank-star');

            $('#fifth_star').removeClass('colored-star');
            $('#fifth_star').addClass('blank-star');
        }

        function addForthStar() {
            $('#first_star').removeClass('blank-star');
            $('#first_star').addClass('colored-star');

            $('#second_star').removeClass('blank-star');
            $('#second_star').addClass('colored-star');

            $('#third_star').removeClass('blank-star');
            $('#third_star').addClass('colored-star');

            $('#forth_star').removeClass('blank-star');
            $('#forth_star').addClass('colored-star');

            $('#fifth_star').removeClass('colored-star');
            $('#fifth_star').addClass('blank-star');
        }

        function addFifthStar() {
            $('#first_star').removeClass('blank-star');
            $('#first_star').addClass('colored-star');

            $('#second_star').removeClass('blank-star');
            $('#second_star').addClass('colored-star');

            $('#third_star').removeClass('blank-star');
            $('#third_star').addClass('colored-star');

            $('#forth_star').removeClass('blank-star');
            $('#forth_star').addClass('colored-star');

            $('#fifth_star').removeClass('blank-star');
            $('#fifth_star').addClass('colored-star');
        }
        let success_review_selector = $('#success_review');
        let error_review_selector = $('#error_review');
        $('#save_review').on('click', function(e) {
            e.preventDefault();

            let review_url = "{{ route('doc.review') }}";
            let id = "{{ $id }}";
            let score = 0;
            if ($('#first_star').hasClass('colored-star') &&
                $('#second_star').hasClass('blank-star') &&
                $('#third_star').hasClass('blank-star') &&
                $('#forth_star').hasClass('blank-star') &&
                $('#fifth_star').hasClass('blank-star')) {
                score = 1;
            } else if ($('#second_star').hasClass('colored-star') &&
                $('#first_star').hasClass('colored-star') &&
                $('#third_star').hasClass('blank-star') &&
                $('#forth_star').hasClass('blank-star') &&
                $('#fifth_star').hasClass('blank-star')) {
                score = 2;
            } else if ($('#third_star').hasClass('colored-star') &&
                $('#first_star').hasClass('colored-star') &&
                $('#second_star').hasClass('colored-star') &&
                $('#forth_star').hasClass('blank-star') &&
                $('#fifth_star').hasClass('blank-star')) {
                score = 3;
            } else if ($('#forth_star').hasClass('colored-star') &&
                $('#first_star').hasClass('colored-star') &&
                $('#second_star').hasClass('colored-star') &&
                $('#third_star').hasClass('colored-star') &&
                $('#fifth_star').hasClass('blank-star')) {
                score = 4;
            } else if ($('#fifth_star').hasClass('colored-star') &&
                $('#first_star').hasClass('colored-star') &&
                $('#second_star').hasClass('colored-star') &&
                $('#third_star').hasClass('colored-star') &&
                $('#forth_star').hasClass('colored-star')) {
                score = 5;
            }

            $.ajax({
                url: review_url,
                type: 'POST',
                data: {
                    'document_id': id,
                    'score': score,
                    '_token': CSRF_TOKEN
                },
                headers: {
                    'accept': 'application/json'
                },
                success: function() {
                    success_review_selector.removeClass('d-none');
                    success_review_selector.addClass('d-block');
                    error_review_selector.removeClass('d-block');
                    error_review_selector.addClass('d-none');

                    setTimeout(function() {
                        location.reload(true);
                    }, 3000);

                },
                error: function(err) {
                    success_review_selector.addClass('d-none');
                    success_review_selector.removeClass('d-block');

                    error_review_selector.addClass('d-block');
                    error_review_selector.removeClass('d-none');

                    if (err.status === 500) {
                        error_review_selector.empty().append(
                            `<li>Some server errors happened, we are trying to fix them soon.</li>`)
                    } else if (err.status === 422) {
                        for (let i in err.responseJSON.errors) {
                            error_review_selector.empty().append(
                                `<li>${err.responseJSON.errors[i]}</li>`)
                        }
                    }
                }
            });

        })

        function closeReviewModal() {
            success_review_selector.addClass('d-none');
            success_review_selector.removeClass('d-block');
            error_review_selector.addClass('d-none');
            error_review_selector.removeClass('d-block');

            $('#first_star').removeClass('colored-star');
            $('#second_star').removeClass('colored-star');
            $('#third_star').removeClass('colored-star');
            $('#forth_star').removeClass('colored-star');
            $('#fifth_star').removeClass('colored-star');
        }
        let avarage_score = "{{ $average_score }}";

        document.getElementById("stars").innerHTML = getStars(avarage_score);

        function getStars(rating) {

            // Round to nearest half
            rating = Math.round(rating * 2) / 2;
            let output = [];

            // Append all the filled whole stars
            for (var i = rating; i >= 1; i--)
                output.push('<i class="fa fa-2x fa-star colored-star" aria-hidden="true"></i>&nbsp;');

            // If there is a half a star, append it
            if (i == .5) {
                // output.push('<i class="fa fa-2x fa-star-half" aria-hidden="true"></i>&nbsp;');
                output.push(`<span class="fa-stack mb-12">
                    <i class="fa fa-fw fa-2x fa-star-half fa-stack-1x"></i>
                    <i class="fa fa-fw fa-2x fa-star fa-stack-1x"></i>
                </span>`);

            }


            // Fill the empty stars
            for (let i = (5 - rating); i >= 1; i--)
                output.push('<i class="fa fa-2x fa-star blank-star" aria-hidden="true"></i>&nbsp;');

            return output.join('');

        }
    </script>
    <script>
        function validateimg(){
            message2 = 'Are you sure? Press OK to continue.';
            return confirm(message2);
        }
    </script>
    <script>
        function passdocumentid(id){
            document.getElementById('main_document_id').value=id;
        }

        function passdocumentdefid(id){
            document.getElementById('main_document_def_id').value=id;
        }

    window.onload = function() {
      /**-------------add reference starts---------------- */
        $(document).ready(function() {
                $('#add_reference_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                            type: 'POST',
                            url: '/user/doc/governancehubdocumentreference',
                            data: formData
                            ,success: function(response) {
                                if(response.status == 200)
                                {
                                    $('#referenceaddeddanger').hide();
                                    $('#referencedangernotadded').hide();
                                    $('#referencealreadyexists').hide();
                                    $('#referenceaddedsuccess').css('display', 'block');
                                    window.location.reload();
                                }
                                else if(response.status == 403)
                                {
                                    $('#referenceaddedsuccess').hide();
                                    $('#referencedangernotadded').hide();
                                    $('#referencealreadyexists').css('display', 'block');
                                }
                            }
                        ,error: function(err) { 
                            if(err.status === 500) {
                                    $('#referencedangernotadded').hide();
                                    $('#referenceaddeddanger').css('display', 'block');
                                }
                                else if (err.status === 422) {
                                    var error_text='';                               
                                    $.each(err.responseJSON.errors, function(i) {
                                        $.each(err.responseJSON.errors[i], function(key, val) {
                                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                                        });
                                }); 
                                    $('#referencedangernotadded').html(error_text);
                                    $('#referenceaddeddanger').hide();
                                    $('#referencedangernotadded').css('display', 'block');        
                                }
                        }              
                    });              
               });
            });
         /**-------------End add reference ---------------- */ 

         /**-------------add definition starts---------------- */
        $(document).ready(function() {
                $('#add_definition_ajax').submit(function(event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                            type: 'POST',
                            url: '/user/doc/governancehubdocumentdefinition',
                            data: formData
                            ,success: function(response) {
                                if(response.status == 200)
                                {
                                    $('#definitionaddeddanger').hide();
                                    $('#definitiondangernotadded').hide();
                                    $('#definitionalreadyexists').hide();
                                    $('#definitionaddedsuccess').css('display', 'block');
                                    window.location.reload();
                                }
                                else if(response.status == 403)
                                {
                                    $('#definitionaddedsuccess').hide();
                                    $('#definitiondangernotadded').hide();
                                    $('#definitionalreadyexists').css('display', 'block');
                                }
                            }
                        ,error: function(err) { 
                            if(err.status === 500) {
                                    $('#definitiondangernotadded').hide();
                                    $('#definitionaddeddanger').css('display', 'block');
                                }
                                else if (err.status === 422) {
                                    var error_text='';                               
                                    $.each(err.responseJSON.errors, function(i) {
                                        $.each(err.responseJSON.errors[i], function(key, val) {
                                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                                        });
                                }); 
                                    $('#definitiondangernotadded').html(error_text);
                                    $('#definitionaddeddanger').hide();
                                    $('#referencedangernotadded').css('display', 'block');        
                                }
                        }                
                    });              
               });
            });
         /**-------------End add definition ---------------- */ 

    }
        
    </script>
    <script>
        $('body').on('click', '.btn_remove', function() {
            const parentElement = $(this).closest('.dynamic-added');
            const responsibility = parentElement.find('.res_list').val();
            const richText = parentElement.find('.rich_list').val();
            if(responsibility !== '' || richText !== '') {
                if (confirm('Are you sure you want to remove this content?')) {
                    parentElement.remove();
                }
            } else {
                parentElement.remove();
            }
        });
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
</body>

</html>
@endsection