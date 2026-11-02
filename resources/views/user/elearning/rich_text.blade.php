@extends('layouts.backend')
@section('content')
<!doctype html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script async charset="utf-8" src="//cdn.embedly.com/widgets/platform.js" crossorigin="anonymous"></script>
    <style>
        #rich_text_container img {
            width: 100% !important;
        }

    </style>

</head>

<body>
    <div class="modal fade" id="edit_content" tabindex="-1" role="dialog" aria-labelledby="edit_contentLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_contentLabel">Edit Content
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="data" method="post" action="{{ route('elearning.update_conent_rich_text') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="alert alert-success" id="successBtn" style="display:none;font-size:12px;">
                            Content edited successfully!
                        </div>
                        <ul class="alert alert-danger" id="dangerBtn" style="display:none;font-size:12px;"></ul>
                        <input type="hidden" name="content_id" id="content_id" value="{{ $course_heading_content->id }}" />
                        <div class="form-group" id="rich_text_div">
                            <div class="col-sm-12">
                                <textarea class="js-summernote" id="rich_text" name="rich_text">{{ $course_heading_content->rich_text }}</textarea>
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px">Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" style="font-size:12px">Save</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Horizontal -->
    <div id="page-container" class="/*sidebar-o*/ sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        <div class="content" style="">
            <div class="block block-rounded">
                <div class="alert alert-success d-none" id="successReadingBtn" style="font-size:13px;width:100%">
                    Content Reading completed successfully!
                </div>
                <div class="block-header">
                    <h3 class="block-title">{{ $course_heading_content->name }}</h3>
                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                        <ol class="breadcrumb breadcrumb-alt">

                            <li class="/*breadcrumb-item*/" aria-current="page">
                                <a class="btn btn-warning text-white" data-toggle="modal" data-target="#edit_content">Edit Content</a>
                            </li>
                            <li class="/*breadcrumb-item*/ ml-2" aria-current="page">
                                <a class="btn btn-dark text-white" href="{{ route('elearning.course', ['id' => $course_id])}}">Back to topics</a>
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-lg-10" id="rich_text_container">
                            {!! $course_heading_content->rich_text !!}
                        </div>
                    </div>
                </div>

                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-lg-10 text-center">
                            <button class="btn btn-alt-secondary" onclick="markAsCompleted('{{$course_heading_content->id}}')">Mark as completed</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('oembed[url]').forEach(element => {
            const anchor = document.createElement('a');

            anchor.setAttribute('href', element.getAttribute('url'));
            anchor.className = 'embedly-card';

            element.appendChild(anchor);
        });
        document.querySelectorAll('div[data-oembed-url]').forEach(element => {
            // Discard the static media preview from the database (empty the <div data-oembed-url="...">).
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }

            // Create the <a href="..." class="embedly-card"></a> element that Embedly uses
            // to discover the media.
            const anchor = document.createElement('a');

            anchor.setAttribute('href', element.dataset.oembedUrl);
            anchor.className = 'embedly-card';

            element.appendChild(anchor);
        });

    </script>

</body>

</html>
@endsection
@section('js_after')
<!-- Page JS Plugins -->
{{-- <script src="{{ asset('js/plugins/ckeditor5-classic/build/ckeditor.js') }}"></script>

<!-- Page JS Helpers (CKEditor 5 plugins) -->
<script>
    jQuery(function() {
        One.helpers(['ckeditor5']);
    });

</script>

<script src="https://ckeditor.com/apps/ckfinder/3.5.0/ckfinder.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#rich_text'), {

            toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'ckfinder', 'mediaEmbed', 'blockQuote', 'insertTable', 'undo', 'redo']
            , ckfinder: {
                uploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
            }



        })

</script> --}}

<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    let route = "{{ route('elearning.update_conent_rich_text') }}";

    let error_selector = $('#dangerBtn');
    let success_selector = $('#successBtn');

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


    $("form#data").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: route
            , headers: {
               'accept': 'application/json'
            }
            , type: 'POST'
            , data: {
                'content_id': $('#content_id').val()
                , 'rich_text': $('#rich_text').val()
                , _token: CSRF_TOKEN
            }
            , success: function(response) {

                if (response.error != null) {
                    showError();
                    error_selector.empty().append(`<li>${response.error}</li>`)
                } else {
                    showSuccess();

                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }
            }
            , error: function(err) {
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

    function markAsCompleted(id) {
        let route = "{{ route('elearning.mark_reading_as_completed', ':id')}}";
        route = route.replace(':id', id);
        $.ajax({
            url: route
            , type: 'GET'
            , success: function(response) {
                $('#successReadingBtn').removeClass('d-none')
                $('#successReadingBtn').addClass('d-inline-block')

                // setTimeout(function() {
                //     window.location.reload();
                // }, 2000);
            }
            , error: function(err) {
                console.log(err);
            }

        });

    }

</script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#rich_text').summernote({
            height: 200
        , });

    });

</script>


@endsection
