@extends('layouts.multiselect')
@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">

            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Governancehub</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Add Content</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="{{ route('doc.governancehub')}}">Back</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>

<body>
    <!-- Content -->
    <div class="content">
        @if(count($errors) >0 )
        @foreach($errors->all() as $error)
        <div class='alert alert-warning'>
            {{ $error }}
        </div>
        @endforeach
        @endif
        @if(Session::has('msg'))
        <div class="alert alert-primary">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-warning">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('error')!!}
        </div>
        @endif
        <div class="alert alert-danger" id="full_content_empty" style="display:none">
          <a class="close" data-dismiss="alert">×</a>
            Title field should not be empty<br/>
            Rich text field should not be empty
        </div> 
        <div class="alert alert-danger" id="full_title_only_empty" style="display:none">
          <a class="close" data-dismiss="alert">×</a>
            Title field should not be empty
        </div>     
        <div class="alert alert-danger" id="full_content_only_empty" style="display:none">
          <a class="close" data-dismiss="alert">×</a>
            Rich text field should not be empty
        </div>         
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" action="{{ route('doc.insert_content') }}" enctype="multipart/form-data"
                            method="POST" files="true">
                            {{csrf_field()}}
                            <input type="hidden" value="{{ $document->id }}" name="document_id" id="document_id" />
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-12 col-form-label" style="font-size:13px;">Procedure
                                        Title</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" style="font-size:13px;"
                                            value="{{ $document->document_name }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="document_type" class="col-sm-12 col-form-label"
                                        style="font-size:13px;">Document Type <span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control"
                                            style="font-size:13px;" required value="{{ $document->doctype ? $document->doctype->typename : ucwords($document->type) }}"
                                            readonly>
                                        <input type="hidden" id="type" name="type" value="{{ $document->doctype ? $document->doctype->id : 0 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6" id="topic">
                                    <label for="document_title" class="col-sm-12 col-form-label"
                                        style="font-size:13px;">Title<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="title" name="title"
                                            style="font-size:13px;">
                                    </div>
                                </div>
                            </div>
                            <div id="newDynamicRow">
                            <div class="row" >
                                <div class="form-group col-sm-3">
                                    <label for="responsibility" class="col-sm-12 col-form-label"
                                        style="font-size:13px;">Responsibility(Position)
                                    <div class="col-sm-12 px-0">
                                        <input type="text" class="form-control" id="responsibility"
                                            name="responsibility[]" style="font-size:13px;">
                                    </div>
                                </div>
                                <div class="form-group col-sm-8">
                                    <label for="rich_text" class="col-sm-12 col-form-label" style="font-size:13px;">Rich
                                        Text field<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <textarea id="rich_text" name="rich_text[]"></textarea>
                                    </div>
                                </div>
                                <div class="form-group col-sm-1" style="margin-top:auto;">
                                    <button type="button" name="add" id="newadd" class="btn btn-sm btn-light"
                                        data-toggle="tooltip" title="Add more">
                                        <i class="fa fa-fw fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-11 text-right">
                                    <button type="reset" class="btn btn-danger"
                                        style="font-size:13px;margin-right:12px;">Cancel</button>
                                    <button type="submit" class="btn btn-primary"
                                        style="font-size:13px;margin-right:12px;" onclick="return checkvalidate();">Save</button>
                                </div>
                            </div>
                        </form>
                        <!-- END Form Horizontal - Default Style -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END Horizontal -->
    </div>
    <!-- END Content -->
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
    @endsection
    @section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="{{ asset('js/document/editor.js') }}"></script>
    <script>
        $(document).ready(function() {
            editorInit('#rich_text');
        });
    </script>
    <script type="text/javascript">
        function checkvalidate(){
            var content = document.getElementById('rich_text').value;
            var titledata = document.getElementById('title').value;

            if(titledata=="" && content==""){
            document.getElementById('full_title_only_empty').style.display="none";
            document.getElementById('full_content_only_empty').style.display="none";
            document.getElementById('full_content_empty').style.display="";
            return false;
        }
        else if(titledata==""){
            document.getElementById('full_content_empty').style.display="none";
            document.getElementById('full_content_only_empty').style.display="none";
            document.getElementById('full_title_only_empty').style.display="";
            return false;
        } 
        else if(content==""){
            document.getElementById('full_content_empty').style.display="none";
            document.getElementById('full_title_only_empty').style.display="none";
            document.getElementById('full_content_only_empty').style.display="";
            return false;
        } 
    }
    </script>
    <script>
       var i = 0;
        $("#newadd").on('click', function() {
            ++i;                 
            $("#newDynamicRow").append(
                '<div class="row dynamic-row">' +
                    '<div class="form-group col-sm-3">' +
                        '<label for="responsibility" class="col-sm-12 col-form-label" style="font-size:13px;">Responsibility(Position)' +
                        '<div class="col-sm-12 px-0">' +
                            '<input type="text" class="form-control" id="responsibility'+i+'" name="responsibility[]" style="font-size:13px;">' +
                        '</div>' +
                    '</div>' +
                    '<div class="form-group col-sm-8">' +
                        '<label for="rich_text" class="col-sm-12 col-form-label" style="font-size:13px;">Rich Text Field' +
                        '<div class="col-sm-12">' +
                            '<textarea id="rich_text'+i+'" name="rich_text[]"></textarea>' +
                        '</div>' +
                    '</div>' +
                    '<div class="form-group col-sm-1" style="margin-top:auto;">' +
                        '<button type="button" name="remove" class="btn btn-danger remove-div" data-toggle="tooltip" title="remove">x</button>' +
                    '</div>' +
                '</div>'
            );
            $(document).ready(function() {
                    $('#rich_text'+i).summernote({
                        height: 200,
                        dialogsInBody: true
                    });
                });
        });

        $(document).on('click', '.remove-div', function() {
            $(this).parents('.dynamic-row').remove();
        });

    </script>
</body>

</html>
@endsection