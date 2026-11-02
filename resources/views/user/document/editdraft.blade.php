@extends('layouts.backend')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>

    </ul>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
    <!-- <script async charset="utf-8" src="//cdn.embedly.com/widgets/platform.js" crossorigin="anonymous"></script> -->
    <script async charset="utf-8" src="//cdn.embedly.com/widgets/platform.js"></script>
    <style>
        /* .rich_text_container img {
            width: 100% !important;
        } */

        .rich_text_container table tr td {
            border: 1px solid #6f7171 !important;
        }
        .block-content .block, .block-content .items-push > div, .block-content .push, .block-content p {
	        margin-bottom: 0;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>
    <script src="https://ckeditor.com/apps/ckfinder/3.5.0/ckfinder.js"></script>
    <!-- Page JS Helpers (CKEditor 5 plugins) -->
    <script>
        function createCKEditor(containerId) {
            ClassicEditor
                .create(document.querySelector(containerId), {
                    toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'ckfinder', 'mediaEmbed', 'blockQuote', 'insertTable', 'undo', 'redo'],
                    ckfinder: {
                        uploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                    }
                });
        }
        function createEditor(containerId) {
        $(document).ready(function() {
            $(containerId).summernote({
                height: 400,
                dialogsInBody: true
            });
        });
    }
    </script>
</head>

<body>
    @php
    $data=$data1=[];
    @endphp
    <!-- Page Content -->
    <div class="content" style="width:97%;">
        @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
        @endif
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
                        <button type="button" class=" btn-sm btn btn-alt-primary dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-file-export fa-1x" style="color: #3366CC;"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
                            <li>
                                <!--change route as below-->
                                <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                                    @csrf
                                    <button onclick="return fnCovertTable('pdf')"
                                        style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                                    <!--change hidden input type id as below-->
                                    <input type="hidden" id="tvalue3" name="tvalue3" />
                                </form>
                            </li>
                            <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
                        </ul>
                        &nbsp;&nbsp;
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    </div>
                    
                </div>
            </div>
            <div class="block-content">
                @if(count($documentContents)>0)
                
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        <tbody>
                            @php $i = 0; $n = 1; @endphp
                            @foreach ($documentContents as $documentContent)
                            @php 
                                $contents = \App\GovernanceHubDraftContentRichText::where('content_id',$relatedDocumentIds[$i])->get();
                            @endphp
                            <tr class="table-active">
                                <td class="text-center" style="width:50px;border-right: 1px solid #D3D3D3;">
                                    <span style="color: #3366CC;font-size:13px;" class="font-w600"><?php printf("%.1f", $n); ?></span>
                                </td>
                                <td class="text-left" style="width:230px;border-right: 1px solid #D3D3D3;">
                                    <span  class="font-w600">Responsibility</span>
                                </td>
                                <td class="text-left">
                                    <span  class="font-w600">{{ucfirst($documentContent->title)}}</span>
                                </td>
                                
                                <td class="text-right" style="width:50px;">
                                    <a href="#" class="font-w600" style="font-size:10px;cursor:pointer;" data-id="{{$i}}" data-toggle="modal" data-target="#edit_content_{{$i}}" title="Edit content"><i class="fa fa-2x fa-edit" style="color:grey;"></i></a>
                                </td>
                                <td class="text-right" style="width:50px;">
                                    <a href="#" class="font-w600" style="font-size:10px;cursor:pointer;" title="Delete" data-title=<?= $relatedDocumentIds[$i] ?>><i class="fas fa-2x fa-trash" style="color:red;"></i></a>
                                </td>    
                            </tr>

                                @php $m = 1; @endphp
                                @foreach($contents as $con)
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
                                            <span>{!! $con->rich_text!!}</span>
                                            @else
                                            <span>-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                   
                            <tr>
                                <td style="padding:0;" colspan="5">
                                    <div class="modal fade" id="edit_content_{{$i}}" tabindex="-1" role="dialog" aria-labelledby="edit_contentLabel{{$i}}" aria-hidden="true">
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
                                                                            <label for="document_title" class="col-sm-12 col-form-label">Section Title<span class="text-danger">*</span></label> 
                                                                            <div class="col-sm-6">
                                                                                <input type="text" class="form-control" name="title" id="title_{{$i}}" value="{{ $documentContent->title }}">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @php $rows = 0; @endphp
                                                                    @foreach($contents as $con)    
                                                                    <tr>    
                                                                        <td>
                                                                            <input type="hidden" class="form-control col-sm-12" name="text_id[]" id="text_id{{$i}}{{$rows}}" value="{{ isset($con->id) ? $con->id : '' }}" /> 
                                                                            <label for="responsibility" class="col-sm-12 col-form-label">Responsibility(Position)<span class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control res_list col-sm-12" id="responsibility_{{$i}}{{$rows}}" name="responsibility[]" value="{{$con->responsibility}}" required=""/>
                                                                        </td>
                                                                        <td>
                                                                            <textarea class="form-control rich_list col-sm-12" id="rich_text_{{$i}}{{$rows}}" name="rich_text[]">{{$con->rich_text}}</textarea>
                                                                        </td>
                                                                        <td></td>                                 
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
                                                        '<label for="responsibility" class="col-sm-12 col-form-label">Responsibility(Position)<span class="text-danger">*</span></label>' +
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

                                        $(document).on('click', '.btn_remove', function() {
                                            $(this).parents('.dynamic-added').remove();
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
                <div class="row" style="padding-bottom:10px;">
                    <div class="col-sm-12 text-center text-danger">
                        <p style="font-size:13px;">There are no contents inside the draft.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END Page Content -->
    <!-- Page JS Plugins -->
    <script>
        function saveFrom(x, y) {
            console.log(x);
            console.log(y);
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
                    url: "{{ route('doc.edit_draft_content_text') }}",
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content'),
                        'accept': 'application/json'
                    },
                    type: 'POST',
                    data: $("#form_id_" + y).serialize(),
                    success: function(response) {
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
                            error_selector.empty().append(`<li>Some server errors happened, we are trying to fix them soon.</li>`)
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
                        alert('Record deleted successfully');
                        if (response) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
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
    <div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
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

                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('doc.register')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-1" href="{{ route('doc.history')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{ route('doc.explorer')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-2" href="{{ route('doc.governancehub')}}">
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
                                <a class="block block-rounded block-link-shadow bg-body mb-0" href="{{ route('doc.analysis')}}">
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
</body>

</html>
@endsection